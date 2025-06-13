// database/migrations/YYYY_MM_DD_HHMMSS_add_remember_token_to_users_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom remember_token
            $table->rememberToken(); // Ini akan membuat kolom string(100) nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom remember_token jika migrasi di-rollback
            $table->dropColumn('remember_token');
        });
    }
};