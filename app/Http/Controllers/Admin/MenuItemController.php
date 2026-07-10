<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function index()
    {
        $locale = Setting::current()->default_locale;
        $items = MenuItem::with('page')->orderBy('position')->get();
        $pages = Page::orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(title, '$.{$locale}'))")->get();

        return view('admin.menu.index', compact('items', 'pages'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $position = (int) MenuItem::max('position') + 1;

        MenuItem::create([
            'label' => $data['label'],
            'page_id' => $data['page_id'] ?: null,
            'url' => $data['page_id'] ? null : ($data['url'] ?? null),
            'file' => ! $data['page_id'] && $request->hasFile('file') ? $request->file('file')->store('menu', 'public') : null,
            'open_in_new_tab' => $request->boolean('open_in_new_tab'),
            'position' => $position,
        ]);

        return back()->with('status', 'მენიუს პუნქტი დაემატა.');
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $data = $this->validated($request);

        $file = $menuItem->file;

        if ($request->boolean('remove_file') && $file) {
            Storage::disk('public')->delete($file);
            $file = null;
        }

        if (! $data['page_id'] && $request->hasFile('file')) {
            if ($file) {
                Storage::disk('public')->delete($file);
            }
            $file = $request->file('file')->store('menu', 'public');
        }

        if ($data['page_id']) {
            if ($file) {
                Storage::disk('public')->delete($file);
            }
            $file = null;
        }

        $menuItem->update([
            'label' => $data['label'],
            'page_id' => $data['page_id'] ?: null,
            'url' => $data['page_id'] ? null : ($data['url'] ?? null),
            'file' => $file,
            'open_in_new_tab' => $request->boolean('open_in_new_tab'),
        ]);

        return back()->with('status', 'მენიუს პუნქტი განახლდა.');
    }

    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->file) {
            Storage::disk('public')->delete($menuItem->file);
        }
        $menuItem->delete();

        return back()->with('status', 'მენიუს პუნქტი წაიშალა.');
    }

    private function validated(Request $request): array
    {
        return $request->validate(array_merge(
            translatableRules('label', ['string', 'max:255'], requiredInDefaultLocale: true),
            [
                'page_id' => ['nullable', 'exists:pages,id'],
                'url' => ['nullable', 'string', 'max:255'],
                'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
                'open_in_new_tab' => ['nullable', 'boolean'],
            ]
        ));
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:menu_items,id'],
        ]);

        foreach ($data['ids'] as $index => $id) {
            MenuItem::where('id', $id)->update(['position' => $index]);
        }

        return response()->json(['status' => 'ok']);
    }
}
