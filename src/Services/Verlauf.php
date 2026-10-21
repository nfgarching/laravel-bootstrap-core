<?php

namespace Nfgarching\Core\Services;

use Illuminate\Support\Facades\Session;

/**
 * Session-basierter Verlauf der zuletzt angesehenen Datensätze
 * (Akten, Dokumente, Namen, Buchungen).
 *
 * Session-Format (kompatibel zu den bestehenden Views):
 * - "Verlauf:{$key}"        => Liste der NRs in Besuchsreihenfolge
 * - "Verlauf:{$key}:{$nr}"  => Anzeige-Label zur NR
 */
class Verlauf
{
    /**
     * Merkt sich einen besuchten Datensatz, sofern noch nicht im Verlauf.
     */
    public function record(string $key, string|int $nr, ?string $label): void
    {
        if (in_array($nr, Session::get("Verlauf:$key", []))) {
            return;
        }

        Session::push("Verlauf:$key", $nr);
        Session::put("Verlauf:$key:$nr", $label);
    }

    /**
     * Liefert den Verlauf als [NR => Label], zuletzt besuchte Einträge am Ende.
     *
     * @return array<int|string, ?string>
     */
    public function entries(string $key, ?int $limit = null): array
    {
        $nrs = Session::get("Verlauf:$key", []);

        if ($limit !== null) {
            $nrs = array_slice($nrs, -$limit);
        }

        $entries = [];
        foreach ($nrs as $nr) {
            $entries[$nr] = Session::get("Verlauf:$key:$nr");
        }

        return $entries;
    }

    /**
     * Liefert den Verlauf als Auswahl-Liste für Typeahead-/Picker-Komponenten:
     * zuletzt besuchte Einträge zuerst, im Format der Suchergebnisse
     * (['NR' => …, 'ID' => '', 'Suchbegriff' => Label]).
     *
     * @param  string  $key  Verlauf-Bereich (z. B. 'namNR', 'dokNR', 'regNR')
     * @param  array<int, int|string>  $reject  Auszuschließende NRs (bereits verwendet)
     * @return array<int, array{NR: int|string, ID: string, Suchbegriff: ?string}>
     */
    public function history(string $key, array $reject = []): array
    {
        return collect($this->entries($key))
            ->reverse()
            ->reject(fn ($label, $nr) => in_array($nr, $reject))
            ->map(fn ($label, $nr) => [
                'NR' => $nr,
                'ID' => '',
                'Suchbegriff' => $label,
            ])
            ->values()
            ->all();
    }

    public function has(string $key): bool
    {
        return ! empty(Session::get("Verlauf:$key"));
    }

    /**
     * Leert den Verlauf für einen Bereich inklusive der Label-Einträge.
     */
    public function clear(string $key): void
    {
        foreach (Session::get("Verlauf:$key", []) as $nr) {
            Session::forget("Verlauf:$key:$nr");
        }

        Session::forget("Verlauf:$key");
    }
}
