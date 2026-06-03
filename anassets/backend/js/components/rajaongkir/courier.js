/*
|--------------------------------------------------------------------------
| Sub-district on change
|--------------------------------------------------------------------------
*/
$('.rajaongkir-subdistrict').change(function () {
    appendCourier();
});

/*
|--------------------------------------------------------------------------
| Appending courier
|--------------------------------------------------------------------------
*/
function appendCourier() {

    $.ajax({
        url: base_url + 'address/get_courier',
        method: "POST",
        dataType: 'json',
        beforeSend: function () {
            resetCourier();
            $('[name="courier"]').parent().append('<span class="spinner-border"></span>');
        },
        success: function (response) {
            //console.log(response);

            $('[name="courier"]').attr("readonly", false);
            $('.spinner-border').remove();

            var optionsData = "<option value='' disabled selected>-- Silahkan Pilih Kurir --</option>";
            for (i = 0; i < response.length; i++) {
                var val = response[i]['code'];
                var text = response[i]['name'];
                optionsData += "<option value='" + val + "'>" + text + "</option>";
            }
            // append options data
            $('[name="courier"]').append(optionsData);
        },
        error: function (response) {
            //console.log(response);
            swal("Maaf!", "Please Try Again..", "error")
        },
        complete: function () {},
    });
}

/*
|--------------------------------------------------------------------------
| Choosing courier
|--------------------------------------------------------------------------
*/
$('[name="courier"]').change(function () {

    var courier     = $(this).val();
    var weight      = $('[name="weight"]').val();
    var destination = $('[name="shipping_subdistrict"]').val();
    var opt_agent   = '';

    if ( $('.question-reg').length ) {
        if ( $('label#opt-agent').hasClass('active') ) {
            opt_agent = 'agent';
        }
    }

    $.ajax({
        url: base_url + 'address/get_courier_cost',
        method: "POST",
        data: {
            courier: courier,
            weight: weight,
            destination: destination,
            opt_agent: opt_agent
        },
        dataType: 'json',
        beforeSend: function () {
            resetTotalAmount(opt_agent);
            $('[name="courier_service"]').attr("readonly", true).empty().parent().append('<span class="spinner-border"></span>') // empty dropdown
        },
        success: function (response) {
            //console.log(response);
            if (response.status == 'success') {
                $('.spinner-border').remove();
                $('[name="courier_service"]').attr("readonly", false);
                // append options data
                $('[name="courier_service"]').append(response.data);
            } else {
                $('.spinner-border').remove();
                swal("Maaf!", response.message, "error")
            }

            // if (response['rajaongkir']['status']['code'] == 400) {
            //     resetCourier();
            //     $('.spinner-border').remove();
            //     // swal("Maaf!", response['rajaongkir']['status']['description'], "error")
            // } else {

            //     $('[name="courier_service"]').attr("readonly", false);
            //     $('.spinner-border').remove();

            //     // var optionsData = "<option value='' disabled selected>-- Silahkan Pilih Layanan Kurir --</option>";
            //     // for (i = 0; i < response['rajaongkir']['results'][0]['costs'].length; i++) {
            //     //     var val = response['rajaongkir']['results'][0]['costs'][i]['service'] + ',' + response['rajaongkir']['results'][0]['costs'][i]['cost'][0]['value'];
            //     //     var text = response['rajaongkir']['results'][0]['costs'][i]['service'] + ' - ' + response['rajaongkir']['results'][0]['costs'][i]['description'];
            //     //     optionsData += "<option value='" + val + "'>" + text + "</option>";
            //     // }
            //     // append options data
            //     $('[name="courier_service"]').append(optionsData);
            // }
        },
        error: function (response) {
            //console.log(response);
            swal("Maaf!", "Please Try Again..", "error")
        },
        complete: function () {},
    });
});

/*
|--------------------------------------------------------------------------
| Show ongkir
|--------------------------------------------------------------------------
*/
$('[name="courier_service"]').change(function () {
    var courier_cost    = $(this).val().split(",");
    var total_checkout  = infoCart().total_amount;
    total_checkout      = parseInt(total_checkout);
    courier_cost        = parseInt(courier_cost[1]);
    
    var total_payment   = courier_cost + total_checkout;

    if ( $('.question-reg').length ) {
        var opt_agent       = $('label#opt-agent').hasClass('active');
        var register_fee    = $('.register-fee').data('regfee');
        if ( opt_agent ) {
            register_fee    = parseInt(register_fee);
            total_payment   = total_payment + register_fee;
        }
    }

    $('[name="courier_cost"]').val(courier_cost);
    $('.courier-cost').text(accounting(courier_cost));
    $('.total-checkout').text(accounting(total_payment));
});