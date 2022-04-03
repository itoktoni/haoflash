<table id="transaction" class="table table-no-more table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-left col-md-1">Action</th>
            <th class="text-left col-md-3">Product Name</th>
            <th class="text-left col-md-4">Product Description</th>
            <th class="text-right col-md-1">Qty</th>
        </tr>
    </thead>
    <tbody class="markup">
        @if(!empty($detail) || old('detail'))
        @foreach (old('detail') ?? $detail as $item)
        <tr>
            <td data-title="ID Product">
                @if(old('detail'))
                <button id="delete" value="{{ $item['temp_id'] }}" type="button" class="btn btn-danger btn-xs btn-block">Delete {{ $item['temp_id'] }}</button>
                @else
                <a id="delete" master="{{ $item->ro_detail_ro_code }}" value="{{ $item->ro_detail_product_id }}" href="{{ route(config('module').'_delete') }}" class="btn btn-danger btn-xs btn-block delete-{{ $item->ro_detail_product_id }}">
                    Delete
                </a>
                @endif
                <input type="hidden" value="{{ $item['temp_id'] ?? $item->ro_detail_product_id }}" name="temp_id[]">
                <input type="hidden" value="{{ $item['temp_id'] ?? $item->ro_detail_product_id }}" name="detail[{{ $loop->index }}][temp_id]">
            </td>
            <td data-title="Product">
                <input type="text" readonly class="form-control input-sm" value="{{ $item['temp_product'] ?? $item->has_product->product_name }}" name="detail[{{ $loop->index }}][temp_product]">
            </td>
            <td data-title="Description">
                <textarea rows="2" readonly class="form-control input-sm" name="detail[{{ $loop->index }}][temp_desc]">{{ $item['temp_desc'] ?? $item->has_product->product_description }}</textarea>
            </td>
            <td data-title="Qty" class="text-right col-lg-1">
                <input type="text" tabindex="{{ $loop->iteration }}1" name="detail[{{ $loop->index }}][temp_qty]" class="form-control input-sm text-right number temp_qty" value="{{ $item['temp_qty'] ?? $item->ro_detail_qty }}">

            </td>
        </tr>
        @endforeach
        @endisset
    </tbody>

</table>