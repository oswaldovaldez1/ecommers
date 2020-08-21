<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $sku
 * @property string $title
 * @property string $description
 * @property string $trademark
 * @property string $category
 * @property float $price
 * @property float $previus_price
 * @property int $stock
 * @property string $size
 * @property boolean $status
 * @property string $url
 * @property string $created_at
 * @property string $updated_at
 * @property Category $category
 * @property ProductCart[] $productCarts
 * @property ProductGallery[] $productGalleries
 * @property ProductOrder[] $productOrders
 */
class ProductModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['sku', 'category', 'title', 'description', 'trademark', 'price', 'previus_price', 'stock', 'size', 'status', 'url', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Category', 'category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productCarts()
    {
        return $this->hasMany('App\ProductCart');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productGalleries()
    {
        return $this->hasMany('App\ProductGallery', 'id_product');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productOrders()
    {
        return $this->hasMany('App\ProductOrder');
    }
}