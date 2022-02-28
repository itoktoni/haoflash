<div class="form-group">

    {!! Form::label('name', __('Name'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('company_name') ? 'has-error' : ''}}">
        {!! Form::text('company_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('company_name', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Category'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('company_type') ? 'has-error' : ''}}">
        {{ Form::select('company_type', $status, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('company_type', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">

    {!! Form::label('name', __('Npwp'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('company_npwp') ? 'has-error' : ''}}">
        {!! Form::text('company_npwp', null, ['class' => 'form-control']) !!}
        {!! $errors->first('company_npwp', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Image'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4" {{ $errors->has('product_image') ? 'has-error' : ''}}">
        <input type="hidden" value="{{ $model->company_logo ?? null }}" name="company_logo">
        <input type="file" name="file"
            class="{{ $errors->has('company_logo') ? 'has-error' : ''}} btn btn-default btn-sm btn-block">
        {!! $errors->first('product_image', '<p class="help-block">:message</p>') !!}
    </div>

</div>


<div class="form-group">

    {!! Form::label('name', __('Address'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4">
        {!! Form::textarea('company_address', null, ['class' => 'form-control', 'rows' => '3']) !!}
    </div>

    {!! Form::label('name', __('Description'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4">
        {!! Form::textarea('company_description', null, ['class' => 'form-control', 'rows' => '3']) !!}
    </div>

</div>