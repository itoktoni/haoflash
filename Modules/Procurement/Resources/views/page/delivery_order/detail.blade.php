<div id="detail" class="panel-body">
    <div class="panel panel-default">
        <div class="row">
            <div class="col-md-6">
                @if ($model->getKeyName() && !old('temp_id'))
                <h2 id="total" class="panel-title text-left">
                    <span id="total_name">Total</span>
                    <span class="money" id="total_value">
                        {{ number_format($model->mask_total) }}
                    </span>
                    <input type="hidden" id="hidden_total" value="{{ $model->mask_total }}" name="total">
                </h2>
                @else
                <h2 id="total" class="panel-title text-left">
                    <span id="total_name">{{ old('total') ? 'Total' : '' }}</span>
                    <span class="money" id="total_value">{{ old('total') ? number_format(old('total')) : '' }}</span>
                    <input type="hidden" id="hidden_total" value="{{ old('total') ? old('total') : 0 }}" name="total">
                </h2>
                @endif
            </div>

        </div>
        <div class="panel-body line">

            <div class="form-group">
                <label class="col-md-1 control-label" for="inputDefault">Category</label>
                <div class="col-md-2 {{ $errors->has('category') ? 'has-error' : ''}}">
                    {{ Form::select('', $category, null, ['class'=> 'form-control', 'id' => 'category']) }}
                </div>

                <label class="col-md-1 control-label" for="inputDefault">Product</label>
                <div class="col-md-3 {{ $errors->has('product') ? 'has-error' : ''}}">
                    {{ Form::select('', $product, null, ['class'=> 'form-control', 'id' => 'product']) }}
                </div>

                {!! Form::label('name', __('Notes'), ['class' => 'col-md-1 col-sm-1 control-label']) !!}
                <div class="col-md-4 col-sm-4" style="margin-top:-40px">
                    {!! Form::textarea('', null, ['class' => 'form-control', 'rows' => '3', 'readonly', 'id' => 'desc']) !!}
                </div>

            </div>
            <div class="">
                <div class="form-group {{ $errors->has('detail') ? 'has-error' : ''}}">

                    <label class="col-md-1 control-label" for="inputDefault">Qty</label>
                    <div class="col-md-2">
                        {!! Form::text('', null, ['id' => 'qty', 'class' => 'number form-control']) !!}
                    </div>

                    <label class="col-md-1 control-label" for="inputDefault">Price</label>
                    <div class="col-md-3">
                        {!! Form::text('', null, ['id' => 'price', 'class' => 'money form-control']) !!}
                    </div>

                    <label class="col-md-1 control-label" for="inputDefault">Total</label>
                    <div class="col-md-2">
                        {!! Form::text('', null, ['id' => 'sub_total', 'readonly', 'class' => 'number form-control'])
                        !!}
                    </div>

                    <div class="col-md-2">
                        <h2 class="panel-title text-right">
                            <span style="margin-top:0px" id="add" class="btn btn-primary btn-block detail">Add Detail</span>
                        </h2>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

@include($folder.'::page.'.$template.'.table')