<div id="detail" class="panel-body">
    <div class="panel panel-default">
        <div class="panel-body line">
            <div class="">
                <div class="form-group {{ $errors->has('detail') ? 'has-error' : ''}}">
                    <label class="col-md-2 control-label" for="inputDefault">Product</label>
                    <div class="col-md-4 {{ $errors->has('product') ? 'has-error' : ''}}">
                        {{ Form::select('', $product, null, ['class'=> 'form-control', 'id' => 'product']) }}
                    </div>

                    <label class="col-md-2 control-label" for="inputDefault">Qty</label>
                    <div class="col-md-2">
                        {!! Form::text('', null, ['id' => 'qty', 'class' => 'number form-control']) !!}
                    </div>

                    <div class="col-md-2">
                        <span id="add" style="margin-top:0px" class="btn btn-primary detail">Add Detail</span>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

@include($folder.'::page.'.$template.'.table')