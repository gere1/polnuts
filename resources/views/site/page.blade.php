<x-site-layout :title="$page->title . ' · ' . \App\Models\Setting::current()->site_name" :description="$page->meta_description">
    @php
        $rowCount = $rows->count();
        $groupColsClass = ['2' => 'md:grid-cols-2', '3' => 'md:grid-cols-3', '4' => 'md:grid-cols-4'];
    @endphp
    @for ($i = 0; $i < $rowCount; $i++)
        @php
            $row = $rows[$i];
            $groupSize = min($row->groupSize(), $rowCount - $i);
        @endphp
        @if ($groupSize > 1)
            @php $shared = $row->hasSharedGroupBackground(); @endphp
            <section class="{{ $shared ? 'px-4' : '' }}" style="{{ $shared ? $row->groupBackgroundStyle() : '' }}">
                <div class="max-w-7xl mx-auto {{ $shared ? '' : 'px-4' }} grid grid-cols-1 {{ $groupColsClass[(string) $groupSize] ?? 'md:grid-cols-2' }} gap-8 items-stretch">
                    @for ($j = 0; $j < $groupSize; $j++)
                        @php $member = $rows[$i + $j]; @endphp
                        <div>@include('site.rows.' . $member->type, ['row' => $member, 'bare' => $shared])</div>
                    @endfor
                </div>
            </section>
            @php $i += $groupSize - 1; @endphp
        @else
            @include('site.rows.' . $row->type, ['row' => $row])
        @endif
    @endfor
</x-site-layout>
