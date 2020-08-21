<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $nombre
 * @property string $tag
 * @property integer $porcentaje
 * @property string $provedores
 * @property string $created_at
 * @property string $updated_at
 */
class MarcasModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'marcas';
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['nombre', 'tag', 'porcentaje', 'provedores', 'created_at', 'updated_at'];
}
