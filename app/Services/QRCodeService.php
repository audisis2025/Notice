<?php
/**
 * Nombre de la clase           : QRCodeService
 * Descripción de la clase      : Servicio que gestiona la generación y validación
 *                                de códigos QR
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 * Fecha de mantenimiento       : 
 * Folio de mantenimiento       : 
 * Tipo de mantenimiento        :
 * Descripción del mantenimiento: 
 * Responsable                  : 
 * Revisor                      : 
 */

namespace App\Services;

use App\Models\Order;
use App\Models\OrderQRCode;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeService
{
    /**
     * Generar código QR de asociación para una orden
     */
    public function generateAssociationQR(Order $order): OrderQRCode
    {
        $code = $this->generateUniqueCode('assoc');
        
        $qrImage = QrCode::format('png')
                        ->size(300)
                        ->margin(2)
                        ->generate($code);

        return OrderQRCode::create([
            'order_id' => $order->id,
            'type' => 'association',
            'code' => $code,
            'qr_image' => base64_encode($qrImage),
            'expires_at' => null, // No expira
        ]);
    }

    /**
     * Generar código QR de entrega para una orden
     */
    public function generateDeliveryQR(Order $order): OrderQRCode
    {
        $code = $this->generateUniqueCode('deliv');
        
        $qrImage = QrCode::format('png')
                        ->size(300)
                        ->margin(2)
                        ->generate($code);

        return OrderQRCode::create([
            'order_id' => $order->id,
            'type' => 'delivery',
            'code' => $code,
            'qr_image' => base64_encode($qrImage),
            'expires_at' => now()->addHours(24), // Expira en 24 horas
        ]);
    }

    /**
     * Validar código QR
     */
    public function validateQR(string $code): ?OrderQRCode
    {
        $qrCode = OrderQRCode::where('code', $code)
                            ->unused()
                            ->valid()
                            ->first();

        return $qrCode;
    }

    /**
     * Usar código QR de asociación
     */
    public function useAssociationQR(string $code, int $userId): ?Order
    {
        $qrCode = $this->validateQR($code);

        if (!$qrCode || $qrCode->type !== 'association') {
            return null;
        }

        $qrCode->markAsUsed($userId);

        // Asociar usuario a la orden
        $order = $qrCode->order;
        $order->update(['mobile_user_id' => $userId]);

        return $order;
    }

    /**
     * Usar código QR de entrega
     */
    public function useDeliveryQR(string $code, int $userId): ?Order
    {
        $qrCode = $this->validateQR($code);

        if (!$qrCode || $qrCode->type !== 'delivery') {
            return null;
        }

        $qrCode->markAsUsed($userId);

        // Marcar orden como entregada
        $order = $qrCode->order;
        $order->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);

        return $order;
    }

    /**
     * Generar código único
     */
    private function generateUniqueCode(string $prefix): string
    {
        do {
            $code = strtoupper($prefix . '-' . Str::random(12));
        } while (OrderQRCode::where('code', $code)->exists());

        return $code;
    }

    /**
     * Obtener imagen QR en base64
     */
    public function getQRImage(OrderQRCode $qrCode): string
    {
        return 'data:image/png;base64,' . $qrCode->qr_image;
    }

    /**
     * Regenerar código QR de entrega (si expiró)
     */
    public function regenerateDeliveryQR(Order $order): OrderQRCode
    {
        // Marcar QR anterior como usado
        $oldQR = $order->qrCodes()->delivery()->first();
        if ($oldQR) {
            $oldQR->update(['is_used' => true]);
        }

        // Generar nuevo QR
        return $this->generateDeliveryQR($order);
    }
}