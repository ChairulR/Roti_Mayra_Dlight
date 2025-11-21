<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Rating;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    /**
     * Model ini merepresentasikan tabel `users` di database.
     * 
     * Properti `$fillable` digunakan untuk menentukan atribut mana saja
     * yang dapat diisi secara mass-assignment (misalnya lewat `User::create()`).
     * 
     * Dengan mendefinisikan `$fillable`, kita mencegah *Mass Assignment Vulnerability*,
     * yaitu ketika user dapat mengubah kolom yang tidak seharusnya bisa diisi (misalnya `is_admin`).
     * ini bakal di gunakan untuk register di file 
     * @see \App\Http\Controllers\AuthController
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */

    
    /**
     * hidden di gunakan agar 2 field ini tidak di tambilkan saat kita menampilkan data user
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a regular user.
     *
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
