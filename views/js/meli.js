//+++++++++++++++++++++++++++++++++++ START RAFAEL FUNCTIONS
//++++++++++++++++++++++++++++++++++++++++++++
var url_base='https://core.enkargo.com.co/';

function combo_p() {
	$.post(url_base+'services/meli_manager.php',
		{ 	action  : 'combo_p', 
			shop_id : 2
		}).fail(function(){

	}).done(function(e){
		var rows = "";
		var response = JSON.parse(e); 		
		rows += "<select class=\"form-control\" name=\"category_p_mx\" id=\"category_p_mx\" onchange=\"combo_h(this.value)\">"; 
		rows += "<option>-- Seleccione --</option>";     
        for(var i in response){
        	rows += "<option value=\""+response[i].id+"\">"+response[i].definition+"</option>";
        }
        rows += "</option>";
        $('#cmbo_p').append(rows);
	});
}

function combo_h1(valor,shop_id) {
	var pre="";
	if (shop_id==1){
		pre="_qb";
	}else{
		pre="_mx";
	}
	
  	$('#cmbo_1'+pre).empty();
  	$('#cmbo_2'+pre).empty();
  	$('#cmbo_3'+pre).empty();
  	$('#cmbo_4'+pre).empty();
  	$('#cmbo_5'+pre).empty();
  	$('#cmbo_6'+pre).empty();
  	$('#cmbo_7'+pre).empty();
  	$('#cmbo_8'+pre).empty();
  	$('#cmbo_9'+pre).empty();
	$('#cmbo_talla'+pre).empty();
	$('#cmbo_color'+pre).empty();
	$.post(url_base+'services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category_mx\" name=\"category_mx\" value=\""+valor+"\">";
        	$('#cmbo_1').append(rows);
			if(response.attribute=="variations"){
				$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
			}
		}else{
			rows += "<select class=\"form-control\" name=\"combo_1\" id=\"combo_1\" onchange=\"combo_h2(this.value,shop_id)\">";
			rows += "<option>-- Seleccione --</option>";     
	        for(var i in response){
	        	rows += "<option value=\""+response[i].id+"\">"+response[i].name+"</option>";
	        }
	        rows += "</option>";
        	$('#cmbo_1'+pre).append(rows);
        }
	});
}
function combo_h2(valor,shop_id) {
	var pre="";
	if (shop_id==1){
		pre="_qb";
	}else{
		pre="_mx";
	}
	

  	$('#cmbo_2'+pre).empty();
  	$('#cmbo_3'+pre).empty();
  	$('#cmbo_4'+pre).empty();
  	$('#cmbo_5'+pre).empty();
  	$('#cmbo_6'+pre).empty();
  	$('#cmbo_7'+pre).empty();
  	$('#cmbo_8'+pre).empty();
  	$('#cmbo_9'+pre).empty();
	$('#cmbo_talla'+pre).empty();
	$('#cmbo_color'+pre).empty();
	$.post(url_base+'services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category_mx\" name=\"category_mx\" value=\""+valor+"\">";
        	$('#cmbo_2').append(rows);
			if(response.attribute=="variations"){
				
				$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
			}
		}else{
			rows += "<select class=\"form-control\" name=\"combo_2\" id=\"combo_2\" onchange=\"combo_h3(this.value,shop_id)\">";
			rows += "<option>-- Seleccione --</option>";     
	        for(var i in response){
	        	rows += "<option value=\""+response[i].id+"\">"+response[i].name+"</option>";
	        }
	        rows += "</option>";
        	$('#cmbo_2'+pre).append(rows);
        }
	});
}
function combo_h3(valor,shop_id) {
	var pre="";
	if (shop_id==1){
		pre="_qb";
	}else{
		pre="_mx";
	}
	
  	$('#cmbo_3'+pre).empty();
  	$('#cmbo_4'+pre).empty();
  	$('#cmbo_5'+pre).empty();
  	$('#cmbo_6'+pre).empty();
  	$('#cmbo_7'+pre).empty();
  	$('#cmbo_8'+pre).empty();
  	$('#cmbo_9'+pre).empty();
	$('#cmbo_talla'+pre).empty();
	$('#cmbo_color'+pre).empty();
	$.post(url_base+'services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category_mx\" name=\"category_mx\" value=\""+valor+"\">";
        	$('#cmbo_3').append(rows);
			if(response.attribute=="variations"){
				
				$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
			}
		}else{
			rows += "<select class=\"form-control\" name=\"combo_3\" id=\"combo_3\" onchange=\"combo_h4(this.value,shop_id)\">";
			rows += "<option>-- Seleccione --</option>";     
	        for(var i in response){
	        	rows += "<option value=\""+response[i].id+"\">"+response[i].name+"</option>";
	        }
	        rows += "</option>";
        	$('#cmbo_3'+pre).append(rows);
        }
	});
}
function combo_h4(valor,shop_id) {
	var pre="";
	if (shop_id==1){
		pre="_qb";
	}else{
		pre="_mx";
	}
	
  	$('#cmbo_4'+pre).empty();
  	$('#cmbo_5'+pre).empty();
  	$('#cmbo_6'+pre).empty();
  	$('#cmbo_7'+pre).empty();
  	$('#cmbo_8'+pre).empty();
  	$('#cmbo_9'+pre).empty();
	$('#cmbo_talla'+pre).empty();
	$('#cmbo_color'+pre).empty();
	$.post(url_base+'services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category_mx\" name=\"category_mx\" value=\""+valor+"\">";
        	$('#cmbo_4').append(rows);
			if(response.attribute=="variations"){
				
				$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
			}
		}else{
			rows += "<select class=\"form-control\" name=\"combo_4\" id=\"combo_4\" onchange=\"combo_h5(this.value,shop_id)\">";
			rows += "<option>-- Seleccione --</option>";     
	        for(var i in response){
	        	rows += "<option value=\""+response[i].id+"\">"+response[i].name+"</option>";
	        }
	        rows += "</option>";
        	$('#cmbo_4'+pre).append(rows);
        }
	});
}
function combo_h5(valor,shop_id) {
	var pre="";
	if (shop_id==1){
		pre="_qb";
	}else{
		pre="_mx";
	}
	
  	$('#cmbo_5'+pre).empty();
  	$('#cmbo_6'+pre).empty();
  	$('#cmbo_7'+pre).empty();
  	$('#cmbo_8'+pre).empty();
  	$('#cmbo_9'+pre).empty();
	$('#cmbo_talla'+pre).empty();
	$('#cmbo_color'+pre).empty();
	$.post(url_base+'services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category_mx\" name=\"category_mx\" value=\""+valor+"\">";
        	$('#cmbo_5').append(rows);
			if(response.attribute=="variations"){
				
				$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
			}
		}else{
			rows += "<select class=\"form-control\" name=\"combo_5\" id=\"combo_5\" onchange=\"combo_h6(this.value,shop_id)\">";
			rows += "<option>-- Seleccione --</option>";     
	        for(var i in response){
	        	rows += "<option value=\""+response[i].id+"\">"+response[i].name+"</option>";
	        }
	        rows += "</option>";
        	$('#cmbo_5'+pre).append(rows);
        }
	});
}
function combo_h6(valor,shop_id) {
	var pre="";
	if (shop_id==1){
		pre="_qb";
	}else{
		pre="_mx";
	}
	
  	$('#cmbo_6'+pre).empty();
  	$('#cmbo_7'+pre).empty();
  	$('#cmbo_8'+pre).empty();
  	$('#cmbo_9'+pre).empty();
	$('#cmbo_talla'+pre).empty();
	$('#cmbo_color'+pre).empty();
	$.post(url_base+'services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category_mx\" name=\"category_mx\" value=\""+valor+"\">";
        	$('#cmbo_6').append(rows);
			$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
		}else{
			rows += "<select class=\"form-control\" name=\"combo_6\" id=\"combo_6\" onchange=\"combo_h7(this.value,shop_id)\">";
			rows += "<option>-- Seleccione --</option>";     
	        for(var i in response){
	        	rows += "<option value=\""+response[i].id+"\">"+response[i].name+"</option>";
	        }
	        rows += "</option>";
        	$('#cmbo_6'+pre).append(rows);
        }
	});
}
function combo_h7(valor,shop_id) {
	var pre="";
	if (shop_id==1){
		pre="_qb";
	}else{
		pre="_mx";
	}
	
  	$('#cmbo_7'+pre).empty();
  	$('#cmbo_8'+pre).empty();
  	$('#cmbo_9'+pre).empty();
	$('#cmbo_talla'+pre).empty();
	$('#cmbo_color'+pre).empty();
	$.post(url_base+'services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category_mx\" name=\"category_mx\" value=\""+valor+"\">";
        	$('#cmbo_7').append(rows);
			if(response.attribute=="variations"){
				
				$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
			}
		}else{
			rows += "<select class=\"form-control\" name=\"combo_7\" id=\"combo_7\" onchange=\"combo_h8(this.value,shop_id)\">";
			rows += "<option>-- Seleccione --</option>";     
	        for(var i in response){
	        	rows += "<option value=\""+response[i].id+"\">"+response[i].name+"</option>";
	        }
	        rows += "</option>";
        	$('#cmbo_7'+pre).append(rows);
        }
	});
}
function combo_h8(valor,shop_id) {
	var pre="";
	if (shop_id==1){
		pre="_qb";
	}else{
		pre="_mx";
	}
	
  	$('#cmbo_8'+pre).empty();
  	$('#cmbo_9'+pre).empty();
	$('#cmbo_talla'+pre).empty();
	$('#cmbo_color'+pre).empty();
	$.post(url_base+'services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category_mx\" name=\"category_mx\" value=\""+valor+"\">";
        	$('#cmbo_8').append(rows);
				if(response.attribute=="variations"){
				
				$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
			}
		}else{
			rows += "<select class=\"form-control\" name=\"combo_8\" id=\"combo8\" onchange=\"combo_h9(this.value,shop_id)\">";
			rows += "<option>-- Seleccione --</option>";     
	        for(var i in response){
	        	rows += "<option value=\""+response[i].id+"\">"+response[i].name+"</option>";
	        }
	        rows += "</option>";
        	$('#cmbo_8'+pre).append(rows);
        }
	});
}
function combo_h9(valor,shop_id) {
	var pre="";
	if (shop_id==1){
		pre="_qb";
	}else{
		pre="_mx";
	}
	
  	$('#cmbo_9'+pre).empty();
	$('#cmbo_talla'+pre).empty();
	$('#cmbo_color'+pre).empty();
	$.post(url_base+'services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category_mx\" name=\"category_mx\" value=\""+valor+"\">";
        	$('#cmbo_9').append(rows);
				if(response.attribute=="variations"){
				
				$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
			}
		}else{
			rows += "<select class=\"form-control\" name=\"combo_9\" id=\"combo_9\">";
			rows += "<option>-- Seleccione --</option>";     
	        for(var i in response){
	        	rows += "<option value=\""+response[i].id+"\">"+response[i].name+"</option>";
	        }
	        rows += "</option>";
        	$('#cmbo_9'+pre).append(rows);
        }
	});
}
function combo_color(valor,shop_id) {
	var pre="";
	if (shop_id==1){
		pre="_qb";
	}else{
		pre="_mx";
	}
	
	$.post(url_base+'services/meli_manager.php',
		{ 	action  : 'combo_color', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var response = JSON.parse(e); 
		if (response.response==0){

		}else{
			rows += "<label >Color</label>";
			rows += "<select class=\"form-control\" name=\"combo_color\" id=\"combo_color\">";
			rows += "<option>-- Seleccione --</option>";     
	        for(var i in response){
	        	rows += "<option value=\""+response[i].name+"\">"+response[i].name+"</option>";
	        }
	        rows += "</option>";
	       	$('#cmbo_color'+pre).append(rows);
	    }
	});
}
function combo_talla(valor,shop_id) {
	var pre="";
	if (shop_id==1){
		pre="_qb";
	}else{
		pre="_mx";
	}
	
	$.post(url_base+'services/meli_manager.php',
		{ 	action  : 'combo_talla', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var response = JSON.parse(e); 
		if (response.response==0){

		}else{
			rows += "<label >Talla</label>";
			rows += "<select class=\"form-control\" name=\"combo_talla\" id=\"combo_talla\">";
			rows += "<option>-- Seleccione --</option>";     
	        for(var i in response){
	        	rows += "<option value=\""+response[i].name+"\">"+response[i].name+"</option>";
	        }
	        rows += "</option>";
	       	$('#cmbo_talla'+pre).append(rows);
	    }
	});
}

//ACTION SEARCH MAUXI
function search_sku_mx(){
	valor="";
	//alert($('#sku1').val());
	$('#loading').show();
	$.post(url_base+'services/meli_manager.php',
		{
			action 	: 'search_sku_mx',
			sku1_mx	: $('#sku1_mx').val(),
			shop_id : 2,
		}).done(function(e){
			var response = JSON.parse(e);
			if (response.response == 0) {
				$('#loading').hide();
				alertify.alert("Error al cargar la información", function () {});
				
			}else{
				alertify.alert("<b>MPID encontrado </b><a href=\""+response[0].permalink+"\" target=\"_blank\">"+response[0].mpid+"</a>", function () {});
				$('#loading').hide();
			}
		});
}
function update_mpid_mx(){
	//alert($('#mpid_mx').val());
	$('#loading').show();
	$.post(url_base+'process/meli_update_by_items.php',
		{
			action 	: 'update_mpid_mx',
			mpid_mx	: $('#mpid_mx').val(),
			shop_id : 2,
		}).done(function(e){
			var response = JSON.parse(e);
			if (response.response == "error_c1") {
				$('#loading').hide();
				alertify.alert("MPID incorrecto", function () {});
				
			}if (response.response == "error_c2") {
				$('#loading').hide();
				alertify.alert("MPID incorrecto", function () {});
				
			}if (response.response == "error_v") {
				$('#loading').hide();
				alertify.alert("Inexistente en BD", function () {});
				
			}if (response.response == "error_closed") {
				$('#loading').hide();
				alertify.alert("Error Cerrando", function () {});
				
			}if (response.response == "error_paused") {
				$('#loading').hide();
				alertify.alert("Error Pausando", function () {});
				
			}if (response.response == "error_active") {
				$('#loading').hide();
				alertify.alert("Error Actualizando", function () {});
				
			}if (response.response == "closed") {
				$('#loading').hide();
				alertify.alert("Publicacion Finalizada", function () {});
				
			}if (response.response == "paused") {
				$('#loading').hide();
				alertify.alert("Publicacion Pausada", function () {});
				
			}if (response.response == "update") {
				$('#loading').hide();
				alertify.alert("Publicacion Actualizada", function () {});
				
			}if (response.response == "review") {
				$('#loading').hide();
				alertify.alert("Error, Para Revisar", function () {});
				
			}if (response.response == "deleted") {
				$('#loading').hide();
				alertify.alert("Publicacion Eliminada", function () {});
				
			}if (response.response == "relist") {
				$('#loading').hide();
				alertify.alert("Publicacion Relistada", function () {});
				
			}if (response.response == "seller") {
				$('#loading').hide();
				alertify.alert("Publicacion no pertenece a Mauxi", function () {});
				
			}
			/*else{
				valor= '<a href="'+response[0].permalink+'">'+response[0].mpid+'</a>';
				$('#loading').hide();
				$('#response_1').append(valor);
				$('#response').show();
			}*/
		});
}
function paused_mpid_mx(){
	$('#loading').show();
	$.post(url_base+'process/meli_update_by_items.php',
		{
			action 	: 'paused_mpid_mx',
			mpid_mx	: $('#mpid_mx').val(),
			shop_id : 2,
			}).done(function(e){
			var response = JSON.parse(e);
			if (response.response == "error_c1") {
				$('#loading').hide();
				alertify.alert("MPID incorrecto", function () {});
				
			}if (response.response == "error_c2") {
				$('#loading').hide();
				alertify.alert("MPID incorrecto", function () {});
				
			}if (response.response == "error_v") {
				$('#loading').hide();
				alertify.alert("Inexistente en BD", function () {});
				
			}if (response.response == "error_closed") {
				$('#loading').hide();
				alertify.alert("Error Cerrando", function () {});
				
			}if (response.response == "error_paused") {
				$('#loading').hide();
				alertify.alert("Error Pausando", function () {});
				
			}if (response.response == "error_active") {
				$('#loading').hide();
				alertify.alert("Error Actualizando", function () {});
				
			}if (response.response == "closed") {
				$('#loading').hide();
				alertify.alert("Publicacion Finalizada", function () {});
				
			}if (response.response == "paused") {
				$('#loading').hide();
				alertify.alert("Publicacion Pausada", function () {});
				
			}if (response.response == "update") {
				$('#loading').hide();
				alertify.alert("Publicacion Actualizada", function () {});
				
			}if (response.response == "review") {
				$('#loading').hide();
				alertify.alert("Error, Para Revisar", function () {});
				
			}if (response.response == "deleted") {
				$('#loading').hide();
				alertify.alert("Publicacion Eliminada", function () {});
				
			}if (response.response == "relist") {
				$('#loading').hide();
				alertify.alert("Publicacion Relistada", function () {});
				
			}if (response.response == "seller") {
				$('#loading').hide();
				alertify.alert("Publicacion no pertenece a Mauxi", function () {});
				
			}
			/*else{
				valor= '<a href="'+response[0].permalink+'">'+response[0].mpid+'</a>';
				$('#loading').hide();
				$('#response_1').append(valor);
				$('#response').show();
			}*/
		});
}
function closed_mpid_mx(){
	$('#loading').show();
	$.post(url_base+'process/meli_update_by_items.php',
		{
			action 	: 'closed_mpid_mx',
			mpid_mx	: $('#mpid_mx').val(),
			shop_id : 2,
			}).done(function(e){
			var response = JSON.parse(e);
			if (response.response == "error_c1") {
				$('#loading').hide();
				alertify.alert("MPID incorrecto", function () {});
				
			}if (response.response == "error_c2") {
				$('#loading').hide();
				alertify.alert("MPID incorrecto", function () {});
				
			}if (response.response == "error_v") {
				$('#loading').hide();
				alertify.alert("Inexistente en BD", function () {});
				
			}if (response.response == "error_closed") {
				$('#loading').hide();
				alertify.alert("Error Cerrando", function () {});
				
			}if (response.response == "error_paused") {
				$('#loading').hide();
				alertify.alert("Error Pausando", function () {});
				
			}if (response.response == "error_active") {
				$('#loading').hide();
				alertify.alert("Error Actualizando", function () {});
				
			}if (response.response == "closed") {
				$('#loading').hide();
				alertify.alert("Publicacion Finalizada", function () {});
				
			}if (response.response == "paused") {
				$('#loading').hide();
				alertify.alert("Publicacion Pausada", function () {});
				
			}if (response.response == "update") {
				$('#loading').hide();
				alertify.alert("Publicacion Actualizada", function () {});
				
			}if (response.response == "review") {
				$('#loading').hide();
				alertify.alert("Error, Para Revisar", function () {});
				
			}if (response.response == "deleted") {
				$('#loading').hide();
				alertify.alert("Publicacion Eliminada", function () {});
				
			}if (response.response == "relist") {
				$('#loading').hide();
				alertify.alert("Publicacion Relistada", function () {});
				
			}if (response.response == "seller") {
				$('#loading').hide();
				alertify.alert("Publicacion no pertenece a Mauxi", function () {});
				
			}
			/*else{
				valor= '<a href="'+response[0].permalink+'">'+response[0].mpid+'</a>';
				$('#loading').hide();
				$('#response_1').append(valor);
				$('#response').show();
			}*/
		});
}
function delete_mpid_mx(){
	$('#loading').show();
	$.post(url_base+'process/meli_update_by_items.php',
		{
			action 	: 'delete_mpid_mx',
			mpid_mx	: $('#mpid_mx').val(),
			shop_id : 2,
			}).done(function(e){
			var response = JSON.parse(e);
			if (response.response == "error_c1") {
				$('#loading').hide();
				alertify.alert("MPID incorrecto", function () {});
				
			}if (response.response == "error_c2") {
				$('#loading').hide();
				alertify.alert("MPID incorrecto", function () {});
				
			}if (response.response == "error_v") {
				$('#loading').hide();
				alertify.alert("Inexistente en BD", function () {});
				
			}if (response.response == "error_closed") {
				$('#loading').hide();
				alertify.alert("Error Cerrando", function () {});
				
			}if (response.response == "error_paused") {
				$('#loading').hide();
				alertify.alert("Error Pausando", function () {});
				
			}if (response.response == "error_active") {
				$('#loading').hide();
				alertify.alert("Error Actualizando", function () {});
				
			}if (response.response == "closed") {
				$('#loading').hide();
				alertify.alert("Publicacion Finalizada", function () {});
				
			}if (response.response == "paused") {
				$('#loading').hide();
				alertify.alert("Publicacion Pausada", function () {});
				
			}if (response.response == "update") {
				$('#loading').hide();
				alertify.alert("Publicacion Actualizada", function () {});
				
			}if (response.response == "review") {
				$('#loading').hide();
				alertify.alert("Error, Para Revisar", function () {});
				
			}if (response.response == "deleted") {
				$('#loading').hide();
				alertify.alert("Publicacion Eliminada", function () {});
				
			}if (response.response == "relist") {
				$('#loading').hide();
				alertify.alert("Publicacion Relistada", function () {});
				
			}if (response.response == "seller") {
				$('#loading').hide();
				alertify.alert("Publicacion no pertenece a Mauxi", function () {});
				
			}
			/*else{
				valor= '<a href="'+response[0].permalink+'">'+response[0].mpid+'</a>';
				$('#loading').hide();
				$('#response_1').append(valor);
				$('#response').show();
			}*/
		});
}
//ACTION SEARCH QUEEN BEE
function search_sku_qb(){
	valor="";
	//alert($('#sku1').val());
	$('#loading').show();
	$.post(url_base+'services/meli_manager.php',
		{
			action 	: 'search_sku_qb',
			sku1_qb : $('#sku1_qb').val(),
			shop_id : 1,
		}).done(function(e){
			var response = JSON.parse(e);
			if (response.response == 0) {
				$('#loading').hide();
				alert("Error al cargar la información");
				
			}else{
				alertify.alert("<b>MPID encontrado </b><a href=\""+response[0].permalink+"\" target=\"_blank\">"+response[0].mpid+"</a>", function () {});
				$('#loading').hide();
			}
		});
}
function update_mpid_qb(){
	//alert($('#mpid_qb').val());
	$('#loading').show();
	$.post(url_base+'process/meli_update_by_items.php',
		{
			action 	: 'update_mpid_qb',
			mpid_qb	: $('#mpid_qb').val(),
			shop_id : 1,
		}).done(function(e){
			var response = JSON.parse(e);
			if (response.response == "error_c1") {
				$('#loading').hide();
				alertify.alert("MPID incorrecto", function () {});
				
			}if (response.response == "error_c2") {
				$('#loading').hide();
				alertify.alert("MPID incorrecto", function () {});
				
			}if (response.response == "error_v") {
				$('#loading').hide();
				alertify.alert("Inexistente en BD", function () {});
				
			}if (response.response == "error_closed") {
				$('#loading').hide();
				alertify.alert("Error Cerrando", function () {});
				
			}if (response.response == "error_paused") {
				$('#loading').hide();
				alertify.alert("Error Pausando", function () {});
				
			}if (response.response == "error_active") {
				$('#loading').hide();
				alertify.alert("Error Actualizando", function () {});
				
			}if (response.response == "closed") {
				$('#loading').hide();
				alertify.alert("Publicacion Finalizada", function () {});
				
			}if (response.response == "paused") {
				$('#loading').hide();
				alertify.alert("Publicacion Pausada", function () {});
				
			}if (response.response == "update") {
				$('#loading').hide();
				alertify.alert("Publicacion Actualizada", function () {});
				
			}if (response.response == "review") {
				$('#loading').hide();
				alertify.alert("Error, Para Revisar", function () {});
				
			}if (response.response == "deleted") {
				$('#loading').hide();
				alertify.alert("Publicacion Eliminada", function () {});
				
			}if (response.response == "relist") {
				$('#loading').hide();
				alertify.alert("Publicacion Relistada", function () {});
				
			}if (response.response == "seller") {
				$('#loading').hide();
				alertify.alert("Publicacion no pertenece a Queen Bee", function () {});
				
			}
			/*else{
				valor= '<a href="'+response[0].permalink+'">'+response[0].mpid+'</a>';
				$('#loading').hide();
				$('#response_1').append(valor);
				$('#response').show();
			}*/
		});
}
function paused_mpid_qb(){
	$('#loading').show();
	$.post(url_base+'process/meli_update_by_items.php',
		{
			action 	: 'paused_mpid_qb',
			mpid_qb	: $('#mpid_qb').val(),
			shop_id : 1,
			}).done(function(e){
			var response = JSON.parse(e);
			if (response.response == "error_c1") {
				$('#loading').hide();
				alertify.alert("MPID incorrecto", function () {});
				
			}if (response.response == "error_c2") {
				$('#loading').hide();
				alertify.alert("MPID incorrecto", function () {});
				
			}if (response.response == "error_v") {
				$('#loading').hide();
				alertify.alert("Inexistente en BD", function () {});
				
			}if (response.response == "error_closed") {
				$('#loading').hide();
				alertify.alert("Error Cerrando", function () {});
				
			}if (response.response == "error_paused") {
				$('#loading').hide();
				alertify.alert("Error Pausando", function () {});
				
			}if (response.response == "error_active") {
				$('#loading').hide();
				alertify.alert("Error Actualizando", function () {});
				
			}if (response.response == "closed") {
				$('#loading').hide();
				alertify.alert("Publicacion Finalizada", function () {});
				
			}if (response.response == "paused") {
				$('#loading').hide();
				alertify.alert("Publicacion Pausada", function () {});
				
			}if (response.response == "update") {
				$('#loading').hide();
				alertify.alert("Publicacion Actualizada", function () {});
				
			}if (response.response == "review") {
				$('#loading').hide();
				alertify.alert("Error, Para Revisar", function () {});
				
			}if (response.response == "deleted") {
				$('#loading').hide();
				alertify.alert("Publicacion Eliminada", function () {});
				
			}if (response.response == "relist") {
				$('#loading').hide();
				alertify.alert("Publicacion Relistada", function () {});
				
			}if (response.response == "seller") {
				$('#loading').hide();
				alertify.alert("Publicacion no pertenece a Queen Bee", function () {});
				
			}
			/*else{
				valor= '<a href="'+response[0].permalink+'">'+response[0].mpid+'</a>';
				$('#loading').hide();
				$('#response_1').append(valor);
				$('#response').show();
			}*/
		});
}
function closed_mpid_qb(){
	$('#loading').show();
	$.post(url_base+'process/meli_update_by_items.php',
		{
			action 	: 'closed_mpid_qb',
			mpid_qb	: $('#mpid_qb').val(),
			shop_id : 1,
			}).done(function(e){
			var response = JSON.parse(e);
			if (response.response == "error_c1") {
				$('#loading').hide();
				alertify.alert("MPID incorrecto", function () {});
				
			}if (response.response == "error_c2") {
				$('#loading').hide();
				alertify.alert("MPID incorrecto", function () {});
				
			}if (response.response == "error_v") {
				$('#loading').hide();
				alertify.alert("Inexistente en BD", function () {});
				
			}if (response.response == "error_closed") {
				$('#loading').hide();
				alertify.alert("Error Cerrando", function () {});
				
			}if (response.response == "error_paused") {
				$('#loading').hide();
				alertify.alert("Error Pausando", function () {});
				
			}if (response.response == "error_active") {
				$('#loading').hide();
				alertify.alert("Error Actualizando", function () {});
				
			}if (response.response == "closed") {
				$('#loading').hide();
				alertify.alert("Publicacion Finalizada", function () {});
				
			}if (response.response == "paused") {
				$('#loading').hide();
				alertify.alert("Publicacion Pausada", function () {});
				
			}if (response.response == "update") {
				$('#loading').hide();
				alertify.alert("Publicacion Actualizada", function () {});
				
			}if (response.response == "review") {
				$('#loading').hide();
				alertify.alert("Error, Para Revisar", function () {});
				
			}if (response.response == "deleted") {
				$('#loading').hide();
				alertify.alert("Publicacion Eliminada", function () {});
				
			}if (response.response == "relist") {
				$('#loading').hide();
				alertify.alert("Publicacion Relistada", function () {});
				
			}if (response.response == "seller") {
				$('#loading').hide();
				alertify.alert("Publicacion no pertenece a Queen Bee", function () {});
				
			}
			/*else{
				valor= '<a href="'+response[0].permalink+'">'+response[0].mpid+'</a>';
				$('#loading').hide();
				$('#response_1').append(valor);
				$('#response').show();
			}*/
		});
}
function delete_mpid_qb(){
	$('#loading').show();
	$.post(url_base+'process/meli_update_by_items.php',
		{
			action 	: 'delete_mpid_qb',
			mpid_qb	: $('#mpid_qb').val(),
			shop_id : 1,
			}).done(function(e){
			var response = JSON.parse(e);
			if (response.response == "error_c1") {
				$('#loading').hide();
				alertify.alert("MPID incorrecto", function () {});
				
			}if (response.response == "error_c2") {
				$('#loading').hide();
				alertify.alert("MPID incorrecto", function () {});
				
			}if (response.response == "error_v") {
				$('#loading').hide();
				alertify.alert("Inexistente en BD", function () {});
				
			}if (response.response == "error_closed") {
				$('#loading').hide();
				alertify.alert("Error Cerrando", function () {});
				
			}if (response.response == "error_paused") {
				$('#loading').hide();
				alertify.alert("Error Pausando", function () {});
				
			}if (response.response == "error_active") {
				$('#loading').hide();
				alertify.alert("Error Actualizando", function () {});
				
			}if (response.response == "closed") {
				$('#loading').hide();
				alertify.alert("Publicacion Finalizada", function () {});
				
			}if (response.response == "paused") {
				$('#loading').hide();
				alertify.alert("Publicacion Pausada", function () {});
				
			}if (response.response == "update") {
				$('#loading').hide();
				alertify.alert("Publicacion Actualizada", function () {});
				
			}if (response.response == "review") {
				$('#loading').hide();
				alertify.alert("Error, Para Revisar", function () {});
				
			}if (response.response == "deleted") {
				$('#loading').hide();
				alertify.alert("Publicacion Eliminada", function () {});
				
			}if (response.response == "relist") {
				$('#loading').hide();
				alertify.alert("Publicacion Relistada", function () {});
				
			}if (response.response == "seller") {
				$('#loading').hide();
				alertify.alert("Publicacion no pertenece a Queen Bee", function () {});
				
			}
			/*else{
				valor= '<a href="'+response[0].permalink+'">'+response[0].mpid+'</a>';
				$('#loading').hide();
				$('#response_1').append(valor);
				$('#response').show();
			}*/
		});
}

//+++++++++++++++++++++++++++++++++++ END RAFAEL FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
/*
+
+
*/
//+++++++++++++++++++++++++++++++++++ START TRACKING FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
//******* START NOT DELIVERED 
function get_meli_not_delivered() {
	console.log('getting items not delivered ...');
	$('.loading_gif').show();
	$.post(url_base+'services/meli_manager.php',{ action : 'get_order_not_delivered', user_id: sessionStorage.getItem('id')}).fail(function(){

	}).done(function(e){
		$('.loading_gif').hide();
		var response = JSON.parse(e);
		if (response == undefined || response.length == 0) {
			alert('Sin productos pendientes por envío!');
		}else {
			var seller_name = "";
			var seller_nit = "";
			var content = "";
			for(var i in response){
				switch (response[i].account) {
					case "1":
					seller_name = "QUEEN BEE";
					seller_nit = "900936668-1";
					break;
					case "2":
					seller_name = "MAUXI";
					seller_nit = "900936668-1";
					break;
				}
				content += '<div class="row" style="border-bottom: 2px solid #73879c; margin-left: 20px;"  id ="order_detail_'+response[i].order+'">';
				content += '<div class="col-md-4 col-sm-4">';
				content += '<img src="'+response[i].image+'" style="width: 80%;margin-left:30px;">';
				content += '</div>';
				content += '<div class="col-md-4 col-sm-4 form-group">';
				content += '<div class="row">';
				content += '<div class="col-md-6" style="padding-left: 0;">';
				content += '<label for="order">#Orden:</label>';
				content += '<input type="text" name="order" id="order" class="form-control" value="'+response[i].order+'" disable>';
				content += '</div>';
				content += '<div class="col-md-6" style="padding-right:0;">';
				content += '<label for="account">#Cuenta:</label>';
				content += '<input type="text" name="account" id="account" class="form-control" value="'+seller_name+'" disable>';
				content += '</div>';
				content += '</div>';
				content += '<div class="row">';
				content += '<label for="title">Título:</label>';
				content += '<input type="text" name="title" id="title" class="form-control" value="'+response[i].title+'" disable>';
				content += '</div>';
				content += '<div class="row">';
				content += '<label for="aws:price">Precio de compra:</label>';
				content += '<input type="text" name="aws:price" id="aws:price" class="form-control" value="'+response[i].price_aws+'" disable>';
				content += '</div>';
				content += '<div class="row">';
				content += '<div class="col-md-6" style="padding-left: 0;">';
				content += '<label for="price">Precio de venta:</label>';
				content += '<input type="text" name="price" id="price" class="form-control" value="'+response[i].price+'" disable>';
				content += '</div>';
				content += '<div class="col-md-6" style="padding-right: 0;">';
				content += '<label for="quantity">Cantidad:</label>';
				content += '<input type="text" name="quantity" id="quantity" class="form-control" value="'+response[i].quantity+'" disable>';
				content += '</div>';
				content += '</div>';
				content += '<div class="row">';
				content += '<label for="note">Nota:</label>';
				content += '<br>';
				for(var j in response[i].notes){
					content += '<b style="font-size:9px; position:relative; left: 333px;">'+response[i].notes[j].date+'</b>';
					content += '<input type="text" name="note" id="note" class="form-control" value="'+response[i].notes[j].note+'" disable>';
				}
				content += '</div>';
				content += '<div class="row">';
				content += '<label for="comment">Comentario:</label>';
				content += '<input type="text" name="comment" id="comment" class="form-control" value="'+response[i].quantity+'" disable>';
				content += '</div>';
				content += '</div>';
				content += '<div class="col-md-3 col-sm-3" style="margin-top: 24px;">';
				switch (response[i].shipping_mode) {
					case 'me2':
					if (response[i].order_status != 'delivered'){
						content += '<button type="button" class="form-control .mercadoenvio_'+response[i].order+'" style="background-color: #51cc60; color: white;" onclick="print_meli_label(\''+response[i].token+'\',\''+response[i].shipping_id+'\',\''+response[i].order+'\')">Mercado Envíos</button>';
						$('.send_later').show();
					}else{
						content += '<button type="button" class="form-control" style="background-color: #51cc60; color: white;" onclick="">Enviado</button>';
					}
						break;
						case 'custom':
						content += '<button type="button" class="form-control" style="background-color: #96e6a0; color: white;" onclick="print_local_label(\''+response[i].order+'\')">Convenir</button>';
						$('.send_later').show();
						break;
						default:
						content += '<button type="button" class="form-control" style="background-color: #96e6a0; color: white;" onclick="print_local_label(\''+response[i].order+'\')">Sin tipo de método</button>';
						$('.send_later').show();
						break;

					}
					content += '<button type="button" class="form-control print" style="background-color: #337ab7; color: white; display:none;" onclick="print_label(\''+response[i].order+'\',\''+response[i].seller_id+'\')">Imprimir</button>';
					content += '<button type="button" class="form-control preview" style="background-color: #f3091c; color: white; display:none;" onclick="hide_label(\''+response[i].order+'\')">Cerrar vista previa</button>';				
					content += '<button type="button" class="form-control" style="background-color: #f37171; color: white; onclick="send_later(\''+response[i].order+'\')">Enviar después</button>';
					content += '</div>';
					content += '</div>';
					content += '<div class="row" style="margin-left:20%; width: 800px; display:none;" id="'+response[i].order+'">';


					//----------------------------------------------------------------------

					content +='<table width="400px" border="1px" style="color:black">';
					content +='<tr style="text-align:center">';
					content +='		<td colspan="2" ><i >Guía a Convenir</i></td>';
					content +='</tr>';
					content +='<tr style="text-align:center">';
					content +='		<td colspan="2"><i>Datos del Destinatario</i></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td>Ciudad</td>';
					content +='		<td><div class=""><h3>'+response[i].buyer_city+'</h3></div></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td>Nombre</td>';
					content +='		<td><div class=""><h3>'+response[i].buyer_fullname+'</h3></div></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td>Teléfono</td>';
					content +='		<td><div class=""><h3>'+response[i].buyer_phone+'</h3></div></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td>Dirección</td>';
					content +='		<td><div class="" style="width:545px; text-align: center;"><h3>'+response[i].buyer_address+'</h2></div></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td colspan="2">';
					content += '		</div>';
					content += '			<div style="height: 20px; background-color: grey;"></div>';
					content += '		<div class="row" style="height: auto;text-align: center; padding-bottom: 10px; margin-left: 0px; width: 800px;">';
					content += '			<h1>Datos del Remitente</h1>';
					content += '			<h3>QUEENBEE</h3>';
					content += '			<h3>NIT.: '+seller_nit+'</h3>';
					content += '			<i>CRA 13 #51 - 25 OFICINA 401</i>';
					content += '			<br>';
					content += '			<i>BOGOTÁ DC</i>';
					content += '			<br>';
					content += '			<i>TELÉFONO: +57 (320) 917 0419</i>';
					content += '		</div>';
					content +='		</td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td colspan="2"></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td>NOTA DE ENVIO</td>';
					content +='		<td><div class=""><h3>'+response[i].order+'</h3></div></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td>Valor Declarado</td>';
					content +='		<td><div class=""><h3>'+response[i].order_price+'</h3></div></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td>Dice Contenedor</td>';
					content +='		<td><div class="" style="border-right: none;"><h3>'+response[i].title+'</h3></div></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td>CANAL</td>';
					content +='		<td><h3>'+seller_name+'</h3></td>';
					content +='</tr>';
					content +='<tr>';
					content +='<td colspan="2">';
					content += '<div class="row" style="height: 170px; display: inline-flex; width: 800px; margin-left: 0px;"> <img src="images/logo.png" style="width: 150px; height: 150px; margin-left: 10px;">';
					content += '<div style="padding-left: 150px; text-align: center; padding-top: 45px;">';
					content += '<i>Consigue los mejores Productos a un Precio más Bajo</i>';
					content += '<h2>ingresa a: www.imppera.com</h2>';
					content += '<h4>320 917 0419</h4>';
					content += '</div></td>';
					content +='</tr>';

					content +='</div>';


					//--------------------------------------------------------
/*
					content += '<div class="body_label">'
					content += '<div style="text-align: center;">';
					content += '<i>Guía a Convenir</i>';
					content += '</div>';
					content += '<div class="header_label"><i>Datos del Destinatario</i></div>';
					content += '<div class="row base_label" style="margin-left: 0px;">';
					content += '<div class="titles_label">Ciudad</div>';
					content += '<div class="col_label s3_label">'+response[i].buyer_city+'</div>';
					content += '<div class="titles_label">Nombre</div>';
					content += '<div class="col_label s3_label">'+response[i].buyer_fullname+'</div>';
					content += '<div class="titles_label">Teléfono</div>';
					content += '<div class="col_label s3_label" style="border-right: none;">'+response[i].buyer_phone+'</div>';
					content += '</div>';
					content += '<div class="row base_label" style="margin-left: 0px;">';
					content += '<div class="col_label s4_label" style="width:254px; border-right: 1px solid black; padding-left: 50px;"><h3>Dirección</h3></div>';      
					content += '<div class="col_label s4_label" style="width:545px; text-align: center;"><h2>'+response[i].buyer_address+'</h2></div>';     
					content += '</div>';
					content += '<div style="height: 20px; background-color: grey; border-top: 1px solid black; border-bottom: 1px solid black;"></div>';
					content += '<div class="row" style="height: auto; border-bottom: 1px solid black; text-align: center; padding-bottom: 10px; margin-left: 0px; width: 800px;">';
					content += '<h1>Datos del Remitente</h1>';
					content += '<h3>'+seller_name+'</h3>';
					content += '<h3>NIT.: '+seller_nit+'</h3>';
					content += '<i>CRA 13 #51 - 25 OFICINA 401</i>';
					content += '<br>';
					content += '<i>BOGOTÁ DC</i>';
					content += '<br>';
					content += '<i>TELÉFONO: +57 (320) 917 0419</i>';
					content += '</div>';
					content += '<div class="row base_label" style="margin-left: 0px;">';
					content += '<div class="titles_label" style="width: 47px; font-size: 10px;">NOTA DE ENVIO</div>';
					content += '<div class="col_label s3_label"><h5>'+response[i].order+'</h5></div>';
					content += '<div class="titles_label" style="width: 47px; font-size: 10px;">Valor Declarado</div>';
					content += '<div class="col_label s3_label"><h3>'+response[i].order_price+'</h3></div>';
					content += '<div class="titles_label" style="width: 47px; font-size: 10px;">Dice Contenedor</div>';
					content += '<div class="col_label s3_label" style="border-right: none;"><p>'+response[i].title+'</p></div>';
					content += '</div>';
					content += '<div class="row base_label" style="height: 80px; margin-left: 0px;">';
					content += '<div class="col_label s4_label" style="width:261px; border-right: 1px solid black;"><h2>CANAL</h2></div>';
					content += '<div class="col_label s4_label" style="width:533px; text-align: center;"><h3>QUEENBEE</h3></div>';
					content += '</div>';
					content += '<div class="row" style="height: 170px; border-top: 1px solid black; display: inline-flex; width: 800px; margin-left: 0px;"> <img src="images/logo.png" style="width: 150px; height: 150px; margin-left: 10px;">';
					content += '<div style="padding-left: 150px; text-align: center; padding-top: 45px;">';
					content += '<i>Consigue los mejores Productos a un Precio más Bajo</i>';
					content += '<h2>ingresa a: www.imppera.com</h2>';
					content += '<h4>320 917 0419</h4>';
					content += '</div>';
					content += '</div>';
					content += '</div>';
					content += '</div>';*/
				}
				$('.loading_gif').hide();
				$('#base_content').append(content);
			}
			
		});
}
//******* END NOT DELIVERED 

//******* START  DELIVERED 
function get_delivered_items_queen_bee() {
	console.log('getting items delivered queen bee...');
	var shop_id = 1;
	$.post(url_base+'services/meli_manager.php',{ action : 'get_order_delivered', shop_id : shop_id, user_id: sessionStorage.getItem('id')}).fail(function(){

	}).done(function(e){
		var rows = "";
		var rows_1 = "";
		var response = JSON.parse(e); 
		var color;
		for(var i in response){
			rows += "<tr>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].id_order+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].update_date+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].sku+"</td>";
			rows += "<td style='width: 100px; word-wrap: break-word;'>"+response[i].mpid+"</td>";
			rows += "<td style='width: 100px; word-wrap: break-word;'>"+response[i].tracking_aws+"</td>";
			if (response[i].quantity != 1){
				rows += "<td style='width: 75px; word-wrap: break-word;'><b style='color:green;'>"+response[i].quantity+"</b></td>";
			}else{
				rows += "<td style='width: 75px; word-wrap: break-word;'>"+response[i].quantity+"</td>";
			}
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver ítem' href = '"+response[i].url+"' target='_blank'><i class='fa fa-eye' style='font-size:24px;'></i></a></td>";
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver nota' onclick='ver_nota(\""+response[i].id_order+"\",\""+shop_id+"\")'>";
			rows += "<i class='fa fa-edit'></i>";
			rows += "</a></td>";
			if (response[i].comentary) {
				rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver comentario' onclick='ver_comentario(\""+response[i].id_order+"\",\""+response[i].comentary+"\")'><i class='fa fa-comments-o' style='color:red; font-size:20px;'></i></a></td>";	
			}else{
				rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver comentario' onclick='ver_comentario(\""+response[i].id_order+"\",\""+response[i].comentary+"\")'><i class='fa fa-comments-o' style='font-size:20px;'></i></a></td>";	
			}
			rows += "</tr>";		
		}
		$('#list_queen_bee > tbody').append(rows);
		init_DataTables_local("list_queen_bee");
	});
}
function get_delivered_items_mauxi() {
	console.log('getting items delivered mauxi...');
	var shop_id = 2;
	$.post(url_base+'services/meli_manager.php',{ action : 'get_order_delivered', shop_id : shop_id, user_id: sessionStorage.getItem('id')}).fail(function(){

	}).done(function(e){
		var rows = "";
		var rows_1 = "";
		var response = JSON.parse(e); 
		var color;
		for(var i in response){
			rows += "<tr>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].id_order+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].update_date+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].sku+"</td>";
			rows += "<td style='width: 100px; word-wrap: break-word;'>"+response[i].mpid+"</td>";
			rows += "<td style='width: 100px; word-wrap: break-word;'>"+response[i].tracking_aws+"</td>";
			if (response[i].quantity != 1){
				rows += "<td style='width: 75px; word-wrap: break-word;'><b style='color:green;'>"+response[i].quantity+"</b></td>";
			}else{
				rows += "<td style='width: 75px; word-wrap: break-word;'>"+response[i].quantity+"</td>";
			}
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver ítem' href = '"+response[i].url+"' target='_blank'><i class='fa fa-eye' style='font-size:24px;'></i></a></td>";
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver nota' onclick='ver_nota(\""+response[i].id_order+"\",\""+shop_id+"\")'>";
			rows += "<i class='fa fa-edit'></i>";
			rows += "</a></td>";
			if (response[i].comentary) {
				rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver comentario' onclick='ver_comentario(\""+response[i].id_order+"\",\""+response[i].comentary+"\")'><i class='fa fa-comments-o' style='color:red; font-size:20px;'></i></a></td>";	
			}else{
				rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver comentario' onclick='ver_comentario(\""+response[i].id_order+"\",\""+response[i].comentary+"\")'><i class='fa fa-comments-o' style='font-size:20px;'></i></a></td>";	
			}
			rows += "</tr>";		
		}
		$('#list_mauxi > tbody').append(rows);
		init_DataTables_local("list_mauxi");
	});
}
//******* END DELIVERED 

//******* START SEARCH TRACKING NUMBER
function search_item(element){
	var content = "";
	$('.loading_gif').show();
	
	$.post(url_base+'services/meli_manager.php',
	{
		action : 'shipping_search',
		aws_tracking : $(element).val(),
		user_id: sessionStorage.getItem('id')
	}).done(function(e){

		var response = JSON.parse(e);
		var seller_name = "";
		var seller_nit = "";
		for(var i in response){
			switch (response[i].account) {
				case "1":
				seller_name = "QUEEN BEE";
				seller_nit = "900936668-1";
				break;
				case "2":
				seller_name = "MAUXI";
				seller_nit = "900936668-1";
				break;
			}
			content += '<div class="row" style="border-bottom: 2px solid #73879c; margin-left: 20px;" id_order = '+response[i].order+' id ="order_detail_'+response[i].order+'">';
			content += '<div class="col-md-4 col-sm-4">';
			content += '<img src="'+response[i].image+'" style="width: 80%;margin-left:30px;">';
			content += '</div>';
			content += '<div class="col-md-4 col-sm-4 form-group">';
			content += '<div class="row">';
			content += '<div class="col-md-6" style="padding-left: 0;">';
			content += '<label for="order">#Orden:</label>';
			content += '<input type="text" name="order" id="order" class="form-control" value="'+response[i].order+'" disable>';
			content += '</div>';
			content += '<div class="col-md-6" style="padding-right:0;">';
			content += '<label for="account">#Cuenta:</label>';
			content += '<input type="text" name="account" id="account" class="form-control" value="'+seller_name+'" disable>';
			content += '</div>';
			content += '</div>';
			content += '<div class="row">';
			content += '<label for="title">Título:</label>';
			content += '<input type="text" name="title" id="title" class="form-control" value="'+response[i].title+'" disable>';
			content += '</div>';
			content += '<div class="row">';
			content += '<label for="aws:price">Precio de compra:</label>';
			content += '<input type="text" name="aws:price" id="aws:price" class="form-control" value="'+response[i].price_aws+'" disable>';
			content += '</div>';
			content += '<div class="row">';
			content += '<div class="col-md-6" style="padding-left: 0;">';
			content += '<label for="price">Precio de venta:</label>';
			content += '<input type="text" name="price" id="price" class="form-control" value="'+response[i].price+'" disable>';
			content += '</div>';
			content += '<div class="col-md-6" style="padding-right: 0;">';
			content += '<label for="quantity">Cantidad:</label>';
			content += '<input type="text" name="quantity" id="quantity" class="form-control" value="'+response[i].quantity+'" disable>';
			content += '</div>';
			content += '</div>';
			content += '<div class="row">';
			content += '<label for="note">Nota:</label>';
			content += '<br>';
			for(var j in response[i].notes){
				content += '<b style="font-size:9px; position:relative; left: 333px;">'+response[i].notes[j].date+'</b>';
				content += '<input type="text" name="note" id="note" class="form-control" value="'+response[i].notes[j].note+'" disable>';
			}
			content += '</div>';
			content += '<div class="row">';
			content += '<label for="comment">Comentario:</label>';
			content += '<input type="text" name="comment" id="comment" class="form-control" value="'+response[i].quantity+'" disable>';
			content += '</div>';
			content += '</div>';
			content += '<div class="col-md-3 col-sm-3" style="margin-top: 24px;">';
			switch (response[i].shipping_mode) {
				case 'me2':
					if (response[i].order_status != 'delivered'){
        				content += '<button type="button" class="form-control mercadoenvio_'+response[i].shipping_id+'" style="background-color: #51cc60; color: white;" onclick="print_meli_label(\''+response[i].token+'\',\''+response[i].shipping_id+'\',\''+response[i].order+'\')">Mercado Envíos</button>';
        				$('.send_later').show();
					}else{
        				content += '<button type="button" class="form-control" style="background-color: #51cc60; color: white;" onclick="">Enviado</button>';}
					break;
				case 'custom':
            		content += '<button type="button" class="form-control" style="background-color: #96e6a0; color: white;" onclick="print_local_label(\''+response[i].order+'\')">Convenir</button>';
            		$('.send_later').show();
					break;
				default:
            		content += '<button type="button" class="form-control" style="background-color: #96e6a0; color: white;" onclick="print_local_label(\''+response[i].order+'\')">Sin tipo de método</button>';
            		$('.send_later').show();
					break;	
			}
			content += '<button type="button" class="form-control print" style="background-color: #337ab7; color: white; display:none;" onclick="print_label(\''+response[i].order+'\',\''+response[i].seller_id+'\')">Imprimir</button>';
			content += '<button type="button" class="form-control preview" style="background-color: #f3091c; color: white; display:none;" onclick="hide_label(\''+response[i].order+'\')">Cerrar vista previa</button>';				
			content += '<button type="button" class="form-control" style="background-color: #f37171; color: white;" onclick="send_later(\''+response[i].order+'\')">Enviar después</button>';
			content += '</div>';
			content += '</div>';
			
			content += '<div class="row" style="margin-left: 20%; display:none; width:800px" id="'+response[i].order+'">';
			content += '<div class="body_label" style="margin-left:0">'
			content +='<table width="400px" border="1px" style="color:black">';
			content +='<tr style="text-align:center">';
			content +='		<td colspan="2" ><i >Guía a Convenir</i></td>';
			content +='</tr>';
			content +='<tr style="text-align:center">';
			content +='		<td colspan="2"><i>Datos del Destinatario</i></td>';	
			content +='</tr>';
			content +='<tr>';
			content +='		<td>Ciudad</td>';
			content +='		<td><div class=""><h3>'+response[i].buyer_city+'</h3></div></td>';
			content +='</tr>';
			content +='<tr>';
			content +='		<td>Nombre</td>';
			content +='		<td><div class=""><h3>'+response[i].buyer_fullname+'</h3></div></td>';
			content +='</tr>';
			content +='<tr>';
			content +='		<td>Teléfono</td>';
			content +='		<td><div class=""><h3>'+response[i].buyer_phone+'</h3></div></td>';
			content +='</tr>';
			content +='<tr>';
			content +='		<td>Dirección</td>';
			content +='		<td><div class="" style="width:545px; text-align: center;"><h3>'+response[i].buyer_address+'</h2></div></td>';
			content +='</tr>';
			content +='<tr>';
			content +='		<td colspan="2">';
			content += '		</div>';
			content += '			<div style="height: 20px; background-color: grey;"></div>';
			content += '		<div class="row" style="height: auto; text-align: center; padding-bottom: 10px; margin-left: 0px; width: 800px;">';
			content += '			<h1>Datos del Remitente</h1>';
			content += '			<h3>QUEENBEE</h3>';
			content += '			<h3>NIT.: '+seller_nit+'</h3>';
			content += '			<i>CRA 13 #51 - 25 OFICINA 401</i>';
			content += '			<br>';
			content += '			<i>BOGOTÁ DC</i>';
			content += '			<br>';
			content += '			<i>TELÉFONO: +57 (320) 917 0419</i>';
			content += '		</div>';
			content +='		</td>';
			content +='</tr>';
			content +='<tr>';
			content +='		<td colspan="2"></td>';
			content +='</tr>';
			content +='<tr>';
			content +='		<td>NOTA DE ENVIO</td>';
			content +='		<td><div class=""><h3>'+response[i].order+'</h3></div></td>';
			content +='</tr>';
			content +='<tr>';
			content +='		<td>Valor Declarado</td>';
			content +='		<td><div class=""><h3>'+response[i].order_price+'</h3></div></td>';
			content +='</tr>';
			content +='<tr>';
			content +='		<td>Dice Contenedor</td>';
			content +='		<td><div class="" style="border-right: none;"><h3>'+response[i].title+'</h3></div></td>';
			content +='</tr>';
			content +='<tr>';
			content +='		<td>CANAL</td>';
			content +='		<td><h3>'+seller_name+'</h3></td>';
			content +='</tr>';
			content +='<tr>';
			content +='<td colspan="2">';
			content += '<div class="row" style="height: 170px; display: inline-flex; width: 800px; margin-left: 0px;"> <img src="images/logo.png" style="width: 150px; height: 150px; margin-left: 10px;">';
			content += '<div style="padding-left: 150px; text-align: center; padding-top: 45px;">';
			content += '<i>Consigue los mejores Productos a un Precio más Bajo</i>';
			content += '<h2>ingresa a: www.imppera.com</h2>';
			content += '<h4>320 917 0419</h4>';
			content += '</div></td>';
			content +='</tr>';
			content += '</table>';
			content += '</div>';
			content += '</div>';
		}
		$('#base_content').empty();
		$('.loading_gif').hide();
		$('#base_content').append(content);
		console.log(response);

			/*
			content += '<div class="row" style="border-bottom: 2px solid #73879c; margin-left: 20px;" id_order = '+response[i].order+' id ="order_detail_'+response[i].order+'">';
			content += '<div class="col-md-4 col-sm-4">';
			content += '<img src="'+response[i].image+'" width="400px">';
			content += '</div>';
			content += '<div class="col-md-4 col-sm-4 form-group">';
			content += '<div class="row">';
			content += '<div class="col-md-6" style="padding-left: 0;">';
			content += '<label for="order">#Orden:</label>';
			content += '<input type="text" name="order" id="order" class="form-control" value="'+response[i].order+'" disable>';
			content += '</div>';
			content += '<div class="col-md-6" style="padding-right:0;">';
			content += '<label for="account">#Cuenta:</label>';
			content += '<input type="text" name="account" id="account" class="form-control" value="'+seller_name+'" disable>';
			content += '</div>';
			content += '</div>';
			content += '<div class="row">';
			content += '<label for="title">Título:</label>';
			content += '<input type="text" name="title" id="title" class="form-control" value="'+response[i].title+'" disable>';
			content += '</div>';
			content += '<div class="row">';
			content += '<label for="aws:price">Precio de compra:</label>';
			content += '<input type="text" name="aws:price" id="aws:price" class="form-control" value="'+response[i].price_aws+'" disable>';
			content += '</div>';
			content += '<div class="row">';
			content += '<div class="col-md-6" style="padding-left: 0;">';
			content += '<label for="price">Precio de venta:</label>';
			content += '<input type="text" name="price" id="price" class="form-control" value="'+response[i].price+'" disable>';
			content += '</div>';
			content += '<div class="col-md-6" style="padding-right: 0;">';
			content += '<label for="quantity">Cantidad:</label>';
			content += '<input type="text" name="quantity" id="quantity" class="form-control" value="'+response[i].quantity+'" disable>';
			content += '</div>';
			content += '</div>';
			content += '<div class="row">';
			content += '<label for="note">Nota:</label>';
			content += '<br>';
			for(var j in response[i].notes){
				content += '<b style="font-size:9px; position:relative; left: 333px;">'+response[i].notes[j].date+'</b>';
				content += '<input type="text" name="note" id="note" class="form-control" value="'+response[i].notes[j].note+'" disable>';
			}
			content += '</div>';
			content += '<div class="row">';
			content += '<label for="comment">Comentario:</label>';
			content += '<input type="text" name="comment" id="comment" class="form-control" value="'+response[i].quantity+'" disable>';
			content += '</div>';
			content += '</div>';
			content += '<div class="col-md-3 col-sm-3" style="margin-top: 24px;">';
			
				switch (response[i].shipping_mode) {
					case 'me2':
						if (response[i].order_status != 'delivered'){
            				content += '<button type="button" class="form-control mercadoenvio_'+response[i].shipping_id+'" style="background-color: #51cc60; color: white;" onclick="print_meli_label(\''+response[i].token+'\',\''+response[i].shipping_id+'\',\''+response[i].order+'\')">Mercado Envíos</button>';
            				$('.send_later').show();
						}else{
            				content += '<button type="button" class="form-control" style="background-color: #51cc60; color: white;" onclick="">Enviado</button>';}
						break;
					case 'custom':
	            		content += '<button type="button" class="form-control" style="background-color: #96e6a0; color: white;" onclick="print_local_label(\''+response[i].order+'\')">Convenir</button>';
	            		$('.send_later').show();
						break;
					default:
	            		content += '<button type="button" class="form-control" style="background-color: #96e6a0; color: white;" onclick="print_local_label(\''+response[i].order+'\')">Sin tipo de método</button>';
	            		$('.send_later').show();
						break;
					
					}
					content += '<button type="button" class="form-control print" style="background-color: #337ab7; color: white; display:none;" onclick="print_label(\''+response[i].order+'\',\''+response[i].seller_id+'\')">Imprimir</button>';
					content += '<button type="button" class="form-control preview" style="background-color: #f3091c; color: white; display:none;" onclick="hide_label(\''+response[i].order+'\')">Cerrar vista previa</button>';				
					content += '<button type="button" class="form-control" style="background-color: #f37171; color: white;" onclick="send_later(\''+response[i].order+'\')">Enviar después</button>';
					content += '</div>';
					content += '</div>';
					
					content += '<div class="row" style="padding-left: 20%; display:none;" id="'+response[i].order+'">';
					content += '<div class="body_label">'
					content +='<table width="400px" border="1px" style="color:black">';
					content +='<tr style="text-align:center">';
					content +='		<td colspan="2" ><i >Guía a Convenir</i></td>';
					content +='</tr>';
					content +='<tr style="text-align:center">';
					content +='		<td colspan="2"><i>Datos del Destinatario</i></td>';	
					content +='</tr>';
					content +='<tr>';
					content +='		<td>Ciudad</td>';
					content +='		<td><div class=""><h3>'+response[i].buyer_city+'</h3></div></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td>Nombre</td>';
					content +='		<td><div class=""><h3>'+response[i].buyer_fullname+'</h3></div></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td>Teléfono</td>';
					content +='		<td><div class=""><h3>'+response[i].buyer_phone+'</h3></div></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td>Dirección</td>';
					content +='		<td><div class="" style="width:545px; text-align: center;"><h3>'+response[i].buyer_address+'</h2></div></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td colspan="2">';
					content += '		</div>';
					content += '			<div style="height: 20px; background-color: grey;"></div>';
					content += '		<div class="row" style="height: auto; text-align: center; padding-bottom: 10px; margin-left: 0px; width: 800px;">';
					content += '			<h1>Datos del Remitente</h1>';
					content += '			<h3>'+seller_name+'</h3>';
					content += '			<h3>NIT.: '+seller_nit+'</h3>';
					content += '			<i>CRA 13 #51 - 25 OFICINA 401</i>';
					content += '			<br>';
					content += '			<i>BOGOTÁ DC</i>';
					content += '			<br>';
					content += '			<i>TELÉFONO: +57 (320) 917 0419</i>';
					content += '		</div>';
					content +='		</td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td colspan="2"></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td>NOTA DE ENVIO</td>';
					content +='		<td><div class=""><h3>'+response[i].order+'</h3></div></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td>Valor Declarado</td>';
					content +='		<td><div class=""><h3>'+response[i].order_price+'</h3></div></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td>Dice Contenedor</td>';
					content +='		<td><div class="" style="border-right: none;"><h3>'+response[i].title+'</h3></div></td>';
					content +='</tr>';
					content +='<tr>';
					content +='		<td>CANAL</td>';
					content +='		<td><h3>QUEENBEE</h3></td>';
					content +='</tr>';
					content +='<tr>';
					content +='<td colspan="2">';
					content += '<div class="row" style="height: 170px; display: inline-flex; width: 800px; margin-left: 0px;"> <img src="images/logo.png" style="width: 150px; height: 150px; margin-left: 10px;">';
					content += '<div style="padding-left: 150px; text-align: center; padding-top: 45px;">';
					content += '<i>Consigue los mejores Productos a un Precio más Bajo</i>';
					content += '<h2>ingresa a: www.imppera.com</h2>';
					content += '<h4>320 917 0419</h4>';
					content += '</div></td>';
					content +='</tr>';
					content += '</table>';
					content += '</div>';
					content += '</div>';
				}
				$('.loading_gif').hide();
				$('#base_content').append(content);
				console.log(response);*/
			

			});
}
//******* END SEARCH TRACKING NUMBER
//+++++++++++++++++++++++++++++++++++ END TRACKING FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
/*
+
+
*/
//+++++++++++++++++++++++++++++++++++ START ORDERS FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
function get_orders_queen_bee() {
	console.log('getting items queen bee...');
	var shop_id = 1;
	$.post(url_base+'services/meli_manager.php',{ action : 'get_order', shop_id : shop_id, user_id: sessionStorage.getItem('id')}).fail(function(){

	}).done(function(e){
		var rows = "";
		var rows_1 = "";
		$('#list_queen_bee > tbody').append(e);
		init_DataTables_local("list_queen_bee");
	});
}
function get_orders_mauxi() {
	console.log('getting items mauxi...');
	var shop_id = 2;
	$.post(url_base+'services/meli_manager.php',{ action : 'get_order', shop_id : shop_id, user_id: sessionStorage.getItem('id')}).fail(function(){

	}).done(function(e){
		var rows = "";
		var rows_1 = "";
		$('#list_mauxi > tbody').append(e);
		init_DataTables_local("list_mauxi");
	});
}
//+++++++++++++++++++++++++++++++++++ END ORDERS FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
/*
+
+
*/
//+++++++++++++++++++++++++++++++++++ START PENDING(P) ORDERS FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
function get_orders_quee_bee_pending() {
	console.log('getting pending items mauxi...');
	var shop_id = 1;
	$.post(url_base+'services/meli_manager.php',{ action : 'get_order_pending', shop_id : shop_id, user_id: sessionStorage.getItem('id')}).fail(function(){

	}).done(function(e){
		var rows = "";
		var msn = "";
		var icon = "";
		var rows_1 = "";
		var response = JSON.parse(e); 
		var color;
		for(var i in response){
			rows += "<tr>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].id_order+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].create_date+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].sku+"</td>";
			rows += "<td style='width: 100px; word-wrap: break-word;'><a href='"+response[i].permalink+"' target='_blank' >"+response[i].mpid+"</a></td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].sale_price+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].unit_price+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].total_paid+"</td>";
			if (response[i].quantity != 1){
				rows += "<td style='width: 75px; word-wrap: break-word;'><b style='color:green;'>"+response[i].quantity+"</b></td>";
			}else{
				if (response[i].quantity != 1){
				rows += "<td style='width: 75px; word-wrap: break-word;'><b style='color:green;'>"+response[i].quantity+"</b></td>";
			}else{
				rows += "<td style='width: 75px; word-wrap: break-word;'>"+response[i].quantity+"</td>";
			}
			}
			if (response[i].avaliable == 'f' || response[i].unit_price < response[i].precio_esp) {
				color = 'orange';
                icon  = 'fa fa-exclamation-triangle';
			}else{
				color = 'green';
                icon  = 'fa fa-thumbs-o-up';
			}
			msn='OK';
            if(response[i].avaliable == 'f'){
                msn=' No Disponible';
            } else if(response[i].unit_price < response[i].precio_esp){
                msn='Valor esperado de compra $'+response[i].precio_esp;    
            }
			rows += "<td style='width: 80px; word-wrap: break-word; color : white; text-align:center; font-size:24px;'>"+response[i].avaliable+"<i style='color : "+color+"' class='"+icon+"' title='"+msn+"'></i></td>";
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver ítem' href = '"+response[i].url+"' target='_blank'><i class='fa fa-eye' style='font-size:24px;'></i></a></td>";
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver nota' onclick='ver_nota(\""+response[i].id_order+"\",\""+shop_id+"\")'>";
			rows += "<i class='fa fa-edit'></i>";
			rows += "</a></td>";
			if (response[i].comentary) {
				rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver comentario' onclick='ver_comentario(\""+response[i].id_order+"\",\""+response[i].comentary+"\")'><i class='fa fa-comments-o' style='color:red; font-size:20px;'></i></a></td>";	
			}else{
				rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver comentario' onclick='ver_comentario(\""+response[i].id_order+"\",\""+response[i].comentary+"\")'><i class='fa fa-comments-o' style='font-size:20px;'></i></a></td>";	
			}
			rows += "<td style='width: 150px; word-wrap: break-word; color: white;' id='res_"+response[i].id_order+"'>"+response[i].autorice+"<a class='btn "+response[i].id+"' title='Confirmar orden' onclick='confirm_order(\""+response[i].id+"\",\""+response[i].id_order+"\")'><i class='fa fa-check-square'style='font-size:20px; color:green;'></i></a><a class='btn "+response[i].id+"' title='Rechazar orden' onclick='refuse_order(\""+response[i].id_order+"\",\""+response[i].id_order+"\",0)'><i class='fa fa-times-circle'style='font-size:20px; color:red;'></i></a></td>";
			rows += "</tr>";		
		}
		$('#list_queen_bee > tbody').append(rows);
		init_DataTables_local("list_queen_bee");
	});
}
function get_orders_mauxi_pending() {
	console.log('getting pending items mauxi...');
	var shop_id = 2;
	$.post(url_base+'services/meli_manager.php',{ action : 'get_order_pending', shop_id : shop_id, user_id: sessionStorage.getItem('id')}).fail(function(){

	}).done(function(e){
		var rows = "";
		var msn = "";
		var icon = "";
		var rows_1 = "";
		var response = JSON.parse(e); 
		var color;
		for(var i in response){
			rows += "<tr>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].id_order+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].create_date+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].sku+"</td>";
			rows += "<td style='width: 100px; word-wrap: break-word;'><a href='"+response[i].permalink+"' target='_blank' >"+response[i].mpid+"</a></td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].sale_price+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].unit_price+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].total_paid+"</td>";
			if (response[i].quantity != 1){
				rows += "<td style='width: 75px; word-wrap: break-word;'><b style='color:green;'>"+response[i].quantity+"</b></td>";
			}else{
				rows += "<td style='width: 75px; word-wrap: break-word;'>"+response[i].quantity+"</td>";
			}
			if (response[i].avaliable == 'f' || response[i].unit_price < response[i].precio_esp) {
				color = 'orange';
                icon  = 'fa fa-exclamation-triangle';
			}else{
				color = 'green';
                icon  = 'fa fa-thumbs-o-up';
			}
			msn='OK';
            if(response[i].avaliable == 'f'){
                msn='No Disponible';
            } else if(response[i].unit_price < response[i].precio_esp){
                msn='Valor esperado de compra $'+response[i].precio_esp;    
            }
			rows += "<td style='width: 80px; word-wrap: break-word; color : white; text-align:center; font-size:24px;'>"+response[i].avaliable+"<i style='color : "+color+"' class='"+icon+"' title='"+msn+"'></i></td>";
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver ítem' href = '"+response[i].url+"' target='_blank'><i class='fa fa-eye' style='font-size:24px;'></i></a></td>";
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver nota' onclick='ver_nota(\""+response[i].id_order+"\",\""+shop_id+"\")'>";
			rows += "<i class='fa fa-edit'></i>";
			rows += "</a></td>";
			if (response[i].comentary) {
				rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver comentario' onclick='ver_comentario(\""+response[i].id_order+"\",\""+response[i].comentary+"\")'><i class='fa fa-comments-o' style='color:red; font-size:20px;'></i></a></td>";	
			}else{
				rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver comentario' onclick='ver_comentario(\""+response[i].id_order+"\",\""+response[i].comentary+"\")'><i class='fa fa-comments-o' style='font-size:20px;'></i></a></td>";	
			}
			rows += "<td style='width: 150px; word-wrap: break-word; color: white;' id='res_"+response[i].id_order+"'>"+response[i].autorice+"<a class='btn "+response[i].id+"' title='Confirmar orden' onclick='confirm_order(\""+response[i].id+"\",\""+response[i].id_order+"\")'><i class='fa fa-check-square'style='font-size:20px; color:green;'></i></a><a class='btn "+response[i].id+"' title='Rechazar orden' onclick='refuse_order(\""+response[i].id_order+"\",\""+response[i].id_order+"\",0)'><i class='fa fa-times-circle'style='font-size:20px; color:red;'></i></a></td>";
			rows += "</tr>";		
		}
		$('#list_mauxi > tbody').append(rows);
		init_DataTables_local("list_mauxi");
	});
}
//+++++++++++++++++++++++++++++++++++ END PENDING ORDERS FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
/*
+
+
*/
//+++++++++++++++++++++++++++++++++++ START CANCEL(R) ORDERS FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
function get_orders_queen_bee_cancel() {
	console.log('getting canceled items mauxi...');
	var shop_id = 1;
	$.post(url_base+'services/meli_manager.php',{ action : 'get_order_cancel', shop_id : shop_id, user_id: sessionStorage.getItem('id')}).fail(function(){

	}).done(function(e){
		var rows = "";
		var msn = "";
		var icon = "";
		var rows_1 = "";
		var response = JSON.parse(e); 
		var color;
		for(var i in response){
			rows += "<tr>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].id_order+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].create_date+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].sku+"</td>";
			rows += "<td style='width: 100px; word-wrap: break-word;'><a href='"+response[i].permalink+"' target='_blank' >"+response[i].mpid+"</a></td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].sale_price+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].unit_price+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].total_paid+"</td>";
			if (response[i].quantity != 1){
				rows += "<td style='width: 75px; word-wrap: break-word;'><b style='color:green;'>"+response[i].quantity+"</b></td>";
			}else{
				rows += "<td style='width: 75px; word-wrap: break-word;'>"+response[i].quantity+"</td>";
			}
			if (response[i].avaliable == 'f' || response[i].unit_price < response[i].precio_esp) {
				color = 'orange';
                icon  = 'fa fa-exclamation-triangle';
			}else{
				color = 'green';
                icon  = 'fa fa-thumbs-o-up';
			}
			msn='OK';
            if(response[i].avaliable == 'f'){
                msn=' No Disponible';
            } else if(response[i].unit_price < response[i].precio_esp){
                msn='Valor esperado de compra $'+response[i].precio_esp;    
            }
			rows += "<td style='width: 80px; word-wrap: break-word; color : white; text-align:center; font-size:24px;'>"+response[i].avaliable+"<i style='color : "+color+"' class='"+icon+"' title='"+msn+"'></i></td>";
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver ítem' href = '"+response[i].url+"' target='_blank'><i class='fa fa-eye' style='font-size:24px;'></i></a></td>";
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver nota' onclick='ver_nota(\""+response[i].id_order+"\",\""+shop_id+"\")'>";
			rows += "<i class='fa fa-edit'></i>";
			rows += "</a></td>";
			rows += "<td style='width: 150px; word-wrap: break-word; color: white;' id='res_"+response[i].id_order+"'>"+response[i].autorice+"<a class='btn "+response[i].id+"' title='Confirmar orden' onclick='confirm_order(\""+response[i].id+"\",\""+response[i].id_order+"\")'><i class='fa fa-check-square'style='font-size:20px; color:green;'></i></a></td>";
			rows += "</tr>";		
		}
		$('#list_queen_bee > tbody').append(rows);
		init_DataTables_local("list_queen_bee");
	});
}
function get_orders_mauxi_cancel() {
	console.log('getting canceled items mauxi...');
	var shop_id = 2;
	$.post(url_base+'services/meli_manager.php',{ action : 'get_order_cancel', shop_id : shop_id, user_id: sessionStorage.getItem('id')}).fail(function(){

	}).done(function(e){
		var rows = "";
		var msn = "";
		var icon = "";
		var rows_1 = "";
		var response = JSON.parse(e); 
		var color;
		for(var i in response){
			rows += "<tr>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].id_order+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].create_date+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].sku+"</td>";
			rows += "<td style='width: 100px; word-wrap: break-word;'><a href='"+response[i].permalink+"' target='_blank' >"+response[i].mpid+"</a></td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].sale_price+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].unit_price+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].total_paid+"</td>";
			if (response[i].quantity != 1){
				rows += "<td style='width: 75px; word-wrap: break-word;'><b style='color:green;'>"+response[i].quantity+"</b></td>";
			}else{
				rows += "<td style='width: 75px; word-wrap: break-word;'>"+response[i].quantity+"</td>";
			}
			if (response[i].avaliable == 'f' || response[i].unit_price < response[i].precio_esp) {
				color = 'orange';
                icon  = 'fa fa-exclamation-triangle';
			}else{
				color = 'green';
                icon  = 'fa fa-thumbs-o-up';
			}
			msn='OK';
            if(response[i].avaliable == 'f'){
                msn=' No Disponible';
            } else if(response[i].unit_price < response[i].precio_esp){
                msn='Valor esperado de compra $'+response[i].precio_esp;    
            }
			rows += "<td style='width: 80px; word-wrap: break-word; color : white; text-align:center; font-size:24px;'>"+response[i].avaliable+"<i style='color : "+color+"' class='"+icon+"' title='"+msn+"'></i></td>";
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver ítem' href = '"+response[i].url+"' target='_blank'><i class='fa fa-eye' style='font-size:24px;'></i></a></td>";
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver nota' onclick='ver_nota(\""+response[i].id_order+"\",\""+shop_id+"\")'>";
			rows += "<i class='fa fa-edit'></i>";
			rows += "</a></td>";
			rows += "<td style='width: 150px; word-wrap: break-word; color: white;' id='res_"+response[i].id_order+"'>"+response[i].autorice+"<a class='btn "+response[i].id+"' title='Confirmar orden' onclick='confirm_order(\""+response[i].id+"\",\""+response[i].id_order+"\")'><i class='fa fa-check-square'style='font-size:20px; color:green;'></i></a></td>";
			rows += "</tr>";		
		}
		$('#list_mauxi > tbody').append(rows);
		init_DataTables_local("list_mauxi");
	});
}
//+++++++++++++++++++++++++++++++++++ END CANCEL ORDERS FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
/*
+
+
*/
//+++++++++++++++++++++++++++++++++++ START BUYER MANAGER(C) FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
function buyer_manager_queen_bee() {
	console.log('getting items queen bee...');
	var shop_id = 1;
	$.post(url_base+'services/meli_manager.php',{ action : 'get_order_aws', shop_id : shop_id, user_id: sessionStorage.getItem('id')}).fail(function(){

	}).done(function(e){
		var rows = "";
		var rows_1 = "";
		$('#list_queen_bee > tbody').append(e);
		init_DataTables_local("list_queen_bee");
	});
}
function buyer_manager_mauxi() {
	console.log('getting items mauxi...');
	var shop_id = 2;
	$.post(url_base+'services/meli_manager.php',{ action : 'get_order_aws', shop_id : shop_id, user_id: sessionStorage.getItem('id')}).fail(function(){

	}).done(function(e){
		var rows = "";
		var rows_1 = "";
		$('#list_mauxi > tbody').append(e);
		init_DataTables_local("list_mauxi");
	});
}
//+++++++++++++++++++++++++++++++++++ END CANCEL BUYER MANAGER FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
/*
+
+
*/
//+++++++++++++++++++++++++++++++++++ START ERROR MANAGER(N) FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
function error_manager_queen_bee() {
	console.log('getting error items queen bee...');
	var shop_id = 1;
	$.post(url_base+'services/meli_manager.php',{ action : 'get_order_error', shop_id : shop_id, user_id: sessionStorage.getItem('id')}).fail(function(){

	}).done(function(e){
		var rows = "";
		var rows_1 = "";
		var response = JSON.parse(e); 
		var color;
		for(var i in response){
			rows += "<tr>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].id_order+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].create_date+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].sku+"</td>";
			rows += "<td style='width: 100px; word-wrap: break-word;'>"+response[i].mpid+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].sale_price+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].unit_price+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].total_paid+"</td>";
			if (response[i].quantity != 1){
				rows += "<td style='width: 75px; word-wrap: break-word;'><b style='color:green;'>"+response[i].quantity+"</b></td>";
			}else{
				rows += "<td style='width: 75px; word-wrap: break-word;'>"+response[i].quantity+"</td>";
			}
			if (response[i].avaliable == 'f') {
				color = 'red';
			}else{
				color = 'green';
			}
			rows += "<td style='width: 80px; word-wrap: break-word; color : white; text-align:center; font-size:24px;'>"+response[i].avaliable+"<i style='color : "+color+"' class='fa fa-exclamation-circle'></i></td>";
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver ítem' href = '"+response[i].url+"' target='_blank'><i class='fa fa-eye' style='font-size:24px;'></i></a></td>";
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Agregar una nota' onclick='ver_nota(\""+response[i].id_order+"\",\""+response[i].comentary+"\")'>";
			rows += "<i class='fa fa-edit'></i>";
			rows += "</a></td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].comentary+"</td>";
			rows += "<i class='fa fa-edit'></i>";
			rows += "</a></td>";			
			rows += "<td style='width: 300px; word-wrap: break-word;color: white; padding-top:12px;' id='res_"+response[i].id_order+"'>"+response[i].autorice+" <a class='btn "+response[i].id+"' title='Confirmar orden' onclick='confirm_order(\""+response[i].id+"\",\""+response[i].id_order+"\")'><i class='fa fa-check-square'style='font-size:20px; color:green;'></i></a><a class='btn "+response[i].id+"' title='Rechazar orden' onclick='refuse_order(\""+response[i].id_order+"\",\""+response[i].id_order+"\",0)'><i class='fa fa-times-circle'style='font-size:20px; color:red;'></i></a></td>";			
			rows += "</tr>";		
		}
		$('#list_queen_bee > tbody').append(rows);
		init_DataTables_local("list_queen_bee");
	});
}
function error_manager_mauxi() {
	console.log('getting error items mauxi...');
	var shop_id = 2;
	$.post(url_base+'services/meli_manager.php',{ action : 'get_order_error', shop_id : shop_id, user_id: sessionStorage.getItem('id')}).fail(function(){

	}).done(function(e){
		var rows = "";
		var rows_1 = "";
		var response = JSON.parse(e); 
		var color;
		for(var i in response){
			rows += "<tr>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].id_order+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].create_date+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].sku+"</td>";
			rows += "<td style='width: 100px; word-wrap: break-word;'>"+response[i].mpid+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].sale_price+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].unit_price+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].total_paid+"</td>";
			if (response[i].quantity != 1){
				rows += "<td style='width: 75px; word-wrap: break-word;'><b style='color:green;'>"+response[i].quantity+"</b></td>";
			}else{
				rows += "<td style='width: 75px; word-wrap: break-word;'>"+response[i].quantity+"</td>";
			}
			if (response[i].avaliable == 'f') {
				color = 'red';
			}else{
				color = 'green';
			}
			rows += "<td style='width: 80px; word-wrap: break-word; color : white; text-align:center; font-size:24px;'>"+response[i].avaliable+"<i style='color : "+color+"' class='fa fa-exclamation-circle'></i></td>";
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver ítem' href = '"+response[i].url+"' target='_blank'><i class='fa fa-eye' style='font-size:24px;'></i></a></td>";
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Agregar una nota' onclick='ver_nota(\""+response[i].id_order+"\",\""+response[i].comentary+"\")'>";
			rows += "<i class='fa fa-edit'></i>";
			rows += "</a></td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].comentary+"</td>";
			rows += "<i class='fa fa-edit'></i>";
			rows += "</a></td>";			
			rows += "<td style='width: 300px; word-wrap: break-word;color: white; padding-top:12px;' id='res_"+response[i].id_order+"'>"+response[i].autorice+" <a class='btn "+response[i].id+"' title='Confirmar orden' onclick='confirm_order(\""+response[i].id+"\",\""+response[i].id_order+"\")'><i class='fa fa-check-square'style='font-size:20px; color:green;'></i></a><a class='btn "+response[i].id+"' title='Rechazar orden' onclick='refuse_order(\""+response[i].id_order+"\",\""+response[i].id_order+"\",0)'><i class='fa fa-times-circle'style='font-size:20px; color:red;'></i></a></td>";			
			rows += "</tr>";		
		}
		$('#list_mauxi > tbody').append(rows);
		init_DataTables_local("list_mauxi");
	});
}
//+++++++++++++++++++++++++++++++++++ END CANCEL ERROR MANAGER FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
/*
+
+
*/
//+++++++++++++++++++++++++++++++++++ START WARRANTY MANAGER FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
function get_warranty_items() {
	console.log('getting items warranties...');
	$.post(url_base+'services/meli_manager.php',{ action : 'get_order_aws_warranty', user_id: sessionStorage.getItem('id')}).fail(function(){

	}).done(function(e){
		var rows = "";
		var rows_1 = "";
		var response = JSON.parse(e); 
		var color;
		for(var i in response){	
			rows += "<tr>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].create_date+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].aws_id_order+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].meli_id_order+"</td>";
			rows += "<td style='width: 75px; word-wrap: break-word;'>Mauxi</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].dolar_price+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].reason+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].status+"</td>";			
			rows += "</tr>";		
		}
		
		$('#warranty_items_list > tbody').append(rows);
		init_DataTables_local("warranty_items_list");
	});
}
//******* START NEW WARRANTIES
function new_warranty(){
	$.post(url_base+'services/meli_manager.php',
	{
		action : 'new_warranty',
		create_date : $('#create_date').val(),
		aws_id_order : $('#aws_id_order').val(),
		meli_id_order : $('#meli_id_order').val(),
		meli_account : $('#meli_account').val(),
		dolar_price : $('#dolar_price').val(),
		reason : $('#reason').val(),
		status : $('#status').val(),
		user_id: sessionStorage.getItem('id')	
	}).done(function(e){
		var response = JSON.parse(e);
		if (response.response == 1) {
			alert("Añadido con éxito");
			location.reload();
		}else{
			alert("Ha ocurrido un error al procesar la información");
		}
		$(".new_warranty").modal("hide");
	});
}
//******* END NEW WARRANTIES

//+++++++++++++++++++++++++++++++++++ END WARRANTY ERROR MANAGER FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
/*
+
+
*/
//+++++++++++++++++++++++++++++++++++ START EXTRA ORDERS MANAGER FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
function get_extra_order_items() {
	console.log('getting items Extra orders...');
	$.post(url_base+'services/meli_manager.php',{ action : 'get_order_aws_extra', user_id: sessionStorage.getItem('id')}).fail(function(){

	}).done(function(e){
		var rows = "";
		var rows_1 = "";
		var response = JSON.parse(e); 
		var color;
		for(var i in response){			
			rows += "<tr>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].create_date+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].applicant+"</td>";
			rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver ítem' href = '"+response[i].url+"' target='_blank'><i class='fa fa-eye' style='font-size:20px;'></i></a></td>";
			if (response[i].quantity != 1){
				rows += "<td style='width: 75px; word-wrap: break-word;'><b style='color:green;'>"+response[i].quantity+"</b></td>";
			}else{
				rows += "<td style='width: 75px; word-wrap: break-word;'>"+response[i].quantity+"</td>";
			}
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].sale_price+"</td>";
			rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].prime+"</td>";
			if (response[i].comentary) {
				rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver comentario' onclick='ver_comentario(\""+response[i].id_order+"\",\""+response[i].comentary+"\")'><i class='fa fa-comments-o' style='color:red; font-size:20px;'></i></a></td>";	
			}else{
				rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver comentario' onclick='ver_comentario(\""+response[i].id_order+"\",\""+response[i].comentary+"\")'><i class='fa fa-comments-o' style='font-size:20px;'></i></a></td>";	
			}
			rows += "<td style='width: 75px; word-wrap: break-word;'>"+response[i].id_order_aws+"</td>";
			rows += "<td style='width: 75px; word-wrap: break-word;'>"+response[i].tracking_aws+"</td>";
			rows += "<td style='width: 75px; word-wrap: break-word;'>"+response[i].cuenta+"</td>";
			rows += "<td style='width: 70px; word-wrap: break-word;'><a class='btn' title='Ver detalle ítem' onclick='ver_item_detail(\""+response[i].id+"\")'><i class='fa fa-pencil' style='font-size:20px;'></i></a></td>";
			rows += "</tr>";		
		}
		
		$('#extra_orders_items > tbody').append(rows);
		init_DataTables_local("extra_orders_items");
	});
}
//******* START NEW ORDERS
function new_order(){
	$.post(url_base+'services/meli_manager.php',
	{
		action : 'new_order',
		create_date : $('#create_date').val(),
		who_buy : $('#who_buy').val(),
		aws_url : $('#aws_url').val(),
		quantity : $('#quantity').val(),
		prime : $('#prime').val(),
		commentary : $('#commentary').val(),
		aws_id_order : $('#aws_id_order').val(),
		tracking : $('#tracking').val(),
		account : $('#account').val(),
		user_id: sessionStorage.getItem('id')
	}).done(function(e){
		var response = JSON.parse(e);
		if (response.response == 1) {
			alert("Añadido con éxito");
			location.reload();
		}else{
			alert("Ha ocurrido un error al procesar la información");
		}
		$(".new_order").modal("hide");
	});
}
//******* END NEW ORDERS
//+++++++++++++++++++++++++++++++++++ END EXTRA ORDERS MANAGER FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
/*
+
+
*/
//+++++++++++++++++++++++++++++++++++ START WORKFLOW MANAGER FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
//******* START ORDER STATUS MANAGER FUNCTIONS
function confirm_order(id_order,id_element){
	$.post(url_base+'services/meli_manager.php',{
		action : 'confirm_order',
		id_order : id_order,
		user_id: sessionStorage.getItem('id')
	}).done(function(e){
		var response = JSON.parse(e);
		var prop = "."+id_order;
		alert("Orden "+id_element+" CONFIRMADA");
		$('#res_'+id_element).html("<a class='btn' title='Comprada orden' style='background-color:  green; width:  97px;height:  25px; color:white; font-size:9px; margin-top:13px;'>CONFIRMADA</a>");
		});
}
function pending_order(id_order,id_element){
	$.post(url_base+'services/meli_manager.php',{
		action : 'pending_order',
		id_order : id_order,
		user_id: sessionStorage.getItem('id')
	}).done(function(e){
		var response = JSON.parse(e);
		var prop = "."+id_order;
		alert("Orden "+id_element+" Pendiente por comprar");
		$('#res_'+id_element).html("<a class='btn' title='Comprada orden' style='background-color:  blue; width:  97px;height:  25px; color:white; font-size:9px; margin-top:13px;'>PENDIENTE</a>");


			//location.reload();		
		});
}
function refuse_order(id_order,id_element,type){
	var message;
	switch (type) {
		case 0:
			type = 'R'; //Rechazada
			message = 'RECHAZADA';
			break;
			case 1:
			type = 'NV'; //Novedad
			message = 'NOVEDAD';
			break;
			case 2:
			type = 'SL'; //Enviar despues
			break;
			case 3:
			type = 'SD';//Enviado
			break;
			case 4:
			type = 'G';//Ordenes
			break;

		}
		$.post(url_base+'services/meli_manager.php',{
			action : 'refuse_order',
			id_order : id_order,
			type: type,
			user_id: sessionStorage.getItem('id')
		}).done(function(e){
			var response = JSON.parse(e);
			var prop = "."+id_order;
			if (id_element !== null) {
				alert("Orden "+id_element+" "+message);
				$('#res_'+id_element).html("<a class='btn' title='Novedad en item' style='background-color:  red;width:  97px;height:  25px; color:white; font-size:9px; margin-top:13px;'>"+message+"</a>");
			}
		//location.reload();
	});
	}

	function buy_order(id_order,id_element){
		$.post(url_base+'services/meli_manager.php',{
			action : 'buy_order',
			id_order : id_order,
			user_id: sessionStorage.getItem('id')
		}).done(function(e){
			var response = JSON.parse(e);
			var prop = "."+id_order;
			alert("Orden "+id_element+" OK");
			$('#res_'+id_element).html("<a class='btn' title='Comprada orden' style='background-color:  green;width:  97px;height:  25px; color:white; font-size:9px; margin-top:13px;'>COMPRADO</a>");
	});
	}

	function ver_comentario(id_order,commentary) {	
		$('#comment_local').val(commentary);
		$(".item_comentary_modal").modal("show");
		$(".save_comment").attr('id_order',id_order);	
	}

	function ver_nota(id_order,shop_id) {	
		var comment;
		var notes = "";
		$(".save_comment").attr('id_order',id_order);
		$.post(url_base+'services/meli_manager.php',{
			action : 'get_note',
			shop_id : shop_id,
			id_order : id_order,
			user_id: sessionStorage.getItem('id')
		}).done(function(e){
			var response = JSON.parse(e);
			for(var list in response){
				notes += "<b><h5 style='font-size:10px;'>Fecha: "+response[list].date+"</h5></b><q style='margin-left:50px;'>"+response[list].note+"</q><hr>";
				notes += "<br>";
			}
			$(".notes_list").empty();
			$(".notes_list").append(notes);
			$(".item_detail_modal").modal("show");
		});
	}

	function update_comment(id_order){
		$.post(url_base+'services/meli_manager.php',{
			action : 'update_comment',
			id_order : $(id_order).attr('id_order'),
			comment : $('#comment_local').val(),
			user_id: sessionStorage.getItem('id')
		}).done(function(e){
			var response = JSON.parse(e);
			if (response.response == 1) {
				alert("Actualizado con éxito");
				$('#comment_local').val();
			}else{
				alert("Ha ocurrido un error al procesar la información");
			}
			$(".item_comentary_modal").modal("hide");

		});
	}

	function send_message(id_order){
		$.post(url_base+'services/meli_manager.php',{
			action : 'create_note',
			id_order : $(id_order).attr('id_order'),
			text : $('#comment').val()
		}).done(function(e){
			var response = JSON.parse(e);
			if (response.response == 1) {
				alert("Actualizado con éxito");
			}else{
				alert("Ha ocurrido un error al procesar la información");
			}
			$(".item_comentary_modal").modal("hide");

		});
	}



	function get_meli_items_queen_bee() {
		console.log('getting items queen bee');
		$.post(url_base+'services/meli_manager.php',{ action : 'get_order', shop_id : 1,user_id: sessionStorage.getItem('id')}).fail(function(){

		}).done(function(e){
			var rows = "";
			var rows_1 = "";
			var response = JSON.parse(e); 
			var color;
			for(var i in response){
				rows += "<tr>";
				rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].id_order+"</td>";
				rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].create_date+"</td>";
				rows += "<td style='width: 90px; word-wrap: break-word;'>"+response[i].sku+"</td>";
				rows += "<td style='width: 100px; word-wrap: break-word;'>"+response[i].mpid+"</td>";
				rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].sale_price+"</td>";
				rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].unit_price+"</td>";
				rows += "<td style='width: 90px; word-wrap: break-word;'>$"+response[i].total_paid+"</td>";
				if (response[i].quantity != 1){
				rows += "<td style='width: 75px; word-wrap: break-word;'><b style='color:green;'>"+response[i].quantity+"</b></td>";
			}else{
				rows += "<td style='width: 75px; word-wrap: break-word;'>"+response[i].quantity+"</td>";
			}
				if (response[i].avaliable == 'f') {
					color = 'red';
				}else{
					color = 'green';
				}
				rows += "<td style='width: 80px; word-wrap: break-word; color : white; text-align:center; font-size:24px;'>"+response[i].avaliable+"<i style='color : "+color+"' class='fa fa-exclamation-circle'></i></td>";
				rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver ítem' href = '"+response[i].url+"' target='_blank'><i class='fa fa-eye' style='font-size:24px;'></i></a></td>";
				rows += "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Agregar una nota' onclick='ver_nota(\""+response[i].id_order+"\",\""+response[i].comentary+"\")'>";
				rows += "<i class='fa fa-edit'></i>";
				rows += "</a></td>";
				switch (response[i].autorice) {
					case 'R':
					rows += "<td style='width: 150px; word-wrap: break-word; color: white;' id='res_"+response[i].id_order+"'>"+response[i].autorice+"<a class='btn "+response[i].id+"' title='Confirmar orden' onclick='confirm_order(\""+response[i].id+"\",\""+response[i].id_order+"\")'><i class='fa fa-check-square'style='font-size:20px; color:red;'></i></a></td>";
					break;
					case 'B':
					rows += "<td style='width: 150px; word-wrap: break-word; color: white;' id='res_"+response[i].id_order+"'>"+response[i].autorice+"<a class='btn "+response[i].id+"' title='Comprado'><i class='fa fa-amazon'style='font-size:20px; color:orange;'></i></a></td>";
					break;
					case 'C':
					rows += "<td style='width: 150px; word-wrap: break-word;color: white;' id='res_"+response[i].id_order+"'>"+response[i].autorice+"<a class='btn "+response[i].id+"' title='Rechazar orden' onclick='refuse_order(\""+response[i].id_order+"\",\""+response[i].id_order+"\",0)'><i class='fa fa-times-circle'style='font-size:20px; color:red;'></i></a></td>";
					break;
					case 'G':
					rows += "<td style='width: 150px; word-wrap: break-word;color: white;' id='res_"+response[i].id_order+"'>"+response[i].autorice+" <a class='btn "+response[i].id+"' title='Confirmar orden' onclick='confirm_order(\""+response[i].id+"\",\""+response[i].id_order+"\")'><i class='fa fa-check-square'style='font-size:20px; color:red;'></i></a><a class='btn "+response[i].id+"' title='Rechazar orden' onclick='refuse_order(\""+response[i].id_order+"\",\""+response[i].id_order+"\",0)'><i class='fa fa-times-circle'style='font-size:20px; color:red;'></i></a></td>";
					break;
					default:
					rows += "<td style='width: 150px; word-wrap: break-word;color: white;' id='res_"+response[i].id_order+"'>"+response[i].autorice+" <a class='btn "+response[i].id+"' title='Confirmar orden' onclick='confirm_order(\""+response[i].id+"\",\""+response[i].id_order+"\")'><i class='fa fa-check-square'style='font-size:20px; color:red;'></i></a><a class='btn "+response[i].id+"' title='Rechazar orden' onclick='refuse_order(\""+response[i].id_order+"\",\""+response[i].id_order+"\",0)'><i class='fa fa-times-circle'style='font-size:20px; color:red;'></i></a></td>";
					break;
				}
				rows += "</tr>";		
			}
			$('#cbt_items_list_queen > tbody').append(rows);
			init_DataTables_local("cbt_items_list_queen");
		});
}
//******* END ORDER STATUS MANAGER FUNCTIONS

//******* START TRACKING UPDATE FILE MANAGER
function aws_file(){
	var data = new FormData();
	console.log($('input:file')[0].files[0]);
	data.append('action','loadFile');
	data.append('file',$('input:file')[0].files[0]);  
	data.append('user_id',sessionStorage.getItem('id'));
	$(".loading").show();
	$.ajax({
		url : url_base+'services/meli_manager.php',
		data: data,
		cache: false,
		contentType: false,
		processData: false,
		type: 'POST'
	}).done(function (data) {
		$(".loading").hide();
		var response = JSON.parse(data);
		if (response.response == 1) {          
			alert('Cargado con éxito!');
		}else{
			alert('Ha ocurrido un error al procesar la información');
		}
	});
}
function aws_extra_file(){
	var data = new FormData();
	console.log($('input:file')[0].files[0]);
	data.append('action','loadFile_aws_extra');
	data.append('file',$('input:file')[0].files[0]);  
	$(".loading").show();
	$.ajax({
		url : url_base+'services/meli_manager.php',
		data: data,
		cache: false,
		contentType: false,
		processData: false,
		type: 'POST'
	}).done(function (data) {
		$(".loading").hide();
		var response = JSON.parse(data);
		if (response.response == 1) {          
			alert('Cargado con éxito!');
		}else{
			alert('Ha ocurrido un error al procesar la información');
		}
	});
}
//******* END TRACKING UPDATE FILE MANAGER

//******* START TRACKING FUNCTIONS MANAGER
function print_local_label(id_order){
	$('#'+id_order).show();
	$('.print').show();
	$('.preview').show();
}

function hide_label(id_order){
	$('#'+id_order).hide();
}

function send_later(id_order){
	refuse_order(id_order,null,2);
	$('#order_detail_'+id_order).hide();
}

function print_label(id_order,id_shop){
	alert('Imprimiendo...');
	var image;
	//var pdfDoc = new jsPDF('landscape', 'px', 'letter');
	var pdfDoc = new jsPDF('p', 'mm', [297, 210]);
	var specialElementHandlers = {
		'.ignoreContent' : function(element, render){return true;}};
		html2canvas(document.getElementById(id_order), {
			onrendered: function(canvas) {
				var imgData = canvas.toDataURL('image/png');
				console.log(imgData);
				pdfDoc.addImage(imgData, 'PNG', 0, 0,210, 297);
				pdfDoc.save('orden_'+id_order+'.pdf');
			}
		});
		refuse_order(id_order,null,3);
	}
function print_meli_label(access_token, shipping_id, id_order,element){
	var url="";
	refuse_order(id_order,null,3);
	alert('Imprimiendo etiqueta de Mercado Envíos');
	$('.mercadoenvio_'+id_order).hide();
	alert(url="http://api.mercadolibre.com/shipment_labels?shipment_ids="+shipping_id+"&savePdf=Y&access_token="+access_token);
	window.open(url);
}
//******* END TRACKING FUNCTIONS MANAGER

//******* START ORDERS FUNCTIONS MANAGER
function ver_item_detail(id){
	sessionStorage.setItem('id_order',id);
	$.post(url_base+'services/meli_manager.php',{
		action:'view_item_detail',
		id: id,
		user_id: sessionStorage.getItem('id')
	}).done(function(e){
		var response = JSON.parse(e);
		if(response.response == 0){
			alert("Error al cargar la información");
		}else{
			$('#priceMl').val(response[0].total_paid);
			$('#quantity').val(response[0].aws_quantity);
			$('#statusMl').val(response[0].status);
			$('#orderAws').val(response[0].id_order_aws);
			$('#buyDate').val(response[0].create_date_buy);
			$('#trackingNumber').val(response[0].tracking_aws);
			$('#trackingStatus').val(response[0].track_status);
			$('#arrivalDate').val(response[0].date_arrival);
			$('#awsAccount').val(response[0].cuenta);
			$('#comentary').val(response[0].comentary);
			$("#price_tool_modal").show();
		}
	});
}

function update_item_detail(){
	$.post(url_base+'services/meli_manager.php',{
		action:'update_item_detail',
		id : sessionStorage.getItem('id_order'),
		priceMl: $('#priceMl').val(),
		quantity:$('#quantity').val(),
		statusMl:$('#statusMl').val(),
		orderAws:$('#orderAws').val(),
		buyDate:$('#buyDate').val(),
		trackingNumber:$('#trackingNumber').val(),
		trackingStatus:$('#trackingStatus').val(),
		arrivalDate:$('#arrivalDate').val(),
		awsAccount:$('#awsAccount').val(),
		comentary:$('#comentary').val(),
		user_id: sessionStorage.getItem('id')
	}).done(function(e){
		var response = JSON.parse(e);
		if(response.response == 1){
			alert("Actualizado con exito!");
		}else{
			alert("Error al cargar la información");
		}
		$("#price_tool_modal").hide();
	});
}

function close_item_detail(){
	$("#price_tool_modal").hide();
}
//******* END ORDERS FUNCTIONS MANAGER

//+++++++++++++++++++++++++++++++++++ END WORKFLOW MANAGER FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
/*
+
+
*/
//+++++++++++++++++++++++++++++++++++ START FUNCTIONS MELI ITEMS ++++++++++++++++++++++++++++++++++++++++++++
function get_meli_item_delete(id,mpid,application) {
	$.post('http://localhost/CBT_core/core/routing/meliDelete.php',{
		id : id,
		mpid : mpid,
		application: application
	}).fail(function(){

	}).done(function(e){
		var result = e;
		if(result.status == 1){
			alert('Eliminado con éxito!');
			location.reload();
		}else {
			alert('Ha ocurrido un error al eliminar la publicación!');
		}
	});
}
function get_meli_item_post(id,mpid,title, price,application) {
	console.log(id+","+mpid+","+title+","+ price+","+application);
	/*
	$.post('http://localhost/CBT_core/core/routing/meliPush.php',{
		id : id,
		mpid : mpid,
		application: application,
		title: title,
		price:price
	}).fail(function(){

	}).done(function(e){
		var result = e;
		if(result.status == 1){
			alert('Publicado con éxito!');
			//location.reload();
		}else {
			alert('Ha ocurrido un error al eliminar la publicación!');
		}
	});*/
}
/*
function get_meli_item_detail(type,mlid,application,id) {	
	$('.cbt_modal').text('');
	$('.cbt_modal').attr('src',' ');
	$(".item_detail_modal").modal("show");		
	$.post('http://localhost/CBT_core/core/routing/meliItemDetail.php',{
		mlid : mlid,
		application: application
	}).fail(function(){

	}).done(function(e){
		var response = e;
		$('#loading_gif').hide();
		$('#detail_modal').show();
		if(response['status'] == 0){
			alert('Consultas no permitidas a la plataforma');
		}else{
			if(type == 'view'){
				$('#item_detail_modal_title').text(response['title']);
				$('#item_detail_modal_image').attr('src',response['pictures'][0]['url']);
				$('#item_detail_modal_SKU').text(response['seller_id']);
				$('#item_detail_modal_sale_price').text(response['price']);
				$('#item_detail_modal_package_weight').text(response['sold_quantity']);
				$('#item_detail_modal_quantity').text(response['available_quantity']);
				$('#item_detail_modal_status').text(response['status']);
			}
			if (type == 'update') {
				$('#update_btn').remove();
				$('#item_detail_modal_title').text(response['title']).attr('contenteditable',true);
				$('#item_detail_modal_image').attr('src',response['pictures'][0]['url']);
				$('#item_detail_modal_SKU').text(response['seller_id']).attr('contenteditable',true);
				$('#item_detail_modal_sale_price').text(response['price']).attr('contenteditable',true);
				$('#item_detail_modal_package_weight').text(response['sold_quantity']);
				$('#item_detail_modal_quantity').text(response['available_quantity']).attr('contenteditable',true);
				$('#item_detail_modal_status').text(response['status']).attr('contenteditable',true);
				$('.close_modal').after('<button type="button" class="btn btn-primary" id="update_btn" onclick="update_meli_item_detail(\''+id+'\',\''+response['id']+'\', \''+application+'\')">Save changes</button>');				
			}
		}		
	});
}
*/
function update_meli_item_detail(id,item_mpid, application){
	console.log('updating item detail...');
	$.post('http://localhost/CBT_core/core/routing/meliItemUpdate.php',{
		application : application,
		id : id,
		mlid : item_mpid,
		SKU : $('#item_detail_modal_SKU').text(),
		product_title_english : $('#item_detail_modal_title').text(),
		sale_price : $('#item_detail_modal_sale_price').text(),
		quantity : $('#item_detail_modal_quantity').text(),
	}).done(function(e){
		if(e.status == 1){
			alert('Actualizado con éxito!');
			location.reload();
		}else {
			location.reload();
			alert('Ha ocurrido un error al actualizar la información!');
		}
	});
}
//+++++++++++++++++++++++++++++++++++ END FUNCTIONS MELI ITEMS ++++++++++++++++++++++++++++++++++++++++++++
/*
+
+
*/
