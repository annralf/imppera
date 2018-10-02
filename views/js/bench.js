var url = "https://core.enkargo.com.co/services/meli_bench_manager.php"
function get_sellers_details(){
	$.post(url,{
		action : 'get_sellers_detail'
	}).done(function(e){
		var response = JSON.parse(e);
		$('#top_sellers > tbody').append(response.top_seller);
		//init_DataTables_local("top_sellers");
		$('#oficial_sellers > tbody').append(response.seller_oficial);
		init_DataTables_local("oficial_sellers");
		$('#oficial_no_sellers > tbody').append(response.seller_no_oficial);
		init_DataTables_local("oficial_no_sellers");
	});
}

function get_seller_details(){
	var seller_id = sessionStorage.getItem('seller_id');
	$.post(url,{
		action : 'get_seller_detail'
	}).done(function(e){
		var response = JSON.parse(e);
		$('#top_sellers > tbody').append(response.top_seller);
		$('#oficial_sellers > tbody').append(response.seller_oficial);
		init_DataTables_local("oficial_sellers");
		$('#oficial_no_sellers > tbody').append(response.seller_no_oficial);
		init_DataTables_local("oficial_no_sellers");
	});
}

function set_seller_session_id(seller_id){
	sessionStorage.setItem('seller_id', seller_id);
}
