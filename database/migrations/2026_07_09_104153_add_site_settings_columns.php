<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->text('sambutan_ketua')->nullable()->after('teks_copyright');
            $table->string('foto_ketua')->nullable()->after('sambutan_ketua');
            $table->string('nama_ketua')->nullable()->after('foto_ketua');
            $table->string('teks_copyright_footer')->nullable()->after('nama_ketua');
            $table->string('email_kontak')->nullable()->after('email_pengirim_notifikasi');
            $table->string('no_wa_kontak')->nullable()->after('email_kontak');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['sambutan_ketua', 'foto_ketua', 'nama_ketua', 'teks_copyright_footer', 'email_kontak', 'no_wa_kontak']);
        });
    }
};
