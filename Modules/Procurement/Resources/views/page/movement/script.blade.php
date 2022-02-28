<x-date :array="['date']"/>
<x-mask :array="['number', 'money']"/>

@push('javascript')
namespace Modules\Sales\Resources\views\page\order;
<script>
$(document).ready(function() {

    function calculate(){

        var qty = $('#qty').val();
        var price = $('#price').val();
        var disc = $('#disc').val();
        var total = numeral(qty).value() * numeral(price).value();

        if(disc != ''){
            total = total - (total * numeral(disc).value() / 100);
        }

        var mask_total = numeral(total).format('0,0');
        var sub_total = $('#sub_total').val(mask_total);
    }

    function sumTotal() {

        var sum = 0;
        $('.temp_total').each(function() {
            sum += numeral($(this).val()).value();
        });
        var total_name = $('#total_name');
        var total_value = $('#total_value');
        var total_input = $('#hidden_total');
        var total_product = $('#total_product');
        var total_payment = $('#total_payment');
        var before_discount = $('#before_discount');

        var disc = $('#grand_discount_value');
        var price = sum * numeral(disc.val()).value() / 100;
        var mask_price_discount = numeral(price).format('0,0');
        
        var total_discount = sum - price;
        var mask_total_discount = numeral(total_discount).format('0,0');
        sum = total_discount;
        $('#grand_total').val(mask_total_discount);

        var grand_tax_id = $("#grand_tax_id option:selected").val();
        var grand_tax_name = $("#grand_tax_id option:selected").text();
        var tax_value = $('#grand_tax_value');

        if (tax_value.val() != '') {

            var price_tax = sum * numeral(tax_value.val()).value() / 100;
            var mask_price_tax = numeral(price_tax).format('0,0');
            
            var total_tax = sum + price_tax;
            var mask_total_tax = numeral(total_tax).format('0,0');

            sum = total_tax;
        }

        total_name.text('Total');
        total_input.val(sum);
        total_value.text(numeral(sum).format('0,0'));
        total_product.val(numeral(sum).format('0,0'));
        total_payment.text(numeral(sum).format('0,0'));
        before_discount.val(numeral(sum + price).format('0,0'));
        
        $('#grand_discount_price').val(mask_price_discount);
        $('#grand_discount_total').val(mask_total_discount);
        
        $('#grand_tax_price').val(mask_price_tax);
        $('#grand_tax_total').val(mask_total_tax);
        
        var tax = numeral(tax_value).value($('#grand_tax_total').val());
        $('#grand_total').val(numeral(sum + tax).format('0,0'));
    }

    function addDetail(e) {
        var input_product = $('#product option:selected');
        var input_qty = $('#qty');
        var input_price = $('#price');
        var input_disc = $('#disc');
        var input_desc = $('#desc');
        var input_notes = $('#notes');
        var input_sub_total = $('#sub_total');

        if (input_product.val() == '') {
            new PNotify({
                title: 'Error Select Product',
                text: 'You must select Product',
                addclass: 'notification-danger',
                icon: 'fa fa-bolt'
            });

            return false;
        }

        var product_id = input_product.val();
        var product_name = input_product.text().trim();
        var product_qty = input_qty.val();
        var product_price = input_price.val();
        var product_notes = input_notes.val();
        var product_disc = input_disc.val();
        var product_desc = input_desc.val();

        var product_total = numeral(product_qty).value() * numeral(product_price).value();

        var product_potongan = 0;
        if(product_disc != ''){
            product_potongan = (product_total * numeral(product_disc).value() / 100);
            product_total = product_total - product_potongan;
        }

        var mask_total = numeral(product_total).format('0,0');
        var real_price = numeral(product_price).value();

        var counter = $(".temp_id").length;

        if (product_id) {

            var product_price = numeral(product_price).value();
            if (product_name) {

                var ep = document.getElementsByName('temp_id[]');
                for (i = 0; i < ep.length; i++) {
                    if (ep[i].value.trim() == product_id.trim()) {

                        new PNotify({
                            title: 'Product Already Exist',
                            text: 'Product ' + product_name.trim() + ' , Already in Table ',
                            addclass: 'notification-danger',
                            icon: 'fa fa-bolt'
                        });

                        return;
                    }
                }
                var total = numeral(product_price).value() * numeral(product_qty).value();
                var markup = "<tr>"+
                    "<td data-title='ID' class='col-lg-1'><button id='delete' value='" + product_id + "' type='button' class='btn btn-danger btn-xs btn-block'>"+product_id+"</button></td>"+
                    "<td data-title='Product'>"+
                    "<input class='form-control input-sm text-left' readonly name='detail[" + counter + "][temp_product]' value='" + product_name + "'>"+
                    "</td>"+
                    "<td data-title='Qty' class='text-right col-lg-1'>"+
                    "<input tabindex='"+counter+"1' class='form-control input-sm text-right number temp_qty' name='detail[" + counter + "][temp_qty]' value='" + product_qty +"'></td>"+
                    "<input type='hidden' value='" + product_id +"' name='detail[" + counter + "][temp_id]'><input type='hidden' class='temp_id' value='"+product_id+"' name='temp_id[]'>"+
                    "'"+
                    "</tr>";
                $("#transaction .markup").append(markup);
                sumTotal();
                $('#price').val("");
                $('#qty').val("");
                $('#disc').val("");
                $('#sub_total').val("");
                $('#notes').val("");

                $('#price').attr("placeholder", "").blur();
                $('#qty').attr("placeholder", "").blur();

                return false;
            } else {

                new PNotify({
                    title: 'Choose Product',
                    text: 'Please Select Product !',
                    addclass: 'notification-danger',
                    icon: 'fa fa-bolt'
                });
            }
        } else {
            new PNotify({
                title: 'Price and Quantity',
                text: 'Please Input Price & Quantity !',
                addclass: 'notification-danger',
                icon: 'fa fa-bolt'
            });
        }
    }

    $('#product').change(function(e) {
        var id = $("#product option:selected").val();
        $.ajax({
            url: '{{ route("product_api") }}',
            method: 'POST',
            data: {
                id: id,
                "_token": "{{ csrf_token() }}"
            },
            success: function(result) {
                if (result) {
                    var mask_price = number_format(result.item_product_sell.toString());
                    var price = $('#price').val(mask_price);
                    var sub_total = $('#sub_total').val(mask_price);
                    setTimeout(function() {
                        $('#qty').val(1);
                        $('#qty').focus();
                    });
                }
            }
        });
    });

  
    $("#detail").on('input', '#qty', function(){
        calculate();
    });

    $("#detail").on('input', '#price', function(){
        calculate();
    });

    $("#detail").on('input', '#disc', function(){
        calculate();
    });

    $("#transaction").on('input', '.temp_qty', function() {
        var qty = $(this).val();
        var price = $(this).closest('tr').find('.temp_price');
        var total = $(this).closest('tr').find('.temp_total');

        var value_total = numeral(qty).value() * numeral(price.val()).value();
        var potongan = $(this).closest('tr').find('.temp_potongan');
        var disc = $(this).closest('tr').find('.temp_disc');
        if(disc != ''){
            var discount = (value_total * numeral(disc.val()).value()) / 100;
            potongan.val(numeral(discount).format('0,0'));
            value_total = value_total - discount;
        }
        
        total.val(numeral(value_total).format('0,0'));
        sumTotal();
    });

    $("#transaction").on('input', '.temp_price', function() {
        var price = $(this).val();
        var qty = $(this).closest('tr').find('.temp_qty');
        var total = $(this).closest('tr').find('.temp_total');

        var value_total = numeral(qty.val()).value() * numeral(price).value();
        
        var potongan = $(this).closest('tr').find('.temp_potongan');
        var disc = $(this).closest('tr').find('.temp_disc');
        if(disc != ''){
            var discount = value_total * numeral(disc.val()).value() / 100;
            potongan.val(numeral(discount).format('0,0'));
            value_total = value_total - discount;
        }

        total.val(numeral(value_total).format('0,0'));
        sumTotal();
    });

    $("#transaction").on('input', '.temp_disc', function() {
        var price = $(this).closest('tr').find('.temp_price');
        var qty = $(this).closest('tr').find('.temp_qty');
        var total = $(this).closest('tr').find('.temp_total');
        
        var value_total = numeral(qty.val()).value() * numeral(price.val()).value();
        
        var potongan = $(this).closest('tr').find('.temp_potongan');
        var disc = $(this).val();
        if(disc != ''){
            var discount = value_total * numeral(disc).value() / 100;
            potongan.val(numeral(discount).format('0,0'));
            value_total = value_total - discount;
        }

        total.val(numeral(value_total).format('0,0'));
        sumTotal();
    });

    $("#transaction").on('input', '#grand_discount_value', function() {
        sumTotal();
    });

    $("#transaction").on('input', '#grand_tax_value', function() {
        sumTotal();
    });

    $('#qty').keypress(function(e) {
        if (e.which == '13') {
            addDetail();
            e.preventDefault();
        }
    });

    $('#sub_total').keypress(function(e) {
        if (e.which == '13') {
            addDetail();
            e.preventDefault();
        }
    });

    $('#transaction').arrowTable();

    $('#price').keypress(function(e) {
        if (e.which == '13') {
            addDetail();
            e.preventDefault();
        }
    });

    $(document).on('click', '#delete', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var id = $(this).attr('value');
        $.alertable.confirm('Are You sure to delete ?').then(function(e) {
            if (typeof url !== typeof undefined && url !== false) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: url,
                    method: 'POST',
                    success: function() {
                        $('.delete-' + id).closest("tr").remove();
                        sumTotal();
                    }
                });
            } else {
                $('button[value="' + id + '"]').parents("tr").remove();
                sumTotal();
            }
            $("#product").val('');
            $("#product").trigger("chosen:updated");
        }, function(x) {
            console.log('Confirmation canceled');
        });
    });

    $("#add").click(function(e) {
        addDetail(e);
        e.preventDefault();
    });

});
</script>
@endpush