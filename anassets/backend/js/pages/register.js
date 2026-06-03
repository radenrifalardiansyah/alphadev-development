var RegisterMember = function() {
    var idcard_img;
    var photo_img;
    var cover_img;
    var form_reg        = $('#member_register');
    var wrapper         = $('.wrapper-form-register');
    var alert_msg       = $('#alert');
    var _dataPIN        = [];
    var _dataPINList    = []; 

    var _trEmpty        = `<tr class="data-empty"><td colspan="5" class="text-left">Produk belum ada yang di pilih.</td></tr>`;

    var subtotal        = 0;
    var total_weight    = 0;
    var total_payment   = 0;
    var shipping_fee    = 0;
    var discount        = 0;
    var discount_code   = '';

    var total_qty       = $('#select_product_package', form_reg).val();

    var reg_fee         = form_reg.data('regfee');
    var saldo           = form_reg.data('deposite');
    var access          = form_reg.data('access');
    var useruid         = form_reg.data('useruid');

    // Update Modal Confrimation
    var updateModalRegisterConfirm = function(modal) {
        var modal_body  = $('.modal-body', modal);
        var is_admin    = modal_body.data('admin');

        var tusername   = $('input[name=reg_member_username]').val();
        var tname       = $('input[name=reg_member_name]').val();
        var temail      = $('input[name=reg_member_email]').val();
        var tbank       = $('select[name="reg_member_bank"] option:selected').text();
        var tbill       = $('input[name=reg_member_bill]').val();
        var tbillname   = $('input[name=reg_member_bill_name]').val();
        var tpackage    = $('select[name="reg_member_package"] option:selected').text();
        var ttotalbv    = $('input[name=reg_member_package_omzet]').val();
        
        if ( ttotalbv == undefined || ttotalbv == "" ) {
            ttotalbv = 0;
        }
        
        var tsponsor    = '';
        if( is_admin == 1 ){
            tsponsor    = $('input[name=reg_member_sponsor]').val() + ' / ' + $('input[name=reg_member_sponsor_name_dsb]').val();
        }else{
            if ($('input[name=sponsored]:checked').val() == 'other_sponsor') {
                tsponsor = $('input[name=reg_member_sponsor]').val() + ' / ' + $('input[name=reg_member_sponsor_name_dsb]').val();
            }else{
                tsponsor = $('input[name=current_member_username]').val() + ' / ' + $('input[name=current_member_name]').val();
            }
        }
        
        $('.confirm-new-member-username', modal_body).text(tusername);
        $('.confirm-new-member-name', modal_body).text(tname);
        $('.confirm-new-member-email', modal_body).text(temail);
        $('.confirm-new-member-sponsor', modal_body).text(tsponsor);
        $('.confirm-new-member-total-bv', modal_body).text(ttotalbv);
        $('.confirm-new-member-package', modal_body).text(tpackage);
        $('.confirm-new-member-bank', modal_body).text(tbank);
        $('.confirm-new-member-bill', modal_body).text(tbill);
        $('.confirm-new-member-bill-name', modal_body).text(tbillname);
    };

    // Handle Validation Form
    var handleValidationRegMember = function() {
        form_reg.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                reg_member_cloning: {
                    minlength: 3,
                },
                select_product: {
                    required: true,
                },
                reg_member_package: {
                    required: true,
                },
                reg_member_upline: {
                    minlength: 5,
                    required: true,
                },
                reg_member_sponsor: {
                    minlength: 3,
                    required: function(element) {
                        if( $('#reg_member_sponsor_admin').length ){
                            return true;
                        }else{
                            return $('label#other_sponsor').hasClass('active');
                        }
                    }
                },
                reg_member_username: {
                    minlength: 5,
                    required: true,
                    unamecheck: true,
                    remote: {
                        url: $("#reg_member_username").data('url'),
                        type: "post",
                        data: {
                            [App.kdName()]: function() {
                                return App.kdToken();
                            },
                            username: function() {
                                return $("#reg_member_username").prop( 'readonly' ) ? '' : $("#reg_member_username").val();
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
                reg_member_password: {
                    minlength: 6,
                    required: true,
                    pwcheck: true,
                },
                reg_member_password_confirm: {
                    required: true,
                    equalTo: '#reg_member_password'
                },
                reg_member_name: {
                    minlength: 3,
                    required: true,
                    lettersonly: true,
                },
                reg_member_email: {
                    email: true,
                    required: true,
                    remote: {
                        url: $("#reg_member_email").data('url'),
                        type: "post",
                        data: {
                            [App.kdName()]: function() {
                                return App.kdToken();
                            },
                            email: function() {
                                return $("#reg_member_email").prop( 'readonly' ) ? '' : $("#reg_member_email").val();
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
                reg_member_phone: {
                    minlength: 8,
                    required: true,
                    remote: {
                        url: $("#reg_member_phone").data('url'),
                        type: "post",
                        data: {
                            [App.kdName()]: function() {
                                return App.kdToken();
                            },
                            phone: function() {
                                return $("#reg_member_phone").prop( 'readonly' ) ? '' : $("#reg_member_phone").val();
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
                reg_member_idcard: {
                    minlength: 16,
                    required: true,
                    remote: {
                        url: $("#reg_member_idcard").data('url'),
                        type: "post",
                        data: {
                            [App.kdName()]: function() {
                                return App.kdToken();
                            },
                            idcard: function() {
                                return $("#reg_member_idcard").prop( 'readonly' ) ? '' : $("#reg_member_idcard").val();
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
                reg_member_bill: {
                    required: true,
                    remote: {
                        url: $("#reg_member_bill").data('url'),
                        type: "post",
                        data: {
                            [App.kdName()]: function() {
                                return App.kdToken();
                            },
                            bill: function() {
                                return $("#reg_member_bill").prop( 'readonly' ) ? '' : $("#reg_member_bill").val();
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
                // reg_member_pob: {
                //     required: true,
                // },
                // reg_member_dob_date: {
                //     required: true,
                // },
                // reg_member_dob_month: {
                //     required: true,
                // },
                // reg_member_dob_year: {
                //     required: true,
                // },
                // reg_member_gender: {
                //     required: true,
                // },
                // reg_member_marital: {
                //     required: true,
                // },
                reg_member_idcard_type: {
                    required: true,
                },
                reg_member_province: {
                    required: true,
                },
                reg_member_district: {
                    required: true,
                },
                reg_member_subdistrict: {
                    required: true,
                },
                reg_member_village: {
                    required: true,
                },
                reg_member_address: {
                    required: true,
                },
                reg_member_postcode: {
                    required: true,
                },
                reg_member_bank: {
                    required: true,
                },
                reg_member_emergency_name: {
                    minlength: 3,
                    // required: true,
                    lettersonly: true,
                },
                reg_member_emergency_relationship: {
                    // required: true,
                    lettersonly: true,
                },
                reg_member_emergency_phone: {
                    minlength: 8,
                    // required: true
                },
                reg_member_term: {
                    required: true,
                },
            },
            messages: {
                reg_member_username: {
                    remote: "Username tidak dapat digunakan. Silahkan gunakan username lain",
                },
                reg_member_password_confirm: {
                    equalTo: "Password konfirmasi tidak cocok dengan password yang di atas",
                },
                reg_member_email: {
                    remote: "Email sudah digunakan. Silahkan gunakan email lain",
                },
                reg_member_phone: {
                    remote: "No. Telp/HP sudah digunakan. Silahkan gunakan No. Telp/HP lain",
                },
                reg_member_idcard: {
                    remote: "No. No. KTP/KITAS sudah terdaftar. Silahkan gunakan No. KTP/KITAS lain",
                },
                reg_member_bill: {
                    remote: "No. Rekening sudah terdaftar. Silahkan gunakan No. Rekening lain",
                },
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.custom-radio').length) { 
                    error.insertAfter(element.parents(".custom-radio-inline"));
                } else if (element.parents('.custom-checkbox').length) { 
                    error.insertAfter(element.parent(".custom-checkbox"));
                } else if (element.parents('.checkbox-list').length) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit 
                App.alert({
                    type: 'danger', 
                    icon: 'exclamation-triangle', 
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
                var access  = form_reg.data('access');
                if( $('.payment_method').length ){
                    if ( access == 'member' ) {
                        if ( $('label#payment_deposite').hasClass('active') ) {
                            if ( parseInt(reg_fee) > parseInt(saldo) ) {
                                App.notify({
                                    icon: 'fa fa-exclamation-triangle', 
                                    message: 'Saldo Deposite Anda tidak mencukupi untuk pendaftaran Agen ini !', 
                                    type: 'danger',
                                });
                                App.scrollTo($('.register_fee'), 0);
                                return false;
                            }
                        }
                    }
                }
                updateModalRegisterConfirm('#modal-save-member');
                $('#modal-save-member').modal('show');
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

    // Handle General Register Form
    var handleGeneralRegForm = function() {
        // Tree Register
        $("body").delegate( "a.add-user", "click", function( event ) {
            event.preventDefault();
            var id_parent   = $(this).data('id');
            var position    = $(this).data('position');
            var url         = window.location.origin + '/member/searchuplinetree';

            var el          = $('#upline_info');
            var msg         = $('#alert');

            $.ajax({
                type:   "POST",
                data:   {'id_parent': id_parent, 'position': position},
                url:    url,
                beforeSend: function (){
                    App.run_Loader('roundBounce');
                    $('#tree_register').removeClass('d-none');
                    $(el).empty().hide();
                },
                success: function( response ){
                    App.close_Loader();
                    response = $.parseJSON(response);

                    if( form_reg.length ){
                        form_reg[0].reset();
                    }
                    if( response.status == 'login' ){
                        $(location).attr('href',response.message);
                    }else{
                        if(response.status == 'success'){
                            App.scrollTo($('#tree_register'), -20);
                            $(el).html(response.info).fadeIn();
                        }else{
                            $('#tree_register').addClass('d-none');
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                title: 'Failed', 
                                message: response.message, 
                                type: 'danger',
                            });
                        }
                    }
                }
            });
            return false;
        });

        // Member Name == Bill Name
        $(':input[name=reg_member_name]', form_reg).bind('blur, keyup', function(){
            var name = $(this).val();
            $(':input[name=reg_member_bill_name]', form_reg).val(name);
        });

        // Select Sponsor
        $('label.spon').each(function(){
            $(this).click(function(e){
                e.preventDefault();
                var val = $(this).find('input.toggle').val();
                $('#reg_member_sponsor').val('');

                if( val == 'other_sponsor' ){
                    $('#sponsor_form').fadeIn();
                    $('#sponsor_form').find('.help-block').remove();
                    $('#reg_member_sponsor').attr('disabled', false);
                }else{
                    $('#sponsor_form').fadeOut();
                    $(this).parent().parent().removeClass('has-error');
                    $('#sponsor_form').find('.help-block').empty().hide();
                    $('#sponsor_info').empty().hide();
                }
            });
        });

        // Select Clone Data
        $('label.clone').each(function(){
            $(this).click(function(e){
                e.preventDefault();
                var val = $(this).find('input.cloning').val();
                if ( $('#reg_member_clone').length ) {
                    $('#reg_member_clone').val('');
                }

                if( val == 'clone' ){
                    if ( $('#clone_form').length && $('#reg_member_clone').length ) {
                        $('#clone_form').fadeIn();
                        $('#clone_form').find('.help-block').remove();
                        $('#reg_member_clone').attr('disabled', false);
                    } else {
                        handleSearchCloning();
                    }
                }else{
                    if ( $('#clone_form').length ) {
                        $('#clone_form').fadeOut();
                        $(this).parent().parent().removeClass('has-error');
                        $('#clone_form').find('.help-block').empty().hide();
                    }
                    handleDataCloning();
                }
            });
        });


        // Search Member Cloning
        $('#reg_member_clone').bind('blur', function(){
            handleSearchCloning();
        });

        $("body").delegate( "#btn_search_clone", "click", function( e ) {
            e.preventDefault();
            handleSearchCloning();
        });

        // Select KTP
        $('#idcard_thumbnail').on('click', function(e) {
            $('#idcard_file').trigger('click');
        });

        $('#idcard_file').on('change', function(e) {
            App.readURLmedia( $(this), $('#idcard_thumbnail'), '', 'idcard-thumbnail');
            idcard_img = e.target.files;
        });

        // Select Photo Profile
        $('#photo_thumbnail').on('click', function(e) {
            $('#photo_file').trigger('click');
        });

        $('#photo_file').on('change', function(e) {
            App.readURLmedia( $(this), $('#photo_thumbnail'), '', 'photo-thumbnail');
            photo_img = e.target.files;
        });

        // Select Photo Cover
        $('#cover_thumbnail').on('click', function(e) {
            $('#cover_file').trigger('click');
        });

        $('#cover_file').on('change', function(e) {
            App.readURLmedia( $(this), $('#cover_thumbnail'), '', 'cover-thumbnail');
            cover_img = e.target.files;
        });

        // Change Country
        $('select[name=reg_member_country]', form_reg).bind('change', function(){
            var name = $(this).val();
            if ( name == 'IDN' ) {
                $('.country-idn', form_reg).show();
                $('.country-noidn', form_reg).hide();
            } else {
                $('.country-idn', form_reg).hide();
                $('.country-noidn', form_reg).show();
            }
        });

        // Change Country Current
        $('select[name=reg_member_country_current]', form_reg).bind('change', function(){
            var name = $(this).val();
            if ( name == 'IDN' ) {
                $('.country-idn-current', form_reg).show();
                $('.country-noidn-current', form_reg).hide();
            } else {
                $('.country-idn-current', form_reg).hide();
                $('.country-noidn-current', form_reg).show();
            }
        });

        // Change Country Current
        $(':input[name=residential_address]', form_reg).bind('change', function(){
            var name = $('select[name=reg_member_country_current]', form_reg).val();
            if ( $(':input[name=residential_address]').prop("checked") == true ) {
                $('.current-address', form_reg).hide();
            } else {
                $('.current-address', form_reg).show();
            }
        });

        // Save Registered Member
        $('#do_save_member').click(function(e){
            e.preventDefault();
            var formid  = $(this).data('formid');
            saveMember($('#' + formid));
        });

        // Reset Form Register
        $('.btn-register-reset').click(function(e){
            e.preventDefault();
            if( $('#sponsor_info').is(":visible") ){ $('#sponsor_info').empty().hide(); }
            $('select', form_reg).val('');
            form_reg[0].reset();
        });

        $('#success_save').on('hidden.bs.modal', function () {
            location.reload();
        });
    };

    // Handle Search Cloning Data Function
    // --------------------------------------------------------------------------
    var handleSearchCloning = function() {
        var usercloning     = useruid;
        var el_cloning      = $('#cloning-data');
        var search          = true;

        if ( ! el_cloning.length ) {
            return;
        }

        if ( access == 'admin' ) {
            if ( $('input[name="reg_member_clone"]').length ) {
                usercloning = $('input[name="reg_member_clone"]').val();
            }
        }

        if ( usercloning == '' ) {
            search          = false;
        }

        var url             = el_cloning.data('url');
        if ( ! url ) {
            search          = false;
        }

        if ( search ) {
            $.ajax({
                type:   "POST",
                data:   { 'username' : usercloning, 'access' : access },
                url:    url,
                beforeSend: function (){
                    App.run_Loader('roundBounce');
                },
                success: function( response ){
                    App.close_Loader();
                    response = $.parseJSON(response);
                    App.scrollTo($('#cloning-data'), -100);

                    if( response.status == 'login' ){
                        $(location).attr('href',response.message);
                    }else{
                        if( response.status == 'success'){
                            App.notify({
                                icon: 'fa fa-check-circle', 
                                message: response.message, 
                                type: 'success',
                            });
                            handleDataCloning(response.data);
                        }else{
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                message: response.message, 
                                type: 'danger',
                            });
                        }
                    }
                }
            });
        }
        return false;
    };

    // Handle Data Cloning Function
    // --------------------------------------------------------------------------
    var handleDataCloning = function(cloningdata = '') {
        var cloning_name                    = ( cloningdata.name ) ? cloningdata.name : '';
        var cloning_email                   = ( cloningdata.email ) ? cloningdata.email : '';
        var cloning_phone                   = ( cloningdata.phone ) ? cloningdata.phone : '';
        var cloning_phone_home              = ( cloningdata.phone_home ) ? cloningdata.phone_home : '';
        var cloning_phone_office            = ( cloningdata.phone_office ) ? cloningdata.phone_office : '';
        var cloning_idcard_type             = ( cloningdata.idcard_type ) ? cloningdata.idcard_type : 'KTP';
        var cloning_idcard                  = ( cloningdata.idcard ) ? cloningdata.idcard : '';
        var cloning_npwp                    = ( cloningdata.npwp ) ? cloningdata.npwp : '';
        var cloning_province                = ( cloningdata.province ) ? cloningdata.province : '';
        var cloning_district                = ( cloningdata.district ) ? cloningdata.district : '';
        var cloning_subdistrict             = ( cloningdata.subdistrict ) ? cloningdata.subdistrict : '';
        var cloning_village                 = ( cloningdata.village ) ? cloningdata.village : '';
        var cloning_address                 = ( cloningdata.address ) ? cloningdata.address : '';
        var cloning_bank                    = ( cloningdata.bank ) ? cloningdata.bank : '';
        var cloning_bill                    = ( cloningdata.bill ) ? cloningdata.bill : '';
        var cloning_bill_name               = ( cloningdata.bill_name ) ? cloningdata.bill_name : '';
        var cloning_emergency_name          = ( cloningdata.emergency_name ) ? cloningdata.emergency_name : '';
        var cloning_emergency_relationship  = ( cloningdata.emergency_relationship ) ? cloningdata.emergency_relationship : '';
        var cloning_emergency_phone         = ( cloningdata.emergency_phone ) ? cloningdata.emergency_phone : '';
        var cloning_opt_provinces           = ( cloningdata.opt_provinces ) ? cloningdata.opt_provinces : '';
        var cloning_opt_districts           = ( cloningdata.opt_districts ) ? cloningdata.opt_districts : '';
        var cloning_opt_subdistricts        = ( cloningdata.opt_subdistricts ) ? cloningdata.opt_subdistricts : '';

        // Member Information
        $('input[name="reg_member_name"]').val(cloning_name);
        $('input[name="reg_member_email"]').val(cloning_email);
        $('input[name="reg_member_phone"]').val(cloning_phone);
        $('input[name="reg_member_phone_home"]').val(cloning_phone_home);
        $('input[name="reg_member_phone_office"]').val(cloning_phone_office);
        $('input[name="reg_member_idcard"]').val(cloning_idcard);
        $('input[name="reg_member_npwp"]').val(cloning_npwp);
        $('select[name="reg_member_idcard_type"]').val(cloning_idcard_type);

        // Member Address
        if ( cloning_province ) {
            $('select[name="reg_member_province"]').empty().append(cloning_opt_provinces).val(cloning_province);
            $('select[name="reg_member_district"]').empty().append(cloning_opt_districts).val(cloning_district);
            $('select[name="reg_member_subdistrict"]').empty().append(cloning_opt_subdistricts).val(cloning_subdistrict);
        } else {
            $('select[name="reg_member_province"]').val('').trigger('change');
        }
        $('input[name="reg_member_village"]').val(cloning_village);
        $('input[name="reg_member_address"]').val(cloning_address);

        // Member Bank
        $('#reg_member_bank').val(cloning_bank).trigger('change');
        $('input[name="reg_member_bill"]').val(cloning_bill);
        $('input[name="reg_member_bill_name"]').val(cloning_bill_name);

        // Member Emergency
        $('input[name="reg_member_emergency_name"]').val(cloning_emergency_name);
        $('input[name="reg_member_emergency_relationship"]').val(cloning_emergency_relationship);
        $('input[name="reg_member_emergency_phone"]').val(cloning_emergency_phone);

        return false;
    };

    // Save Member
    var saveMember = function(form) {
        var form_reg    = $(form);
        var form_url    = form_reg.data('url');
        var form_val    = form_reg.data('val');
        var msg         = $('#alert');
        var url         = form_reg.attr('action');
        var data        = form_reg.serialize();
        var wrapper     = $('.register_body_wrapper');

        var form_data   = new FormData();
        var wrapper     = $('.register_body_wrapper');

        // Get Token
        form_data.append(App.kdName(), App.kdToken());

        // get inputs
        $('textarea.form-control, select.form-control, input.form-control:not([type="radio"],[type="checkbox"])',  form_reg).each(function(){
            form_data.append($(this).attr("name"), $(this).val());
        });

        // get all checkable inputs
        $( 'input.custom-checked[type="checkbox"]:checked, input.custom-control-input[type="radio"]:checked', form_reg ).each( function() {
            form_data.append($(this).attr("name"), $(this).val());
        });

        // get all checkable inputs
        $( 'input.sponsored[type="checkbox"]:checked, input.sponsored[type="radio"]:checked', form_reg ).each( function() {
            form_data.append($(this).attr("name"), $(this).val());
        });
            
        if ( idcard_img ) {
            $.each(idcard_img, function(key, value){
                form_data.append('idcard_img', value);
            });
        }
            
        if ( photo_img ) {
            $.each(photo_img, function(key, value){
                form_data.append('photo_img', value);
            });
        }
            
        if ( cover_img ) {
            $.each(cover_img, function(key, value){
                form_data.append('cover_img', value);
            });
        }

        msg.hide();

        $.ajax({
            type:   "POST",
            data:   form_data,
            url:    url,
            contentType:false,
            processData:false,
            cache:false,
            beforeSend: function (){
                App.run_Loader('timer');
                $('#modal-save-member').modal('hide');
            },
            success: function( resp ){
                App.close_Loader();
                resp = resp.replace(/<br\s*[\/]?>/g,"");
                response = $.parseJSON(resp);
                
                if ( response.token ) {
                    App.kdToken(response.token);
                }

                if(response.message == 'error'){
                    if( response.login == 'login' ){
                        $(location).attr('href',response.data);
                    }else{
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            title: 'Failed', 
                            message: response.data.msg, 
                            type: 'danger',
                        });
                    }
                }else if(response.message == 'success'){
                    $('#success_member').empty().html(response.data.memberinfo);
                    $('#modal-success-save').modal('show');
                    $('#modal-success-save').on('hidden.bs.modal', function () {
                        if( $('#sponsor_info').is(":visible") ){ $('#sponsor_info').empty().hide(); }
                        form_reg[0].reset();
                        location.reload();
                    });
                }

                App.scrollTo(wrapper, 0);
                return false;
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
    };

    return {
        init: function() {
            handleValidationRegMember();
            handleGeneralRegForm();
        }
    };
}();
    