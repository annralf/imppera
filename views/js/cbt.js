//--------------------- Index page manager
function upload_video(){
	$.post('../services/cbt_manager.php',
	{
		action: 'upload_video',
		shop : $('#shop').val(),
		video : $('#video_url').val()
	}).done(function(e){
		$(".video_manager").attr('dismiss', 'modal');
		if (e == 1) {
			var shop_id = $('#shop_id').val();
			$('#shop').val("");
			$('#video_url').val("");
			alert("Video almacenado con éxito");
		}else{
			alert("Ha ocurrido un error a procesar la información!");
		}
	}).fail(function(){
		alert("Ha ocurrido un error a procesar la información!");
	});
}
function display_video(element){
	$('#video_display').attr('src', $(element).val()+'?autoplay=1');
}
function load_items(option){
	var amz_check = [];
	$(".amz_check:checked").each(function() {
		amz_check.push($(this).val());		
	});
	$.post('https://core.enkargo.com.co/core_enkargo/services/loadItemsCbt.php', {
		application: option,
		items :amz_check 
	}).done(function(e){
		alert(e['msg']);
	});
	console.log(amz_check);
}

function go_to_cbt_core(site_name){
	$(location).attr('href','cbt_manager.html');
}

function price_tool(){
	console.log('price_tool');
	var shop_id = $('#shop_id').val();
	var range_1 = $('#range_1').val()/100;
	var range_2 = $('#range_2').val()/100;
	var range_3 = $('#range_3').val()/100;
	var range_4 = $('#range_4').val()/100;
	var date = new Date();
	$.post('../services/cbt_manager.php',
	{
		action: 'add_price_manager',
		range_1 : range_1,
		range_2 : range_2,
		range_3 : range_3,
		range_4 : range_4,
		shop_id : shop_id
	}).done(function(e){
		$("#price_tool_modal").attr('dismiss', 'modal');
		if (e == 1) {
			var shop_id = $('#shop_id').val();
			$('#range_1').val("");
			$('#range_2').val("");
			$('#range_3').val("");
			$('#range_4').val("");
			alert("Success");
		}else{
			alert("Ha ocurrido un error a procesar la información!");
		}
	}).fail(function(){
		alert("Ha ocurrido un error a procesar la información!");
	});
}

function get_ctb_items_mauxi() {
	console.log('getting items mauxi');
	$.post('../services/cbt_manager.php',{action: 'get_items', application : 3, limit : 2, offset :0}).fail(function(){

	}).done(function(e){
		var rows = "";
		var rows_1 = "";
		var response = JSON.parse(e); 
		for(var i in response){
			rows += "<tr>";
			rows += "<td style='width: 100px; word-wrap: break-word;'>"+response[i].sku+"</td>";
			rows += "<td style='width: 100px; word-wrap: break-word;'>"+response[i].category+"</td>";
			rows += "<td style='width: 450px; word-wrap: break-word;'>"+response[i].title+"</td>";
			rows += "<td style='width: 100px; word-wrap: break-word;'>$"+response[i].price+"</td>";
			rows += "<td style='width: 100px; word-wrap: break-word;'>"+response[i].status+"</td>";
			rows += "<td>";
			rows += "<a class='btn' title='Ver detalles del ítem' onclick='get_ctb_item_detail(\"view\",\""+response[i].sku+"\",3,"+response[i].id+")'>";
			rows += "<i class='fa fa-eye'></i> ";
			rows += "</a>";
			rows += "<a class='btn' title='Editar información del ítem' onclick='get_ctb_item_detail(\"update\",\""+response[i].sku+"\",3,"+response[i].id+")'>";
			rows += "<i class='fa fa-edit'></i>";
			rows += "</a>";
			rows += "<a class='btn' title='Eliminar ítem' onclick='get_ctb_item_delete("+response[i].id+",\""+response[i].sku+"\",3,"+response[i].id+")'>";
			rows += "<i class='fa fa-trash'></i>";
			rows += "</a>";
			rows += "<a class='btn' title='Publicar ítem' onclick='get_ctb_item_post(\""+response[i].sku+"\",3)'>";
			rows += "<i class='fa fa-bullhorn'></i>";
			rows += "</a>";
			rows += "</td>";
			rows += "</tr>";			
		}
		$('#cbt_items_list_mauxi').append(rows);
		init_DataTables_local("cbt_items_list_mauxi");
	});
}

function get_ctb_items_queen_bee() {
	console.log('getting items queen bee');
	$.post('http://localhost/CBT_core/core/routing/cbtGetItems.php',{ application : 2, limit : 5}).fail(function(){

	}).done(function(e){
		var rows = "";
		var rows_1 = "";
		var response = e; 
		for(var i in response){
			rows += "<tr>";
			rows += "<td style='width: 100px; word-wrap: break-word;'>"+response[i].mpid+"</td>";
			rows += "<td style='width: 100px; word-wrap: break-word;'>"+response[i].category+"</td>";
			rows += "<td style='width: 450px; word-wrap: break-word;'>"+response[i].title+"</td>";
			rows += "<td style='width: 100px; word-wrap: break-word;'>$"+response[i].price+"</td>";
			rows += "<td style='width: 100px; word-wrap: break-word;'>"+response[i].status+"</td>";
			rows += "<td>";
			rows += "<a class='btn' title='Ver detalles del ítem' onclick='get_ctb_item_detail(\"view\",\""+response[i].sku+"\",2,"+response[i].id+")'>";
			rows += "<i class='fa fa-eye'></i> ";
			rows += "</a>";
			rows += "<a class='btn' title='Editar información del ítem' onclick='get_ctb_item_detail(\"update\",\""+response[i].sku+"\",2,"+response[i].id+")'>";
			rows += "<i class='fa fa-edit'></i>";
			rows += "</a>";
			rows += "<a class='btn' title='Eliminar ítem' onclick='get_ctb_item_delete("+response[i].id+",\""+response[i].sku+"\",2,"+response[i].id+")'>";
			rows += "<i class='fa fa-trash'></i>";
			rows += "</a>";
			rows += "<a class='btn' title='Publicar ítem' onclick='get_ctb_item_post(\""+response[i].sku+"\",2)'>";
			rows += "<i class='fa fa-bullhorn'></i>";
			rows += "</a>";
			rows += "</td>";
			rows += "</tr>";			
		}
		$('#cbt_items_list_queen').append(rows);
		init_DataTables_local("cbt_items_list_queen");
	});
}
function get_ctb_item_delete(id,mpid,application) {
	$.post('../services/cbt_manager.php',{
		action : 'delete_item_detail',
		id : id,
		mpid : mpid,
		application_id: application
	}).fail(function(){

	}).done(function(e){
		var result = JSON.parse(e);
		if(result.status == 1){
			alert('Eliminado con éxito!');
			location.reload();
		}else {
			alert('Ha ocurrido un error al eliminar la publicación!');
		}
	});
}
function get_ctb_item_post(mpid,application) {
	$.post('../services/cbt_manager.php',{
		action : 'publish_item_detail',
		mpid : mpid,
		application_id: application
	}).fail(function(){

	}).done(function(e){
		var result = JSON.parse(e);
		if(result.status == 1){
			alert('Publicado con éxito!');
			location.reload();
		}else {
			alert('Ha ocurrido un error al publicar la información!');
		}
	});
}
function get_ctb_item_detail(type,sku,application,id) {	
	$('.cbt_modal').text('');
	$('.cbt_modal').attr('src',' ');
	$(".item_detail_modal").modal("show");		
	$.post('../services/cbt_manager.php',{
		sku : sku,
		application: application,
		action: 'get_item_detail'
	}).fail(function(){
	}).done(function(e){
		var response = JSON.parse(e);
		$('#loading_gif').hide();
		$('#detail_modal').show();
		var image_item = response['image_url'];
		image_item = image_item.split("~^~");
		if(type == 'view'){
			$('#item_detail_modal_title').text(response['product_title_english']);
			$('#item_detail_modal_image').attr('src',image_item);
			$('#item_detail_modal_SKU').text(response['SKU']);
			$('#item_detail_modal_sale_price').text(response['sale_price']);
			$('#item_detail_modal_package_weight').text(response['package_weight']);
			$('#item_detail_modal_quantity').text(response['quantity']);
			$('#item_detail_modal_specification_english').text(response['specification_english']);
			$('#item_detail_modal_status').text(response['status']);
		}
		if (type == 'update') {
			$('#update_btn').remove();
			$('#item_detail_modal_title').text(response['product_title_english']).attr('contenteditable',true);
			$('#item_detail_modal_image').attr('src',image_item);
			$('#item_detail_modal_SKU').text(response['SKU']).attr('contenteditable',true);
			$('#item_detail_modal_sale_price').text(response['sale_price']).attr('contenteditable',true);
			$('#item_detail_modal_package_weight').text(response['package_weight']).attr('contenteditable',true);
			$('#item_detail_modal_quantity').text(response['quantity']).attr('contenteditable',true);
			$('#item_detail_modal_specification_english').text(response['specification_english']).attr('contenteditable',true);
			$('.close_modal').after('<button type="button" class="btn btn-primary" id="update_btn" onclick="update_cbt_item_detail(\''+id+'\',\''+response['mpid']+'\', \''+application+'\')">Save changes</button>');				
		}
	});
}

function update_cbt_item_detail(id,item_mpid, application){
	console.log('updating item detail...');
	$.post('../services/cbt_manager.php',{
		application : application,
		id : id,
		action : 'update_item_detail',
		mpid : item_mpid,
		SKU : $('#item_detail_modal_SKU').text(),
		product_title_english : $('#item_detail_modal_title').text(),
		specification_english : $('#item_detail_modal_specification_english').text(),
		sale_price : $('#item_detail_modal_sale_price').text(),
		quantity : $('#item_detail_modal_quantity').text(),
		package_weight : $('#item_detail_modal_package_weight').text()
	}).done(function(e){
		if(e == 1){
			alert('Actualizado con éxito!');
			location.reload();
		}else {
			alert('Ha ocurrido un error al actualizar la información!');
		}
	});
}


$('#mpid_search').click(function() {
	get_ctb_item_detail("update",$('#mpid_search_text').val(),3,null);
});
$(document).ready(function(){
	$('.panel_queen_bee').hide();
	get_ctb_items_mauxi();
	/*if(sessionStorage.getItem('shop') == 1 || sessionStorage.getItem('shop') == 2){
		$('.panel_queen_bee').hide();
		get_ctb_items_mauxi();
	}
	if(sessionStorage.getItem('shop') == 3 || sessionStorage.getItem('shop') == 4){
		$('.panel_mauxi').hide();
		get_ctb_items_queen_bee();
	}
	if(sessionStorage.getItem('shop') == 5){
		get_ctb_items_mauxi();
		get_ctb_items_queen_bee();
	}
	*/
	$('[data-toggle="tooltip"]').tooltip();
});
