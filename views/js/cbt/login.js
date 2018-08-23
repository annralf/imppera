function login_cbt(client_id, redirect_uri) {
	$.get('https://cbt.mercadolibre.com/merchant/authorization/',
		{
			client_id : client_id,
			redirect_uri : redirect_uri
		}).done(function(){
			alert('Welcome to CBR API CORE');
		});
}