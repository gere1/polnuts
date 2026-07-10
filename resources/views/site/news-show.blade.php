<x-site-layout :title="$article->title . ' · ' . \App\Models\Setting::current()->site_name" :description="$article->excerpt" :image="$article->image ? asset('storage/' . $article->image) : null">
    <article class="py-16 px-4 max-w-3xl mx-auto">
        <a href="{{ localizedRoute('news.index') }}" class="text-sm text-brand hover:underline">{{ __('← ყველა სიახლე') }}</a>
        <h1 class="font-display italic text-4xl mt-4 mb-2">{{ $article->title }}</h1>
        <div class="text-sm text-gray-400 mb-6">{{ $article->published_at?->format('Y-m-d') }}</div>
        @if ($article->image)
            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full rounded-lg mb-8 max-h-[420px] object-cover">
        @endif
        <div class="prose max-w-none [&_p]:mt-0 [&_p]:mb-0 [&_*]:!text-inherit">{!! str_contains($article->body ?? '', '<') ? $article->body : nl2br(e($article->body)) !!}</div>
    </article>
</x-site-layout>
