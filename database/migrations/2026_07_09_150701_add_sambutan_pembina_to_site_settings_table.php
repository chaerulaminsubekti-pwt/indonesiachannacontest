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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('nama_pembina')->nullable()->after('teks_copyright');
            $table->string('jabatan_pembina')->nullable()->after('nama_pembina');
            $table->text('sambutan_pembina')->nullable()->after('jabatan_pembina');
            $table->string('foto_pembina')->nullable()->after('sambutan_pembina');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['nama_pembina', 'jabatan_pembina', 'sambutan_pembina', 'foto_pembina']);
        });
    }
};
