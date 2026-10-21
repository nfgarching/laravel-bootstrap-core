<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm mt-5">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Login</h2>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <x-form action="{{ route('login') }}">
                            <div class="mb-3">
                                <x-form.input
                                    type="email"
                                    field="email"
                                    label="Email"
                                    :value="old('email')"
                                    attri="required autofocus autocomplete='username'"
                                />
                            </div>

                            <div class="mb-3">
                                <x-form.input
                                    type="password"
                                    field="password"
                                    label="Password"
                                    attri="required autocomplete='current-password'"
                                />
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
