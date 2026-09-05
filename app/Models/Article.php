<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Article extends Model
{
    public const KATEGORI = ['Panduan', 'Liputan Event', 'Berita', 'Tips'];

    protected $fillable = [
        'user_id', 'judul', 'slug', 'kategori', 'ringkasan',
        'isi', 'gambar_sampul', 'status', 'published_at', 'views',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Article $article) {
            if (blank($article->slug)) {
                $article->slug = static::uniqueSlug($article->judul);
            }
        });

        static::updating(function (Article $article) {
            if ($article->isDirty('judul') && blank($article->getOriginal('slug'))) {
                $article->slug = static::uniqueSlug($article->judul);
            }
        });
    }

    public static function uniqueSlug(string $judul, ?int $ignoreId = null): string
    {
        $base = Str::slug($judul) ?: 'artikel';
        $slug = $base;
        $counter = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ArticleComment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(ArticleComment::class)->where('status', 'approved')->latest();
    }
}
