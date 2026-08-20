<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_sf_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tipe')->default('team');
            $table->string('nama');
            $table->string('pic_name');
            $table->string('pic_wa');
            $table->boolean('pernyataan_sanggup')->default(false);
            $table->string('signature_path')->nullable();
            $table->string('status')->default('menunggu_verifikasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_sf_registrations');
    }
};
