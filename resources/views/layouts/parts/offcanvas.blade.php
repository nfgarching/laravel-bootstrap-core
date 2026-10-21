<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbarDark">

    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarDarkLabel">Seitenleiste</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">

            <li class="nav-item"><a class="nav-link active" href="#">Links</a>
                <ul class="">

                    <li class="nav-item">
                        <a class="nav-link" href="#" target=_blank>Link #1</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#" target=_blank>Link #2</a>
                    </li>

                </ul>
            </li>

            <hr>

            @auth
                @if (Route::has('settings.edit'))
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            {{ __('Login') }}: {{ auth()->user()->name }}
                        </a>
                        <ul class="">
                            <li class="nav-item">
                                <x-nav.link :href="route('settings.edit', auth()->user())">
                                    {{ __('Profile') }}
                                </x-nav.link>
                            </li>
                            <li class="nav-item">
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-nav.link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-nav.link>
                                </form>
                            </li>

                        </ul>
                    </li>
                    <hr>
                @endif
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

            <li class="nav-item text-info-emphasis">
                <center>
                    <small>
                        Hostname: {{ gethostname() }}
                        <br>
                        Database: {{ config('database.default') }} {{ config('database.connections.' . config('database.default') . '.host') }} / {{ config('database.connections.' . config('database.default') . '.database') }}
                        <br>
                        Datadisk: {{ config('filesystems.default') }}
                    </small>
                </center>
            </li>

            <li class="nav-item text-info-emphasis">
                <center>
                    <small>
                        ENV: {{ config('app.env') . ' - ' . config('app.name') . ' - ' . config('app.version') }}
                    </small>
                </center>
            </li>

        </ul>
    </div>
</div>
