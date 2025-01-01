<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *     schema="Order",
 *     title="Order",
 *     description="Order model schema",
 *     required={"id", "customer_id", "status"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="customer_id", type="integer", example=1),
 *     @OA\Property(property="status", type="string", example="Pending"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'order_date', 'total_amount', 'status', 'delivery_address', 'is_home_delivery'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class,'order_id');
    }

    protected function casts():array {
        return [
            'order_date' => 'datetime',
        ];
    }
}
