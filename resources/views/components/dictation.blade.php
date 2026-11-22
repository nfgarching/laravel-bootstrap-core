{{-- x-tags: dictation --}}
{{--
    Diktat-Button (Split-Button „Diktat ▾“) für ein Textfeld.

    Modi:
      - Live-Diktat (Web Speech API, nur Chrome/Edge): Text erscheint fortlaufend.
      - Aufnehmen & transkribieren (KI): MediaRecorder → WAV → Livewire-Upload
        'audioUpload' → Trait Nfgarching\Core\Traits\TranscribesDictation an der
        umgebenden Livewire-Komponente → AudioTranscriber (z. B. Gemini im textbot)
        → Browser-Event 'dictation-transcript' → Einfügen am Cursor.
    Der Modus wird im Dropdown gewählt (Häkchen, gemerkt im localStorage je
    Browser), der Hauptbutton startet ihn; ohne Wahl der beste verfügbare.
    Mikrofon nur in einem Secure Context (HTTPS/localhost).

    Props:
      - target      string  CSS-Selektor des Textfelds (textarea/input), z. B. '#chatbot-input'
      - transcribe  bool    KI-Modus anbieten; nur setzen, wenn die Livewire-Komponente
                            TranscribesDictation (+ WithFileUploads) nutzt. Wirksam nur,
                            wenn ein AudioTranscriber gebunden ist.

    Eingefügt wird per setRangeText() mit input-/change-Event, wire:model syncht also mit.
--}}
@props([
    'target',
    'transcribe' => false,
])

@php
    $transcribe = $transcribe && app()->bound(\Nfgarching\Core\Contracts\AudioTranscriber::class);
@endphp

<div {{ $attributes->merge(['class' => 'btn-group btn-group-sm']) }} wire:ignore
    x-data="coreDictation(@js($target), @js($transcribe))"
    x-on:dictation-transcript.window="onTranscript($event.detail)">

    {{-- Hauptbutton: startet den gewählten Modus --}}
    <button type="button" class="btn"
        :class="active ? 'btn-danger core-dictation-pulse' : 'btn-outline-secondary'"
        :disabled="!available"
        :title="available
            ? (active ? 'Aufnahme beenden' : 'Diktat starten – ' + modeLabel())
            : (secure
                ? 'Spracheingabe wird von diesem Browser nicht unterstützt'
                : 'Mikrofon nicht verfügbar – HTTPS oder localhost erforderlich')"
        @click="toggle()">
        <i class="bi" :class="active ? 'bi-mic-fill' : 'bi-mic'"></i>
        <span x-show="!active">Diktat<span x-show="preferred === 'recorder'" x-cloak> (KI)</span></span>
        <span x-show="active" x-cloak>
            <span x-text="elapsed"></span> Stopp
        </span>
    </button>

    {{-- Modus-Auswahl --}}
    <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split"
        data-bs-toggle="dropdown" aria-expanded="false" :disabled="!available || active">
        <span class="visually-hidden">Diktat-Modus wählen</span>
    </button>
    <ul class="dropdown-menu">
        <li>
            <button type="button" class="dropdown-item d-flex align-items-center" :disabled="!speechAvailable"
                :class="preferred === 'speech' && 'active'" :aria-current="preferred === 'speech'"
                :title="speechAvailable ? '' : 'Nur in Chrome/Edge verfügbar'" @click="choose('speech')">
                <i class="bi bi-soundwave me-2"></i> Live-Diktat (Browser)
                <i class="bi bi-check-lg ms-auto ps-3" x-show="preferred === 'speech'"></i>
            </button>
        </li>
        @if ($transcribe)
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" :disabled="!recorderAvailable"
                    :class="preferred === 'recorder' && 'active'" :aria-current="preferred === 'recorder'"
                    :title="recorderAvailable ? '' : (secure
                        ? 'Aufnahme wird von diesem Browser nicht unterstützt'
                        : 'Mikrofon nicht verfügbar – HTTPS oder localhost erforderlich')"
                    @click="choose('recorder')">
                    <i class="bi bi-record-circle me-2"></i> Aufnehmen &amp; transkribieren (KI)
                    <i class="bi bi-check-lg ms-auto ps-3" x-show="preferred === 'recorder'"></i>
                </button>
            </li>
        @endif
    </ul>

    <style>
        .core-dictation-pulse i {
            animation: core-dictation-pulse 1.2s ease-in-out infinite;
        }

        @keyframes core-dictation-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .35; }
        }
    </style>

    <script>
        window.coreDictation = window.coreDictation || function (target, transcribe) {
            const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
            // Mikrofon nur im Secure Context. Chrome stellt webkitSpeechRecognition
            // auch über HTTP bereit, die Erkennung scheitert dort aber mit 'not-allowed'.
            const secure = window.isSecureContext;

            return {
                active: false,
                mode: null,      // laufender Modus: 'speech' | 'recorder'
                preferred: null, // gewählter Modus (Dropdown), Start per Hauptbutton
                elapsed: '0:00',
                secure,
                speechAvailable: !!SR && secure,
                recorderAvailable: transcribe && !!(navigator.mediaDevices?.getUserMedia && window.MediaRecorder),
                available: false,
                recognition: null,
                recorder: null,
                stream: null,
                chunks: [],
                timer: null,
                startedAt: null,

                init() {
                    this.available = this.speechAvailable || this.recorderAvailable;

                    let saved = null;
                    try { saved = localStorage.getItem('core-dictation-mode'); } catch (e) {}
                    this.preferred = this.usable(saved) ? saved
                        : (this.speechAvailable ? 'speech' : (this.recorderAvailable ? 'recorder' : null));
                },

                usable(mode) {
                    return (mode === 'speech' && this.speechAvailable)
                        || (mode === 'recorder' && this.recorderAvailable);
                },

                choose(mode) {
                    if (!this.usable(mode)) return;
                    this.preferred = mode;
                    try { localStorage.setItem('core-dictation-mode', mode); } catch (e) {}
                },

                modeLabel() {
                    return this.preferred === 'recorder' ? 'Aufnehmen & transkribieren (KI)' : 'Live-Diktat (Browser)';
                },

                // Text am Cursor des Ziel-Felds einfügen und Livewire syncen.
                insert(text) {
                    const input = document.querySelector(target);
                    if (!input || !text) return;
                    input.setRangeText(text, input.selectionStart, input.selectionEnd, 'end');
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    input.focus();
                },

                // Nur das Transkript der eigenen Livewire-Komponente übernehmen.
                onTranscript(detail) {
                    if (!transcribe || detail?.source !== this.$wire?.$id) return;
                    const text = (detail.text ?? '').trim();
                    if (text) this.insert(text + ' ');
                },

                toggle() {
                    if (this.active) {
                        this.stop();
                    } else if (this.preferred === 'recorder') {
                        this.startRecorder();
                    } else {
                        this.startSpeech();
                    }
                },

                // ---- Live-Diktat (Web Speech API) ----------------------------
                startSpeech() {
                    if (this.active || !this.speechAvailable) return;

                    this.recognition = new SR();
                    this.recognition.lang = 'de-DE';
                    this.recognition.continuous = true;
                    this.recognition.interimResults = true;

                    this.recognition.onresult = (event) => {
                        for (let i = event.resultIndex; i < event.results.length; i++) {
                            if (event.results[i].isFinal) {
                                const text = event.results[i][0].transcript.trim();
                                if (text) this.insert(text + ' ');
                            }
                        }
                    };
                    // Chrome beendet die Erkennung bei Stille selbst.
                    this.recognition.onend = () => this.reset();
                    this.recognition.onerror = (event) => {
                        this.reset();
                        // 'no-speech' und 'aborted' sind normale Abbrüche.
                        const messages = {
                            'not-allowed': 'Mikrofon-Zugriff verweigert. Bitte die Berechtigung im Browser erlauben (erfordert HTTPS oder localhost).',
                            'service-not-allowed': 'Die Spracherkennung des Browsers ist nicht erlaubt.',
                            'audio-capture': 'Kein Mikrofon gefunden.',
                            'network': 'Die Spracherkennung des Browsers ist nicht erreichbar (Netzwerkfehler).',
                        };
                        if (messages[event.error]) alert(messages[event.error]);
                    };

                    this.recognition.start();
                    this.begin('speech');
                },

                // ---- Aufnahme + KI-Transkription -----------------------------
                async startRecorder() {
                    if (this.active || !this.recorderAvailable) return;

                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    } catch (e) {
                        alert('Mikrofon-Zugriff verweigert oder nicht verfügbar.');
                        return;
                    }

                    const mime = ['audio/webm;codecs=opus', 'audio/webm', 'audio/mp4', 'audio/ogg;codecs=opus']
                        .find(m => MediaRecorder.isTypeSupported(m)) || '';

                    this.chunks = [];
                    this.recorder = new MediaRecorder(this.stream, mime ? { mimeType: mime } : {});
                    this.recorder.ondataavailable = (e) => { if (e.data.size > 0) this.chunks.push(e.data); };
                    this.recorder.onstop = () => this.uploadRecording();

                    this.recorder.start();
                    this.begin('recorder');
                },

                async uploadRecording() {
                    const mime = this.recorder?.mimeType || 'audio/webm';
                    const blob = new Blob(this.chunks, { type: mime });

                    this.stream?.getTracks().forEach(t => t.stop());
                    this.reset();

                    if (blob.size === 0) return;

                    // Gemini liest WebM als Video ("0 Frames found") → im Browser
                    // nach WAV (16 kHz, mono) umwandeln.
                    let wav;
                    try {
                        wav = await this.toWav(blob);
                    } catch (e) {
                        alert('Die Aufnahme konnte nicht umgewandelt werden.');
                        return;
                    }

                    const file = new File([wav], 'diktat.wav', { type: 'audio/wav' });
                    this.$wire.upload('audioUpload', file, () => {}, () => {
                        alert('Upload der Aufnahme fehlgeschlagen.');
                    });
                },

                // Dekodieren, auf 16 kHz mono mischen, als 16-bit-PCM-WAV verpacken.
                async toWav(blob) {
                    const Ctx = window.AudioContext || window.webkitAudioContext;
                    const ctx = new Ctx();
                    let buffer;

                    try {
                        buffer = await ctx.decodeAudioData(await blob.arrayBuffer());
                    } finally {
                        ctx.close();
                    }

                    const rate = 16000;
                    const channels = [];
                    for (let c = 0; c < buffer.numberOfChannels; c++) {
                        channels.push(buffer.getChannelData(c));
                    }

                    const ratio = buffer.sampleRate / rate;
                    const length = Math.floor(buffer.length / ratio);
                    const samples = new Float32Array(length);

                    for (let i = 0; i < length; i++) {
                        const src = Math.floor(i * ratio);
                        let sum = 0;
                        for (let c = 0; c < channels.length; c++) sum += channels[c][src];
                        samples[i] = sum / channels.length;
                    }

                    const bytes = new DataView(new ArrayBuffer(44 + samples.length * 2));
                    const text = (offset, str) => {
                        for (let i = 0; i < str.length; i++) bytes.setUint8(offset + i, str.charCodeAt(i));
                    };

                    text(0, 'RIFF');
                    bytes.setUint32(4, 36 + samples.length * 2, true);
                    text(8, 'WAVEfmt ');
                    bytes.setUint32(16, 16, true);       // fmt-Chunk-Größe
                    bytes.setUint16(20, 1, true);        // PCM
                    bytes.setUint16(22, 1, true);        // mono
                    bytes.setUint32(24, rate, true);
                    bytes.setUint32(28, rate * 2, true); // Byte-Rate
                    bytes.setUint16(32, 2, true);        // Block-Align
                    bytes.setUint16(34, 16, true);       // Bits pro Sample
                    text(36, 'data');
                    bytes.setUint32(40, samples.length * 2, true);

                    for (let i = 0; i < samples.length; i++) {
                        const s = Math.max(-1, Math.min(1, samples[i]));
                        bytes.setInt16(44 + i * 2, s < 0 ? s * 0x8000 : s * 0x7fff, true);
                    }

                    return new Blob([bytes.buffer], { type: 'audio/wav' });
                },

                // ---- Gemeinsames ---------------------------------------------
                begin(mode) {
                    this.mode = mode;
                    this.active = true;
                    this.startedAt = Date.now();
                    this.elapsed = '0:00';
                    this.timer = setInterval(() => {
                        const s = Math.floor((Date.now() - this.startedAt) / 1000);
                        this.elapsed = Math.floor(s / 60) + ':' + String(s % 60).padStart(2, '0');
                    }, 1000);
                },

                stop() {
                    if (this.mode === 'speech') {
                        this.recognition?.stop(); // onend räumt auf
                    } else if (this.mode === 'recorder') {
                        this.recorder?.stop();    // onstop lädt hoch und räumt auf
                    }
                },

                reset() {
                    clearInterval(this.timer);
                    this.timer = null;
                    this.active = false;
                    this.mode = null;
                    this.recognition = null;
                    this.recorder = null;
                    this.stream = null;
                },
            };
        };
    </script>
</div>

@if ($transcribe)
    {{-- Transkriptions-Ladeanzeige (außerhalb von wire:ignore) --}}
    <span wire:loading wire:target="audioUpload" class="text-muted" style="font-size:.8rem;">
        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
        Transkribiere …
    </span>
@endif
