<x-main-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
        <hr>
    </x-slot>

    <div class="container">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row mt-4">

            <div class="col-xl-3">

                <div class="card shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="bi bi-person-circle" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="card-title mb-1">{{ $user->name }}</h5>
                        <p class="text-muted small">{{ $user->email }}</p>
                    </div>
                </div>

            </div>

            <div class="col-xl-9">

                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Profil</h5>
                    </div>
                    <div class="card-body p-4">
                        <x-form :action="route('settings.update', $user->id)" method="POST">
                            @method('PATCH')
                            <div class="mb-3">
                                <x-form.input
                                    type="text"
                                    field="name"
                                    label="Name"
                                    :value="old('name', $user->name)"
                                    attri="required autocomplete='name'"
                                />
                            </div>

                            <div class="mb-3">
                                <x-form.input
                                    type="email"
                                    field="email"
                                    label="Email"
                                    :value="old('email', $user->email)"
                                    attri="required autocomplete='email'"
                                    disabled
                                />
                            </div>

                            <x-form.submit class="btn-primary">
                                Speichern
                            </x-form.submit>
                        </x-form>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Passwort ändern</h5>
                    </div>
                    <div class="card-body p-4">
                        <x-form :action="route('settings.update', $user->id)" method="POST">
                            @method('PATCH')
                            <div class="mb-3">
                                <x-form.input
                                    type="password"
                                    field="current_password"
                                    label="Aktuelles Passwort"
                                    attri="autocomplete='current-password'"
                                />
                            </div>

                            <div class="mb-3">
                                <x-form.input
                                    type="password"
                                    field="password"
                                    label="Neues Passwort"
                                    attri="autocomplete='new-password'"
                                />
                            </div>

                            <div class="mb-3">
                                <x-form.input
                                    type="password"
                                    field="password_confirmation"
                                    label="Passwort bestätigen"
                                    attri="autocomplete='new-password'"
                                />
                            </div>

                            <x-form.submit class="btn-primary">
                                Passwort aktualisieren
                            </x-form.submit>
                        </x-form>
                    </div>
                </div>

            </div>

        </div>

    </div>

</x-main-layout>
