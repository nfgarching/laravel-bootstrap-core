<x-field.group>

    <div class="col-sm-6">
        <x-field field="model_type" :value="old('model_type', $prop->model_type ?? '')" />
    </div>
    <div class="col-sm-6">
        <x-field field="model_id" type="number" :value="old('model_id', $prop->model_id ?? '')" />
    </div>

    <div class="col-sm-6">
        <x-field.select field="state" :value="old('state', $prop->state ?? '')">
            <option value=""></option>
            <option value="active">active</option>
            <option value="error">error</option>
            <option value="verified">verified</option>
            <option value="unverified">unverified</option>
            <option value="pending">pending</option>
        </x-field.select>
    </div>
    <div class="col-sm-6">
        <x-field.select field="type" :value="old('type', $prop->type ?? '')">
            <option value=""></option>
            <option value="birth_date">birth_date</option>
            <option value="death_date">death_date</option>
            <option value="iban">iban</option>
            <option value="metadata">metadata</option>
        </x-field.select>
    </div>

    <div class="col-sm-6">
        <x-field field="key" :value="old('key', $prop->key ?? '')" />
    </div>
    <div class="col-sm-6">
        <x-field field="label" :value="old('label', $prop->label ?? '')" />
    </div>

    <div class="col-sm-12">
        <x-field field="value" :value="old('value', $prop->value ?? '')" />
    </div>

    <div class="col-sm-12">
        <x-field.text field="memo" :value="old('memo', $prop->memo ?? '')" />
    </div>

</x-field.group>
