@if(!empty($detail))
<table id="transaction" class="table table-no-more table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-left col-md-2">Product Name</th>
            <th class="text-left col-md-1">Qty</th>
            <th class="text-left col-md-1">Prepare</th>
            <th class="text-left col-md-1">Receive</th>
            <th class="text-left col-md-1">Remaining Receive</th>
        </tr>
    </thead>
    <tbody class="markup">
        @foreach ($detail as $item)
        @php

        $split = Adapter::splitKey($item->mask_key);
        $split_product = $split[0];
        $split_supplier = $split[1];
        $split_buy = $split[2];
        $split_expired = $split[3];
        $prepare = Adapter::getTotalStockDoProduct($model->{$model->getKeyName()}, $split_product, $split_supplier, $split_buy ,$item->mask_expired);
        $receive = Adapter::getTotalStockReceiveProduct($model->{$model->getKeyName()}, $split_product , $split_supplier, $model->do_branch_id, $split_buy, $item->mask_expired) ?? 0;
        $remaining = $item->mask_qty - $receive;
        @endphp
        <tr>
            <input type="hidden" value="{{ $model->{$model->getKeyName()} }}" name="detail[{{ $loop->index }}][do_detail_do_code]">
            <input type="hidden" value="{{ $item->mask_key }}" name="detail[{{ $loop->index }}][do_detail_key]">
            <input type="hidden" value="{{ $item->mask_qty }}" name="detail[{{ $loop->index }}][do_detail_qty]">
            <input type="hidden" value="{{ $prepare }}" name="detail[{{ $loop->index }}][do_detail_prepare]">
            <input type="hidden" value="{{ $receive }}" name="detail[{{ $loop->index }}][do_detail_receive]">

            <td data-title="Product Name">
                {{ $item->mask_product_name }}
            </td>
            <td data-title="Qty">
                {{ $item->mask_qty }}
            </td>
            <td data-title="Prepare" class="col-lg-1">
                {{ $prepare }}
            </td>
            <td data-title="Receive" class="col-lg-1">
                {{ $receive }}
            </td>
            <td data-title="Remaining" class="col-lg-1">
                {{ $model->mask_status == DeliveryStatus::Receive ? $remaining : $item->mask_qty }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif