<x-date :array="['date']" />
<x-mask :array="['number', 'money']" />

<div class="form-group">

    {!! Form::label('name', __('Bank From'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('payment_bank_from') ? 'has-error' : ''}}">
        {!! Form::text('payment_bank_from', null, ['class' => 'form-control']) !!}
        {!! $errors->first('payment_bank_from', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Bank To'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('payment_bank_to') ? 'has-error' : ''}}">
        {!! Form::text('payment_bank_to', null, ['class' => 'form-control']) !!}
        {!! $errors->first('payment_bank_to', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">

    {!! Form::label('name', __('Person'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('payment_person') ? 'has-error' : ''}}">
        {!! Form::text('payment_person', null, ['class' => 'form-control']) !!}
        {!! $errors->first('payment_person', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Date'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('payment_date') ? 'has-error' : ''}}">
        <div class="input-group">
            {!! Form::text('payment_date', $model->payment_date ?? date('Y-m-d'), ['class' => 'form-control date']) !!}
            <span class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </span>
        </div>
        {!! $errors->first('payment_date', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">

    {!! Form::label('name', __('Payment User'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('payment_value_user') ? 'has-error' : ''}}">
        {!! Form::text('payment_value_user', null, ['class' => 'form-control', 'disabled']) !!}
        {!! $errors->first('payment_value_user', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Payment Approve'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('payment_value_approve') ? 'has-error' : ''}}">
        {!! Form::text('payment_value_approve', null, ['class' => 'form-control number']) !!}
        {!! $errors->first('payment_value_approve', '<p class="help-block">:message</p>') !!}
    </div>



</div>

<div class="form-group">

    {!! Form::label('name', __('Notes User'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('payment_notes_user') ? 'has-error' : ''}}">
        {!! Form::textarea('payment_notes_user', null, ['class' => 'form-control', 'disabled', 'rows' => 3]) !!}
        {!! $errors->first('payment_notes_user', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Notes Admin'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('payment_notes_approve') ? 'has-error' : ''}}">
        {!! Form::textarea('payment_notes_approve', null, ['class' => 'form-control', 'rows' => 3]) !!}
        {!! $errors->first('payment_notes_approve', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">

    {!! Form::label('name', __('Payment Model'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('payment_model') ? 'has-error' : ''}}">
        {{ Form::select('payment_model', $mod, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('payment_model', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Status'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('payment_status') ? 'has-error' : ''}}">
        {{ Form::select('payment_status', $status, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('payment_status', '<p class="help-block">:message</p>') !!}
    </div>

</div>


<div class="form-group">

    {!! Form::label('name', __('Reference'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('payment_reference') ? 'has-error' : ''}}">
        {!! Form::text('payment_reference', null, ['class' => 'form-control']) !!}
        {!! $errors->first('payment_reference', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Attachement'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4" {{ $errors->has('payment_file') ? 'has-error' : ''}}">
        <div class="input-group">
            <input type="file" name="file" class="{{ $errors->has('payment_file') ? 'has-error' : ''}} btn btn-default btn-sm btn-block">
            <span class="input-group-addon">
                <a target="_blank" class="text-primary" href="{{ isset($model) ? Helper::files('payment/'.$model->payment_file) : '' }}">Download</a>
            </span>
        </div>

        {!! $errors->first('payment_file', '<p class="help-block">:message</p>') !!}
    </div>

</div>