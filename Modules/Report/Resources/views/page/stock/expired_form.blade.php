<x-date :array="['date']" />


<div class="form-group">


	{!! Form::label('name', __('Branch'), ['class' => 'col-md-1 col-sm-1 control-label']) !!}
	<div class="col-md-3 col-sm-3 {{ $errors->has('stock_branch_id') ? 'has-error' : ''}}">
		{{ Form::select('stock_branch_id', $branch, request()->get('stock_branch_id') ?? null, ['class'=> 'form-control ']) }}
		{!! $errors->first('stock_branch_id', '<p class="help-block">:message</p>') !!}
	</div>

	{!! Form::label('name', __('Supplier'), ['class' => 'col-md-1 col-sm-1 control-label']) !!}
	<div class="col-md-3 col-sm-3 {{ $errors->has('stock_supplier_id') ? 'has-error' : ''}}">
		{{ Form::select('stock_supplier_id', $supplier, request()->get('stock_supplier_id') ?? null, ['class'=> 'form-control ']) }}
		{!! $errors->first('stock_supplier_id', '<p class="help-block">:message</p>') !!}
	</div>

	{!! Form::label('name', __('Product'), ['class' => 'col-md-1 col-sm-1 control-label']) !!}
	<div class="col-md-3 col-sm-3 {{ $errors->has('stock_product_id') ? 'has-error' : ''}}">
		{{ Form::select('stock_product_id', $product, request()->get('stock_product_id') ?? null, ['class'=> 'form-control ']) }}
		{!! $errors->first('stock_product_id', '<p class="help-block">:message</p>') !!}
	</div>

</div>



@isset($preview)

<hr>
@includeIf(Views::form(request()->get('name'), $template, $folder))

@endif