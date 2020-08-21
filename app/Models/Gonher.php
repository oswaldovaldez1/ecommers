<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sku
 * @property string $titulo
 * @property string $marca
 * @property string $descripcion_corta
 * @property string $descripcion_completa
 * @property string $imagen
 * @property string $imgs
 * @property string $categorias
 * @property string $propietario
 * @property string $precio
 * @property string $precio_dis
 * @property string $precio_desc
 * @property string $disponible
 * @property string $created_at
 * @property string $updated_at
 */
class Gonher extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gonher';

    /**
     * @var array
     */
    protected $fillable = ['sku', 'titulo', 'marca', 'descripcion_corta', 'descripcion_completa', 'imagen', 'imgs', 'categorias', 'precio', 'precio_dis', 'precio_desc', 'disponible', 'created_at', 'updated_at', 'propietario'];
}
