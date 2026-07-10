<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Row;
use App\Models\RowItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RowItemController extends Controller
{
    public function store(Request $request, Row $row)
    {
        $data = $request->validate([
            'title.ka' => ['nullable', 'string', 'max:255'],
            'title.en' => ['nullable', 'string', 'max:255'],
            'title.de' => ['nullable', 'string', 'max:255'],
            'subtitle.ka' => ['nullable', 'string', 'max:1000'],
            'subtitle.en' => ['nullable', 'string', 'max:1000'],
            'subtitle.de' => ['nullable', 'string', 'max:1000'],
            'body.ka' => ['nullable', 'string'],
            'body.en' => ['nullable', 'string'],
            'body.de' => ['nullable', 'string'],
            'link' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:8192'],
            'image_height' => ['nullable', 'integer', 'min:20', 'max:800'],
            'align' => ['nullable', 'in:left,center,right'],
            'width' => ['nullable', 'integer', 'min:10', 'max:100'],
        ]);

        $position = (int) $row->items()->max('position') + 1;

        $row->items()->create([
            'position' => $position,
            'title' => $data['title'] ?? null,
            'subtitle' => $data['subtitle'] ?? null,
            'body' => $data['body'] ?? null,
            'link' => $data['link'] ?? null,
            'image' => $request->hasFile('image') ? $request->file('image')->store('rows', 'public') : null,
            'image_height' => $data['image_height'] ?? null,
            'align' => $data['align'] ?? 'center',
            'width' => $data['width'] ?? 100,
        ]);

        return back()->with('status', 'სლაიდი დაემატა.');
    }

    public function update(Request $request, RowItem $rowItem)
    {
        $data = $request->validate([
            'title.ka' => ['nullable', 'string', 'max:255'],
            'title.en' => ['nullable', 'string', 'max:255'],
            'title.de' => ['nullable', 'string', 'max:255'],
            'subtitle.ka' => ['nullable', 'string', 'max:1000'],
            'subtitle.en' => ['nullable', 'string', 'max:1000'],
            'subtitle.de' => ['nullable', 'string', 'max:1000'],
            'body.ka' => ['nullable', 'string'],
            'body.en' => ['nullable', 'string'],
            'body.de' => ['nullable', 'string'],
            'link' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:8192'],
            'image_height' => ['nullable', 'integer', 'min:20', 'max:800'],
            'align' => ['nullable', 'in:left,center,right'],
            'width' => ['nullable', 'integer', 'min:10', 'max:100'],
        ]);

        if ($request->hasFile('image')) {
            if ($rowItem->image) {
                Storage::disk('public')->delete($rowItem->image);
            }
            $data['image'] = $request->file('image')->store('rows', 'public');
        }

        $rowItem->update($data);

        return back()->with('status', 'სლაიდი განახლდა.');
    }

    public function destroy(RowItem $rowItem)
    {
        if ($rowItem->image) {
            Storage::disk('public')->delete($rowItem->image);
        }
        $rowItem->delete();

        return back()->with('status', 'სლაიდი წაიშალა.');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:row_items,id'],
        ]);

        foreach ($data['ids'] as $index => $id) {
            RowItem::where('id', $id)->update(['position' => $index]);
        }

        return response()->json(['status' => 'ok']);
    }
}
