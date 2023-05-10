<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     title="UserPerfil",
 *     description="Modelo de perfil de usuário",
 *     @OA\Xml(name="UserPerfil"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="user_id", type="integer", example="1"),
 *     @OA\Property(property="nome", type="string", example="João"),
 *     @OA\Property(property="tipo", type="string", example="comum"),
 *     @OA\Property(property="cpf", type="string", example="123.456.789-00"),
 *     @OA\Property(property="cnpj", type="string", example="12.345.678/0001-99")
 * )
 */
class UserPerfil extends Model
{
    protected $table = 'user_perfil';

    protected $fillable = [
        'user_id', 'nome', 'tipo', 'cpf', 'cnpj',
    ];

    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
