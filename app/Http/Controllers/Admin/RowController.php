<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Row;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RowController extends Controller
{
    public function store(Request $request, Page $page)
    {
        $data = $request->validate([
            'type' => ['required', 'in:' . implode(',', Row::TYPES)],
        ]);

        $position = (int) $page->rows()->max('position') + 1;

        $row = $page->rows()->create([
            'type' => $data['type'],
            'position' => $position,
            'title' => match ($data['type']) {
                'slider' => 'ახალი სლაიდერი',
                'news' => 'სიახლეები',
                'text' => 'ახალი ტექსტი',
                'products' => 'პროდუქცია',
                'map' => 'რუკა',
                'form' => 'დაგვიკავშირდით',
                'image' => 'ახალი სურათები',
            },
            'settings' => match ($data['type']) {
                'news' => ['limit' => 3, 'columns' => 3],
                'products' => ['limit' => 4, 'columns' => 3, 'layout' => 'grid'],
                'map' => ['lat' => 39.4699, 'lng' => -0.3763, 'zoom' => 15],
                'image' => ['columns' => 1],
                default => ['columns' => 1],
            },
        ]);

        return redirect()->route('admin.rows.edit', $row)->with('status', 'რიგი დაემატა.');
    }

    public function edit(Row $row)
    {
        $row->load('items');
        $articles = $row->type === 'news' ? \App\Models\Article::orderByDesc('published_at')->get() : collect();
        $products = $row->type === 'products' ? \App\Models\Product::ordered()->get() : collect();
        $otherPages = Page::where('id', '!=', $row->page_id)->orderBy('title')->get();
        $sharedPageIds = $row->sharedPages()->pluck('pages.id')->toArray();

        return view('admin.rows.edit', compact('row', 'articles', 'products', 'otherPages', 'sharedPageIds'));
    }

    public function update(Request $request, Row $row)
    {
        $data = $request->validate(array_merge(
            translatableRules('title', ['string', 'max:255']),
            translatableRules('subtitle', ['string', 'max:255']),
            translatableRules('body', ['string']),
            [
            'group_size' => ['nullable', 'integer', 'in:1,2,3,4'],
            'group_shared_background' => ['nullable', 'boolean'],
            'group_background_color' => ['nullable', 'string', 'max:20'],
            'group_text_color' => ['nullable', 'string', 'max:20'],
            'group_background_gradient' => ['nullable', 'string', 'max:20'],
            'group_use_gradient' => ['nullable', 'boolean'],
            'group_gradient_angle' => ['nullable', 'integer', 'min:0', 'max:360'],
            'group_padding_top' => ['nullable', 'integer', 'min:0', 'max:300'],
            'group_padding_bottom' => ['nullable', 'integer', 'min:0', 'max:300'],
            'override_background' => ['nullable', 'boolean'],
            'background_color' => ['nullable', 'string', 'max:20'],
            'background_gradient' => ['nullable', 'string', 'max:20'],
            'use_gradient' => ['nullable', 'boolean'],
            'gradient_angle' => ['nullable', 'integer', 'min:0', 'max:360'],
            'background_image' => ['nullable', 'image', 'max:8192'],
            'remove_background_image' => ['nullable', 'boolean'],
            'text_color' => ['nullable', 'string', 'max:20'],
            'text_align' => ['nullable', 'in:left,center,right'],
            'padding_top' => ['nullable', 'integer', 'min:0', 'max:300'],
            'padding_bottom' => ['nullable', 'integer', 'min:0', 'max:300'],
            'columns' => ['nullable', 'integer', 'min:1', 'max:4'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:12'],
            'article_ids' => ['nullable', 'array'],
            'article_ids.*' => ['integer', 'exists:articles,id'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],
            'layout' => ['nullable', 'in:grid,list,carousel'],
            'card_style' => ['nullable', 'in:bordered,frameless,flip'],
            'image_height' => ['nullable', 'integer', 'min:100', 'max:600'],
            'show_all_link' => ['nullable', 'boolean'],
            'style' => ['nullable', 'in:center,left,right,minimal,split,left-middle,right-middle'],
            'height' => ['nullable', 'in:auto,small,medium,large,full'],
            'fluid' => ['nullable', 'boolean'],
            'text_side' => ['nullable', 'in:left,right'],
            'shared_page_ids' => ['nullable', 'array'],
            'shared_page_ids.*' => ['integer', 'exists:pages,id'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'zoom' => ['nullable', 'integer', 'min:1', 'max:20'],
            'text_animation' => ['nullable', 'in:' . implode(',', Row::TEXT_ANIMATIONS)],
            'hero_image_effect' => ['nullable', 'in:' . implode(',', Row::HERO_IMAGE_EFFECTS)],
            'hero_text_effect' => ['nullable', 'in:' . implode(',', Row::HERO_TEXT_EFFECTS)],
            ]
        ));

        $settings = $row->settings ?? [];
        $settings['group_size'] = (int) ($data['group_size'] ?? 1);
        $settings['group_shared_background'] = $request->boolean('group_shared_background');
        $settings['group_background_color'] = $data['group_background_color'] ?? null;
        $settings['group_text_color'] = $data['group_text_color'] ?? null;
        $settings['group_background_gradient'] = $request->boolean('group_use_gradient') ? ($data['group_background_gradient'] ?? null) : null;
        $settings['group_gradient_angle'] = $data['group_gradient_angle'] ?? 135;
        $settings['group_padding_top'] = $data['group_padding_top'] ?? 48;
        $settings['group_padding_bottom'] = $data['group_padding_bottom'] ?? 48;
        $settings['override_background'] = $request->boolean('override_background');
        $settings['background_color'] = $data['background_color'] ?? null;
        $settings['background_gradient'] = $request->boolean('use_gradient') ? ($data['background_gradient'] ?? null) : null;
        $settings['gradient_angle'] = $data['gradient_angle'] ?? 135;
        $settings['text_color'] = $data['text_color'] ?? null;
        $settings['text_align'] = $data['text_align'] ?? 'center';
        $settings['padding_top'] = $data['padding_top'] ?? 0;
        $settings['padding_bottom'] = $data['padding_bottom'] ?? 0;
        $settings['text_animation'] = $data['text_animation'] ?? 'none';

        if ($request->boolean('remove_background_image') && ! empty($settings['background_image'])) {
            Storage::disk('public')->delete($settings['background_image']);
            $settings['background_image'] = null;
        }
        if ($request->hasFile('background_image')) {
            if (! empty($settings['background_image'])) {
                Storage::disk('public')->delete($settings['background_image']);
            }
            $settings['background_image'] = $request->file('background_image')->store('rows', 'public');
        }

        $settings['columns'] = $data['columns'] ?? ($row->type === 'news' ? 3 : 1);

        if ($row->type === 'news') {
            $settings['limit'] = $data['limit'] ?? 3;
            $settings['article_ids'] = $data['article_ids'] ?? [];
        }
        if ($row->type === 'products') {
            $settings['limit'] = $data['limit'] ?? 4;
            $settings['product_ids'] = $data['product_ids'] ?? [];
            $settings['layout'] = $data['layout'] ?? 'grid';
            $settings['card_style'] = $data['card_style'] ?? 'bordered';
            $settings['image_height'] = $data['image_height'] ?? 288;
            $settings['show_all_link'] = $request->boolean('show_all_link');
        }
        if ($row->type === 'slider') {
            $settings['style'] = $data['style'] ?? 'center';
            $settings['height'] = $data['height'] ?? 'medium';
            $settings['fluid'] = $request->boolean('fluid');
            $settings['text_side'] = $data['text_side'] ?? 'left';
            $settings['hero_image_effect'] = $data['hero_image_effect'] ?? 'zoom-out';
            $settings['hero_text_effect'] = $data['hero_text_effect'] ?? 'fade-up';
        }
        if ($row->type === 'text') {
            $settings['height'] = $data['height'] ?? 'auto';
            $settings['fluid'] = $request->boolean('fluid');
            $settings['text_side'] = $data['text_side'] ?? 'left';
        }
        if ($row->type === 'map') {
            $settings['lat'] = $data['lat'] ?? 39.4699;
            $settings['lng'] = $data['lng'] ?? -0.3763;
            $settings['zoom'] = $data['zoom'] ?? 15;
        }

        $row->update([
            'title' => $data['title'] ?? null,
            'subtitle' => $data['subtitle'] ?? null,
            'body' => $data['body'] ?? null,
            'settings' => $settings,
        ]);

        $currentShared = $row->sharedPages()->pluck('pages.id')->toArray();
        $newShared = $data['shared_page_ids'] ?? [];

        $row->sharedPages()->detach(array_diff($currentShared, $newShared));

        foreach (array_diff($newShared, $currentShared) as $pageId) {
            $position = max(
                (int) Row::where('page_id', $pageId)->max('position'),
                (int) DB::table('page_row')->where('page_id', $pageId)->max('position')
            ) + 1;
            $row->sharedPages()->attach($pageId, ['position' => $position]);
        }

        return back()->with('status', 'რიგი განახლდა.');
    }

    public function destroy(Row $row)
    {
        $pageId = $row->page_id;
        $row->delete();

        return redirect()->route('admin.pages.builder', $pageId)->with('status', 'რიგი წაიშალა.');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:rows,id'],
            'page_id' => ['nullable', 'integer', 'exists:pages,id'],
        ]);

        foreach ($data['ids'] as $index => $id) {
            $row = Row::find($id);

            if ($data['page_id'] && $row->page_id !== (int) $data['page_id']) {
                $row->sharedPages()->updateExistingPivot($data['page_id'], ['position' => $index]);
            } else {
                $row->update(['position' => $index]);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function detach(Page $page, Row $row)
    {
        $page->sharedRows()->detach($row->id);

        return redirect()->route('admin.pages.builder', $page)->with('status', 'რიგი მოიხსნა ამ გვერდიდან.');
    }
}
