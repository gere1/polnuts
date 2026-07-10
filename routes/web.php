<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RowController;
use App\Http\Controllers\Admin\RowItemController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SiteController;
use App\Models\Article;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Laravel's optional route parameters only collapse correctly at the END of a URI,
// so "{locale?}/products" 404s when locale is omitted. Instead: the default locale
// (ka) gets its own unprefixed routes with the canonical names below, and the other
// locales get a second, prefixed registration under 'loc.'-prefixed names further down. Use the
// localizedRoute() helper (app/helpers.php) anywhere a link must follow the current
// locale.
Route::middleware('setlocale')->group(function () {
    Route::get('/', [SiteController::class, 'home'])->name('home');
    Route::get('/news', [SiteController::class, 'newsIndex'])->name('news.index');
    Route::get('/news/{article:slug}', [SiteController::class, 'newsShow'])->name('news.show');
    Route::get('/products', [SiteController::class, 'productsIndex'])->name('products.index');
    Route::get('/products/{product:slug}', [SiteController::class, 'productsShow'])->name('products.show');
    Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.submit');
});

// Mirrors the group above under /en, /de, /pl, /es. Laravel passes route parameters to
// controller methods positionally (in URI order), so reusing the same controller
// array-callable here would shift {locale} into the model-binding argument slot.
// Each closure keeps an explicit, correctly-named/typed $locale + model parameter
// (so implicit route-model binding still resolves the model by name) purely to
// preserve that positional order, then forwards to the real controller method.
Route::prefix('{locale}')->where(['locale' => 'ka|en|de|pl|es'])->middleware('setlocale')->name('loc.')->group(function () {
    Route::get('/', fn (string $locale) => app(SiteController::class)->home())->name('home');
    Route::get('/news', fn (string $locale) => app(SiteController::class)->newsIndex())->name('news.index');
    Route::get('/news/{article:slug}', fn (string $locale, Article $article) => app(SiteController::class)->newsShow($article))->name('news.show');
    Route::get('/products', fn (string $locale) => app(SiteController::class)->productsIndex())->name('products.index');
    Route::get('/products/{product:slug}', fn (string $locale, Product $product) => app(SiteController::class)->productsShow($product))->name('products.show');
    Route::post('/contact', fn (string $locale, Request $request) => app(ContactController::class)->store($request))->middleware('throttle:5,1')->name('contact.submit');
});

Route::get('/robots.txt', [SitemapController::class, 'robots']);
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap']);

Route::get('/dashboard', function () {
    return redirect()->route('admin.pages.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('pages', PageController::class);
    Route::get('pages/{page}/builder', [PageController::class, 'builder'])->name('pages.builder');

    Route::post('pages/{page}/rows', [RowController::class, 'store'])->name('rows.store');
    Route::get('rows/{row}/edit', [RowController::class, 'edit'])->name('rows.edit');
    Route::put('rows/{row}', [RowController::class, 'update'])->name('rows.update');
    Route::delete('rows/{row}', [RowController::class, 'destroy'])->name('rows.destroy');
    Route::post('rows/reorder', [RowController::class, 'reorder'])->name('rows.reorder');
    Route::delete('pages/{page}/rows/{row}', [RowController::class, 'detach'])->name('pages.rows.detach');

    Route::post('rows/{row}/items', [RowItemController::class, 'store'])->name('row-items.store');
    Route::put('row-items/{rowItem}', [RowItemController::class, 'update'])->name('row-items.update');
    Route::delete('row-items/{rowItem}', [RowItemController::class, 'destroy'])->name('row-items.destroy');
    Route::post('row-items/reorder', [RowItemController::class, 'reorder'])->name('row-items.reorder');

    Route::resource('articles', ArticleController::class);
    Route::resource('products', ProductController::class);
    Route::post('products/reorder', [ProductController::class, 'reorder'])->name('products.reorder');

    Route::get('menu', [MenuItemController::class, 'index'])->name('menu.index');
    Route::post('menu', [MenuItemController::class, 'store'])->name('menu.store');
    Route::put('menu/{menuItem}', [MenuItemController::class, 'update'])->name('menu.update');
    Route::delete('menu/{menuItem}', [MenuItemController::class, 'destroy'])->name('menu.destroy');
    Route::post('menu/reorder', [MenuItemController::class, 'reorder'])->name('menu.reorder');

    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';

// Catch-all for custom pages must stay last so it doesn't shadow the routes above.
Route::middleware('setlocale')->group(function () {
    Route::get('/{page:slug}', [SiteController::class, 'show'])->name('page.show');
});

Route::prefix('{locale}')->where(['locale' => 'ka|en|de|pl|es'])->middleware('setlocale')->name('loc.')->group(function () {
    Route::get('/{page:slug}', fn (string $locale, Page $page) => app(SiteController::class)->show($page))->name('page.show');
});
