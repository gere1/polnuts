@php
    $productIds = $row->settings['product_ids'] ?? [];
    if (!empty($productIds)) {
        $products = \App\Models\Product::ordered()->whereIn('id', $productIds)
            ->orderByRaw('FIELD(id, ' . implode(',', array_map('intval', $productIds)) . ')')
            ->get();
    } else {
        $products = \App\Models\Product::ordered()->limit($row->itemLimit())->get();
    }
    $layout = $row->layout();
    $gridCols = ['2' => 'md:grid-cols-2', '3' => 'md:grid-cols-3', '4' => 'md:grid-cols-4'][(string) $row->columnCount()] ?? 'md:grid-cols-3';
@endphp
@php $bare = $bare ?? false; @endphp
@if ($products->count())
    <section class="{{ $bare ? '' : 'px-4' }}" style="{{ $bare ? (($groupTextColor ?? null) ? 'color:'.$groupTextColor : $row->textColorStyle()) : $row->styleAttr() }}">
        <div class="{{ $bare ? '' : 'max-w-7xl mx-auto' }}">
            <x-animated-heading :row="$row" class="font-display italic text-3xl md:text-4xl text-center mb-2" />
            @if ($row->subtitle)
                <p class="text-center opacity-70 mb-10">{{ $row->subtitle }}</p>
            @endif

            @if ($layout === 'list')
                <div class="max-w-5xl mx-auto divide-y divide-black/5 mt-6">
                    @foreach ($products as $product)
                        @php
                            $fullText = $product->excerpt
                                ? nl2br(e($product->excerpt))
                                : (str_contains($product->body ?? '', '<') ? $product->body : nl2br(e($product->body ?? '')));
                        @endphp
                        <a href="{{ localizedRoute('products.show', $product) }}" class="flex items-start gap-6 py-6 hover:opacity-80 transition">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="height: {{ $row->productImageHeight() }}px; width: {{ $row->productImageHeight() }}px" class="object-contain object-top rounded-md shrink-0">
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold leading-tight">{{ $product->name }}</h3>
                                @if (trim(strip_tags($fullText)))
                                    <div class="prose prose-sm max-w-none opacity-70 mt-1.5 !text-inherit [&_*]:!text-inherit">{!! $fullText !!}</div>
                                @endif
                            </div>
                            @if (! is_null($product->price))
                                <div class="font-semibold text-indigo-600 whitespace-nowrap">{{ number_format((float) $product->price, 2) }} ₾</div>
                            @endif
                        </a>
                    @endforeach
                </div>
            @elseif ($layout === 'carousel')
                <div class="swiper swiper-pb-reserve products-swiper-{{ $row->id }} mt-6">
                    <div class="swiper-wrapper">
                        @foreach ($products as $product)
                            @php
                                $fullText = $product->excerpt
                                    ? nl2br(e($product->excerpt))
                                    : (str_contains($product->body ?? '', '<') ? $product->body : nl2br(e($product->body ?? '')));
                            @endphp
                            <div class="swiper-slide h-auto">
                                <a href="{{ localizedRoute('products.show', $product) }}" class="block bg-white rounded-lg shadow-sm border border-black/5 overflow-hidden hover:shadow-md transition h-full">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-64 object-contain bg-gray-50 p-2">
                                    @endif
                                    <div class="p-4">
                                        <h3 class="font-semibold text-sm mb-1 text-gray-900">{{ $product->name }}</h3>
                                        @if (trim(strip_tags($fullText)))
                                            <div class="prose prose-sm max-w-none text-xs text-gray-500 [&_*]:!text-inherit">{!! $fullText !!}</div>
                                        @endif
                                        @if (! is_null($product->price))
                                            <div class="text-indigo-600 font-semibold text-sm mt-1">{{ number_format((float) $product->price, 2) }} ₾</div>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>

                @push('scripts')
                <script>
                    new Swiper('.products-swiper-{{ $row->id }}', {
                        loop: {{ $products->count() > $row->columnCount() ? 'true' : 'false' }},
                        autoplay: { delay: 3000, disableOnInteraction: false },
                        autoHeight: true,
                        spaceBetween: 20,
                        slidesPerView: 1,
                        breakpoints: {
                            640: { slidesPerView: {{ min(2, $row->columnCount()) }} },
                            1024: { slidesPerView: {{ $row->columnCount() }} },
                        },
                        pagination: { el: '.products-swiper-{{ $row->id }} .swiper-pagination', clickable: true },
                        navigation: { nextEl: '.products-swiper-{{ $row->id }} .swiper-button-next', prevEl: '.products-swiper-{{ $row->id }} .swiper-button-prev' },
                    });
                </script>
                @endpush
            @elseif ($row->cardStyle() === 'flip')
                <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridCols }} gap-6 mt-6">
                    @foreach ($products as $product)
                        @php
                            $fullText = $product->excerpt
                                ? nl2br(e($product->excerpt))
                                : (str_contains($product->body ?? '', '<') ? $product->body : nl2br(e($product->body ?? '')));
                        @endphp
                        <a href="{{ localizedRoute('products.show', $product) }}" class="product-flip-card block" style="height: {{ $row->productImageHeight() + 96 }}px">
                            <div class="product-flip-card-inner">
                                <div class="product-flip-card-front">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @endif
                                    <div class="product-flip-card-title-bar">
                                        <h3 class="font-semibold text-lg text-white">{{ $product->name }}</h3>
                                    </div>
                                </div>
                                <div class="product-flip-card-back">
                                    <h3 class="font-semibold text-lg mb-2 text-gray-900">{{ $product->name }}</h3>
                                    @if (trim(strip_tags($fullText)))
                                        <div class="product-flip-card-back-body prose prose-sm max-w-none text-gray-600">{!! $fullText !!}</div>
                                    @endif
                                    @if (! is_null($product->price))
                                        <div class="text-indigo-600 font-semibold mt-2">{{ number_format((float) $product->price, 2) }} ₾</div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @elseif ($row->cardStyle() === 'frameless')
                <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridCols }} gap-6 mt-6">
                    @foreach ($products as $product)
                        @php $summary = productSummary($product, 160); @endphp
                        <a href="{{ localizedRoute('products.show', $product) }}" class="group block text-center">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="height: {{ $row->productImageHeight() }}px" class="w-full object-contain rounded-full group-hover:scale-105 transition duration-300">
                            @endif
                            <div class="pt-4">
                                <h3 class="font-semibold text-lg mb-1">{{ $product->name }}</h3>
                                @if ($summary)
                                    <p class="text-sm opacity-70 mb-2">{!! nl2br(e($summary)) !!}</p>
                                @endif
                                @if (! is_null($product->price))
                                    <div class="text-indigo-600 font-semibold">{{ number_format((float) $product->price, 2) }} ₾</div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridCols }} gap-6 mt-6">
                    @foreach ($products as $product)
                        @php $summary = productSummary($product, 160); @endphp
                        <a href="{{ localizedRoute('products.show', $product) }}" class="group block bg-white rounded-lg shadow-sm border border-black/5 overflow-hidden hover:shadow-md transition">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="height: {{ $row->productImageHeight() }}px" class="w-full object-contain bg-gray-50 p-3 group-hover:scale-105 transition duration-300">
                            @endif
                            <div class="p-5">
                                <h3 class="font-semibold text-lg mb-1 text-gray-900">{{ $product->name }}</h3>
                                @if ($summary)
                                    <p class="text-sm text-gray-500 mb-2">{!! nl2br(e($summary)) !!}</p>
                                @endif
                                @if (! is_null($product->price))
                                    <div class="text-indigo-600 font-semibold">{{ number_format((float) $product->price, 2) }} ₾</div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($row->settings['show_all_link'] ?? true)
                <div class="text-center mt-10">
                    <a href="{{ localizedRoute('products.index') }}" class="inline-block px-6 py-3 border border-brand rounded-md text-sm font-medium hover:bg-brand hover:text-white transition">{{ __('ყველა პროდუქტი') }}</a>
                </div>
            @endif
        </div>
    </section>
@endif
