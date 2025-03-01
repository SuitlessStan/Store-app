<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Address",
 *     title="Address",
 *     description="Address model schema",
 *     required={"id", "user_id", "address", "label"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="address", type="string", example="123 Main St, City, Country"),
 *     @OA\Property(property="label", type="string", example="home"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Address extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'address', 'label'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
