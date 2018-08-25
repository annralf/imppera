//--------------------- Index page manager
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
function get_amz_item_detail(asin){
	$('.modal_detail').text(' ');
	$('.modal_detail').attr('src',' ');
	$.get('https://core.enkargo.com.co/core_enkargo/services/getAmzDet.php', {
		asin : $(asin).attr('data-asin')
	}).done(function(e){
		var response = e;
		var image_item = response['image_url'];
		image_item = image_item.split("~^~");
		$('#item_detail_modal_title').text(response['product_title_english']);
		$('#item_detail_modal_image').attr('src',image_item[0]);
		$('#item_detail_modal_brand').text(response['brand']);
		$('#item_detail_modal_condition').text(response['condition']);
		$('#item_detail_modal_currency').text(response['currency']);
		if(response['is_prime'] == 1){
			prime = 'Si';
		}else {
			prime = 'No';			
		}
		$('#item_detail_modal_prime').text(prime);
		$('#item_detail_modal_quantity').text(response['quantity']);
		$('#item_detail_modal_sale_price').text('$'+response['sale_price']);
		$('#item_detail_modal_specification').text(response['specification_english']);
		console.log(response);
	});
}
function get_amz_items_plus(cant){
	console.log('Añadiendo items');
	var cant = cant + 50000;
	$.post('https://core.enkargo.com.co/core_enkargo/services/getAmz.php', {
		limit: 50000,
		offset: cant
	}).done(function(e){
		var response = e;
		var rows = "";
		for(resp in response){
			var asin = response[resp]['asin'];
			rows += "<tr>";
            rows += "<td><!--div class='checkbox'>\
                            <label class=''>\
                              <div class='icheckbox_flat-green' style='position: relative;'><input type='checkbox' class='flat' style='position: absolute; opacity: 0;'><ins class='iCheck-helper' style='position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;'></ins></div>\
                            </label>\
                          </div-->\
                          <input type='checkbox' class='form-control amz_check' value='"+response[resp]['asin']+"'/>\
                          </td>";
            rows += "<td>"+response[resp]['asin']+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp]['title']+"</td>";
            rows += "<td>$"+response[resp]['price']+"</td>";
            rows += "<td>"+response[resp]['weight']+"</td>";
            if(response[resp]['status'] == 1){
            	rows += "<td>Activo</td>";
            }else{
            	rows += "<td>Inactivo</td>";
            }
            rows += "<td>";
            rows += '<a class="btn" title="View items details" data-toggle="modal" data-asin = '+response[resp]['asin']+' data-target=".item_detail_modal"\
            	     onclick="get_amz_item_detail(this)">';
            rows += "<i class='fa fa-eye'></i> ";
            rows += "</a>";
            rows += "</td>";
            rows += "</tr>";
		}
		$('#amz_items_list').append(rows);
		//init_DataTables_local("amz_items_list");
            
	});
}
function get_amz_items(){
	$.post('http://localhost/CBT_core/core/routing/awsItem.php', {
		action: 'getItems',
		limit: 1,
		offset: 0
	}).done(function(e){
		var response = e;
		var rows = "";
		for(var resp in response){
			var asin = response[resp].sku;
			rows += "<tr>";
            rows += "<td><input type='checkbox' class='form-control amz_check' value='"+response[resp].asin+"'</td>";
            rows += "<td>"+response[resp].sku+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].product_type+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].product_title_english+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].brand+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].sale_price+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].quantity+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].package_weight+"</td>";
             if(response[resp].is_prime == 1){
            	rows += "<td>Si</td>";
            }else{
            	rows += "<td>No</td>";
            }
            if(response[resp].active == 1){
            	rows += "<td>Activo</td>";
            }else{
            	rows += "<td>Inactivo</td>";
            }
            rows += "<td>";
            rows += '<a class="btn" title="View items details" data-toggle="modal" data-asin = '+response[resp].asin+' data-target=".item_detail_modal"\
            	     onclick="get_amz_item_detail(this)">';
            rows += "<i class='fa fa-eye'></i> ";
            rows += "</a>";
            rows += "</td>";
            rows += "</tr>";
		}
		$('#amz_items_list').append(rows);
		init_DataTables_local("amz_items_list");
	});
}

//
function go_to_cbt_core(site_name){
	$(location).attr('href','cbt_manager.html');
}

function price_tool(){
	var site_name = $('#price_tool_site_name').val();
	var zero_fifty = $('#zero_fifty').text()/100;
	var fifty_one_hundred = $('#fifty_one_hundred').text()/100;
	var one_hundred_fifty = $('#one_hundred_fifty').text()/100;
	var hundred_fifty_more = $('#hundred_fifty_more').text()/100;
	var date = new Date();
	$.post('http://cbt.camcinfo.com/CBT_core/models/post_db.php',
		{
			table: 'price_tool',
			application_id : site_name,
			zero_fifty : zero_fifty,
			fifty_one_hundred : fifty_one_hundred,
			one_hundred_fifty : one_hundred_fifty,
			hundred_fifty_more : hundred_fifty_more,
			last_update : date.getFullYear()+"/"+date.getMonth()+"/"+date.getDay()
		}).done(function(e){
			/*new PNotify({
                title: 'Sticky Danger',
                text: 'Saved!',
                type: 'success',
                hide: false,
                styling: 'bootstrap3'
                });
                */
                alert("Success");
		});
}

function get_ctb_items_mauxi() {
	console.log('getting items mauxi');
	$.post('https://core.enkargo.com.co/core_enkargo/services/getCbt.php',{ application : 2, limit : 5}).fail(function(){

	}).done(function(e){
		var rows = "";
		var rows_1 = "";
		for(resp in e){
			var item = JSON.parse(e[resp]);
				rows += "<tr>";
	            rows += "<!--td style='width: 100px;'> <input type='checkbox' class='form-control amz_check' value='"+item['mpid']+"'/></td-->";
	            rows += "<td style='width: 100px; word-wrap: break-word;'>"+item['mpid']+"</td>";
	            rows += "<td style='width: 100px; word-wrap: break-word;'>"+item['name']+"</td>";
	            rows += "<td style='width: 450px; word-wrap: break-word;'>"+item['title']+"</td>";
	            rows += "<td>";
	            rows += "<a class='btn' title='View items details' data-toggle='modal' data-target='.item_detail_modal'\
	            	     onclick='get_ctb_item_detail(\"view\","+item['mpid']+",2)'>";
	            rows += "<i class='fa fa-eye'></i> ";
	            rows += "</a>";
				rows += "<a class='btn' title='Edit items details' data-toggle='modal' data-target='.item_detail_modal'\
	            	     onclick='get_ctb_item_detail(\"update\","+item['mpid']+",2)'>";
	            rows += "<i class='fa fa-edit'></i>";
	            rows += "</a>";
	            rows += "<a class='btn' title='Detail items'>";
	            rows += "<i class='fa fa-trash'></i>";
	            rows += "</a>";
	            rows += "<a class='btn' title='Post item'>";
	            rows += "<i class='fa fa-bullhorn'></i>";
	            rows += "</a>";
	            rows += "</td>";
	            rows += "</tr>";			
		}
		$('#cbt_items_list_mauxi').append(rows);
		init_DataTables_local("cbt_items_list_mauxi");
	});
}


function get_ctb_item_detail(type,mlid,application) {	
	$('.cbt_modal').text(' ');
	$('.cbt_modal').attr('src',' ');
	$.post('https://core.enkargo.com.co/core_enkargo/services/getCbtDet.php',{
		mlid : mlid,
		application: application
	}).fail(function(){

	}).done(function(e){
		var response = JSON.parse(e);
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
			$('#item_detail_modal_title').text(response['product_title_english']).attr('contenteditable',true);
			$('#item_detail_modal_image').attr('src',image_item);
			$('#item_detail_modal_SKU').text(response['SKU']).attr('contenteditable',true);
			$('#item_detail_modal_sale_price').text(response['sale_price']).attr('contenteditable',true);
			$('#item_detail_modal_package_weight').text(response['package_weight']).attr('contenteditable',true);
			$('#item_detail_modal_quantity').text(response['quantity']).attr('contenteditable',true);
			$('#item_detail_modal_specification_english').text(response['specification_english']).attr('contenteditable',true);
			$('#item_detail_modal_status').text(response['status']);
			$('.close_modal').after('<button type="button" class="btn btn-primary"\
			 onclick="update_cbt_item_detail('+response['mpid']+','+$('#item_detail_modal_title').text()+',\''+$('#item_detail_modal_SKU').text()+'\',\''+$('#item_detail_modal_sale_price').text()+'\',\''+$('#item_detail_modal_package_weight').text()+'\',\''+$('#item_detail_modal_quantity').text()+'\',\''+$('#item_detail_modal_specification_english').text()+'\',2)">Save changes</button>');
		}
	});
}

function update_cbt_item_detail(item_mpid, item_title, item_SKU, item_sale_price, item_package_weight, item_quantity, item_specification_english,application){
	console.log('updating item detail...');
	$.post('https://core.enkargo.com.co/core_enkargo/services/updateCbt.php',{
		application : application,
		mlid : item_mpid,
		SKU : item_SKU,
		product_title_english : item_title,
		specification_english : item_specification_english,
		sale_price : item_sale_price,
		quantity : item_quantity,
		package_weight : item_package_weight
	}).done(function(e){
		var result = JSON.parse(e);
		if(result['error'] != " "){
			alert('Success!');
			location.reload();
		}else {
			alert('something wrong!');
		}
		console.log(result);
	});
}


$(document).ready(function(){
	get_amz_items();
	$('[data-toggle="tooltip"]').tooltip();
});
