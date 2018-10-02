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
		action : 'get_seller_detail',
		seller_id: seller_id
	}).done(function(e){
		var response = JSON.parse(e);
		$('#shop_name').replaceWith('<h1>'+response.seller_info.nick_name+'</h1>');
		$('#total_sales').replaceWith('<h2> Total de ventas:'+response.seller_info.transactions_completed+'</h2>');
		$('#total_stock').replaceWith('<h2> Total de visitas:'+response.seller_info.total_stock+'</h2>');
		$('#seller_sales_header').append(response.seller_sales_header);
		$('#seller_sales_body').append(response.seller_sales_body);
		$('#items_sellers > tbody').append(response.seller_top_items);
		init_DataTables_local("items_sellers");
		console.log(response);
		
	});
}

function set_seller_session_id(seller_id){
	sessionStorage.setItem('seller_id',seller_id);
	$(location).attr('href','https://core.enkargo.com.co/views/seller_detail.html');
}
