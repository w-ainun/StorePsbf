<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('id');
            $table->string('nama_barang');
            $table->enum('kategori', ['hp', 'laptop', 'printer', 'kamera']);
            $table->integer('stok');
            $table->integer('harga');
            $table->text('deskripsi');
            $table->binary('gambar')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
