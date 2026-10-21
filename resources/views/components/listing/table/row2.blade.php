<!-- Listing listing.table.row2 -->

<tr wire:ignore.self id="collapse-{{ $token . '-' . $row->id }}" class="accordion-collapse collapse"
    data-bs-parent="#accordion-{{ $token }}">

    <td align="center"></td>
    <td colspan=2>

        <small>

            @if (isset($row->text))
                {!! nl2br(e(Str::limit($row->text, 60))) !!}
            @elseif (isset($row->memo))
                {{ Str::limit($row->memo, 60) }}
            @else
                ...
            @endif

        </small>

        <hr>

        <div class="btn-group btn-group-sm float-end" role="group">
            <button.select keyid='{{ $row->id }}' />
            <button.update keyid='{{ $row->id }}' />
            <button.raw keyid='{{ $row->id }}' table='{{ $table }}' />
            <button.delete keyid='{{ $row->id }}' />
        </div>

    </td>

</tr>
