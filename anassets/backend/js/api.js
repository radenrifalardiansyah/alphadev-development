var API = function() {
	var host = window.document.location.hostname;
	var url = function( scheme, method ) {
		return '//' + host + '/' + scheme + '/' + method;
	};
	
	var call = function( scheme, method, data, callback ) {
		$.ajax({
            type: "POST",
            data: data,
            url: url( scheme, method ),
            beforeSend: function (){
                App.run_Loader('timer');
            },
            success: function( response ){
                App.close_Loader();
                response = $.parseJSON( response );

                if( response.token ){
                    App.kdToken(response.token);
                }

                if( response.status == 'access_denied' ){
                    $(location).attr('href',response.url);
                }

                if ( typeof( callback ) == "function" ) {
                	return callback( response );
                }
                return response;
            },
            error: function( jqXHR, textStatus, errorThrown ) {
            	App.close_Loader();
            	if ( typeof( callback ) == "function" ) {
                	return callback( false );
                }
            	return false;
            }
        });
	};
	
	return {
		get: function( scheme, data, callback ) {
			return call( scheme, 'get', data, callback );
		},
		put: function( scheme, data, callback ) {
			return call( scheme, 'put', data, callback );
		}
	};
}();
