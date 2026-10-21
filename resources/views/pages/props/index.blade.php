<x-app-layout>

    <x-slot name="header">
        <div class="container">
            <div class="btn-group">
                <x-button type="light">
                    {{ Str::limit('Props', 150) }}
                </x-button>
            </div>
        </div>
        <hr>
    </x-slot>

    <x-card>

        <x-slot name="headerLeft">
            <x-table.filter>
                <x-table.filter.cnt_per_page route="core.props.index" :request="$request" />
                <x-table.filter.search field="search" table="{{ $props[0] ? $props[0]->getTable() : '' }}"
                    :oldval="$request->search" />
                <x-table.filter.select field="state" :oldval="$request->state"
                    :options="['active', 'error', 'verified', 'unverified', 'pending']" />
                <x-table.filter.select field="type" :oldval="$request->type"
                    :options="['birth_date', 'death_date', 'iban', 'metadata']" />
            </x-table.filter>
        </x-slot>

        <x-form :action="route('core.props.store')">

            @php
                $colspan = 7;
                $token = rand();
                $toggleCollapse = true;
                $toggleChecks = true;
                $toggleRadios = true;
            @endphp

            <x-table :acc_id="$token" class="table-sm table-hover table-striped" :$colspan :$toggleCollapse :$toggleChecks
                :$toggleRadios>

                @forelse ($props as $prop)
                    @if ($loop->first)
                        <x-slot:header>
                            <th></th>
                            <th></th>
                            <x-table.head.sort field="state" />
                            <x-table.head.sort field="type" />
                            <th>Key</th>
                            <th>Label</th>
                            <th>Value</th>
                        </x-slot:head>
                    @endif

                    <x-table.row :acc_id="$token" :row_id="$prop->id" :$toggleCollapse :$toggleChecks :$toggleRadios>

                        <td>
                            <x-button.group>
                                <x-button.link :href="route('core.props.show', $prop)" type="outline-secondary" size="sm">
                                    <i class="bi bi-info-square"></i>
                                </x-button.link>
                            </x-button.group>
                        </td>
                        <td nowrap>
                            <x-button.model :model="$prop->model_type" :modid="$prop->model_id" />
                        </td>
                        <td align="center" nowrap>
                            <x-button.group>
                                <a class="btn btn-primary" role="button"
                                    href="{{ request()->fullUrlWithQuery(array_merge(request()->all(), ['page' => '1', 'state' => $prop->state])) }}">
                                    {{ $prop->state }}
                                </a>
                            </x-button.group>
                        </td>
                        <td align="center" nowrap>
                            <x-button.group>
                                <a class="btn btn-primary" role="button"
                                    href="{{ request()->fullUrlWithQuery(array_merge(request()->all(), ['page' => '1', 'type' => $prop->type])) }}">
                                    {{ $prop->type }}
                                </a>
                            </x-button.group>
                        </td>
                        <td>{{ $prop->key }}</td>
                        <td>{{ $prop->label }}</td>
                        <td align="right">{{ $prop->value }}</td>

                        <x-slot:row2>
                            <td colspan="{{ $colspan - 1 }}">
                                <x-desc.list :item="$prop->toArray()" />
                                <hr>
                                <x-button.group>
                                    <x-button.link :href="route('core.props.edit', $prop)" type="outline-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </x-button.link>
                                    <x-button.link :href="route('core.props.show', $prop)" type="outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </x-button.link>
                                </x-button.group>
                                <hr>
                            </td>
                        </x-slot:row2>

                    </x-table.row>

                @empty
                    <tr>
                        <td colspan="{{ $colspan }}">No Data found</td>
                    </tr>
                @endforelse

                <x-slot:footer>
                    <hr>
                    <div class="btn-group">
                        <div class="input-group">
                            <button class="btn btn-light" onclick="return confirm('Auswahl ändern?')" type="submit"
                                value="change" name="switchTo">
                                Ändern
                            </button>
                        </div>
                    </div>

                    <button class="btn btn-danger" onclick="return confirm('Löschen ausführen?')" type="submit"
                        name="switchTo" value="delete">
                        Löschen
                    </button>

                    <x-form.submit />
                </x-slot:footer>

            </x-table>

        </x-form>

        <x-slot name="footer">
            {{ $props->links() }}
        </x-slot>

    </x-card>

    <x-counter startTime="{{ isset($startTime) ? $startTime : 0 }}" />

</x-app-layout>
