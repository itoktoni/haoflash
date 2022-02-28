<div class="form-group">
    {!! Form::label('name', 'Date', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('po_date_order') ? 'has-error' : ''}}">
        {!! Form::text('po_date_order', !empty($model->po_date_order) ? $model->po_date_order : date('Y-m-d'), ['class' =>
        'form-control date']) !!}
        {!! $errors->first('po_date_order', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Status'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('po_status') ? 'has-error' : ''}}">
        {{ Form::select('po_status', $status, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('po_status', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">

    {!! Form::label('name', __('Supplier'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('po_supplier_id') ? 'has-error' : ''}}">
        {{ Form::select('po_supplier_id', $supplier, null, ['class'=> 'form-control', 'id' => 'supplier']) }}
        {!! $errors->first('po_supplier_id', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Notes'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4">
        {!! Form::textarea('po_notes', null, ['class' => 'form-control', 'rows' => '3']) !!}
    </div>

</div>