<div class="form-group">

    {!! Form::label('name', __('Category'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('product_category_id') ? 'has-error' : ''}}">
        {{ Form::select('product_category_id', $category, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('product_category_id', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Name'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('product_name') ? 'has-error' : ''}}">
        {!! Form::text('product_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('product_name', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">

    {!! Form::label('name', __('Minimum'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('product_min') ? 'has-error' : ''}}">
        {!! Form::text('product_min', null, ['class' => 'form-control']) !!}
        {!! $errors->first('product_min', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Maximum'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('product_max') ? 'has-error' : ''}}">
        {!! Form::text('product_max', null, ['class' => 'form-control']) !!}
        {!! $errors->first('product_max', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">

    {!! Form::label('name', __('Price'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('product_buy') ? 'has-error' : ''}}">
        {!! Form::number('product_buy', null, ['class' => 'form-control']) !!}
        {!! $errors->first('product_buy', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Description'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4">
        {!! Form::textarea('product_description', null, ['class' => 'form-control', 'rows' => '5']) !!}
    </div>

</div>