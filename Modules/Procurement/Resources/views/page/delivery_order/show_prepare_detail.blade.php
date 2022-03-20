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
        {!! Form::model($model, ['route'=>[$module.'_post_prepare_detail'],'class'=>'form-horizontal','files'=>true]) !!}
        <div class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title">{{ __('Show Receive') }} : {{ $model->{$model->getKeyName()} }}
                </h2>
            </header>
            <div class="panel-body line">
                <div class="">

                    <div class="form-group">

                        {!! Form::label('name', __('Receive Date'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('do_prepare_date') ? 'has-error' : ''}}">
                            <div class="input-group">
                                {!! Form::text('do_prepare_date', $model->do_prepare_date ?? date('Y-m-d'), ['class' =>
                                'form-control date']) !!}
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                            {!! $errors->first('do_prepare_date', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('Branch'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('do_prepare_buy') ? 'has-error' : ''}}">
                            {!! Form::text('do_prepare_buy', $model->has_branch->branch_name ?? '' , ['class' => 'form-control', $model->do_prepare_type == CategoryType::BDP ? '' : 'readonly']) !!}
                            {!! $errors->first('do_prepare_buy', '<p class="help-block">:message</p>') !!}
                        </div>

                    </div>

                    <hr>

                    <div class="form-group">

                        {!! Form::label('name', __('Qty'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('do_prepare_qty') ? 'has-error' : ''}}">
                            {!! Form::text('do_prepare_qty', $model->do_prepare_qty ?? 0, ['class' => 'form-control', 'readonly']) !!}
                            {!! $errors->first('do_prepare_qty', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('Prepare'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('do_prepare_prepare') ? 'has-error' : ''}}">
                            {!! Form::text('do_prepare_prepare', null, ['class' => 'form-control', 'readonly']) !!}
                            {!! $errors->first('do_prepare_prepare', '<p class="help-block">:message</p>') !!}
                        </div>

                    </div>

                    @if($model->do_prepare_type == CategoryType::Virtual)

                    <hr>

                    <div class="form-group">

                        {!! Form::label('name', __('Start Number'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('do_prepare_start') ? 'has-error' : ''}}">
                            {!! Form::text('do_prepare_start', null, ['class' => 'form-control', 'readonly']) !!}
                            {!! $errors->first('do_prepare_start', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('End Number'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('do_prepare_end') ? 'has-error' : ''}}">
                            {!! Form::text('do_prepare_end', null, ['class' => 'form-control', 'readonly']) !!}
                            {!! $errors->first('do_prepare_end', '<p class="help-block">:message</p>') !!}
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