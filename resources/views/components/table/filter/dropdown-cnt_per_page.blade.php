{{-- x-tags: table.filter.cnt_per_page --}}
@props(['route', 'request'])

@php $perPage = (int) $request->get('per_page', 8); @endphp

{{-- <div class="dropdown"> --}}
    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        {{-- {{ $request->get('per_page', 10) }} pro Seite --}}
    </button>
    <input type="hidden" name="per_page" value="{{ $perPage }}" />
    <ul class="dropdown-menu">
        <li><span class="dropdown-item-text">Anzahl der Datensätze:</span></li>
        <li>
            <hr class="dropdown-divider">
        </li>
        @foreach ([5, 10, 25, 50, 100] as $n)
            <li><a class="dropdown-item @if ($perPage === $n) active @endif"
                    href="{{ route($route, array_merge($request->except('per_page'), ['per_page' => $n])) }}">
                    {{ $n }} pro Seite
                </a></li>
        @endforeach
    </ul>
{{-- </div> --}}
