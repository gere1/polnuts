<x-site-layout :title="$product->name . ' · ' . \App\Models\Setting::current()->site_name" :description="$product->excerpt" :image="$product->image ? asset('storage/' . $product->image) : null">
    <article class="py-16 px-4 max-w-3xl mx-auto">
        <a href="{{ localizedRoute('products.index') }}" class="text-sm text-brand hover:underline">{{ __('← ყველა პროდუქტი') }}</a>
        <h1 class="font-display italic text-4xl mt-4 mb-2">{{ $product->name }}</h1>
        @if (! is_null($product->price))
            <div class="text-xl text-indigo-600 font-semibold mb-6">{{ number_format((float) $product->price, 2) }} ₾</div>
        @endif
        @if ($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full rounded-lg mb-8 max-h-[420px] object-contain bg-gray-50 p-4">
        @endif
        <div class="prose max-w-none [&_*]:!text-inherit">{!! str_contains($product->body ?? '', '<') ? $product->body : nl2br(e($product->body)) !!}</div>
    </article>
</x-site-layout>
