<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Product",
 *     title="Product",
 *     description="Product model schema",
 *     required={"id", "name", "description", "price"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Sample Product"),
 *     @OA\Property(property="description", type="string", example="Product description"),
 *     @OA\Property(property="price", type="number", format="float", example=99.99),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'category', 'brand', 'price', 'stock_quantity','product_image'];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function productSuppliers()
    {
        return $this->hasMany(ProductSupplier::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

}
