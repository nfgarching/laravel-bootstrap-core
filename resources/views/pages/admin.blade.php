<x-main-layout>

    <x-slot name="header">
        <div class="container">
            <div class="btn-group btn-group">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Administration') }}
                </h2>
            </div>
        </div>
        <hr>
    </x-slot>

    <div class="container">

        <div class="row">

            <div class="col-md-6">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Account</h3>
                        <div class="btn-group btn-group-sm">
                            <x-button.link :href="route('settings.edit', auth()->user())" type="outline-primary">
                                <i class="bi bi-gear"></i> Settings
                            </x-button.link>
                            <x-button.link :href="route('logout')" type="outline-danger"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </x-button.link>
                        </div>
                        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                            @csrf
                        </form>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li><strong>Name:</strong> {{ auth()->user()->name }}</li>
                            <li><strong>Email:</strong> {{ auth()->user()->email }}</li>
                            <li><strong>Member since:</strong> {{ auth()->user()->created_at?->format('F d, Y') }}</li>
                        </ul>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Env</h3>
                        <i class="bi bi-hdd-stack"></i>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm small mb-0">
                            <tr>
                                <th class="w-25">ENV</th>
                                <td>{{ config('app.env') . ' - ' . config('app.name') . ' - ' . config('app.version') }}
                                </td>
                            </tr>
                            <tr>
                                <th>Hostname</th>
                                <td>{{ gethostname() }}</td>
                            </tr>
                            <tr>
                                <th>Database</th>
                                <td>{{ config('database.default') }}
                                    {{ config('database.connections.' . config('database.default') . '.host') }} /
                                    {{ config('database.connections.' . config('database.default') . '.database') }}
                                </td>
                            </tr>
                            <tr>
                                <th>DB AkV</th>
                                <td>{{ config('database.connections.akv.host') }} /
                                    {{ config('database.connections.akv.database') }}</td>
                            </tr>
                            <tr>
                                <th>Datadisk</th>
                                <td>{{ config('filesystems.default') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <x-card>
                    <x-slot name="headerLeft">
                        User Statistics ({{ \App\Models\User::count() }} total)
                    </x-slot>
                    <x-slot name="headerRight"></x-slot>

                    <x-table class="table-sm">
                        @forelse (\App\Models\User::limit(5)->get() as $user)
                            <tr>
                                <td>
                                    <x-button.link :href="Route::has('user.show') ? route('user.show', $user) : '#'" type="outline-primary">
                                        <i class="bi bi-person"></i>
                                    </x-button.link>
                                </td>
                                <td>{{ $user->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">--- No Users ---</td>
                            </tr>
                        @endforelse
                    </x-table>
                </x-card>

                {{-- Externe Tools: Erreichbarkeit prüft das Script unten live im Browser --}}
                @php
                    $tools = collect([
                        ['label' => 'Goolge', 'href' => 'https://www.google.com'],
                        ['label' => 'Guestpage', 'href' => '/guestpage'],
                        ['label' => 'Mailcatcher', 'href' => config('app.url') . ':1080'],
                        ['label' => 'Mailpit', 'href' => config('app.url') . ':8025'],
                        ['label' => 'Meilisearch', 'href' => config('app.url') . ':7700'],
                        ['label' => 'phpMyAdmin', 'href' => config('app.url') . ':9010'],
                        ['label' => 'Portainer', 'href' => config('app.url') . ':9000'],
                        ['label' => 'Telescope', 'href' => '/telescope'],
                        ['label' => 'Testing', 'href' => '/testing'],
                        ['label' => 'Testpage', 'href' => '/testpage'],
                        ['label' => 'Welcome', 'href' => '/welcome'],

                        ['label' => 'Calls', 'href' => 'https://calls.froehler-kollegen.de/'],
                        ['label' => 'Download', 'href' => 'https://download.froehler-kollegen.de/'],

                        ['label' => 'Demoseite', 'href' => 'https://demo.phpmyoffice.de/'],
                        ['label' => 'Testseite', 'href' => 'https://test.phpmyoffice.de/'],

                    ])->sortBy('label', SORT_NATURAL | SORT_FLAG_CASE);
                    $tools = $tools->chunk((int) ceil($tools->count() / 2));
                @endphp
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Tools</h3>
                        <i class="bi bi-tools"></i>
                    </div>
                    <div class="row g-0">
                        @foreach ($tools as $spalte)
                            <div class="col-6">
                                <div class="list-group list-group-flush">
                                    @foreach ($spalte as $tool)
                                        <a class="list-group-item list-group-item-action disabled" href="{{ $tool['href'] }}" target="_blank" aria-disabled="true" data-tool-check>
                                            {{ __($tool['label']) }}
                                            <small class="text-muted" data-tool-status>(prüfe …)</small>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <script>
                    // Eigene Seiten: nur 2xx gilt als erreichbar (Weiterleitung, z.B. Guestpage → Dashboard, nicht).
                    // Fremde Ports: no-cors liefert keinen Status, aber eine Antwort überhaupt heißt "Dienst läuft".
                    (() => {
                        const check = async (link) => {
                            const url = new URL(link.href);
                            const sameOrigin = url.origin === location.origin;
                            const signal = AbortSignal.timeout(4000);
                            try {
                                if (sameOrigin) {
                                    const res = await fetch(url, { redirect: 'manual', signal });
                                    return res.ok;
                                }
                                await fetch(url, { mode: 'no-cors', signal });
                                return true;
                            } catch {
                                return false;
                            }
                        };

                        document.querySelectorAll('[data-tool-check]').forEach(async (link) => {
                            const status = link.querySelector('[data-tool-status]');
                            if (await check(link)) {
                                link.classList.remove('disabled');
                                link.removeAttribute('aria-disabled');
                                status.outerHTML = '<i class="bi bi-box-arrow-up-right small"></i>';
                            } else {
                                status.textContent = '(nicht verfügbar)';
                            }
                        });
                    })();
                </script>

            </div>

            <div class="col-md-6">

                {{-- Client-Infos: serverseitig aus dem Request, browserseitig per JS --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Browser</h3>
                        <i class="bi bi-globe"></i>
                    </div>
                    <div class="card-body">
                        <h6>Request (Server)</h6>
                        <table class="table table-sm small mb-3">
                            <tr>
                                <th class="w-25">IP (Laravel)</th>
                                <td>{{ request()->ip() }}</td>
                            </tr>
                            <tr>
                                <th>IP-Kette</th>
                                <td>{{ implode(', ', request()->ips()) }}</td>
                            </tr>
                            <tr>
                                <th>REMOTE_ADDR</th>
                                <td>{{ request()->server('REMOTE_ADDR') }}</td>
                            </tr>
                            <tr>
                                <th>X-Forwarded-For</th>
                                <td>{{ request()->header('X-Forwarded-For') ?? '–' }}</td>
                            </tr>
                            <tr>
                                <th>X-Real-IP</th>
                                <td>{{ request()->header('X-Real-IP') ?? '–' }}</td>
                            </tr>
                            <tr>
                                <th>Host / Schema</th>
                                <td>{{ request()->getHost() }}
                                    ({{ request()->getScheme() }}{{ request()->secure() ? ', TLS' : '' }})</td>
                            </tr>
                            <tr>
                                <th>Server</th>
                                <td>{{ request()->server('SERVER_ADDR') }}:{{ request()->server('SERVER_PORT') }} –
                                    {{ request()->server('SERVER_SOFTWARE') ?? PHP_SAPI }}</td>
                            </tr>
                            <tr>
                                <th>Protokoll</th>
                                <td>{{ request()->server('SERVER_PROTOCOL') }}</td>
                            </tr>
                            <tr>
                                <th>User-Agent</th>
                                <td class="text-break">{{ request()->userAgent() }}</td>
                            </tr>
                            <tr>
                                <th>Sprachen</th>
                                <td>{{ request()->header('Accept-Language') }}</td>
                            </tr>
                            <tr>
                                <th>Referer</th>
                                <td class="text-break">{{ request()->header('Referer') ?? '–' }}</td>
                            </tr>
                            <tr>
                                <th>Session</th>
                                <td>{{ config('session.driver') }} – {{ substr(session()->getId(), 0, 8) }}…</td>
                            </tr>
                        </table>

                        <h6>Browser (Client)</h6>
                        <table class="table table-sm small mb-0" id="client-info">
                            <tr>
                                <th class="w-25">Plattform</th>
                                <td data-info="platform"></td>
                            </tr>
                            <tr>
                                <th>Sprache</th>
                                <td data-info="language"></td>
                            </tr>
                            <tr>
                                <th>Zeitzone</th>
                                <td data-info="timezone"></td>
                            </tr>
                            <tr>
                                <th>Bildschirm</th>
                                <td data-info="screen"></td>
                            </tr>
                            <tr>
                                <th>Fenster</th>
                                <td data-info="viewport"></td>
                            </tr>
                            <tr>
                                <th>Farbschema</th>
                                <td data-info="scheme"></td>
                            </tr>
                            <tr>
                                <th>CPU-Kerne / RAM</th>
                                <td data-info="hardware"></td>
                            </tr>
                            <tr>
                                <th>Verbindung</th>
                                <td data-info="connection"></td>
                            </tr>
                            <tr>
                                <th>Cookies / Online</th>
                                <td data-info="status"></td>
                            </tr>
                            <tr>
                                <th>Sicherer Kontext</th>
                                <td data-info="secure"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <script>
                    (() => {
                        const n = navigator;
                        const c = n.connection;
                        const info = {
                            platform: n.userAgentData?.platform || n.platform,
                            language: (n.languages || [n.language]).join(', '),
                            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                            screen: `${screen.width}×${screen.height} @${window.devicePixelRatio}x, ${screen.colorDepth} bit`,
                            viewport: `${window.innerWidth}×${window.innerHeight}`,
                            scheme: matchMedia('(prefers-color-scheme: dark)').matches ? 'dunkel' : 'hell',
                            hardware: `${n.hardwareConcurrency ?? '?'} / ${n.deviceMemory ? n.deviceMemory + ' GB' : '?'}`,
                            connection: c ? `${c.effectiveType}, ${c.downlink} Mbit/s, RTT ${c.rtt} ms` : '–',
                            status: `${n.cookieEnabled ? 'ja' : 'nein'} / ${n.onLine ? 'ja' : 'nein'}`,
                            secure: window.isSecureContext ? 'ja' : 'nein',
                        };
                        document.querySelectorAll('#client-info [data-info]').forEach(td => {
                            td.textContent = info[td.dataset.info] ?? '–';
                        });
                    })();
                </script>

            </div>

        </div>

    </div>

</x-main-layout>
