<?php

namespace Nfgarching\Core\Modals;

use Closure;

/**
 * Registry for shared modal partials contributed by feature packages.
 *
 * Each package registers its own modal view in its ServiceProvider's boot()
 * method, instead of the core package hardcoding every package's modals.
 * The shared modals partial then simply renders the ordered, visible modals.
 */
class ModalRegistry
{
    /**
     * @var list<array{view: string, order: int, when: ?Closure}>
     */
    private array $modals = [];

    /**
     * Register a modal partial.
     *
     * @param  string  $view  Blade view name rendering one or more modals.
     * @param  int  $order  Lower numbers render first.
     * @param  (Closure(): bool)|null  $when  Optional visibility guard; the modal is hidden when it returns false.
     */
    public function modal(string $view, int $order = 100, ?Closure $when = null): static
    {
        $this->modals[] = ['view' => $view, 'order' => $order, 'when' => $when];

        return $this;
    }

    /**
     * Ordered list of modal view names that should currently be visible.
     *
     * @return list<string>
     */
    public function visibleModals(): array
    {
        $modals = array_filter(
            $this->modals,
            fn (array $modal): bool => $modal['when'] === null || ($modal['when'])(),
        );

        usort($modals, fn (array $a, array $b): int => $a['order'] <=> $b['order']);

        return array_values(array_column($modals, 'view'));
    }
}
