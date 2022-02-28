<div class="form-group">

    {!! Form::label('name', __('Branch'), ['class' => 'col-md-1 col-sm-2 control-label']) !!}
    <div class="col-md-3 col-sm-3 {{ $errors->has('stock_branch_id') ? 'has-error' : ''}}">
        {{ Form::select('stock_branch_id', $branch, $model->stock_branch_id ?? 1, ['class'=> 'form-control ']) }}
        {!! $errors->first('stock_branch_id', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Product'), ['class' => 'col-md-1 col-sm-2 control-label']) !!}
    <div class="col-md-3 col-sm-3 {{ $errors->has('stock_product_id') ? 'has-error' : ''}}">
        {{ Form::select('stock_product_id', $product, $model->stock_product_id ?? 1, ['class'=> 'form-control ']) }}
        {!! $errors->first('stock_product_id', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Qty'), ['class' => 'col-md-1 col-sm-2 control-label']) !!}
    <div class="col-md-3 col-sm-3">
        {!! Form::text('stock_qty', null, ['class' => 'form-control']) !!}
    </div>

</div>