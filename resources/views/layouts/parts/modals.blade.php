<!-- Modals -->

@stack('modals')

@foreach (app(\Nfgarching\Core\Modals\ModalRegistry::class)->visibleModals() as $modal)
    @includeIf($modal)
@endforeach

{{-- <livewire:akv.doc.calendar /> --}}
{{-- <livewire:akv.doc.create /> --}}
{{-- <livewire:akv.doc.upsert /> --}}
{{-- <livewire:akv.nam.modal /> --}}
{{-- <livewire:akv.reg.modal /> --}}

{{-- <livewire:modal.main /> --}}

{{-- <livewire:modal.disk /> --}}
<livewire:modal.field /> {{-- \Nfgarching\Core\Livewire\Modals\Field --}}
{{-- <livewire:modal.file /> --}}
{{-- <livewire:modal.files /> --}}
{{-- <livewire:modal.imap /> --}}
{{-- Nicht auf der eigenständigen /raw-Popup-Seite einbinden: dort ist die Seite
     selbst bereits die Raw-Komponente. Sonst rendert das Formular doppelt
     (zweite id="modalRAW", zweite Card via popupMode). Gleiches Muster wie mail.compose. --}}
@unless (request()->routeIs('raw'))
    <livewire:modal.raw /> {{-- \Nfgarching\Core\Views\components\raw; --}}
@endunless
{{-- <livewire:modal.test /> --}}
{{-- <livewire:modal.upload /> --}}
<livewire:modal.multi /> {{-- \Nfgarching\Core\Livewire\Modals\Multi --}}
{{-- \Nfgarching\Core\Livewire\Modals\Field --}}

{{-- @if (class_exists(\Nfgarching\Mails\Livewire\Compose::class) && ! request()->routeIs('mail.compose'))
    <livewire:mail.compose />
@endif --}}

{{-- <livewire:sipgate.modal /> --}}

<x-modal.msg id="modalConfirm" title="Bitte bestätigen" />

<script>
    console.log('init modal');

    window.addEventListener('hide-modal', event => {
        console.log('hide-modal: ' + event.detail.modalName);
        var myModal = bootstrap.Modal.getOrCreateInstance(
            document.getElementById(event.detail.modalName));
        myModal.hide();
    })

    window.addEventListener('show-modal', event => {
        console.log('show-modal: ' + event.detail.modalName);
        var myModal = bootstrap.Modal.getOrCreateInstance(
            document.getElementById(event.detail.modalName));
        myModal.show();
    })

    function closeAllModals() {

        // get modals
        const modals = document.getElementsByClassName('modal');

        // on every modal change state like in hidden modal
        for (let i = 0; i < modals.length; i++) {
            modals[i].classList.remove('show');
            modals[i].setAttribute('aria-hidden', 'true');
            modals[i].setAttribute('style', 'display: none');
        }

        // get modal backdrops
        const modalsBackdrops = document.getElementsByClassName('modal-backdrop');

        // remove every modal backdrop
        for (let i = 0; i < modalsBackdrops.length; i++) {
            document.body.removeChild(modalsBackdrops[i]);
        }
    }
</script>

<script>
    // console.log('init check');

    // function docheck() {
    //     console.log('docheck');
    //     let checkboxes = document.querySelectorAll('input[type="checkbox"]');
    //     checkboxes.forEach(box => {
    //         box.checked = true;
    //     });
    // }

    // function toggle() {
    //     console.log('toggle');
    //     let checkboxes = document.querySelectorAll('input[type="checkbox"]');
    //     checkboxes.forEach(box => {
    //         box.checked = box.checked ? false : true;
    //     });


    //     var toggle = document.getElementById("toggle");
    //     var toggleClass = toggle.className;
    //     console.log(toggleClass);
    //     if (toggleClass == "bi bi-toggle-off") {
    //         console.log('1');
    //         toggle.className = "bi bi-toggle-on";
    //     } else {
    //         console.log('2');
    //         toggle.className = "bi bi-toggle-off";
    //     }

    // }

    // function uncheck() {
    //     console.log('uncheck');
    //     let checkboxes = document.querySelectorAll('input[type="checkbox"]');
    //     checkboxes.forEach(box => {
    //         box.checked = false;
    //     });
    // }

</script>
