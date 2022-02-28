<div class="form-group">

    {!! Form::label('name', 'Date', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('procurement_movement_date_order') ? 'has-error' : ''}}">
        {!! Form::text('procurement_movement_date', !empty($model->procurement_movement_date) ?
        $model->procurement_movement_date : date('Y-m-d'), ['class' =>
        'form-control date']) !!}
        {!! $errors->first('procurement_movement_date', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Status'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('procurement_movement_status') ? 'has-error' : ''}}">
        {{ Form::select('procurement_movement_status', $status, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('procurement_movement_status', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('name', __('Notes'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-10 col-sm-10">
        {!! Form::textarea('procurement_movement_notes', null, ['class' => 'form-control', 'rows' => '3']) !!}
    </div>
</div>

<div class="form-group">

    {!! Form::label('name', __('Branch From'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('procurement_movement_from_id') ? 'has-error' : ''}}">
        {{ Form::select('procurement_movement_from_id', $branch, $model->procurement_movement_from_id ?? 1, ['class'=> 'form-control ']) }}
        {!! $errors->first('procurement_movement_from_id', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Branch To'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('procurement_movement_to_id') ? 'has-error' : ''}}">
        {{ Form::select('procurement_movement_to_id', $branch, $model->procurement_movement_to_id ?? 1, ['class'=> 'form-control ']) }}
        {!! $errors->first('procurement_movement_to_id', '<p class="help-block">:message</p>') !!}
    </div>


</div>

<hr>

@include($folder.'::page.'.$template.'.detail')
@include($folder.'::page.'.$template.'.script')