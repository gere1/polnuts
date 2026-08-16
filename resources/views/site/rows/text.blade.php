@php
    $align = $row->settings['text_align'] ?? 'center';
    $columns = (int) ($row->settings['columns'] ?? 1);
    $gridCols = ['2' => 'md:grid-cols-2', '3' => 'md:grid-cols-3', '4' => 'md:grid-cols-4'][(string) $columns] ?? 'md:grid-cols-3';
    $bare = $bare ?? false;
@endphp
@if ($columns <= 1)
    <section class="{{ $bare ? '' : 'px-4' }} {{ $bare ? 'h-full flex flex-col justify-center' : '' }}" style="{{ $bare ? (($groupTextColor ?? null) ? 'color:'.$groupTextColor : $row->textColorStyle()) : $row->styleAttr() }}">
        <div class="{{ $bare ? '' : 'max-w-5xl mx-auto' }} text-{{ $align }}">
            <x-animated-heading :row="$row" class="font-display italic text-3xl md:text-4xl mb-4" />
            @if ($row->subtitle)
                <p class="text-lg opacity-80 mb-6">{{ $row->subtitle }}</p>
            @endif
            @if ($row->body)
                <div class="prose prose-lg max-w-none text-{{ $align }} !text-inherit [&_p]:mt-0 [&_p]:mb-0 [&_*]:!text-inherit">{!! str_contains($row->body, '<') ? $row->body : nl2br(e($row->body)) !!}</div>
            @endif
        </div>
    </section>
@elseif ($row->items->count())
    <section class="{{ ($bare || $row->isFluid()) ? '' : 'px-4' }} {{ $row->textHeightClass() }} flex flex-col justify-center" style="{{ $bare ? (($groupTextColor ?? null) ? 'color:'.$groupTextColor : $row->textColorStyle()) : $row->styleAttr() }}">
        <div class="{{ $row->isFluid() ? 'w-full' : 'max-w-7xl mx-auto w-full' }}">
            <x-animated-heading :row="$row" class="font-display italic text-3xl md:text-4xl text-center mb-2 {{ $row->isFluid() ? 'px-4' : '' }}" />
            @if ($row->subtitle)
                <p class="text-center opacity-70 mb-10 {{ $row->isFluid() ? 'px-4' : '' }}">{{ $row->subtitle }}</p>
            @endif

            @if ($columns === 2)
                <div class="{{ $row->isFluid() ? 'flex-1 flex flex-col' : 'space-y-12' }}">
                    @foreach ($row->items as $item)
                        @continue(! $item->image && ! $item->title && ! $item->subtitle && ! $item->body && ! $item->link)
                        <div class="{{ $row->isFluid() ? 'flex-1' : '' }} flex flex-col md:flex-row {{ $row->textSide() === 'right' ? 'md:flex-row-reverse' : '' }} {{ $row->isFluid() ? 'items-stretch' : 'items-center gap-8 max-w-7xl mx-auto w-full px-4' }}">
                            <div class="w-full md:w-1/2">
                                @if ($item->image)
                                    @if ($item->image_height)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                             style="height:{{ $item->image_height }}px"
                                             class="w-auto max-w-full object-contain rounded-lg mx-auto">
                                    @else
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                             class="{{ $row->isFluid() ? 'w-full h-full min-h-[280px] object-cover block' : 'w-full h-auto rounded-lg' }}">
                                    @endif
                                @endif
                            </div>
                            <div class="w-full md:w-1/2 flex flex-col justify-center text-{{ $align }} {{ $row->isFluid() ? 'px-6 md:px-16 py-10' : '' }}">
                                @if ($item->title)
                                    <h3 class="font-semibold text-lg mb-1">{{ $item->title }}</h3>
                                @endif
                                @if ($item->subtitle)
                                    <p class="text-sm opacity-70 mb-2">{{ $item->subtitle }}</p>
                                @endif
                                @if ($item->body)
                                    <div class="prose prose-sm max-w-none {{ $row->isFluid() ? 'text-lg leading-relaxed' : 'text-sm opacity-90' }} !text-inherit [&_p]:mt-0 [&_p]:mb-0 [&_*]:!text-inherit">{!! str_contains($item->body, '<') ? $item->body : nl2br(e($item->body)) !!}</div>
                                @endif
                                @if ($item->link)
                                    <a href="{{ $item->link }}" class="inline-block mt-3 text-sm font-medium underline">გაიგე მეტი</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                @php
                    $displayColumns = min($columns, max($row->items->count(), 1));
                    $displayGridCols = ['2' => 'md:grid-cols-2', '3' => 'md:grid-cols-3', '4' => 'md:grid-cols-4'][(string) $displayColumns] ?? '';
                @endphp
                <div class="grid grid-cols-1 {{ $displayGridCols }} gap-8 {{ $row->isFluid() ? 'px-4' : '' }}">
                    @foreach ($row->items as $item)
                        <div class="text-{{ $align }}">
                            @if ($item->image)
                                @if ($item->image_height)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" style="height:{{ $item->image_height }}px" class="w-auto max-w-full object-contain rounded-lg mx-auto mb-4">
                                @else
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" style="height:160px" class="w-full object-cover rounded-lg mb-4">
                                @endif
                            @endif
                            @if ($item->title)
                                <h3 class="font-semibold text-lg mb-1">{{ $item->title }}</h3>
                            @endif
                            @if ($item->subtitle)
                                <p class="text-sm opacity-70 mb-2">{{ $item->subtitle }}</p>
                            @endif
                            @if ($item->body)
                                <div class="prose prose-sm max-w-none text-sm opacity-90 !text-inherit [&_p]:mt-0 [&_p]:mb-0 [&_*]:!text-inherit">{!! str_contains($item->body, '<') ? $item->body : nl2br(e($item->body)) !!}</div>
                            @endif
                            @if ($item->link)
                                <a href="{{ $item->link }}" class="inline-block mt-3 text-sm font-medium underline">გაიგე მეტი</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endif
