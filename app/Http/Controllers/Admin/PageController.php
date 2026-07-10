<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $locale = Setting::current()->default_locale;
        $pages = Page::withCount('rows')->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(title, '$.{$locale}'))")->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(array_merge(
            translatableRules('title', ['string', 'max:255'], requiredInDefaultLocale: true),
            translatableRules('meta_description', ['string', 'max:500']),
            [
                'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug'],
                'is_home' => ['nullable', 'boolean'],
            ]
        ));

        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['title'][Setting::current()->default_locale]);
        $data['is_home'] = $request->boolean('is_home');

        if ($data['is_home']) {
            Page::query()->update(['is_home' => false]);
        }

        $page = Page::create($data);

        return redirect()->route('admin.pages.builder', $page)->with('status', 'გვერდი შეიქმნა.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate(array_merge(
            translatableRules('title', ['string', 'max:255'], requiredInDefaultLocale: true),
            translatableRules('meta_description', ['string', 'max:500']),
            [
                'slug' => ['required', 'string', 'max:255', 'unique:pages,slug,' . $page->id],
                'is_home' => ['nullable', 'boolean'],
            ]
        ));

        $data['is_home'] = $request->boolean('is_home');

        if ($data['is_home']) {
            Page::query()->where('id', '!=', $page->id)->update(['is_home' => false]);
        }

        $page->update($data);

        return redirect()->route('admin.pages.index')->with('status', 'გვერდი განახლდა.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')->with('status', 'გვერდი წაიშალა.');
    }

    public function builder(Page $page)
    {
        $rows = $page->displayRows();

        return view('admin.pages.builder', compact('page', 'rows'));
    }
}
