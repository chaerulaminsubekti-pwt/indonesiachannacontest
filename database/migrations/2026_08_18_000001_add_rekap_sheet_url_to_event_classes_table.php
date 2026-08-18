<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_classes', function (Blueprint $table) {
            if (! Schema::hasColumn('event_classes', 'rekap_sheet_url')) {
                $table->text('rekap_sheet_url')->nullable()->after('harga_tiket');
            }
        });
    }

    public function down(): void
    {
        Schema::table('event_classes', function (Blueprint $table) {
            if (Schema::hasColumn('event_classes', 'rekap_sheet_url')) {
                $table->dropColumn('rekap_sheet_url');
            }
        });
    }
};
