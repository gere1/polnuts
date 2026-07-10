<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">გვერდები</h2>
                <p class="text-sm text-slate-500 mt-0.5">საიტის გვერდები და მათი row-ები</p>
            </div>
            <a href="{{ route('admin.pages.create') }}" class="admin-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                ახალი გვერდი
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto">
        @if (session('status'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-sm">{{ session('status') }}</div>
        @endif

        <div class="mb-5">
            <a href="{{ route('admin.articles.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">→ სიახლეების მართვა</a>
        </div>

        @if ($pages->isEmpty())
            <div class="admin-card p-10 text-center text-slate-400 text-sm">გვერდები ჯერ არ არის. დაამატეთ პირველი.</div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($pages as $page)
                    <div class="admin-card admin-card-hover p-5">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-3">
                                <span class="h-10 w-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </span>
                                <div>
                                    <div class="font-semibold text-slate-900 flex items-center gap-2">
                                        {{ $page->title }}
                                        @if ($page->is_home)
                                            <span class="text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full font-medium">მთავარი</span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-slate-500 mt-0.5">/{{ $page->slug }} · {{ $page->rows_count }} რიგი</div>
                                </div>
                            </div>

                            <x-dropdown align="right" width="44">
                                <x-slot name="trigger">
                                    <button class="text-slate-400 hover:text-slate-700 p-1 -mr-1 -mt-1 rounded-lg hover:bg-slate-50">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link href="{{ route('admin.pages.edit', $page) }}">რედაქტირება</x-dropdown-link>
                                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" onsubmit="return confirm('წავშალო გვერდი?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="block w-full px-4 py-2 text-start text-sm text-red-600 hover:bg-red-50">წაშლა</button>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <a href="{{ route('admin.pages.builder', $page) }}" class="mt-4 inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700">
                            რიგების მართვა
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-admin-layout>
