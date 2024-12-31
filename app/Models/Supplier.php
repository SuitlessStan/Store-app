<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Supplier",
 *     title="Supplier",
 *     description="Supplier model schema",
 *     required={"id", "name", "contact_email"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Supplier Name"),
 *     @OA\Property(property="contact_email", type="string", format="email", example="supplier@example.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'contact_person', 'phone', 'address'];

    public function productSuppliers()
    {
        return $this->hasMany(ProductSupplier::class);
    }
}
