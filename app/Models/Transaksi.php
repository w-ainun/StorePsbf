<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Payment;
use App\Models\TransaksiDetail;

class Transaksi extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaksi_id';

    protected $fillable = [
        'total_harga',
        'tanggal_transaksi',
        'payment_id',
        'status_transaksi',
        'user_id',
    ];

    /**
     * Relasi ke User (pembeli/penjual)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke Payment
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'transaksi_id', 'transaksi_id');
    }

    /**
     * Relasi ke TransaksiDetail (produk yang dibeli)
     */
    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id', 'transaksi_id');
    }
}
