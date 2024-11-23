<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements HasAvatar, JWTSubject 
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar_url',
        'created_by',
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

    // Mengimplementasikan metode dari kontrak JWTSubject
    public function getJWTIdentifier()
    {
        // Biasanya, ini akan mengembalikan ID pengguna
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        // Biasanya, Anda bisa menambahkan klaim khusus yang ingin dimasukkan ke dalam token JWT
        return [];
    }    

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url("$this->avatar_url") : null;
    }
    
    /**
     * tampilkan nama user di kolom created_by pda table user 
     */    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     *  Mengatur nilai default untuk avatar_url pada saat proses create user di dashboard
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {            
            if (empty($user->avatar_url)) {
                $user->avatar_url = 'avatars/default.jpg'; // Set avatar_url dengan default jika tidak ada nilai
            }
        });
    }    
}
