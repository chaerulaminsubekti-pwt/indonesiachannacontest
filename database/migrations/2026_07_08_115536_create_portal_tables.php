<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama_organisasi');
            $table->string('jabatan_pic')->nullable();
            $table->string('no_wa');
            $table->string('no_ktp')->nullable();
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nama_event');
            $table->string('slug')->unique();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('venue')->nullable();
            $table->string('kategori')->nullable();
            $table->string('tema')->nullable();
            $table->string('wilayah_kota')->nullable();
            $table->string('flyer')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('event_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('nama_kelas');
            $table->timestamps();
        });

        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_class_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nama_peserta');
            $table->string('no_urut')->nullable();
            $table->timestamps();
        });

        Schema::create('winners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_class_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nama_pemenang');
            $table->string('peringkat')->nullable();
            $table->timestamps();
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('winner_id')->constrained()->cascadeOnDelete();
            $table->string('nomor_sertifikat')->nullable();
            $table->string('file_path')->nullable();
            $table->string('kode_verifikasi')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('event_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('caption')->nullable();
            $table->timestamps();
        });

        Schema::create('icc_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('judul_album')->nullable();
            $table->string('file_path');
            $table->string('caption')->nullable();
            $table->date('tanggal')->nullable();
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organizer_id')->nullable()->constrained()->nullOnDelete();
            $table->text('isi_testimoni');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable();
            $table->string('gambar');
            $table->string('link')->nullable();
            $table->unsignedTinyInteger('urutan')->default(0);
            $table->boolean('status_aktif')->default(true);
            $table->date('tgl_mulai_tayang')->nullable();
            $table->date('tgl_selesai_tayang')->nullable();
            $table->timestamps();
        });

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->string('no_wa')->nullable();
            $table->string('email')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        Schema::create('organization_structures', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');
            $table->string('tipe')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('judges_lists', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');
            $table->string('tipe')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('regulations', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');
            $table->string('tipe')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo_header')->nullable();
            $table->string('favicon')->nullable();
            $table->string('nama_website')->nullable();
            $table->string('tagline')->nullable();
            $table->string('warna_primary')->nullable();
            $table->string('warna_secondary')->nullable();
            $table->text('alamat')->nullable();
            $table->string('link_instagram')->nullable();
            $table->string('link_facebook')->nullable();
            $table->string('link_youtube')->nullable();
            $table->string('link_tiktok')->nullable();
            $table->string('email_pengirim_notifikasi')->nullable();
            $table->string('teks_copyright')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('regulations');
        Schema::dropIfExists('judges_lists');
        Schema::dropIfExists('organization_structures');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('sliders');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('icc_galleries');
        Schema::dropIfExists('event_galleries');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('winners');
        Schema::dropIfExists('participants');
        Schema::dropIfExists('event_classes');
        Schema::dropIfExists('events');
        Schema::dropIfExists('organizers');
    }
};
