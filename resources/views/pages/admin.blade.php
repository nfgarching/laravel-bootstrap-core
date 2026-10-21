<x-main-layout>

    <x-slot name="header">
        <div class="container">
            <div class="btn-group btn-group">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Administration') }}
                </h2>
            </div>
            <div class="btn-group btn-group float-end">
                @auth
                    <x-button.link :href="route('settings.edit', auth()->user())" type="outline-primary">
                        <i class="bi bi-person"></i>
                    </x-button.link>
                    <x-button.link :href="route('logout')" type="outline-danger"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                    </x-button.link>
                @endauth
            </div>
        </div>
        <hr>
    </x-slot>

    <div class="container">

        <div class="row">

            <div class="col-md-6">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Env</h3>
                    </div>
                    <div class="card-body">

                        @auth
                            <p class="text-muted">You are logged in to your account.</p>
                            <div class="mt-4">
                                <h6>Account Information:</h6>
                                <ul class="list-unstyled">
                                    <li><strong>Name:</strong> {{ auth()->user()?->name }}</li>
                                    <li><strong>Email:</strong> {{ auth()->user()?->email }}</li>
                                    <li><strong>Member since:</strong> {{ auth()->user()?->created_at?->format('F d, Y') }}
                                    </li>
                                </ul>
                            </div>
                            <small>
                                ENV: {{ config('app.env') . ' - ' . config('app.name') . ' - ' . config('app.version') }}
                                <br>Hostname: {{ gethostname() }}
                                <br>Database: {{ config('database.default') }}
                                {{ config('database.connections.' . config('database.default') . '.host') }} /
                                {{ config('database.connections.' . config('database.default') . '.database') }}
                                <br>DB AkV: {{ config('database.connections.akv.host') }} /
                                {{ config('database.connections.akv.database') }}
                                <br>Datadisk: {{ config('filesystems.default') }}
                            </small>
                        @else
                            <x-form action="{{ route('login') }}">
                                <div class="mb-3">
                                    <x-form.input type="email" field="email" label="Email" :value="old('email')"
                                        attri="required autofocus autocomplete='username'" />
                                </div>

                                <div class="mb-3">
                                    <x-form.input type="password" field="password" label="Password"
                                        attri="required autocomplete='current-password'" />
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>

                                <div class="d-grid gap-2">
                                    <x-form.submit class="btn-primary">
                                        Login
                                    </x-form.submit>
                                </div>

                                <div class="text-center mt-3">
                                    <p class="text-muted">
                                        Don't have an account?
                                        <a href="{{ route('register') }}" class="text-decoration-none">Register</a>
                                    </p>
                                </div>
                            </x-form>
                        @endauth

                    </div>
                </div>

                @auth

                    <x-card>
                        <x-slot name="headerLeft">
                            User Statistics ({{ \App\Models\User::count() }} total)
                        </x-slot>
                        <x-slot name="headerRight"></x-slot>

                        <x-table class="table-sm">
                            @forelse (\App\Models\User::limit(5)->get() as $user)
                                <tr>
                                    <td>
                                        <x-button.link :href="Route::has('user.show') ? route('user.show', $name) : '#'" type="outline-primary">
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

                    {{-- <x-card>
                        <x-slot name="headerLeft">
                            User Statistics ({{ \App\Models\User::count() }} total)
                        </x-slot>
                        <x-desc.list :count="5" :item="\App\Models\User::get()->toArray()" />
                    </x-card> --}}
                @endauth

                {{-- <x-desc.group class="small">
                    <x-desc label="APP_ENV" :value="config('app.env')" />
                    <x-desc label="DB_CONNECTION" :value="config('database.default')" />
                    <x-desc label="Users" :value="\App\Models\User::count()" />
                </x-desc.group> --}}

                {{-- <x-desc.list :item="\App\Models\User::get()->toArray()" /> --}}

            </div>

            <div class="col-md-6">

                <h4>{{ __('Configuration') }}</h4>
                <hr>
                Configuration details for the database connections
                <hr>
                <x-desc.list :item="config('database.connections')" />
                <hr>
                Configuration details for the filesystem disks
                <hr>
                <x-desc.list :item="config('filesystems.disks')" />

            </div>

        </div>

    </div>

</x-main-layout>
