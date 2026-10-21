<?php

namespace Nfgarching\Core\Navigation;

use Closure;

/**
 * Registry for sidebar navigation sections contributed by feature packages.
 *
 * Each package registers its own section view in its ServiceProvider's boot()
 * method, instead of the core package hardcoding every package's routes.
 * The sidebar then simply renders the ordered, visible sections.
 */
class SidebarNavigation
{
    /**
     * @var list<array{view: string, order: int, when: ?Closure}>
     */
    private array $sections = [];

    /**
     * Register a navigation section.
     *
     * @param  string  $view  Blade view name rendering one or more sidebar entries.
     * @param  int  $order  Lower numbers render first.
     * @param  (Closure(): bool)|null  $when  Optional visibility guard; the section is hidden when it returns false.
     */
    public function section(string $view, int $order = 100, ?Closure $when = null): static
    {
        $this->sections[] = ['view' => $view, 'order' => $order, 'when' => $when];

        return $this;
    }

    /**
     * Ordered list of section view names that should currently be visible.
     *
     * @return list<string>
     */
    public function visibleSections(): array
    {
        $sections = array_filter(
            $this->sections,
            fn (array $section): bool => $section['when'] === null || ($section['when'])(),
        );

        usort($sections, fn (array $a, array $b): int => $a['order'] <=> $b['order']);

        return array_values(array_column($sections, 'view'));
    }
}
