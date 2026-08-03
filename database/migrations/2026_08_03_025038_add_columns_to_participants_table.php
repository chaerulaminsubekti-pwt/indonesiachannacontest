<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            // Tambah kolom baru sesuai rencana (nama rencana berbeda dengan existing)
            if (! Schema::hasColumn('participants', 'nama_pemilik')) {
                $table->string('nama_pemilik', 255)->nullable()->after('event_class_id');
            }
            if (! Schema::hasColumn('participants', 'team_sf')) {
                $table->string('team_sf', 255)->nullable()->after('nama_pemilik');
            }
            if (! Schema::hasColumn('participants', 'no_hp')) {
                $table->string('no_hp', 20)->nullable()->after('kota_asal');
            }
            // fishin/fishout sudah ada, no_urut sudah ada
            // Pastikan status enum benar
            if (Schema::hasColumn('participants', 'status')) {
                // Cek apakah enum sudah benar, jika tidak skip
            }
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn(['nama_pemilik', 'team_sf', 'no_hp']);
        });
    }
};
