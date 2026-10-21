<!-- Listing listing.pagination -->

<div class="btn-group float-start" role="group">

    <label class="input-group-text" for="inputGroupSelect01">
        {{ $rows->firstItem() }} - {{ $rows->lastItem() }} of {{ $rows->total() }}
    </label>
    <select wire:model.live="pageLimit" class="form-select" id="inputGroupSelect01">
        <option>--</option>
        <option>05</option>
        <option>10</option>
        <option>25</option>
        <option>50</option>
    </select>

</div>

<div class="btn-group float-end" role="group">

    {{ $rows->links() }}

</div>
