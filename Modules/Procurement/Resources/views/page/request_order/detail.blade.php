<div id="detail" class="panel-body">
    <div class="panel panel-default">
        <div class="row">
            <div class="col-md-10">
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
            <div class="col-md-2">
                <h2 class="panel-title text-right" style="margin-top:10px;margin-right:15px">
                    <span id="add" class="btn btn-primary btn-block detail">Add
                        Detail</span>
                </h2>
            </div>
        </div>
        <div class="panel-body line">

            <div class="form-group">
                <label class="col-md-2 control-label" for="inputDefault">Category</label>
                <div class="col-md-4 {{ $errors->has('category') ? 'has-error' : ''}}">
                    {{ Form::select('', $category, null, ['class'=> 'form-control', 'id' => 'category']) }}
                </div>


                <label class="col-md-2 control-label" for="inputDefault">Qty</label>
                <div class="col-md-4">
                    {!! Form::text('', null, ['id' => 'qty', 'class' => 'number form-control']) !!}
                </div>

            </div>
            <div class="">
                <div class="form-group {{ $errors->has('detail') ? 'has-error' : ''}}">

                    <label class="col-md-2 control-label" for="inputDefault">Product</label>
                    <div class="col-md-4 {{ $errors->has('product') ? 'has-error' : ''}}">
                        {{ Form::select('', $product, null, ['class'=> 'form-control', 'id' => 'product']) }}
                    </div>


                    {!! Form::label('name', __('Notes'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
                    <div class="col-md-4 col-sm-4">
                        {!! Form::textarea('', null, ['class' => 'form-control', 'rows' => '3', 'readonly', 'id' =>
                        'desc']) !!}
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

@include($folder.'::page.'.$template.'.table')