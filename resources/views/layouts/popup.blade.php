<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@if (file_exists(resource_path('views/layouts/parts/head.blade.php')))
    @include('layouts.parts.head')
@else
    @include('layout::parts.head')
@endif

<body>

    {{-- Im iframe-Modal (layout::parts.modal-frame): <html class="in-frame">, so früh
         wie möglich, damit kein Leerraum aufblitzt. Elemente mit .frame-hide (z. B. der
         Abstand oben für das Popup) entfallen dort. --}}
    <script>
        if (window.self !== window.top) {
            document.documentElement.classList.add('in-frame');
        }
    </script>
    <style>
        html.in-frame .frame-hide { display: none !important; }
    </style>

    {{-- @if (file_exists(resource_path('views/layouts/parts/navbar.blade.php')))
        @include('layouts.parts.navbar')
    @else
        @include('layout::parts.navbar')
    @endif --}}
    {{-- modals + toasts werden bereits vom script-Partial (unten) eingebunden.
         NICHT zusätzlich hier einbinden, sonst rendert jede Modal-Komponente
         (u.a. <livewire:modal.raw />) doppelt -> Formular mehrfach + doppelte
         id="modalRAW". Vgl. main.blade.php, das ebenfalls nur script einbindet. --}}

    @if (Session::has('message'))
        <div x-init="$dispatch('show-message', { message: '{{ Session::get('message') }}' })"></div>
    @endif

    {{-- <div class="container-fluid"> --}}
    {{-- <div class="row"> --}}
    <!-- Nav tabs -->
    {{-- <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3 sidebar-sticky">
                    @if (file_exists(resource_path('views/layouts/parts/sidebar.blade.php')))
                        @include('layouts.parts.sidebar')
                    @else
                        @include('layout::parts.sidebar')
                    @endif
                </div>
            </nav> --}}
    <!--Container Main start-->
    {{-- <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4"> --}}
        {{-- <br><br> --}}
        <!-- Main Header -->
        @if (isset($header))
            {{ $header }}
        @endif
        <!-- Main Content -->
        {{ $slot }}
        <!-- Main Footer -->
        @if (isset($footer))
            {{ $footer }}
        @endif
    {{-- </main> --}}
    <!--Container Main end-->
    {{-- </div> --}}
    {{-- </div> --}}

    @if (file_exists(resource_path('views/layouts/parts/script.blade.php')))
        @include('layouts.parts.script')
    @else
        @include('layout::parts.script')
    @endif

    {{-- Abschluss einer Popup-Seite (z. B. Datei-Import/-Upload, beA-Übernahme), im Popup wie im
         iframe-Modal (layout::parts.modal-frame):
           inOpener = true  => aufrufende Seite auf url (Popup: window.opener,
                               Modal: Hauptfenster), Popup bzw. Modal schließen
           inOpener = false => url in neuem Tab, Popup bzw. Modal schließen
         Ohne Opener (z. B. per CTRL als Tab geöffnet) => url im selben Fenster. --}}
    <script>
        window.finishPopup = function (url, inOpener) {
            if (window.self !== window.top) {
                if (inOpener) {
                    window.top.location.href = url;
                } else {
                    window.top.open(url, '_blank');
                    window.parent.closeFrameModal && window.parent.closeFrameModal();
                }
                return;
            }
            if (inOpener && window.opener && !window.opener.closed) {
                window.opener.location.href = url;
                window.opener.focus();
                window.close();
                return;
            }
            if (inOpener) {
                window.location.href = url;
                return;
            }
            window.open(url, '_blank');
            window.close();
        };

        // Abschluss ohne Ziel-URL: aufrufende Seite neu laden (Modal: Hauptfenster,
        // Popup: window.opener) und Popup bzw. Modal schließen. Ohne Opener wird
        // fallbackUrl geöffnet (z. B. eine Übersicht).
        window.reloadOpener = function (fallbackUrl) {
            if (window.self !== window.top) {
                window.top.location.reload();
                return;
            }
            if (window.opener && !window.opener.closed) {
                window.opener.location.reload();
                window.opener.focus();
                window.close();
                return;
            }
            if (fallbackUrl) {
                window.location.href = fallbackUrl;
            }
        };
    </script>

    {{-- Im iframe-Modal (layout::parts.modal-frame): Links mit data-frame-close
         (z. B. „Abbrechen“) schließen das Modal, statt im iframe zu navigieren. --}}
    <script>
        if (window.self !== window.top) {
            document.addEventListener('click', function (event) {
                const link = event.target.closest('[data-frame-close]');
                if (link && typeof window.parent.closeFrameModal === 'function') {
                    event.preventDefault();
                    window.parent.closeFrameModal();
                }
            });
        }
    </script>

</body>

</html>
