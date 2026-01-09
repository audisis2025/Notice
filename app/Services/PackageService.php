<?php
/**
 * Nombre de la clase           : PackageService
 * Descripción de la clase      : Servicio que encapsula la lógica de negocio
 *                                para la gestión de paquetes comerciales
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

use App\Models\Package;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * PackageService
 * 
 * Servicio para gestionar paquetes comerciales.
 *
 * @package App\Services
 */
class PackageService
{
    /**
     * Crea un nuevo paquete comercial.
     *
     * @param array $data Datos del paquete
     * @return Package
     */
    public function createPackage(array $data): Package
    {
        DB::beginTransaction();
        
        try {
            $package = Package::create($data);
            
            // Registrar actividad
            activity()
                ->performedOn($package)
                ->causedBy(auth()->user())
                ->log('Paquete creado');
            
            DB::commit();
            
            return $package;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Actualiza un paquete existente.
     *
     * @param Package $package Paquete a actualizar
     * @param array $data Datos actualizados
     * @return Package
     */
    public function updatePackage(Package $package, array $data): Package
    {
        DB::beginTransaction();
        
        try {
            $package->update($data);
            
            // Registrar actividad
            activity()
                ->performedOn($package)
                ->causedBy(auth()->user())
                ->log('Paquete actualizado');
            
            DB::commit();
            
            return $package;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Elimina un paquete (soft delete).
     *
     * @param Package $package Paquete a eliminar
     * @return bool
     */
    public function deletePackage(Package $package): bool
    {
        DB::beginTransaction();
        
        try {
            $package->delete();
            
            // Registrar actividad
            activity()
                ->performedOn($package)
                ->causedBy(auth()->user())
                ->log('Paquete eliminado');
            
            DB::commit();
            
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Obtiene todos los paquetes activos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActivePackages()
    {
        return Package::active()->orderBy('price')->get();
    }

    /**
     * Activa o desactiva un paquete.
     *
     * @param Package $package Paquete
     * @param bool $isActive Estado activo
     * @return Package
     */
    public function togglePackageStatus(Package $package, bool $isActive): Package
    {
        $package->update(['is_active' => $isActive]);
        return $package;
    }
}