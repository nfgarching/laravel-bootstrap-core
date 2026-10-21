<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
</script>

@if (file_exists(resource_path('views/layouts/parts/modals.blade.php')))
    @include('layouts.parts.modals')
@else
    @include('layout::parts.modals')
@endif
@if (file_exists(resource_path('views/layouts/parts/toasts.blade.php')))
    @include('layouts.parts.toasts')
@else
    @include('layout::parts.toasts')
@endif

{{--
    Livewire 4 lädt Alpine.js gebündelt mit. @livewireScripts MUSS explizit hier
    stehen, sonst injiziert Livewire das Script (und damit Alpine) nur automatisch
    auf Seiten, die tatsächlich eine Livewire-Komponente rendern – auf allen anderen
    Seiten fehlte Alpine komplett (x-data/@click/x-init taten nichts).
    NICHT zusätzlich das Alpine-CDN-Script laden -> "multiple instances of Alpine".
--}}
@livewireScripts

@stack('scripts')

{{-- Globale Funktion openPopup(): öffnet ein zentriertes Popup-Fenster
     (opts = window.open-Features) bzw. einen neuen Tab, wenn opts leer ist.
     Wird von <x-button.popup> und <x-button.dispatch> genutzt. Bewusst eine
     plain Funktion auf window (keine Alpine-Magic), damit sie auch außerhalb
     jeder Alpine-/Livewire-Wurzel funktioniert (z.B. in der Navbar). --}}
<script>
    window.openPopup = function (url, opts) {
        if (!opts) { return window.open(url, '_blank'); }
        const p = Object.fromEntries(opts.split(',').map(e => e.split('=')));
        const w = parseInt(p.width) || 800;
        const h = parseInt(p.height) || 800;
        const left = Math.round((screen.width - w) / 2);
        const top = Math.round((screen.height - h) / 2);
        window.open(url, '_blank', `${opts},left=${left},top=${top}`);
    };
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // console.log('init popover + tooltip');
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
        [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    });
</script>

<script>
    window.addEventListener('reload-window', event => {
        window.location.reload(false);
    })
</script>
