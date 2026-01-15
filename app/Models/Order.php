<?php

/**
 * Nombre de la clase           : Order
 * Descripción de la clase      : Modelo Eloquent que representa una orden con
 *                                su lógica de negocio y gestión de estados
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 14/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 3.0
 * Fecha de mantenimiento       : 14/01/2026
 * Folio de mantenimiento       : 2
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Simplificación - Solo order_number requerido
 *                                description y amount son opcionales
 * Responsable                  : Jesús Núñez
 * Revisor                      : Jesús Núñez
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'order_number',
        'business_id',
        'user_id',
        'description',
        'amount',
        'status',
        'qr_code',
        'qr_delivery_code',
        'paid_at',
        'ready_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
        'associated_at',
        'chat_enabled',
    ];

    /**
     * Los atributos que deben ser convertidos.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'ready_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'associated_at' => 'datetime',
        'chat_enabled' => 'boolean',
    ];

    /**
     * Relación: Una orden pertenece a un negocio.
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Relación: Una orden puede pertenecer a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Una orden puede tener muchos recordatorios.
     */
    public function reminders()
    {
        return $this->hasMany(OrderReminder::class);
    }

    /**
     * Relación: Una orden puede tener una calificación.
     */
    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    /**
     * Relación: Una orden puede tener un chat.
     */
    public function chat()
    {
        return $this->hasOne(Chat::class);
    }

    /**
     * Relación: Una orden puede tener muchos códigos QR.
     */
    public function qrCodes()
    {
        return $this->hasMany(OrderQRCode::class);
    }

    /**
     * Relación: Código QR de asociación.
     */
    public function associationQR()
    {
        return $this->hasOne(OrderQRCode::class)->where('type', 'association');
    }

    /**
     * Relación: Código QR de entrega.
     */
    public function deliveryQR()
    {
        return $this->hasOne(OrderQRCode::class)->where('type', 'delivery')->latest();
    }

    /**
     * Verifica si la orden está asociada a un usuario.
     */
    public function isAssociated(): bool
    {
        return $this->user_id !== null && $this->associated_at !== null;
    }

    /**
     * Verifica si la orden puede ser entregada.
     */
    public function canBeDelivered(): bool
    {
        return $this->status === 'ready' && $this->isAssociated();
    }

    /**
     * Verifica si se ha excedido el período de entrega.
     */
    public function isDelayedDelivery(): bool
    {
        if (!$this->ready_at || $this->status !== 'ready') {
            return false;
        }

        $deliveryPeriod = $this->business->delivery_period_minutes;
        $readyTime = Carbon::parse($this->ready_at);
        $expectedDeliveryTime = $readyTime->addMinutes($deliveryPeriod);

        return Carbon::now()->isAfter($expectedDeliveryTime);
    }

    /**
     * Scope: Filtra órdenes por estado.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filtra órdenes listas.
     */
    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    /**
     * Scope: Filtra órdenes con retraso en entrega.
     */
    public function scopeDelayed($query)
    {
        return $query->where('status', 'ready')
            ->whereNotNull('ready_at')
            ->where('chat_enabled', false)
            ->get()
            ->filter(function ($order) {
                return $order->isDelayedDelivery();
            });
    }

    // ====================================================================
    // MÉTODOS DE LÓGICA DE NEGOCIO (Anteriormente en OrderService)
    // ====================================================================

    /**
     * Crea una nueva orden SOLO con número de orden.
     * Description y amount son opcionales.
     *
     * @param Business $business
     * @param array $data
     * @return Order
     */
    public static function createOrder(Business $business, array $data): Order
    {
        return self::create([
            'order_number' => $data['order_number'], // ✅ Ahora manual
            'business_id' => $business->id,
            'description' => $data['description'] ?? null, // ✅ Opcional
            'amount' => $data['amount'] ?? null, // ✅ Opcional
            'status' => 'pending',
        ]);
    }

    /**
     * Marca la orden como pagada y genera QR de asociación.
     *
     * @return bool
     */
    public function markAsPaid(): bool
    {
        // Generar código QR para asociar orden
        $qrCode = $this->generateQRCode('association');

        return $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'qr_code' => $qrCode,
        ]);
    }

    /**
     * Marca la orden como lista y genera QR de entrega.
     *
     * @return bool
     */
    public function markAsReady(): bool
    {
        // Generar QR para confirmación de entrega
        $qrDeliveryCode = $this->generateQRCode('delivery');

        $result = $this->update([
            'status' => 'ready',
            'ready_at' => now(),
            'qr_delivery_code' => $qrDeliveryCode,
        ]);

        // Notificar al usuario si está asociado
        if ($this->user) {
            $this->user->notify(new \App\Notifications\OrderReadyNotification($this));
        }

        return $result;
    }

    /**
     * Asocia la orden con un usuario mediante escaneo de QR.
     *
     * @param User $user
     * @return bool
     */
    public function associateWithUser(User $user): bool
    {
        return $this->update([
            'user_id' => $user->id,
            'associated_at' => now(),
        ]);
    }

    /**
     * Confirma la entrega de la orden.
     *
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function confirmDelivery(User $user): bool
    {
        // Verificar que el usuario sea el dueño de la orden
        if ($this->user_id !== $user->id) {
            throw new \Exception('Solo el usuario asociado puede confirmar la entrega.');
        }

        // Verificar que la orden esté lista
        if ($this->status !== 'ready') {
            throw new \Exception('La orden no está lista para entrega.');
        }

        return $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    /**
     * Cancela la orden.
     *
     * @param string $reason
     * @return bool
     */
    public function cancelOrder(string $reason): bool
    {
        $result = $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);

        // Notificar al usuario si está asociado
        if ($this->user) {
            $this->user->notify(new \App\Notifications\OrderCancelledNotification($this));
        }

        return $result;
    }

    /**
     * Genera un código QR para la orden.
     *
     * @param string $type (association o delivery)
     * @return string Path del archivo QR
     */
    protected function generateQRCode(string $type): string
    {
        $data = json_encode([
            'order_id' => $this->id,
            'order_number' => $this->order_number,
            'type' => $type,
            'business_id' => $this->business_id,
        ]);

        $qrCode = QrCode::format('png')
            ->size(300)
            ->generate($data);

        $filename = "{$this->order_number}_{$type}_" . time() . '.png';
        $path = "qrcodes/{$filename}";

        Storage::disk('public')->put($path, $qrCode);

        return $path;
    }

    /**
     * Verifica órdenes con retraso y habilita chat.
     */
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

            // Notificar al usuario que puede abrir chat
            if ($order->user) {
                $order->user->notify(new \App\Notifications\ChatEnabledNotification($order));
            }
        }
    }
}
