<?php

/**
 * Nombre de la clase           : User
 * Descripción de la clase      : Modelo Eloquent que representa un usuario
 *                                con lógica de negocio integrada
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Eliminación de Services - Lógica movida al modelo
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'birth_date',
        'role',
        'is_active',
    ];

    /**
     * Los atributos que deben ocultarse para serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser convertidos.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Relación: Un usuario puede tener un negocio (BusinessAdministrator).
     */
    public function business()
    {
        return $this->hasOne(Business::class, 'user_id');
    }

    /**
     * Relación: Un usuario móvil puede tener muchas órdenes.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relación: Un usuario móvil puede tener muchas calificaciones.
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Relación: Un usuario móvil puede tener muchos tokens de dispositivo.
     */
    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    /**
     * Relación: Un usuario puede tener muchos chats.
     */
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    /**
     * Relación: Un usuario puede enviar muchos mensajes.
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Verifica si el usuario es SuperAdministrador.
     */
    public function isSuperAdministrator(): bool
    {
        return $this->role === 'SuperAdministrator';
    }

    /**
     * Verifica si el usuario es Administrador de Negocio.
     */
    public function isBusinessAdministrator(): bool
    {
        return $this->role === 'BusinessAdministrator';
    }

    /**
     * Verifica si el usuario es Usuario Móvil.
     */
    public function isMobileUser(): bool
    {
        return $this->role === 'MobileUser';
    }

    /**
     * Scope: Filtra usuarios activos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filtra usuarios por rol.
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Obtener las iniciales del usuario.
     */
    public function initials(): string
    {
        $words = explode(' ', trim($this->name));

        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }

        return strtoupper(substr($this->name, 0, 2));
    }

    // ====================================================================
    // MÉTODOS DE LÓGICA DE NEGOCIO (Anteriormente en UserService)
    // ====================================================================

    /**
     * Crea un nuevo usuario en el sistema.
     *
     * @param array $data
     * @return User
     */
    public static function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return self::create($data);
    }

    /**
     * Actualiza el usuario.
     *
     * @param array $data
     * @return bool
     */
    public function updateUser(array $data): bool
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->update($data);
    }

    /**
     * Activa o desactiva el usuario.
     *
     * @param bool $isActive
     * @return bool
     */
    public function toggleStatus(bool $isActive): bool
    {
        return $this->update(['is_active' => $isActive]);
    }

    /**
     * Obtiene usuarios por rol (estático).
     *
     * @param string $role
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getUsersByRole(string $role)
    {
        return self::byRole($role)->active()->get();
    }
}
