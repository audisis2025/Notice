<?php
/**
 * Nombre de la clase           : CouponService
 * Descripción de la clase      : Servicio que encapsula la lógica de negocio
 *                                para la gestión de cupones
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

use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * CouponService
 * 
 * Servicio para gestionar cupones de descuento.
 *
 * @package App\Services
 */
class CouponService
{
    /**
     * Genera un nuevo cupón.
     *
     * @param array $data Datos del cupón
     * @return Coupon
     */
    public function generateCoupon(array $data): Coupon
    {
        DB::beginTransaction();
        
        try {
            // Si no se proporciona código, generar uno automáticamente
            if (!isset($data['code']) || empty($data['code'])) {
                $data['code'] = $this->generateUniqueCode();
            }
            
            $coupon = Coupon::create($data);
            
            // Registrar actividad
            activity()
                ->performedOn($coupon)
                ->causedBy(auth()->user())
                ->log('Cupón generado');
            
            DB::commit();
            
            return $coupon;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Genera un código único para un cupón.
     *
     * @return string
     */
    protected function generateUniqueCode(): string
    {
        $length = config('system_settings.coupon_code_length', 8);
        
        do {
            $code = strtoupper(Str::random($length));
        } while (Coupon::where('code', $code)->exists());
        
        return $code;
    }

    /**
     * Valida un cupón por su código.
     *
     * @param string $code Código del cupón
     * @return Coupon|null
     * @throws \Exception
     */
    public function validateCoupon(string $code): ?Coupon
    {
        $coupon = Coupon::where('code', $code)->first();
        
        if (!$coupon) {
            throw new \Exception('El cupón no existe.');
        }
        
        if (!$coupon->is_active) {
            throw new \Exception('El cupón está inactivo.');
        }
        
        if ($coupon->is_used) {
            throw new \Exception('El cupón ya ha sido utilizado.');
        }
        
        if ($coupon->isExpired()) {
            throw new \Exception('El cupón ha expirado.');
        }
        
        return $coupon;
    }

    /**
     * Actualiza un cupón existente.
     *
     * @param Coupon $coupon Cupón a actualizar
     * @param array $data Datos actualizados
     * @return Coupon
     */
    public function updateCoupon(Coupon $coupon, array $data): Coupon
    {
        DB::beginTransaction();
        
        try {
            $coupon->update($data);
            
            // Registrar actividad
            activity()
                ->performedOn($coupon)
                ->causedBy(auth()->user())
                ->log('Cupón actualizado');
            
            DB::commit();
            
            return $coupon;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Elimina un cupón (soft delete).
     *
     * @param Coupon $coupon Cupón a eliminar
     * @return bool
     */
    public function deleteCoupon(Coupon $coupon): bool
    {
        DB::beginTransaction();
        
        try {
            $coupon->delete();
            
            // Registrar actividad
            activity()
                ->performedOn($coupon)
                ->causedBy(auth()->user())
                ->log('Cupón eliminado');
            
            DB::commit();
            
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Activa o desactiva un cupón.
     *
     * @param Coupon $coupon Cupón
     * @param bool $isActive Estado activo
     * @return Coupon
     */
    public function toggleCouponStatus(Coupon $coupon, bool $isActive): Coupon
    {
        $coupon->update(['is_active' => $isActive]);
        return $coupon;
    }
}