<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransaksiDetail;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'nama_barang',
        'kategori',
        'stok',
        'harga',
        'deskripsi',
        'gambar'
    ];

    /**
     * Get the transaction details that include this product.
     */
    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class, 'product_id', 'product_id');
    }
}
