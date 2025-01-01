<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Profile",
 *     title="Profile",
 *     description="Profile model schema",
 *     required={"id", "user_id", "bio"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="bio", type="string", example="This is a sample bio."),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['bio'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
