<div class="form-group">

    {!! Form::label('name', __('Code'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('size_code') ? 'has-error' : ''}}">
        {!! Form::text('size_code', null, ['class' => 'form-control']) !!}
        {!! $errors->first('size_code', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Name'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('size_name') ? 'has-error' : ''}}">
        {!! Form::text('size_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('size_name', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">
    {!! Form::label('name', __('Description'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-10 col-sm-10">
        {!! Form::textarea('size_description', null, ['class' => 'form-control', 'rows' => '3']) !!}
    </div>
</div>