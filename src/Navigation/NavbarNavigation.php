<?php

namespace Nfgarching\Core\Navigation;

use Closure;

/**
 * Registry for navbar items contributed by feature packages.
 *
 * Each package registers its own navbar item view in its ServiceProvider's
 * boot() method, instead of the core package hardcoding every package's
 * navbar buttons. The navbar then simply renders the ordered, visible items.
 */
class NavbarNavigation
{
    /**
     * @var list<array{view: string, order: int, when: ?Closure}>
     */
    private array $items = [];

    /**
     * Register a navbar item.
     *
     * @param  string  $view  Blade view name rendering one or more navbar entries.
     * @param  int  $order  Lower numbers render first.
     * @param  (Closure(): bool)|null  $when  Optional visibility guard; the item is hidden when it returns false.
     */
    public function item(string $view, int $order = 100, ?Closure $when = null): static
    {
        $this->items[] = ['view' => $view, 'order' => $order, 'when' => $when];

        return $this;
    }

    /**
     * Ordered list of item view names that should currently be visible.
     *
     * @return list<string>
     */
    public function visibleItems(): array
    {
        $items = array_filter(
            $this->items,
            fn (array $item): bool => $item['when'] === null || ($item['when'])(),
        );

        usort($items, fn (array $a, array $b): int => $a['order'] <=> $b['order']);

        return array_values(array_column($items, 'view'));
    }
}
