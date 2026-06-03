// ===========================================================
// GLOBAL FUNTION
// ===========================================================
$(function(){    
    $('.photo-wrapper').tooltip({
        html:true
    });
    $('.photo-wrapper-board').tooltip({
        html:true
    });
    $('.btn-tooltip').tooltip({
        html:true
    });

    // Show / Hide Password
    // -----------------------------------------------
    $("body").delegate( ".pass-show-hide", "click", function( event ) {
        event.preventDefault();
        var parent  = $(this).parent().parent();
        var icon    = $(this).children();
        if ( ! parent.length ) { return; }
        if ( ! icon.length ) { return; }
        var input   = parent.children('input');
        if ( ! input.length ) { return; }
        var type    = input.attr('type');
        if (type === "password") {
            type = "text";
            icon.removeClass('fa-eye-slash');
            icon.addClass('fa-eye');
        } else {
            type = "password";
            icon.removeClass('fa-eye');
            icon.addClass('fa-eye-slash');
        }
        input.attr('type',type);
        return;
    });

    // Copy To Clipboard
    // -----------------------------------------------
    $("body").delegate( ".copy-to-clipboard", "click", function( event ) {
        event.preventDefault();
        copyToClipboard(document.getElementById("input-copy-to-clipboard"));
        return;
    });
});

var copyToClipboard = function(elem) {
      // create hidden text element, if it doesn't already exist
    var targetId    = "_hiddenCopyText_";
    var isInput     = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target              = elem;
        origSelectionStart  = elem.selectionStart;
        origSelectionEnd    = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target              = document.createElement("textarea");
            target.style.position   = "absolute";
            target.style.left       = "-9999px";
            target.style.top        = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }

    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    
    // copy the selection
    var succeed;
    try {
        succeed = document.execCommand("copy");
    } catch(e) {
        succeed = false;
    }

    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }
    
    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    return succeed;
}

var popupManager = function  (url) {
    var w = 880;
    var h = 570;
    var l = Math.floor((screen.width-w)/2);
    var t = Math.floor((screen.height-h)/2);
    window.open(url, 'Media', "scrollbars=1,width=" + w + ",height=" + h + ",top=" + t + ",left=" + l);
}

// ===========================================================
// Check Json
// ===========================================================
var isJson = function(str) {
    try {
        $.parseJSON(str);
    } catch (e) {
        return false;
    }
    return true;
};

// ===========================================================
// Read Url File
// ===========================================================
var readURL = function(input, img_id, video_id = '') {
    if (input[0].files && input[0].files[0]) {
        var typeFile    = input[0].files[0].type;
        var sizeFile    = input[0].files[0].size;
        var _size       = Math.round(sizeFile/1024);
        var _type       = 'image';
        if ( typeFile ) {
            _type       = typeFile.substr(0, typeFile.indexOf('/')); 
        }
        $('.img-information').show();

        var reader = new FileReader();
        reader.onload = function (e) {
            if ( _type == 'video' && video_id ) {
                video_id.attr('src', e.target.result);
                video_id.show();
                img_id.hide();
                img_id.attr('src', '');
            } else {
                img_id.attr('src', e.target.result);
                img_id.show();
                if ( video_id ) {
                    video_id.hide();
                    video_id.attr('src', '');
                }
            }

            if ( $('#size_img_thumbnail').length ) {
                if ( _size > 1024 ) {
                    _size = Math.round(_size/1024);
                    _size = _size + ' MB';
                } else {
                    _size = _size + ' KB';
                }
                $('#size_img_thumbnail').text(_size);
            }
        }

        reader.readAsDataURL(input[0].files[0]);
    }
};

var imageReadURL = function(img_url = '', img_id = '') {
    if ( img_url && img_id ) {
        img_id.attr('src', img_url);
    }
};

// CUrrency Format Function
// --------------------------------------------------------------------------
var formatCurrency = function( currency = '', rp = false ) {
    if (currency) {
        var number_string = currency.toString();
        sisa   = number_string.length % 3;
        rupiah = number_string.substr(0, sisa);
        ribuan = number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah   += separator + ribuan.join('.');
        }
        return ( rp ? 'Rp ' : '' ) + rupiah;
    } else {
        return rp ? 'Rp 0 ' : '0';
    }
};

// ===========================================================
// Tinymce
// ===========================================================
var TinymceText = function() {
    return {
        init: function() {
            tinymce.init({
                selector: '#tinymce',
                plugins: [
                     "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                     "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                     "table contextmenu directionality paste textcolor responsivefilemanager code"
                 ],
                 toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | responsivefilemanager image media | link unlink anchor  | forecolor backcolor  | print preview code ",
                 // toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
                 image_advtab: true,
                 external_filemanager_path:"http://web.local/filemanager/",
                 filemanager_title:"Responsive Filemanager",
                 external_plugins: { "filemanager" : "http://web.local/filemanager/plugin.min.js"}
            });
        }
    };
}();

// ===========================================================
// Input Mask
// ===========================================================
var InputMask = function() {
    var handleInputMask = function() {
        $(".numbermask").inputmask({
            "mask": "9",
            "repeat": 30,
            "greedy": false
        });
        $( '.numbercurrency').inputmask("currency", {
            prefix: "",
            radixPoint: ",",
            groupSeparator: ".",
            placeholder: "0",
            digits: 0,
            rightAlign: 0
        });
        $( '.numberdecimal').inputmask("decimal", {
            prefix: "",
            radixPoint: ",",
            digits: 2,
            groupSeparator: ".",
            rightAlign: 0
        });
        $( '.numberpercent').inputmask("percentage", {
            radixPoint: ",",
            digits: 2,
            groupSeparator: ".",
            rightAlign: 0
        });
        $(".npwp").inputmask("99\.999\.999\.9-999\.999");

        $("input.phonenumber").keyup(function () {
            if (this.value.substring(0, 1) == "0") {
                this.value = this.value.replace(/^0+/g, "");
            }
        });

        $("input.numbercurrency").blur(function () {
            if (this.value.substring(0, 1) == "-") {
                this.value = this.value.replace(/^-+/g, "");
            }
        });
    };

    var handleInputClick = function() {
        // Edit Link
        $("body").delegate( ".btn-edit-link", "click", function( event ) {
            event.preventDefault();
            if ( $('.slug-link').length ) {
                $('.slug-link').removeAttr('disabled');
                $('.slug-link').focus();
            }
        });

        // Remove Image
        $("body").delegate( ".btn-remove-image", "click", function( event ) {
            event.preventDefault();
            var src = $(this).data('url');
            if ( src ) {
                imageReadURL(src, $('#view_image'));
                $(this).hide();
                if ( $('#post_image').length ) {
                    $('#post_image').val('');
                }
            }
        });
    };

    return {
        init: function() {
            handleInputMask();
            handleInputClick();
            if ( $("input.phonenumber").length ) {
                $( ':input.phonenumber' ).each( function() {
                    if (this.value.substring(0, 1) == "0") {
                        this.value = this.value.replace(/^0+/g, "");
                    }
                });
            }
        }
    };
}();

// ===========================================================
// iCheck Input
// ===========================================================
var iCheckInput = function() {
    var handleiCheckInput = function() {
        $('input[type="checkbox"].icheck-min, input[type="radio"].icheck-min').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass   : 'iradio_flat-blue'
        });
    };

    return {
        init: function() {
            handleiCheckInput();
        }
    };
}();

// ===========================================================
// Button Action
// ===========================================================
var ButtonAction = function() {
    // Handle Button Delete 
    var handleActionDelete = function() {
        // Delete List Data
        $("body").delegate( ".btn-delete-data", "click", function( event ) {
            event.preventDefault();
            var url         = $(this).attr('href');
            var btn_list    = $(this).data('btn-list');
            var msg_list    = $(this).data('message');
            var wrapper     = $('.content');
            var msg         = 'Anda yakin akan hapus data ini ?';
            if ( msg_list ) {
                msg         = msg_list;
            }

            bootbox.confirm(msg, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);

                            if( response.status == 'login' ){
                                $(location).attr('href',response.message);
                            }else{
                                if( response.status == 'success'){
                                    var _type = 'success';
                                    var _icon = 'check';
                                    if ( btn_list ) {
                                        $('#' + btn_list).trigger('click');
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
                                    closeInSeconds: 3,
                                });
                            }
                            return false;
                        },
                        error: function( jqXHR, textStatus, errorThrown ) {
                            App.close_Loader();
                            bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.');
                        }
                    });
                }
            });

            return false;
        });
    };

    // Handle Button Confirm
    var handleActionConfirm = function() {
        // Register Member Confirm
        $("body").delegate( "a.btn-member-confirm", "click", function( event ) {
            event.preventDefault();
            var url             = $(this).data('url');
            var username        = $(this).data('username');
            var name            = $(this).data('name');
            var nominal         = $(this).data('nominal');

            var msg_body    = `
                <h4 class="pt-4 pb-3 text-center">Apakah anda yakin akan Konfirmasi Pendaftaran Reseller ini ?</h4>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Username :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Username :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${username} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Nama :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Nama :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${name} </small></div>
                </div>
                <hr class="mt-2 mb-3">
                <div class="row justify-content-center">
                    <form class="form-horizontal" id="form-member-confirm">
                        <div class="form-group mb-1">
                            <div class="col-md-12">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>`;

            bootbox.confirm({
                title: "",
                message: msg_body,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-danger'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm',
                        className: 'btn-primary'
                    }
                },
                callback: function (result) {
                    if( result == true ){

                        var data = {};
                        var password = '';

                        if ($('#password_confirm', '#form-member-confirm').length) {
                            password = $('#password_confirm', '#form-member-confirm').val();
                            data.password = password;
                        }

                        if (password == "" || password == undefined) {
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                title: 'Failed', 
                                message: 'Password harus diisi !', 
                                type: 'warning',
                            });
                            $('#password_confirm').focus();
                            return false;
                        }

                        $.ajax({
                            type:   "POST",
                            data:   data,
                            url:    url,
                            beforeSend: function (){
                                App.run_Loader('roundBounce');
                            },
                            success: function( response ){  
                                App.close_Loader();
                                response    = $.parseJSON(response);
                                if( response.status == 'login'){
                                    $(location).attr('href',response.url);
                                } else {
                                    if( response.status == 'success'){
                                        var _type = 'success';
                                        var _icon = 'fa fa-check';
                                        App.kdToken(response.token);
                                        $('#btn_list_table_member').trigger('click');
                                    }else{
                                        var _type = 'danger';
                                        var _icon = 'fa fa-exclamation-circle';
                                    }

                                    App.notify({
                                        icon: _icon, 
                                        message: response.message, 
                                        type: _type,
                                    });
                                }
                                return false;
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
                }
            });
        });

        // As Member Confirm  
        $("body").delegate( "a.asconfirm", "click", function( event ) {
            event.preventDefault();
            var url             = $(this).attr('href');
            var name            = $(this).data('name');
            var username        = $(this).data('username');
            var table_container = $('#registration_list').parents('.dataTables_wrapper');

            var msg  = 'Anda yakin akan konfirmasi member [<b>'+username+'</b>] ' + name + ' ?';
            bootbox.confirm(msg, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);

                            if( response.status == 'login' ){
                                $(location).attr('href',response.message);
                            }else{
                                if( response.status == 'success'){
                                    App.alert({
                                        type: 'success',
                                        icon: 'check',
                                        message: response.message,
                                        container: table_container,
                                        place: 'prepend',
                                        closeInSeconds: 3,
                                    });
                                    $('button#btn_registration_list').trigger('click');
                                }else{
                                    App.alert({
                                        type: 'danger',
                                        icon: 'warning',
                                        message: response.message,
                                        container: table_container,
                                        place: 'prepend',
                                        closeInSeconds: 3,
                                    });
                                }
                            }
                            return false;
                        }
                    });
                }
            });
        });

        // Topup Ewallet Confirm
        $("body").delegate( "a.ewallettopupconfirm", "click", function( event ) {
            event.preventDefault();
            var url         = $(this).attr('href');
            var username    = $(this).data('username');
            var name        = $(this).data('name');
            var transfer    = $(this).data('transfer');
            var nominal     = $(this).data('nominal');
            var unique      = $(this).data('unique');
            var container   = $(this).data('container');
            var message     = $(this).data('message');

            var table_container = $('#'+container).parents('.dataTables_wrapper');

            var msg_body    = `<form class="form-horizontal" id="form-topup-saldo-confirm">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">ID Anggota</label>
                                    <div class="col-md-6">
                                        <input type="text" value="`+username+`" class="form-control" readonly="readonly" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Nama</label>
                                    <div class="col-md-6">
                                        <input type="text" value="`+name+`" class="form-control" readonly="readonly" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Nominal Topup</label>
                                    <div class="col-md-6">
                                        <input type="text" value="`+nominal+`" class="form-control" readonly="readonly" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Kode Unik</label>
                                    <div class="col-md-6">
                                        <input type="text" value="`+unique+`" class="form-control" readonly="readonly" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Jumlah Transfer</label>
                                    <div class="col-md-6">
                                        <input type="text" value="`+transfer+`" class="form-control" readonly="readonly" />
                                    </div>
                                </div>
                                <br><br><div class="form-group">
                                    <label class="col-md-4 control-label">Password</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="password" name="pwd" id="pwd" class="form-control" placeholder="Password Konfirmasi" autocomplete="off">
                                            <span class="input-group-btn">
                                                <button class="btn btn-flat btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                </form>`;

            bootbox.confirm({
                title: message,
                message: msg_body,
                buttons: {
                    cancel: {
                        label: 'Kembali'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Konfirmasi'
                    }
                },
                callback: function (result) {
                    if( result == true ){
                        var password = '';

                        if ( $('#pwd', '#form-topup-saldo-confirm').length ) {
                            password = $('#pwd').val();
                        }

                        if ( password == "" || password == undefined ) {
                            App.alert({
                                type: 'danger', 
                                icon: 'warning', 
                                message: 'Password harus di isi !', 
                                container: $('#form-topup-saldo-confirm'), 
                                place: 'prepend'
                            });
                            $('#pwd').focus();
                            return false;
                        }

                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   { password: password },
                            beforeSend: function (){
                                App.run_Loader('roundBounce');
                            },
                            success: function( response ){
                                App.close_Loader();
                                response    = $.parseJSON(response);

                                if( response.status == 'login' ){
                                    $(location).attr('href',response.login);
                                }else{
                                    if( response.status == 'success'){
                                        var type = 'success';
                                        var icon = 'check';
                                    }else{
                                        var type = 'danger';
                                        var icon = 'warning';
                                    }

                                    App.alert({
                                        type: type,
                                        icon: icon,
                                        message: response.message,
                                        container: table_container,
                                        place: 'prepend'
                                    });

                                    if( response.status == 'success'){
                                        $('#btn_'+container).trigger('click');
                                    }
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.');
                            }
                        });
                    }
                }
            });
        });

        // Withdraw Confirm Transfer
        $("body").delegate( "a.withdrawaltransfer", "click", function( event ) {
            event.preventDefault();
            var url             = $(this).attr('href');
            var username        = $(this).attr('username');
            var name            = $(this).attr('name');
            var bank            = $(this).attr('bank');
            var bill            = $(this).attr('bill');
            var billnama        = $(this).attr('billnama');
            var nominal         = $(this).attr('nominal');

            var msg_body    = `
                <h4 class="pt-4 pb-3 text-center">Apakah anda yakin akan konfirmasi withdraw ini ?</h4>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Username :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Username :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${username} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Nama :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Nama :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${name} </small></div>
                </div>
                <hr class="my-1">
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Bank :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Bank :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${bank} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">No. Rekening :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">No. Rekening :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${bill} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Pemilik Rek. :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Pemilik Rek. :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${billnama} </small></div>
                </div>
                <hr class="my-1">
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Jumlah Transfer :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Jumlah Transfer :</small></div>
                    <div class="col-sm-6"><small class="heading-title text-warning font-weight-bold"> ${nominal} </small></div>
                </div>
                <hr class="mt-2 mb-3">
                <div class="row justify-content-center">
                    <form class="form-horizontal" id="form-withdraw-confirm">
                        <div class="form-group mb-1">
                            <div class="col-md-12">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>`;

            bootbox.confirm({
                title: "",
                message: msg_body,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-danger'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm',
                        className: 'btn-primary'
                    }
                },
                callback: function (result) {
                    if( result == true ){

                        var data = {};
                        var password = '';

                        if ($('#password_confirm', '#form-withdraw-confirm').length) {
                            password = $('#password_confirm', '#form-withdraw-confirm').val();
                            data.password = password;
                        }

                        if (password == "" || password == undefined) {
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                title: 'Failed', 
                                message: 'Password harus diisi !', 
                                type: 'warning',
                            });
                            $('#password_confirm').focus();
                            return false;
                        }

                        $.ajax({
                            type:   "POST",
                            data:   data,
                            url:    url,
                            beforeSend: function (){
                                App.run_Loader('roundBounce');
                            },
                            success: function( response ){  
                                App.close_Loader();
                                response    = $.parseJSON(response);
                                if ( response.token ) {
                                    App.kdToken(response.token);
                                }
                                if( response.status == 'login'){
                                    $(location).attr('href',response.url);
                                } else {
                                    if( response.status == 'success'){
                                        var _type = 'success';
                                        var _icon = 'fa fa-check';
                                        $('#btn_list_table_withdraw').trigger('click');
                                    }else{
                                        var _type = 'danger';
                                        var _icon = 'fa fa-exclamation-circle';
                                    }

                                    App.notify({
                                        icon: _icon, 
                                        message: response.message, 
                                        type: _type,
                                    });
                                }
                                return false;
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                                    location.reload();
                                });
                            }
                        });
                    }
                }
            });
        });

        // Withdraw Inquiry Bank
        $("body").delegate("a.withdrawinquiry", "click", function (event) {
            event.preventDefault();
            var url             = $(this).attr('href');
            var username        = $(this).attr('username');
            var name            = $(this).attr('name');
            var bank            = $(this).attr('bank');
            var bill            = $(this).attr('bill');
            var billnama        = $(this).attr('billnama');
            var table_container = $('#list_table_withdraw').parents('.dataTables_wrapper');

            var msg_body = `<h4 class="pt-4 pb-3 text-center">Apakah Anda yakin akan Inquiry Data Bank terbaru Member ?</h4>
                            <table width="90%" border="0" align="center">
                                <tr><td width="30%">Username</td><td width="5%">:</td><td><b>` + username + `</b></td></tr>
                                <tr><td>Nama</td><td>:</td><td><b>` + name + `</b></td></tr>
                                <tr><td colspan="3" style="height:20px"></td></tr>
                                <tr><td>Bank</td><td>:</td><td><b>` + bank + `</b></td></tr>
                                <tr><td>No. Rekening</td><td>:</td><td><b>` + bill + `</b></td></tr>
                                <tr><td>Pemilik Rek.</td><td>:</td><td><b>` + billnama + `</b></td></tr>
                            </table>`;

            bootbox.confirm({
                title: "",
                message: msg_body,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-danger'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Inquiry Bank',
                    }
                },
                callback: function (result) {
                    if (result == true) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            beforeSend: function () {
                                App.run_Loader('roundBounce');
                            },
                            success: function (response) {
                                App.close_Loader();
                                response = $.parseJSON(response);
                                if ( response.token ) {
                                    App.kdToken(response.token);
                                }
                                if (response.status == 'login') {
                                    $(location).attr('href', response.message);
                                } else {
                                    if (response.status == 'success') {
                                        var _type = 'success';
                                        var _icon = 'check';
                                        $('#btn_list_table_withdraw').trigger('click');
                                    } else {
                                        var _type = 'warning';
                                        var _icon = 'fa fa-exclamation-triangle';
                                    }
                                    App.alert({
                                        type: _type,
                                        icon: _icon,
                                        message: response.message,
                                        container: table_container,
                                        place: 'prepend'
                                    });
                                }
                                return false;
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                                    location.reload();
                                });
                            }
                        });
                    }
                }
            });
        });
        
        // Withdraw Confirm Inquiry Bank
        $("body").delegate("a.withdrawinquiryconfirm", "click", function (event) {
            event.preventDefault();
            var url             = $(this).attr('href');
            var username        = $(this).attr('username');
            var name            = $(this).attr('name');
            var bank            = $(this).attr('bank');
            var bill            = $(this).attr('bill');
            var billnama        = $(this).attr('billnama');
            var table_container = $('#list_table_withdraw').parents('.dataTables_wrapper');

            var msg_body = `<h4 class="pt-4 pb-3 text-center">Apakah Anda yakin akan Konfirmasi Inquiry Data Bank terbaru Member ?</h4>
                            <table width="90%" border="0" align="center">
                                <tr><td width="30%">Username</td><td width="5%">:</td><td><b>` + username + `</b></td></tr>
                                <tr><td>Nama</td><td>:</td><td><b>` + name + `</b></td></tr>
                                <tr><td colspan="3" style="height:20px"></td></tr>
                                <tr><td>Bank</td><td>:</td><td><b>` + bank + `</b></td></tr>
                                <tr><td>No. Rekening</td><td>:</td><td><b>` + bill + `</b></td></tr>
                                <tr><td>Pemilik Rek.</td><td>:</td><td><b>` + billnama + `</b></td></tr>
                            </table>`;

            bootbox.confirm({
                title: "",
                message: msg_body,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-danger'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm Inquiry',
                    }
                },
                callback: function (result) {
                    if (result == true) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            beforeSend: function () {
                                App.run_Loader('roundBounce');
                            },
                            success: function (response) {
                                App.close_Loader();
                                response = $.parseJSON(response);
                                if ( response.token ) {
                                    App.kdToken(response.token);
                                }
                                if (response.status == 'login') {
                                    $(location).attr('href', response.message);
                                } else {
                                    if (response.status == 'success') {
                                        var _type = 'success';
                                        var _icon = 'check';
                                        $('#btn_list_table_withdraw').trigger('click');
                                    } else {
                                        var _type = 'warning';
                                        var _icon = 'fa fa-exclamation-triangle';
                                    }
                                    App.alert({
                                        type: _type,
                                        icon: _icon,
                                        message: response.message,
                                        container: table_container,
                                        place: 'prepend'
                                    });
                                }
                                return false;
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                                    location.reload();
                                });
                            }
                        });
                    }
                }
            });
        });

        // Withdraw Confirm Transfer
        $("body").delegate("a.withdrawtransferfaspay", "click", function (event) {
            event.preventDefault();
            var url             = $(this).attr('href');
            var username        = $(this).attr('username');
            var name            = $(this).attr('name');
            var bank            = $(this).attr('bank');
            var bill            = $(this).attr('bill');
            var billnama        = $(this).attr('billnama');
            var nominal         = $(this).attr('nominal');
            var table_container = $('#list_table_withdraw').parents('.dataTables_wrapper');

            var msg_body = `<h4 class="pt-4 pb-3 text-center">Apakah Anda yakin akan transfer withdraw dengan Faspay ?</h4>
                            <table width="90%" border="0" align="center">
                                <tr><td width="30%">Username</td><td width="5%">:</td><td>` + username + `</td></tr>
                                <tr><td>Nama</td><td>:</td><td>` + name + `</td></tr>
                                <tr><td colspan="3" style="height:20px"></td></tr>
                                <tr><td>Bank</td><td>:</td><td>` + bank + `</td></tr>
                                <tr><td>No. Rekening</td><td>:</td><td>` + bill + `</td></tr>
                                <tr><td>Pemilik Rek.</td><td>:</td><td>` + billnama + `</td></tr>
                                <tr><th>Jumlah Transfer</th><td>:</td><th>` + nominal + `</th></tr>
                            </table>
                            <hr class="mt-2 mb-3">
                            <div class="row justify-content-center">
                                <form class="form-horizontal" id="form-withdraw-faspay">
                                    <div class="form-group mb-1">
                                        <div class="col-md-12">
                                            <div class="input-group input-group-merge">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                                </div>
                                                <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                                <div class="input-group-append">
                                                    <button class="btn btn-secondary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>`;

            bootbox.confirm({
                title: "",
                message: msg_body,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-danger'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Transfer By Faspay SendMe',
                    }
                },
                callback: function (result) {
                    if (result == true) {

                        var data = {};
                        var password = '';

                        if ($('#password_confirm', '#form-withdraw-faspay').length) {
                            password = $('#password_confirm', '#form-withdraw-faspay').val();
                            data.password = password;
                        }

                        if (password == "" || password == undefined) {
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                title: 'Failed', 
                                message: 'Password harus diisi !', 
                                type: 'warning',
                            });
                            $('#password_confirm').focus();
                            return false;
                        }

                        $.ajax({
                            type: "POST",
                            url: url,
                            beforeSend: function () {
                                App.run_Loader('roundBounce');
                            },
                            success: function (response) {
                                App.close_Loader();
                                response = $.parseJSON(response);
                                if ( response.token ) {
                                    App.kdToken(response.token);
                                }
                                if (response.status == 'login') {
                                    $(location).attr('href', response.message);
                                } else {
                                    if (response.status == 'success') {
                                        var _type = 'success';
                                        var _icon = 'check';
                                        $('#btn_list_table_withdraw').trigger('click');
                                    } else {
                                        var _type = 'danger';
                                        var _icon = 'fa fa-exclamation-triangle';
                                    }
                                    App.alert({
                                        type: _type,
                                        icon: _icon,
                                        message: response.message,
                                        container: table_container,
                                        place: 'prepend'
                                    });
                                }
                                return false;
                            },
                            error: function( jqXHR, textStatus, errorThrown ) {
                                App.close_Loader();
                                bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                                    location.reload();
                                });
                            }
                        });
                    }
                }
            });
        });

        // Reward Confirm
        $("body").delegate( "a.rewardconfirm", "click", function( event ) {
            event.preventDefault();
            var url             = $(this).data('url');
            var username        = $(this).data('username');
            var name            = $(this).data('name');
            var nominal         = $(this).data('nominal');
            var reward          = $(this).data('reward');

            var msg_body    = `
                <h4 class="pt-4 pb-3 text-center">Apakah anda yakin akan konfirmasi reward ini ?</h4>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Username :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Username :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${username} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Nama :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Nama :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${name} </small></div>
                </div>
                <hr class="my-2">
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Reward :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Reward :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${reward} </small></div>
                </div>
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Nominal :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Nominal :</small></div>
                    <div class="col-sm-6"><small class="heading-small text-warning font-weight-bold"> ${nominal} </small></div>
                </div>
                <hr class="mt-2 mb-3">
                <div class="row justify-content-center">
                    <form class="form-horizontal" id="form-reward-confirm">
                        <div class="form-group mb-1">
                            <div class="col-md-12">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>`;

            bootbox.confirm({
                title: "",
                message: msg_body,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-danger'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm',
                        className: 'btn-primary'
                    }
                },
                callback: function (result) {
                    if( result == true ){

                        var data = {};
                        var password = '';

                        if ($('#password_confirm', '#form-reward-confirm').length) {
                            password = $('#password_confirm', '#form-reward-confirm').val();
                            data.password = password;
                        }

                        if (password == "" || password == undefined) {
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                title: 'Failed', 
                                message: 'Password harus diisi !', 
                                type: 'warning',
                            });
                            $('#password_confirm').focus();
                            return false;
                        }

                        $.ajax({
                            type:   "POST",
                            data:   data,
                            url:    url,
                            beforeSend: function (){
                                App.run_Loader('roundBounce');
                            },
                            success: function( response ){  
                                App.close_Loader();
                                response    = $.parseJSON(response);
                                if( response.status == 'login'){
                                    $(location).attr('href',response.url);
                                } else {
                                    if( response.status == 'success'){
                                        var _type = 'success';
                                        var _icon = 'fa fa-check';
                                        $('#btn_list_table_reward').trigger('click');
                                    }else{
                                        var _type = 'danger';
                                        var _icon = 'fa fa-exclamation-circle';
                                    }

                                    App.notify({
                                        icon: _icon, 
                                        message: response.message, 
                                        type: _type,
                                    });
                                }
                                return false;
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
                }
            });
        });
    };

    // Handle Button Confirm
    var handleActionDetail = function() {
        // Daily Omzet Posting Detail 
        $("body").delegate( "a.omzetpostingdailydetail", "click", function( event ) {
            event.preventDefault();

            var url         = $(this).attr('href');
            var id          = $(this).data('id');
            var parentrow   = $(this).parent().parent().parent();
            var el_tr       = $('tr.posting_daily_id_' +  id);
            var wrapper     = $('#omzet_posting_daily_list');

            if( $(el_tr).length ){
                if( $(el_tr).is(':visible') ){
                    $(el_tr).hide();
                }else{
                    $(el_tr).show();
                }
            }else{
                $.ajax({
                    type:   "POST",
                    url:    url,
                    beforeSend: function (){
                        App.run_Loader('roundBounce');
                    },
                    success: function( response ){
                        App.close_Loader();
                        response = $.parseJSON(response);

                        if( response.status == 'login' ){
                            $(location).attr('href',response.message);
                        }else{
                            if( response.status == 'error'){
                                App.alert({
                                    type: 'danger',
                                    icon: 'warning',
                                    message: response.message,
                                    container: wrapper,
                                    place: 'prepend'
                                });
                            }else{
                                parentrow.after(response.detail);
                            }
                        }
                    }
                });
            }

            if( $(this).hasClass('bg-blue') ){
                $(this).removeClass('bg-blue').addClass('btn-danger');
                $(this).find("i").removeClass('fa-plus').addClass('fa-minus');
            }else{
                $(this).removeClass('btn-danger').addClass('bg-blue');
                $(this).find("i").removeClass('fa-minus').addClass('fa-plus');
            }
            return false;
        });
        
        // Monthly Omzet Posting Detail 
        $("body").delegate( "a.omzetpostingmonthlydetail", "click", function( event ) {
            event.preventDefault();

            var url         = $(this).attr('href');
            var id          = $(this).data('id');
            var parentrow   = $(this).parent().parent().parent();
            var el_tr       = $('tr.posting_monthly_id_' +  id);
            var wrapper     = $('#omzet_posting_monthly_list');

            if( $(el_tr).length ){
                if( $(el_tr).is(':visible') ){
                    $(el_tr).hide();
                }else{
                    $(el_tr).show();
                }
            }else{
                $.ajax({
                    type:   "POST",
                    url:    url,
                    beforeSend: function (){
                        App.run_Loader('roundBounce');
                    },
                    success: function( response ){
                        App.close_Loader();
                        response = $.parseJSON(response);

                        if( response.status == 'login' ){
                            $(location).attr('href',response.message);
                        }else{
                            if( response.status == 'error'){
                                App.alert({
                                    type: 'danger',
                                    icon: 'warning',
                                    message: response.message,
                                    container: wrapper,
                                    place: 'prepend'
                                });
                            }else{
                                parentrow.after(response.detail);
                            }
                        }
                    }
                });
            }

            if( $(this).hasClass('bg-blue') ){
                $(this).removeClass('bg-blue').addClass('btn-danger');
                $(this).find("i").removeClass('fa-plus').addClass('fa-minus');
            }else{
                $(this).removeClass('btn-danger').addClass('bg-blue');
                $(this).find("i").removeClass('fa-minus').addClass('fa-plus');
            }
            return false;
        });
        
        // Monthly Omzet Posting Detail 
        $("body").delegate( "a.btn-grade-upgrade-detail", "click", function( event ) {
            event.preventDefault();
            var modal_detail    = $('#modal-grade-upgrade-detail');
            var table_detail    = $('#table-grade-upgrade-detail');
            var tbody_detail    = $('tbody', table_detail);
            var url             = $(this).data('url');
            $.ajax({
                type:   "POST",
                url:    url,
                beforeSend: function (){
                    App.run_Loader('roundBounce');
                    tbody_detail.remove();
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
                            if ( response.tbody ) {
                                table_detail.append(response.tbody);
                            }
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
                    bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                        location.reload();
                    });
                }
            });
            return false;
        });

        // 
        $("body").delegate( "a.btn-grade-maintain-detail", "click", function( event ) {
            event.preventDefault();
            var modal_detail    = $('#modal-grade-maintenance-detail');
            var table_detail    = $('#table-grade-maintenance-detail');
            var tbody_detail    = $('tbody', table_detail);
            var url             = $(this).data('url');
            $.ajax({
                type:   "POST",
                url:    url,
                beforeSend: function (){
                    App.run_Loader('roundBounce');
                    tbody_detail.remove();
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
                            if ( response.tbody ) {
                                table_detail.append(response.tbody);
                            }
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
                    bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                        location.reload();
                    });
                }
            });
            return false;
        });
    };

    var handleActionGeneral = function() {
        $( '#btn_copy_referral_link' ).click( function(){
            var wrapper         = $('.alert-wrapper-copy-referral');
            var copyText        = document.getElementById("referral_link");
            
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
             
            App.alert({
                type: 'success', 
                icon: 'check', 
                message: 'Copied the Reseller Rekomen Info Link : ' + copyText.value, 
                container: wrapper, 
                place: 'prepend',
                closeInSeconds: 5,
            });
        });
        
        $( '#btn_copy_referral_link_store' ).click( function(){
            var wrapper         = $('.alert-wrapper-copy-referral');
            var copyText        = document.getElementById("referral_link_store");
            
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
             
            App.alert({
                type: 'success', 
                icon: 'check', 
                message: 'Copied the Reseller Rekomen Store Link : ' + copyText.value, 
                container: wrapper, 
                place: 'prepend',
                closeInSeconds: 5,
            });
        });
        
        // As Stockist
        $("body").delegate( "a.btn-as-status-member", "click", function( event ) {
            event.preventDefault();
            App.run_Loader('timer');
            var url         = $(this).attr('href');
            $.ajax({
                type:   "POST",
                url:    url,
                beforeSend: function (){
                    App.run_Loader('timer');
                },
                success: function( response ){
                    App.close_Loader();
                    response = $.parseJSON(response);

                    if ( response.token ) {
                        App.kdToken(response.token);
                    }

                    if( response.status == 'login' ){
                        $(location).attr('href',response.message);
                    }else{
                        if( response.status == 'success'){
                            $('.change-stockist', '#modal_select_stockist').val('');
                            $('.change-stockist-username').val(response.member.username);
                            $('.change-stockist-name').val(response.member.name);
                            //$('select[name="stockist_status"]').val(response.member.status_member);
                            $('select[name="stockist_province"]').val(response.member.id_province);
                            if ( response.member.opt_district ) {
                                $('select[name="stockist_district"]').empty().append(response.member.opt_district);
                                $('select[name="stockist_district"]').val(response.member.id_district);
                            }
                            if ( response.member.opt_subdistrict ) {
                                $('select[name="stockist_subdistrict"]').empty().append(response.member.opt_subdistrict);
                                $('select[name="stockist_subdistrict"]').val(response.member.id_subdistrict);
                            }
                            $('input[name="stockist_village"]').val(response.member.village);
                            $('input[name="stockist_address"]').val(response.member.address);
                            $('#asmember').val(response.member.id);
                            $('#asmember').val(response.member.id);
                            $('#alert_form_stockist').hide();
                            $('#modal_select_stockist').modal('show');
                        }else{
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                message: response.message, 
                                type: 'warning',
                            });
                        }
                    }
                },
                error: function( jqXHR, textStatus, errorThrown ) {
                    App.close_Loader();
                    bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.');
                }
            });
            return false;
        });

        // As Banned
        $("body").delegate( "a.asbanned", "click", function( event ) {
            event.preventDefault();
            var url = $(this).attr('href');
            var container   = $(this).data('container');

            bootbox.confirm("Anda yakin akan Banned member ini?", function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            if ( container == 'registration_list')    { $('#btn_member_lists').trigger('click'); }
                            if ( container == 'member_stockist_list') { $('#btn_member_stockist_list').trigger('click'); }
                            $('#btn_member_banned_list').trigger('click');
                        }
                    });
                }
            });
        });

        // As Active
        $("body").delegate( "a.asactive", "click", function( event ) {
            event.preventDefault();
            var url = $(this).attr('href');
            
            bootbox.confirm("Anda yakin akan aktifkan status member ini?", function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            $('#btn_member_banned_list').trigger('click'); 
                            $('#btn_member_lists').trigger('click');
                        }
                    });
                }
            });
        });

        // Button Deposite Loan
        $("body").delegate( "#btn-modal-loan-deposite", "click", function( event ) {
            event.preventDefault();
            const _modal    = $('#modal-form-loan-deposite');
            const _form     = $('#form-loan-deposite');

            if ( !_modal.length || !_form.length ) {
                return;
            }

            _form[0].reset();
            $('input.form-control', _form).val('');
            _modal.modal({backdrop: 'static', keyboard: false, show: true});
        });

        // Button Withdraw Loan
        $("body").delegate( "#btn-modal-loan-withdraw", "click", function( event ) {
            event.preventDefault();
            const _modal    = $('#modal-form-loan-withdraw');
            const _form     = $('#form-loan-withdraw');

            if ( !_modal.length || !_form.length ) {
                return;
            }

            _form[0].reset();
            $('input.form-control', _form).val('');
            _modal.modal({backdrop: 'static', keyboard: false, show: true});
        });

        // Button Withdraw
        $("body").delegate( "#btn-modal-withdraw", "click", function( event ) {
            event.preventDefault();
            const _modal    = $('#modal-form-withdraw');
            const _form     = $('#form-withdraw');

            if ( !_modal.length || !_form.length ) {
                return;
            }

            _form[0].reset();
            $('input[name="nominal"]', _form).val('');
            _modal.modal({backdrop: 'static', keyboard: false, show: true});
        });

        // Button Flip Topup
        $("body").delegate( "#btn-modal-flip-topup", "click", function( event ) {
            event.preventDefault();
            const _modal    = $('#modal-form-flip-topup');
            const _form     = $('#form-flip-topup');

            if ( !_modal.length || !_form.length ) {
                return;
            }

            _form[0].reset();
            $('input[name="nominal"]', _form).val('');
            _modal.modal({backdrop: 'static', keyboard: false, show: true});
        });
    };

    return {
        init: function() {
            handleActionDelete();
            handleActionConfirm();
            handleActionDetail();
            handleActionGeneral();

            $("body").delegate( "input#password_confirm", "keypress", function( e ) {
                var key = e.which;
                if(key == 13){ 
                    if ( $('.btn-primary', '.bootbox-confirm').length ) {
                        $('.btn-primary', '.bootbox-confirm').trigger('click');
                    }
                    return false;
                }
            });
        },
    };
}();

// ===========================================================
// Select Change Action
// ===========================================================
var SelectChange = function() {
    // Handle Province Change Function
    // --------------------------------------------------------------------------
    var handleProvinceChange = function() {
        // Province Change
        $('.select_province, .select_province_current').change(function(e){
            var val         = $(this).val();
            var url         = $(this).data('url');
            var form        = $(this).data('form');
            var el_change   = $(this).data('el');
            var el_dist     = $('.select_district');
            var el_subdist  = $('.select_subdistrict');
            var el_village  = $('.select_village');
            var el_courier  = $('#select_courier');
            var el_service  = $('#select_service');
            var ids         = $(this).children("option:selected").data('id');

            if ( ids ) { val = ids; }

            if ( el_courier.length ) {
                el_courier.val('');
            }

            if ( el_service.length ) {
                el_service.empty();
            }

            if ( el_change == 'select_current' ) {
                el_dist     = $('.select_district_current');
                el_subdist  = $('.select_subdistrict_current');
            }

            if ( url ) {
                $.ajax({
                    type: "POST",
                    data: { 'province' : val },
                    url: url,
                    beforeSend: function (){},
                    success: function( response ){
                        response = $.parseJSON(response);
                        if ( el_dist.length ) {
                            el_dist.empty();
                            el_dist.removeAttr('disabled');
                            el_dist.parent().removeClass('has-danger');
                            el_dist.parent().find('.invalid-feedback').empty().hide();
                            el_dist.html(response.data);
                        }

                        if ( el_subdist.length ) {
                            el_subdist.empty();
                            if ( response.subdistrict != "" || response.subdistrict != undefined) {
                                el_subdist.html(response.subdistrict);
                            }
                        }

                        if ( el_village.length ) {
                            el_village.empty();
                            if ( response.village != "" || response.village != undefined) {
                                el_village.html(response.village);
                            }
                        }
                    }
                });
            }
            return false;
        });
    };

    // Handle District Change Function
    // --------------------------------------------------------------------------
    var handleDistrictChange = function() {
        // District Change
        $('.select_district, .select_district_current').change(function(e){
            var val         = $(this).val();
            var url         = $(this).data('url');
            var form        = $(this).data('form');
            var el_change   = $(this).data('el');
            var el_subdist  = $('.select_subdistrict');
            var el_village  = $('.select_village');
            var el_courier  = $('#select_courier');
            var el_service  = $('#select_service');
            var ids         = $(this).children("option:selected").data('id');

            if ( ids ) { val = ids; }

            if ( el_courier.length ) {
                el_courier.val('');
            }

            if ( el_service.length ) {
                el_service.empty();
            }

            if ( el_change == 'select_current' ) {
                el_subdist  = $('.select_subdistrict_current');
            }

            if ( url ) {
                $.ajax({
                    type: "POST",
                    data: { 'district' : val },
                    url: url,
                    beforeSend: function (){},
                    success: function( response ){
                        response = $.parseJSON(response);
                        if ( el_subdist.length ) {
                            el_subdist.empty();
                            el_subdist.removeAttr('disabled');
                            el_subdist.parent().removeClass('has-danger');
                            el_subdist.parent().find('.invalid-feedback').empty().hide();
                            el_subdist.html(response.data);
                        }

                        if ( el_village.length ) {
                            el_village.empty();
                            if ( response.village != "" || response.village != undefined) {
                                el_village.html(response.village);
                            }
                        }
                    }
                });
            }
            return false;
        });
    };

    // Handle Subdistrict Change Function
    // --------------------------------------------------------------------------
    var handleSubdistrictChange = function() {
        // Subdistrict Change
        $('.select_subdistrict').change(function(e){
            var val         = $(this).val();
            var url         = $(this).data('url');
            var form        = $(this).data('form');
            var el_village  = $('.select_village');
            var el_courier  = $('#select_courier');
            var el_service  = $('#select_service');

            if ( el_courier.length ) {
                el_courier.val('');
            }

            if ( el_service.length ) {
                el_service.empty();
            }

            if ( url ) {
                $.ajax({
                    type: "POST",
                    data: { 'subdistrict' : val },
                    url: url,
                    beforeSend: function (){},
                    success: function( response ){
                        response = $.parseJSON(response);
                        if ( el_village.length ) {
                            el_village.empty();
                            el_village.removeAttr('disabled');
                            el_village.parent().removeClass('has-danger');
                            el_village.parent().find('.invalid-feedback').empty().hide();
                            el_village.html(response.data);
                        }
                    }
                });
            }
            return false;
        });
    };

    // Handle Subdistrict Change Function
    // --------------------------------------------------------------------------
    var handleCourierChange = function() {
        // Select Courier 
        // -----------------------------------------------
        $("body").delegate( "#select_courier", "change", function( e ) {
            e.preventDefault();
            var url         = $(this).data('url');
            var courier     = $(this).val();
            var province    = $('.select_province').val();
            var district    = $('.select_district').val();
            var subdistrict = $('.select_subdistrict').val();
            var products    = $('.input-products');
            var el_service  = $('#select_service');

            if ( courier != 'pickup' && courier != 'ekspedisi' ) {
                if ( province == '' || province == 0 || province == undefined ) {
                    App.notify({
                        icon: 'fa fa-exclamation-triangle', 
                        message: 'Provinsi belum di pilih. Silahkan pilih provinsi terlebih dahulu !', 
                        type: 'danger',
                    });
                    $("#select_courier").val('');
                    return false;
                }

                if ( district == '' || district == 0 || district == undefined ) {
                    App.notify({
                        icon: 'fa fa-exclamation-triangle', 
                        message: 'Kab/Kota belum di pilih. Silahkan pilih Kab/Kota terlebih dahulu !', 
                        type: 'danger',
                    });
                    $("#select_courier").val('');
                    return false;
                }

                if ( subdistrict == '' || subdistrict == 0 || subdistrict == undefined ) {
                    App.notify({
                        icon: 'fa fa-exclamation-triangle', 
                        message: 'Kecamatan belum di pilih. Silahkan pilih Kecamatan terlebih dahulu !', 
                        type: 'danger',
                    });
                    $("#select_courier").val('');
                    return false;
                }
            }

            var form_data = {
                courier: courier,
                province: province,
                district: district,
                subdistrict: subdistrict,
            };

            shipping_fee = 0;
            $('#courier_cost').val(shipping_fee);
            $('.shipping_fee').text(App.formatCurrency(shipping_fee));

            if ( courier ) {
                $.ajax({
                    type:   "POST",
                    url:    url,
                    data:   form_data,
                    beforeSend: function (){
                        App.run_Loader('timer');
                    },
                    success: function( resp ){
                        App.close_Loader();
                        response = $.parseJSON(resp);
                        var _icon = 'fa fa-exclamation-triangle';
                        var _type = 'danger';

                        if( response.login == 'access_denied' ){
                            $(location).attr('href',response.url);
                        }else{
                            if(response.status == 'success'){
                                var _icon = 'fa fa-check';
                                var _type = 'success';
                            }

                            if ( el_service.length ) {
                                el_service.empty();
                                el_service.removeAttr('disabled');
                                el_service.parent().removeClass('has-danger');
                                el_service.parent().find('.invalid-feedback').empty().hide();
                                el_service.html(response.data);
                            }

                            App.notify({
                                icon: _icon, 
                                message: response.message, 
                                type: _type,
                            });
                            App.scrollTo($('#select_courier'), 0);
                            $('#select_courier').val(courier);
                            $('#select_service').trigger('change');
                        }
                    },
                    error: function( jqXHR, textStatus, errorThrown ) {
                        App.close_Loader();
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            message: 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', 
                            type: 'danger',
                        });
                    }
                });
            }
            return false;
        });

        // Select Courier Service 
        // -----------------------------------------------
        $("body").delegate( "#select_service", "change", function( e ) {
            e.preventDefault();
            var cost        = $('select[name="select_service"] option:selected').data('cost');
            var payment     = $('form').data('subtotal');
            var total       = 0;
            if ( cost ) {
                shipping_fee = parseInt(cost);
            } else {
                shipping_fee = 0;
            }

            if ( payment ) {
                total       = parseInt(shipping_fee) + parseInt(payment);
            }

            $('#courier_cost').val(shipping_fee);
            $('.shipping_fee').text(App.formatCurrency(shipping_fee));
            $('.total_payment').text(App.formatCurrency(total, true));
            return false;
        });
    };

    return {
        init: function() {
            handleProvinceChange();
            handleDistrictChange();
            handleSubdistrictChange();
        },
        initCourier: function() {
            handleCourierChange();
        }
    };
}();

// ===========================================================
// Search Action
// ===========================================================
var SearchAction = function() {
    // Handle Search Member Function
    // --------------------------------------------------------------------------
    var handleSearchMember = function() {
        // Search Sponsor
        $('input.search_member').bind('blur', function(e){
            e.preventDefault();
            var _form = $(this).parents('form');
            if ( _form.length ) {
                if ( $('#btn_search_member', _form).length ) {
                    $('#btn_search_member', _form).trigger('click');
                }
                if ( $('.btn_search_member', _form).length ) {
                    $('.btn_search_member', _form).trigger('click');
                }
            } else {
                if ( $('#btn_search_member').length ) {
                    $('#btn_search_member').trigger('click');
                }
                if ( $('.btn_search_member').length ) {
                    $('.btn_search_member').trigger('click');
                }
            }
        });

        $("body").delegate( "#btn_search_member, .btn_search_member", "click", function( e ) {
            e.preventDefault();
            var member      = $('input.search_member').val();
            var url         = $(this).data('url');
            var type        = $(this).data('type');
            var form        = $(this).data('form');
            var inputid     = $(this).data('inputid');
            var el          = $('#member_info');
            var wrapper     = $('.card-body');
            var search      = true;

            if ( inputid != '' && inputid != undefined ) {
                if ( $('input#'+inputid).length ) {
                    member  = $('input#'+inputid).val();
                }
            }

            if ( member == '' ) {
                search      = false;
                if ( $(el).length ) {
                    $(el).empty().hide();
                }
                $('input.search_member').val('');
            }

            if ( $('input[name="member_username"]').length ) {
                if ( $('input[name="member_username"]').val() == member ) {
                    search  = false;
                }
            }

            if ( search ) {
                $.ajax({
                    type:   "POST",
                    data:   { 'username' : member, 'type' : type, 'form' : form },
                    url:    url,
                    beforeSend: function (){
                        App.run_Loader('timer');
                    },
                    success: function( response ){
                        App.close_Loader();
                        response = $.parseJSON(response);

                        if ( response.token ) {
                            App.kdToken(response.token);
                        }

                        if( response.status == 'login' ){
                            $(location).attr('href',response.message);
                        }else{
                            if( response.status == 'success'){
                                App.notify({
                                    icon: 'fa fa-check-circle', 
                                    message: response.message, 
                                    type: 'success',
                                });
                                if ( $(el).length ) {
                                    $(el).html(response.info).fadeIn('fast');
                                }
                                if ( form == 'pin_generate' ) {
                                    if ( response.data ) {
                                        if ( $('input[name="name"]').length ) {
                                            $('input[name="name"]').val(response.data.name);
                                        }
                                        if ( $('input[name="phone"]').length ) {
                                            $('input[name="phone"]').val(response.data.phone);
                                        }
                                        if ( $('input[name="email"]').length ) {
                                            $('input[name="email"]').val(response.data.email);
                                        }
                                        if ( $('input[name="village"]').length ) {
                                            $('input[name="village"]').val(response.data.village);
                                        }
                                        if ( $('input[name="address"]').length ) {
                                            $('input[name="address"]').val(response.data.address);
                                        }
                                        if ( $('select[name="province"]').length ) {
                                            $('select[name="province"]').val(response.data.id_province);
                                        }
                                        if ( $('select[name="district"]').length && response.data.opt_district ) {
                                            $('select[name="district"]').empty().append(response.data.opt_district);
                                            $('select[name="district"]').val(response.data.id_district);
                                            $('select[name="district"]').removeAttr('disabled');
                                        }
                                        if ( $('select[name="subdistrict"]').length && response.data.opt_subdistrict ) {
                                            $('select[name="subdistrict"]').empty().append(response.data.opt_subdistrict);
                                            $('select[name="subdistrict"]').val(response.data.id_subdistrict);
                                            $('select[name="subdistrict"]').removeAttr('disabled');
                                        }
                                    }
                                    InputMask.init();
                                }

                                if ( type == 'data' && response.data ) {
                                    if ( $('input[name="member_name"]').length && response.data.name ) {
                                        $('input[name="member_name"]').val(response.data.name);
                                    }

                                    if ( $('input[name="deposite"]').length && response.data.saldo_deposite ) {
                                        $('input[name="deposite"]').val(response.data.saldo_deposite);
                                    }
                                }
                            }else{
                                App.notify({
                                    icon: 'fa fa-exclamation-triangle', 
                                    message: response.message, 
                                    type: 'danger',
                                });
                                if ( $(el).length ) {
                                    $(el).empty().hide();
                                }
                                $('input.search_member').val('');
                                $('input.search_member').focus();

                                if ( type == 'data' && $('input[name="member_name"]').length ) {
                                    $('input[name="member_name"]').val('');
                                }
                            }
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
            return false;
        });
    };

    // Handle Search Sponsor Function
    // --------------------------------------------------------------------------
    var handleSearchSponsor = function() {
        // Search Sponsor
        $('#reg_member_sponsor').bind('blur', function(){
            $('#btn_search_sponsor').trigger('click');
        });

        $("body").delegate( "#btn_search_sponsor", "click", function( e ) {
            e.preventDefault();
            var upline      = '';
            var sponsor     = $('#reg_member_sponsor').val();
            var url         = $(this).data('url');
            var el          = $('#sponsor_info');
            var wrapper     = $('.register_body_wrapper');
            var search      = true;

            if ( sponsor == '' ) {
                search      = false;
                $(el).empty().hide();
                $('#reg_member_sponsor').val('');
            }

            if ( $('input[name="reg_member_sponsor_username"]').length ) {
                if ( $('input[name="reg_member_sponsor_username"]').val() == sponsor ) {
                    search  = false;
                }
            }

            if ( $('input[name="reg_member_upline"]').length ) {
                upline      = $('input[name="reg_member_upline"]').val();
            }

            if ( search ) {
                $.ajax({
                    type:   "POST",
                    data:   { 'username' : sponsor, 'upline' : upline },
                    url:    url,
                    beforeSend: function (){
                        App.run_Loader('roundBounce');
                    },
                    success: function( response ){
                        App.close_Loader();
                        response = $.parseJSON(response);
                        App.scrollTo($('#reg_member_sponsor'), -100);

                        if( response.status == 'login' ){
                            $(location).attr('href',response.message);
                        }else{
                            if( response.status == 'success'){
                                App.notify({
                                    icon: 'fa fa-check-circle', 
                                    title: 'Success', 
                                    message: response.message, 
                                    type: 'success',
                                });
                                $(el).html(response.info).fadeIn('fast');
                            }else{
                                App.notify({
                                    icon: 'fa fa-exclamation-triangle', 
                                    title: 'Failed', 
                                    message: response.message, 
                                    type: 'danger',
                                });
                                $(el).empty().hide();
                                $('#reg_member_sponsor').val('');
                            }
                        }
                    }
                });
            }
            return false;
        });
    };

    // Handle Search Upline Function
    // --------------------------------------------------------------------------
    var handleSearchUpline = function() {
        // Search Sponsor
        $('#reg_member_upline').bind('blur', function(){
            $('#btn_search_upline').trigger('click');
        });

        $("body").delegate( "#btn_search_upline", "click", function( e ) {
            e.preventDefault();
            var upline      = $('#reg_member_upline').val();
            var url         = $(this).data('url');
            var el          = $('#upline_info');
            var wrapper     = $('.register_body_wrapper');
            var search      = true;

            if ( upline == '' ) {
                search      = false;
                $(el).empty().hide();
                $('#reg_member_upline').val('');
            }

            if ( $('input[name="reg_member_upline_username"]').length ) {
                if ( $('input[name="reg_member_upline_username"]').val() == upline ) {
                    search  = false;
                }
            }

            if ( search ) {
                $.ajax({
                    type:   "POST",
                    data:   { 'username' : upline },
                    url:    url,
                    beforeSend: function (){
                        App.run_Loader('roundBounce');
                    },
                    success: function( response ){
                        App.close_Loader();
                        response = $.parseJSON(response);
                        App.scrollTo($('#reg_member_upline'), -100);

                        if( response.status == 'login' ){
                            $(location).attr('href',response.message);
                        }else{
                            if( response.status == 'available'){
                                App.notify({
                                    icon: 'fa fa-check-circle', 
                                    title: 'Success', 
                                    message: response.message, 
                                    type: 'success',
                                });
                                $(el).html(response.info).fadeIn('fast');
                                if ( $('#sponsor_info').length ) {
                                    $('#sponsor_info').empty().hide();
                                    if ( $('#reg_member_sponsor').length ) {
                                        $('#reg_member_sponsor').val('');
                                    }
                                }
                            }else{
                                App.notify({
                                    icon: 'fa fa-exclamation-triangle', 
                                    title: 'Failed', 
                                    message: response.message, 
                                    type: 'danger',
                                });
                                $(el).empty().hide();
                                $('#reg_member_upline').val('');
                            }
                        }
                    }
                });
            }
            return false;
        });
    };

    // Handle Search Tree Function
    // --------------------------------------------------------------------------
    var handleSearchTree = function() {

        var form_search = $('#form-search-member-tree');

        if ( ! form_search.length )
            return;

        var url_search  = form_search.data('url');

        // Search Sponsor
        $('#search_member_tree').bind('blur', function(e){
            e.preventDefault();
            form_search.submit();
        });

        $("body").delegate( "#btn_search_member_tree", "click", function( e ) {
            e.preventDefault();
            form_search.submit();
        });

        $("body").delegate( form_search, "submit", function( e ) {
            e.preventDefault();
            var username    = $('#search_member_tree').val();
            var search      = true;

            if ( username == '' ) {
                search      = false;
            }

            if ( search ) {
                $.ajax({
                    type:   "POST",
                    data:   { 'username' : username },
                    url:    url_search,
                    beforeSend: function (){
                        App.run_Loader('roundBounce');
                    },
                    success: function( response ){
                        App.close_Loader();
                        response = $.parseJSON(response);

                        if( response.status == 'login' ){
                            $(location).attr('href',response.message);
                        }else{
                            if( response.status == 'success'){
                                $(location).attr('href', response.direct);
                            }else{
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
            }
            return false;
        });
    };

    // Handle Search Board Tree Function
    // --------------------------------------------------------------------------
    var handleSearchBoardTree = function() {

        var form_search = $('#form-search-member-board-tree');

        if ( ! form_search.length )
            return;

        var url_search  = form_search.data('url');

        // Search Sponsor
        $('#search_member_board_tree').bind('blur', function(){
            form_search.submit();
        });

        $("body").delegate( form_search, "submit", function( e ) {
            e.preventDefault();
            var username    = $('#search_member_board_tree').val();
            var search      = true;

            if ( username == '' ) {
                search      = false;
            }

            if ( search ) {
                $.ajax({
                    type:   "POST",
                    data:   { 'username' : username },
                    url:    url_search,
                    beforeSend: function (){
                        App.run_Loader('roundBounce');
                    },
                    success: function( response ){
                        App.close_Loader();
                        response = $.parseJSON(response);

                        if( response.status == 'login' ){
                            $(location).attr('href',response.message);
                        }else{
                            if( response.status == 'success'){
                                $(location).attr('href', response.direct);
                            }else{
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
            }
            return false;
        });
    };

    // Handle Search Upline Function
    // --------------------------------------------------------------------------
    var handleSearchGenerationMember = function() {
        // Search Member
        var form_search = $('#form-search-generation-member');

        if ( ! form_search.length )
            return;

        var url_search  = form_search.data('url');

        $('#search_generation_member').bind('blur', function(){
            var username    = $(this).val();
            var direct      = url_search +'/'+ username;
            if ( username != "" ) {
                $(location).attr('href', direct);
            }
            return false;
        });

        $("body").delegate( "#btn_search_generation_member", "click", function( e ) {
            e.preventDefault();
            form_search.submit();
        });

        $("body").delegate( form_search, "submit", function( e ) {
            e.preventDefault();
            var username    = $('#search_generation_member').val();
            var direct      = url_search +'/'+ username;
            if ( username != "" ) {
                $(location).attr('href', direct);
            }
            return false;
        });
    };

    return {
        init: function() {
            handleSearchMember();
            handleSearchSponsor();
            handleSearchUpline();
            handleSearchTree();
            handleSearchBoardTree();
            handleSearchGenerationMember();
        }
    };
}();

// ===========================================================
// Get Product Function
// ===========================================================
var GetProduct = function() {
    var selectProduct       = $('.select_product');
    var selectProductList   = $('#select_product_list');
    var access              = '';
    var type                = '';
    var form                = '';

    if ( selectProduct.length ) {
        access              = $(selectProduct).data('access');
        type                = $(selectProduct).data('type');
        form                = $(selectProduct).data('form');
    }

    var _dataProduct        = [];
    var _dataPINList        = [];

    // ---------------------------------
    // Handle Get Product Record
    // ---------------------------------
    var handleGetProductRecord = function() {
        if ( !selectProduct.length ) {
            return;
        }

        var load    = $(selectProduct).data('load');
        var code    = $(selectProduct).data('code');
        var type    = $(selectProduct).data('type');

        if ( load ) {
            API.get( load, { id_member: code, type: type, form: form }, function( response ) {
                if ( ! response.success ) {
                    if( response.status == 'access_denied' ){
                        $(location).attr('href',response.url);
                    }
                }                            

                _dataProduct = [];

                if ( response.data ) {
                    $.each(response.data, function(index, val) {
                         _dataProduct.push(val)
                    });
                }

                _dataPINList = Object.assign([], _dataProduct);
                handleGenerateSelectProduct();

            });
        } else {
            _dataProduct = [];
            _dataPINList = Object.assign([], _dataProduct);
            handleGenerateSelectProduct();
        }
    };

    // ---------------------------------
    // Handle Generate Select Product
    // ---------------------------------
    var handleGenerateSelectProduct = function() {
        handleSortProductRecord();
        selectProduct.empty().append('<option value="">Pilih</option>');
        $.each(_dataPINList, function(index, val) {
            selectProduct.append('<option value="' + val.id + '" data-stock="' + val.stock + '">' + val.name + '</option>');
        });
    };

    // ---------------------------------
    // Handle Sort Product Record
    // ---------------------------------
    var handleSortProductRecord = function() {
        _dataPINList.sort(function(a, b) {
            return a.order - b.order;
        });
    };

    // ---------------------------------
    // Handle Append Product
    // ---------------------------------
    var handleAppendProduct = function(productId) {
        var index   = _dataPINList.findIndex(x => x.id == productId);
        var data    = _dataPINList[index];
        var html    =   `<div class="form-group row mb-2" data-id="${data.id}">
                            <label class="col-md-3 col-form-label form-control-label" style="padding:10px 15px 0px; margin-top:0;">
                                ${data['name']} <br>
                                <small class="text-success" style="position:relative; top:-5px;">
                                    <strong>${App.formatCurrency(data.bv)} BV</strong>
                                </small>
                            </label>
                            <div class="col-md-5">
                                <div class="input-group">`;
        // Type Product
        if ( type == 'register' ) {
            html   +=               `<input type="text" name="products[${data.id}]" id="products_${data.id}" data-code="${data.id}" data-bv="${data.bv}" data-price="${data.price}" data-weight="${data.weight}" data-max="${data.stock}" class="form-control numbermask calculate-pin" placeholder="0" autocomplete="off">`;
        } else {
            html   +=               `<input type="text" name="products[${data.id}]" id="products_${data.id}" data-code="${data.id}" data-bv="${data.bv}" data-price="${data.price}" data-weight="${data.weight}" data-max="${data.stock}" class="form-control numbermask calculate-pin" placeholder="0" autocomplete="off">`;
        }
        // Stock Product
        if ( access != 'admin' ) {
            html   += `             <span class="input-group-append">
                                        <span class="input-group-text">Tersedia <span class="badge bg-primary ml-1 text-white">${App.formatCurrency(data.stock)}</span></span>
                                    </span>`;
        }

        html   += `                 <span class="input-group-append">
                                        <button class="btn btn-secondary btn-tooltip remove_product" type="button" data-product="${data.id}" data-original-title="Hapus Produk"><i class="fa fa-times text-danger"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>`;
                        
        _dataPINList.splice(index, 1);
        handleGenerateSelectProduct();
        selectProductList.append(html);
        $(".calculate-pin").inputmask({"mask": "9","repeat": 30,"greedy": false});
        setRuleValidateProduct();
        calculateTotalProduct();
    }

    var setRuleValidateProduct = function() {
        if ( form ) {
            var form_input = $('#'+form);
            if ( form_input.length ) {
                if ( $( ':input.calculate-pin', form_input ).length ) {
                    $( ':input.calculate-pin', form_input ).rules( "remove" );
                    $( ':input.calculate-pin', form_input ).each( function() {
                        var max = $( this ).data( 'max' );
                        $( this ).rules( "add", {
                            number: true,
                            min: 0,
                            messages: {
                                number: 'Mohon gunakan nilai numerik',
                                min: $.validator.format( 'Mohon gunakan jumlah tidak kurang dari {0}' ),
                            }
                        });
                    });
                }
            }
        }
        return false;
    };

    // ---------------------------------
    // Handle Add Product
    // ---------------------------------
    var handleAddProduct = function() {
        if ( !selectProduct.length ) {
            return;
        }

        var productId   = selectProduct.val();
        if( ! productId ) {
            bootbox.alert('Silahkan Pilih product')
            return false
        }

        if( $('[data-id="'+productId+'"]', selectProductList).length ) {
            bootbox.alert('Produk ini sudah ada')
            return false;
        }

        handleAppendProduct(productId);
    };

    // ---------------------------------
    // Handle Remove Product
    // ---------------------------------
    var handleRemoveProduct = function(productId = '') {
        if ( productId ) {
            var index = _dataProduct.findIndex(x => x.id == productId);
            var data  = _dataProduct[index];
            $('[data-id="'+productId+'"]', selectProductList).remove();
            _dataPINList.push(data);
            handleGenerateSelectProduct();
            setRuleValidateProduct();
            calculateTotalProduct();
        }
    };

    // ---------------------------------
    // Handle General Product Manage
    // ---------------------------------
    var handleGeneralGetProduct = function( form ) {     
        // Select Product
        selectProduct.change(function(e){
            e.preventDefault();
            handleCalculateProduct();
            //handleAddProduct();
        });

        // Remove Product
        $('form').delegate( ".remove_product", "click", function( e ) {
            e.preventDefault();
            var product = $(this).data('product');
            handleRemoveProduct(product);
        });

        // Remove Product
        $('form').delegate( ".calculate-pin", "keyup", function( e ) {
            e.preventDefault();
            calculateTotalProduct();
        });
    };
    
    // ---------------------------------
    // Handle Add Product
    // ---------------------------------
    var handleCalculateProduct = function() {
        if ( !selectProduct.length ) {
            return;
        }

        var productId   = selectProduct.val();
        if( ! productId ) {
            $('#total_bv').val(0);
            $('#package').val('');
            $('#reg_member_package').val('');
            bootbox.alert('Silahkan Pilih product')
            return false
        }
        
        var index       = _dataPINList.findIndex(x => x.id == productId);
        var data        = _dataPINList[index];
        var total_bv    = data.bv;
        var pack        = data.package_type;
        
        if ( $('#total_bv').length ) {
            $('#total_bv').val(total_bv);
            $('#package').val(pack);
            $('#reg_member_package').val(pack);
        }
    };

    // ---------------------------------
    // Calculate Total Product
    // ---------------------------------    
    var calculateTotalProduct = function() {
        var total_bv            = 0;
        var total_qty           = 0;
        var total_price         = 0;
        var total_weight        = 0;
        var el_input_pin        = $('input.calculate-pin');

        if ( el_input_pin.length ) {
            el_input_pin.each(function(index) {
                _qty            = $(this).val();
                _idx            = $(this).data('code');
                _bv             = $(this).data('bv');
                _price          = $(this).data('price');
                _weight         = $(this).data('weight');
                
                subtotal        = parseInt(_qty) * parseInt(_price);
                total_qty       = parseInt(total_qty) + parseInt(_qty);
                total_price     = parseInt(total_price) + parseInt(subtotal);
                total_weight    = parseInt(total_weight) + ( parseInt(_qty) * parseInt(_weight) );
                total_bv        = parseInt(total_bv) + ( parseInt(_qty) * parseInt(_bv) );
            });
        }

        if ( $('#total_bv').length ) {
            $('#total_bv').val(total_bv);
            
            if ( $('#package').length ) {
                if ( total_bv < 900 ) {
                    $('#package').val('Reseller Star1');
                    $('#reg_member_package').val('star1');
                } else if ( total_bv >= 900 && total_bv < 2100 ) {
                    $('#package').val('Reseller Star2');
                    $('#reg_member_package').val('star2');
                } else if ( total_bv >= 2100 && total_bv < 4500 ) {
                    $('#package').val('Reseller Star3');
                    $('#reg_member_package').val('star3');
                } else if ( total_bv >= 4500 ) {
                    $('#package').val('Reseller Star4');
                    $('#reg_member_package').val('star4');
                }
            }
        }
    };

    return {
        init: function() {
            handleGetProductRecord();
            handleGeneralGetProduct();
        }
    };
}();

// ===========================================================
// Get PIN Product Function
// ===========================================================
var GetPINProduct = function() {
    var selectProduct       = $('.select_pin_product');
    var selectPINList       = $('.select_pin');
    var access              = '';
    var type                = '';
    var form                = '';

    if ( selectProduct.length ) {
        access              = $(selectProduct).data('access');
        type                = $(selectProduct).data('type');
        form                = $(selectProduct).data('form');
    }

    var _dataProduct        = [];
    var _dataPINList        = [];

    // ---------------------------------
    // Handle Get Product Record
    // ---------------------------------
    var handleGetProductRecord = function() {
        if ( !selectProduct.length ) {
            return;
        }

        var load    = $(selectProduct).data('load');
        var code    = $(selectProduct).data('code');
        var type    = $(selectProduct).data('type');

        if ( load ) {
            API.get( load, { id_member: code, type: type, form: form }, function( response ) {
                if ( ! response.success ) {
                    if( response.status == 'access_denied' ){
                        $(location).attr('href',response.url);
                    }
                }                            

                _dataProduct = [];

                if ( response.data ) {
                    $.each(response.data, function(index, val) {
                         _dataProduct.push(val)
                    });
                }

                _dataPINList = Object.assign([], _dataProduct);
                handleGenerateSelectProduct();

            });
        } else {
            _dataProduct = [];
            _dataPINList = Object.assign([], _dataProduct);
            handleGenerateSelectProduct();
        }
    };

    // ---------------------------------
    // Handle Generate Select Product
    // ---------------------------------
    var handleGenerateSelectProduct = function() {
        handleSortProductRecord();
        selectProduct.empty().append('<option value="">Pilih Produk</option>');
        $.each(_dataPINList, function(index, val) {
            selectProduct.append('<option value="' + val.id + '" data-stock="' + val.stock + '">' + val.name + ' (Stok : ' + val.stock + ')</option>');
        });
    };

    // ---------------------------------
    // Handle Sort Product Record
    // ---------------------------------
    var handleSortProductRecord = function() {
        _dataPINList.sort(function(a, b) {
            return a.order - b.order;
        });
    };

    // ---------------------------------
    // Handle Generate PIN Product
    // ---------------------------------
    var handleGeneratePINProduct = function(productId = 0) {
        $('.select-pin-load').show();
        selectPINList.empty().append('<option value="">Pilih PIN Produk</option>');
        if ( productId ) {
            var index   = _dataPINList.findIndex(x => x.id == productId);
            var data    = _dataPINList[index];
            var pins    = data.pins;

            setTimeout(function(){ 
                $('.select-pin-load').hide(); 
                if ( pins ) {
                    $.each(pins, function(index, val) {
                        selectPINList.append('<option value="' + val.value + '" >' + val.name + '</option>');
                    });
                }
            }, 500);

        } else {
            setTimeout(function(){ $('.select-pin-load').hide(); }, 500);
        }
    }

    // ---------------------------------
    // Handle General Product Manage
    // ---------------------------------
    var handleGeneralGetProduct = function( form ) {     
        // Select Product
        selectProduct.change(function(e){
            e.preventDefault();
            var productId   = $(this).val();
            handleGeneratePINProduct(productId);
        });
    };

    return {
        init: function() {
            handleGetProductRecord();
            handleGeneralGetProduct();
        }
    };
}();

// ===========================================================
// Manage Product Function
// ===========================================================
var ProductManage = function() {

    var product_img;

    var total_qty       = 0;
    var total_price     = 0;

    var _trEmpty        = `<tr class="data-empty"><td colspan="5" class="text-center">Produk belum ada yang di pilih.</td></tr>`;

    // ---------------------------------
    // Quill Editor Load
    // ---------------------------------
    var text_editor         = $('#editor');
    var placeholder_editor  = $('#editor').data("quill-placeholder");
    if ( text_editor.length ) {
        var quill_editor    = new Quill('#editor', {
            modules: {
                toolbar: [
                    ["bold", "italic"],
                    ["link", "blockquote", "code"],
                    [{list: "ordered"}, {list: "bullet"}]
                ]
            },
            placeholder: placeholder_editor,
            theme: "snow"
        });
    }

    // ---------------------------------
    // Handle General Product Manage
    // ---------------------------------
    var handleGeneralProductManage = function() {
        $('#product_img_thumbnail').on('click', function(e) {
            $('.file-image').trigger('click');
        });

        $('#product_file, .file-image').on('change', function(e) {
            readURL( $(this), $('#product_img_thumbnail') );
            product_img = e.target.files;
        });

        $('#btn-modal-category').on('click', function(e) {
            $('#modal-add-category').modal('show');
        });
        
        $('#discount_agent_type').on('change', function(e) {
            var val = $(this).val();
            if ( val == 'percent' ) {
                $('#discount_agent').removeClass('numbercurrency');
                $('#discount_agent').addClass('numberpercent');
                $('.label_discount_agent').text('Jumlah (%)');
            } else {
                $('#discount_agent').removeClass('numberpercent');
                $('#discount_agent').addClass('numbercurrency');
                $('.label_discount_agent').text('Jumlah (Rp)');
            }
            InputMask.init();
        });

        $('#discount_customer_type').on('change', function(e) {
            var val = $(this).val();
            if ( val == 'percent' ) {
                $('#discount_customer').removeClass('numbercurrency');
                $('#discount_customer').addClass('numberpercent');
                $('.label_discount_customer').text('Jumlah (%)');
            } else {
                $('#discount_customer').removeClass('numberpercent');
                $('#discount_customer').addClass('numbercurrency');
                $('.label_discount_customer').text('Jumlah (Rp)');
            }
            InputMask.init();
        });

        $('#discount_agent_type').trigger('change');
        $('#discount_customer_type').trigger('change');

        // Button Edit Status Product Data
        // -----------------------------------------------
        $("body").delegate( "a.btn-status-product", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).attr('href');
            var product     = $(this).data('product');
            var status      = $(this).data('status');
            var msg_title   = (status == '1') ? 'Apakah anda yakin akan Meng-Nonaktifkan Produk Ini ?' : 'Apakah anda yakin akan Meng-Aktifkan Produk Ini ?';

            var msg_body    = `
                <div class="row pt-5 align-items-center">
                    <div class="col-sm-12">
                        <h3 class="heading mb-3 text-center">`+ msg_title +`</h3>
                    </div>
                    <div class="col-sm-12 text-center">
                        <small class="text-uppercase text-muted font-weight-bold">Produk : </small>
                        <h2 class="heading-title text-primary mb-0">`+ product +`</h2>
                    </div>
                </div>`;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_product').trigger('click');
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
                }
            });
        });

        // Button Delete Product Data
        // -----------------------------------------------
        $("body").delegate( "a.btn-delete-product", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).data('url');
            var product     = $(this).data('product');

            var msg_body    = `
                <div class="row pt-5 align-items-center">
                    <div class="col-sm-12">
                        <h3 class="heading mb-3 text-center">Apakah anda yakin akan Meng-Hapus Produk Ini ?</h3>
                    </div>
                    <div class="col-sm-12 text-center">
                        <small class="text-uppercase text-muted font-weight-bold">Produk : </small>
                        <h2 class="heading-title text-primary mb-0">`+ product +`</h2>
                    </div>
                </div>`;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_product').trigger('click');
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
                }
            });
        });
    };

    // ---------------------------------
    // Handle Validation Product Manage
    // ---------------------------------
    var handleValidationProductManage = function() {
        var form            = $('#form-product');
        var wrapper         = $('.wrapper-form-product');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                product_name: {
                    minlength: 3,
                    required: true
                },
                product_category: {
                    required: true
                },
                price_member: {
                    required: true
                },
                price_customer: {
                    required: true
                },
                bv: {
                    required: true
                },
                weight: {
                    required: true
                },
            },
            messages: {
                product_name: {
                    required: "Nama Produk harus di isi !",
                    minlength: "Minimal 3 karakter"
                },
                category: {
                    required: "Kategori Produk harus di pilih !",
                },
                price_member: {
                    required: "Harga Reseller harus di isi !",
                },
                price_customer: {
                    required: "Harga Konsumen harus di isi !",
                },
                bv: {
                    required: "BV Produk harus di isi !",
                },
                weight: {
                    required: "Berat Produk harus di isi !",
                },
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
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
                $(element).closest('.form-group').addClass('has-danger'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-danger'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-danger'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url         = $(form).attr('action');
                var data        = new FormData();
                var description = quill_editor.root.innerHTML;

                // Get Token
                data.append(App.kdName(), App.kdToken());

                // get inputs
                $('textarea.form-control, select.form-control, input.form-control',  $(form)).each(function(){
                    data.append($(this).attr("name"), $(this).val());
                });
            
                if (description) {
                    data.append('description', description);
                }
            
                if (product_img) {
                    $.each(product_img, function(key, value){
                        data.append('product_img', value);
                    });
                }

                bootbox.confirm("Apakah anda yakin akan simpan data produk ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   data,
                            processData:false,
                            contentType:false,
                            cache:false,
                            beforeSend: function (){
                                App.run_Loader('roundBounce');
                            },
                            success: function( response ){
                                App.close_Loader();
                                response = $.parseJSON(response);

                                if( response.token ){
                                    App.kdToken(response.token);
                                }
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        App.notify({
                                            icon: 'fa fa-check-circle', 
                                            title: 'Success', 
                                            message: response.message, 
                                            type: 'success',
                                        });
                                        $(form)[0].reset();
                                        setTimeout(function(){ $(location).attr('href',response.url); }, 1500);
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

    // ---------------------------------
    // Handle General Product Manage
    // ---------------------------------
    var handleGeneralProductCategory = function() {
        $('#btn-modal-category').on('click', function(e) {
            var url = $(this).data('url');
            $('#form-category')[0].reset();
            $('#modal-add-category').modal('show');
            if ( url != '' || url != 'undefined' ) {
                $('#form-category').attr('action', url);
            }
        });


        // Button Edit Category Data 
        // -----------------------------------------------
        $("body").delegate( "a.btn-edit-category", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).attr('href');
            var category    = $(this).data('category');
            $('#form-category')[0].reset();            
            $('#category', $('#form-category')).val(category);            
            $('#modal-add-category').modal('show');
            $('#form-category').attr('action', url);
        });

        // Button Edit Status Category Data
        // -----------------------------------------------
        $("body").delegate( "a.btn-status-category", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).attr('href');
            var category    = $(this).data('category');
            var status      = $(this).data('status');
            var msg_title   = (status == '1') ? 'Apakah anda yakin akan Meng-Nonaktifkan Kategori Ini ?' : 'Apakah anda yakin akan Meng-Aktifkan Kategori Ini ?';

            var msg_body    = `
                <div class="row pt-5 align-items-center">
                    <div class="col-sm-12">
                        <h3 class="heading mb-3 text-center">`+ msg_title +`</h3>
                    </div>
                    <div class="col-sm-12 text-center">
                        <small class="text-uppercase text-muted font-weight-bold">Kategori : </small>
                        <h2 class="heading-title text-primary mb-0">`+ category +`</h2>
                    </div>
                </div>`;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_category').trigger('click');
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
                }
            });
        });

        // Button Delete Category Data
        // -----------------------------------------------
        $("body").delegate( "a.btn-delete-category", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).data('url');
            var category    = $(this).data('category');

            var msg_body    = `
                <div class="row pt-5 align-items-center">
                    <div class="col-sm-12">
                        <h3 class="heading mb-3 text-center">Apakah anda yakin akan Meng-Hapus Kategori Ini ?</h3>
                    </div>
                    <div class="col-sm-12 text-center">
                        <small class="text-uppercase text-muted font-weight-bold">Kategori : </small>
                        <h2 class="heading-title text-primary mb-0">`+ category +`</h2>
                    </div>
                </div>`;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_category').trigger('click');
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
                }
            });
        });
    };

    // ---------------------------------
    // Handle Validation Product Manage
    // ---------------------------------
    var handleValidationAddCategory = function() {
        var form            = $('#form-category');
        var wrapper         = $('.wrapper-form-category');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                category: {
                    minlength: 2,
                    required: true
                }
            },
            messages: {
                category: {
                    required: "Kategori Produk harus di isi !",
                    minlength: "Minimal 2 karakter"
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
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
                $(element).closest('.form-group').addClass('has-danger'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-danger'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-danger'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url         = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan simpan data kategori produk ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('roundBounce');
                            },
                            success: function( response ){
                                App.close_Loader();
                                response = $.parseJSON(response);
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        App.notify({
                                            icon: 'fa fa-check-circle', 
                                            title: 'Success', 
                                            message: response.message, 
                                            type: 'success',
                                        });
                                        $(form)[0].reset();
                                        $('#modal-add-category').modal('hide');
                                        if ( response.option ) {
                                            var el = $('#product_category');
                                            if ( el.length ) {
                                                el.empty();
                                                el.empty().append(response.option);
                                            }
                                        }
                                        if ( response.form_input ) {
                                            if ( response.form_input == 'category' ) {
                                                $('#btn_list_table_category').trigger('click');
                                            }
                                        }
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

    // ---------------------------------
    // Handle Validation Product Stock
    // ---------------------------------
    var handleValidationProductStock = function() {
        var form            = $('#form-product-stock');
        var wrapper         = $('.wrapper-form-product-stock');

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                product: {
                    required: true
                },
                qty: {
                    required: true
                },
                price: {
                    required: true
                },
                total: {
                    required: true
                },
                supplier: {
                    required: true,
                },
                description: {
                    required: true,
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
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
                $(element).closest('.form-group').addClass('has-danger'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-danger'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-danger'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url         = $(form).attr('action');
                var data        = new FormData();

                data.append(App.kdName(), App.kdToken());

                // get inputs
                $('textarea.form-control, select.form-control, input.form-control',  $(form)).each(function(){
                    data.append($(this).attr("name"), $(this).val());
                });

                bootbox.confirm("Apakah anda yakin akan simpan data stok produk ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   data,
                            processData:false,
                            contentType:false,
                            cache:false,
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
                                        $(form)[0].reset();
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
                                            title: 'Failed', 
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
            }
        });
    };

    return {
        init: function() {
            handleGeneralProductManage();
            handleValidationProductManage();
            handleValidationAddCategory();
            handleValidationProductStock();
        },
        initCategory: function() {
            handleGeneralProductCategory();
            handleValidationAddCategory();
        }
    };
}();

// ===========================================================
// Manage Promo Code Function
// ===========================================================
var PromoCodeManage = function() {
    var _form       = $('#form-promocode');
    var _modal      = $('#modal-form-promocode');
    var wrapper     = $('.wrapper-form-promocode');

    var _trEmpty    = `<tr class="data-empty"><td colspan="2" class="text-center">Produk belum ada yang di pilih.</td></tr>`;

    // ---------------------------------
    // Handle General Promo Code
    // ---------------------------------
    var handleGeneralPromoCode = function() {
        $('#btn-modal-promo-code').on('click', function(e) {
            var url = $(this).data('url');
            _form[0].reset();
            $('input.form-control', _form).val('');
            _modal.modal('show');
            if ( url != '' || url != 'undefined' ) {
                _form.attr('action', url);
            }
            clearProductPromo();
            $('#discount_agent_type').trigger('change');
            $('#discount_customer_type').trigger('change');
        });

        $('#discount_agent_type').on('change', function(e) {
            var val = $(this).val();
            if ( val == 'percent' ) {
                $('#discount_agent').removeClass('numbercurrency');
                $('#discount_agent').addClass('numberpercent');
                $('.label_discount_agent').text('Jumlah (%)');
            } else {
                $('#discount_agent').removeClass('numberpercent');
                $('#discount_agent').addClass('numbercurrency');
                $('.label_discount_agent').text('Jumlah (Rp)');
            }
            InputMask.init();
        });

        $('#discount_customer_type').on('change', function(e) {
            var val = $(this).val();
            if ( val == 'percent' ) {
                $('#discount_customer').removeClass('numbercurrency');
                $('#discount_customer').addClass('numberpercent');
                $('.label_discount_customer').text('Jumlah (%)');
            } else {
                $('#discount_customer').removeClass('numberpercent');
                $('#discount_customer').addClass('numbercurrency');
                $('.label_discount_customer').text('Jumlah (Rp)');
            }
            InputMask.init();
        });

        // Button Edit Promo Data 
        // -----------------------------------------------
        $("body").delegate( "a.btn-edit-promo", "click", function( e ) {
            e.preventDefault();
            var url                 = $(this).attr('href');
            var code                = $(this).data('code');
            var promo               = $(this).data('promo');
            var agent_type          = $(this).data('agent_type');
            var agent_discount      = $(this).data('agent_discount');
            var customer_type       = $(this).data('customer_type');
            var customer_discount   = $(this).data('customer_discount');
            var products            = $(this).data('products');

            App.run_Loader('roundBounce');

            _form[0].reset();
            if ( url != '' || url != 'undefined' ) {
                _form.attr('action', url);
            }
            $('#form_code', _form).val(code);
            $('#promo_code', _form).val(promo);
            $('#discount_agent_type', _form).val(agent_type);
            $('#discount_customer_type', _form).val(customer_type);
            $('#discount_agent_type').trigger('change');
            $('#discount_customer_type').trigger('change');
            if ( products ) {
                clearProductPromo();
                loadProductPromo(products);
            }
            setTimeout(function(){ 
                App.close_Loader();
                $('#discount_agent', _form).val(agent_discount);
                $('#discount_customer', _form).val(customer_discount);
                _modal.modal('show');
            }, 500);
        });

        // Button Edit Status Promo Data
        // -----------------------------------------------
        $("body").delegate( "a.btn-status-promo", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).attr('href');
            var promo       = $(this).data('promo');
            var status      = $(this).data('status');
            var msg_title   = (status == '1') ? 'Apakah anda yakin akan Meng-Nonaktifkan Promo Ini ?' : 'Apakah anda yakin akan Meng-Aktifkan Promo Ini ?';

            var msg_body    = `
                <div class="row pt-5 align-items-center">
                    <div class="col-sm-12">
                        <h3 class="heading-small mb-3 text-center">`+ msg_title +`</h3>
                    </div>
                    <div class="col-sm-12 text-center">
                        <small class="text-uppercase text-muted font-weight-bold">Kode Promo : </small>
                        <h2 class="heading-title text-primary mb-0">`+ promo +`</h2>
                    </div>
                </div>`;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_promo_code').trigger('click');
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
                }
            });
        });

        // Button Delete Status Promo Data
        // -----------------------------------------------
        $("body").delegate( "a.btn-delete-promo", "click", function( e ) {
            e.preventDefault();
            var url         = $(this).attr('href');
            var promo       = $(this).data('promo');

            var msg_body    = `
                <div class="row pt-5 align-items-center">
                    <div class="col-sm-12">
                        <h3 class="heading-small mb-3 text-center">Apakah anda yakin akan Menghapus Promo ini ? </h3>
                    </div>
                    <div class="col-sm-12 text-center">
                        <small class="text-uppercase text-muted font-weight-bold">Kode Promo : </small>
                        <h2 class="heading-title text-primary mb-0">`+ promo +`</h2>
                    </div>
                </div>`;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_promo_code').trigger('click');
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
                }
            });
        });

        // Button Add Product Promo Data 
        // -----------------------------------------------
        $("body").delegate( "#select_product", "change", function( e ) {
            e.preventDefault();
            $('#btn-add-product-promo').trigger('click');
        });

        // Button Add Product Promo Data 
        // -----------------------------------------------
        $("body").delegate( "#btn-add-product-promo", "click", function( e ) {
            e.preventDefault();
            var product     = $('#select_product', _form).val();
            if ( product ) {
                addProductPromo();
            } else {
                bootbox.alert('Produk belum di pilih !');
                $('#select_product', _form).val('');
                return false;
            }
        });

        // Button Add Product Promo Data 
        // -----------------------------------------------
        $("body").delegate( ".btn-remove-product-promo", "click", function( e ) {
            e.preventDefault();
            var _product    = $(this).data('id');
            var _tr         = $(this).parents('tr');
            var _table      = $('#list_table_product_promo');
            var _tbody      = $('tbody', _table);
            var _count_data = $('tr', _tbody).length;

            if( $('[data-id="'+_product+'"]', _tbody).length ) {
                _tr.remove();

                if ( _count_data == 1 ) {
                    _tbody.append(_trEmpty);
                }
            }
        });
    };

    // ---------------------------------
    // Add Product Promo Code
    // ---------------------------------
    var addProductPromo = function() {
        var _table      = $('#list_table_product_promo');
        var _tbody      = $('tbody', _table);
        var _tr         = $('tr', _tbody);
        var _count_data = _tr.length;
        var _empty_row  = _tbody.find('tr.data-empty');
        var _product    = $('#select_product', _form).val();
        var t_product   = $('select[name="select_product"] option:selected').text();

        if( $('[data-id="'+_product+'"]', _tbody).length ) {
            bootbox.alert('Produk ini sudah ada ');
            return false;
        }

        if ( _empty_row.length ) {
            _empty_row.remove();
        }

        var _append_row = `
            <tr data-id="${_product}">
                <td class="py-1"><b>${t_product}</b></td>
                <td class="py-1 text-center">
                    <input type="hidden" name="products[${_product}]" value="${_product}" class="d-none input-products" />
                    <button class="btn btn-sm btn-outline-warning btn-remove-product-promo" type="button" data-id="${_product}">
                    <i class="fa fa-times"></i> Remove</button>
                </td>
            </tr>`;
        _tbody.append(_append_row);
        $('#select_product', _form).val('');
    }

    // ---------------------------------
    // Clear Product Promo Code
    // ---------------------------------
    var clearProductPromo = function() {
        var _table      = $('#list_table_product_promo');
        var _tbody      = $('tbody', _table);

        if ( _tbody.length ) {
            _tbody.empty();
            _tbody.append(_trEmpty);
        }
    }

    // ---------------------------------
    // Load Product Promo Code
    // ---------------------------------
    var loadProductPromo = function(products_idx = '') {
        var _table      = $('#list_table_product_promo');
        var _tbody      = $('tbody', _table);

        if ( products_idx ) {
            $.each(products_idx, function(index, val) {
                $('#select_product', _form).val(val);
                if ( $('#select_product', _form).val() ) {
                    $('#select_product', _form).trigger('change');
                }
            });
        }
    }

    // ---------------------------------
    // Handle Validation Promo Code
    // ---------------------------------
    var handleValidationPromoCode = function() {
        var form        = _form;
        var products    = '';

        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                promo_code: {
                    required: true,
                    vouchercode: true,
                    minlength: 2,
                    remote: {
                        url: $("#promo_code").data('url'),
                        type: "post",
                        data: {
                            [App.kdName()]: function() {
                                return App.kdToken();
                            },
                            promo_code: function() {
                                return $("#promo_code").prop( 'readonly' ) ? '' : $("#promo_code").val();
                            },
                            code: function() {
                                return $("#form_code").length ? $("#form_code").val() : '';
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
                discount_agent: {
                    required: function(element) {
                        if( $('#discount_customer').val() == '' || $('#discount_customer').val() == 0 || $('#discount_customer').val() == '0 %' ){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
                discount_customer: {
                    required: function(element) {
                        if( $('#discount_agent').val() == '' || $('#discount_agent').val() == 0 || $('#discount_agent').val() == '0 %' ){
                            return true;
                        }else{
                            return false;
                        }
                    }
                }
            },
            messages: {
                promo_code: {
                    required: "Kode Promo harus di isi !",
                    minlength: "Minimal 2 karakter",
                    remote: "Kode Promo sudah terdaftar. Silahkan gunakan Kode Promo lain",
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
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
                $(element).closest('.form-group').addClass('has-danger'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-danger'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-danger'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url         = $(form).attr('action');
                var form_input  = $( ':input[name=form_input]').val();

                if ( form_input == 'products' ) {
                    var _table  = $('#list_table_product_promo');
                    var _tbody  = $('tbody', _table);

                    if ( ! $('.input-products', _tbody).length ) {
                        bootbox.alert('Produk belum di pilih !');
                        return false;
                    }
                }

                bootbox.confirm("Apakah anda yakin akan simpan data kode promo ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('roundBounce');
                            },
                            success: function( response ){
                                App.close_Loader();
                                response = $.parseJSON(response);
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        App.notify({
                                            icon: 'fa fa-check-circle', 
                                            title: 'Success', 
                                            message: response.message, 
                                            type: 'success',
                                        });
                                        $(form)[0].reset();
                                        _modal.modal('hide');
                                        $('#btn_list_table_promo_code').trigger('click');
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
                    }
                });
            }
        });

        $.validator.addMethod("vouchercode", function(value) {
            return /^[a-z0-9\-]{4,100}$/i.test(value);
        }, "Kode Voucher harus berupa Huruf atau Angka atau karakter strip (-)");
    };

    return {
        init: function() {
            handleGeneralPromoCode();
            handleValidationPromoCode();
        }
    };
}();

// ===========================================================
// Manage Shop Order Function
// ===========================================================
var ShopOrderManage = function() {
    var modal_detail = $('#modal-shop-order-detail');

    // ---------------------------------
    // Handle General Promo Code
    // ---------------------------------
    var handleGeneralShopOrder = function() {
        // Button Detail Shop Order
        $("body").delegate( "a.btn-shop-order-detail", "click", function( e ) {
            var url     = $(this).data('url');
            var invoice = $(this).data('invoice');
            $.ajax({
                type:   "POST",
                url:    url,
                beforeSend: function (){
                    App.run_Loader('roundBounce');
                    $('.info-shop-order-detail', modal_detail).empty();
                },
                success: function( response ){
                    App.close_Loader();
                    response = $.parseJSON(response);
                    if( response.status == 'access_denied' ){
                        $(location).attr('href',response.url);
                    }else{
                        if( response.status == 'success'){
                            $('.title-invoice', modal_detail).text(invoice);
                            $('.info-shop-order-detail', modal_detail).html(response.data);
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
            return false;
        });

        // Shop Order Payment
        $("body").delegate( "a.btn-shop-payment", "click", function( event ) {
            event.preventDefault();
            var bank        = $(this).data('bank');
            var bill        = $(this).data('bill');
            var bill_name   = $(this).data('bill_name');
            var nominal     = $(this).data('nominal');
            var img         = $(this).data('img');
            var type        = $(this).data('type');

            var txt_bank        = 'Bank';
            var txt_bill        = 'No. Rekening';
            var txt_bill_name   = 'Nama Pemilik Rek.';
            var txt_total       = 'Jumlah Transfer';
            var txt_desc        = '';

            if ( type == 'deposite' ) {
                txt_bank        = 'Saldo Deposite';
                txt_bill        = 'Username';
                txt_bill_name   = 'Nama';
                txt_total       = 'Jumlah';
                txt_desc        = '<h4 class="heading-small text-warning">Pembayaran dilakukan melalui Saldo '+ bank +'</h4><hr class="my-2">';
            }
            
            var msg_body    = `
                <h3 class="heading pt-4 pb-3 text-center">Bukti Pembayaran</h3>
                `+txt_desc+`
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">${txt_bank} :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">${txt_bank} :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${bank} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">${txt_bill} :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">${txt_bill} :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${bill} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">${txt_bill_name} :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">${txt_bill_name} :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${bill_name} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">${txt_total} :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">${txt_total} :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${nominal} </small></div>
                </div>`;

            if ( type != 'deposite' && img != '' ) {
                msg_body   += `
                    <div class="row justify-content-center mt-4">
                        <div class="col-sm-8">
                            <a href="`+img+`" target="_blank">
                                <img class="img-responsive" width="100%" src="`+img+`">
                            </a>
                        </div>
                    </div>`;
            }

            bootbox.alert({
                title: '',
                message: msg_body,
                size: 'large'
            });
        });

        // Button Action Shop Order
        $("body").delegate( "a.btn-shop-order-action", "click", function( e ) {
            var url         = $(this).data('url');
            var message     = $(this).data('message');
            var status      = $(this).data('status');
            var invoice     = $(this).data('invoice');
            var name        = $(this).data('name');
            var detail      = $(this).data('detail');
            var total       = $(this).data('total');
            var uniquecode  = $(this).data('uniquecode');
            var subtotal    = $(this).data('subtotal');
            var shipping    = $(this).data('shipping');
            var discount    = $(this).data('discount');
            var voucher     = $(this).data('voucher');
            var _parent     = $(this).parents('table');
            var shippingmethod  = $(this).data('shippingmethod');
            var btn_search      = _parent.children('thead').find('.filter-submit');

            var msg_body    = `
                <h3 class="h5 pt-4 pb-3 text-center">${message}</h3>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Invoice :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Invoice :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${invoice} </small></div>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Username :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Username :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${name} </small></div>
                </div>`;

            if( Array.isArray(detail) && (detail.length > 0) ){
                msg_body += `<div class="mt-4 mb-2"><small class="heading-small text-muted font-weight-bold">Ringkasan Order :</small></div>`;
                
                detail.map((item, index)=>{
                    var qty_price = `Qty : <span class="font-weight-bold mr-1">${item.qty}</span>`;
                    if ( parseInt(item.price) > parseInt(item.price_cart) ) {
                        //qty_price += `( <s>${App.formatCurrency(item.price)}</s> <span class="text-warning">${App.formatCurrency(item.price_cart)}</span> )`;
                        qty_price += `( ${App.formatCurrency(item.price_cart)} )`;
                    } else {
                        qty_price += `( ${App.formatCurrency(item.price_cart)} )`;
                    }
                    msg_body += `
                        <div class="row align-items-center">
                            <div class="col-sm-12">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="text-capitalize px-1 pl-2 py-2">
                                                <span class="text-primary font-weight-bold">${item.product_name}</span><br>
                                                <span class="small">${qty_price}</span>
                                            </td>
                                            <td class="text-right px-1 pr-2 py-1">${App.formatCurrency(item.subtotal)}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;
                });

                msg_body  += `
                    <hr class="mt-0 mb-2">
                    <div class="row px-2">
                        <div class="col-sm-7"><span>Subtotal</span></div>
                        <div class="col-sm-5 text-right"><span class="font-weight-bold">${App.formatCurrency(subtotal)}</span></div>
                    </div>
                    <div class="row px-2">
                        <div class="col-sm-7"><span>Kode Unik</span></div>
                        <div class="col-sm-5 text-right"><span class="font-weight-bold">${uniquecode}</span></div>
                    </div>
                    <div class="row px-2">
                        <div class="col-sm-7"><span>Biaya Pengiriman</span></div>
                        <div class="col-sm-5 text-right"><span class="font-weight-bold">${App.formatCurrency(shipping)}</span></div>
                    </div>
                    <div class="row px-2">
                        <div class="col-sm-7">
                            <span>
                                Diskon `+ ( voucher ? `(<span class="text-success">`+ voucher +`</span>)` : ``) +`
                            </span>
                        </div>
                        <div class="col-sm-5 text-right"><span class="font-weight-bold">${App.formatCurrency(discount)}</span></div>
                    </div>
                    <hr class="mt-2 mb-3">
                    <div class="row align-items-center mb-1">
                        <div class="col-sm-6"><span class="heading-small font-weight-bold">Total Pembayaran</span></div>
                        <div class="col-sm-6 text-right">
                            <span class="heading text-warning font-weight-bold">${total} </span>
                        </div>
                    </div>`;
            } else {
                msg_body += `
                    <div class="row">
                        <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Pembayaran :</small></div>
                        <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Pembayaran :</small></div>
                        <div class="col-sm-6"><small class="heading-small font-weight-bold text-warning"> ${total} </small></div>
                    </div>`;
            }

            if ( status == 'shipping' ) {
                var courier             = $(this).data('courier');
                var service             = $(this).data('service');
                var courier_readonly    = '';
                var service_readonly    = '';
                if ( courier ) {
                    courier_readonly    = 'readonly="readonly"';
                }
                if ( service ) {
                    service_readonly    = 'readonly="readonly"';
                }

                var password_html       = `
                    <div class="form-group row mb-2">
                        <label class="col-md-4 col-form-label form-control-label" for="password_confirm">Password <span class="required">*</span></label>
                        <div class="col-md-8">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                </div>
                                <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                <div class="input-group-append">
                                    <button class="btn btn-secondary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>`;

                msg_body += `
                    <hr class="mt-2">
                    <h3 class="heading-small text-muted font-weight-bold">Informasi Pengiriman</h3>
                    <form role="form" id="form-shop-order-action">`;

                if ( shippingmethod == 'pickup' ) {
                    msg_body += `
                        <div class="form-group row mb-2">
                            <label class="col-md-4 col-form-label form-control-label" for="resi_confirm">Metode Pengiriman<span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control text-uppercase" value="PICKUP" disabled="" />
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-4 col-form-label form-control-label" for="resi_confirm">Nama Pengambil <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" id="resi_confirm" name="resi_confirm" class="form-control text-uppercase" placeholder="Nama Pengambil" />
                            </div>
                        </div>`;
                } else {
                    msg_body += `
                        <div class="form-group row mb-2">
                            <label class="col-md-4 col-form-label form-control-label" for="courier_confirm">Kurir <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" id="courier_confirm" name="courier_confirm" class="form-control text-uppercase" placeholder="Kurir" value="${courier}" ${courier_readonly} />
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-4 col-form-label form-control-label" for="service_confirm">Layanan Kurir <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" id="service_confirm" name="service_confirm" class="form-control text-uppercase" placeholder="Layanan Kurir" value="${service}" ${service_readonly} />
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-4 col-form-label form-control-label" for="resi_confirm">No. Resi <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" id="resi_confirm" name="resi_confirm" class="form-control text-uppercase" placeholder="NO. RESI" />
                            </div>
                        </div>`;
                }

                msg_body += `
                        <hr class="mt-2">
                        ${password_html}
                    </form>`;

            } else {
                msg_body += `
                    <hr class="mt-2 mb-3">
                    <div class="row justify-content-center">
                        <form class="form-horizontal" id="form-shop-order-action">
                            <div class="form-group mb-1">
                                <div class="col-md-12">
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                        </div>
                                        <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>`;
            }


            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    var data = {};
                    var password        = '';
                    var input_courier   = '';
                    var input_service   = '';
                    var input_resi      = '';

                    if ($('#password_confirm', '#form-shop-order-action').length) {
                        password = $('#password_confirm', '#form-shop-order-action').val();
                        data.password = password;
                    }

                    if (password == "" || password == undefined) {
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            message: 'Password harus diisi !', 
                            type: 'warning',
                        });
                        $('#password_confirm').focus();
                        return false;
                    }

                    if ( status == 'shipping' ) {
                        if ( shippingmethod == 'ekspedisi' ) {
                            if ($('#courier_confirm', '#form-shop-order-action').length) {
                                input_courier = $('#courier_confirm', '#form-shop-order-action').val();
                                data.courier = input_courier;

                                if (input_courier == "" || input_courier == undefined) {
                                    App.notify({
                                        icon: 'fa fa-exclamation-triangle', 
                                        message: 'Kurir harus diisi !', 
                                        type: 'warning',
                                    });
                                    $('#courier_confirm').focus();
                                    return false;
                                }
                            }
                            if ($('#service_confirm', '#form-shop-order-action').length) {
                                input_service = $('#service_confirm', '#form-shop-order-action').val();
                                data.service = input_service;

                                if (input_service == "" || input_service == undefined) {
                                    App.notify({
                                        icon: 'fa fa-exclamation-triangle', 
                                        message: 'Layanan Kurir harus diisi !', 
                                        type: 'warning',
                                    });
                                    $('#service_confirm').focus();
                                    return false;
                                }
                            }
                        }

                        if ($('#resi_confirm', '#form-shop-order-action').length) {
                            input_resi = $('#resi_confirm', '#form-shop-order-action').val();
                            data.resi = input_resi;
                        }

                        if (input_resi == "" || input_resi == undefined) {
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                message: 'Resi harus diisi !', 
                                type: 'warning',
                            });
                            $('#resi_confirm').focus();
                            return false;
                        }
                    }

                    $.ajax({
                        type:   "POST",
                        data:   data,
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.token ){
                                App.kdToken(response.token);
                            }

                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    if ( btn_search.length ) {
                                        btn_search.trigger('click');
                                    }
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
                            bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                                location.reload();
                            });
                        }
                    });
                }
            });
            return false;
        });

        // Button Confirm Shop Order
        $("body").delegate( "a.btn-shop-order-confirm", "click", function( e ) {
            var url     = $(this).data('url');
            var invoice = $(this).data('invoice');
            var total   = $(this).data('total');

            var msg_body    = `
                <h3 class="heading pt-4 pb-3 text-center">Apakah anda yakin akan konfirmasi pesanan ini ?</h3>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Invoice :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Invoice :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${invoice} </small></div>
                </div>
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Total Pembayaran :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Total Pembayaran :</small></div>
                    <div class="col-sm-6"><small class="heading-title text-warning font-weight-bold"> ${total} </small></div>
                </div>
                <hr class="mt-2 mb-3">
                <div class="row justify-content-center">
                    <form class="form-horizontal" id="form-shop-order-confirm">
                        <div class="form-group mb-1">
                            <div class="col-md-12">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>`;

            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    var data = {};
                    var password = '';

                    if ($('#password_confirm', '#form-shop-order-confirm').length) {
                        password = $('#password_confirm', '#form-shop-order-confirm').val();
                        data.password = password;
                    }

                    if (password == "" || password == undefined) {
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            message: 'Password harus diisi !', 
                            type: 'warning',
                        });
                        $('#password_confirm').focus();
                        return false;
                    }

                    $.ajax({
                        type:   "POST",
                        data:   data,
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);

                            if( response.token ){
                                App.kdToken(response.token);
                            }
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_shop_order').trigger('click');
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
                }
            });
            return false;
        });

        // Button Approved Shop Order
        $("body").delegate( "a.btn-shop-order-approved", "click", function( e ) {
            var url     = $(this).data('url');
            var invoice = $(this).data('invoice');
            var total   = $(this).data('total');

            var msg_body    = `
                <h3 class="heading pt-4 pb-3 text-center">Apakah anda yakin akan approved pesanan ini ?</h3>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Invoice :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Invoice :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${invoice} </small></div>
                </div>
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Total Pembayaran :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Total Pembayaran :</small></div>
                    <div class="col-sm-6"><small class="heading-title text-warning font-weight-bold"> ${total} </small></div>
                </div>
                <hr class="mt-2 mb-3">
                <div class="row justify-content-center">
                    <form class="form-horizontal" id="form-shop-order-confirm">
                        <div class="form-group mb-1">
                            <div class="col-md-12">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>`;

            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    var data = {};
                    var password = '';

                    if ($('#password_confirm', '#form-shop-order-confirm').length) {
                        password = $('#password_confirm', '#form-shop-order-confirm').val();
                        data.password = password;
                    }

                    if (password == "" || password == undefined) {
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            message: 'Password harus diisi !', 
                            type: 'warning',
                        });
                        $('#password_confirm').focus();
                        return false;
                    }

                    $.ajax({
                        type:   "POST",
                        data:   data,
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_shop_order').trigger('click');
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
                }
            });
            return false;
        });

        // Button Cancel Shop Order
        $("body").delegate( "a.btn-shop-order-cancel", "click", function( e ) {
            var url     = $(this).data('url');
            var invoice = $(this).data('invoice');
            var total   = $(this).data('total');

            var msg_body    = `
                <h3 class="heading pt-4 pb-3 text-center">Apakah anda yakin akan membatalkan pesanan ini ?</h3>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Invoice :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Invoice :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${invoice} </small></div>
                </div>
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Total Pembayaran :</small></div>
                    <div class="col-sm-5 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Total Pembayaran :</small></div>
                    <div class="col-sm-6"><small class="heading-title text-warning font-weight-bold"> ${total} </small></div>
                </div>
                <hr class="mt-2 mb-3">
                <div class="row justify-content-center">
                    <form class="form-horizontal" id="form-shop-order-cancel">
                        <div class="form-group mb-1">
                            <div class="col-md-12">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                `;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    var data = {};
                    var password = '';

                    if ($('#password_confirm', '#form-shop-order-cancel').length) {
                        password = $('#password_confirm', '#form-shop-order-cancel').val();
                        data.password = password;
                    }

                    if (password == "" || password == undefined) {
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            message: 'Password harus diisi !', 
                            type: 'warning',
                        });
                        $('#password_confirm').focus();
                        return false;
                    }

                    $.ajax({
                        type:   "POST",
                        data:   data,
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.token ){
                                App.kdToken(response.token);
                            }
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_shop_order').trigger('click');
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
                }
            });
            return false;
        });

        // Button Input Resi Shop Order
        $("body").delegate( "a.btn-shop-order-resi", "click", function( e ) {
            var url         = $(this).data('url');
            var invoice     = $(this).data('invoice');
            var total       = $(this).data('total');
            var courier     = $(this).data('courier');
            var service     = $(this).data('service');

            var msg_body    = `
                <h3 class="heading pt-4 pb-3 text-center">Apakah anda yakin akan membatalkan pesanan ini ?</h3>
                <div class="row">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Invoice :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Invoice :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${invoice} </small></div>
                </div>
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Total Pembayaran :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Total Pembayaran :</small></div>
                    <div class="col-sm-6"><small class="heading-small text-warning font-weight-bold"> ${total} </small></div>
                </div>
                <hr class="mt-2 mb-3">
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Kurir :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Kurir :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${courier} </small></div>
                </div>
                <div class="row align-items-center">
                    <div class="col-sm-6 d-md-none"><small class="heading-small text-muted font-weight-bold">Layanan :</small></div>
                    <div class="col-sm-6 d-none d-md-inline-block text-right"><small class="heading-small text-muted font-weight-bold">Layanan :</small></div>
                    <div class="col-sm-6"><small class="heading-small font-weight-bold"> ${service} </small></div>
                </div>
                <hr class="mt-2 mb-3">
                <div class="row justify-content-center">
                    <form class="form-horizontal" id="form-shop-order-resi">
                        <div class="form-group mb-2">
                            <div class="col-md-12">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-truck"></i></span>
                                    </div>
                                    <input type="text" name="resi" id="resi" class="form-control" placeholder="Nomor Resi" autocomplete="off" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-1">
                            <div class="col-md-12">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>`;
            bootbox.confirm(msg_body, function(result) {
                if( result == true ){
                    var data = {};
                    var password = '';
                    var resi = '';

                    if ($('#resi', '#form-shop-order-resi').length) {
                        resi = $('#resi', '#form-shop-order-resi').val();
                        data.resi = resi;
                    }

                    if ($('#password_confirm', '#form-shop-order-resi').length) {
                        password = $('#password_confirm', '#form-shop-order-resi').val();
                        data.password = password;
                    }

                    if (resi == "" || resi == undefined) {
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            message: 'Nomor Resi harus diisi !', 
                            type: 'warning',
                        });
                        $('#resi').focus();
                        return false;
                    }

                    if (password == "" || password == undefined) {
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            message: 'Password harus diisi !', 
                            type: 'warning',
                        });
                        $('#password_confirm').focus();
                        return false;
                    }

                    $.ajax({
                        type:   "POST",
                        data:   data,
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
                                    $('#btn_list_table_shop_order').trigger('click');
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
                }
            });
            return false;
        });

        $("body").delegate( "input#password_confirm", "keypress", function( e ) {
            var key = e.which;
            if(key == 13){ return false; }
        });
    };

    return {
        init: function() {
            handleGeneralShopOrder();
        }
    };
}();

// ===========================================================
// Handle Commission
// ===========================================================
var Commission = function() {
    // --------------------------------
    // Handle Commission
    // --------------------------------
    var handleCommission = function() {
        if ( ! ('#search-daterange-commission').length || typeof(moment) != "function" )
            return;

        var startDate   = moment();
        var endDate     = moment();

        if ( daterange  = $( 'input[name=search_date_commission]').val() ) {
            daterange   = daterange.split( '|' );
            startDate   = moment( daterange[0], 'YYYY-MM-DD' );
            endDate     = moment( daterange[1], 'YYYY-MM-DD' );
        }

        cbCommission( startDate, endDate );

        $('#search-daterange-commission').daterangepicker({
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cbCommission);

        $('#search-daterange-commission').on('apply.daterangepicker', function(ev, picker) {
            startDate = picker.startDate.format('YYYY-MM-DD');
            endDate = picker.endDate.format('YYYY-MM-DD');

            $( 'input[name=search_date_commission]' ).val( startDate + '|' + endDate );
            $( '#btn_commission_list' ).click();
        });

        if ( $('input#newdate').length ) {
            var url     = $('input#newdate').data('url')
            var datest  = $('input#newdate').data('star')
            var dateen  = $('input#newdate').data('end')
            $('input#newdate').daterangepicker({
                "startDate": datest,
                "endDate": dateen,
                locale: {
                    format: 'YYYY/MM/DD'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function(start, end, label) {
                window.location.href = url + '?daterange=' + start.format('YYYY-MM-DD') + '|' + end.format('YYYY-MM-DD');
            });
        }
    };

    var cbCommission = function( start, end ) {
        $( '#search-daterange-commission span' ).html( start.format( 'MMMM D, YYYY' ) + ' - ' + end.format( 'MMMM D, YYYY' ) );
    };

    // --------------------------------
    // Handle My Commission
    // --------------------------------
    var handleMyCommission = function() {
        if ( ! ('#search-daterange').length || typeof(moment) != "function" )
            return;

        var startDate = moment();
        var endDate = moment();

        if ( daterange = $( 'input[name=daterange]').val() ) {
            daterange = daterange.split( '|' );
            startDate = moment( daterange[0], 'YYYY-MM-DD' );
            endDate = moment( daterange[1], 'YYYY-MM-DD' );
        }

        cbMyCommission( startDate, endDate );

        $('#search-daterange').daterangepicker({
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cbMyCommission);

        $('#search-daterange').on('apply.daterangepicker', function(ev, picker) {
            startDate = picker.startDate.format('YYYY-MM-DD');
            endDate = picker.endDate.format('YYYY-MM-DD');

            $( 'input[name=daterange]' ).val( startDate + '|' + endDate );
            $( 'form[name=form-my-commission], form[name=form-commission]' ).submit();
        });
    };

    var cbMyCommission = function( start, end ) {
        $( '#search-daterange span' ).html( start.format( 'MMMM D, YYYY' ) + ' - ' + end.format( 'MMMM D, YYYY' ) );
    };

    return {
        init: function() {
            handleCommission();
            handleMyCommission();
        }
    };
}();

// ===========================================================
// Profile Function
// ===========================================================
var Profile = function() {
    var idcard_img;
    var logo_img;
    var profile_img;
    // Handle Profile Function
    // --------------------------------------------------------------------------
    var handleProfile = function() {
        // Select KTP
        // $('#idcard_thumbnail').on('click', function(e) {
        //     $('.file-image').trigger('click');
        // });

        $('#idcard_file, .file-image').on('change', function(e) {
            App.readURLmedia( $(this), $('#idcard_thumbnail') );
            idcard_img = e.target.files;
            $('.btn-idcard-photo').show();
        });
        
        $('#logo_file, .file-image').on('change', function(e) {
            App.readURLmedia( $(this), $('#logo_thumbnail') );
            logo_img = e.target.files;
            $('.btn-logo-image').show();
        });

        // on Click Image Or Button Profile Image 
        $('.profile-photo').on('click', function(e){
            e.preventDefault();
            $('#profile_img').trigger('click');
        });

        // On Change file Profile Image
        $('#profile_img').on('change', function(e){
            profile_img = e.target.files;

            var reader = new FileReader();
            reader.onload = function (e) {
                $('#profile-photo').attr('src', e.target.result);
            }
            reader.readAsDataURL(profile_img[0]);
            
            $('.btn-profile-photo').show();
        });

        // Reset Change Password Form
        $('.btn-pass-reset').click(function(e){
            e.preventDefault();
            var msg         = $('.alert');

            $(msg).hide();
            $('.form-group').removeClass('has-danger');
            $('.invalid-feedback').hide().empty();
            $('#cpassword')[0].reset();
            return false;
        });

        // Save Update Profile
        $('#do_save_profile').click(function(e){
            e.preventDefault();
            saveProfile();
        });

        // Save Update ID Card Photo
        $('#do_save_idcard_photo').click(function(e){
            e.preventDefault();
            var url         = $(this).data('url');
            var form_data   = new FormData();
            if ( idcard_img ) {
                // Get Token
                form_data.append(App.kdName(), App.kdToken());

                // Get Image Upload
                $.each(idcard_img, function(key, value){
                    form_data.append('idcard_img', value);
                });

                $.ajax({
                    type:   "POST",
                    data:   form_data,
                    url:    url,
                    contentType:false,
                    processData:false,
                    cache:false,
                    beforeSend: function (){
                        App.run_Loader('roundBounce');
                    },
                    success: function( response ){
                        App.close_Loader();
                        response = $.parseJSON(response);
                        if( response.status == 'access_denied' ){
                            $(location).attr('href',response.url);
                        }else{
                            if( response.status == 'success'){
                                App.notify({
                                    icon: 'fa fa-check-circle', 
                                    title: 'Success', 
                                    message: response.message, 
                                    type: 'success',
                                });
                                $('.btn-idcard-photo').hide();
                                $('#idcard_file, .file-image').val('');
                            }else{
                                App.notify({
                                    icon: 'fa fa-exclamation-triangle', 
                                    title: 'Failed', 
                                    message: response.message, 
                                    type: 'danger',
                                });
                            }
                        }
                        return false;
                    },
                    error: function( jqXHR, textStatus, errorThrown ) {
                        App.close_Loader();
                        bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                            location.reload();
                        });
                    }
                });
            } else {
                App.notify({
                    icon: 'fa fa-exclamation-triangle', 
                    title: 'Foto KTP', 
                    message: 'Anda belum upload Foto KTP', 
                    type: 'danger',
                });
            }
            return false;
        });
        
        // Save Update Logo Image
        $('#do_save_logo_image').click(function(e){
            e.preventDefault();
            var url         = $(this).data('url');
            var form_data   = new FormData();
            if ( logo_img ) {
                // Get Token
                form_data.append(App.kdName(), App.kdToken());

                // Get Image Upload
                $.each(logo_img, function(key, value){
                    form_data.append('logo_img', value);
                });

                $.ajax({
                    type:   "POST",
                    data:   form_data,
                    url:    url,
                    contentType:false,
                    processData:false,
                    cache:false,
                    beforeSend: function (){
                        App.run_Loader('roundBounce');
                    },
                    success: function( response ){
                        App.close_Loader();
                        response = $.parseJSON(response);
                        if( response.status == 'access_denied' ){
                            $(location).attr('href',response.url);
                        }else{
                            if( response.status == 'success'){
                                App.notify({
                                    icon: 'fa fa-check-circle', 
                                    title: 'Success', 
                                    message: response.message, 
                                    type: 'success',
                                });
                                $('.btn-logo-image').hide();
                                $('#logo_file, .file-image').val('');
                            }else{
                                App.notify({
                                    icon: 'fa fa-exclamation-triangle', 
                                    title: 'Failed', 
                                    message: response.message, 
                                    type: 'danger',
                                });
                            }
                        }
                        return false;
                    },
                    error: function( jqXHR, textStatus, errorThrown ) {
                        App.close_Loader();
                        bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                            location.reload();
                        });
                    }
                });
            } else {
                App.notify({
                    icon: 'fa fa-exclamation-triangle', 
                    title: 'Logo Image', 
                    message: 'Anda belum upload Logo Image', 
                    type: 'danger',
                });
            }
            return false;
        });

        // Save Update Profile Photo
        $('#do_save_profile_photo').click(function(e){
            e.preventDefault();
            var url         = $(this).data('url');
            var form_data   = new FormData();
            if ( profile_img ) {
                // Get Token
                form_data.append(App.kdName(), App.kdToken());

                // Get Image Upload
                $.each(profile_img, function(key, value){
                    form_data.append('profile_img', value);
                });

                $.ajax({
                    type:   "POST",
                    data:   form_data,
                    url:    url,
                    contentType:false,
                    processData:false,
                    cache:false,
                    beforeSend: function (){
                        App.run_Loader('roundBounce');
                    },
                    success: function( response ){
                        App.close_Loader();
                        response = $.parseJSON(response);
                        if( response.status == 'access_denied' ){
                            $(location).attr('href',response.url);
                        }else{
                            if( response.status == 'success'){
                                App.notify({
                                    icon: 'fa fa-check-circle', 
                                    title: 'Success', 
                                    message: response.message, 
                                    type: 'success',
                                });
                                $('.btn-profile-photo').hide();
                                $('[name=profile_img]').val('');
                            }else{
                                App.notify({
                                    icon: 'fa fa-exclamation-triangle', 
                                    title: 'Failed', 
                                    message: response.message, 
                                    type: 'danger',
                                });
                            }
                        }
                        return false;
                    },
                    error: function( jqXHR, textStatus, errorThrown ) {
                        App.close_Loader();
                        bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                            location.reload();
                        });
                    }
                });
            } else {
                App.notify({
                    icon: 'fa fa-exclamation-triangle', 
                    title: 'Foto KTP', 
                    message: 'Anda belum memilih Foto Profil', 
                    type: 'danger',
                });
            }
            return false;
        });

        // Save Change Password
        $('#do_save_cpassword').click(function(e){
            e.preventDefault();
            var form  = $(this).data('form');
            saveCpassword(form);
        });

        // Save Change Password
        $('#do_save_cpassword_pin').click(function(e){
            e.preventDefault();
            var form  = $(this).data('form');
            saveCpassword(form);
        });
    };

    // --------------------------------------------------------------------------------------
    // General Function
    // --------------------------------------------------------------------------------------

    // Save Profile
    var saveProfile = function() {
        var form_personal   = $('#personal');
        var url             = form_personal.attr('action');
        var data            = form_personal.serialize();

        $.ajax({
            type:   "POST",
            data:   data,
            url:    url,
            beforeSend: function (){
                App.run_Loader('roundBounce');
                $('#save_profile').modal('hide');
            },
            success: function( response ){
                App.close_Loader();
                response = $.parseJSON(response);

                if( response.status == 'login' ){
                    $(location).attr('href',response.url);
                }else{
                    if( response.status == 'success'){
                        if( response.logout ){
                            $(location).attr('href',response.logout);
                        }else{
                            App.notify({
                                icon: 'fa fa-check', 
                                message: response.message, 
                                type: 'success',
                            });
                        }
                    } else {
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            message: response.message, 
                            type: 'danger',
                        });
                    }
                }
                App.scrollTo($('body'), 0);
            }
        });
        return false;
    };

    // Save Change Password
    var saveCpassword   = function(form = '') {
        if ( form ) {
            var form_cpass  = $('#'+form);
        } else {
            var form_cpass  = $('#cpassword');
        }
        var error       = $('.alert-danger', form_cpass);
        var success     = $('.alert-success', form_cpass);

        var url         = form_cpass.attr('action');
        var data        = form_cpass.serialize();

        $.ajax({
            type:   "POST",
            data:   data,
            url:    url,
            beforeSend: function (){
                App.run_Loader('roundBounce');
                $('#save_cpassword').modal('hide');
                $('#save_cpassword_pin').modal('hide');
            },
            success: function( response ){
                App.close_Loader();
                response = $.parseJSON(response);

                if(response.message == 'error'){
                    if( response.login == 'login' ){
                        $(location).attr('href',response.data);
                    }else{
                        App.notify({
                            icon: 'fa fa-exclamation-triangle', 
                            message: response.data, 
                            type: 'danger',
                        });
                    }

                    // App.scrollTo($('body'), 0);
                }else{
                    if( response.access == "admin" ){
                        // error.hide();
                        // success.empty();
                        // success.html(response.data).fadeIn('fast');
                        $('input[type="password"]', form_cpass).val('');
                        if ( $('#modal-change-password').length ) {
                            $('#modal-change-password').modal('hide');
                            bootbox.alert(response.data, function(){ 
                                location.reload();
                            });
                        } else {
                            App.notify({
                                icon: 'fa fa-check', 
                                message: response.data, 
                                type: 'success',
                            });
                        }
                    }else{
                        $(location).attr('href',response.data);
                    }
                }
            }
        });
    };

	return {
		init: function() {
            handleProfile();
		}
	};
}();

// ===========================================================
// General Setting Function
// ===========================================================
var GeneralSetting = function() {
    // Notification Setting Update
    var handleSaveSetting = function(url, value){
        if ( ! url || url == undefined || value == undefined ) { 
            return; 
        }
        $.ajax({
            type: "POST",
            url: url,
            data: { 'value' : value },
            beforeSend: function (){ App.run_Loader('roundBounce'); },
            success: function( response ){ 
                App.close_Loader();
                response = $.parseJSON(response);
                if( response.status == 'login' ){
                    $(location).attr('href',response.url);
                }else{
                    if( response.status == 'success'){
                        var type = 'success';
                        var icon = 'fa fa-check';
                    }else{
                        var type = 'danger';
                        var icon = 'fa fa-exclamation-triangle';
                    }
                    App.notify({
                        icon: icon, 
                        title: 'Informasi', 
                        message: response.message, 
                        type: type,
                    });
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
    };

    // General Function
    var handleGeneralSetting = function() {
        // Update General Setting
        // -----------------------------------------------
        $('button.general-setting').click(function(e){
            e.preventDefault();
            var url     = $(this).data('url');
            var id      = $(this).data('id');
            var type    = $(this).data('type');
            var wraper  = $(this).data('wraper');
            var value   = $('#'+id).val();

            handleSaveSetting(url, value);
        });

        // Update General Setting
        // -----------------------------------------------
        $('button.general-setting-each').click(function(e){
            e.preventDefault();
            var url     = $(this).data('url');
            var type    = $(this).data('type');
            var data    = new FormData();

            // Get Token
            data.append(App.kdName(), App.kdToken());
            
            // get inputs
            $('textarea.'+type+', select.'+type+', input.'+type).each(function(){
                data.append($(this).attr("name"), $(this).val());
            });

            bootbox.confirm("Apakah anda yakin akan simpan data pengaturan ini ?", function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        data:   data,
                        processData:false,
                        contentType:false,
                        cache:false,
                        async:false,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);
                            if( response.token ){
                                App.kdToken(response.token);
                            }
                            if( response.status == 'login' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    var _type = 'success';
                                    var _icon = 'fa fa-check';
                                }else{
                                    var _type = 'danger';
                                    var _icon = 'fa fa-exclamation-triangle';
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
        });

        // Button Action Get Data Notification 
        // -----------------------------------------------
        $("body").delegate( "a.notifdata", "click", function( e ) {
            e.preventDefault();
            var url     = $(this).attr('href');
            var wrapper = $('#notification_list').parents('.dataTables_wrapper');
            var m_edit  = $('#modal-form-notification');
            var m_view  = $('#notification_view_modal');
            var _form   = $('#form_notif_edit');

            $.ajax({
                type:   "POST",
                url:    url,
                beforeSend: function (){
                    App.run_Loader('roundBounce');
                    _form[0].reset();
                },
                success: function( response ){
                    App.close_Loader();
                    response    = $.parseJSON(response);
                    if( response.status == 'login' ){
                        $(location).attr('href',response.url);
                    }else{
                        if( response.status == 'success'){
                            if ( response.process == 'edit' ) {
                                $('#notif_edit_title').text(response.notification.name);
                                $('#notif_id', _form).val(response.notification.id);
                                $('#notif_type', _form).val(response.notification.type);
                                $('#notif_title', _form).val(response.notification.title);
                                $('#notif_status', _form).val(response.notification.status);
                                $('#notif_content_plain', _form).val(response.notification.content);
                                CKEDITOR.instances['notif_content_email'].setData( response.notification.content );
                                CKEDITOR.instances['notif_content_email'].resize(CKEDITOR.instances['notif_content_email'].width, 300);
                                if ( response.notification.type == 'email' ) {
                                    $('#content_email', _form).show();
                                    $('#content_plain', _form).hide();
                                    $('#notif_edit_type').text('Email');
                                    $('#notif_edit_color').removeClass('label-success').addClass('label-primary');
                                    $('#notif_edit_icon').removeClass('fa-whatsapp').addClass('fa-envelope');
                                } else {
                                    $('#content_email', _form).hide();
                                    $('#content_plain', _form).show();
                                    $('#notif_edit_type').text('WhatsApp');
                                    $('#notif_edit_color').removeClass('label-primary').addClass('label-success');
                                    $('#notif_edit_icon').removeClass('fa-envelope').addClass('fa-whatsapp');
                                }
                                m_edit.modal('show');
                            } else {
                                $('#notif_view_title').text(response.notification.title);
                                $('#notif_view_content').html(response.notification.content);
                                if ( response.notification.type == 'email' ) {
                                    $('#notif_view_type').text('Email');
                                    $('#notif_view_color').removeClass('label-success').addClass('label-primary');
                                    $('#notif_view_icon').removeClass('fa-whatsapp').addClass('fa-envelope');
                                }else{
                                    $('#notif_view_type').text('WhatsApp');
                                    $('#notif_view_color').removeClass('label-primary').addClass('label-success');
                                    $('#notif_view_icon').removeClass('fa-envelope').addClass('fa-whatsapp');
                                }
                                m_view.modal('show');
                            }
                        }else{
                            App.alert({
                                type: 'danger',
                                icon: 'warning',
                                message: response.message,
                                container: wrapper,
                                place: 'prepend'
                            });
                        }
                    }
                }
            });
        }); 
    };

    // Handle Form Company Setting Function
    var handleFormSettingCompany = function() {
        var form        = $( '#form-setting-company' );
        var wrapper     = $( '.wrapper-setting-company' );
        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                company_name: {
                    required: true,
                },
                company_phone: {
                    required: true,
                },
                company_email: {
                    email: true,
                    required: true,
                },
                company_province: {
                    required: true,
                },
                company_city: {
                    required: true,
                },
                company_subdistrict: {
                    required: true,
                },
                company_address: {
                    required: true,
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent(".input-group"));
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
                $(element).closest('.form-group').addClass('has-danger'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-danger'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-danger'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url         = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan simpan data infomasi Perumahan ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('roundBounce');
                            },
                            success: function( response ){
                                App.close_Loader();
                                response = $.parseJSON(response);

                                if( response.token ){
                                    App.kdToken(response.token);
                                }
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        App.notify({
                                            icon: 'fa fa-check-circle', 
                                            title: 'Success', 
                                            message: response.message, 
                                            type: 'success',
                                        });
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

    // Handle Form Company Billing Function
    var handleFormSettingCompanyBilling = function() {
        var form        = $( '#form-setting-company-billing' );
        var wrapper     = $( '.wrapper-setting-company-billing' );
        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                company_bank: {
                    required: true,
                },
                company_bill: {
                    required: true,
                },
                company_bill_name: {
                    required: true,
                },
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent(".input-group"));
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
                $(element).closest('.form-group').addClass('has-danger'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-danger'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-danger'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url         = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan simpan data Informasi Bank Perusahaan ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('roundBounce');
                            },
                            success: function( response ){
                                App.close_Loader();
                                response = $.parseJSON(response);

                                if( response.token ){
                                    App.kdToken(response.token);
                                }
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        App.notify({
                                            icon: 'fa fa-check-circle', 
                                            title: 'Success', 
                                            message: response.message, 
                                            type: 'success',
                                        });
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

    // Handle Form Company Billing Function
    var handleFormSettingStockistOrder = function() {
        var form        = $( '#form-setting-stockist-order' );
        var wrapper     = $( '.wrapper-setting-stockist-order' );
        if ( ! form.length ) {
            return;
        }

        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                minimal_qty: {
                    required: true,
                },
                minimal_nominal: {
                    required: true,
                },
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent(".input-group"));
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
                $(element).closest('.form-group').addClass('has-danger'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-danger'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-danger'); // set success class to the control group
            },
            submitHandler: function (form) {
                var url         = $(form).attr('action');
                bootbox.confirm("Apakah anda yakin akan simpan data Minimal Stockist Order ini ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('roundBounce');
                            },
                            success: function( response ){
                                App.close_Loader();
                                response = $.parseJSON(response);

                                if( response.token ){
                                    App.kdToken(response.token);
                                }
                                
                                if( response.status == 'access_denied' ){
                                    $(location).attr('href',response.url);
                                }else{
                                    if( response.status == 'success'){
                                        App.notify({
                                            icon: 'fa fa-check-circle', 
                                            title: 'Success', 
                                            message: response.message, 
                                            type: 'success',
                                        });
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

    // Handle Form Reward Setting Function
    var handleFormRewardSetting = function() {
        var form        = $( '#form-setting-reward' );
        var wrapper     = $( '.box-body' );
        if ( ! form.length ) {
            return;
        }

        // Handle Validation Setting Reward
        // ---------------------------------
        form.validate({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                reward: {
                    required: true,
                },
                nominal: {
                    required: true,
                },
                point: {
                    required: true,
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
                bootbox.confirm("Anada yakin akan simpan data setting Reward ?", function(result) {
                    if( result == true ){
                        $.ajax({
                            type:   "POST",
                            url:    url,
                            data:   $(form).serialize(),
                            beforeSend: function (){
                                App.run_Loader('roundBounce');
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
                                    $(location).attr('href', response.url);
                                }

                                App.notify({
                                    icon: alert_icon, 
                                    message: response.message, 
                                    type: alert_type,
                                });
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

        // Period Reward Change
        // ---------------------------------
        $("body").delegate( $(':input[name=is_lifetime]', form), "change", function( e ) {
            e.preventDefault();
            if ( $(':input[name=is_lifetime]').prop("checked") == true ) {
                $('#period_reward').hide();
            } else {
                $('#period_reward').show();
            }
        });
    };

    // Handle Form Grade Upgrade Function
    var handleFormSettingGradeUpgrade = function() {
        var form        = $( '#form-setting-grade-upgrade' );
        var wrapper     = $( '.wrapper-setting-grade-upgrade' );
        if ( ! form.length ) {
            return;
        }

        var url         = form.attr('action');
        form.submit(function(event) {
            event.preventDefault();
            bootbox.confirm("Apakah anda yakin akan simpan data setting kenaikan peringkat ?", function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        data:   form.serialize(),
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);

                            if( response.token ){
                                App.kdToken(response.token);
                            }
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
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
                            bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                                location.reload();
                            });
                        }
                    });
                }
            });
            return false;
        });
    };

    // Handle Form Grade Mintain Function
    var handleFormSettingGradeMintain = function() {
        var form        = $( '#form-setting-grade-maintain' );
        var wrapper     = $( '.wrapper-setting-grade-maintain' );
        if ( ! form.length ) {
            return;
        }

        var url         = form.attr('action');
        form.submit(function(event) {
            event.preventDefault();
            bootbox.confirm("Apakah anda yakin akan simpan data setting mempertahankan peringkat ?", function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        data:   form.serialize(),
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response = $.parseJSON(response);

                            if( response.token ){
                                App.kdToken(response.token);
                            }
                            
                            if( response.status == 'access_denied' ){
                                $(location).attr('href',response.url);
                            }else{
                                if( response.status == 'success'){
                                    App.notify({
                                        icon: 'fa fa-check-circle', 
                                        title: 'Success', 
                                        message: response.message, 
                                        type: 'success',
                                    });
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
                            bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                                location.reload();
                            });
                        }
                    });
                }
            });
            return false;
        });
    };

    return {
        init: function() {
            handleGeneralSetting();
            handleFormSettingCompany();
            handleFormSettingCompanyBilling();
            handleFormSettingStockistOrder();
        },
        initGrade: function() {
            handleFormSettingGradeUpgrade();
            handleFormSettingGradeMintain();
        },
        initReward: function() {
            handleFormRewardSetting();
        }
    };
}();

// ===========================================================
// Staff
// ===========================================================
var Staff = function() {
    var handleAccess = function() {
        var _staffAccessToggle = function( val ) {
            $( '.staff-access-box' ).hide();
            $( '.staff-access-box.staff-access-box-' + val ).show( 'fast' );
        };
        
        _staffAccessToggle( $( 'input[name=staff_access]:checked' ).val() );
        
        $('label.staff-access-toggle').click( function() {
            var input = $( this ).find( 'input[name=staff_access]' );
            var val = input.val();
            
            input.attr( 'checked', 'checked' );
            _staffAccessToggle( val );
        });

        // PIN Order Edit
        $("body").delegate( "a.delstaff", "click", function( event ) {
            event.preventDefault();
            var url = $(this).attr('href');
            var table_container = $('#list_pin_member').parents('.dataTables_wrapper');

            bootbox.confirm("Anda yakin akan menghapus Akun Staff?", function(result) {
                if( result == true ){
                    $.ajax({
                        type:   "POST",
                        url:    url,
                        beforeSend: function (){
                            App.run_Loader('roundBounce');
                        },
                        success: function( response ){
                            App.close_Loader();
                            response    = $.parseJSON(response);
                            if ( response.status == 'access_denied' ) {
                                $(location).attr('href', response.url);
                            }
                            if( response.success ){
                                msg = 'Staff telah berhasil di hapus !';
                                App.alert({
                                    type: 'success',
                                    icon: 'check',
                                    message: msg,
                                    container: table_container,
                                    place: 'prepend'
                                });
                            }else{
                                msg = 'Member tidak berhasil di hapus !';
                                App.alert({
                                    type: 'danger',
                                    icon: 'warning',
                                    message: msg,
                                    container: table_container,
                                    place: 'prepend'
                                });
                            }
                            $('#btn_list_table_staff').trigger('click');
                        },
                        error: function( jqXHR, textStatus, errorThrown ) {
                            App.close_Loader();
                            App.notify({
                                icon: 'fa fa-exclamation-triangle', 
                                message: 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', 
                                type: 'danger',
                            });
                        }
                    });
                }
            });
        });
    };

    var handleResetPasswordStaff = function() {
        var formStaffResetPass  = $('#form_staff_reset_password');

        $("body").delegate(".grid-reset-password-staff", "click", function( event ) {
            event.preventDefault();
            var url = $(this).attr('href');
            var table_container = $('#list_staff').parents('.dataTables_wrapper');
            $.ajax({
                type:   "POST",
                url:    url,
                beforeSend: function (){
                    App.run_Loader('roundBounce');
                    formStaffResetPass[0].reset();
                },
                success: function( response ){
                    App.close_Loader();
                    response = $.parseJSON(response);
                    if( response.status == 'login' ){
                        $(location).attr('href',response.message);
                    }else{
                        if( response.status == 'error'){
                            App.alert({
                                type: 'danger',
                                icon: 'warning',
                                message: response.message,
                                container: wrapper,
                                place: 'prepend'
                            });
                        }else{
                            $('#staff_id', '#form_staff_reset_password').val(response.data.id);
                            $('#staff_username', '#form_staff_reset_password').val(response.data.username);
                            $('#modal_staff_reset_password').modal('show');
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
        });

        $("body").delegate("#btn-staff-reset-password", "click", function( event ) {
            event.preventDefault();
            
            var url         = $(formStaffResetPass).attr('action');
            var data        = $(formStaffResetPass).serialize();
            var wrapper     = $('.wrapper-form_staff_reset_password');
            var _container  = $('#list_table_staff').parents('.dataTables_wrapper');

            $.ajax({
                type: "POST",
                data: data,
                url: url,
                beforeSend: function (){
                    App.run_Loader('roundBounce');
                    $('.alert').hide();
                },
                success: function( response ){
                    App.close_Loader();
                    response = $.parseJSON(response);                    
                    if( response.status == 'login' ){
                        $(location).attr('href',response.message);
                    }else{
                        if( response.status == 'success'){
                            App.alert({
                                type: 'success',
                                icon: 'check',
                                message: response.message,
                                container: _container,
                                place: 'prepend'
                            });
                            formStaffResetPass[0].reset();
                            $('#modal_staff_reset_password').modal('hide');
                        } else {
                            App.alert({
                                type: 'danger',
                                message: response.message,
                                container: wrapper,
                                place: 'prepend'
                            });
                        }
                        return false;
                    }                    
                },
                error: function( jqXHR, textStatus, errorThrown ) {
                    App.close_Loader();
                    bootbox.alert('Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', function(){ 
                        location.reload();
                    });
                }
            });
        });
    };
    
    return {
        init: function() {
            handleAccess();
            handleResetPasswordStaff();
        }
    };
}();