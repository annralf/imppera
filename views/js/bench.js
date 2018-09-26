var url = "https://core.enkargo.com.co/services/meli_bench_manager.php"
function get_sellers_details(){
	$.post(url,{
		action : 'get_sellers_detail'
	}).done(function(e){
		var response = JSON.parse(e);
		console.log(response.seller_oficial);
	});
}