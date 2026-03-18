<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio_base',
        'sku_base',
        'activo',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenProducto::class, 'producto_id');
    }

    // Relación directa solo a la imagen principal
    public function imagenPrincipal()
    {
        return $this->hasOne(ImagenProducto::class, 'producto_id')
                    ->where('es_principal', 1);
    }
}