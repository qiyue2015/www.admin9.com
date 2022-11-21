<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JetBrains\PhpStorm\ArrayShape;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

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

    /**
     * @param $device
     * @param  array  $abilities
     * @return array
     */
    #[ArrayShape(['type' => "string", 'token' => "mixed"])]
    public function createDeviceToken($device = null, array $abilities = ['*']): array
    {
        $expiresAt = now()->addMinutes(config('sanctum.expiration'));
        $textToken = $this->createToken($device ?? 'web', $abilities, $expiresAt)->plainTextToken;
        $accessToken = last(explode('|', $textToken));
        return [
            'type' => 'bearer',
            'token' => $accessToken,
        ];
    }
}
