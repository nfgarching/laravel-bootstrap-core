<!-- Listing listing.table.row1 -->

<tr>
    @if ($toggleSelect)
        <td align="center" nowrap width="10%">
            @include('core::listing.select.checkboxes')
            <div class="btn-group" role="group">
                <button.select keyid='{{ $row->id }}' />
            </div>
        </td>
    @endif
    <td align="right" width="10%">
        @php
            $showRoute = method_exists($row, 'getShowRoute')
                ? $row->getShowRoute()
                : Str::plural(Str::snake(class_basename($row))) . '.show';
        @endphp
        <x-link :href="Route::has($showRoute) ? route($showRoute, $row->id) : '#'">
            @if (method_exists($row, 'getIcon'))
                <x-icon :name="$row->getIcon()" size="lg" />
                {{-- @elseif (isset($row->icon))
            <x-icon :name="$row->icon" size="lg" /> --}}
            @else
                {{-- <span class="badge bg-secondary"> --}}
                    {{ $row->id }}
                {{-- </span> --}}
                {{-- <x-icon /> --}}
            @endif
        </x-link>
    </td>
    <td data-bs-toggle="collapse" data-bs-target="#collapse-{{ $token . '-' . $row->id }}" width="80%">
        @if (method_exists($row, 'getSuchbegriff'))
            {{ Str::limit($row->getSuchbegriff(), 60) }}
        @elseif (isset($row->buchungstext))
            {{ Str::limit($row->buchungstext, 60) }}
        @elseif (isset($row->subject))
            {{ Str::limit($row->subject, 60) }}
        @elseif (isset($row->suchbegriff))
            {{ Str::limit($row->suchbegriff, 60) }}
        @elseif (isset($row->term))
            {{ Str::limit($row->term, 60) }}
        @else
            ...
        @endif
    </td>
    <td align="right" width="10%">
        <span class="badge bg-secondary">{{ $loop->iteration }}</span>
    </td>
</tr>
