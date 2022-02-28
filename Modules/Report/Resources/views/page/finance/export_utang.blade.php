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
                <th class="text-left" style="width:12%">No. Order</th>
                <th class="text-left" style="width:20%">Nama Supplier</th>
                <th class="text-left" style="width:20%">Nama Customer</th>
                <th class="text-right" style="width:10%">Total</th>
                <th class="text-right" style="width:10%">Pembayaran</th>
                <th class="text-right" style="width:10%">Outstanding</th>
            </tr>
        </thead>
        <tbody>
            @php
            $sum_pembayaran = $sum_outstanding = 0;
            @endphp
            @foreach($preview as $data)

            @php
            $total_pembayaran = $total_outstanding = 0;
            $pembayaran = $data->has_payment ?? false;

            if($pembayaran){
                $total_pembayaran = $pembayaran->sum('payment_value_approve');
                $sum_pembayaran = $sum_pembayaran + $total_pembayaran;
            }

            $total_outstanding = $data->wo_sum_total - $total_outstanding;
            $sum_outstanding = $sum_outstanding + $total_outstanding;
            @endphp

            <tr>
                <td data-title="No">{{ $loop->iteration }} </td>
                <td data-title="No. Order">{{ $data->wo_code ?? '' }} </td>
                <td data-title="Nama Supplier">{{ $data->has_supplier->mask_name ?? '' }} </td>
                <td data-title="Nama Customer">{{ $data->has_customer->name ?? '' }} </td>
                <td class="text-right" data-title="Total">{{ Helper::createRupiah($data->wo_sum_total) }} </td>
                <td class="text-right" data-title="Pembayaran">{{ Helper::createRupiah($total_pembayaran) }} </td>
                <td class="text-right" data-title="Outstanding">{{ Helper::createRupiah($total_outstanding) }} </td>
            </tr>
            @endforeach
            <tr>
                <td class="total" data-title="" colspan="4">Grand Total</td>
                <td class="total text-right" data-title="Grand Total">{{ Helper::createRupiah($preview->sum('wo_sum_total')) }}</td>
                <td class="total text-right" data-title="Grand Pembayaran">{{ Helper::createRupiah($sum_pembayaran) }}</td>
                <td class="total text-right" data-title="Grand Outstanding">{{ Helper::createRupiah($sum_outstanding) }}</td>
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