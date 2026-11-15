{{--
    Generisches iframe-Modal: lädt eine beliebige (Popup-)Seite in einem Modal.
    Geöffnet von <x-button.dispatch frame …> / <x-nav.popup frame …> per
    window.openFrameModal(url, title, size); geschlossen per window.closeFrameModal()
    (z. B. vom „Abbrechen“-Link mit data-frame-close in der Seite im iframe).

    Größe:
      - Breite: size = sm | lg | xl (Default) | fullscreen → modal-{size}
      - Höhe:   füllt den ganzen Platz bei fullscreen oder wenn die Seite im iframe ein
                Element mit data-frame-fill enthält (Seiten mit height:100vh wie der
                Chatbot – deren Inhalt ist im iframe immer so hoch wie das iframe selbst);
                sonst folgt sie dem Inhalt der Seite im iframe (gleiche Domain, daher messbar).
                Ein ResizeObserver misst nach, wenn der Inhalt wächst (Livewire,
                Namen-Auswahl, Fehlermeldungen). Begrenzt auf die Fensterhöhe –
                darüber scrollt das iframe selbst.

    Plain JS auf window (keine Alpine-Magic), damit es auch außerhalb jeder
    Alpine-/Livewire-Wurzel funktioniert (z. B. in der Navbar) – wie openPopup().
    Läuft die Seite selbst im iframe, wird nichts initialisiert (keine
    verschachtelten Modals).
--}}
<div class="modal fade" id="modalFrame" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            {{-- Kein Modal-Header: die Seite im iframe bringt ihre Überschrift selbst mit
                 (Kartenkopf). Der Titel landet nur in aria-label. Schließen-Button oben rechts. --}}
            <button type="button" class="btn-close position-absolute top-0 end-0 m-2" style="z-index:1"
                data-bs-dismiss="modal" aria-label="Schließen"></button>
            <div class="modal-body" style="padding:2.5rem 0">
                <iframe id="modalFrameIframe" src="about:blank"
                    style="width:100%;height:200px;border:0;display:block"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        if (window.self !== window.top) {
            return;
        }

        const SIZES = ['modal-sm', 'modal-lg', 'modal-xl', 'modal-fullscreen'];
        const modalEl = document.getElementById('modalFrame');
        const dialog = modalEl.querySelector('.modal-dialog');
        const iframe = document.getElementById('modalFrameIframe');
        let observer = null;

        // Platz für den Dialog-Rand und den Abstand im modal-body (je 2.5rem = 40px oben/unten)
        function maxHeight() {
            return dialog.classList.contains('modal-fullscreen')
                ? window.innerHeight - 80
                : window.innerHeight - 160;
        }

        // documentElement.offsetHeight = Höhe des Inhalts (anders als scrollHeight
        // nicht mindestens die iframe-Höhe), daher schrumpft das iframe auch wieder.
        function fitFrame() {
            const doc = iframe.contentDocument;
            const fill = dialog.classList.contains('modal-fullscreen')
                || (doc && doc.querySelector('[data-frame-fill]'));
            if (fill) {
                iframe.style.height = maxHeight() + 'px';
                return;
            }
            if (!doc || !doc.documentElement) {
                return;
            }
            const height = Math.min(doc.documentElement.offsetHeight, maxHeight());
            iframe.style.height = Math.max(height, 100) + 'px';
        }

        function stopObserving() {
            if (observer) {
                observer.disconnect();
                observer = null;
            }
        }

        // Jede geladene Seite (auch nach Submit/Validierungsfehler) neu vermessen
        iframe.addEventListener('load', function () {
            stopObserving();
            const doc = iframe.contentDocument;
            if (!doc || !doc.body || doc.location.href === 'about:blank') {
                return;
            }
            fitFrame();
            observer = new ResizeObserver(fitFrame);
            observer.observe(doc.body);
        });

        window.addEventListener('resize', fitFrame);

        window.openFrameModal = function (url, title, size) {
            size = SIZES.includes('modal-' + size) ? 'modal-' + size : 'modal-xl';
            dialog.classList.remove(...SIZES);
            dialog.classList.add(size);
            modalEl.setAttribute('aria-label', title || '');
            iframe.style.height = '200px';
            fitFrame();
            iframe.src = url;
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        };

        window.closeFrameModal = function () {
            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
        };

        // Beim Schließen leeren, damit beim nächsten Öffnen ein frisches Formular erscheint
        modalEl.addEventListener('hidden.bs.modal', function () {
            stopObserving();
            iframe.src = 'about:blank';
        });
    })();
</script>
