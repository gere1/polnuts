<x-site-layout :title="__('სიახლეები') . ' · ' . \App\Models\Setting::current()->site_name">
    <section class="py-16 px-4 max-w-7xl mx-auto">
        <h1 class="font-display italic text-4xl text-center mb-10">{{ __('სიახლეები') }}</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @forelse ($articles as $article)
                <a href="{{ localizedRoute('news.show', $article) }}" class="group block bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition border">
                    @if ($article->image)
                        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                    @endif
                    <div class="p-5">
                        <div class="text-xs text-gray-400 mb-2">{{ $article->published_at?->format('Y-m-d') }}</div>
                        <h3 class="font-semibold text-lg mb-2">{{ $article->title }}</h3>
                        @if ($article->excerpt)
                            <p class="text-sm text-gray-600 line-clamp-3">{{ $article->excerpt }}</p>
                        @endif
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center text-gray-500">{{ __('სიახლეები ჯერ არ არის.') }}</p>
            @endforelse
        </div>

        <div class="mt-10">{{ $articles->links() }}</div>
    </section>
</x-site-layout>
