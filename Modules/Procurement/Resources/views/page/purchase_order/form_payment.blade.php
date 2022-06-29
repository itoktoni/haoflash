@extends(Views::backend())

@section('content')

<x-date :array="['date']" />
<x-mask :array="['number', 'money']" />

<div class="row">
    <div class="panel-body">
        {!! Form::model($model, ['route'=>[$module.'_post_payment'],'class'=>'form-horizontal','files'=>true]) !!}
        <div class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title">{{ __('Payment') }} {{ __($form_name) }} : {{ $model->{$model->getKeyName()} }}
                </h2>
            </header>
            <div class="panel-body line">
                <div class="">

                    <div class="form-group">

                        {!! Form::label('name', __('Account Name'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('payment_person') ? 'has-error' : ''}}">
                            {!! Form::text('payment_person', $supplier->mask_name ?? null, ['class' => 'form-control']) !!}
                            {!! $errors->first('payment_person', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('Date'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('payment_date') ? 'has-error' : ''}}">
                            <div class="input-group">
                                {!! Form::text('payment_date', $model->payment_date ?? date('Y-m-d'), ['class' =>
                                'form-control date']) !!}
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                            {!! $errors->first('payment_date', '<p class="help-block">:message</p>') !!}
                        </div>

                    </div>

                    <div class="form-group">

                        {!! Form::label('name', __('Payment Method'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('payment_method') ? 'has-error' : ''}}">
                            {{ Form::select('payment_method', $method, null, ['class'=> 'form-control ']) }}
                            {!! $errors->first('payment_method', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('Bank'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('payment_from') ? 'has-error' : ''}}">
                            {{ Form::select('payment_from', $bank, null, ['class'=> 'form-control ']) }}
                            {!! $errors->first('payment_from', '<p class="help-block">:message</p>') !!}
                        </div>

                    </div>

                    <div class="form-group">

                        {!! Form::label('name', __('Amount'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('payment_value_approve') ? 'has-error' : ''}}">
                            {!! Form::text('payment_value_approve', null, ['class' => 'form-control number']) !!}
                            {!! $errors->first('payment_value_approve', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('Notes'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('payment_notes_approve') ? 'has-error' : ''}}">
                            {!! Form::textarea('payment_notes_approve', null, ['class' => 'form-control',
                            'rows' => 3]) !!}
                            {!! $errors->first('payment_notes_approve', '<p class="help-block">:message</p>') !!}
                        </div>

                        <input type="hidden" value="{{ $model->{$model->getKeyName()} }}" name="code">

                    </div>

                    <!--
                    <div class="form-group">

                        {!! Form::label('name', __('File'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4" {{ $errors->has('file') ? 'has-error' : ''}}">
                            <input type="file" name="file" class="{{ $errors->has('file') ? 'has-error' : ''}} btn btn-default btn-sm btn-block">
                            {!! $errors->first('file', '<p class="help-block">:message</p>') !!}
                        </div>

                        {!! Form::label('name', __('Account'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                        <div class="col-md-4 col-sm-4 {{ $errors->has('payment_account') ? 'has-error' : ''}}">
                            {!! Form::text('payment_account', $supplier->supplier_bank_account ?? null, ['class' => 'form-control']) !!}
                            {!! $errors->first('payment_account', '<p class="help-block">:message</p>') !!}
                        </div>

                    </div>

                    -->

                    <hr>

                    @include($folder.'::page.'.$template.'.table_payment')

                </div>
            </div>
        </div>
        <div class="navbar-fixed-bottom" id="menu_action">
            <div class="text-right action-wrapper">
                <a id="linkMenu" href="{!! route($route_index) !!}" class="btn btn-warning">{{ __('Back') }}</a>
                @if(isset($actions['post_payment']) && $model->mask_payment != PurchasePayment::Paid && $model->mask_status != PurchaseStatus::Create)
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                @endif
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

@endsection