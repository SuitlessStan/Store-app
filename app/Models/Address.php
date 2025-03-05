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
 *     @OA\Property(property="latitude", type="number", format="float", example=40.712776),
 *     @OA\Property(property="longitude", type="number", format="float", example=-74.005974),
 *     @OA\Property(property="streetname", type="string", example="Main St"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T12:30:00Z")
 * )
 */
class Address extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'address', 'label', 'latitude', 'longitude', 'streetname'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
