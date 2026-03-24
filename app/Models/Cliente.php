<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id';
    public $timestamps = false;

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'usuario_id',
        'telefono',
        'identificacion_fiscal',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'cliente_id');
    }

    public function direcciones()
    {
        return $this->hasMany(DireccionEnvio::class, 'cliente_id');
    }

    // Nombre completo del cliente tirando del usuario relacionado
    public function getNombreCompletoAttribute()
    {
        return $this->usuario
            ? trim("{$this->usuario->nombre} {$this->usuario->apellido_paterno} {$this->usuario->apellido_materno}")
            : 'Sin nombre';
    }
}