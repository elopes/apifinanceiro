<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @OA\Schema(
 *     title="User",
 *     description="Modelo de usuário",
 *     @OA\Xml(name="User"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="name", type="string", example="João"),
 *     @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", example="2022-01-01 12:00:00")
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function carteira(): HasOne
    {
        return $this->hasOne(Carteira::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserPerfil::class);
    }
}
