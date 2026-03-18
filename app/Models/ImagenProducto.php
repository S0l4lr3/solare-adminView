<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenProducto extends Model
{
    protected $table = 'imagenes_producto';
    protected $primaryKey = 'id';
    public $timestamps = false;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null; // Esta tabla NO tiene updated_at

    protected $fillable = [
        'producto_id',
        'url',
        'es_principal',
        'orden',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}