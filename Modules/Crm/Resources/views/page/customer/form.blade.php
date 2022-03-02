@php
$city = $area = [];

$province_data = old('customer_province') ?? null;
if($province_data){

$city = DB::table('rajaongkir_cities')
->where('rajaongkir_city_province_id', $province_data)
->get()->pluck('rajaongkir_city_name', 'rajaongkir_city_id')->prepend('- Select City -', '');
}

$city_data = old('customer_city') ?? null;
if($city_data){

$area = DB::table('rajaongkir_areas')->where('rajaongkir_area_city_id', $city_data)
->pluck('rajaongkir_area_name','rajaongkir_area_id')->prepend('- Select Area -', '');
}
@endphp

<div class="form-group">

    {!! Form::label('name', __('Name'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('customer_name') ? 'has-error' : ''}}">
        {!! Form::text('customer_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('customer_name', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Contact Person'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('customer_contact') ? 'has-error' : ''}}">
        {!! Form::text('customer_contact', null, ['class' => 'form-control']) !!}
        {!! $errors->first('customer_contact', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">

    {!! Form::label('name', __('Email'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('customer_email') ? 'has-error' : ''}}">
        {!! Form::text('customer_email', null, ['class' => 'form-control']) !!}
        {!! $errors->first('customer_email', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Phone'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('customer_phone') ? 'has-error' : ''}}">
        {!! Form::text('customer_phone', null, ['class' => 'form-control']) !!}
        {!! $errors->first('customer_phone', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">

    {!! Form::label('name', __('Owner'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('customer_owner') ? 'has-error' : ''}}">
        {!! Form::text('customer_owner', null, ['class' => 'form-control']) !!}
        {!! $errors->first('customer_owner', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Description'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('customer_description') ? 'has-error' : ''}}">
        {!! Form::textarea('customer_description', null, ['class' => 'form-control', 'rows' => '3']) !!}
        {!! $errors->first('customer_description', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<hr>

<div class="form-group">
    {!! Form::label('name', __('Province'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('customer_province') ? 'has-error' : ''}}">
        {{ Form::select('customer_province', $province, null, ['class'=> 'form-control ', 'id' => 'province']) }}
        {!! $errors->first('customer_province', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('City'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('customer_city') ? 'has-error' : ''}}">
        {{ Form::select('customer_city', $city, null, ['class'=> 'form-control ', 'id' => 'city']) }}
        {!! $errors->first('customer_city', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('name', __('Area'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('customer_area') ? 'has-error' : ''}}">
        {{ Form::select('customer_area', $area, old('customer_area') ?? null, ['class'=> 'form-control ', 'id' => 'area']) }}
        {!! $errors->first('customer_area', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('RT / RW'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-2 col-sm-2 {{ $errors->has('customer_rt') ? 'has-error' : ''}}">
        {!! Form::text('customer_rt', null, ['class' => 'form-control']) !!}
        {!! $errors->first('customer_rt', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-2 col-sm-2 {{ $errors->has('customer_rw') ? 'has-error' : ''}}">
        {!! Form::text('customer_rw', null, ['class' => 'form-control']) !!}
        {!! $errors->first('customer_rw', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">

    {!! Form::label('name', __('Address'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-10 col-sm-10 {{ $errors->has('customer_address') ? 'has-error' : ''}}">
        {!! Form::textarea('customer_address', null, ['class' => 'form-control', 'rows' => '3']) !!}
        {!! $errors->first('customer_address', '<p class="help-block">:message</p>') !!}
    </div>

</div>


<!--
<div class="form-group">

    {!! Form::label('name', __('Bank Name'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('customer_bank_name') ? 'has-error' : ''}}">
        {!! Form::text('customer_bank_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('customer_bank_name', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Bank Account'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('customer_bank_account') ? 'has-error' : ''}}">
        {!! Form::text('customer_bank_account', null, ['class' => 'form-control']) !!}
        {!! $errors->first('customer_bank_account', '<p class="help-block">:message</p>') !!}
    </div>
</div>
-->

<div class="form-group">



</div>

<hr>

<div class="form-group">

    {!! Form::label('name', __('Type PPN'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-2 col-sm-2 {{ $errors->has('customer_ppn') ? 'has-error' : ''}}">
        {{ Form::select('customer_ppn', $check, null, ['class'=> 'form-control ', 'id' => 'check']) }}
        {!! $errors->first('customer_ppn', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('NPWP'), ['class' => 'col-md-1 col-sm-1 control-label']) !!}
    <div class="col-md-3 col-sm-3 {{ $errors->has('customer_npwp') ? 'has-error' : ''}}">
        {!! Form::text('customer_npwp', null, ['class' => 'form-control', 'id' => 'customer_npwp', old('customer_ppn') == 1 || (isset($model) && $model->customer_ppn == 1) ? '' : 'readonly']) !!}
        {!! $errors->first('customer_npwp', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('PKP'), ['class' => 'col-md-1 col-sm-1 control-label']) !!}
    <div class="col-md-3 col-sm-3 {{ $errors->has('customer_pkp') ? 'has-error' : ''}}">
        {!! Form::text('customer_pkp', null, ['class' => 'form-control', 'id' => 'customer_pkp', old('customer_ppn') == 1 || (isset($model) && $model->customer_ppn == 1) ? '' : 'readonly']) !!}
        {!! $errors->first('customer_pkp', '<p class="help-block">:message</p>') !!}
    </div>
</div>

@push('javascript')
<script>
    $(document).ready(function() {

        $('#check').change(function(e) {
            var id = $("#check option:selected").val();

            if (id == "1") {
                $("#customer_npwp").attr('readonly', false);
                $("#customer_pkp").attr('readonly', false);
            } else {
                $("#customer_npwp").val('').attr('readonly', true);
                $("#customer_pkp").val('').attr('readonly', true);
            }
        });

        $('#province').change(function(e) {
            var id = $("#province option:selected").val();
            $.ajax({
                url: '{{ route("city_api") }}',
                method: 'POST',
                data: {
                    id: id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(result) {

                    $("#city").empty();
                    $('#city').append('<option value="">- Select City -</option>');
                    $.each(result, function(i, item) {
                        $('#city').append('<option value="' + item.rajaongkir_city_id + '">' + item.rajaongkir_city_type + ' - ' + item.rajaongkir_city_name + '</option>');
                    });
                    $("#city").trigger("chosen:updated");
                }
            });
        });

        $('#city').change(function(e) {
            var id = $("#city option:selected").val();
            $.ajax({
                url: '{{ route("area_api") }}',
                method: 'POST',
                data: {
                    id: id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(result) {

                    $("#area").empty();
                    $('#area').append('<option value="">- Select Area -</option>');
                    $.each(result, function(i, item) {
                        $('#area').append('<option value="' + item.rajaongkir_area_id + '">' + item.rajaongkir_area_type + ' - ' + item.rajaongkir_area_name + '</option>');
                    });
                    $("#area").trigger("chosen:updated");
                }
            });
        });

    });
</script>
@endpush