<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="CartItem",
 *     title="CartItem",
 *     description="Represents a single item in a user's shopping cart",
 *     required={"cart_id", "product_id", "quantity"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Unique identifier for the cart item",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="cart_id",
 *         type="integer",
 *         description="Identifier of the cart this item belongs to",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="product_id",
 *         type="integer",
 *         description="Identifier of the product associated with this cart item",
 *         example=5
 *     ),
 *     @OA\Property(
 *         property="quantity",
 *         type="integer",
 *         description="Number of units of the product in the cart",
 *         example=2
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp when the cart item was created"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp when the cart item was last updated"
 *     )
 * )
 */
class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
