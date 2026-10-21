{{-- x-tags: table.check --}}
<div class="container">
    <div class="btn-group btn-group-sm" style="font-size:9pt;">
        <a class="link-secondary" href="javascript:docheck();" type="button">
            Alles markieren
        </a>
        &nbsp;
        <a class="link-secondary" href="javascript:uncheck();" type="button">
            Nichts markieren
        </a>
    </div>
    <div class="btn-group btn-group-sm float-end">
        {{ $slot }}
    </div>
</div>


@pushOnce('scripts')
    <script>
        console.log('init check');

        function docheck() {
            console.log('docheck');
            let checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(box => {
                box.checked = true;
            });
        }

        function uncheck() {
            console.log('uncheck');
            let checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(box => {
                box.checked = false;
            });
        }
    </script>
@endPushOnce
