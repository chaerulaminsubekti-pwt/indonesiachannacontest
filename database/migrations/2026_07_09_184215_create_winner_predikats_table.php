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
        Schema::create('winner_predikats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_class_id')->constrained('event_classes')->cascadeOnDelete();
            $table->string('nama_predikat'); // Juara 1, Juara 2, Grand Champion Marulioder, etc.
            $table->integer('urutan')->default(0); // untuk sorting
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('winner_predikats');
    }
};
