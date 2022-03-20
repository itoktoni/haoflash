@extends(Views::backend())

@push('js')
<script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
@endpush

@push('javascript')
<script>
    $(document).ready(function() {
        $('#transaction').DataTable({
            "paging": true,
        });
    });
</script>
@endpush

@section('content')

<x-date :array="['date']" />

<div class="row">
    <div class="panel-body">
        {!! Form::model($model, ['route'=>[$module.'_post_payment'],'class'=>'form-horizontal','files'=>true]) !!}
        <div class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title">{{ __('Show Receive') }} : {{ $model->{$model->getKeyName()} }}
                </h2>
            </header>
            <div class="panel-body line">
                <div class="">

                    <div class="form-group">

                        {!! Form::label('name', __('Receive Date'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('po_receive_date') ? 'has-error' : ''}}">
                            <div class="input-group">
                                {!! Form::text('po_receive_date', $model->po_receive_date ?? date('Y-m-d'), ['class' =>
                                'form-control date']) !!}
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                            {!! $errors->first('po_receive_date', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('Branch'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('po_receive_buy') ? 'has-error' : ''}}">
                            {!! Form::text('po_receive_buy', $model->has_branch->branch_name ?? '' , ['class' => 'form-control', $model->po_receive_type == CategoryType::BDP ? '' : 'readonly']) !!}
                            {!! $errors->first('po_receive_buy', '<p class="help-block">:message</p>') !!}
                        </div>

                    </div>

                    <div class="form-group">

                        {!! Form::label('name', __('Buying Price'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('po_receive_buy') ? 'has-error' : ''}}">
                            {!! Form::text('po_receive_buy', null, ['class' => 'form-control', $model->po_receive_type == CategoryType::BDP ? '' : 'readonly']) !!}
                            {!! $errors->first('po_receive_buy', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('Supplier'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('po_receive_supplier_id') ? 'has-error' : ''}}">
                            {{ Form::select('po_receive_supplier_id', $supplier, null, ['class'=> 'form-control', 'id' => 'supplier']) }}
                            {!! $errors->first('po_receive_supplier_id', '<p class="help-block">:message</p>') !!}
                        </div>

                    </div>

                    <hr>

                    <div class="form-group">

                        {!! Form::label('name', __('Qty'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('po_receive_qty') ? 'has-error' : ''}}">
                            {!! Form::text('po_receive_qty', $model->po_receive_qty ?? 0, ['class' => 'form-control', 'readonly']) !!}
                            {!! $errors->first('po_receive_qty', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('Receive'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('po_receive_receive') ? 'has-error' : ''}}">
                            {!! Form::text('po_receive_receive', null, ['class' => 'form-control', 'readonly']) !!}
                            {!! $errors->first('po_receive_receive', '<p class="help-block">:message</p>') !!}
                        </div>

                    </div>

                    @if($model->po_receive_type == CategoryType::Virtual)

                    <hr>

                    <div class="form-group">

                        {!! Form::label('name', __('Start Number'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('po_receive_start') ? 'has-error' : ''}}">
                            {!! Form::text('po_receive_start', null, ['class' => 'form-control', 'readonly']) !!}
                            {!! $errors->first('po_receive_start', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('End Number'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('po_receive_end') ? 'has-error' : ''}}">
                            {!! Form::text('po_receive_end', null, ['class' => 'form-control', 'readonly']) !!}
                            {!! $errors->first('po_receive_end', '<p class="help-block">:message</p>') !!}
                        </div>


                    </div>

                    @endif

                    @if(!empty($detail))
                    <table id="transaction" class="table table-no-more table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-left col-md-2">Stock code</th>
                                <th class="text-left col-md-1">Product ID</th>
                                <th class="text-left col-md-2">Product Name</th>
                                <th class="text-left col-md-2">Product Description</th>
                                <th class="text-left col-md-1">Qty</th>
                            </tr>
                        </thead>
                        <tbody class="markup">
                            @foreach ($detail as $item)
                            <tr>
                                <td data-title="Product Name">
                                    {{ $item->stock_code ?? '' }}
                                </td>
                                <td data-title="Product ID">
                                    {{ $item->stock_product_id ?? '' }}
                                </td>
                                <td data-title="Product ID">
                                    {{ $item->has_product->mask_name ?? '' }}
                                </td>
                                <td data-title="Product ID">
                                    {!! nl2br($item->has_product->mask_description) ?? '' !!}
                                </td>
                                <td data-title="Receive" class="col-lg-1">
                                    {{ $item->stock_qty ?? '' }}
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif

                </div>
            </div>
        </div>
        <div class="navbar-fixed-bottom" id="menu_action">
            <div class="text-right action-wrapper">
                <a id="linkMenu" href="{!! route($route_index) !!}" class="btn btn-warning">{{ __('Back') }}</a>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

@endsection