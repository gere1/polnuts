<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">სიახლის რედაქტირება</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" class="admin-card p-6 space-y-5">
            @csrf @method('PUT')
            @include('admin.articles._form', ['article' => $article])
            <button class="admin-btn-primary">შენახვა</button>
        </form>
    </div>
</x-admin-layout>
