{{-- x-tags: form.cancel --}}
@props([
    'attri' => '',
    'label' => '',
    'type' => 'secondary',
])

<button {{ $attributes->merge(['class' => 'btn btn-' . $type]) }} 
    {{ $attri }} type="button"
    data-bs-dismiss="modal"
    wire:loading.attr="disabled"
>
    @if ($slot->isNotEmpty())
        {{ $slot }}
    @else
        @if ($label)
            {{ $label }}
        @else
            Abbrechen
        @endif
    @endif
</button>
