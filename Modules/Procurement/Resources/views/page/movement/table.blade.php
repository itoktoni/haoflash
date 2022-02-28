<table id="transaction" class="table table-no-more table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-left col-md-1">ID</th>
            <th class="text-left col-md-4">Product Name</th>
            <th class="text-right col-md-1">Qty</th>
        </tr>
    </thead>
    <tbody class="markup">
        @if(!empty($detail) || old('detail'))
        @foreach (old('detail') ?? $detail as $item)
        <tr>
            <td data-title="ID Product">
                @if(old('detail'))
                <button id="delete" value="{{ $item['temp_id'] }}" type="button" class="btn btn-danger btn-xs btn-block">{{ $item['temp_id'] }}</button>
                @else
                <a id="delete" value="{{ $item->procurement_movement_detail_item_product_id }}"
                    href="{{ route(config('module').'_delete', ['code' => $item->procurement_movement_detail_id, 'detail' => $item->procurement_movement_detail_item_product_id ]) }}"
                    class="btn btn-danger btn-xs btn-block delete-{{ $item->procurement_movement_detail_item_product_id }}">
                    {{ $item->procurement_movement_detail_item_product_id }}
                </a>
                @endif
                <input type="hidden" value="{{ $item['temp_id'] ?? $item->procurement_movement_detail_item_product_id }}" name="temp_id[]">
                <input type="hidden" value="{{ $item['temp_id'] ?? $item->procurement_movement_detail_item_product_id }}"
                    name="detail[{{ $loop->index }}][temp_id]">
            </td>
            <td data-title="Product">
                <input type="text" readonly class="form-control input-sm"
                    value="{{ $item['temp_product'] ?? $item->product->item_product_name }}" name="detail[{{ $loop->index }}][temp_product]">
            </td>
            <td data-title="Qty" class="text-right col-lg-1">
                <input type="text" tabindex="{{ $loop->iteration }}1" name="detail[{{ $loop->index }}][temp_qty]"
                    class="form-control input-sm text-right number temp_qty"
                    value="{{ $item['temp_qty'] ?? $item->procurement_movement_detail_qty }}">

            </td>
        </tr>
        @endforeach
        @endisset
    </tbody>

</table>