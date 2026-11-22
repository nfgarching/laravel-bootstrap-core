<?php

namespace Nfgarching\Core\Contracts;

use RuntimeException;

/**
 * Transkribiert eine Diktat-Aufnahme (Modus „Aufnehmen & transkribieren“ von
 * <x-dictation>). Core kennt keinen KI-Dienst: ein Package bindet die
 * Implementierung (textbot: Gemini über AudioTranscriptionService). Ist nichts
 * gebunden, bietet <x-dictation> nur das Live-Diktat des Browsers an.
 */
interface AudioTranscriber
{
    /**
     * @throws RuntimeException mit einer für den Nutzer lesbaren Meldung
     */
    public function transcribe(string $audioBytes, string $mimeType): string;
}
