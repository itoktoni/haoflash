<div class="form-group">

    {!! Form::label('name', __('Name'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('location_name') ? 'has-error' : ''}}">
        {!! Form::text('location_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('location_name', '<p class="help-block">:message</p>') !!}
    </div>


    {!! Form::label('name', __('Warehouse'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('location_warehouse_id') ? 'has-error' : ''}}">
        {{ Form::select('location_warehouse_id', $warehouse, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('location_warehouse_id', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">

    {!! Form::label('name', __('Description'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-10 col-sm-10">
        {!! Form::textarea('location_description', null, ['class' => 'form-control', 'rows' => '3']) !!}
    </div>

</div>