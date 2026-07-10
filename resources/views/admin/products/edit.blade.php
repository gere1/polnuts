<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">პროდუქტის რედაქტირება</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="admin-card p-6 space-y-5">
            @csrf @method('PUT')
            @include('admin.products._form', ['product' => $product])
            <button class="admin-btn-primary">შენახვა</button>
        </form>
    </div>
</x-admin-layout>
