<div class="action text-center">
    @if (isset($actions['update']))
    <a id="linkMenu" href="{{ route($route_edit, ['code' => $model->{$model->getKeyName()}]) }}" class="btn btn-xs btn-primary">@lang('pages.update')</a>
    <a id="linkMenu" href="{{ route($module.'_copy', ['code' => $model->{$model->getKeyName()}]) }}" class="btn btn-xs btn-success">Delivery</a>
    @endif
</div>