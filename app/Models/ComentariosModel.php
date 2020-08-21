<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $sku
 * @property string $correo
 * @property string $comentario
 * @property integer $cal
 * @property string $created_at
 * @property string $updated_at
 */
class ComentariosModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comentarios';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['sku', 'correo', 'comentario', 'cal', 'created_at', 'updated_at'];

}