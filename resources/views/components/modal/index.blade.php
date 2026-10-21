{{-- x-tags: modal --}}
@props([
    'modid' => rand(),
    'title' => 'Modal',
    'type' => 'primary',
])

<!-- Button trigger modal -->
@if (isset($button))
    <x-modal.button modid="{{ $modid }}" type="{{ $type }}">
        {{ $button }}
    </x-modal.button>
@endif

<!-- Modal -->
{{-- @push('modals') - Livewire Click funktioniert nicht mit push --}}
<div wire:ignore.self class="modal fade" id="{{ $modid }}" tabindex="-1">
    {{-- <div class="modal fade" id="{{ $modid }}"> --}}
    <div {{ $attributes->merge(['class' => 'modal-dialog']) }}>
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ $title }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                @if (isset($footer))
                    {{ $footer }}
                @else
                    <div class="btn-group" role="group" disabled>
                        <button wire:loading.attr="disabled" type="button" class="btn btn-primary"
                            data-bs-dismiss="modal">Schliessen</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
{{-- @endpush --}}
