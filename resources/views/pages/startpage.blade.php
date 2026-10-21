<x-main-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Startpage') }}
        </h2>
        <hr>
    </x-slot>

    <div class="container">

        <div class="row">

            <div class="col-xl-6">

                <x-card>

                    <x-slot name="headerLeft">
                        <h5>
                            {{ __('welcome') }}
                        </h5>
                    </x-slot>

                    <x-form>

                        <x-form.input field="test" />

                        <div class="form-group mb-3">
                            <label for="contact">Contact:</label>
                            <input
                                class="form-control 
                                @error('contact') is-invalid @enderror 
                                @if (old('contact')) is-valid @endif"
                                type="text" name="contact" id="contact" value="{{ old('contact') }}">
                            <div class="invalid-feedback">
                                @error('contact')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="phonenumber">Validate New Phonenumber:</label>
                            <input
                                class="form-control 
                                @error('phonenumber') is-invalid @enderror 
                                @if (old('phonenumber')) is-valid @endif"
                                type="text" name="phonenumber" id="phonenumber" value="{{ old('phonenumber') }}">
                            <div class="invalid-feedback">
                                @error('phonenumber')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>


                    </x-form>

                </x-card>

            </div>

            <div class="col-xl-6">

                @livewire('counter')

                ---

                <livewire:counter />

            </div>

        </div>

    </div>

</x-main-layout>
