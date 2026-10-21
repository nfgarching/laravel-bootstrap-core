{{-- x-tags: table --}}
@props([
    'acc_id' => 'accordionExample',
    'colspan' => 1,
    'toggleChecks' => false, // Standard: false (Checkboxes ausgeblendet)
])

<div class="table-responsive">
    <table {{ $attributes->merge(['class' => 'table']) }}>

        @if (isset($header))
            <thead>
                <tr>
                    <x-table.head.toggle :$toggleChecks>
                        {{ $header }}
                    </x-table.head.toggle>
                </tr>
            </thead>
        @endif

        <tbody class="accordion" id="{{ $acc_id }}">
            {{ $slot }}
        </tbody>

        @if (isset($footer))
            @if ($toggleChecks)
                {{-- <tr>
                    <td colspan="{{ $colspan }}">
                        <x-table.check />
                    </td>
                </tr> --}}
                <tr>
                    <td colspan="{{ $colspan }}">
                        {{ $footer }}
                    </td>
                </tr>
            @endif
        @endif

    </table>
</div>
