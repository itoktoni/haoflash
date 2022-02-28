<div class="form-group">

    {!! Form::label('name', __('Name'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('branch_name') ? 'has-error' : ''}}">
        {!! Form::text('branch_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('branch_name', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Active'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('branch_status') ? 'has-error' : ''}}">
        {{ Form::select('branch_status', ['1' => 'Yes', '0' => 'No'], null, ['class'=> 'form-control ']) }}
        {!! $errors->first('branch_status', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('name', __('Description'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4">
        {!! Form::textarea('branch_description', null, ['class' => 'form-control', 'rows' => '3']) !!}
    </div>
    
    {!! Form::label('name', __('Address'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4">
        {!! Form::textarea('branch_address', null, ['class' => 'form-control', 'rows' => '3']) !!}
    </div>
</div>