<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function robots(): Response
    {
        $content = "User-agent: *\nDisallow: /admin\nDisallow: /login\n\nSitemap: " . url('/sitemap.xml') . "\n";

        return response($content, 200)->header('Content-Type', 'text/plain');
    }

    public function sitemap(): Response
    {
        $urls = [];

        foreach (Page::all() as $page) {
            $urls[] = [
                'loc' => $page->is_home ? url('/') : route('page.show', $page),
                'lastmod' => $page->updated_at,
            ];
        }

        foreach (Article::whereNotNull('published_at')->get() as $article) {
            $urls[] = ['loc' => route('news.show', $article), 'lastmod' => $article->updated_at];
        }

        foreach (Product::all() as $product) {
            $urls[] = ['loc' => route('products.show', $product), 'lastmod' => $product->updated_at];
        }

        $urls[] = ['loc' => route('news.index'), 'lastmod' => now()];
        $urls[] = ['loc' => route('products.index'), 'lastmod' => now()];

        $xml = view('sitemap', compact('urls'))->render();

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }
}
