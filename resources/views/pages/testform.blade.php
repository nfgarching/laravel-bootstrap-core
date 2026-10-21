<x-main-layout>

    <x-slot name="header">
        <h2>{{ __('Testform') }}</h2>
        <hr>
    </x-slot>

    <div class="container">
        <div class="row">

            <div class="col-xl-6">

                <x-card>

                    <x-slot name="headerLeft">
                        <x-button.group>
                            <x-dropdown.list>
                                <x-dropdown.click>dropdown-click</x-dropdown.click>
                                <div class="dropdown-divider"></div>
                                <x-dropdown.link>dropdown-link</x-dropdown.link>
                            </x-dropdown.list>
                            <x-button>Button</x-button>
                            <x-button.link>Link</x-button.link>
                            <x-button.click>Click</x-button.click>
                            <x-button.cancel>Cancel</x-button.cancel>
                            <x-button.dispatch>Dispatch</x-button.dispatch>
                            {{-- <x-button.file-link>File</x-button.file-link> --}}
                            {{-- <x-button.model>Model</x-button.model> --}}
                            {{-- <x-button.wire>Wire</x-button.wire> --}}
                            {{-- <x-button.props>Props</x-button.props> --}}
                            {{-- <x-button.submit>Submit</x-button.submit> --}}
                        </x-button.group>
                    </x-slot>

                    <x-slot name="headerRight"></x-slot>

                    <x-form>
                        <x-form.input field="name" />
                        <x-form.input field="email" />
                        <x-form.input field="password" type="password" />
                        <x-form.input field="password_confirmation" label="Password Confirmation" type="password" />

                        <x-form.text field="textarea" />

                        <x-form.input field="checkbox" label="Checkbox" placeholder="Checkbox" type="checkbox" />
                        <x-form.input field="radio" label="Radio" placeholder="Radio" type="radio" />

                        <x-form.select field="select">
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option>
                            <option value="4">Option 4</option>
                            <option value="5">Option 5</option>
                        </x-form.select>

                        <x-form.input field="file" type="file" />
                        <x-form.input field="color" type="color" />
                        <x-form.input field="date" type="date" />
                        <x-form.input field="datetime-local" type="datetime-local" />
                        <x-form.input field="month" type="month" />
                        <x-form.input field="number" type="number" />
                        <x-form.input field="range" type="range" />
                        <x-form.input field="search" type="search" />
                        <x-form.input field="time" type="time" />
                        <x-form.input field="week" type="week" />
                        {{-- <x-form.input field="hidden" type="hidden" /> --}}

                        <br>
                        <x-form.cancel /><x-form.submit />

                    </x-form>

                </x-card>

            </div>

            <div class="col-xl-6">

                <x-card>

                    <x-form>
                        <x-field.input field="name" />
                        <x-field.input field="email" />
                        <x-field.input field="password" type="password" />
                        <x-field.input field="password_confirmation" label="" placeholder="Password Confirmation"
                            type="password" />

                        <x-field.text field="textarea" />

                        <x-field.input field="checkbox" label="Checkbox" placeholder="Checkbox" type="checkbox" />
                        <x-field.input field="radio" label="Radio" placeholder="Radio" type="radio" />

                        <x-field.select field="select">
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option>
                            <option value="4">Option 4</option>
                            <option value="5">Option 5</option>
                        </x-field.select>

                        <x-field.input field="file" type="file" />
                        <x-field.input field="color" type="color" />
                        <x-field.input field="date" type="date" />
                        <x-field.input field="datetime-local" type="datetime-local" />
                        <x-field.input field="month" type="month" />
                        <x-field.input field="number" type="number" />
                        <x-field.input field="range" type="range" />
                        <x-field.input field="search" type="search" />
                        <x-field.input field="time" type="time" />
                        <x-field.input field="week" type="week" />
                        {{-- <x-field.input field="hidden" type="hidden" /> --}}

                        <x-form.cancel /><x-form.submit />

                    </x-form>

                </x-card>

            </div>

        </div>
    </div>

</x-main-layout>