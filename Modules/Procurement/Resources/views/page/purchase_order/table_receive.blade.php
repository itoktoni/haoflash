@if(!empty($detail))
<table id="transaction" class="table table-no-more table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-left col-md-2">Product Name</th>
            <th class="text-left col-md-1">Qty</th>
            <th class="text-left col-md-1">Receive</th>
            <th class="text-left col-md-1">Remaining</th>
            <th class="text-center col-md-1">Action</th>
        </tr>
    </thead>
    <tbody class="markup">
        @foreach ($detail as $item)
        <tr>
            <td data-title="Product Name">
                {{ $item->mask_product_name }}
            </td>
            <td data-title="Qty">
                {{ $item->mask_qty }}
            </td>
            <td data-title="Receive" class="col-lg-1">
                {{ 0 }}
            </td>
            <td data-title="Bank To" class="col-lg-1">
                {{ $item->mask_qty }}
            </td>
            <td data-title="Account" class="col-lg-1 text-center">
                <a href="{!! route($module.'_form_receive_detail', ['code' => $model->{$model->getKeyName()}, 'detail' => $item->mask_product_id]) !!}" class="btn btn-success btn-xs">{{ __('Receive') }}</a>
                <!-- <a href="{!! route($route_index) !!}" class="btn btn-danger btn-xs">{{ __('Invoice') }}</a> -->
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif