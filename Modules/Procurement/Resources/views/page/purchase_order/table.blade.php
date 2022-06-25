<table id="transaction" class="table table-no-more table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-left col-md-1">Action</th>
            <th class="text-left col-md-3">Product Name</th>
            <th class="text-left col-md-4">Product Description</th>
            <th class="text-right col-md-2">Qty</th>
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
                <button id="delete" value="{{ $item['temp_id'] }}" type="button" class="btn btn-danger btn-xs btn-block">Delete {{ $item['temp_id'] }}</button>
                @else
                <a id="delete" master="{{ $item->po_detail_po_code }}" value="{{ $item->po_detail_product_id }}" href="{{ route(config('module').'_delete') }}" class="btn btn-danger btn-xs btn-block delete-{{ $item->po_detail_product_id }}">
                    Delete
                </a>
                @endif
                <input type="hidden" value="{{ $item['temp_id'] ?? $item->po_detail_product_id }}" name="temp_id[]">
                <input type="hidden" value="{{ $item['temp_id'] ?? $item->po_detail_product_id }}" name="detail[{{ $loop->index }}][temp_id]">
            </td>
            <td data-title="Product">
                <input type="text" readonly class="form-control input-sm" value="{{ $item['temp_product'] ?? $item->has_product->product_name }}" name="detail[{{ $loop->index }}][temp_product]">
            </td>
            <td data-title="Description">
                <textarea rows="2" readonly class="form-control input-sm" name="detail[{{ $loop->index }}][temp_desc]">{{ $item['temp_desc'] ?? $item->has_product->product_description }}</textarea>
            </td>
            <td data-title="Qty" class="text-right col-lg-1">
                <input type="text" tabindex="{{ $loop->iteration }}1" name="detail[{{ $loop->index }}][temp_qty]" class="form-control input-sm text-right number temp_qty" value="{{ $item['temp_qty'] ?? $item->po_detail_qty }}">

            </td>
            <td data-title="Price" class="text-right col-lg-1">
                <input type="text" tabindex="{{ $loop->iteration }}2" name="detail[{{ $loop->index }}][temp_price]" class="form-control input-sm text-right money temp_price" value="{{ $item['temp_price'] ?? $item->po_detail_price }}">

            </td>
            <td data-title="Total" class="text-right col-lg-1">
                <input type="text" readonly name="detail[{{ $loop->index }}][temp_total]" class="form-control input-sm text-right number temp_total" value="{{ $item['temp_total'] ?? $item->po_detail_total }}">
            </td>
        </tr>
        @endforeach
        @endisset
    </tbody>

    @if($model->mask_tax)
    <tbody>
        <tr>
            <td data-title="Total DPP" colspan="5" class="text-right">
                <strong>Total PO</strong>
            </td>
            <td data-title="" class="text-right">
                {!! Form::text('', $model->mask_value ?? 0 , ['readonly', 'class' => 'number form-control text-right']) !!}
            </td>
        </tr>
        <tr>
            <td data-title="Total DPP" colspan="5" class="text-right">
                <strong>Total DPP</strong>
            </td>
            <td data-title="" class="text-right">
                {!! Form::text('', $model->mask_dpp ?? 0 , ['readonly', 'class' => 'number form-control text-right']) !!}
            </td>
        </tr>
        <tr>
            <td data-title="Total PPN" colspan="5" class="text-right">
                <strong>Total PPN</strong>
            </td>
            <td data-title="" class="text-right">
                {!! Form::text('', $model->mask_ppn ?? 0 , ['readonly', 'class' => 'number form-control text-right']) !!}
            </td>
        </tr>
        <tr>
            <td data-title="Total PPH" colspan="5" class="text-right">
                <strong>Total PPH</strong>
            </td>
            <td data-title="" class="text-right">
                {!! Form::text('', $model->mask_pph ?? 0 , ['readonly', 'class' => 'number form-control text-right']) !!}
            </td>
        </tr>
    </tbody>
    @endif

    <tbody class="discount_container">
        <tr>
            <td data-title="Sebelum Discount" colspan="5" class="text-right">
                <strong>Total Sebelum Discount</strong>
            </td>
            <td data-title="" class="text-right">
                {!! Form::text('po_sum_product', $model->mask_value, ['id' => 'before_discount',
                'readonly', 'class' => 'number form-control text-right']) !!}
            </td>
        </tr>
        <tr style="margin-bottom: 20px;">
            <td data-title="" class="text-left col-md-1 hide-xs">
                <button value="Discount" type="button" class="btn btn-xs btn-success btn-block">Discount</button>
            </td>
            <td data-title="Description" class="text-left col-md-4" colspan="2">
                {!! Form::textarea('po_discount_name', null, ['id' => 'grand_discount_description', 'class' =>
                'form-control', 'rows' => 2, 'tabindex' => 500]) !!}
            </td>
            <td data-title="Value" class="text-right col-md-1">
                {!! Form::text('po_discount_value', null, ['id' => 'grand_discount_value', 'placeholder' =>
                'Dalam Rupiah' ,'class' => 'number form-control text-right', 'tabindex' => 501]) !!}
            </td>
            <td data-title="Price" class="text-right col-md-1">
                {!! Form::text('po_discount_value', null, ['id' => 'grand_discount_price',
                'readonly', 'class' => 'number form-control text-right', 'tabindex' => 502]) !!}
            </td>
            <td data-title="Total" class="text-right col-md-1">
                {!! Form::text('po_sum_discount', null, ['id' => 'grand_discount_total',
                'readonly', 'class' => 'number form-control text-right']) !!}
            </td>
        </tr>

    </tbody>
</table>