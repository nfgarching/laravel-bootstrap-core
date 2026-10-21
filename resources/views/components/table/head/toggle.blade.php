{{-- x-tags: table.head.toggle --}}
@props([
    'toggleChecks' => true, // Standard: false (Checkboxes ausgeblendet)
])

@if ($toggleChecks)
    {{-- Alpine statt @pushOnce('scripts'): Gepushte Scripts erreichen das Layout nicht,
         wenn die Tabelle in einer (lazy) Livewire-Komponente nachgeladen wird. Wirkt nur
         auf die Checkboxen der eigenen Tabelle und setzt alle an bzw. aus. --}}
    <th width="1%" x-data="{ on: false }">
        <i role="button" :class="on ? 'bi bi-toggle-on' : 'bi bi-toggle-off'" class="bi bi-toggle-off"
            x-on:click="on = ! on; $el.closest('table').querySelectorAll('tbody input[type=checkbox]').forEach(box => box.checked = on)"></i>

        {{ $slot }}

    </th>
@else
    {{-- Leere Spalte für die (ausgeblendeten) Checkboxen, die Spaltenköpfe bleiben --}}
    <th width="1%"></th>

    {{ $slot }}
@endif
