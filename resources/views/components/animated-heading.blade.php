@props(['row', 'tag' => 'h2'])

@php
    $animation = $row->textAnimation();
    $rotating = $animation === 'rotate' ? $row->rotatingTexts() : [];
    $display = $animation === 'rotate' ? ($rotating[0] ?? '') : $row->title;
    // typewriter/rotate rewrite their own text over time — putting data-text-anim on an inner
    // inline-block span (rather than the outer block-level heading) keeps the heading's own box
    // and alignment completely untouched, so only that inner span's fixed-width box is what the
    // JS resizes/retypes, and it still centers correctly via the heading's own text-align.
    $needsInnerSpan = in_array($animation, ['typewriter', 'rotate'], true);
@endphp

@if ($display)
    <{{ $tag }} {{ $attributes }}
        @if ($animation !== 'none' && ! $needsInnerSpan) data-text-anim="{{ $animation }}" @endif
    >@if ($needsInnerSpan)<span
            data-text-anim="{{ $animation }}"
            @if ($animation === 'rotate' && count($rotating) > 1) data-text-rotate="{{ json_encode($rotating, JSON_UNESCAPED_UNICODE) }}" @endif
        >{{ $display }}</span>@else{{ $display }}@endif</{{ $tag }}>
@endif
