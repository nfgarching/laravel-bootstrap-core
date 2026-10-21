<hr>

<div class="container">

    <div class="btn-group btn-group-sm float-start">
        <small>{{ date('H:i:s') }}</small>
    </div>

    {{-- {{ url()->current() . '/' }} | {{ url()->previous() }} --}}


    <div class="btn-group btn-group-sm float-end">
        @if (url()->current() . '/' != url()->previous())
            <x-button.link :href="old(' url', url()->previous())">
                <i class="bi bi-arrow-left-square"></i> &nbsp; Zurück
            </x-button.link>
        @endif
        <x-button.link href="#">
            <i class="bi bi-arrow-up-square"></i> &nbsp; Oben
        </x-button.link>
    </div>

</div>

<br>
