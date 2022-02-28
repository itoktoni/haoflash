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
                <th class="text-left" style="width:20%">Nama Company</th>
                <th class="text-left" style="width:20%">Nama Customer</th>
                <th class="text-left" style="width:20%">Nama Product</th>
                <th class="text-right" style="width:5%">Qty</th>
                <th class="text-right" style="width:7%">Harga</th>
                <th class="text-right" style="width:10%">Total</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($test))
            @foreach($preview as $data)
            @if($detail = $data->has_detail)
            @foreach($detail as $item)
            <tr>
                <td data-title="No">{{ $loop->iteration }} </td>
                <td data-title="No. Order">{{ $data->so_code ?? '' }} </td>
                <td data-title="Nama Company">{{ $data->so_company_name ?? '' }} </td>
                <td data-title="Nama Customer">{{ $data->has_customer->name ?? '' }} </td>
                <td data-title="Nama Product">{{ $item->has_product->mask_name ?? '' }} </td>
                <td data-title="Qty" class="text-right">{{ $item->mask_qty ?? '' }} </td>
                <td data-title="Harga" class="text-right">{{ Helper::createRupiah($item->mask_price) ?? '' }} </td>
                <td data-title="Total" class="text-right">{{ Helper::createRupiah($data->so_sum_total) }} </td>
            </tr>
            @endforeach
            @endif
            @endforeach
            @endif
            <tr>
                <td class="total" data-title="" colspan="7">Grand Total</td>
                <td class="total text-right" data-title="Grand Total">{{ Helper::createRupiah($preview->sum('so_sum_total')) }}</td>
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