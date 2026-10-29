{{-- x-tags: button.dispatch --}}
{{--
    Button mit tastenabhängigem Klick-Verhalten. Alpine-frei (natives onclick +
    Livewire.dispatch), funktioniert daher überall – auch außerhalb einer
    Alpine-/Livewire-Wurzel (z.B. in der Navbar).

    Klick-Verhalten:
      - Ctrl  => Link in neuem Tab (window.open '_blank'); mit ctrlHref => dieser
                 Link im selben Fenster (z.B. mailto: für das Standard-Mailprogramm)
      - mit modalName:  Klick => Livewire-Event modalName feuern, Shift => Popup
      - ohne modalName: Klick => Popup (zentriertes window.open via openPopup())

    Props:
      - href      string  Ziel-URL (data wird als Query angehängt)
      - data      array   Query-Parameter + Payload des Livewire-Events (data: {...})
      - modalName string  Name des per Livewire.dispatch gefeuerten Events (optional)
      - ctrlHref  string  Ziel bei Ctrl statt neuem Tab, z.B. 'mailto:…' (optional)
      - popup     string  window.open-Features (z.B. 'width=800,height=800')
      - class     string  Bootstrap-Varianten: button, nav-link
      - type      string  Bootstrap-Variante (btn-{type}), Default 'primary'
      - tooltip   string  HTML-Tooltip; leer => automatischer Hinweistext

    openPopup() ist global in layouts/parts/script.blade.php definiert.
    Siehe auch button.popup (dünner Wrapper ohne modalName).
--}}
@props([
    'data' => [],
    'href' => '',
    'class' => 'btn btn-',
    'type' => 'primary',
    'popup' => 'width=800,height=800', // '_blank' od. 'popup' od. 'left=100,top=100,width=320,height=320',
    'tooltip' => '',
    'modalName' => '',
    'ctrlHref' => '',
])

@php
    // json_encode statt '$val': ein Wert mit Backslashes (z.B. ein Model-FQCN wie
    // App\Models\VNamReg) wäre in einem JS-String-Literal sonst verstümmelt –
    // \M und \V sind keine gültigen Escapes und JS wirft den Backslash weg.
    $arr = '';
    foreach ($data as $key => $val) {
        if (is_scalar($val) || is_array($val)) {
            $arr .= "$key: " . json_encode($val, JSON_UNESCAPED_UNICODE) . ',';
        }
    }
    $urlData = array_map(fn($val) => is_array($val) ? json_encode($val) : $val, $data);
    $href = url()->query($href, $urlData);
    if (!$tooltip) {
        if ($modalName) {
            $tooltip =
                '<small>Press => <b>Open Modal</b><br>
                        SHIFT => <b>Open New Popup</b><br>
                        CTRL  => <b>Open Tab</b></small>';
        } else {
            $tooltip =
                '<small>Press => <b>Open New Popup</b></small>';
        }
    }
@endphp

<button {{ $attributes->merge(['class' => $class . $type]) }}
    type="button"
    @if ($tooltip)
        data-bs-toggle="tooltip"
        data-bs-html="true"
        data-bs-title="{{ $tooltip }}"
    @endif
    onclick="
        @if ($ctrlHref)
        if (event.ctrlKey) { window.location.href = '{{ $ctrlHref }}'; }
        @else
        if (event.ctrlKey) { window.open('{{ $href }}', '_blank'); }
        @endif
        @if ($modalName)
        else if (event.shiftKey) { openPopup('{{ $href }}', '{{ $popup }}'); }
        else { Livewire.dispatch('{{ $modalName }}', { data: { {{ $arr }} } }); }
        @else
        else { openPopup('{{ $href }}', '{{ $popup }}'); }
        @endif
    ">

    {{ $slot }}

</button>
