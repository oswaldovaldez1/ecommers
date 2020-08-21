<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $method
 * @property float $pay_amount
 * @property string $order_number
 * @property string $customer_email
 * @property string $customer_name
 * @property string $customer_phone
 * @property string $customer_addr
 * @property string $customer_city
 * @property string $customer_col
 * @property string $customer_edo
 * @property string $customer_zip
 * @property string $referencias
 * @property string $nseg
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property ProductOrder[] $productOrders
 */
class Order extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orden';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['method', 'pay_amount', 'order_number', 'customer_email', 'customer_name', 'customer_phone', 'customer_addr', 'customer_city', 'customer_col', 'customer_edo', 'customer_zip', 'referencias', 'nseg', 'status', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productOrders()
    {
        return $this->hasMany('App\ProductOrder');
    }
}
