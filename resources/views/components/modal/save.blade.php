{{-- x-tags: modal.save --}}
@props([
    'modid' => 'modal' . rand(),
    'title' => 'Modal',
    'type' => 'primary',
    'button' => null,
    'btnSaving' => 'Speichern',
    'btnCancel' => 'Abbrechen',
])

<!-- Button trigger modal -->
@if (isset($button))
    <x-modal.button :modid="$modid" :type="$type">
        {{ $button }}
    </x-modal.button>
@endif

<!-- Modal -->
{{-- <div class="modal fade" id="{{ $modid }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div> --}}

<!-- Modal -->
{{-- @push('modals') - Livewire Click funktioniert nicht mit push --}}
<div wire:ignore.self class="modal fade" id="{{ $modid }}" tabindex="-1">
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
                        @if ($btnCancel)
                            <button wire:loading.attr="disabled" type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ $btnCancel }}</button>
                        @endif
                        <button wire:loading.attr="disabled" wire:click="saveModal" type="submit"
                            class="btn btn-danger">{{ $btnSaving }}</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
{{-- @endpush --}}

{{-- @pushOnce('scripts')
<script>
    console.log('hello modal');
    window.addEventListener('hide-modal', event => {
        console.log('close modal');
        $('.modal').modal('hide');
    })
</script>
@endPushOnce --}}

{{-- @if (isset($scripts)) --}}
{{-- @endif --}}
