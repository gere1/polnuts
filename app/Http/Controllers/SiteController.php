<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Page;
use App\Models\Product;

class SiteController extends Controller
{
    public function home()
    {
        $page = Page::where('is_home', true)->first();

        if (! $page) {
            $page = Page::orderBy('id')->first();
        }

        if (! $page) {
            return view('site.no-page');
        }

        return $this->render($page);
    }

    public function show(Page $page)
    {
        return $this->render($page);
    }

    public function newsIndex()
    {
        $articles = Article::published()->orderByDesc('published_at')->paginate(9);

        return view('site.news-index', compact('articles'));
    }

    public function newsShow(Article $article)
    {
        return view('site.news-show', compact('article'));
    }

    public function productsIndex()
    {
        $products = Product::ordered()->paginate(12);

        return view('site.products-index', compact('products'));
    }

    public function productsShow(Product $product)
    {
        return view('site.products-show', compact('product'));
    }

    private function render(Page $page)
    {
        $rows = $page->displayRows();

        return view('site.page', compact('page', 'rows'));
    }
}
