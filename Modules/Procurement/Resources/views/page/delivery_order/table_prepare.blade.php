@if(!empty($detail))
<table id="transaction" class="table table-no-more table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-left col-md-2">Product Name</th>
            <th class="text-left col-md-1">Qty</th>
            <th class="text-left col-md-1">Buy Price</th>
            <th class="text-left col-md-1">Expired</th>
            <th class="text-left col-md-1">Prepare</th>
            <th class="text-left col-md-1">Remaining</th>
            <th class="text-center col-md-1">Action</th>
        </tr>
    </thead>
    <tbody class="markup">
        @foreach ($detail as $item)
        @php
        $receive = Adapter::getTotalStockDoProduct($model->{$model->getKeyName()}, $item->mask_product_id);
        $remaining = $item->mask_qty - $receive;
        @endphp
        <tr>
            <td data-title="Product Name">
                {{ $item->mask_product_name }}
            </td>
            <td data-title="Qty">
                {{ $item->mask_qty }}
            </td>
            <td data-title="Price">
                {{ Helper::createRupiah($item->mask_price) }}
            </td>
            <td data-title="Expired">
                {{ $item->mask_expired }}
            </td>
            <td data-title="Receive" class="col-lg-1">
                {{ $receive }}
            </td>
            <td data-title="Remaining" class="col-lg-1">
                {{ $remaining }}
            </td>
            <td data-title="Account" class="col-lg-1 text-center">
                @if(($model->mask_status == DeliveryStatus::Create || $model->mask_status == DeliveryStatus::Prepare) && $remaining > 0)
                <a href="{!! route($module.'_form_prepare_detail', ['code' => $model->{$model->getKeyName()}, 'detail' => $item->mask_product_id, 'key' => $item->mask_key]) !!}" class="btn btn-success btn-xs">{{ __('Prepare') }}</a>
                @endif
                <!-- <a href="{!! route($route_index) !!}" class="btn btn-danger btn-xs">{{ __('Invoice') }}</a> -->
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif