{{-- x-tags: counter --}}
@props([
    'startTime' => microtime(true),
])

@php
    $startTimeFloat = is_numeric($startTime) ? (float) $startTime : 0.0;
@endphp

@if ($startTimeFloat != 0.0)
    <div class="container">
        <p class="small text-muted float-end" style="font-size:8pt;">
            @php
                // echo date('H:i:s') . ' - Query took ' . number_format(microtime(true) - $startTimeFloat, 4) . ' seconds to execute.';
                echo 'Query took ' . number_format(microtime(true) - $startTimeFloat, 4) . ' seconds to execute.';
            @endphp
        </p>
    </div>
@endif
