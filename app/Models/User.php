<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
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
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Relación: Un usuario puede tener un negocio (BusinessAdministrator).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function business()
    {
        return $this->hasOne(Business::class, 'user_id');
    }

    /**
     * Relación: Un usuario móvil puede tener muchas órdenes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relación: Un usuario móvil puede tener muchas calificaciones.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Relación: Un usuario móvil puede tener muchos tokens de dispositivo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    /**
     * Relación: Un usuario puede tener muchos chats.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    /**
     * Relación: Un usuario puede enviar muchos mensajes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Verifica si el usuario es SuperAdministrador.
     *
     * @return bool
     */
    public function isSuperAdministrator(): bool
    {
        return $this->role === 'SuperAdministrator';
    }

    /**
     * Verifica si el usuario es Administrador de Negocio.
     *
     * @return bool
     */
    public function isBusinessAdministrator(): bool
    {
        return $this->role === 'BusinessAdministrator';
    }

    /**
     * Verifica si el usuario es Usuario Móvil.
     *
     * @return bool
     */
    public function isMobileUser(): bool
    {
        return $this->role === 'MobileUser';
    }

    /**
     * Scope: Filtra usuarios activos.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filtra usuarios por rol.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }
    /**
     * Obtener las iniciales del usuario
     * 
     * @return string
     */
    public function initials(): string
    {
        $words = explode(' ', trim($this->name));

        if (count($words) >= 2) {
            // Si tiene dos o más palabras, toma la primera letra de las primeras dos
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }

        // Si solo tiene una palabra, toma las primeras dos letras
        return strtoupper(substr($this->name, 0, 2));
    }
}
