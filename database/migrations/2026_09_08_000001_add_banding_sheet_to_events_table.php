<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('banding_sheet_url')->nullable()->after('google_sheet_url');
            $table->string('banding_sheet_gid', 20)->nullable()->after('banding_sheet_url');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['banding_sheet_url', 'banding_sheet_gid']);
        });
    }
};
