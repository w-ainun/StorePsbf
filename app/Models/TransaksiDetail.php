<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Transaksi;

class TransaksiDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaksi_detail_id';

    protected $fillable = [
        'product_id',
        'jumlah',
        'subtotal',
        'transaksi_id',
    ];

    /**
     * Relasi ke Produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    /**
     * Relasi ke Transaksi
     */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'transaksi_id');
    }
}
