<!-- Listing listing.selectCheckbox -->

@if ($toggleSelect == true)
    <input class="form-check-input" value="{{ $row->id }}" type="checkbox"
        @if ($selectAll !== false) checked @endif>
    <input class="form-check-input" value="{{ $row->id }}" type="radio">
@endif
