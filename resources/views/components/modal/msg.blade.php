{{-- x-tags: modal.msg --}}
<x-modal id="modalBestaetigung" title="Wirklich löschen?" size="modal-sm">
    <x-slot:footer>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
        <button type="button" class="btn btn-danger" id="btnLoeschenBestaetigen">Löschen</button>
    </x-slot:footer>

    <p>Dieser Vorgang kann nicht rückgängig gemacht werden.</p>
</x-modal>

<x-modal id="modalDatei" title="Dokument anzeigen" size="modal-xl" :scrollable="true">
    <x-slot:footer>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
        <a href="#" id="btnDownload" class="btn btn-primary">Herunterladen</a>
    </x-slot:footer>

    {{-- <iframe id="dateiFrame" src="" style="width:100%; height:70vh; border:none;"></iframe> --}}
</x-modal>

@pushOnce('scripts')
    <script>
        console.log('init modal.msg');

        // Datei-Vorschau mit dynamischer URL
        document.querySelectorAll('[modalBestaetigung]').forEach(btn => {
            btn.addEventListener('click', () => {
                // const url = btn.dataset.openDatei;
                // document.getElementById('dateiFrame').src = url;
                new bootstrap.Modal(document.getElementById('modalBestaetigung')).show();
            });
        });
    </script>
@endPushOnce
