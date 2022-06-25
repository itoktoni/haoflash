<div class="export">

    <table id="header">
        <tr>
            <td>
                REKAP TOTAL ORDER
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
                <th class="text-left" style="width:2%">No.</th>
                <th class="text-left" style="width:15%">No. Order</th>
                <th class="text-left" style="width:20%">Nama Supplier</th>
                <th class="text-left" style="width:12%">Date</th>
                <th class="text-left" style="width:12%">DPP</th>
                <th class="text-left" style="width:12%">PPN</th>
                <th class="text-left" style="width:12%">PPH</th>
                <th class="text-left" style="width:12%">Payment</th>
                <th class="text-left" style="width:12%">Status</th>
                <th class="text-right" style="width:10%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($preview as $data)
            <tr>
                <td data-title="No">{{ $loop->iteration }} </td>
                <td data-title="No. Order">{{ $data->po_code ?? '' }} </td>
                <td data-title="Nama Supplier">{{ $data->supplier_name ?? '' }} </td>
                <td data-title="Date">{{ $data->po_date_order ?? '' }} </td>
                <td data-title="DPP">{{ Helper::createRupiah($data->po_sum_dpp) ?? '' }} </td>
                <td data-title="PPN">{{ Helper::createRupiah($data->po_sum_ppn) ?? '' }} </td>
                <td data-title="PPH">{{ Helper::createRupiah($data->po_sum_pph) ?? '' }} </td>
                <td data-title="Payment">{{ PurchasePayment::getDescription($data->po_payment) ?? '' }} </td>
                <td data-title="Status">{{ PurchaseStatus::getDescription($data->po_status) ?? '' }} </td>
                <td class="text-right" data-title="Total">{{ Helper::createRupiah($data->po_sum_total) }} </td>
            </tr>
            @endforeach
            <tr>
                <td class="total" data-title="" colspan="9">Grand Total</td>
                <td class="total text-right" data-title="Grand Total">{{ Helper::createRupiah($preview->sum('po_sum_total')) }}</td>
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

    table tbody tr td {
        padding: 10px 5px !important;
        border: 1px solid lightgray;
    }

    table thead tr th {
        border: 1px solid gray;
        padding: 10px 5px !important;
        font-weight: bold;
    }

    .total {
        font-weight: bold;
        color: #fff;
        background-color: grey;
    }
</style>