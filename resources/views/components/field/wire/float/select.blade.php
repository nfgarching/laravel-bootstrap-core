{{-- x-tags: wire.select --}}
@props([
    'field',
    'item' => null,
    'attri' => '',
    'class' => '',
    'dirty' => 'bg-info',
    'fname' => '',
    'keyid' => '',
    'label' => '',
    'modifier' => '',
    'token' => rand(),
    'value' => '',
    // select special
    'options' => [],
])

@php
    if ($fname == '') {
        $fname = $field;
    }
    if ($label === '' || $label === null) {
        $label = ucfirst($field);
    }
    // dd($field, $fname, $label); 

    // if (isset($this->form)) {
    // }

    if (isset($this->form) && is_object($this->form) && property_exists($this->form, $fname)) {
        $field = 'form.' . $field;
    } elseif (isset($this->form) && is_array($this->form) && isset($this->form[$fname])) {
        $field = 'form.' . $field;
    } elseif (isset($this->item) && !is_array($this->item) && isset($this->item->$fname)) {
        $field = 'item.' . $field;
    } elseif (isset($this->item) && is_array($this->item) && isset($this->item[$fname])) {
        $field = 'item.' . $field;
    }
    // if ($slot->isNotEmpty()) {
    //     $value = $slot;
    // }
    // dd($this->form->Konto);
    // dd($this->form->{$fname} ?? null, $this->form[$fname] ?? null, $this->item->{$fname} ?? null, $this->item[$fname] ?? null);
    // Resolve initial value for SSR selected state
    if ($value === '') {
        $resolved = null;
        if (isset($this->form) && is_object($this->form) && property_exists($this->form, $fname)) {
            $resolved = $this->form->{$fname};
            // dd($resolved, $this->form, $fname, '1');
        } elseif (isset($this->form) && is_array($this->form) && isset($this->form[$fname])) {
            // dd($this->form, $fname . '2');
            $resolved = $this->form[$fname];
        } elseif (isset($this->item) && !is_array($this->item) && isset($this->item->{$fname})) {
            $resolved = $this->item->{$fname};
        } elseif (isset($this->item) && is_array($this->item) && isset($this->item[$fname])) {
            $resolved = $this->item[$fname];
        }

        if ($resolved instanceof \BackedEnum) {
            $value = $resolved->value;
        } elseif ($resolved !== null && !is_array($resolved) && !is_object($resolved)) {
            $value = (string) $resolved;
        }
    } else {
        $resolved = $value;
    }
    $class = 'form-select ' . $class;
    // dump(App\Enums\BuchungTyp::options());
    // $opt = App\Models\Konten::finanzkonto()->orderBy('Konto')->get()->mapWithKeys(
    //     fn($k) => [
    //         $k->Konto => $k->Konto . ' ' . $k->Suchbegriff,
    //     ],
    // );
    // dump($opt);
    // foreach ($opt as $key => $value) {
    //     # code...
    //     dump($key, $value);
    // }
    // dd($field, $fname, $value, $options, $resolved, $this->form ?? null, $this->item ?? null);
@endphp

<div class="@if ($label !== false) form-floating @endif @error($field) is-invalid @enderror">

    <select autocomplete="off" {{ $attri }}
        @switch($modifier)
            @case('blur')
                wire:model.live.blur="{{ $field }}"
                @break
            @case('change')
                wire:model.live.change="{{ $field }}"
                @break
            @case('lazy')
                wire:model.lazy="{{ $field }}"                
                @break
            @case('live')
                wire:model.live="{{ $field }}"                
                @break
            @case('store')
                onkeydown="this.classList.add('bg-info');"
                wire:change="store({{ $keyid }},'{{ $field }}',$event.target.value)" 
                wire:loading.class="bg-info"
                wire:loading.attr="disabled"
                @break
            @case('disabled')
            @case('readonly')
                wire:model="{{ $field }}"
                disabled
                @break
            @default
                wire:model="{{ $field }}"                
        @endswitch
        @if ($dirty) wire:dirty.class="{{ $dirty }}" @endif
        class="{{ $class }} @error($field) is-invalid @enderror" id="field-{{ $token }}"
        name="{{ $fname }}">

        <option value="">---</option>

        @forelse($options as $key => $txt)
            {{-- <option value="{{ $key }}" @if ($isSelected($key)) selected="selected" @endif> --}}
            {{-- <option value="{{ $key }}"> --}}
            <option {{ $key == $value ? 'selected' : '' }} value="{{ $key }}">
                {{ $txt }}
            </option>
        @empty
            {{ $slot }}
        @endforelse

    </select>

    {{-- <label for="field-{{ $token }}">{{ $label }} - {{ $field }} - {{ $value }}</label> --}}
    @if ($label !== false)
        <label for="field-{{ $token }}">{{ $label }}</label>
    @endif

    <div class="invalid-feedback">
        @error($field)
            {{ $message }}
        @enderror
    </div>

</div>
