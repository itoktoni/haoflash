@extends(Views::backend())

@section('content')

<x-date :array="['date']" />

<div class="row">
    <div class="panel-body">
        {!! Form::model($model, ['route'=>[$module.'_post_prepare'],'class'=>'form-horizontal','files'=>true]) !!}
        <div class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title">{{ __('Prepare') }} {{ __($form_name) }} : {{ $model->{$model->getKeyName()} }}
                </h2>
            </header>
            <div class="panel-body line">
                <div class="">

                    @include($folder.'::page.'.$template.'.combine')
                    <hr>

                    @include($folder.'::page.'.$template.'.table_prepare')

                </div>
            </div>
        </div>
        <div class="navbar-fixed-bottom" id="menu_action">
            <div class="text-right action-wrapper">
                <a id="linkMenu" href="{!! route($route_index) !!}" class="btn btn-warning">{{ __('Back') }}</a>
                <!-- <a id="linkMenu" href="{!! route($route_index) !!}" class="btn btn-danger">{{ __('Print Receive') }}</a> -->
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

@endsection