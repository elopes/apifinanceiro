<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Transfere",
 *     description="Modelo de transferência",
 *     @OA\Xml(name="Transfere"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="origem_id", type="integer", example="1"),
 *     @OA\Property(property="transferido_de", type="string", maxLength=32, example="João"),
 *     @OA\Property(property="destinatario_id", type="integer", example="2"),
 *     @OA\Property(property="recebido_por", type="string", maxLength=32, example="Maria"),
 *     @OA\Property(property="quantia", type="number", example="100.00"),
 *     @OA\Property(property="origem", type="integer", example="1"),
 *     @OA\Property(property="destino", type="integer", example="2")
 * )
 */
class Transfere extends Model
{
    use HasFactory;
    // sobrescrever $table 
    protected $table = 'transferencia';

    protected $fillable = [
        'origem_id',
        'transferido_de',
        'destinatario_id',
        'recebido_por',
        'quantia',
        'origem',
        'destino'
    ];

    public function origem()
    {
        return $this->belongsTo(User::class, 'origem');
    }

    public function destino()
    {
        return $this->belongsTo(User::class, 'destino');
    }
}
