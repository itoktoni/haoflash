<div class="export">

    <table id="header">
        <tr>
            <td>
                REKAP Payment In Out
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
                <th class="text-left" style="width:20%">Reference</th>
                <th class="text-left" style="width:15%">From</th>
                <th class="text-left" style="width:15%">To</th>
                <th class="text-left" style="width:15%">Person</th>
                <th class="text-left" style="width:20%">Voucher</th>
                <th class="text-left" style="width:15%">Description</th>
                <th class="text-left" style="width:10%">Type</th>
                <th class="text-right" style="width:10%">Submit</th>
                <th class="text-right" style="width:10%">Approve</th>
            </tr>
        </thead>
        <tbody>
            @foreach($preview as $data)
            <tr>
                <td data-title="No">{{ $loop->iteration }} </td>
                <td data-title="Reference">{{ $data->payment_reference ?? '' }} </td>
                <td data-title="From">{{ $data->payment_bank_from ?? '' }} </td>
                <td data-title="To">{{ $data->payment_bank_to ?? '' }} </td>
                <td data-title="Person">{{ $data->payment_person ?? '' }} </td>
                <td data-title="Voucher">{{ $data->payment_voucher ?? '' }} </td>
                <td data-title="Description">{{ PaymentModel::getDescription($data->payment_model) ?? '' }} </td>
                <td data-title="Type">{{ PaymentType::getDescription($data->payment_type) ?? '' }} </td>
                <td class="text-right" data-title="Submit">{{ Helper::createRupiah($data->payment_value_user) ?? '' }} </td>
                <td class="text-right" data-title="Total">{{ Helper::createRupiah($data->payment_value_approve) }} </td>
            </tr>
            @endforeach
            <tr>
                <td class="total" data-title="" colspan="8">Grand Total</td>
                <td class="total text-right" data-title="Grand Total">{{ Helper::createRupiah($preview->sum('payment_value_user')) }}</td>
                <td class="total text-right" data-title="Grand Total">{{ Helper::createRupiah($preview->sum('payment_value_approve')) }}</td>
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