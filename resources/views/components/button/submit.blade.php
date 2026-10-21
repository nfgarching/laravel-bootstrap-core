{{-- x-tags: submit --}}
@props([
    'click' => '',
    'type' => 'primary',
])

<button @if ($click) wire:click="{{ $click }}" @endif
    {{ $attributes->merge(['class' => 'btn btn-' . $type]) }} type="button">
    {{ $slot }}
</button>


<button type="submit" class="btn btn-success">Submit</button>
