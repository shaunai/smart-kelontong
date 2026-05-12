<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'store_id', // Ditambahkan untuk arsitektur multi-tenant
        'name',
        'username', // Menggantikan email
        'password',
        'role',     // Ditambahkan untuk membedakan owner/cashier
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            // 'email_verified_at' => 'datetime', // Dihapus karena menggunakan username
            'password' => 'hashed', // Biarkan ini agar password otomatis terenkripsi
        ];
    }

    // --- RELASI DATABASE ---

    /**
     * Relasi ke toko (Setiap user terikat pada 1 toko)
     */
    public function store() 
    { 
        return $this->belongsTo(Store::class); 
    }

    /**
     * Relasi ke transaksi (1 kasir bisa menangani banyak penjualan)
     */
    public function sales() 
    { 
        return $this->hasMany(Sale::class); 
    }
}
