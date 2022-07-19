<h4>Delivery Order : </h4>
<table id="transaction" class="table table-no-more table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-left col-md-1">Action</th>
            <th class="text-left col-md-3">Product Name</th>
            <th class="text-left col-md-4">Product Description</th>
            <th class="text-right col-md-1">Qty</th>
            <th class="text-right col-md-2">Price</th>
            <th class="text-right col-md-2">Total</th>
        </tr>
    </thead>
    <tbody class="markup">
        @if(!empty($detail) || old('detail'))
        @foreach (old('detail') ?? $detail as $item)
        <tr>
            <td data-title="ID Product">
                @if(old('detail'))
                <button id="delete" value="{{ $item['temp_id'] }}" type="button" class="btn btn-danger btn-xs btn-block">Delete</button>
                @else
                <a id="delete" master="{{ $item->do_detail_do_code }}" value="{{ $item->do_detail_key }}" href="{{ route(config('module').'_delete') }}" class="btn btn-danger btn-xs btn-block delete-{{ $item->do_detail_key }}">
                    Delete
                </a>
                @endif
                <input type="hidden" value="{{ $item['temp_id'] ?? $item->do_detail_key }}" name="temp_id[]">
                <input type="hidden" value="{{ $item['temp_id'] ?? $item->do_detail_key }}" name="detail[{{ $loop->index }}][temp_id]">
            </td>
            <td data-title="Product">
                <textarea rows="2" readonly class="form-control input-sm" name="detail[{{ $loop->index }}][temp_product]">{{ $item['temp_product'] ?? $item->has_product->product_name ?? '' }}</textarea>
            </td>
            <td data-title="Description">
                <textarea rows="2" readonly class="form-control input-sm" name="detail[{{ $loop->index }}][temp_desc]">{{ $item['temp_desc'] ?? $item->has_product->product_description ?? '' }} {{ $item['temp_id'] ?? $item->do_detail_key ?? '' }}</textarea>
            </td>
            <td data-title="Qty" class="text-right col-lg-1">
                <input type="text" tabindex="{{ $loop->iteration }}1" name="detail[{{ $loop->index }}][temp_qty]" class="form-control input-sm text-right number temp_qty" value="{{ $item['temp_qty'] ?? $item->do_detail_qty }}">

            </td>
            <td data-title="Price" class="text-right col-lg-1">
                <input type="text" tabindex="{{ $loop->iteration }}2" name="detail[{{ $loop->index }}][temp_price]" class="form-control input-sm text-right money temp_price" value="{{ $item['temp_price'] ?? $item->do_detail_price }}">

            </td>
            <td data-title="Total" class="text-right col-lg-1">
                <input type="text" readonly name="detail[{{ $loop->index }}][temp_total]" class="form-control input-sm text-right number temp_total" value="{{ $item['temp_total'] ?? $item->do_detail_total }}">
            </td>
        </tr>
        @endforeach
        @endisset
    </tbody>

</table>