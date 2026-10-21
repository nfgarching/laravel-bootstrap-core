{{-- x-tags: button.clipboard --}}
@props([
    'text' => '',
    'html' => null,
    'type' => 'primary',
    'tooltip' => 'Klicken um zu kopieren',
    'message' => 'In Zwischenablage kopiert',
])

@php
    $defaults = ['class' => 'btn btn-sm btn-' . $type];
    if ($tooltip) {
        $defaults['data-bs-toggle'] = 'tooltip';
        $defaults['data-bs-placement'] = 'bottom';
        $defaults['data-bs-title'] = $tooltip;
    }
@endphp

<button {{ $attributes->merge($defaults) }} type="button"
    onclick="(function() {
        var text = @js($text);
        var html = @js($html);
        var showToast = function() {
            var toast = document.createElement('div');
            toast.className = 'position-fixed top-0 end-0 m-3 alert alert-success alert-dismissible fade show';
            toast.style.zIndex = '9999';
            toast.innerHTML = '<i class=\'bi bi-clipboard-check\'></i> {{ addslashes($message) }} <button type=\'button\' class=\'btn-close\' data-bs-dismiss=\'alert\'></button>';
            document.body.appendChild(toast);
            setTimeout(function() { toast.remove(); }, 3000);
        };
        var copyPlainText = function() {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(showToast);
                return;
            }
            var ta = document.createElement('textarea');
            ta.value = text;
            ta.style.cssText = 'position:fixed;opacity:0';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            showToast();
        };
        {{-- execCommand('copy') on a selected rich-text node copies both HTML and plain text without needing a secure context (navigator.clipboard requires https/localhost). --}}
        var copyHtmlViaSelection = function() {
            var container = document.createElement('div');
            container.innerHTML = html;
            container.style.cssText = 'position:fixed;opacity:0;pointer-events:none;';
            document.body.appendChild(container);
            var range = document.createRange();
            range.selectNodeContents(container);
            var selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
            var copied = false;
            try { copied = document.execCommand('copy'); } catch (e) { copied = false; }
            selection.removeAllRanges();
            document.body.removeChild(container);
            return copied;
        };
        if (html && window.ClipboardItem && navigator.clipboard && navigator.clipboard.write) {
            navigator.clipboard.write([
                new ClipboardItem({
                    'text/plain': Promise.resolve(new Blob([text], {type: 'text/plain'})),
                    'text/html': Promise.resolve(new Blob([html], {type: 'text/html'})),
                }),
            ]).then(showToast, function() {
                if (copyHtmlViaSelection()) { showToast(); } else { copyPlainText(); }
            });
        } else if (html) {
            if (copyHtmlViaSelection()) { showToast(); } else { copyPlainText(); }
        } else {
            copyPlainText();
        }
    })()"
>
    {{ $slot }}
</button>
