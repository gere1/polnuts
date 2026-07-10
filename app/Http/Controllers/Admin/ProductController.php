<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::ordered()->get();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name'][Setting::current()->default_locale]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('status', 'პროდუქტი დაემატა.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, $product->id);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name'][Setting::current()->default_locale]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('status', 'პროდუქტი განახლდა.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return back()->with('status', 'პროდუქტი წაიშალა.');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:products,id'],
        ]);

        foreach ($data['ids'] as $index => $id) {
            Product::where('id', $id)->update(['position' => $index]);
        }

        return response()->json(['status' => 'ok']);
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate(array_merge(
            translatableRules('name', ['string', 'max:255'], requiredInDefaultLocale: true),
            translatableRules('excerpt', ['string', 'max:1000']),
            translatableRules('body', ['string']),
            [
                'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug' . ($ignoreId ? ",{$ignoreId}" : '')],
                'price' => ['nullable', 'numeric', 'min:0'],
                'position' => ['nullable', 'integer', 'min:0'],
                'image' => ['nullable', 'image', 'max:8192'],
            ]
        ));
    }
}
