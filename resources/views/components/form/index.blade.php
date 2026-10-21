{{-- x-tags: form --}}
@props([
    'id'     => rand(),
    'action' => '',
    'method' => 'POST',
])

@php
    // HTML kennt nur GET und POST; PUT/PATCH/DELETE laufen als POST mit _method-Feld.
    $method = strtoupper($method);
    $spoof = ! in_array($method, ['GET', 'POST']);
@endphp

<form method="{{ $spoof ? 'POST' : $method }}" action="{{ $action }}" id="{{ $id }}" {{ $attributes }}>
    @unless ($method === 'GET')
        @csrf
    @endunless
    @if ($spoof)
        @method($method)
    @endif

    {{ $slot }}

</form>
