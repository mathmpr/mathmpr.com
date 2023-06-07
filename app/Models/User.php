<?php

namespace App\Models;

namespace App\Models;

use App\Models\Auth\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @property $name
 * @property $email
 * @property $password
 * @property $remember_token
 * @property $token
 */
class User extends Auth
{
    use HasFactory, Notifiable, HasApiTokens;

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
     * @return string
     */
    public function generateToken(): string
    {
        return $this->createToken('LaravelAuthApp')->accessToken;
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

}
