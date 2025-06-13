<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Transaksi;
use App\Models\Product;
use Filament\Panel; // Penting: Import kelas Filament\Panel

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'roles', // Pastikan kolom 'roles' ada di tabel 'users'
        'alamat',
        'no_telp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Properti ini digunakan oleh Filament untuk redirect setelah login
    protected $redirects = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Atur redirect berdasarkan role pengguna
        // Penting: Gunakan $this->attributes['roles'] karena $this->roles mungkin belum terisi penuh di konstruktor.
        if (isset($this->attributes['roles'])) {
            if ($this->attributes['roles'] === 'penjual') {
                $this->redirects['login'] = '/seller'; // Redirect penjual ke /seller
            } elseif ($this->attributes['roles'] === 'pembeli') {
                $this->redirects['login'] = '/user'; // Redirect pembeli ke /user
            }
            // Tambahkan role lain jika diperlukan, misal admin
            // elseif ($this->attributes['roles'] === 'admin') {
            //     $this->redirects['login'] = '/admin';
            // }
        }
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getFilamentName(): string
    {
        return $this->name ?? $this->email ?? 'Pengguna Tidak Dikenal';
    }

    /**
     * Determine if the user can access a Filament panel.
     *
     * @param \Filament\Panel $panel
     * @return bool
     */
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // Izinkan akses ke panel 'seller' hanya untuk role 'penjual'
        if ($panel->getId() === 'seller') {
            return $this->roles === 'penjual';
        }

        // Izinkan akses ke panel 'user' hanya untuk role 'pembeli'
        if ($panel->getId() === 'user') {
            return $this->roles === 'pembeli';
        }

        // Contoh: Izinkan akses ke panel 'admin' hanya untuk role 'admin'
        if ($panel->getId() === 'admin') {
            return $this->roles === 'admin';
        }

        // Tolak akses untuk panel lain secara default
        return false;
    }

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'user_id', 'id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'user_id', 'id');
    }
}