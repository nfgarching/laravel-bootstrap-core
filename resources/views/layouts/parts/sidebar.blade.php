{{-- Navigationsbereiche, die jedes Feature-Package selbst über die
     SidebarNavigation-Registry beiträgt (Reihenfolge via order). --}}
<ul class="nav flex-column mb-2">
    @foreach (app(\Nfgarching\Core\Navigation\SidebarNavigation::class)->visibleSections() as $section)
        @includeIf($section)
    @endforeach
</ul>

<hr>

@if (Route::has('dashboard'))
    <h6>
        <x-nav.link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-nav.link>
    </h6>
@endif

<hr>

{{-- App-Shell: paketübergreifende Entwickler- und Infrastruktur-Menüs --}}
<ul class="nav flex-column mb-2">

    <li class="btn-group nav-item">{{-- Controllers --}}
        <a class="nav-link dropdown-toggle" href="#" role="a" data-bs-toggle="dropdown" aria-expanded="false">
            {{ __('Controllers') }}
        </a>
        <ul class="dropdown-menu">
            @if (Route::has('admin'))
                <li class="dropdown-item">
                    <x-nav.link href="{{ route('admin') }}" :active="request()->routeIs('admin')">
                        {{ __('Admin') }}
                    </x-nav.link>
                </li>
            @endif

            @if (Route::has('bookings.index'))
                <li class="dropdown-item">
                    <x-nav.link :href="route('bookings.index')">
                        {{ __('Bookings') }}
                    </x-nav.link>
                </li>
            @endif
            @if (Route::has('compose.index'))
                <li class="dropdown-item">
                    <x-nav.link :href="route('compose.index')" target="_blank">
                        {{ __('Compose') }}
                    </x-nav.link>
                </li>
            @endif

            @if (Route::has('documents.index'))
                <li class="dropdown-item">
                    <x-nav.link :href="route('documents.index')">
                        {{ __('Documents') }}
                    </x-nav.link>
                </li>
            @endif

            @if (Route::has('mailboxes'))
                <li class="dropdown-item">
                    <x-nav.link :href="route('mailboxes')">
                        {{ __('Imap') }}
                    </x-nav.link>
                </li>
            @endif

            @if (Route::has('names.index'))
                <li class="dropdown-item">
                    <x-nav.link :href="route('names.index')">
                        {{ __('Names') }}
                    </x-nav.link>
                </li>
            @endif

            @if (Route::has('items.index'))
                <li class="dropdown-item">
                    <x-nav.link :href="route('items.index')">
                        {{ __('Items') }}
                    </x-nav.link>
                </li>
            @endif
            @if (Route::has('props.index'))
                <li class="dropdown-item">
                    <x-nav.link :href="route('props.index')">
                        {{ __('Props') }}
                    </x-nav.link>
                </li>
            @endif

            @if (Route::has('records.index'))
                <li class="dropdown-item">
                    <x-nav.link :href="route('records.index')">
                        {{ __('Records') }}
                    </x-nav.link>
                </li>
            @endif

            @if (Route::has('registers.index'))
                <li class="dropdown-item">
                    <x-nav.link :href="route('registers.index')">
                        {{ __('Registers') }}
                    </x-nav.link>
                </li>
            @endif

            @if (Route::has('sipgate.index'))
                <li class="dropdown-item">
                    <x-nav.link :href="route('sipgate.index')">
                        {{ __('Sipgate') }}
                    </x-nav.link>
                </li>
            @endif
            @if (Route::has('tables.index'))
                <li class="dropdown-item">
                    <x-nav.link :href="route('tables.index')">
                        {{ __('Tabellen') }}
                    </x-nav.link>
                </li>
            @endif
            @if (Route::has('times.index'))
                <li class="dropdown-item">
                    <x-nav.link :href="route('times.index')">
                        {{ __('Timetoken') }}
                    </x-nav.link>
                </li>
            @endif
        </ul>
    </li>

    <li class="btn-group nav-item">{{-- Docker --}}
        <a class="nav-link dropdown-toggle" href="#" role="a" data-bs-toggle="dropdown"
            aria-expanded="false">
            {{ __('Docker') }}
        </a>
        <ul class="dropdown-menu">
            <li class="dropdown-item">
                <a class="nav-link" href="{{ config('app.url') }}:9000" target="_blank">
                    {{ __('Portainer') }}
                </a>
            </li>
            <li class="dropdown-item">
                <a class="nav-link" href="{{ config('app.url') }}:8025" target="_blank">
                    {{ __('Mailpit') }}
                </a>
            </li>
            <li class="dropdown-item">
                <a class="nav-link" href="{{ config('app.url') }}:7700" target="_blank">
                    {{ __('Meilisearch') }}
                </a>
            </li>
            <li class="dropdown-item">
                <a class="nav-link" href="{{ config('app.url') }}:9010" target="_blank">
                    {{ __('phpMyAdmin') }}
                </a>
            </li>
            <li class="dropdown-item">
                <a class="nav-link" href="/telescope" target="_blank">
                    {{ __('Telescope') }}
                </a>
            </li>
        </ul>
    </li>

    @if (Route::has('startpage'))
        <li class="nav-item">
            <x-nav.link href="{{ route('startpage') }}" :active="request()->routeIs('startpage')">
                {{ __('Startpage') }}
            </x-nav.link>
        </li>
    @endif

    <li class="btn-group nav-item">
        <a class="nav-link dropdown-toggle" href="#" role="a" data-bs-toggle="dropdown"
            aria-expanded="false">
            {{ __('Testpages') }}
        </a>
        <ul class="dropdown-menu">
            <li class="dropdown-item">
                <a class="nav-link" href="/guestpage" target="_blank">
                    {{ __('Guestpage') }}
                </a>
            </li>
            <li class="dropdown-item">
                <a class="nav-link" href="/telescope" target="_blank">
                    {{ __('Telescope') }}
                </a>
            </li>
            <li class="dropdown-item">
                <a class="nav-link" href="/testing" target="_blank">
                    {{ __('Testing') }}
                </a>
            </li>
            <li class="dropdown-item">
                <a class="nav-link" href="/testpage" target="_blank">
                    {{ __('Testpage') }}
                </a>
            </li>
            @if (Route::has('chatbot.testpage'))
                <li class="dropdown-item">
                    <a class="nav-link" href="{{ route('chatbot.testpage') }}" target="_blank">
                        {{ __('Testpage Chatbot') }}
                    </a>
                </li>
            @endif

            <li class="dropdown-item">
                <a class="nav-link" href="{{ route('textgenerator.testpage') }}" target="_blank">
                    {{ __('Testpage Textgenerator') }}
                </a>
            </li>
            <li class="dropdown-item">
                <a class="nav-link" href="/welcome" target="_blank">
                    {{ __('Welcome') }}
                </a>
            </li>
        </ul>
    </li>

</ul>

<hr>

<h6>{{ __('Externe Links') }}</h6>
<hr>
<ul class="nav flex-column mb-2">
    <li class="nav-item"><a class="nav-link" href="#" target="_blank">{{ __('#') }}</a></li>
</ul>

<hr>
{{-- <center><x-copyright /></center> --}}
<center><i class="bi bi-c-circle"></i> {{ date('Y') }}</center>
<br>
