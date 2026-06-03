var ROMember = function() {
    var form_ro         = $('#member_ro');
    var wrapper         = $('.wrapper-form-ro');
    var form_user       = form_ro.data('username');
    var form_name       = form_ro.data('name');
    var alert_msg       = $('#alert');

    // Handle Validation Form
    var handleValidationROMember = function() {
        form_ro.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                select_pin: {
                    required: true,
                },
                username: {
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
                var url     = $(form).attr('action');
                bootbox.confirm("Apakah data RO sudah benar ?", function(result) {
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
                                        setTimeout(function(){ location.reload(); }, 500);
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
                                bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                                    location.reload();
                                });
                            }
                        });
                    }
                });
            }
        });
    };

    // Handle General RP Form
    var handleGeneralROForm = function() {
        // Select Sponsor
        $('label.optro').each(function(){
            $(this).click(function(e){
                e.preventDefault();
                var val = $(this).find('input.toggle').val();
                $('#username').val('');
                $('#name').val('');
                $(this).parent().parent().removeClass('has-error');
                $('.option-ro-username').find('.invalid-feedback').remove();

                if( val == 'other' ){
                    $('#username').attr('disabled', false);
                    $('#username').focus();
                    $('.btn-search-member').show();
                }else{
                    $('#username').val(form_user);
                    $('#name').val(form_name);
                    $('#username').attr('disabled', true);
                    $('.btn-search-member').hide();
                }

            });
        });
    };

    return {
        init: function() {
            handleValidationROMember();
            handleGeneralROForm();
        }
    };
}();
    