<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">პროდუქტები</h2>
                <p class="text-sm text-slate-500 mt-0.5">პროდუქციის კატალოგის მართვა</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="admin-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                ახალი პროდუქტი
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto">
        @if (session('status'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-sm">{{ session('status') }}</div>
        @endif

        @if ($products->isEmpty())
            <div class="admin-card p-10 text-center text-slate-400 text-sm">პროდუქტები ჯერ არ არის.</div>
        @else
            <div class="space-y-3" id="products-list">
                @foreach ($products as $product)
                    <div data-id="{{ $product->id }}" class="admin-card admin-card-hover p-4 flex items-center justify-between cursor-grab">
                        <div class="flex items-center gap-4">
                            <svg class="w-5 h-5 text-slate-300 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M7 4a1 1 0 11-2 0 1 1 0 012 0zM7 10a1 1 0 11-2 0 1 1 0 012 0zM7 16a1 1 0 11-2 0 1 1 0 012 0zM15 4a1 1 0 11-2 0 1 1 0 012 0zM15 10a1 1 0 11-2 0 1 1 0 012 0zM15 16a1 1 0 11-2 0 1 1 0 012 0z" /></svg>
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-16 h-12 object-contain bg-gray-50 rounded-lg shrink-0 p-1">
                            @else
                                <span class="h-12 w-16 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                </span>
                            @endif
                            <div>
                                <div class="font-semibold text-slate-900">{{ $product->name }}</div>
                                <div class="text-sm text-slate-500 mt-0.5">
                                    @if (! is_null($product->price))
                                        {{ number_format((float) $product->price, 2) }} ₾
                                    @else
                                        ფასის გარეშე
                                    @endif
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
                                <x-dropdown-link href="{{ route('admin.products.edit', $product) }}">რედაქტირება</x-dropdown-link>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('წავშალო პროდუქტი?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="block w-full px-4 py-2 text-start text-sm text-red-600 hover:bg-red-50">წაშლა</button>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        const productsList = document.getElementById('products-list');
        if (productsList) {
            Sortable.create(productsList, {
                animation: 150,
                onEnd: function () {
                    const ids = Array.from(productsList.children).map(el => el.dataset.id).filter(Boolean);
                    fetch('{{ route('admin.products.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ ids }),
                    });
                },
            });
        }
    </script>
    @endpush
</x-admin-layout>
