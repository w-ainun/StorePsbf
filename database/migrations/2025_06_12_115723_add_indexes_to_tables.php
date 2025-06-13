<?php

// database/migrations/YYYY_MM_DD_HHMMSS_add_indexes_to_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tambahkan indeks pada kategori dan user_id
            $table->index('kategori');
            // Jika Anda menambahkan user_id ke tabel products
            // $table->index('user_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            // Tambahkan indeks pada transaksi_id dan status_payment
            $table->index('transaksi_id');
            $table->index('status_payment');
        });

        Schema::table('transaksis', function (Blueprint $table) {
            // Tambahkan indeks pada user_id dan status_transaksi
            $table->index('user_id');
            $table->index('status_transaksi');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['kategori']);
            // $table->dropIndex(['user_id']); // Jika ditambahkan
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['transaksi_id']);
            $table->dropIndex(['status_payment']);
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status_transaksi']);
        });
    }
};