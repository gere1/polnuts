@php
    $columns = (int) ($row->settings['columns'] ?? 1);
    $heroStyle = $row->sliderStyle();
    $heroHeight = $row->sliderHeightClass();
    $bare = $bare ?? false;
@endphp
@if ($row->items->count())
    <section class="relative {{ ($bare || ($columns <= 1 && $row->isFluid())) ? '' : 'px-4' }}" style="{{ ($columns > 1 && ! $bare) ? $row->styleAttr() : '' }}">
        <div class="{{ $columns > 1 ? 'max-w-7xl mx-auto' : '' }}">
            @if ($columns > 1)
                <x-animated-heading :row="$row" class="font-display italic text-3xl md:text-4xl text-center mb-2" />
            @endif
            @if ($columns > 1 && $row->subtitle)
                <p class="text-center opacity-70 mb-10">{{ $row->subtitle }}</p>
            @endif

            <div class="swiper hero-swiper-{{ $row->id }} {{ $columns > 1 ? 'swiper-pb-reserve' : '' }}">
                <div class="swiper-wrapper">
                    @foreach ($row->items as $item)
                        @if ($columns <= 1 && $heroStyle === 'left')
                            <div class="swiper-slide relative overflow-hidden {{ $heroHeight }} bg-gray-900">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="hero-media absolute inset-0 w-full h-full object-cover" data-effect="{{ $row->heroImageEffect() }}">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/25 to-transparent"></div>
                                @if ($item->title || $item->subtitle)
                                    <div class="hero-content absolute inset-0 flex flex-col items-start justify-end text-left text-white px-8 md:px-16 pb-14 md:pb-20" data-effect="{{ $row->heroTextEffect() }}">
                                        @if ($item->title)
                                            <h2 class="font-display italic text-3xl md:text-5xl mb-3 max-w-xl">{{ $item->title }}</h2>
                                        @endif
                                        @if ($item->subtitle)
                                            <p class="text-base md:text-lg max-w-md opacity-90">{{ $item->subtitle }}</p>
                                        @endif
                                        @if ($item->link)
                                            <a href="{{ $item->link }}" class="mt-5 inline-block px-5 py-2.5 border border-white/70 rounded-md font-medium hover:bg-white hover:text-gray-900 transition">გაიგე მეტი</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @elseif ($columns <= 1 && $heroStyle === 'right')
                            <div class="swiper-slide relative overflow-hidden {{ $heroHeight }} bg-gray-900">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="hero-media absolute inset-0 w-full h-full object-cover" data-effect="{{ $row->heroImageEffect() }}">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-l from-black/75 via-black/25 to-transparent"></div>
                                @if ($item->title || $item->subtitle)
                                    <div class="hero-content absolute inset-0 flex flex-col items-end justify-end text-right text-white px-8 md:px-16 pb-14 md:pb-20" data-effect="{{ $row->heroTextEffect() }}">
                                        @if ($item->title)
                                            <h2 class="font-display italic text-3xl md:text-5xl mb-3 max-w-xl">{{ $item->title }}</h2>
                                        @endif
                                        @if ($item->subtitle)
                                            <p class="text-base md:text-lg max-w-md opacity-90">{{ $item->subtitle }}</p>
                                        @endif
                                        @if ($item->link)
                                            <a href="{{ $item->link }}" class="mt-5 inline-block px-5 py-2.5 border border-white/70 rounded-md font-medium hover:bg-white hover:text-gray-900 transition">გაიგე მეტი</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @elseif ($columns <= 1 && $heroStyle === 'left-middle')
                            <div class="swiper-slide relative overflow-hidden {{ $heroHeight }} bg-gray-900">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="hero-media absolute inset-0 w-full h-full object-cover" data-effect="{{ $row->heroImageEffect() }}">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/25 to-transparent"></div>
                                @if ($item->title || $item->subtitle)
                                    <div class="hero-content absolute inset-0 flex flex-col items-start justify-center text-left text-white px-8 md:px-16" data-effect="{{ $row->heroTextEffect() }}">
                                        @if ($item->title)
                                            <h2 class="font-display italic text-3xl md:text-5xl mb-3 max-w-xl">{{ $item->title }}</h2>
                                        @endif
                                        @if ($item->subtitle)
                                            <p class="text-base md:text-lg max-w-md opacity-90">{{ $item->subtitle }}</p>
                                        @endif
                                        @if ($item->link)
                                            <a href="{{ $item->link }}" class="mt-5 inline-block px-5 py-2.5 border border-white/70 rounded-md font-medium hover:bg-white hover:text-gray-900 transition">გაიგე მეტი</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @elseif ($columns <= 1 && $heroStyle === 'right-middle')
                            <div class="swiper-slide relative overflow-hidden {{ $heroHeight }} bg-gray-900">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="hero-media absolute inset-0 w-full h-full object-cover" data-effect="{{ $row->heroImageEffect() }}">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-l from-black/75 via-black/25 to-transparent"></div>
                                @if ($item->title || $item->subtitle)
                                    <div class="hero-content absolute inset-0 flex flex-col items-end justify-center text-right text-white px-8 md:px-16" data-effect="{{ $row->heroTextEffect() }}">
                                        @if ($item->title)
                                            <h2 class="font-display italic text-3xl md:text-5xl mb-3 max-w-xl">{{ $item->title }}</h2>
                                        @endif
                                        @if ($item->subtitle)
                                            <p class="text-base md:text-lg max-w-md opacity-90">{{ $item->subtitle }}</p>
                                        @endif
                                        @if ($item->link)
                                            <a href="{{ $item->link }}" class="mt-5 inline-block px-5 py-2.5 border border-white/70 rounded-md font-medium hover:bg-white hover:text-gray-900 transition">გაიგე მეტი</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @elseif ($columns <= 1 && $heroStyle === 'split')
                            <div class="swiper-slide relative {{ $heroHeight }} bg-white">
                                <div class="flex flex-col md:flex-row {{ $row->textSide() === 'right' ? 'md:flex-row-reverse' : '' }} h-full">
                                    <div class="w-full md:w-1/2 h-1/2 md:h-full overflow-hidden">
                                        @if ($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="hero-media w-full h-full object-cover" data-effect="{{ $row->heroImageEffect() }}">
                                        @endif
                                    </div>
                                    <div class="hero-content w-full md:w-1/2 h-1/2 md:h-full flex flex-col items-start justify-center px-8 md:px-14 py-6" data-effect="{{ $row->heroTextEffect() }}" style="{{ $row->styleAttr() }}">
                                        @if ($item->title)
                                            <h2 class="font-display italic text-2xl md:text-4xl mb-3">{{ $item->title }}</h2>
                                        @endif
                                        @if ($item->subtitle)
                                            <p class="text-sm md:text-base opacity-80 max-w-sm">{{ $item->subtitle }}</p>
                                        @endif
                                        @if ($item->link)
                                            <a href="{{ $item->link }}" class="mt-5 inline-block px-5 py-2.5 bg-brand text-white rounded-md font-medium hover:opacity-90 transition">გაიგე მეტი</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @elseif ($columns <= 1 && $heroStyle === 'minimal')
                            <div class="swiper-slide relative overflow-hidden {{ $heroHeight }} bg-gray-900">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="hero-media absolute inset-0 w-full h-full object-cover" data-effect="{{ $row->heroImageEffect() }}">
                                @endif
                                @if ($item->title || $item->subtitle)
                                    <div class="absolute inset-x-0 bottom-8 flex justify-center px-6">
                                        <div class="hero-content bg-white/90 backdrop-blur rounded-full px-6 py-3 text-center shadow-lg max-w-xl" data-effect="{{ $row->heroTextEffect() }}">
                                            @if ($item->title)
                                                <span class="font-display italic text-lg md:text-xl text-gray-900">{{ $item->title }}</span>
                                            @endif
                                            @if ($item->subtitle)
                                                <span class="block text-xs text-gray-500 mt-0.5">{{ $item->subtitle }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                @if ($item->link)
                                    <a href="{{ $item->link }}" class="absolute inset-0" aria-label="{{ $item->title }}"></a>
                                @endif
                            </div>
                        @elseif ($columns <= 1)
                            <div class="swiper-slide relative overflow-hidden {{ $heroHeight }} bg-gray-900">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="hero-media absolute inset-0 w-full h-full object-cover" data-effect="{{ $row->heroImageEffect() }}">
                                @endif
                                <div class="absolute inset-0 bg-black/30"></div>
                                @if ($item->title || $item->subtitle)
                                    <div class="hero-content absolute inset-0 flex flex-col items-center justify-center text-center text-white px-6" data-effect="{{ $row->heroTextEffect() }}">
                                        @if ($item->title)
                                            <h2 class="font-display italic text-4xl md:text-6xl mb-4 drop-shadow-lg max-w-4xl">{{ $item->title }}</h2>
                                        @endif
                                        @if ($item->subtitle)
                                            <p class="text-lg md:text-xl max-w-2xl drop-shadow">{{ $item->subtitle }}</p>
                                        @endif
                                        @if ($item->link)
                                            <a href="{{ $item->link }}" class="mt-6 inline-block px-6 py-3 bg-white text-gray-900 rounded-md font-medium hover:bg-gray-100">გაიგე მეტი</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="swiper-slide">
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden h-full">
                                    @if ($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-44 object-cover">
                                    @endif
                                    <div class="p-5">
                                        @if ($item->title)
                                            <h3 class="font-semibold text-lg mb-1">{{ $item->title }}</h3>
                                        @endif
                                        @if ($item->subtitle)
                                            <p class="text-sm text-gray-600 mb-2">{{ $item->subtitle }}</p>
                                        @endif
                                        @if ($item->link)
                                            <a href="{{ $item->link }}" class="text-sm text-brand font-medium hover:underline">გაიგე მეტი</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                @if ($row->items->count() > 1)
                    <div class="swiper-button-prev {{ $columns <= 1 ? '!text-white' : '' }}"></div>
                    <div class="swiper-button-next {{ $columns <= 1 ? '!text-white' : '' }}"></div>
                @endif
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        new Swiper('.hero-swiper-{{ $row->id }}', {
            @if ($columns <= 1)
                loop: {{ $row->items->count() > 1 ? 'true' : 'false' }},
                autoplay: { delay: 5000, disableOnInteraction: false },
                effect: 'fade',
            @else
                loop: {{ $row->items->count() > $columns ? 'true' : 'false' }},
                autoplay: { delay: 3500, disableOnInteraction: false },
                effect: 'slide',
                spaceBetween: 24,
                slidesPerView: 1,
                breakpoints: {
                    640: { slidesPerView: {{ min(2, $columns) }} },
                    1024: { slidesPerView: {{ $columns }} },
                },
            @endif
            pagination: { el: '.hero-swiper-{{ $row->id }} .swiper-pagination', clickable: true },
            navigation: { nextEl: '.hero-swiper-{{ $row->id }} .swiper-button-next', prevEl: '.hero-swiper-{{ $row->id }} .swiper-button-prev' },
        });
    </script>
    @endpush
@endif
