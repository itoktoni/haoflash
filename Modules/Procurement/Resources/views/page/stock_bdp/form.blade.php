<x-date :array="['date']" />

<div class="form-group">

    {!! Form::label('name', 'Tanggal Aktivasi', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('stock_activated_at') ? 'has-error' : ''}}">
        {!! Form::text('stock_activated_at', !empty($model->stock_activated_at) ? $model->stock_activated_at : date('Y-m-d'), ['class' =>
        'form-control date']) !!}
        {!! $errors->first('stock_activated_at', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Estimasi Qty'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('qty') ? 'has-error' : ''}}">
        {!! Form::text('qty', null, ['class' => 'form-control']) !!}
        {!! $errors->first('qty', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">

    {!! Form::label('name', __('Product BOM'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('stock_refer_product_id') ? 'has-error' : ''}}">
        {{ Form::select('stock_refer_product_id', $bdp, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('stock_refer_product_id', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Product Jadi'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('stock_product_id') ? 'has-error' : ''}}">
        {{ Form::select('stock_product_id', $voucher, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('stock_product_id', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">

    {!! Form::label('name', __('Start Number'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('start') ? 'has-error' : ''}}">
        {!! Form::text('start', null, ['class' => 'form-control']) !!}
        {!! $errors->first('start', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('End Number'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('end') ? 'has-error' : ''}}">
        {!! Form::text('end', null, ['class' => 'form-control']) !!}
        {!! $errors->first('end', '<p class="help-block">:message</p>') !!}
    </div>

</div>