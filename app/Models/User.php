<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * Customize the creation timestamp name.
     */
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'updated_at'; // Laravel migrations create updated_at by default

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre_completo',
        'name',
        'email',
        'rol_id',
        'codigo_acceso',
        'activo',
        'password',
        'grupo',
        'departamento_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
            'fecha_creacion' => 'datetime',
        ];
    }

    /**
     * Relationship with Role
     */
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }

    /**
     * Relationship with Departamento
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    /**
     * Accessor for name attribute (for backward compatibility)
     */
    public function getNameAttribute(): string
    {
        return $this->nombre_completo ?? '';
    }

    /**
     * Mutator for name attribute (for backward compatibility)
     */
    public function setNameAttribute(?string $value): void
    {
        $this->attributes['nombre_completo'] = $value;
    }

    /**
     * Accessor for role attribute (for backward compatibility in dashboards)
     */
    public function getRoleAttribute(): string
    {
        $nombreRol = $this->rol->nombre ?? '';
        return match($nombreRol) {
            'Admin' => 'admin',
            'Agente TI' => 'agente',
            'Operador' => 'user',
            default => 'user'
        };
    }

    /**
     * Automatically generate unique access codes and default roles on creation.
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->rol_id)) {
                $operadorRole = Role::where('nombre', 'Operador')->first();
                if ($operadorRole) {
                    $user->rol_id = $operadorRole->id;
                }
            }
            
            if (empty($user->codigo_acceso)) {
                $prefix = 'OP';
                if ($user->rol_id) {
                    $rolNombre = Role::find($user->rol_id)->nombre ?? '';
                    $prefix = match($rolNombre) {
                        'Admin' => 'AD',
                        'Agente TI' => 'TI',
                        default => 'OP',
                    };
                } else if ($user->grupo) {
                    $prefix = match($user->grupo) {
                        'Técnico' => 'OP',
                        default => 'AD',
                    };
                }
                $user->codigo_acceso = $prefix . '-' . rand(1000, 9999);
            }
            if (!isset($user->activo)) {
                $user->activo = true;
            }
        });
    }
}
