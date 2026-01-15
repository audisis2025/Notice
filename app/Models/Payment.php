<?php

/**
 * Nombre de la clase           : Payment
 * Descripción de la clase      : Modelo Eloquent que representa un pago simulado
 *                                con lógica de negocio integrada
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Tipo de mantenimiento        : Perfectivo
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'business_id',
        'business_package_id',
        'payment_method',
        'card_last_four',
        'card_brand',
        'amount',
        'status',
        'transaction_id',
    ];

    /**
     * Los atributos que deben ser convertidos.
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Relación: Un pago pertenece a un negocio.
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Relación: Un pago pertenece a una suscripción de paquete.
     */
    public function businessPackage()
    {
        return $this->belongsTo(BusinessPackage::class);
    }

    /**
     * Scope: Filtra pagos completados.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // ====================================================================
    // MÉTODOS DE LÓGICA DE NEGOCIO
    // ====================================================================

    /**
     * Procesa un pago simulado.
     *
     * @param Business $business
     * @param BusinessPackage $businessPackage
     * @param array $paymentData
     * @param float $amount
     * @return Payment
     */
    public static function processPayment(
        Business $business,
        BusinessPackage $businessPackage,
        array $paymentData,
        float $amount
    ): Payment {
        // Generar ID de transacción único
        $transactionId = 'TXN-' . strtoupper(uniqid());

        // Obtener últimos 4 dígitos de la tarjeta
        $cardLastFour = substr($paymentData['card_number'], -4);

        // Simular tipo de tarjeta
        $cardBrand = self::detectCardBrand($paymentData['card_number']);

        return self::create([
            'business_id' => $business->id,
            'business_package_id' => $businessPackage->id,
            'payment_method' => $paymentData['payment_method'],
            'card_last_four' => $cardLastFour,
            'card_brand' => $cardBrand,
            'amount' => $amount,
            'status' => 'completed',
            'transaction_id' => $transactionId,
        ]);
    }

    /**
     * Detecta la marca de tarjeta por el número (simulado).
     *
     * @param string $cardNumber
     * @return string
     */
    protected static function detectCardBrand(string $cardNumber): string
    {
        $firstDigit = substr($cardNumber, 0, 1);

        return match($firstDigit) {
            '4' => 'Visa',
            '5' => 'Mastercard',
            '3' => 'American Express',
            default => 'Unknown',
        };
    }
}
