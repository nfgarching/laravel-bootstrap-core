{{-- x-tags: markdown --}}
{{--
    Zeigt Text formatiert als Markdown an (nur Anzeige, kein Eingabefeld).

    HTML im Text wird maskiert (erscheint als Text, wird nie ausgeführt), unsichere
    Links (javascript: …) werden entfernt. Einfache Zeilenumbrüche bleiben erhalten
    (breaks), damit Memo/OCR-Texte nicht zu einem Absatz zusammenlaufen.

    <x-markdown :text="$item->get_multi()" rows="18" class="form-control-sm bg-light" />

    Props:
      - text    string    Markdown-Text
      - rows    int|null  Höhe wie ein <textarea rows="…"> (scrollt bei mehr Inhalt); null = Höhe folgt dem Inhalt
      - breaks  bool      einfache Zeilenumbrüche als <br> ausgeben (Default true)
      - empty   string    Hinweis bei leerem Text
    Übrige Attribute (class, style) landen am Container (Rahmen über form-control).
--}}
@props([
    'text' => '',
    'rows' => null,
    'breaks' => true,
    'empty' => 'Kein Text vorhanden.',
])

@php
    $text = (string) $text;
    $options = ['html_input' => 'escape', 'allow_unsafe_links' => false];
    if ($breaks) {
        $options['renderer'] = ['soft_break' => "<br>\n"];
    }
    $html = trim($text) === '' ? '' : \Illuminate\Support\Str::markdown($text, $options);
    // Zeilenhöhe 1.5 wie .form-control, dazu Innenabstand und Rahmen
    $height = $rows ? 'height: calc(' . (int) $rows . ' * 1.5em + .75rem + 2px)' : '';
@endphp

<div {{ $attributes->class(['form-control', 'core-markdown'])->style([$height => $height !== '']) }}>
    @if ($html !== '')
        {!! $html !!}
    @else
        <span class="text-body-secondary">{{ $empty }}</span>
    @endif
</div>

@once
    <style>
        .core-markdown {
            overflow-y: auto;
            overflow-wrap: anywhere;
        }

        .core-markdown > :last-child {
            margin-bottom: 0;
        }

        .core-markdown h1,
        .core-markdown h2,
        .core-markdown h3,
        .core-markdown h4,
        .core-markdown h5,
        .core-markdown h6 {
            font-size: 1.1em;
            font-weight: 600;
            margin: .75em 0 .4em;
        }

        .core-markdown p,
        .core-markdown ul,
        .core-markdown ol,
        .core-markdown blockquote,
        .core-markdown pre {
            margin-bottom: .6em;
        }

        .core-markdown blockquote {
            padding-left: .75em;
            border-left: .2em solid var(--bs-border-color);
            color: var(--bs-secondary-color);
        }

        .core-markdown pre {
            white-space: pre-wrap;
        }

        .core-markdown hr {
            margin: .75em 0;
        }

        .core-markdown table {
            margin-bottom: .6em;
            border-collapse: collapse;
        }

        .core-markdown th,
        .core-markdown td {
            border: 1px solid var(--bs-border-color);
            padding: .2em .4em;
        }
    </style>
@endonce
