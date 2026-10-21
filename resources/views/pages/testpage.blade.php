<x-guest-layout>

    <x-slot name="header">
        <h2>{{ __('Testpage') }}</h2>
        <hr>
    </x-slot>

    <div class="container">
        <div class="row">

            <div class="col-xl-6">

                <x-alert message="This is a danger alert—check it out!" />
                <x-alert type="danger" message="This is a danger alert—check it out!" />
                <x-alert type="info" message="This is an info alert—check it out!" />
                <x-alert type="success" message="This is a success alert—check it out!" />
                <x-alert type="warning" message="This is a warning alert—check it out!" />

                <x-card>

                    <x-slot name="headerLeft">
                        <x-button.group>
                            <x-dropdown.list>
                                <x-dropdown.click>dropdown-click</x-dropdown.click>
                                <div class="dropdown-divider"></div>
                                <x-dropdown.link>dropdown-link</x-dropdown.link>
                            </x-dropdown.list>
                            <x-button>Button</x-button>
                            <x-button.cancel>Cancel</x-button.cancel>
                            <x-button.dispatch>Dispatch</x-button.dispatch>
                            {{-- <x-button.file-link>File</x-button.file-link> --}}
                            <x-button.link>Link</x-button.link>
                            {{-- <x-button.model>Model</x-button.model> --}}
                            {{-- <x-button.wire>Wire</x-button.wire> --}}
                            {{-- <x-button.props>Props</x-button.props> --}}
                            {{-- <x-button.submit>Submit</x-button.submit> --}}
                        </x-button.group>
                    </x-slot>

                    <x-slot name="headerRight">
                        <x-dropdown class="dropstart">...</x-dropdown>
                    </x-slot>

                    <x-table>
                        <x-slot:head>
                            <x-table.head.sort field="#" />
                            <x-table.head.sort field="First" />
                            <x-table.head.sort field="Last" />
                            <x-table.head.sort field="Handle" />
                        </x-slot:head>
                        <x-table.row>
                            <th>#1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </x-table.row>
                        <x-table.row>
                            <th>#2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </x-table.row>
                        <x-table.row>
                            <th>#3</th>
                            <td>John</td>
                            <td>Smith</td>
                            <td>@social</td>
                        </x-table.row>
                    </x-table>

                    <x-slot name="footer">

                        <x-button.group>
                            <x-modal button="Modal">
                                ...
                            </x-modal>
                            <x-modal.save button="Modal_SAVE" modid="12345">
                                ...
                            </x-modal.save>
                            <x-modal.button modid="12345">
                                Modal Save
                            </x-modal.button>
                        </x-button.group>

                    </x-slot>

                </x-card>

            </div>

            <div class="col-xl-6">

                <x-card>

                    <x-slot name="headerLeft">
                        <x-table.filter>
                            x-table.filter.search
                            {{-- <x-table.filter.search   field="search" /> --}}
                            {{-- <x-table.filter.dropdown field="status" /> --}}
                            {{-- <x-table.filter.dropdown field="typ"    /> --}}
                        </x-table.filter>
                    </x-slot>

                    <x-slot name="headerRight">
                        <x-button.link class="btn-sm" type='outline-primary'>
                            Neu
                        </x-button.link>
                    </x-slot>

                    <x-table>
                        <x-slot:head>
                            <x-table.head.toggle />
                            <x-table.head.sort field="#" />
                            <x-table.head.sort field="First" />
                            <x-table.head.sort field="Last" />
                            <x-table.head.sort field="Handle" />
                            <th width="5%"></th>
                        </x-slot:head>
                        <x-table.row.line1>
                            <th>#1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </x-table.row.line1>
                        <x-table.row.line2>
                            <th>#2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </x-table.row.line2>

                        <x-table.row.line1>
                            <th>#3</th>
                            <td>John</td>
                            <td>Smith</td>
                            <td>@social</td>
                        </x-table.row.line1>
                        <x-table.row.line2>
                            <th>#4</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </x-table.row.line2>

                        <x-table.row.line1>
                            <th>#5</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </x-table.row.line1>
                        <x-table.row.line2>
                            <th>#6</th>
                            <td>John</td>
                            <td>Smith</td>
                            <td>@social</td>
                        </x-table.row.line2>

                    </x-table>
                    <x-table.check />
                    <hr><x-counter />

                </x-card>

                <x-card>

                    <x-slot name="headerLeft">
                        <x-button.group>
                            <x-button>Button</x-button>
                            <x-button.cancel>Cancel</x-button.cancel>
                            <x-button.dispatch>Dispatch</x-button.dispatch>
                            {{-- <x-button.file-link>File</x-button.file-link> --}}
                            <x-button.link>Link</x-button.link>
                            {{-- <x-button.model>Model</x-button.model> --}}
                            {{-- <x-button.wire>Wire</x-button.wire> --}}
                            {{-- <x-button.props>Props</x-button.props> --}}
                            {{-- <x-button.submit>Submit</x-button.submit> --}}
                        </x-button.group>
                    </x-slot>

                    @livewire('counter')


                </x-card>

                <div class="row">
                    <div class="col-6">
                        <h3>Test 1</h3>
                    </div>
                    <div class="col-6">
                        <h3>Test 2</h3>

                        @php $token = rand(); @endphp
                        <x-accordion :acc_id="$token" :flush="false">
                            <x-accordion.item :acc_id="$token" header="Header 1">
                                #1
                            </x-accordion.item>
                            <x-accordion.item :acc_id="$token" header="Header 2">
                                <x-slot name="header">
                                    <h1>Header 3</h1>
                                </x-slot>
                                #2
                            </x-accordion.item>
                        </x-accordion>

                    </div>
                </div>

            </div>

        </div>
    </div>

</x-guest-layout>
