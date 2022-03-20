<div class="form-group">
    {!! Form::label('name', 'Date', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('ro_date_order') ? 'has-error' : ''}}">
        {!! Form::text('ro_date_order', !empty($model->ro_date_order) ? $model->ro_date_order : date('Y-m-d'), ['class' =>
        'form-control date']) !!}
        {!! $errors->first('ro_date_order', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Status'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('ro_status') ? 'has-error' : ''}}">
        {{ Form::select('ro_status', $status, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('ro_status', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">

    {!! Form::label('name', __('Request From'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('ro_branch_id') ? 'has-error' : ''}}">
        {{ Form::select('ro_branch_id', $branch, null, ['class'=> 'form-control', 'id' => 'branch']) }}
        {!! $errors->first('ro_branch_id', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Notes'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4">
        {!! Form::textarea('ro_notes', null, ['class' => 'form-control', 'rows' => '3']) !!}
    </div>

</div>