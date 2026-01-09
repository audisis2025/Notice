<?php

namespace App\Services;

use App\Models\Business;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

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
            // Procesar logo si existe
            if (isset($data['logo']) && $data['logo']) {
                $logoPath = $data['logo']->store('businesses/logos', 'public');
                $data['logo'] = $logoPath;
            }
            
            $data['user_id'] = $user->id;
            
            $business = Business::create($data);
            
            DB::commit();
            
            return $business;
        } catch (\Exception $e) {
            DB::rollBack();
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
            
            DB::commit();
            
            return $business;
        } catch (\Exception $e) {
            DB::rollBack();
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
            
            // Registrar actividad
            activity()
                ->performedOn($business)
                ->causedBy(auth()->user())
                ->withProperties(['reason' => $reason])
                ->log('Servicio suspendido');
            
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
            
            // Registrar actividad
            activity()
                ->performedOn($business)
                ->causedBy(auth()->user())
                ->log('Servicio reactivado');
            
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