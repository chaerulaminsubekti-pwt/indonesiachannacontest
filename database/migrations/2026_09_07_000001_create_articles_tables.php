<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('kategori')->default('Berita');
            $table->string('ringkasan', 500)->nullable();
            $table->longText('isi');
            $table->string('gambar_sampul')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index('kategori');
        });

        Schema::create('article_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->string('nama', 100);
            $table->string('email');
            $table->text('isi');
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index(['article_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_comments');
        Schema::dropIfExists('articles');
    }
};
