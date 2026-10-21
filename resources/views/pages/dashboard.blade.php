<x-main-layout>
    <div class="container py-5">
        <div class="row">

            <div class="col-md-6">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Dashboard</h4>
                    </div>
                    <div class="card-body">

                        @auth
                            Willkommen, {{ auth()->user()?->name }}
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
                                <br>Database: {{ config('database.default') }} {{ config('database.connections.' . config('database.default') . '.host') }} / {{ config('database.connections.' . config('database.default') . '.database') }}
                                {{-- <br>DB AkV: {{ env('DB_AKV_HOST') }} / {{ env('DB_AKV_DATABASE') }} --}}
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
                        <x-slot name="headerRight">
                            @if (Route::has('users.index'))
                                <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                            @endif
                        </x-slot>

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

                        {{-- <x-desc.list :count="5" :item="\App\Models\User::get()->toArray()" /> --}}

                    </x-card>
                @endauth

            </div>

            <div class="col-md-6">

                @auth

                    <x-card>
                        <x-slot name="headerLeft">
                            <h4 class="mb-0">Quick Actions</h4>
                        </x-slot>
                        <x-slot name="headerRight"></x-slot>

                        <div class="d-grid gap-2">
                            @if (Route::has('users.create'))
                                <a href="{{ route('users.create') }}" class="btn btn-primary">Create New User</a>
                            @endif
                            @if (Route::has('bookings.create'))
                                <a href="{{ route('bookings.create') }}" class="btn btn-secondary">New Booking</a>
                            @endif
                            @if (Route::has('files.create'))
                                <a href="{{ route('files.create') }}" class="btn btn-success">Upload File</a>
                            @endif
                            @if (Route::has('documents.create'))
                                <a href="{{ route('documents.create') }}" class="btn btn-info">New Document</a>
                            @endif
                            @if (Route::has('mails.create'))
                                <a href="{{ route('mails.create') }}" class="btn btn-warning">Send Mail</a>
                            @endif
                            @if (Route::has('names.create'))
                                <a href="{{ route('names.create') }}" class="btn btn-dark">Add Name</a>
                            @endif

                            @if (Route::has('registers.create'))
                                <a href="{{ route('registers.create') }}" class="btn btn-outline-primary">New Register</a>
                            @endif
                        </div>
                    </x-card>

                    @if (Route::has('mails.index'))
                        <livewire:mails.status />
                    @endif

                    <x-card>
                        <x-slot name="headerLeft">
                            {{ __('Livewire Listings') }}
                        </x-slot>
                        <x-slot name="headerRight"></x-slot>

                        @php $token = rand(); @endphp
                        <x-accordion :acc_id="$token" :flush="false">

                            @if (Route::has('bookings.index'))
                                <x-accordion.item :acc_id="$token" header="Bookings">
                                    <livewire:listing.bookings />
                                </x-accordion.item>
                            @endif

                            @if (Route::has('files.index'))
                                <x-accordion.item :acc_id="$token" header="Files">
                                    <livewire:listing.files />
                                </x-accordion.item>
                            @endif

                            @if (Route::has('documents.indexx'))
                                <x-accordion.item :acc_id="$token" header="Documents">
                                    <livewire:listing.documents />
                                </x-accordion.item>
                            @endif

                            @if (Route::has('mails.index'))
                                <x-accordion.item :acc_id="$token" header="Mails">
                                    <livewire:listing.mails />
                                </x-accordion.item>
                            @endif

                            @if (Route::has('names.index'))
                                <x-accordion.item :acc_id="$token" header="Names">
                                    <livewire:listing.names />
                                </x-accordion.item>
                            @endif

                            @if (Route::has('registers.indexx'))
                                <x-accordion.item :acc_id="$token" header="Register">
                                    <livewire:listing.registers />
                                </x-accordion.item>
                            @endif

                        </x-accordion>

                    </x-card>

                @endauth

                <hr>
                <h5>{{ __('Other Components') }}</h5>
                <hr>

                ...

            </div>

        </div>
    </div>
</x-main-layout>
