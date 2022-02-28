<div class="form-group">

    {!! Form::label('name', __('Tax Code'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('tax_code') ? 'has-error' : ''}}">
        {!! $errors->first('tax_code', '<p class="help-block">:message</p>') !!}
        <div class="input-group">
            {!! Form::text('tax_code', null, ['class' => 'form-control']) !!}
            <span class="input-group-addon">
                %
            </span>
        </div>
    </div>

    
    {!! Form::label('name', __('Tax Name'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('tax_name') ? 'has-error' : ''}}">
        {!! $errors->first('tax_name', '<p class="help-block">:message</p>') !!}
        {!! Form::text('tax_name', null, ['class' => 'form-control']) !!}
    </div>

</div>
<div class="form-group">

    {!! Form::label('name', __('Description'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-10 col-sm-10">
        {!! Form::textarea('tax_description', null, ['class' => 'form-control', 'rows' => '3']) !!}
    </div>

</div>