<h4>Request Order :</h4>
<table id="transaction" class="table table-no-more table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-left col-md-1">No.</th>
            <th class="text-left col-md-3">Product Name</th>
            <th class="text-left col-md-4">Product Description</th>
            <th class="text-right col-md-1">Qty</th>
            <th class="text-right col-md-2">Price</th>
            <th class="text-right col-md-2">Total</th>
        </tr>
    </thead>
    <tbody class="">
        @if(isset($data_ro))
        @foreach ($data_ro as $item)
        <tr>
            <td data-title="ID Product">
                {{ $loop->iteration }}
            </td>
            <td data-title="Product">
               {{ $item->has_product->product_name ?? '' }}
            </td>
            <td data-title="Description">
                {{ $item->ro_detail_notes ?? '' }}
            </td>
            <td data-title="Qty" class="text-right col-lg-1">
                {{ $item->ro_detail_qty ?? '' }}
            </td>
            <td data-title="Price" class="text-right col-lg-1">
                {{ Helper::createRupiah($item->ro_detail_product_price) ?? '' }}
            </td>
            <td data-title="Total" class="text-right col-lg-1">
                {{ Helper::createRupiah($item->ro_detail_qty * $item->ro_detail_product_price) ?? '' }}
            </td>
        </tr>
        @endforeach
        @endisset
    </tbody>

</table>