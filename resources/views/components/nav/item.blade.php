{{-- x-tags: nav.item --}}
@props([
    'tab' => '0',
    'show' => '0',
    'token' => null,
])

<div class="tab-pane fade 
    @if ($tab == $show) show active @endif" id="tab{{ $token.$tab }}"
    role="tabpanel" tabindex="0">

    {{ $slot }}

</div>
