<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm mt-5">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Register</h2>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <x-form action="{{ route('register') }}">
                            <div class="mb-3">
                                <x-form.input
                                    type="text"
                                    field="name"
                                    label="Name"
                                    :value="old('name')"
                                    attri="required autofocus autocomplete='name'"
                                />
                            </div>

                            <div class="mb-3">
                                <x-form.input
                                    type="email"
                                    field="email"
                                    label="Email"
                                    :value="old('email')"
                                    attri="required autocomplete='username'"
                                />
                            </div>

                            <div class="mb-3">
                                <x-form.input
                                    type="password"
                                    field="password"
                                    label="Password"
                                    attri="required autocomplete='new-password'"
                                />
                            </div>

                            <div class="mb-3">
                                <x-form.input
                                    type="password"
                                    field="password_confirmation"
                                    label="Confirm Password"
                                    attri="required autocomplete='new-password'"
                                />
                            </div>

                            <div class="d-grid gap-2">
                                <x-form.submit class="btn-primary">
                                    Register
                                </x-form.submit>
                            </div>

                            <div class="text-center mt-3">
                                <p class="text-muted">
                                    Already have an account?
                                    <a href="{{ route('login') }}" class="text-decoration-none">Login</a>
                                </p>
                            </div>
                        </x-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
