<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $page->title }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">გადაათრიეთ რიგები დასალაგებლად</p>
            </div>
            <a href="{{ route('admin.pages.index') }}" class="text-sm text-slate-500 hover:text-slate-800">← გვერდებზე დაბრუნება</a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto" x-data="{ addOpen: false }">
        @if (session('status'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-sm">{{ session('status') }}</div>
        @endif

        <ul id="rows-list" class="space-y-3 mb-6">
            @forelse ($rows as $row)
                @php $isShared = $row->page_id !== $page->id; @endphp
                <li data-id="{{ $row->id }}" class="admin-card admin-card-hover p-4 flex items-center gap-4 cursor-grab">
                    <svg class="w-5 h-5 text-slate-300 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M7 4a1 1 0 11-2 0 1 1 0 012 0zM7 10a1 1 0 11-2 0 1 1 0 012 0zM7 16a1 1 0 11-2 0 1 1 0 012 0zM15 4a1 1 0 11-2 0 1 1 0 012 0zM15 10a1 1 0 11-2 0 1 1 0 012 0zM15 16a1 1 0 11-2 0 1 1 0 012 0z" /></svg>
                    <span class="text-xs font-semibold uppercase px-2.5 py-1 rounded-full
                        @class([
                            'bg-purple-50 text-purple-700' => $row->type === 'slider',
                            'bg-amber-50 text-amber-700' => $row->type === 'news',
                            'bg-slate-100 text-slate-600' => $row->type === 'text',
                            'bg-teal-50 text-teal-700' => $row->type === 'products',
                            'bg-emerald-50 text-emerald-700' => $row->type === 'map',
                            'bg-rose-50 text-rose-700' => $row->type === 'form',
                            'bg-indigo-50 text-indigo-700' => $row->type === 'image',
                        ])">
                        @switch($row->type)
                            @case('slider') სლაიდერი @break
                            @case('news') სიახლეები @break
                            @case('text') ტექსტი @break
                            @case('products') პროდუქცია @break
                            @case('map') რუკა @break
                            @case('form') ფორმა @break
                            @case('image') სურათები @break
                        @endswitch
                    </span>
                    @if ($isShared)
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-sky-50 text-sky-700" title="ეს რიგი სხვა გვერდზეა შექმნილი და აქ საზიაროდაა დამატებული — რედაქტირება აისახება ყველგან">საზიარო</span>
                    @endif
                    <div class="flex-1">
                        <div class="font-medium text-slate-800">{{ $row->title ?: '(უსათაუროდ)' }}</div>
                        @php $rowColumns = (int) ($row->settings['columns'] ?? (in_array($row->type, ['news', 'products']) ? 3 : 1)); @endphp
                        @if ($row->type === 'slider' && $rowColumns > 1)
                            <div class="text-xs text-slate-400">{{ $row->items->count() }} სლაიდი ({{ $rowColumns }} ერთდროულად)</div>
                        @elseif ($row->type === 'slider')
                            <div class="text-xs text-slate-400">{{ $row->items->count() }} სლაიდი</div>
                        @elseif ($row->type === 'text' && $rowColumns > 1)
                            <div class="text-xs text-slate-400">{{ $row->items->count() }} სვეტი ({{ $rowColumns }} ერთ ხაზში)</div>
                        @elseif ($row->type === 'products')
                            <div class="text-xs text-slate-400">{{ ucfirst($row->layout()) }} · {{ $rowColumns }} სვეტი</div>
                        @elseif ($row->type === 'image')
                            <div class="text-xs text-slate-400">{{ $row->items->count() }} სურათი{{ $rowColumns > 1 ? " ({$rowColumns} ერთ ხაზში)" : '' }}</div>
                        @endif
                    </div>
                    <a href="{{ route('admin.rows.edit', $row) }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">რედაქტირება</a>
                    @if ($isShared)
                        <form action="{{ route('admin.pages.rows.detach', [$page, $row]) }}" method="POST" onsubmit="return confirm('მოვხსნა ეს რიგი ამ გვერდიდან? (რიგი თავად არ წაიშლება, სხვაგან დარჩება)');">
                            @csrf @method('DELETE')
                            <button class="text-sm text-slate-500 hover:text-slate-700">მოხსნა</button>
                        </form>
                    @else
                        <form action="{{ route('admin.rows.destroy', $row) }}" method="POST" onsubmit="return confirm('წავშალო რიგი?');">
                            @csrf @method('DELETE')
                            <button class="text-sm text-red-500 hover:text-red-700">წაშლა</button>
                        </form>
                    @endif
                </li>
            @empty
                <li class="admin-card p-10 text-center text-slate-400 text-sm">რიგები ჯერ არ არის დამატებული.</li>
            @endforelse
        </ul>

        <div class="relative inline-block">
            <button @click="addOpen = !addOpen" class="admin-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                რიგის დამატება
            </button>
            <div x-show="addOpen" @click.outside="addOpen = false" x-cloak class="absolute mt-2 admin-card w-56 overflow-hidden z-10 py-1">
                @foreach (['slider' => 'სლაიდერი', 'news' => 'სიახლეები', 'text' => 'ტექსტი', 'products' => 'პროდუქცია', 'map' => 'რუკა', 'form' => 'საკონტაქტო ფორმა', 'image' => 'სურათები'] as $type => $label)
                    <form action="{{ route('admin.rows.store', $page) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        <button class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">{{ $label }}</button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        const rowsList = document.getElementById('rows-list');
        if (rowsList) {
            Sortable.create(rowsList, {
                animation: 150,
                onEnd: function () {
                    const ids = Array.from(rowsList.children).map(li => li.dataset.id).filter(Boolean);
                    fetch('{{ route('admin.rows.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ ids, page_id: {{ $page->id }} }),
                    });
                },
            });
        }
    </script>
    @endpush
</x-admin-layout>
