<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    private function makeArticle(array $overrides = []): Article
    {
        return Article::create(array_merge([
            'judul' => 'Panduan Merawat Channa',
            'slug' => 'panduan-merawat-channa-'.uniqid(),
            'kategori' => 'Panduan',
            'ringkasan' => 'Ringkasan singkat.',
            'isi' => '<p>Isi artikel.</p>',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ], $overrides));
    }

    public function test_index_lists_published_articles(): void
    {
        $this->makeArticle();
        $this->makeArticle(['judul' => 'Draft Saja', 'slug' => 'draft-'.uniqid(), 'status' => 'draft', 'published_at' => null]);

        $response = $this->get(route('artikel.index'));

        $response->assertOk();
        $response->assertSee('Panduan Merawat Channa', false);
        $response->assertDontSee('Draft Saja', false);
    }

    public function test_show_renders_published_article(): void
    {
        $article = $this->makeArticle();

        $response = $this->get(route('artikel.show', $article->slug));

        $response->assertOk();
        $response->assertSee($article->judul, false);
    }

    public function test_show_returns_404_for_draft_and_scheduled(): void
    {
        $draft = $this->makeArticle(['slug' => 'draf-'.uniqid(), 'status' => 'draft', 'published_at' => null]);
        $scheduled = $this->makeArticle(['slug' => 'jadwal-'.uniqid(), 'published_at' => now()->addDay()]);

        $this->get(route('artikel.show', $draft->slug))->assertNotFound();
        $this->get(route('artikel.show', $scheduled->slug))->assertNotFound();
    }

    public function test_category_filter_works(): void
    {
        $this->makeArticle();
        $this->makeArticle(['judul' => 'Liputan Kontes', 'slug' => 'liputan-'.uniqid(), 'kategori' => 'Liputan Event']);

        $response = $this->get(route('artikel.index', ['kategori' => 'Liputan Event']));

        $response->assertOk();
        $response->assertSee('Liputan Kontes', false);
        $response->assertDontSee('Panduan Merawat Channa', false);
    }

    public function test_comment_is_stored_as_pending(): void
    {
        $article = $this->makeArticle();

        $response = $this->post(route('artikel.comment', $article->slug), [
            'nama' => 'Budi',
            'email' => 'budi@example.com',
            'isi' => 'Artikel bagus!',
            'website' => '',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('article_comments', [
            'article_id' => $article->id,
            'nama' => 'Budi',
            'status' => 'pending',
        ]);
        $this->get(route('artikel.show', $article->slug))->assertDontSee('Artikel bagus!', false);
    }

    public function test_honeypot_rejects_spam(): void
    {
        $article = $this->makeArticle();

        $this->post(route('artikel.comment', $article->slug), [
            'nama' => 'Spammer',
            'email' => 'spam@example.com',
            'isi' => 'Beli obat murah!',
            'website' => 'http://spam.example.com',
        ])->assertSessionHasErrors('website');

        $this->assertDatabaseMissing('article_comments', ['nama' => 'Spammer']);
    }
}
