<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderByDesc('published_at')->paginate(15);

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title'][Setting::current()->default_locale]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        Article::create($data);

        return redirect()->route('admin.articles.index')->with('status', 'სიახლე დაემატა.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $data = $this->validated($request, $article->id);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title'][Setting::current()->default_locale]);

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')->with('status', 'სიახლე განახლდა.');
    }

    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }
        $article->delete();

        return back()->with('status', 'სიახლე წაიშალა.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate(array_merge(
            translatableRules('title', ['string', 'max:255'], requiredInDefaultLocale: true),
            translatableRules('excerpt', ['string', 'max:1000']),
            translatableRules('body', ['string']),
            [
                'slug' => ['nullable', 'string', 'max:255', 'unique:articles,slug' . ($ignoreId ? ",{$ignoreId}" : '')],
                'published_at' => ['nullable', 'date'],
                'image' => ['nullable', 'image', 'max:8192'],
            ]
        ));
    }
}
