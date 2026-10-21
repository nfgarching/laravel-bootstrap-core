{{-- x-tags: modal.button --}}
@props(['modid', 'click' => '', 'type' => 'primary'])

<!-- Button trigger modal -->
{{-- <button 
    type="button" 
    class="btn btn-primary" 
    data-bs-toggle="modal" 
    data-bs-target="#{{ $modid }}">
    {{ $slot }}
</button> --}}

<button 
    wire:click="{{ $click }}" 
    wire:loading.attr="disabled"
    {{ $attributes->merge(['class' => 'btn btn-' . $type]) }} 
    type="button" 
    data-bs-toggle="modal"
    data-bs-target="#{{ $modid }}">
    {{ $slot }}
</button>
