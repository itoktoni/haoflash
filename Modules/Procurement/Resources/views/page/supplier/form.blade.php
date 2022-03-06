<div class="form-group">

    {!! Form::label('name', __('Name'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('supplier_name') ? 'has-error' : ''}}">
        {!! Form::text('supplier_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('supplier_name', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Contact Person'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('supplier_contact') ? 'has-error' : ''}}">
        {!! Form::text('supplier_contact', null, ['class' => 'form-control']) !!}
        {!! $errors->first('supplier_contact', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">

    {!! Form::label('name', __('Email'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('supplier_email') ? 'has-error' : ''}}">
        {!! Form::text('supplier_email', null, ['class' => 'form-control']) !!}
        {!! $errors->first('supplier_email', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Phone'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('supplier_phone') ? 'has-error' : ''}}">
        {!! Form::text('supplier_phone', null, ['class' => 'form-control']) !!}
        {!! $errors->first('supplier_phone', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<!--
<div class="form-group">

    {!! Form::label('name', __('Bank Name'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('supplier_bank_name') ? 'has-error' : ''}}">
        {!! Form::text('supplier_bank_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('supplier_bank_name', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Bank Account'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('supplier_bank_account') ? 'has-error' : ''}}">
        {!! Form::text('supplier_bank_account', null, ['class' => 'form-control']) !!}
        {!! $errors->first('supplier_bank_account', '<p class="help-block">:message</p>') !!}
    </div>
</div>
-->

<div class="form-group">

    {!! Form::label('name', __('Description'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('supplier_description') ? 'has-error' : ''}}">
        {!! Form::textarea('supplier_description', null, ['class' => 'form-control', 'rows' => '3']) !!}
        {!! $errors->first('supplier_description', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Address'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('supplier_address') ? 'has-error' : ''}}">
        {!! Form::textarea('supplier_address', null, ['class' => 'form-control', 'rows' => '3']) !!}
        {!! $errors->first('supplier_address', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<hr>

<div class="form-group">

    {!! Form::label('name', __('Type PPN'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('supplier_ppn') ? 'has-error' : ''}}">
        {{ Form::select('supplier_ppn', $ppn, null, ['class'=> 'form-control ', 'id' => 'ppn']) }}
        {!! $errors->first('supplier_ppn', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Type PPH'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('supplier_pph') ? 'has-error' : ''}}">
        {{ Form::select('supplier_pph', $pph, null, ['class'=> 'form-control ', 'id' => 'pph']) }}
        {!! $errors->first('supplier_pph', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">

    {!! Form::label('name', __('NPWP'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('supplier_npwp') ? 'has-error' : ''}}">
        {!! Form::text('supplier_npwp', null, ['class' => 'form-control', 'id' => 'supplier_npwp', isset($model) && $model->supplier_ppn == 1 ? '' : 'readonly']) !!}
        {!! $errors->first('supplier_npwp', '<p class="help-block">:message</p>') !!}
    </div>
    {!! Form::label('name', __('PKP'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('supplier_pkp') ? 'has-error' : ''}}">
        {!! Form::text('supplier_pkp', null, ['class' => 'form-control', 'id' => 'supplier_pkp', isset($model) && $model->supplier_ppn == 1 ? '' : 'readonly']) !!}
        {!! $errors->first('supplier_pkp', '<p class="help-block">:message</p>') !!}
    </div>
</div>

@push('javascript')
<script>
    $(document).ready(function() {

        $('#ppn').change(function(e) {
            var id = $("#ppn option:selected").val();

            if (id == "1") {
                $("#supplier_npwp").attr('readonly', false);
                $("#supplier_pkp").attr('readonly', false);
            } else {
                $("#supplier_npwp").val('').attr('readonly', true);
                $("#supplier_pkp").val('').attr('readonly', true);
            }
        });

    });
</script>
@endpush