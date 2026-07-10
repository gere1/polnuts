<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">ახალი პროდუქტი</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="admin-card p-6 space-y-5">
            @csrf
            @include('admin.products._form')
            <button class="admin-btn-primary">შექმნა</button>
        </form>
    </div>
</x-admin-layout>
