<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="uuid", type="string", example="152fc8c8-6eeb-48a7-94d1-1a3727ead99d"),
 *     @OA\Property(property="name", type="string", example="David Miller"),
 *     @OA\Property(property="email", type="string", format="email", example="devid@example.com")
 * )
 *  * @OA\Schema(
 *     schema="UserWithIban",
 *     type="object",
 *     @OA\Property(property="uuid", type="string", example="152fc8c8-6eeb-48a7-94d1-1a3727ead99d"),
 *     @OA\Property(property="name", type="string", example="David Miller"),
 *     @OA\Property(property="email", type="string", format="email", example="devid@example.com"),
 *     @OA\Property(property="iban", type="string", example="**********1234")
 * )
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'iban',
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

    protected static function boot()
    {
        parent::boot();

        // Generate a UUID before creating a new user
        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if user has the admin role
     */
    public function isAdmin()
    {
        return $this->roles()->where('name', 'admin')->exists(); 
    }
}
