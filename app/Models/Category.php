<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     title="Category",
 *     description="Category entity",
 *     @OA\Property(property="id", type="integer", description="ID of the category", example=1),
 *     @OA\Property(property="name", type="string", description="Name of the category", example="Electronics"),
 *     @OA\Property(property="description", type="string", description="Description of the category", example="Electronics and gadgets"),
 *     @OA\Property(property="image", type="string", description="Path to the category image", example="categories/image.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the category was created", example="2025-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the category was last updated", example="2025-01-01T12:30:00Z")
 * )
 */

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'image'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
