//--------------------- Index page manager
var url_file;
var dataTable;

function session_check(){
	if (response.user_type == 1 || response.user_type == 2) {
    	$(location).attr('href','http://192.168.0.52/enkargo/views/meli_manager.html');				
	}
	if (response.user_type == 3 || response.user_type == 4) {
		$(location).attr('href','http://192.168.0.52/enkargo/views/cbt_manager.html');				
	}		
	if (response.user_type == 5) {
		$(location).attr('href','http://192.168.0.52/enkargo/views/aws_manager.html');	
	}
	if (response.user_type == 6) {
		$(location).attr('href','http://192.168.0.52/enkargo/views/publish_manager.html');				
	}
	if (response.user_type == null) {
		$(location).attr('href','index.html');
	}

}

function read_sku(){
	var sku = $('#skuEncrypt').val();
	$.post('../services/index_manager.php', {sku: sku, action:'read_sku_single'}).done(function(e){
		var value = JSON.parse(e);
		$('#skuEncrypt').val(value.sku);
		//$("#sku_result").text(value.sku).show();
	});	
}
function upload_sku(element){
	$('.amazon_table').hide();
	$('.detail_file').show();
	var file = $(element)[0].files[0];
	var data = new FormData();
	data.append('action','upload_file');
    data.append('file',file);
    $.ajax({
        url : '../services/index_manager.php',
        data: data,
        async: true,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST'
    },function(){
		$('.loading_gif').show();
		$('.amazon_table').hide();
    }).done(function (data) {
    	$('.loading_gif').hide();
		$('.amazon_table').hide();
		$('.detail_charge').hide();
		$('.detail_sku').show();
    	var result = JSON.parse(data);
        if (result.status != 0) {   
        	window.url_file = result.url;
        	$.post('../services/index_manager.php',
        		{
        			'action': 'read_sku',
        			'url': result.url
        		},function(){
	        		$('.loading_gif').show();
	        		$('.amazon_table').hide();
        		}
        		).done(function(e){
	        		$('.loading_gif').hide();
        			var response = JSON.parse(e);
        			if(response.status == 1){
	        			var rows = "";
	        			var items = JSON.parse(response.items);
	        			var j = 1;
						for(var i in items){
							rows += "<tr>";
					        rows += "<td style='word-wrap: break-word;'>"+j+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i]+"</td>";
					        rows += "</tr>";	
					        j++;
						}
						$("#upload_items").append(rows);
						init_DataTables_local("upload_items");
        			}

        		});         
        }if(response.status == 0){
            alert(response.message);
        }
});
}
function upload_file(element){
	$('.amazon_table').hide();
	$('.detail_file').show();
	var file = $(element)[0].files[0];
	var data = new FormData();
	data.append('action','upload_file');
    data.append('file',file);
    $.ajax({
        url : '../services/index_manager.php',
        data: data,
        async: true,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST'
    },function(){
		$('.loading_gif').show();
		$('.amazon_table').hide();
    }).done(function (data) {
    	$('.loading_gif').hide();
		$('.amazon_table').hide();
		$('.detail_file').show();
    	var result = JSON.parse(data);
        if (result.status != 0) {   
        	window.url_file = result.url;
        	$.post('../services/index_manager.php',
        		{
        			'action': 'read_items',
        			'url': result.url
        		},function(){
	        		$('.loading_gif').show();
	        		$('.amazon_table').hide();
        		}
        		).done(function(e){
	        		$('.loading_gif').hide();
        			var response = JSON.parse(e);
        			if(response.status == 1){
	        			var rows = "";
	        			var items = JSON.parse(response.items);
						for(var i in items){
							console.log(items[i]);
							rows += "<tr>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][0]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][1]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][2]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][3]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][4]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][5]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][6]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][7]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][8]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][9]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][10]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][11]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][12]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][13]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][14]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][15]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][16]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][17]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][18]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][19]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][20]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][21]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][22]+"</td>";
					        rows += "<td style='word-wrap: break-word;'>"+items[i][23]+"</td>";
					        rows += "</tr>";	
						}
						$("#upload_items").append(rows);
        			}
        		});         
        }else{
            alert(response.message);
        }
});
}

function load_all(){	
	if($('#shopT').val() == 1){
		$.post('../services/index_manager.php', {
			action: 'load_items',
			url : window.url_file,
			application : $('#shop').val(),
			shopType    : $('#shopT').val(),
			type        : $('#show_type').val()
		}).done(function(e){
			var response = JSON.parse(e);
			if(response.status == 1){
				alert('Cargado con éxito');
				location.reload();
			}else{
				//alert('Ha ocurrido un problema al cargar los registros!');
				//location.reload();
			}
		});
	}
	if($('#shopT').val() == 2){
		$.post('http://localhost/CBT_core/core/routing/cbtAddByFile.php', {
			url : window.url_file,
			application : $('#shop').val(),
			shopType    : $('#shopT').val(),
			type        : $('#show_type').val()
		}).done(function(e){
			var response = JSON.parse(e);
			if(response.status == 1){
				alert('Cargado con éxito');
				location.reload();
			}else{
				alert('Ha ocurrido un problema al cargar los registros!');
				location.reload();
			}
		});
	}
	
}

function cancel_all(){
	$.post('../services/index_manager.php', {
		action: 'delete_items',
		url : window.url_file
	}).done(function(e){
		var response = JSON.parse(e);
		if(response.status == 1){
			alert('Eliminado con éxito');
			location.reload();
		}else{
			alert('Ha ocurrido un problema al eliminar los registros!');
			location.reload();
		}
	});
}

function load_items(option){
	var amz_check = [];
	$(".amz_check:checked").each(function() {
		amz_check.push($(this).val());		
	});
	$.post('../services/loadItemsCbt.php', {
		application: option,
		items :amz_check 
	}).done(function(e){
		alert(e['msg']);
	});
}
function get_amz_item_detail(asin){
	$('.modal_detail').text(' ');
	$('.modal_detail').attr('src',' ');
	$.get('../services/getAmzDet.php', {
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
	});
}
function get_amz_items_plus(cant){
	console.log('Añadiendo items');
	var table = $('#amz_items_list').DataTable();
	table.destroy();
	var cant = cant + 5;
	$.post('../routing/awsItem.php', {
		action: 'getItems',
		limit: 5,
		offset: cant
	}).done(function(e){
		var response = e;
		var rows = "";
		for(var resp in response){
			var asin = response[resp].sku;
			rows += "<tr>";
            rows += "<td><input type='checkbox' class='form-control amz_check' value='"+response[resp].asin+"'</td>";
            rows += "<td>"+response[resp].sku+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].brand+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].product_type+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].product_title_english+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].sale_price+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].quantity+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].package_weight+"</td>";
             if(response[resp].is_prime == 1){
            	rows += "<td>Si</td>";
            }else{
            	rows += "<td>No</td>";
            }
            if(response[resp].active == 't'){
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
		$('#amz_items_list').append(rows);
		}
		init_DataTables_local("amz_items_list");
	});

}
function get_amz_items(){
	$.post('../routing/awsItem.php', {
		action: 'getItems',
		limit: 5,
		offset: 0
	}).done(function(e){
		var response = e;
		var rows = "";
		for(var resp in response){
			var asin = response[resp].sku;
			rows += "<tr>";
            rows += "<td><input type='checkbox' class='form-control amz_check' value='"+response[resp].asin+"'</td>";
            rows += "<td>"+response[resp].sku+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].brand+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].product_type+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].product_title_english+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].sale_price+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].quantity+"</td>";
            rows += "<td style='width: 150px; word-wrap: break-word;'>"+response[resp].package_weight+"</td>";
             if(response[resp].is_prime == 1){
            	rows += "<td>Si</td>";
            }else{
            	rows += "<td>No</td>";
            }
            if(response[resp].active == 't'){
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
	$.post('../models/post_db.php',
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
	$.post('../services/getCbt.php',{ application : 2, limit : 5}).fail(function(){

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
	$.post('../services/getCbtDet.php',{
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
	$.post('../services/updateCbt.php',{
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
