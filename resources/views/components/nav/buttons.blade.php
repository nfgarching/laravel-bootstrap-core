{{-- x-tags: nav.pagination-buttons --}}
@props([
    'navigation' => null,
    'firstItem' => null,
    'previousItem' => null,
    'nextItem' => null,
    'lastItem' => null,
    'routeName' => 'registers.show',
    'request' => null,
    'paramName' => null,
])

@php
    // Support both navigation array and individual props for backwards compatibility
    $first = $navigation['first'] ?? $firstItem;
    $previous = $navigation['previous'] ?? $previousItem;
    $next = $navigation['next'] ?? $nextItem;
    $last = $navigation['last'] ?? $lastItem;

    // Auto-derive paramName from routeName if not provided
    // e.g., 'registers.show' -> 'register', 'categories.show' -> 'category'
    if (!$paramName && $routeName) {
        $routeParts = explode('.', $routeName);
        $paramName = Str::singular($routeParts[0]);
    }

    // Get total count and current position from navigation array
    $totalCount = $navigation['totalCount'] ?? 0;
    $currentPosition = $navigation['currentPosition'] ?? 0;

    // Build filter info
    $filterInfo = [];
    if ($request) {
        if ($request->has('search') && $request->search) {
            $filterInfo[] = 'Suche: "' . Str::limit($request->search, 20) . '"';
        }
        if ($request->has('categorie_id') && $request->categorie_id) {
            $filterInfo[] = 'Kategorie';
        }
        if ($request->has('state') && $request->state) {
            $filterInfo[] = 'Status';
        }
        if ($request->has('type') && $request->type) {
            $filterInfo[] = 'Typ';
        }
    }
    $filterText = !empty($filterInfo) ? ' | Filter: ' . implode(', ', $filterInfo) : '';

    // Build tooltip texts
    $positionText = $totalCount > 0 ? "Position {$currentPosition} von {$totalCount}{$filterText}" : '';
    $firstTooltip = $first ? "Zum ersten Eintrag{$filterText}" : 'Bereits beim ersten Eintrag';
    $previousTooltip = $previous
        ? 'Vorheriger Eintrag' . ($positionText ? " | {$positionText}" : '')
        : 'Kein vorheriger Eintrag';
    $nextTooltip = $next ? 'Nächster Eintrag' . ($positionText ? " | {$positionText}" : '') : 'Kein nächster Eintrag';
    $lastTooltip = $last ? "Zum letzten Eintrag{$filterText}" : 'Bereits beim letzten Eintrag';
@endphp

<div {{ $attributes->merge(['class' => 'btn-group']) }}>
    @if ($first)
        @php
            $firstParams = $request
                ? array_merge([$paramName => $first->getKey()], $request->except($paramName))
                : [$paramName => $first->getKey()];
        @endphp
        <x-button.link :href="route($routeName, $firstParams)" type="outline-secondary" :tooltip="$firstTooltip">
            <i class="bi bi-chevron-bar-left"></i>
        </x-button.link>
    @else
        <x-button type="outline-secondary" disabled :tooltip="$firstTooltip">
            <i class="bi bi-chevron-bar-left"></i>
        </x-button>
    @endif

    @if ($previous)
        @php
            $prevParams = $request
                ? array_merge([$paramName => $previous->getKey()], $request->except($paramName))
                : [$paramName => $previous->getKey()];
        @endphp
        <x-button.link :href="route($routeName, $prevParams)" type="outline-secondary" :tooltip="$previousTooltip">
            <i class="bi bi-chevron-left"></i>
        </x-button.link>
    @else
        <x-button type="outline-secondary" disabled :tooltip="$previousTooltip">
            <i class="bi bi-chevron-left"></i>
        </x-button>
    @endif

    @if ($slot != '')
        {{ $slot }}
    @else
        <x-button onclick="window.location.reload()" type="outline-secondary" :tooltip="$positionText">
            <i class="bi bi-arrow-clockwise"></i>
        </x-button>
    @endif

    @if ($next)
        @php
            $nextParams = $request
                ? array_merge([$paramName => $next->getKey()], $request->except($paramName))
                : [$paramName => $next->getKey()];
        @endphp
        <x-button.link :href="route($routeName, $nextParams)" type="outline-secondary" :tooltip="$nextTooltip">
            <i class="bi bi-chevron-right"></i>
        </x-button.link>
    @else
        <x-button type="outline-secondary" disabled :tooltip="$nextTooltip">
            <i class="bi bi-chevron-right"></i>
        </x-button>
    @endif

    @if ($last)
        @php
            $lastParams = $request
                ? array_merge([$paramName => $last->getKey()], $request->except($paramName))
                : [$paramName => $last->getKey()];
        @endphp
        <x-button.link :href="route($routeName, $lastParams)" type="outline-secondary" :tooltip="$lastTooltip">
            <i class="bi bi-chevron-bar-right"></i>
        </x-button.link>
    @else
        <x-button type="outline-secondary" disabled :tooltip="$lastTooltip">
            <i class="bi bi-chevron-bar-right"></i>
        </x-button>
    @endif
</div>
