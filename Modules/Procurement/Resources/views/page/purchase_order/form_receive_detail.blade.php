@extends(Views::backend())

@section('content')

<x-date :array="['date']" />
<x-mask :array="['number', 'money']" />

<div class="row">
    <div class="panel-body">
        {!! Form::model($model, ['route'=>[$module.'_post_receive_detail'],'class'=>'form-horizontal','files'=>true]) !!}
        <div class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title">{{ __('Receive') }} : {{ __($model->purchase_product_name ?? '' )}}
                </h2>
            </header>
            <div class="panel-body line">
                <div class="">

                    <div class="form-group">
                        {!! Form::label('name', __('Purchase Date'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 ">
                            <div class="input-group">
                                {!! Form::text('', $model->purchase_date, ['class' =>
                                'form-control date', 'readonly']) !!}
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                        </div>

                        {!! Form::label('name', __('Status'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('purchase_status') ? 'has-error' : ''}}">
                            {!! Form::text('', PurchaseStatus::getDescription($model->purchase_status) ?? null, ['class' => 'form-control', 'readonly']) !!}
                            {!! $errors->first('purchase_status', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">

                        {!! Form::label('name', __('Supplier Name'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4">
                            {!! Form::text('', $model->purchase_supplier ?? '', ['class' => 'form-control', 'readonly']) !!}
                        </div>

                        {!! Form::label('name', __('Notes'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4">
                            {!! Form::textarea('', $model->purchase_notes ?? '', ['class' => 'form-control',
                            'rows' => 3, 'readonly']) !!}
                        </div>

                    </div>

                    <hr>

                    <div class="form-group">

                    {!! Form::label('name', __('Buy'), ['class' => 'col-md-1 col-sm-1 control-label']) !!}
                        <div class="col-md-3 col-sm-3 {{ $errors->has('po_receive_buy') ? 'has-error' : ''}}">
                            {!! Form::text('po_receive_buy', null, ['class' => 'form-control number', $model->po_receive_type == CategoryType::BDP ? '' : 'readonly']) !!}
                            {!! $errors->first('po_receive_buy', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('Receive'), ['class' => 'col-md-1 col-sm-1 control-label']) !!}
                        <div class="col-md-3 col-sm-3 {{ $errors->has('po_receive_date') ? 'has-error' : ''}}">
                            <div class="input-group">
                                {!! Form::text('po_receive_date', $model->po_receive_date ?? date('Y-m-d'), ['class' =>
                                'form-control date']) !!}
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                            {!! $errors->first('po_receive_date', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('Branch'), ['class' => 'col-md-1 col-sm-1 control-label']) !!}
                        <div class="col-md-3 col-sm-3 {{ $errors->has('po_receive_branch_id') ? 'has-error' : ''}}">
                            {{ Form::select('po_receive_branch_id', $branch, null, ['class'=> 'form-control ']) }}
                            {!! $errors->first('po_receive_branch_id', '<p class="help-block">:message</p>') !!}
                        </div>

                    </div>

                    <div class="form-group">

                        <input type="hidden" value="{{ $model->po_receive_po_code ?? null }}" name="code">
                        <input type="hidden" value="{{ $model->po_receive_po_code ?? null }}" name="po_receive_po_code">
                        <input type="hidden" value="{{ $model->po_receive_type ?? 0 }}" name="po_receive_type">
                        <input type="hidden" value="{{ $model->po_receive_supplier_id ?? '' }}" name="po_receive_supplier_id">
                        <input type="hidden" value="{{ $model->po_receive_product_id ?? '' }}" name="po_receive_product_id">
                        <input type="hidden" value="{{ $detail->sum('po_receive_receive') }}" name="remaining">
                        <input type="hidden" value="{{ $model->purchase_status ?? '' }}" name="purchase_status">

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
                            {!! Form::text('po_receive_receive', 0, ['class' => 'form-control']) !!}
                            {!! $errors->first('po_receive_receive', '<p class="help-block">:message</p>') !!}
                        </div>

                    </div>

                    @if($model->po_receive_type == CategoryType::Virtual || $model->po_receive_type == CategoryType::BDP)

                    <hr>

                    <div class="form-group">

                        {!! Form::label('name', __('Start'), ['class' => 'col-md-1 col-sm-1 control-label']) !!}
                        <div class="col-md-3 col-sm-3 {{ $errors->has('po_receive_start') ? 'has-error' : ''}}">
                            {!! Form::text('po_receive_start', '', ['class' => 'form-control']) !!}
                            {!! $errors->first('po_receive_start', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('End'), ['class' => 'col-md-1 col-sm-1 control-label']) !!}
                        <div class="col-md-3 col-sm-3 {{ $errors->has('po_receive_end') ? 'has-error' : ''}}">
                            {!! Form::text('po_receive_end', '', ['class' => 'form-control']) !!}
                            {!! $errors->first('po_receive_end', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('Expired'), ['class' => 'col-md-1 col-sm-1 control-label']) !!}
                        <div class="col-md-3 col-sm-3 {{ $errors->has('po_receive_expired_date') ? 'has-error' : ''}}">
                            <div class="input-group">
                                {!! Form::text('po_receive_expired_date', $model->po_receive_expired_date ?? date('Y-m-d'), ['class' =>
                                'form-control date']) !!}
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                            {!! $errors->first('po_receive_expired_date', '<p class="help-block">:message</p>') !!}
                        </div>

                    </div>

                    @endif

                    @include($folder.'::page.'.$template.'.table_receive_detail')

                </div>
            </div>
        </div>
        <div class="navbar-fixed-bottom" id="menu_action">
            <div class="text-right action-wrapper">
                <a id="linkMenu" href="{!! route($route_index) !!}" class="btn btn-warning">{{ __('Back') }}</a>
                @isset($actions['post_payment'])
                @if($model->purchase_status != PurchaseStatus::Cancel && $model->purchase_status != PurchaseStatus::Finish)

                <button type="submit" class="btn btn-primary">{{ __('Receive') }}</button>
                @endisset
                @endisset
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

@endsection