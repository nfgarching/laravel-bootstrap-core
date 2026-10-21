{{-- x-tags: card --}}
<div {{ $attributes->merge(['class' => 'card']) }}>


    @if (isset($header))
        {{ $header }}
    @else
        <div class="card-header">
            <div class="float-start">
                @if (isset($headerLeft))
                    {{ $headerLeft }}
                @else
                    <p class="card-text"><small class="text-muted">.get_cardheader_left</small></p>
                @endif
            </div>
            <div class="float-end">
                @if (isset($headerRight))
                    {{ $headerRight }}
                @else
                    <p class="card-text"><small class="text-muted">.get_cardheader_right</small></p>
                @endif
            </div>
        </div>
    @endif

    <div class="card-body">

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        {{ $slot }}

    </div>

    @if (isset($footer))
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif

</div>
