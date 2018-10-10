var url = "https://core.enkargo.com.co/services/meli_bench_manager.php"
function get_sellers_details(){
	$.post(url,{
		action : 'get_sellers_detail'
	}).done(function(e){
		var response = JSON.parse(e);
		$('#top_sellers > tbody').append(response.top_seller);
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

function get_local_items(){
	$.post(url,{
		action : 'get_local_items',
	}).done(function(e){
		var response = JSON.parse(e);
		$('#qb_items > tbody').append(response.items_qb);
		init_DataTables_local("qb_items");
		$('#mx_items > tbody').append(response.items_mx);
		init_DataTables_local("mx_items");
		
	});
}

function get_update_price(id_item){
	$('#update_price_modal').attr("id_item",id_item);
	$(".price_update").modal("show");
	$("#local_price").val(" ");
}

function cancel_modal(){
	$(".modal_cancel").empty();
}

function set_update_price(){
	$.post(url,{
		action : 'set_update_price',
		item_id: $("#update_price_modal").attr("id_item"),
		price : $("#local_price").val(),
		shop : $("#shop").val()
	}).done(function(e){
		var response = JSON.parse(e);
		if (response.response == 1) {
			alert("Item actualizado con éxito!");
		}else{
			alert("Ha ocurrido un error al actualizar el ítem");
		}
	});
}

function get_top_sales(){
	$.post(url,{
		action : 'get_top_sales'
	}).done(function(e){
		var response = JSON.parse(e);
		for(var r in response){
			$('#sales_content').append(response[r].content);
		}
		$('[data-toggle="tooltip"]').tooltip();
	});
}

function view_table(id){
	if ($('#id_'+id).is(':hidden')) {
		$('#id_'+id).show();				
	}else{
		$('#id_'+id).hide();				
	}
}

function hide_sale(category_id){
	$('#'+category_id).hide();
}

function delete_sale(category_id){
	$.post(url,{
		action:'delete_top_sale_category',
		id_category: category_id
	}).done(function(e){
		var response = JSON.parse(e);
		if (response.result == 1) {
			alert("Categoría Eliminada con éxito!");
		}else{
			alert("Ha ocurrido un error al eliminar la categoría");
		}
	});
}