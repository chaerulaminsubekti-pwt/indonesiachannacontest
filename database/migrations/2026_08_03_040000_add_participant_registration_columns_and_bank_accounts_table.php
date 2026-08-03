<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Perluas kolom peserta sesuai rencana (idempotent: hanya tambah yang belum ada)
        Schema::table('participants', function (Blueprint $table) {
            if (! Schema::hasColumn('participants', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('event_class_id')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('participants', 'nama_pemilik')) {
                $table->string('nama_pemilik', 255)->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('participants', 'team_sf')) {
                $table->string('team_sf', 255)->nullable()->after('nama_pemilik');
            }
            if (! Schema::hasColumn('participants', 'nama_ikan')) {
                $table->string('nama_ikan', 255)->nullable()->after('team_sf');
            }
            if (! Schema::hasColumn('participants', 'jenis_ikan')) {
                $table->string('jenis_ikan', 255)->nullable()->after('nama_ikan');
            }
            if (! Schema::hasColumn('participants', 'kota_asal')) {
                $table->string('kota_asal', 255)->nullable()->after('jenis_ikan');
            }
            if (! Schema::hasColumn('participants', 'alamat')) {
                $table->string('alamat', 500)->nullable()->after('kota_asal');
            }
            if (! Schema::hasColumn('participants', 'no_hp')) {
                $table->string('no_hp', 20)->nullable()->after('alamat');
            }
            if (! Schema::hasColumn('participants', 'status')) {
                $table->string('status', 30)->default('menunggu_verifikasi')->after('no_hp');
            }
            if (! Schema::hasColumn('participants', 'bukti_pembayaran')) {
                $table->string('bukti_pembayaran', 255)->nullable()->after('status');
            }
            if (! Schema::hasColumn('participants', 'biaya')) {
                $table->decimal('biaya', 12, 2)->nullable()->after('bukti_pembayaran');
            }
            if (! Schema::hasColumn('participants', 'fishin')) {
                $table->boolean('fishin')->default(false)->after('biaya');
            }
            if (! Schema::hasColumn('participants', 'fishout')) {
                $table->boolean('fishout')->default(false)->after('fishin');
            }
        });

        // 2) Buat tabel rekening pembayaran
        if (! Schema::hasTable('bank_accounts')) {
            Schema::create('bank_accounts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained()->cascadeOnDelete();
                $table->string('nama_bank', 100);
                $table->string('nomor_rekening', 50);
                $table->string('atas_nama', 100);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $drop = ['user_id', 'nama_pemilik', 'team_sf', 'nama_ikan', 'jenis_ikan',
                'kota_asal', 'alamat', 'no_hp', 'status', 'bukti_pembayaran',
                'biaya', 'fishin', 'fishout'];

            foreach ($drop as $column) {
                if (Schema::hasColumn('participants', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::dropIfExists('bank_accounts');
    }
};
