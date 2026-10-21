{{-- x-tags: accordion.item --}}
@props([
    'acc_id' => rand(),
    'header' => 'accordion_item_title',
    'row_id' => 'row' . rand(),
    'collapsed' => true,
])

<div class="accordion-item">

    <h2 class="accordion-header">
        <button class="accordion-button @if ($collapsed) collapsed @endif" type="button"
            data-bs-toggle="collapse" data-bs-target="#row_{{ $row_id }}">

            @if (isset($header))
                {{ $header }}
            @else
                accordion_header
            @endif

        </button>
    </h2>

    <div wire:ignore.self id="row_{{ $row_id }}" class="accordion-collapse collapse 
        @if (!$collapsed) show @endif"
        data-bs-parent="#acc_{{ $acc_id }}">
        <div class="accordion-body">

            {{ $slot }}

        </div>
    </div>

</div>
