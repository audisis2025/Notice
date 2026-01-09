<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * UserService
 * 
 * Servicio para gestionar operaciones relacionadas con usuarios.
 *
 * @package App\Services
 */
class UserService
{
    /**
     * Crea un nuevo usuario en el sistema.
     *
     * @param array $data Datos del usuario
     * @return User
     */
    public function createUser(array $data): User
    {
        DB::beginTransaction();
        
        try {
            $data['password'] = Hash::make($data['password']);
            
            $user = User::create($data);
            
            DB::commit();
            
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Actualiza un usuario existente.
     *
     * @param User $user Usuario a actualizar
     * @param array $data Datos actualizados
     * @return User
     */
    public function updateUser(User $user, array $data): User
    {
        DB::beginTransaction();
        
        try {
            if (isset($data['password']) && !empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            
            $user->update($data);
            
            DB::commit();
            
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Elimina un usuario del sistema (soft delete).
     *
     * @param User $user Usuario a eliminar
     * @return bool
     */
    public function deleteUser(User $user): bool
    {
        DB::beginTransaction();
        
        try {
            $user->delete();
            
            DB::commit();
            
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Activa o desactiva un usuario.
     *
     * @param User $user Usuario
     * @param bool $isActive Estado activo
     * @return User
     */
    public function toggleUserStatus(User $user, bool $isActive): User
    {
        $user->update(['is_active' => $isActive]);
        return $user;
    }

    /**
     * Obtiene usuarios por rol.
     *
     * @param string $role Rol del usuario
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsersByRole(string $role)
    {
        return User::byRole($role)->active()->get();
    }
}