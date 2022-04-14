@if(!empty($detail))

<hr>

<table id="transaction" class="table table-no-more table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-left col-md-2">Receive Code</th>
            <th class="text-left col-md-1">Receive Date</th>
            <th class="text-left col-md-1">Created Time</th>
            <th class="text-left col-md-1">Price</th>
            @if($model->do_prepare_type != CategoryType::Accesories)
            <th class="text-left col-md-1">Start Number</th>
            <th class="text-left col-md-1">End Number</th>
            @endif
            <th style="width: 4%;" class="text-left">Qty</th>
        </tr>
    </thead>
    <tbody class="markup">
        @foreach ($detail as $item)
        <tr>
            <td data-title="Receive Code">
                <a href="{!! route($route_index) !!}" class="btn btn-info btn-xs">
                    {{ $item->do_prepare_code }}
                </a>
                <a onClick="return confirm('Are you sure you want to delete?')" href="{!! route($module.'_delete_prepare_detail', ['code' => $item->do_prepare_code]) !!}" class="btn btn-danger btn-xs">
                    Delete
                </a>
            </td>
            <td data-title="Receive Date">
                {{ $item->do_prepare_date ?? '' }}
            </td>
            <td data-title="Receive Date">
                {{ $item->do_prepare_created_at ?? '' }}
            </td>
            <td data-title="Branch">
                {{ Helper::createRupiah($item->mask_sell) ?? '' }}
            </td>
            @if($model->do_prepare_type != CategoryType::Accesories)
            <td data-title="Start Number">
                {{ $item->do_prepare_start ?? '' }}
            </td>
            <td data-title="End Number">
                {{ $item->do_prepare_end ?? '' }}
            </td>
            @endif

            <td data-title="Receive" class="col-lg-1">
                {{ $item->do_prepare_prepare }}
            </td>
        </tr>
        @endforeach
        <tr>
            <td colspan="{{ $model->do_prepare_type != CategoryType::Accesories ? '6' : '3' }}">Total Penerimaan Barang</td>
            <td>{{ $detail->sum('do_prepare_prepare') }}</td>
        </tr>
    </tbody>
</table>
@endif