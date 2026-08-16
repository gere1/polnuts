@php
    $articleIds = $row->settings['article_ids'] ?? [];
    if (!empty($articleIds)) {
        $articles = \App\Models\Article::published()->whereIn('id', $articleIds)
            ->orderByRaw('FIELD(id, ' . implode(',', array_map('intval', $articleIds)) . ')')
            ->get();
    } else {
        $articles = \App\Models\Article::published()->orderByDesc('published_at')->limit($row->newsLimit())->get();
    }
    $gridCols = ['2' => 'md:grid-cols-2', '3' => 'md:grid-cols-3', '4' => 'md:grid-cols-4'][(string) $row->columnCount()] ?? 'md:grid-cols-3';
@endphp
@php $bare = $bare ?? false; @endphp
@if ($articles->count())
    <section class="{{ $bare ? '' : 'px-4' }}" style="{{ $bare ? (($groupTextColor ?? null) ? 'color:'.$groupTextColor : $row->textColorStyle()) : $row->styleAttr() }}">
        <div class="{{ $bare ? '' : 'max-w-7xl mx-auto' }}">
            <x-animated-heading :row="$row" class="font-display italic text-3xl md:text-4xl text-center mb-2" />
            @if ($row->subtitle)
                <p class="text-center opacity-70 mb-10">{{ $row->subtitle }}</p>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridCols }} gap-6 mt-6">
                @foreach ($articles as $article)
                    <a href="{{ localizedRoute('news.show', $article) }}" class="group block bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
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
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ localizedRoute('news.index') }}" class="inline-block px-6 py-3 border border-brand rounded-md text-sm font-medium hover:bg-brand hover:text-white transition">{{ __('ყველა სიახლე') }}</a>
            </div>
        </div>
    </section>
@endif
