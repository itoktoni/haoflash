<div class="export">

    <table id="header">
        <tr>
            <td>
                REKAP DETAIL ORDER
            </td>
        </tr>

        <tr>
            <td>
                Tanggal Cetak : {{ date('d / m / Y') }}
            </td>
        </tr>
    </table>

    <table id="datatable" class="responsive table table-no-more table-bordered table-striped mb-none">
        <thead>
            <tr>
                <th class="text-left" style="width:3%">No.</th>
                <th class="text-left" style="width:12%">No. Order</th>
                <th class="text-left" style="width:20%">Nama Supplier</th>
                <th class="text-left" style="width:20%">Nama Product</th>
                <th class="text-right" style="width:5%">Qty</th>
                <th class="text-right" style="width:7%">Harga</th>
                <th class="text-right" style="width:10%">Total</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($preview))
@php
$total = 0;
$number = 1;
@endphp
            @foreach($preview as $data)
            @if($detail = $data->has_detail)
@php
if($product = request()->get('po_product_id')){
    $detail = $detail->where('po_detail_product_id', $product);
}
@endphp
            @foreach($detail as $item)
@php
$total = $total + $item->po_detail_total;
@endphp
            <tr>
                <td data-title="No">{{ $number++ }} </td>
                <td data-title="No. Order">{{ $data->po_code ?? '' }} </td>
                <td data-title="Nama Supplier">{{ $data->supplier_name ?? '' }} </td>
                <td data-title="Nama Product">{{ $item->has_product->product_name ?? '' }} </td>
                <td data-title="Qty" class="text-right">{{ $item->po_detail_qty ?? '' }} </td>
                <td data-title="Harga" class="text-right">{{ number_format($item->po_detail_price) ?? '' }} </td>
                <td data-title="Total" class="text-right">{{ number_format($item->po_detail_total) }} </td>
            </tr>
            @endforeach
            @endif

            @endforeach
            @endif

            <tr>
                <td class="total" data-title="" colspan="6">Grand Total</td>
                <td class="total text-right" data-title="Grand Total">{{ number_format($total) }}</td>
            </tr>
        </tbody>
    </table>
</div>


<style>
    .export {
        width: 100%;
    }

    #header {
        margin-bottom: 20px;
        font-weight: bold;
        width: 30%;
    }

    .text-right {
        text-align: right;
    }

    .text-left {
        text-align: left;
    }

    #datatable {
        width: 100%;
        position: relative;
    }

    table tbody td {
        padding: 10px 5px;
        border: 1px solid lightgray;
    }

    table thead tr th {
        border: 1px solid gray;
        padding: 10px 5px;
        font-weight: bold;
    }

    .total {
        font-weight: bold;
        color: #fff;
        background-color: grey;
    }
</style>