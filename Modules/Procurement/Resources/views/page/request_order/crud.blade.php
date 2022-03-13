<div class="navbar-fixed-bottom" id="menu_action">
    <div class="text-right action-wrapper">
        @switch($action_function)

        @case('index')
        @if(isset($actions['create']))
        <a href="{!! route($route_create) !!}" class="btn btn-success">{{ __('Create') }}</a>
        @endif
        
        @if(isset($actions['delete']))
        <button type="submit" onclick="return confirm('Are you sure to delete data ?');" id="delete-action"
            value="delete" name="action" class="btn btn-danger">{{ __('Delete') }}</button>
        @endif
        @break

        @case('create')
        <a id="linkMenu" href="{!! route($route_index) !!}" class="btn btn-warning">{{ __('Back') }}</a>
        <button type="reset" class="btn btn-default">{{ __('Reset') }}</button>
        @if(isset($actions['create']))
        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
        @endif
        @break

        @case('edit')
        <a id="linkMenu" href="{!! route($route_index) !!}" class="btn btn-warning">{{ __('Back') }}</a>
        @if(isset($actions['update']) && ($model->mask_status != PurchaseStatus::Cancel && $model->mask_status != PurchaseStatus::Finish))
        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
        @endif
        @break

        @case('show')
        <a id="linkMenu" href="{!! route($route_index) !!}" class="btn btn-warning">{{ __('Back') }}</a>
        @if(isset($actions['update']))
        <a id="linkMenu" href="{!! route($route_edit, ['code' => $model->{$model->getKeyName()} ]) !!}" class="btn
            btn-primary">{{ __('Update') }}</a>
        @endif
        @break

        @endswitch
    </div>
</div>
