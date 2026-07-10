@php
    $columns = $row->columnCount();
    $gridCols = ['2' => 'md:grid-cols-2', '3' => 'md:grid-cols-3', '4' => 'md:grid-cols-4'][(string) $columns] ?? 'md:grid-cols-1';
    $bare = $bare ?? false;
    $alignCls = ['left' => 'mr-auto', 'center' => 'mx-auto', 'right' => 'ml-auto'];
@endphp
@if ($row->items->count())
    <section class="{{ $bare ? '' : 'px-4' }}" style="{{ $bare ? '' : $row->styleAttr() }}">
        <div class="max-w-7xl mx-auto">
            <x-animated-heading :row="$row" class="font-display italic text-3xl md:text-4xl text-center mb-2" />
            @if ($row->subtitle)
                <p class="text-center opacity-70 mb-10">{{ $row->subtitle }}</p>
            @endif

            <div class="grid grid-cols-1 {{ $columns > 1 ? $gridCols : '' }} gap-8">
                @foreach ($row->items as $item)
                    @continue(! $item->image)
                    <div class="{{ $alignCls[$item->align] ?? 'mx-auto' }}" style="width:{{ $item->width }}%">
                        @if ($item->link)
                            <a href="{{ $item->link }}" class="block">
                                @if ($item->image_height)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" style="height:{{ $item->image_height }}px" class="w-full object-contain rounded-lg">
                                @else
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-auto rounded-lg">
                                @endif
                            </a>
                        @else
                            @if ($item->image_height)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" style="height:{{ $item->image_height }}px" class="w-full object-contain rounded-lg">
                            @else
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-auto rounded-lg">
                            @endif
                        @endif
                        @if ($item->title)
                            <p class="text-sm opacity-70 mt-2 text-center">{{ $item->title }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
