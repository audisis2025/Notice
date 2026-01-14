<?php
/**
 * Nombre de la clase           : BusinessService
 * Descripción de la clase      : Servicio que encapsula la lógica de negocio
 *                                para la gestión de negocios
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 * Fecha de mantenimiento       : 13/01/2026
 * Folio de mantenimiento       : 2
 * Tipo de mantenimiento        : Correctivo
 * Descripción del mantenimiento: Eliminación de función activity() que no existe
 *                                y uso de Log::info() en su lugar
 * Responsable                  : Jesús Núñez
 * Revisor                      : Jesús Núñez
 */
namespace App\Services;

use App\Models\Business;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * BusinessService
 * 
 * Servicio para gestionar operaciones relacionadas con negocios.
 *
 * @package App\Services
 */
class BusinessService
{
    /**
     * Registra un nuevo negocio en el sistema.
     *
     * @param User $user Usuario propietario del negocio
     * @param array $data Datos del negocio
     * @return Business
     */
    public function registerBusiness(User $user, array $data): Business
    {
        DB::beginTransaction();
        
        try {
            // ✅ AGREGAR LOG
            Log::info('Iniciando registro de negocio', [
                'user_id' => $user->id,
                'data' => $data
            ]);
            
            // Procesar logo si existe
            if (isset($data['logo']) && $data['logo']) {
                $logoPath = $data['logo']->store('businesses/logos', 'public');
                $data['logo'] = $logoPath;
                
                Log::info('Logo procesado', ['path' => $logoPath]);
            }
            
            // ✅ Asegurar que can_be_rated sea boolean
            $data['can_be_rated'] = isset($data['can_be_rated']) ? (bool)$data['can_be_rated'] : true;
            
            $data['user_id'] = $user->id;
            
            // ✅ AGREGAR LOG
            Log::info('Creando negocio con datos', $data);
            
            $business = Business::create($data);
            
            // ✅ LOG en lugar de activity()
            Log::info('Negocio registrado exitosamente', [
                'business_id' => $business->id,
                'business_name' => $business->business_name
            ]);
            
            DB::commit();
            
            return $business;
        } catch (\Exception $e) {
            DB::rollBack();
            
            // ✅ LOG del error
            Log::error('Error al registrar negocio', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id
            ]);
            
            throw $e;
        }
    }

    /**
     * Actualiza un negocio existente.
     *
     * @param Business $business Negocio a actualizar
     * @param array $data Datos actualizados
     * @return Business
     */
    public function updateBusiness(Business $business, array $data): Business
    {
        DB::beginTransaction();
        
        try {
            // Procesar logo si existe
            if (isset($data['logo']) && $data['logo']) {
                // Eliminar logo anterior
                if ($business->logo) {
                    Storage::disk('public')->delete($business->logo);
                }
                
                $logoPath = $data['logo']->store('businesses/logos', 'public');
                $data['logo'] = $logoPath;
            }
            
            $business->update($data);
            
            Log::info('Negocio actualizado', [
                'business_id' => $business->id
            ]);
            
            DB::commit();
            
            return $business;
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error al actualizar negocio', [
                'error' => $e->getMessage(),
                'business_id' => $business->id
            ]);
            
            throw $e;
        }
    }

    /**
     * Suspende el servicio de un negocio.
     *
     * @param Business $business Negocio a suspender
     * @param string|null $reason Razón de suspensión
     * @return Business
     */
    public function suspendBusiness(Business $business, ?string $reason = null): Business
    {
        DB::beginTransaction();
        
        try {
            $business->update([
                'is_active' => false,
                'service_suspended_at' => now(),
            ]);
            
            // ✅ LOG en lugar de activity()
            Log::info('Servicio suspendido', [
                'business_id' => $business->id,
                'reason' => $reason,
                'user_id' => auth()->id()
            ]);
            
            DB::commit();
            
            return $business;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reactiva el servicio de un negocio.
     *
     * @param Business $business Negocio a reactivar
     * @return Business
     */
    public function reactivateBusiness(Business $business): Business
    {
        DB::beginTransaction();
        
        try {
            $business->update([
                'is_active' => true,
                'service_suspended_at' => null,
            ]);
            
            // ✅ LOG en lugar de activity()
            Log::info('Servicio reactivado', [
                'business_id' => $business->id,
                'user_id' => auth()->id()
            ]);
            
            DB::commit();
            
            return $business;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Obtiene negocios cercanos a una ubicación.
     *
     * @param float $latitude Latitud
     * @param float $longitude Longitud
     * @param int $radiusKm Radio en kilómetros
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNearbyBusinesses(float $latitude, float $longitude, int $radiusKm = 10)
    {
        // Fórmula de Haversine para calcular distancia
        $businesses = Business::active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("
                *,
                ( 6371 * acos( cos( radians(?) ) *
                cos( radians( latitude ) ) *
                cos( radians( longitude ) - radians(?) ) +
                sin( radians(?) ) *
                sin( radians( latitude ) ) ) ) AS distance
            ", [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radiusKm)
            ->orderBy('distance')
            ->get();
        
        return $businesses;
    }

    /**
     * Configura si un negocio puede ser calificado.
     *
     * @param Business $business Negocio
     * @param bool $canBeRated Puede ser calificado
     * @return Business
     */
    public function toggleRatings(Business $business, bool $canBeRated): Business
    {
        $business->update(['can_be_rated' => $canBeRated]);
        return $business;
    }

    /**
     * Actualiza el período de entrega de un negocio.
     *
     * @param Business $business Negocio
     * @param int $minutes Minutos de entrega
     * @return Business
     */
    public function updateDeliveryPeriod(Business $business, int $minutes): Business
    {
        $business->update(['delivery_period_minutes' => $minutes]);
        return $business;
    }
}