@extends(Views::backend())

@section('content')

<x-date :array="['date']" />
<x-mask :array="['number', 'money']" />

<div class="row">
    <div class="panel-body">
        {!! Form::model($model, ['route'=>[$module.'_post_prepare_detail'],'class'=>'form-horizontal','files'=>true]) !!}
        <div class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title">{{ __('Prepare') }} : {{ __($prepare->has_product->mask_name ?? '' )}} untuk Cabang {{ $model->has_branch->mask_name ?? '' }}
                </h2>
            </header>
            <div class="panel-body line">
                <div class="">

                    <div class="form-group">
                        {!! Form::label('name', __('Prepare Date'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 ">
                            <div class="input-group">
                                {!! Form::text('do_prepare_date', $model->do_prepare_date ?? date('Y-m-d'), ['class' =>
                                'form-control date', 'readonly']) !!}
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                        {!! Form::label('name', __('Status'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('purchase_status') ? 'has-error' : ''}}">
                            {!! Form::text('', DeliveryStatus::getDescription($model->mask_status) ?? null, ['class' => 'form-control', 'readonly']) !!}
                            {!! $errors->first('purchase_status', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">

                        {!! Form::label('name', __('Buying Price'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('do_prepare_buy') ? 'has-error' : ''}}">
                            {!! Form::text('do_prepare_buy', $prepare->mask_price ?? null, ['class' => 'form-control number', 1 == CategoryType::BDP ? '' : 'readonly']) !!}
                            {!! $errors->first('do_prepare_buy', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('Selling Price'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('do_prepare_sell') ? 'has-error' : ''}}">
                            {!! Form::text('do_prepare_sell', $prepare->mask_sell ?? 0, ['class' => 'form-control number']) !!}
                            {!! $errors->first('do_prepare_sell', '<p class="help-block">:message</p>') !!}
                        </div>

                    </div>

                    <div class="form-group">

                        {!! Form::label('name', __('Qty'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('do_prepare_qty') ? 'has-error' : ''}}">
                            {!! Form::text('do_prepare_qty', $prepare->mask_qty ?? null, ['class' => 'form-control', 'readonly']) !!}
                            {!! $errors->first('do_prepare_qty', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('Prepare'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('do_prepare_prepare') ? 'has-error' : ''}}">
                            {!! Form::text('do_prepare_prepare', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('do_prepare_prepare', '<p class="help-block">:message</p>') !!}
                        </div>

                    </div>
                    <input type="hidden" value="{{ $model->mask_branch_id ?? '' }}" name="do_prepare_branch_id">
                    <input type="hidden" value="{{ $model->{$model->getKeyName()} ?? '' }}" name="do_prepare_do_code">
                    <input type="hidden" value="{{ $prepare->mask_product_id ?? '' }}" name="do_prepare_product_id">
                    <input type="hidden" value="{{ $prepare->has_product->has_category->mask_type ?? '' }}" name="do_prepare_type">
                    <input type="hidden" value="{{ $total ?? 0 }}" name="remaining">
                    <input type="hidden" value="{{ $model->mask_status ?? '' }}" name="status">
                    <input type="hidden" value="{{ $prepare->mask_expired ?? '' }}" name="do_prepare_expired">
                    <input type="hidden" value="{{ $prepare->do_detail_key ?? '' }}" name="do_prepare_key">
                    <input type="hidden" value="{{ $supplier ?? '' }}" name="do_prepare_supplier_id">

                    @if($prepare->has_product->has_category->mask_type != CategoryType::Accesories)

                    <hr>

                    <div class="form-group">

                        {!! Form::label('name', __('Start Number'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('do_prepare_start') ? 'has-error' : ''}}">
                            {!! Form::text('do_prepare_start', '', ['class' => 'form-control']) !!}
                            {!! $errors->first('do_prepare_start', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('End Number'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('do_prepare_end') ? 'has-error' : ''}}">
                            {!! Form::text('do_prepare_end', '', ['class' => 'form-control']) !!}
                            {!! $errors->first('do_prepare_end', '<p class="help-block">:message</p>') !!}
                        </div>

                    </div>

                    @endif

                    @include($folder.'::page.'.$template.'.table_prepare_detail')

                </div>
            </div>
        </div>
        <div class="navbar-fixed-bottom" id="menu_action">
            <div class="text-right action-wrapper">
                <a id="linkMenu" href="{!! route($route_index) !!}" class="btn btn-warning">{{ __('Back') }}</a>
                @if(($model->mask_status == DeliveryStatus::Create)  || ($model->mask_status == DeliveryStatus::Prepare || (isset($detail) && ($prepare->mask_qty != $detail->sum('do_prepare_prepare')))))
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                @endif
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

@endsection