<div class="form-group">

    {!! Form::label('name', __('Name'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('branch_name') ? 'has-error' : ''}}">
        {!! Form::text('branch_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('branch_name', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('PIC'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('branch_contact') ? 'has-error' : ''}}">
        {!! Form::text('branch_contact', null, ['class' => 'form-control']) !!}
        {!! $errors->first('branch_contact', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">

    {!! Form::label('name', __('Phone'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('branch_phone') ? 'has-error' : ''}}">
        {!! Form::text('branch_phone', null, ['class' => 'form-control']) !!}
        {!! $errors->first('branch_phone', '<p class="help-block">:message</p>') !!}
    </div>
    
    {!! Form::label('name', __('Address'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('branch_address') ? 'has-error' : ''}}">
        {!! Form::textarea('branch_address', null, ['class' => 'form-control', 'rows' => '2']) !!}
        {!! $errors->first('branch_address', '<p class="help-block">:message</p>') !!}
    </div>
    
</div>

<hr>

<div class="form-group">
    
    {!! Form::label('name', __('Description'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-10 col-sm-10 {{ $errors->has('branch_description') ? 'has-error' : ''}}">
        {!! Form::textarea('branch_description', null, ['class' => 'form-control', 'rows' => '3']) !!}
        {!! $errors->first('branch_description', '<p class="help-block">:message</p>') !!}
    </div>

</div>