<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_classes', function (Blueprint $table) {
            $table->decimal('harga_tiket', 12, 2)->nullable()->after('nama_kelas');
        });
    }

    public function down(): void
    {
        Schema::table('event_classes', function (Blueprint $table) {
            $table->dropColumn('harga_tiket');
        });
    }
};
