<dl class="row small mb-0">
    @forelse ($this->cols as $key => $col)
        @php
            // dd($key,$col);
            $colName = $col->Field; // only $col to get column name in sqlite
            $field = "form.{$colName}";
            $label = $colName;
            $value = $item->{$colName};
            $value = match (true) {
                $value === null => '',
                is_bool($value) => $value ? '1' : '0',
                is_scalar($value) => (string) $value,
                $value instanceof \DateTimeInterface => $value->format('Y-m-d H:i:s'),
                is_object($value) && method_exists($value, '__toString') => (string) $value,
                default => json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            };
            switch ($colName) {
                case 'id':
                    // case 'ID':
                    // case 'id':
                    $attri = 'disabled';
                    break;
                default:
                    $attri = '';
                    break;
            }
            // $colType = Schema::getColumnType($this->item->getTable(), $colName); // get column type in sqlite
            $colType = $col->Type;
        @endphp
        <dt class="col-3 text-truncate">{{ $label }}</dt>
        <dd class="col-9			  ">

            @switch($colType)
                @case('bigint unsigned')
                @case('bigint')
                @case('bigint(20) unsigned')

                @case('date')
                @case('double(10,2)')
                @case('int')
                @case('integer')
                @case('INTEGER')

                @case('time')

                @case('varchar')
                @case('varchar(8)')
                @case('varchar(25)')
                @case('varchar(191)')
                @case('varchar(255)')
                    <input wire:model="{{ $field }}" {{ $attri }} class="form-control form-control-sm"
                        placeholder="{{ $label }}" type="text">
                @break

                @case('text')
                    <textarea wire:model="{{ $field }}" class="form-control form-control-sm" style="height: 100px"></textarea>
                @break

                @default
                    <input type="text" class="form-control form-control-sm" disabled
                        value="{{ $value }} ({{ $colType }})">
            @endswitch
        </dd>
        @empty
            --- NO Data ---
        @endforelse
</dl>
