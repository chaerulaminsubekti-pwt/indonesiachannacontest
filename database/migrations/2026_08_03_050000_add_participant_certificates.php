<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jadikan winner_id nullable dan tambah participant_id untuk sertifikat peserta
        Schema::table('certificates', function (Blueprint $table) {
            if (! Schema::hasColumn('certificates', 'participant_id')) {
                $table->unsignedBigInteger('winner_id')->nullable()->change();
                $table->foreignId('participant_id')->nullable()->after('winner_id')->constrained()->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            if (Schema::hasColumn('certificates', 'participant_id')) {
                $table->dropConstrainedForeignId('participant_id');
            }
            $table->unsignedBigInteger('winner_id')->nullable(false)->change();
        });
    }
};
