<!-- Toast -->

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            {{-- <img src="..." class="rounded me-2" alt="..."> --}}
            <i class="bi bi-exclamation-circle"></i> &nbsp; &nbsp;
            <strong id="titleToast" class="me-auto">Info</strong>
            <small id="subtitleToast"></small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div id="bodyToast" class="toast-body">Dies ist eine Testmitteilung.</div>
    </div>
</div>

<script>
    console.log('init show-toast');
    window.addEventListener('show-toast', event => {
        console.log('show-toast: ' + event.detail.message);
        if (event.detail.title) document.getElementById("titleToast").innerHTML = event.detail.title;
        if (event.detail.subtitle) document.getElementById("subtitleToast").innerHTML = event.detail.subtitle;
        if (event.detail.message) document.getElementById("bodyToast").innerHTML = event.detail.message;
        var toastElList = [].slice.call(document.querySelectorAll('.toast'))
        var toastList = toastElList.map(function(toastEl) {
            // Creates an array of toasts (it only initializes them)
            return new bootstrap.Toast(toastEl) // No need for options; use the default options
        });
        toastList.forEach(toast => toast.show()); // This show them
        // console.log(toastList); // Testing to see if it works
    })
</script>

<!-- Toast Message -->

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast toast-message align-items-center text-bg-danger border-0" role="alert">
        <div class="d-flex">
            <div id="bodyMessage" class="toast-body" style="white-space: pre-line">Dies ist eine Testmitteilung.</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script>
    console.log('init show-message')
    /*
     * detail.message: reiner Text (textContent, kein HTML — Meldungen enthalten
     *                 Benutzerdaten wie Betreffzeilen). Zeilenumbrüche mit "\n".
     * detail.sticky:  true = Toast bleibt offen, bis er geschlossen wird.
     */
    window.addEventListener('show-message', event => {
        console.log('show-message: ' + event.detail.message);
        if (event.detail.message) document.getElementById("bodyMessage").textContent = event.detail.message;
        document.querySelectorAll('.toast-message').forEach(toastEl => {
            // Instanz neu anlegen, damit autohide je Meldung gilt
            bootstrap.Toast.getInstance(toastEl)?.dispose();
            new bootstrap.Toast(toastEl, { autohide: !event.detail.sticky }).show();
        });
    })
</script>
