// =========================================================================
// Global Function
// =========================================================================
$.fn.digits = function(){
    return this.each(function() {
        $(this).val( $(this).val().replace(/\D/g,".").replace(/\B(?=(\d{3})+(?!\d))/g, "") );
    });
};

// ============================================================
// Form Validation Ganerate and Order PIN
// ============================================================
var FV_Profile = function () {   
    
    var handleValidationPersonal = function() {
        var form_personal   = $('#personal');
        var wrapper         = $('.content');

        form_personal.validate({
            errorElement: 'div',       // default input error message container
            errorClass: 'invalid-feedback',   // default input error message class
            focusInvalid: false,        // do not focus the last invalid input
            ignore: "",
            rules: {
                member_username: {
                    minlength: 5,
                    required: true,
                    unamecheck: true,
                },
                member_name: {
                    minlength: 3,
                    required: true,
                    lettersonly: true,
                },
                member_email: {
                    required: true,
                },
                member_phone: {
                    required: true,
                },
                // member_pob: {
                //     required: true,
                // },
                // member_dob_date: {
                //     required: true,
                // },
                // member_dob_month: {
                //     required: true,
                // },
                // member_dob_year: {
                //     required: true,
                // },
                // member_gender: {
                //     required: true,
                // },
                // member_marital: {
                //     required: true,
                // },
                member_idcard_type: {
                    required: true,
                },
                member_province_idcard: {
                    required: true,
                },
                member_district_idcard: {
                    required: true,
                },
                member_subdistrict_idcard: {
                    required: true,
                },
                member_village_idcard: {
                    required: true,
                },
                member_address_idcard: {
                    required: true,
                },
                member_province: {
                    required: true,
                },
                member_district: {
                    required: true,
                },
                member_subdistrict: {
                    required: true,
                },
                member_village: {
                    required: true,
                },
                member_address: {
                    required: true,
                },
                member_postcode: {
                    required: true,
                },
                member_bank: {
                    required: true,
                },
                member_bill: {
                    required: true,
                },
                member_bill_name: {
                    required: true,
                },
                member_bank_branch: {
                    required: true,
                },
                member_city_code: {
                    required: true,
                },
                member_emergency_name: {
                    required: true,
                },
                member_emergency_relationship: {
                    required: true,
                },
                member_emergency_phone: {
                    required: true,
                },
            },
            invalidHandler: function (event, validator) { //display error alert on form submit     
                App.alert({
                    type: 'danger', 
                    icon: 'warning', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: form_personal, 
                    closeInSeconds: 5,
                    place: 'prepend'
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                $('#save_profile').modal('show');
            }
        });
        
        $.validator.addMethod("unamecheck", function(value) {
            return /^[A-Za-z0-9]{4,16}$/i.test(value);   // consists of only these
        }, "Username tidak memenuhi kriteria" );
        
        $.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
        }, "Silahkan inputkan Nama dengan huruf saja" );
    };
    
    var handleValidationCPassword = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation
        var form_cpass  = $('#cpassword');
        form_cpass.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                cur_pass: {
                    required: true
                },
                new_pass: {
                    minlength: 6,
                    required: true,
                    pwcheck: true,
                },
                cnew_pass: {
                    minlength: 6,
                    required: true,
                    equalTo : "#new_pass"
                },
            },
            messages: {
                cur_pass: {
                    minlength: "Minimal harus 6 karakter",
                    required: "Password lama harus di isi"
                },
                new_pass: {
                    minlength: "Minimal harus 6 karakter",
                    required: "Password baru harus di isi"
                },
                cnew_pass: {
                    minlength: "Minimal harus 6 karakter",
                    required: "Konfirmasi Password harus di isi",
                    equalTo: "Konfirmasi password tidak sesuai dengan password yang diinputkan"
                },
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'warning', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: form_cpass, 
                    closeInSeconds: 5,
                    place: 'prepend'
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                $('#save_cpassword').modal('show');
            }
        });
        
        $.validator.addMethod("pwcheck", function(value) {
            return /[a-z].*[0-9]|[0-9].*[a-z]/i.test(value); // consists of only these
        }, "Password harus terdiri dari huruf dan angka" );
    };
    
    return {
        //main function to initiate the module
        init: function () {
            handleValidationPersonal();
            handleValidationCPassword();
        },
    };
}();

// ============================================================
// Form Validation pin Generate
// ============================================================
var FV_AsStockist = function () {    
    // ---------------------------------
    // Handle Validation Generate pin
    // ---------------------------------
    var handleValidationAsStokist = function() {
        var form            = $('#form-stockist');
        var wrapper         = $('.wrapper-form-stockist');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                asmember: {
                    required: true
                },
                stockist_status: {
                    required: true
                },
                stockist_province: {
                    required: true
                },
                stockist_district: {
                    required: true
                },
                stockist_subdistrict: {
                    required: true
                },
                stockist_village: {
                    required: true
                },
                stockist_address: {
                    required: true
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length > 0) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length > 0) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length > 0) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'bell', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper, 
                    place: 'prepend'
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                var asMember    = $('input[name="asmember"]', $(form)).val();
                var tStockist   = $('select[name="stockist_status"] option:selected').text();
                var msg         = 'Anda yakin akan merubah status anggota ini menjadi '+tStockist+' ? ';
                var url         = $(form).attr('action') +'/'+ asMember;
                bootbox.confirm(msg, function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                response = $.parseJSON(response);
                                App.close_Loader();

                                if ( response.token ) {
                                    App.kdToken(response.token);
                                }
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        var _type = 'success';
                                        var _icon = 'fa fa-check-circle';
                                        $(form)[0].reset();
                                        $('#modal_select_stockist').modal('hide');
                                        $('#btn_list_table_member').trigger('click');
                                    }else{
                                        var _type = 'warning';
                                        var _icon = 'fa fa-exclamation-circle';
                                    }
                                    App.notify({
                                        icon: _icon, 
                                        message: response.message, 
                                        type: _type,
                                    });
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                App.notify({
                                    icon: 'fa fa-exclamation-circle', 
                                    message: 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', 
                                    type: 'warning',
                                });
                            }
                        });
                    }
                });
                return false;
            }
        });
    };
    
    return {
        //main function to initiate the module
        init: function () {
            handleValidationAsStokist();
        },
    };
}();
// ============================================================
// Form Validation PIN Generate
// ============================================================
var FV_PINGenerate = function () {    
    var _form           = $('#form-pin-generate');
    var _wrapper        = $('.wrapper-form-pin-generate');
    var _trEmpty        = `<tr class="pin_item_empty"><td colspan="6" class="text-center">No data available in table</td></tr>`;

    var subtotal_pin    = 0;
    var total_qty       = 0;
    var total_payment   = 0;
    var total_weight    = 0;
    var shipping_fee    = 0;
    var discount        = 0;
    var discount_code   = '';

    // ---------------------------------
    // Handle Validation Generate PIN
    // ---------------------------------
    var handleValidationPINGenerate = function() {
        var form            = $('#form-pin-generate');
        var wrapper         = $('.wrapper-form-pin-generate');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                username: {
                    required: true
                },
                payment_method: {
                    required: true
                },
                shipping_method: {
                    required: true
                },
                name: {
                    minlength: 3,
                    required: function(element) {
                        if( $('#shipping_method').val() == 'ekspedisi' ){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
                phone: {
                    minlength: 8,
                    required: function(element) {
                        if( $('#shipping_method').val() == 'ekspedisi' ){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
                email: {
                    email: true,
                    required: function(element) {
                        if( $('#shipping_method').val() == 'ekspedisi' ){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
                province: {
                    required: function(element) {
                        if( $('#shipping_method').val() == 'ekspedisi' ){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
                district: {
                    required: function(element) {
                        if( $('#shipping_method').val() == 'ekspedisi' ){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
                subdistrict: {
                    required: function(element) {
                        if( $('#shipping_method').val() == 'ekspedisi' ){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
                village: {
                    required: function(element) {
                        if( $('#shipping_method').val() == 'ekspedisi' ){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
                address: {
                    required: function(element) {
                        if( $('#shipping_method').val() == 'ekspedisi' ){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length > 0) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length > 0) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length > 0) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'bell', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper, 
                    closeInSeconds: 5,
                    place: 'prepend'
                });
                App.scrollTo(wrapper, -100);
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url             = $(form).attr('action');

                if ( total_qty == "" || total_qty == 0 || total_qty == undefined ) {
                    App.alert({
                        type: 'danger', 
                        icon: 'warning', 
                        message: 'Jumlah Produk belum di isi. Silakan isi Produk terlebih dahulu !', 
                        container: wrapper, 
                        closeInSeconds: 5,
                        place: 'prepend'
                    });
                    App.scrollTo(wrapper, -100);
                    return false;
                }

                bootbox.confirm("Apakah anda yakin akan Ganerate Tiket ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                response = $.parseJSON(response);
                                App.close_Loader();

                                if ( response.token ) {
                                    App.kdToken(response.token);
                                }
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        var _type   = 'success';
                                        var _icon   = 'fa fa-check-circle';
                                        var _table  = $('#list_table_product_pin_generate');
                                        var _tbody  = $('tbody', _table);

                                        $(form)[0].reset();
                                        $('#member_info').empty();
                                        $('.info_shipping_method').hide();
                                        _tbody.empty().append(_trEmpty);
                                        calculateTotalPayment();
                                    }else{
                                        var _type   = 'warning';
                                        var _icon   = 'fa fa-exclamation-circle';
                                    }
                                    App.notify({
                                        icon: _icon, 
                                        message: response.message, 
                                        type: _type,
                                    });
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                App.notify({
                                    icon: 'fa fa-exclamation-circle', 
                                    message: 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', 
                                    type: 'warning',
                                });
                            }
                        });
                    }
                });
                return false;
            }
        });
    };

    // ---------------------------------
    // Handle General
    // ---------------------------------
    var handleGeneralPINGenerate = function() {
        // Button Add Product Promo Data 
        // -----------------------------------------------
        $("body").delegate( "#select_product", "change", function( e ) {
            e.preventDefault();
            $('#btn-add-pin-item').trigger('click');
        });

        // Button Add Product Promo Data 
        // -----------------------------------------------
        $("body").delegate( "#btn-add-pin-item", "click", function( e ) {
            e.preventDefault();
            var product     = $('#select_product', _form).val();
            if ( product ) {
                addProductPIN();
            } else {
                bootbox.alert('Produk belum di pilih !');
                $('#select_product', _form).val('');
            }
            return false;
        });

        // Button Add Product Promo Data 
        // -----------------------------------------------
        $("body").delegate( ".btn-remove-pin-item", "click", function( e ) {
            e.preventDefault();
            var rowid    = $(this).data('id');
            removeProductPIN(rowid);
            calculateTotalPayment();
            return false;
        });

        // Change Minus Qty Product 
        // -----------------------------------------------
        $("body").delegate( ".btn-pin-minus-qty", "click", function( e ) {
            e.preventDefault();
            var step    = $(this).data('step');
            var count   = $(this).closest(".pin-quantity").find('.pin-item-qty').val();
            var countEl = $(this).closest(".pin-quantity").find('.pin-item-qty');

            if ( parseInt(count) >= 0 ) {
                count = parseInt(count) - parseInt(step);
                countEl.val(count).change();
            }
        });

        // Change Plus Qty Product 
        // -----------------------------------------------
        $("body").delegate( ".btn-pin-plus-qty", "click", function( e ) {
            e.preventDefault();
            var step    = $(this).data('step');
            var count   = $(this).closest(".pin-quantity").find('.pin-item-qty').val();
            var countEl = $(this).closest(".pin-quantity").find('.pin-item-qty');

            count = parseInt(count) + parseInt(step);
            countEl.val(count).change();
        });

        // Change Qty Product 
        // -----------------------------------------------
        $("body").delegate( ".pin-item-qty", "change", function( e ) {
            e.preventDefault();
            var rowid       = $(this).data('rowid');
            var qty         = $(this).val();
            if ( qty == '' ) {
                qty = 0;
            }

            if ( parseInt(qty) == 0 ) {
                removeProductPIN(rowid);
            }
            
            calculateTotalPayment();
            return false;
        });

        // Change Price Product 
        // -----------------------------------------------
        $("body").delegate( ".pin-item-price", "blur", function( e ) {
            e.preventDefault();            
            calculateTotalPayment();
            return false;
        });

        // Change Shipping Method
        // -----------------------------------------------
        $("body").delegate( "#shipping_method", "change", function( e ) {
            e.preventDefault();
            var val = $(this).val();
            if ( val == 'ekspedisi' ) {
                $('.info_shipping_method').show();
            } else {
                $('.info_shipping_method').hide();
            }
            return false;
        });
    };

    // ---------------------------------
    // Add Product PIN
    // ---------------------------------
    var addProductPIN = function() {
        var _table          = $('#list_table_product_pin_generate');
        var _tbody          = $('tbody', _table);
        var _tr             = $('tr', _tbody);
        var _count_data     = _tr.length;
        var _empty_row      = _tbody.find('tr.pin_item_empty');
        var id_product      = $('#select_product', _form).val();
        var t_product       = $('select[name="select_product"] option:selected').text();
        var price_product   = $('#select_product', _form).children("option:selected").data('price');
        var weight_product  = $('#select_product', _form).children("option:selected").data('weight');
        var img_product     = $('#select_product', _form).children("option:selected").data('image');

        if ( id_product == '' || id_product == 0 || id_product == undefined ) {
            bootbox.alert('Produk belum di pilih !');
            $('#select_product', _form).val('');
            return false;
        }

        if( $('[data-id="'+id_product+'"]', _tbody).length ) {
            bootbox.alert('Produk ini sudah ada ');
            return false;
        }

        if ( _empty_row.length ) {
            _empty_row.remove();
        }

        var qty_product = 1;
        var subtotal    = parseInt(qty_product) * parseInt(price_product);

        var _append_row = `
            <tr class="pin_item" data-id="${id_product}" data-weight="${weight_product}">
                <td class="text-center">*</td>
                <th scope="row">
                    <div class="media align-items-center" style="white-space: normal">
                        <a href="#" class="avatar mr-3">
                            <img alt="Image placeholder" src="${img_product}">
                        </a>
                        <div class="media-body">
                            <span class="name mb-0 text-sm">${t_product}</span>
                        </div>
                    </div>
                </th>
                <td class="budget">
                    <input class="form-control form-control-sm text-right numbercurrency pin-item-price pin-price-${id_product}" 
                        type="text" data-rowid="${id_product}" name="products[${id_product}][price]" value="${price_product}" title="Price" />
                </td>
                <td class="text-center">
                    <div class="input-group pin-quantity">
                        <div class="input-group-prepend">
                            <button class="btn btn-sm btn-outline-default btn-pin-minus-qty" type="button" data-step="1">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input class="form-control form-control-sm text-center numbermask pin-item-qty pin-qty-${id_product}" 
                            type="text" step="1" data-rowid="${id_product}"
                            name="products[${id_product}][qty]"
                            value="${qty_product}"
                            title="Qty" pattern="[0-9]*" inputmode="numeric" 
                            style="background-color: transparent !important; border-color: #172b4d; border-left: none;" />
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-outline-default btn-pin-plus-qty" type="button" data-step="1">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </td>
                <td class="budget text-right">
                    <span class="heading font-weight-bold pin-item-subtotal pin-subtotal-${id_product}">
                        ${ App.formatCurrency(subtotal) }
                    </span>
                </td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-warning btn-remove-pin-item" type="button" data-id="${id_product}" title="Hapus Produk">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>`;
        _tbody.append(_append_row);
        $('#select_product', _form).val('');
        calculateTotalPayment();
        InputMask.init();
        return false;
    };

    // ---------------------------------
    // Remove Product PIN
    // ---------------------------------
    var removeProductPIN = function(rowid = '') {
        var _table      = $('#list_table_product_pin_generate');
        var _tbody      = $('tbody', _table);
        var _tr         = $('[data-id="'+rowid+'"]', _tbody);
        var _count_data = $('tr', _tbody).length;
        
        if ( rowid ) {
            if( _tr.length ) {
                _tr.remove();
                if ( _count_data == 1 ) {
                    _tbody.append(_trEmpty);
                }
            }
        }
    };

    // ---------------------------------
    // Calculate Total Payment
    // ---------------------------------    
    var calculateTotalPayment = function() {
        var _total_qty          = 0;
        var _total_price        = 0;
        var _total_weight       = 0;
        var _total_weight       = 0;
        var total_discount      = $('#discount').val();
        var el_pin_generate     = $('.pin_item');

        if ( el_pin_generate.length ) {
            el_pin_generate.each(function(index) {
                _idx            = $(this).data('id');
                weight          = $(this).data('weight');
                qty             = $('.pin-qty-'+_idx).val();
                price           = $('.pin-price-'+_idx).val();
                price           = price.replace(/\./g, '');
                
                subtotal        = parseInt(qty) * parseInt(price);
                _total_qty      = parseInt(_total_qty) + parseInt(qty);
                _total_price    = parseInt(_total_price) + parseInt(subtotal);
                _total_weight   = parseInt(_total_weight) + ( parseInt(qty) * parseInt(weight) );

                if ( $('.pin-subtotal-'+_idx).length ) {
                    $('.pin-subtotal-'+_idx).text(App.formatCurrency(subtotal));
                }
            });
        }

        total_qty               = parseInt(_total_qty);
        total_payment           = parseInt(_total_price);
        total_weight            = parseInt(_total_weight);

        if ( $('#discount').length ) {
            total_payment       = parseInt(_total_price) - parseInt(total_discount);
        }

        // $('.total-weight').text('( ' + App.formatCurrency(total_weight, '') + ' gr )');
        $('.pin-total-paymnet').text(App.formatCurrency(total_payment));
    };
    
    return {
        //main function to initiate the module
        init: function () {
            handleValidationPINGenerate();
            handleGeneralPINGenerate();
        },
    };
}();

// ============================================================
// Form Validation PIN Transfer
// ============================================================
var FV_PINTransfer = function () {    
    // ---------------------------------
    // Handle Validation 
    // ---------------------------------
    var handleValidationPINTransfer = function() {
        var form            = $('#form-pin-transfer');
        var wrapper         = $('.wrapper-form-pin-transfer');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                username: {
                    required: true
                },
                pin_qty: {
                    required: true
                },
                password_confirm: {
                    required: true
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length > 0) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length > 0) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length > 0) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'bell', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper, 
                    place: 'prepend'
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url             = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan Transfer Tiket ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                response = $.parseJSON(response);
                                App.close_Loader();

                                if ( response.token ) {
                                    App.kdToken(response.token);
                                }
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        var _type = 'success';
                                        var _icon = 'fa fa-check-circle';
                                        $(form)[0].reset();
                                        $('#member_info').empty();
                                        setTimeout(function(){ location.reload(); }, 700);
                                    }else{
                                        var _type = 'warning';
                                        var _icon = 'fa fa-exclamation-circle';
                                    }
                                    App.notify({
                                        icon: _icon, 
                                        message: response.message, 
                                        type: _type,
                                    });
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                App.notify({
                                    icon: 'fa fa-exclamation-circle', 
                                    message: 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', 
                                    type: 'warning',
                                });
                            }
                        });
                    }
                });
                return false;
            }
        });
    };
    
    return {
        //main function to initiate the module
        init: function () {
            handleValidationPINTransfer();
        },
    };
}();

// ============================================================
// Form Validation Member Loan
// ============================================================
var FV_MemberLoan = function () {    
    // ---------------------------------
    // Handle Validation Deposite 
    // ---------------------------------
    var handleValidationDepositeLoan = function() {
        var form            = $('#form-loan-deposite');
        var wrapper         = $('.wrapper-form-loan-deposite');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                username: {
                    required: true
                },
                amount: {
                    required: true
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'bell', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper,  
                    closeInSeconds: 5,
                    place: 'prepend'
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url             = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan simpan data deposite loan ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                response = $.parseJSON(response);
                                App.close_Loader();

                                if ( response.token ) {
                                    App.kdToken(response.token);
                                }
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        var _type = 'success';
                                        var _icon = 'fa fa-check-circle';
                                        $(form)[0].reset();
                                        if ( $('#modal-form-loan-deposite').length ) {
                                            $('#modal-form-loan-deposite').modal('hide');
                                        } else {
                                            setTimeout(function(){ location.reload(); }, 700);
                                        }
                                    }else{
                                        var _type = 'warning';
                                        var _icon = 'fa fa-exclamation-circle';
                                    }
                                    App.notify({
                                        icon: _icon, 
                                        message: response.message, 
                                        type: _type,
                                    });

                                    if ( $('.filter-submit').length ) {
                                        $('.filter-submit').trigger('click');
                                    }
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                                    location.reload();
                                });
                            }
                        });
                    }
                });
                return false;
            }
        });
    };  

    // ---------------------------------
    // Handle Validation Withdraw 
    // ---------------------------------
    var handleValidationWithdrawLoan = function() {
        var form            = $('#form-loan-withdraw');
        var wrapper         = $('.wrapper-form-loan-withdraw');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                username: {
                    required: true
                },
                amount: {
                    required: true
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'bell', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper,  
                    closeInSeconds: 5,
                    place: 'prepend'
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url             = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan simpan data withdraw loan ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                response = $.parseJSON(response);
                                App.close_Loader();

                                if ( response.token ) {
                                    App.kdToken(response.token);
                                }
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        var _type = 'success';
                                        var _icon = 'fa fa-check-circle';
                                        $(form)[0].reset();
                                        if ( $('#modal-form-loan-withdraw').length ) {
                                            $('#modal-form-loan-withdraw').modal('hide');
                                        } else {
                                            setTimeout(function(){ location.reload(); }, 700);
                                        }
                                    }else{
                                        var _type = 'warning';
                                        var _icon = 'fa fa-exclamation-circle';
                                    }
                                    App.notify({
                                        icon: _icon, 
                                        message: response.message, 
                                        type: _type,
                                    });

                                    if ( $('.filter-submit').length ) {
                                        $('.filter-submit').trigger('click');
                                    }
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                                    location.reload();
                                });
                            }
                        });
                    }
                });
                return false;
            }
        });
    };
    
    return {
        //main function to initiate the module
        init: function () {
            handleValidationDepositeLoan();
            handleValidationWithdrawLoan();
        },
    };
}();

// ============================================================
// Form Validation Withdraw
// ============================================================
var FV_Withdraw = function () {    
    // ---------------------------------
    // Handle Validation Withdraw 
    // ---------------------------------
    var handleValidationWithdraw = function() {
        var form            = $('#form-withdraw');
        var wrapper         = $('.wrapper-form-withdraw');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                nominal: {
                    required: true
                },
                password: {
                    required: true
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'bell', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper, 
                    closeInSeconds: 5,
                    place: 'prepend'
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url             = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan withdraw  ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                response = $.parseJSON(response);
                                App.close_Loader();

                                if ( response.token ) {
                                    App.kdToken(response.token);
                                }
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        if ( $('#modal-form-withdraw').length ) {
                                            $('#modal-form-withdraw').modal('hide');
                                        }
                                        bootbox.alert(response.message, function(){ 
                                            location.reload();
                                        });
                                    }else{
                                        App.alert({
                                            type: 'danger', 
                                            icon: 'bell', 
                                            message: response.message, 
                                            container: wrapper, 
                                            closeInSeconds: 5,
                                            place: 'prepend'
                                        });
                                    }
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                                    location.reload();
                                });
                            }
                        });
                    }
                });
                return false;
            }
        });
    };
    
    return {
        //main function to initiate the module
        init: function () {
            handleValidationWithdraw();
        },
    };
}();

// ============================================================
// Form Validation Flip
// ============================================================
var FV_Flip = function () {    
    // ---------------------------------
    // Handle Validation Flip Topup 
    // ---------------------------------
    var handleValidationFlipTopup = function() {
        var form            = $('#form-flip-topup');
        var el_modal        = $('#modal-form-flip-topup');
        var wrapper         = $('.wrapper-form-flip-topup');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                nominal: {
                    required: true
                },
                password: {
                    required: true
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'bell', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper, 
                    closeInSeconds: 5,
                    place: 'prepend'
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url             = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan Topup Flip ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                response = $.parseJSON(response);
                                App.close_Loader();

                                if ( response.token ) {
                                    App.kdToken(response.token);
                                }
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        if ( el_modal.length ) {
                                            el_modal.modal('hide');
                                        }
                                        bootbox.alert(response.message, function(){ 
                                            location.reload();
                                        });
                                    }else{
                                        App.alert({
                                            type: 'danger', 
                                            icon: 'bell', 
                                            message: response.message, 
                                            container: wrapper, 
                                            closeInSeconds: 5,
                                            place: 'prepend'
                                        });
                                    }
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                                    location.reload();
                                });
                            }
                        });
                    }
                });
                return false;
            }
        });
    };
    
    return {
        //main function to initiate the module
        init: function () {
            handleValidationFlipTopup();
        },
    };
}();

// ============================================================
// Form Validation Post
// ============================================================
var FV_Category = function () {    
    // ---------------------------------
    // Handle Validation Generate PIN
    // ---------------------------------
    var handleValidationCategory = function() {
        var form            = $('#form-category');
        var wrapper         = $('.content');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                cat_category: {
                    minlength: 3,
                    required: true
                }
            },
            messages: {
                cat_category: {
                    minlength: "Minimal 3 karakter",
                    required: "Title harus di isi !",
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length > 0) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length > 0) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length > 0) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'warning', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper, 
                    place: 'prepend'
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url             = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan simpan data kategori ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                response = $.parseJSON(response);
                                App.close_Loader();
                                
                                if( response.status == 'login' ){
                                    $(location).attr('href',response.message);
                                }else{
                                    if( response.status == 'success'){
                                        var _type = 'success';
                                        var _icon = 'check';
                                        $(form)[0].reset();
                                        $('#btn_posts_list').trigger('click');
                                    }else{
                                        var _type = 'danger';
                                        var _icon = 'warning';
                                    }
                                    App.alert({
                                        type: _type, 
                                        icon: _icon, 
                                        message: response.message, 
                                        container: wrapper, 
                                        place: 'prepend',
                                        closeInSeconds: 5,
                                    });
                                }
                            }
                        });
                    }
                });
            }
        });
    };
    
    return {
        //main function to initiate the module
        init: function () {
            handleValidationCategory();
        },
    };
}();

// ============================================================
// Form Validation Staff
// ============================================================
var FV_Staff = function () {    
    // ---------------------------------
    // Handle Validation Staff
    // ---------------------------------
    var handleValidationStaff = function() {
        var form            = $('#form-staff');
        var wrapper         = $('.wrapper-form-staff');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                staff_username: {
                    minlength: 5,
                    required: true,
                    unamecheck: true,
                    remote: {
                        url: $("#staff_username").data('url'),
                        type: "post",
                        data: {
                            [App.kdName()]: function() {
                                return App.kdToken();
                            },
                            username: function() {
                                return $("#staff_username").prop( 'readonly' ) ? '' : $("#staff_username").val();
                            }
                        },
                        dataFilter: function(response) {
                            response = $.parseJSON(response);
                            if ( response.token ) {
                                App.kdToken(response.token);
                            }
                            return response.status;
                        }
                    }
                },
                staff_password: {
                    minlength: 6,
                    required: true,
                    pwcheck: true,
                },
                staff_password_confirm: {
                    required: true,
                    equalTo: '#staff_password'
                },
                staff_name: {
                    minlength: 3,
                    required: true,
                    lettersonly: true,
                },
                staff_phone: {
                    minlength: 8,
                    required: true,
                    remote: {
                        url: $("#staff_phone").data('url'),
                        type: "post",
                        data: {
                            [App.kdName()]: function() {
                                return App.kdToken();
                            },
                            phone: function() {
                                return $("#staff_phone").prop( 'readonly' ) ? '' : $("#staff_phone").val();
                            }
                        },
                        dataFilter: function(response) {
                            response = $.parseJSON(response);
                            if ( response.token ) {
                                App.kdToken(response.token);
                            }
                            return response.status;
                        }
                    }
                },
                staff_email: {
                    email: true,
                    required: true,
                    remote: {
                        url: $("#staff_email").data('url'),
                        type: "post",
                        data: {
                            [App.kdName()]: function() {
                                return App.kdToken();
                            },
                            email: function() {
                                return $("#staff_email").prop( 'readonly' ) ? '' : $("#staff_email").val();
                            }
                        },
                        dataFilter: function(response) {
                            response = $.parseJSON(response);
                            if ( response.token ) {
                                App.kdToken(response.token);
                            }
                            return response.status;
                        }
                    }
                }
            },
            messages: {
                staff_username: {
                    remote: "Username sudah digunakan. Silahkan gunakan username lain",
                },
                staff_password_confirm: {
                    equalTo: "Password konfirmasi tidak cocok dengan password yang di atas",
                },
                staff_email: {
                    remote: "Email sudah digunakan. Silahkan gunakan email lain",
                },
                staff_phone: {
                    remote: "No. Telp/HP sudah digunakan. Silahkan gunakan No. Telp/HP lain",
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length > 0) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length > 0) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length > 0) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'bell', 
                    message: 'Ada beberapa error, silahkan cek formulir di bawah!', 
                    container: wrapper, 
                    place: 'prepend'
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url             = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan simpan data Staff ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                response = $.parseJSON(response);
                                App.close_Loader();

                                if ( response.token ) {
                                    App.kdToken(response.token);
                                }
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        var _type = 'success';
                                        var _icon = 'check';
                                        $(form)[0].reset();
                                        if ( response.url ) {
                                            setTimeout(function(){ $(location).attr('href', response.url); }, 1000);
                                        }
                                    }else{
                                        var _type = 'danger';
                                        var _icon = 'warning';
                                    }
                                    App.alert({
                                        type: _type, 
                                        icon: _icon, 
                                        message: response.message, 
                                        container: wrapper, 
                                        place: 'prepend',
                                        closeInSeconds: 5,
                                    });
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.');
                            }
                        });
                    }
                });
            }
        });

        $.validator.addMethod("pwcheck", function(value) {
            return /[a-z].*[0-9]|[0-9].*[a-z]/i.test(value); // consists of only these
        }, "Password harus terdiri dari huruf dan angka" );
        
        $.validator.addMethod("unamecheck", function(value) {
            return /^[A-Za-z0-9]{4,16}$/i.test(value);   // consists of only these
        }, "Username tidak memenuhi kriteria" );
        
        $.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
        }, "Silahkan inputkan Nama dengan huruf saja" );
    };
    
    return {
        //main function to initiate the module
        init: function () {
            handleValidationStaff();
        },
    };
}();

// ============================================================
// Form Validation Notification
// ============================================================
var FV_Notification = function () {    
    // ---------------------------------
    // Handle Validation Notification
    // ---------------------------------
    var handleValidationUpdateNotification = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation

        var form        = $('#form_notif_edit');
        var wrapper     = $('.wrapper_notif_edit');
        
        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                notif_id: {
                    required: true
                },
                notif_type: {
                    required: true
                },
                notif_title: {
                    required: true
                },
                notif_status: {
                    required: true
                },
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length > 0) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length > 0) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length > 0) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                App.alert({
                    type: 'danger', 
                    icon: 'warning', 
                    message: 'Anda memiliki beberapa kesalahan. Silakan cek di formulir bawah ini!', 
                    container: wrapper, 
                    place: 'prepend',
                    closeInSeconds: 5,
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                $(element).closest('.help-block').remove();
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
                label.closest('.help-block').remove();
            },
            submitHandler: function (form) {
                var url             = $(form).attr('action');
                var notif_id        = $('input[name=notif_id]', $(form)).val();
                var notif_type      = $('input[name=notif_type]', $(form)).val();
                var notif_title     = $('input[name=notif_title]', $(form)).val();
                var notif_status    = $('select[name=notif_status]', $(form)).val();
                var content_plain   = $('textarea[name=notif_content_plain]', $(form)).val();
                var content_email   = CKEDITOR.instances['notif_content_email'].getData();

                var data = {
                    'notif_id'      : notif_id,
                    'notif_type'    : notif_type,
                    'notif_title'   : notif_title,
                    'notif_status'  : notif_status,
                    'content_plain' : content_plain,
                    'content_email' : content_email
                }

                bootbox.confirm('Apakah anda yakin akan edit Notifikasi ini ?', function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   data,
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                App.close_Loader();
                                response = $.parseJSON(response);
                                if(response.status == 'login'){
                                    $(location).attr('href',response.login);
                                    return false;
                                }else{
                                    if(response.status == 'success'){
                                        var type = 'success';
                                        var icon = 'check';
                                        wrapper  = $('#notification_list').parents('.dataTables_wrapper');
                                        $('#modal-form-notification').modal('hide');
                                        $('#btn_notification_list').trigger('click');
                                    }else{
                                        var type = 'danger';
                                        var icon = 'warning';
                                    }
                                    App.alert({
                                        type: type,
                                        icon: icon,
                                        message: response.message,
                                        container: wrapper,
                                        closeInSeconds: 3,
                                        place: 'prepend'
                                    });
                                    return false;
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.');
                            }
                        });
                    }
                });
            }
        });
    };
    
    return {
        //main function to initiate the module
        init: function () {
            handleValidationUpdateNotification();
        },
    };
}();

// ============================================================
// Form Validation Setting Withdraw
// ============================================================
var FV_SettingWithdraw = function () {    
    // ---------------------------------
    // Handle Validation Setting Withdraw
    // ---------------------------------
    var handleValidationSettingWithdraw = function() {
        var form        = $( '#form-setting-wd' );
        var wrapper     = $( '.wrapper-setting-withdraw' );

        if ( ! form.length ) {
            return;
        }
        
        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                wd_min: {
                    required: true,
                },
                wd_fee: {
                    required: true,
                },
                wd_tax: {
                    required: true,
                },
                wd_tax_npwp: {
                    required: true,
                },
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit 
                App.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'Anda memiliki beberapa kesalahan. Silakan cek di formulir bawah ini!',
                    container: form,
                    place: 'prepend'
                });
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url         = $(form).attr('action');
                bootbox.confirm("Anda yakin akan edit data setting Withdraw ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('timer');
                            },
                            success: function( response ){
                                App.close_Loader();
                                response = $.parseJSON(response);
                                var alert_type = 'danger';
                                var alert_icon = 'fa fa-exclamation-triangle';
                                if ( response.status == 'login' ) {
                                    $(location).attr('href', response.url);
                                }
                                if ( response.status == 'success' ) {
                                    alert_type = 'success';
                                    alert_icon = 'fa fa-check';
                                }
                                App.notify({
                                    icon: alert_icon, 
                                    title: '', 
                                    message: response.message, 
                                    type: alert_type
                                });
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                App.notify({
                                    icon: 'fa fa-exclamation-triangle', 
                                    title: 'Failed', 
                                    message: 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', 
                                    type: 'danger',
                                });
                            }
                        });
                    }
                });
            }
        });
    };
    
    return {
        //main function to initiate the module
        init: function () {
            handleValidationSettingWithdraw();
        },
    };
}();