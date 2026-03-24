<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DireccionEnvio extends Model
{
    protected $table = 'direcciones_envio';
    protected $primaryKey = 'id';
    public $timestamps = false;

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'cliente_id',
        'alias',
        'calle',
        'numero_exterior',
        'numero_interior',
        'colonia',
        'ciudad',
        'estado',
        'codigo_postal',
        'pais',
        'referencias',
        'es_principal',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // Helper para mostrar dirección completa en una línea
    public function getDireccionCompletaAttribute()
    {
        $interior = $this->numero_interior ? " Int. {$this->numero_interior}" : '';
        return "{$this->calle} {$this->numero_exterior}{$interior}, {$this->colonia}, {$this->ciudad}, {$this->estado} CP {$this->codigo_postal}";
    }
}