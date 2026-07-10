<x-site-layout :title="__('პროდუქცია') . ' · ' . \App\Models\Setting::current()->site_name">
    <section class="py-16 px-4 max-w-7xl mx-auto">
        <h1 class="font-display italic text-4xl text-center mb-10">{{ __('პროდუქცია') }}</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @forelse ($products as $product)
                @php $summary = productSummary($product, 160); @endphp
                <a href="{{ localizedRoute('products.show', $product) }}" class="group block bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition border">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-72 object-contain bg-gray-50 p-3 group-hover:scale-105 transition duration-300">
                    @endif
                    <div class="p-5">
                        <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                        @if ($summary)
                            <p class="text-sm text-gray-600 mb-2">{!! nl2br(e($summary)) !!}</p>
                        @endif
                        @if (! is_null($product->price))
                            <div class="text-indigo-600 font-semibold">{{ number_format((float) $product->price, 2) }} ₾</div>
                        @endif
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center text-gray-500">{{ __('პროდუქტები ჯერ არ არის.') }}</p>
            @endforelse
        </div>

        <div class="mt-10">{{ $products->links() }}</div>
    </section>
</x-site-layout>
