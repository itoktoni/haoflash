<x-mask :array="['number', 'money']" />

<div class="form-group">

    {!! Form::label('name', __('Category'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('product_category_id') ? 'has-error' : ''}}">
        {{ Form::select('product_category_id', $category, null, ['class'=> 'form-control', 'id' => 'category']) }}
        {!! $errors->first('product_category_id', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Name'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('product_name') ? 'has-error' : ''}}">
        {!! Form::text('product_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('product_name', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">

    {!! Form::label('name', __('Minimum'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('product_min') ? 'has-error' : ''}}">
        {!! Form::number('product_min', null, ['class' => 'form-control']) !!}
        {!! $errors->first('product_min', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Price'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('product_buy') ? 'has-error' : ''}}">
        {!! Form::text('product_buy', null, ['class' => 'form-control number']) !!}
        {!! $errors->first('product_buy', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">

    {!! Form::label('name', __('Kode Barang'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('product_sku') ? 'has-error' : ''}}">
        {!! Form::text('product_sku', null, ['class' => 'form-control','id' => 'part', Query::getCategoryType(old('product_category_id') ?? (isset($mode) ? $model->product_category_id : null)) ? '' : 'readonly' ]) !!}
        {!! $errors->first('product_sku', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Description'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('product_description') ? 'has-error' : ''}}">
        {!! Form::textarea('product_description', null, ['class' => 'form-control simple', 'rows' => '5']) !!}
        {!! $errors->first('product_description', '<p class="help-block">:message</p>') !!}
    </div>

</div>


@push('javascript')
<script>
    $(document).ready(function() {

        $('#category').change(function(e) {
            var id = $("#category option:selected").val();
            if (id) {
                $.ajax({
                    url: '{{ route("get_category_api") }}',
                    method: 'POST',
                    data: {
                        id: id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(result) {
                        if (result) {
                            console.log(result);
                            if (result.category_type == 3) {
                                setTimeout(function() {
                                    $('#part').attr('readonly', false);
                                });
                            } else {
                                setTimeout(function() {
                                    $('#part').val('');
                                    $('#part').attr('readonly', true);
                                });
                            }
                        }
                    }
                });
            }

        });

    });
</script>
@endpush