<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $orden_id
 * @property string $sku
 * @property string $titulo
 * @property string $precio
 * @property integer $qty
 * @property string $propietario
 * @property string $ubicacion
 * @property string $created_at
 * @property string $updated_at
 * @property Orden $orden
 */
class ProductOrderModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_orders';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['sku', 'orden_id', 'quantities', 'created_at', 'updated_at','ubicacion'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orden()
    {
        return $this->belongsTo('App\Orden');
    }
}
