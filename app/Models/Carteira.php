<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     title="Carteira",
 *     description="Modelo de carteira",
 *     @OA\Xml(name="Carteira"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="saldo", type="number", example="100.00"),
 *     @OA\Property(property="user_id", type="integer", example="1")
 * )
 */
class Carteira extends Model
{
    protected $fillable = [
        'saldo', 'user_id'
    ];

    use HasFactory;
    // sobrescrever $table 
    protected $table = 'carteira';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
