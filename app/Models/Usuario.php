<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    public $timestamps = false;

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'correo',
        'contrasena',
        'rol_id',
    ];

    protected $hidden = [
        'contrasena',
        'token_recuerdo',
    ];

    // Le dice a Laravel que el campo password es "contrasena"
    protected $authPasswordName = 'contrasena';

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    // Helper para mostrar nombre completo
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}");
    }
}