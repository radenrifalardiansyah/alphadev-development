// ===========================================================
// Shopping Product
// ===========================================================
var ShoppingProduct = function() {
    var _limit          = 12;
    var _offset         = 0;
    var _sortby         = '';
    var _orderby        = '';
    var totalRecords    = 0;

    var handleGeneralShoppingProduct = function(){
        // Button See More
        // -----------------------------------------------
        $("body").delegate( "#btn-shopping-see-more", "click", function( e ) {
            e.preventDefault();
            handleLoadDataShoppingProduct();
        });

        // Button Product Detail
        // -----------------------------------------------
        $("body").delegate( ".btn-product-detail", "click", function( e ) {
            e.preventDefault();
            var url             = $(this).data('url');
            var product_name    = $(this).data('product');
            var modal_detail    = $('#modal-shopping-product-detail');

            $.ajax({
                type:   "POST",
                url:    url,
                beforeSend: function (){
                    App.run_Loader('timer');
                    $('.info-shopping-product-detail', modal_detail).empty();
                },
                success: function( response ){
                    App.close_Loader();
                    response = $.parseJSON(response);
                    if( response.status == 'access_denied' ){
                        $(location).attr('href',response.url);
                    }else{
                        if( response.status == 'success'){
                            $('.title-product', modal_detail).text(product_name);
                            $('.info-shopping-product-detail', modal_detail).html(response.data);
                            modal_detail.modal('show');
                        }else{
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                title: 'Failed', 
                                message: response.message, 
                                type: 'danger',
                            });
                        }
                    }
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
        });

        // Search Product
        // -----------------------------------------------
        $("body").delegate( ".search_product", "blur", function( e ) {
            e.preventDefault();
            var search      = $( '.search_product').val();
            var product     = $(this).data('product');
            var s_product   = true;
            if ( search == product ) {
                s_product   = false;
            }

            if ( s_product ) {
                $( "#form-search-shopping-product" ).submit();
            }
            return false;
        });

        // Search Category
        // -----------------------------------------------
        $("body").delegate( ".search_category", "change", function( e ) {
            e.preventDefault();
            $( "#form-search-shopping-product" ).submit();
        });

        $( "#form-search-shopping-product" ).submit(function( event ) {
            event.preventDefault();
            var url         = $(this).data( 'url' );
            var product     = $( '.search_product').val();
            var category    = $( '.search_category').val();
            var data        = {
                'product': product,
                'category': category,
                'orderby': _orderby,
                'sortby': _sortby
            };

            var page_url    = url +"?"+ $.param(data);
            $(location).attr('href', page_url);
        });

        // Add To Cart
        // -----------------------------------------------
        $("body").delegate( "a.add-to-cart", "click", function( e ) {
            e.preventDefault();
            var el          = $(this);
            var url         = $(this).attr('href');
            var product     = $(this).data('cart');
            var type        = $(this).data('type');  

            if ( type == 'cart' ) { 
                $(location).attr('href', url);
                return false;
            }

            if ( product && type == 'addcart' ) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: { 'id': product, 'qty': 1 },
                    beforeSend: function (){
                        // App.run_Loader('timer');
                        el.addClass('btn-outline-default').addClass('disabled');
                        $('.shopping-cart-loading', el).show();
                    },
                    success: function( response ){
                        // App.close_Loader();     
                        response = $.parseJSON(response);
                        $('.shopping-cart-loading', el).hide();
                        el.removeClass('disabled');
                        el.removeClass('btn-outline-default');

                        if( response.status == 'access_denied' ){
                            $(location).attr('href',response.url);
                        }

                        if ( response.token ) {
                            App.kdToken(response.token);
                        }

                        if( response.status == 'success'){
                            var type = 'success';
                            var icon = 'fa fa-check';
                            el.text('Go to cart');
                            el.attr('href', response.url_cart);
                            el.removeData('cart');
                            el.data('type', 'cart');
                            if ( $('#cart-total-item').length ) {
                                $('#cart-total-item').text(response.total_item);
                            }
                        }else{
                            var type = 'danger';
                            var icon = 'fa fa-exclamation-triangle';
                        }
                        App.notify({
                            icon: icon, 
                            message: response.message, 
                            type: type,
                        });
                        // App.scrollTo(el, 0);
                    },
                    error: function( jqXHR, textStatus, errorThrown ) {
                        // App.close_Loader();
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            message: 'Terjadi kesalahan sistem. Silahkan reload page!', 
                            type: 'danger',
                        });
                    }
                });          
            }
            return false;
        });
    };

    var handleLoadDataShoppingProduct = function(){
        var el          = $('#product-shop-list')
        var url         = el.data( 'url' );
        var product     = $( '.search_product').val();
        var category    = $( '.search_category').val();
        var btn_loader  = $(".shopping-see-more-loading"); 

        if ( el && url ) {
            var h = el.height(); 
            if ( h > 0 ) {
                h = h - 50;
            }
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    'limit': _limit,
                    'offset': _offset,
                    'product': product,
                    'category': category,
                    'sortby': _sortby,
                    'orderby': _orderby
                },
                beforeSend: function (){
                    // App.run_Loader('timer');
                    if ( btn_loader.length ) {
                        btn_loader.show();
                    }
                },
                success: function( response ){
                    // App.close_Loader();     
                    response = $.parseJSON(response);

                    if( response.status == 'access_denied' ){
                        $(location).attr('href',response.url);
                    }

                    if ( response.token ) {
                        App.kdToken(response.token);
                    }

                    _limit          = response.displayLimit;
                    _offset         = response.displayStart;
                    totalRecords    = response.totalRecords;
                    if ( response.totalRecords > response.totalDisplayRecords ) {
                        $(".shopping-see-more").show();
                    } else {
                        $(".shopping-see-more").hide();
                    }

                    el.append(response.displayHTML);
                    if ( btn_loader.length ) {
                        btn_loader.hide();
                    }
                    // App.scrollTo(el, h);
                },
                error: function( jqXHR, textStatus, errorThrown ) {
                    // App.close_Loader();
                    bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                        location.reload();
                    });
                }
            });
        }
        return false;
    };
    
    return {
        init: function() {
            handleGeneralShoppingProduct();
            handleLoadDataShoppingProduct();
        }
    };
}();

// ===========================================================
// Shopping Cart
// ===========================================================
var ShoppingCart = function() {
    var _limit          = 12;
    var _offset         = 0;
    var _sortby         = '';
    var _orderby        = '';
    var totalRecords    = 0;

    var handleGeneralShoppingCart = function(){
        // Change Minus Qty Product 
        // -----------------------------------------------
        $("body").delegate( ".btn-cart-minus-qty", "click", function( e ) {
            e.preventDefault();
            var step    = $(this).data('step');
            var count   = $(this).closest(".product-quantity").find('.cart-item-qty').val();
            var countEl = $(this).closest(".product-quantity").find('.cart-item-qty');

            if ( parseInt(count) > parseInt(step) ) {
                count = parseInt(count) - parseInt(step);
                countEl.val(count).change();
            }
        });

        // Change Plus Qty Product 
        // -----------------------------------------------
        $("body").delegate( ".btn-cart-plus-qty", "click", function( e ) {
            e.preventDefault();
            var step    = $(this).data('step');
            var count   = $(this).closest(".product-quantity").find('.cart-item-qty').val();
            var countEl = $(this).closest(".product-quantity").find('.cart-item-qty');

            count = parseInt(count) + parseInt(step);
            countEl.val(count).change();
        });

        // Change Qty Product 
        // -----------------------------------------------
        $("body").delegate( ".cart-item-qty", "change", function( e ) {
            e.preventDefault();
            var url         = $(this).data('url');
            var rowid       = $(this).data('rowid');
            var productid   = $(this).data('productid');
            var price       = $(this).data('price');
            var qty         = $(this).val();

            var priceCart   = $(this).closest(".cart_item").find('.cart-item-price');
            var totalCart   = $(this).closest(".cart_item").find('.cart-item-subtotal');

            if ( ! qty || qty == '' || qty == '0' || qty == 0 || qty == undefined ) {
                location.reload();
                return false
            }

            $.ajax({
                type: "POST",
                url: url,
                data: { 'rowid': rowid, 'productid': productid, 'qty': qty },
                beforeSend: function (){
                    App.run_Loader('timer');
                },
                success: function( response ){
                    App.close_Loader();     
                    response = $.parseJSON(response);

                    if( response.status == 'access_denied' ){
                        $(location).attr('href',response.url);
                    }

                    if ( response.token ) {
                        App.kdToken(response.token);
                    }

                    if( response.status == 'success'){
                        priceCart.html(response.price_cart);
                        totalCart.html(response.subtotal_cart);
                        if ( $('.cart-total-paymnet').length ) {
                            $('.cart-total-paymnet').html(response.total_cart);
                        }
                    }else{
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            message: response.message, 
                            type: 'danger',
                        });
                    }
                },
                error: function( jqXHR, textStatus, errorThrown ) {
                    App.close_Loader();
                    bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                        location.reload();
                    });
                }
            });

            return false;
        });

        // Delete Product Cart 
        // -----------------------------------------------
        $("body").delegate( "a.btn-product-cart-delete", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).attr('href');
            var tr_cart     = $(this).closest("tr.cart_item");

            if ( url ) {
                $.ajax({
                    type: "POST",
                    url: url,
                    beforeSend: function (){
                        App.run_Loader('timer');
                    },
                    success: function( response ){
                        App.close_Loader();     
                        response = $.parseJSON(response);

                        if( response.status == 'access_denied' ){
                            $(location).attr('href',response.url);
                        }

                        if ( response.token ) {
                            App.kdToken(response.token);
                        }

                        if( response.status == 'success'){
                            tr_cart.remove();
                            if ( $('.cart-total-paymnet').length ) {
                                $('.cart-total-paymnet').html(response.total_cart);
                            }
                            if ( $('#cart-total-item').length ) {
                                $('#cart-total-item').text('');
                                if ( response.total_item != 0 && response.total_item != '0' ) {
                                    $('#cart-total-item').text(response.total_item);
                                }
                            }
                            if ( response.total_item == 0 && response.total_item == '0' ) {
                                location.reload();
                            }
                        }else{
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                message: response.message, 
                                type: 'danger',
                            });
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
            return false;
        });

        // Empty Cart 
        // -----------------------------------------------
        $("body").delegate( "a.btn-cart-empty", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).attr('href');

            if ( url ) {
                $.ajax({
                    type: "POST",
                    url: url,
                    beforeSend: function (){
                        App.run_Loader('timer');
                    },
                    success: function( response ){
                        response = $.parseJSON(response);

                        if( response.status == 'access_denied' ){
                            $(location).attr('href',response.url);
                        }

                        if ( response.token ) {
                            App.kdToken(response.token);
                        }

                        if( response.status == 'success'){
                            location.reload();
                        }else{
                            App.close_Loader();     
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                message: response.message, 
                                type: 'danger',
                            });
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
            return false;
        });
    };
    
    return {
        init: function() {
            handleGeneralShoppingCart();
        }
    };
}();

// ===========================================================
// Shopping Checkout
// ===========================================================
var ShoppingCheckout = function() {

    var form            = $('#form-shopping-checkout');
    var wrapper         = $('.wrapper-form-shopping-checkout');

    var subtotal_pin    = 0;
    var total_qty       = 0;
    var total_payment   = 0;
    var total_weight    = 0;
    var shipping_fee    = 0;
    var discount        = 0;
    var discount_code   = '';

    // ---------------------------------
    // Handle Validation
    // ---------------------------------
    var handleValidationShoppingCheckout = function() {
        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
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
                bootbox.confirm("Apakah anda yakin akan checkout pesanan produk ini ?", function(result) {
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
                                        bootbox.alert(response.message, function(){ 
                                            if ( response.url ) {
                                                $(location).attr('href',response.url);
                                            } else {
                                                location.reload();
                                            }
                                        });
                                    }else{
                                        App.notify({
                                            icon: 'fa fa-exclamation-triangle', 
                                            message: response.message, 
                                            type: 'danger',
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

    var handleGeneralShoppingCheckout = function(){
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

        // Button Checkout 
        // -----------------------------------------------
        $("body").delegate( "a.btn-shopping-checkout", "click", function( e ) {
            e.preventDefault();
            form.submit();
            return false;
        });
    };
    
    return {
        init: function() {
            handleValidationShoppingCheckout();
            handleGeneralShoppingCheckout();
        }
    };
}();