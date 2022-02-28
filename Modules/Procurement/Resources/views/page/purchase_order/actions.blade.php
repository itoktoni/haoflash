<div class="action text-center">
    @if (isset($actions['update']))
    <a id="linkMenu" href="{{ route($route_edit, ['code' => $model->{$model->getKeyName()}]) }}" class="btn btn-xs btn-primary">@lang('pages.update')</a>
    @endif
    @if (isset($actions['show']))
    <a id="linkMenu" href="{{ route($route_show, ['code' => $model->{$model->getKeyName()}]) }}" class="btn btn-xs btn-warning">@lang('pages.show')</a>
    @endif
    @if (isset($actions['form_payment']))
    <a id="linkMenu" href="{{ route($module.'_form_payment', ['code' => $model->{$model->getKeyName()}]) }}" class="btn btn-xs btn-success">Payment</a>
    @endif
    @if (isset($actions['form_receive']))
    <a id="linkMenu" href="{{ route($module.'_form_receive', ['code' => $model->{$model->getKeyName()}]) }}" class="btn btn-xs btn-info">Receive</a>
    @endif
</div>