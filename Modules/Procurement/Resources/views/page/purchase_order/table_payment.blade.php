@if(!empty($detail))
<table id="transaction" class="table table-no-more table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-left col-md-1">File</th>
            <th class="text-left col-md-2">Voucher</th>
            <th class="text-left col-md-1">Date</th>
            <th class="text-left col-md-1">Bank From</th>
            <th class="text-left col-md-1">Bank To</th>
            <th class="text-left col-md-2">Account Number</th>
            <th class="text-left col-md-2">Account Name</th>
            <th class="text-left col-md-3">Notes</th>
            <th class="text-right col-md-1">Amount</th>
        </tr>
    </thead>
    <tbody class="markup">
        @foreach ($detail as $item)
        <tr>
            <td data-title="File">
                @if(!empty($item->payment_file)) 
                <a href="{{ Helper::files('payment/'.$item->payment_file) }}" target="_blank" class="btn btn-danger btn-xs" rel="noopener noreferrer">Download</a>
                @else
                <a class="btn btn-warning btn-xs" href="">No. File</a>
                @endif
            </td>
            <td data-title="ID Product">
                {{ $item->mask_voucher }}
            </td>
            <td data-title="Date">
                {{ $item->mask_date }}
            </td>
            <td data-title="Bank From" class="col-lg-1">
                {{ $item->mask_from }}
            </td>
            <td data-title="Bank To" class="col-lg-1">
                {{ $item->mask_to }}
            </td>
            <td data-title="Account" class="col-lg-1">
                {{ $item->mask_account }}
            </td>
            <td data-title="Person" class="col-lg-1">
                {{ $item->mask_person }}
            </td> 
            <td data-title="Notes" class="col-lg-2">
                {{ $item->mask_notes_approve }}
            </td>
            <td data-title="Total" class="text-right col-lg-1">
                {{ Helper::createRupiah($item->mask_approve) }}
            </td>
        </tr>
        @endforeach
    </tbody>

    <tbody>
        <tr>
            <td data-title="Total Pembayaran" colspan="8" class="text-right">
                <strong>Total Order</strong>
            </td>
            <td data-title="" class="text-right">
                {{ Helper::createRupiah($model->mask_total) }}
            </td>
        </tr>
        <tr>
            <td data-title="Total Pembayaran" colspan="8" class="text-right">
                <strong>Total Payment</strong>
            </td>
            <td data-title="" class="text-right">
                {{ Helper::createRupiah($detail->sum('payment_value_approve')) }}
            </td>
        </tr>
        <tr>
            <td data-title="Total Pembayaran" colspan="8" class="text-right">
                <strong>UnPaid </strong>
            </td>
            <td data-title="" class="text-right">
                {{ Helper::createRupiah($detail->sum('payment_value_approve') - $model->mask_total) }}
            </td>
        </tr>
    </tbody>
</table>
@endif
