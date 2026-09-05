<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        if (! Schema::hasTable('articles')) {
            return view('article.index', ['articles' => collect(), 'selectedKategori' => '']);
        }

        $query = Article::published()->with('author');

        if ($request->filled('kategori') && in_array($request->kategori, Article::KATEGORI, true)) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%'.$request->search.'%')
                    ->orWhere('ringkasan', 'like', '%'.$request->search.'%');
            });
        }

        $articles = $query->orderBy('published_at', 'desc')->paginate(12)->withQueryString();

        return view('article.index', [
            'articles' => $articles,
            'selectedKategori' => $request->query('kategori', ''),
            'search' => $request->query('search', ''),
        ]);
    }

    public function show(string $slug)
    {
        if (! Schema::hasTable('articles')) {
            abort(404);
        }

        $article = Article::published()
            ->with(['author', 'approvedComments'])
            ->where('slug', $slug)
            ->firstOrFail();

        $article->increment('views');

        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->where('kategori', $article->kategori)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        if ($related->count() < 3) {
            $related = $related->merge(
                Article::published()
                    ->where('id', '!=', $article->id)
                    ->whereNotIn('id', $related->pluck('id'))
                    ->orderBy('published_at', 'desc')
                    ->limit(3 - $related->count())
                    ->get()
            );
        }

        return view('article.show', compact('article', 'related'));
    }

    public function comment(Request $request, string $slug)
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'isi' => 'required|string|max:2000',
            'website' => 'prohibited',
        ], [
            'website.prohibited' => 'Komentar ditolak.',
        ]);

        unset($validated['website']);

        $article->comments()->create($validated + ['status' => 'pending']);

        return back()->with('comment_sent', 'Terima kasih! Komentar Anda akan tampil setelah disetujui admin.');
    }
}
