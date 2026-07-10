<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">სიახლეები</h2>
                <p class="text-sm text-slate-500 mt-0.5">საიტის ნიუს ფიდის მართვა</p>
            </div>
            <a href="{{ route('admin.articles.create') }}" class="admin-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                ახალი სიახლე
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto">
        @if (session('status'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-sm">{{ session('status') }}</div>
        @endif

        @if ($articles->isEmpty())
            <div class="admin-card p-10 text-center text-slate-400 text-sm">სიახლეები ჯერ არ არის.</div>
        @else
            <div class="space-y-3">
                @foreach ($articles as $article)
                    <div class="admin-card admin-card-hover p-4 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            @if ($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" class="w-16 h-12 object-cover rounded-lg shrink-0">
                            @else
                                <span class="h-12 w-16 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7a2 2 0 012-2 2 2 0 012 2v11a2 2 0 01-2 2zM9 8h4m-4 4h6m-6 4h6" /></svg>
                                </span>
                            @endif
                            <div>
                                <div class="font-semibold text-slate-900">{{ $article->title }}</div>
                                <div class="text-sm text-slate-500 mt-0.5">
                                    {{ $article->published_at?->format('Y-m-d') ?? 'გამოუქვეყნებელი' }}
                                </div>
                            </div>
                        </div>

                        <x-dropdown align="right" width="44">
                            <x-slot name="trigger">
                                <button class="text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-50">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link href="{{ route('admin.articles.edit', $article) }}">რედაქტირება</x-dropdown-link>
                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('წავშალო სიახლე?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="block w-full px-4 py-2 text-start text-sm text-red-600 hover:bg-red-50">წაშლა</button>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-4">{{ $articles->links() }}</div>
    </div>
</x-admin-layout>
