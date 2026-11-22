<?php

namespace Nfgarching\Core\Traits;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Nfgarching\Core\Contracts\AudioTranscriber;
use RuntimeException;

/**
 * Serverseite von <x-dictation ... transcribe>: nimmt die im Browser
 * aufgezeichnete Aufnahme (WAV) als temporären Livewire-Upload entgegen,
 * transkribiert sie über den gebundenen AudioTranscriber und schickt das
 * Transkript als Browser-Event 'dictation-transcript' zurück. <x-dictation>
 * fügt es am Cursor seines Ziel-Felds ein. Die Aufnahme wird verworfen.
 *
 * Die Komponente braucht WithFileUploads. Meldungen gehen über die Hooks
 * dictationFailed()/dictationTranscribed(); Default: $errorMessage setzen,
 * sofern die Komponente es hat.
 */
trait TranscribesDictation
{
    /** @var TemporaryUploadedFile|null */
    public $audioUpload = null;

    public function updatedAudioUpload(): void
    {
        $upload = $this->audioUpload;
        $this->audioUpload = null; // Temp-Datei räumt Livewire auf

        if (! $upload) {
            return;
        }

        $this->dictationFailed(null);

        if (! app()->bound(AudioTranscriber::class)) {
            $this->dictationFailed('Keine KI-Transkription verfügbar.');

            return;
        }

        $mime = (string) $upload->getMimeType();

        // Der Browser lädt WAV hoch (<x-dictation>); WebM liest Gemini als Video.
        if (! str_starts_with($mime, 'audio/')) {
            $this->dictationFailed('Nur Audio-Aufnahmen können transkribiert werden.');

            return;
        }

        if ($upload->getSize() > 12 * 1024 * 1024) {
            $this->dictationFailed('Aufnahme zu groß (max. 12 MB).');

            return;
        }

        try {
            $transcript = app(AudioTranscriber::class)->transcribe($upload->get(), $mime);
        } catch (RuntimeException $e) {
            $this->dictationFailed($e->getMessage());

            return;
        }

        // source: nur das <x-dictation> dieser Komponente fügt ein, nicht
        // weitere auf derselben Seite (z. B. Mailer-Modal neben dem Textbot).
        $this->dispatch('dictation-transcript', text: $transcript, source: $this->getId());
        $this->dictationTranscribed();
    }

    /** Fehlermeldung anzeigen; null = vorherige Meldung zurücksetzen. */
    protected function dictationFailed(?string $message): void
    {
        if (property_exists($this, 'errorMessage')) {
            $this->errorMessage = $message;
        }
    }

    protected function dictationTranscribed(): void
    {
        //
    }
}
