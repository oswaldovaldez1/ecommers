<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $provedor
 * @property integer $porcentaje
 * @property integer $descuento
 * @property string $created_at
 * @property string $updated_at
 */
class ProvedorModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'provedor';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['provedor', 'porcentaje', 'descuento', 'created_at', 'updated_at'];
}
