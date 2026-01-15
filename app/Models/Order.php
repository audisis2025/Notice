<?php

/**
 * Nombre de la clase           : Order
 * Descripción de la clase      : Modelo con Chillerlan - Opciones simplificadas
 * Versión                      : 7.3 Chillerlan Simplified
 * Fecha de mantenimiento       : 15/01/2026
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

// Chillerlan QR Code
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'business_id',
        'user_id',
        'description',
        'amount',
        'status',
        'qr_code',
        'qr_delivery_code',
        'association_token',
        'delivery_token',
        'paid_at',
        'ready_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
        'associated_at',
        'chat_enabled',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'ready_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'associated_at' => 'datetime',
        'chat_enabled' => 'boolean',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reminders()
    {
        return $this->hasMany(OrderReminder::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isReady(): bool
    {
        return $this->status === 'ready';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isAssociated(): bool
    {
        return !is_null($this->user_id);
    }

    public function isDelayedDelivery(): bool
    {
        if (!$this->ready_at) {
            return false;
        }

        $delayThreshold = config('orders.delay_threshold_minutes', 30);
        return $this->ready_at->addMinutes($delayThreshold)->isPast();
    }

    public static function createOrder(Business $business, array $data): self
    {
        $order = self::create([
            'order_number' => $data['order_number'],
            'business_id' => $business->id,
            'description' => $data['description'] ?? null,
            'amount' => $data['amount'] ?? null,
            'status' => 'pending',
        ]);

        // Generar token único
        $order->association_token = Str::random(32);

        // ✅ USAR CONFIG APP_URL (ngrok) EN LUGAR DE route()
        $baseUrl = rtrim(config('app.url'), '/');
        $qrData = $baseUrl . '/orders/associate/' . $order->association_token;

        // Generar QR con la URL completa
        $order->qr_code = $order->generateQrCode($qrData, "order-{$order->id}-association.png");
        $order->save();

        return $order;
    }

    public function associateToUser(User $user, string $token): bool
    {
        if ($this->association_token !== $token) {
            throw new \Exception('Token de asociación inválido.');
        }

        if ($this->user_id) {
            throw new \Exception('La orden ya está asociada a un usuario.');
        }

        return $this->update([
            'user_id' => $user->id,
            'associated_at' => now(),
        ]);
    }

    public function changeStatus(string $newStatus): bool
    {
        $validStatuses = ['pending', 'paid', 'ready', 'delivered', 'cancelled'];

        if (!in_array($newStatus, $validStatuses)) {
            throw new \Exception('Estado inválido.');
        }

        $updates = ['status' => $newStatus];

        switch ($newStatus) {
            case 'paid':
                $updates['paid_at'] = $this->paid_at ?? now();
                break;
            case 'ready':
                $updates['ready_at'] = $this->ready_at ?? now();
                break;
            case 'delivered':
                $updates['delivered_at'] = $this->delivered_at ?? now();
                break;
            case 'cancelled':
                $updates['cancelled_at'] = $this->cancelled_at ?? now();
                break;
        }

        return $this->update($updates);
    }

    public function cancelOrder(string $reason): bool
    {
        return $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);
    }

    /**
     * Genera QR usando Chillerlan
     * OPCIONES SIMPLIFICADAS - Sin constantes problemáticas
     */
    private function generateQrCode(string $data, string $filename): string
    {
        try {
            // Crear directorio
            $directory = storage_path('app/public/qr-codes');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $path = "qr-codes/{$filename}";
            $fullPath = storage_path("app/public/{$path}");

            // Opciones SIMPLIFICADAS que funcionan
            $options = new QROptions([
                'version'              => 10,
                'outputType'           => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel'             => QRCode::ECC_L,
                'scale'                => 10,
                'imageBase64'          => false,
                'imageTransparent'     => false,
                'drawCircularModules'  => false,
                'drawLightModules'     => true,
            ]);

            // Generar QR
            $qrcode = new QRCode($options);
            $output = $qrcode->render($data);

            // Guardar
            file_put_contents($fullPath, $output);

            // Verificar que se guardó correctamente
            if (!file_exists($fullPath) || filesize($fullPath) === 0) {
                throw new \Exception('El archivo QR no se guardó correctamente');
            }

            return $path;
        } catch (\Exception $e) {
            \Log::error('Error generando QR Code', [
                'data' => $data,
                'filename' => $filename,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public static function checkAndEnableDelayedChats(): void
    {
        $delayedOrders = self::ready()
            ->whereNotNull('ready_at')
            ->where('chat_enabled', false)
            ->get()
            ->filter(function ($order) {
                return $order->isDelayedDelivery();
            });

        foreach ($delayedOrders as $order) {
            $order->update(['chat_enabled' => true]);
        }
    }
}
