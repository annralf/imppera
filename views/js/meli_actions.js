
//+++++++++++++++++++++++++++++++++++ START RAFAEL FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++

function mayus(e) {
    e.value = e.value.toUpperCase();
    $('#'+e.id).css({ "box-shadow": "0 0 0px #ccc","border":"1px solid #ccc"});
}

function combo_p() {
	$.post('https://core.enkargo.com.co/services/meli_manager.php',
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
	$.post('https://core.enkargo.com.co/services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var rows_color = "";
		var rows_talla = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category"+pre+"\" name=\"category"+pre+"\" value=\""+valor+"\">";
        	$('#cmbo_1'+pre).append(rows);
			document.getElementById('boton'+pre).disabled = false;
			if(response.attribute=="variations"){
				$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
			}
		}else{
			rows += "<select class=\"form-control\" name=\"combo_1\" id=\"combo_1\" onchange=\"combo_h2(this.value,'"+shop_id+"')\">";
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
	$.post('https://core.enkargo.com.co/services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var rows_color = "";
		var rows_talla = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category"+pre+"\" name=\"category"+pre+"\" value=\""+valor+"\">";
        	$('#cmbo_2'+pre).append(rows);
			document.getElementById('boton'+pre).disabled = false;
			if(response.attribute=="variations"){
				
				$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
			}else{
				rows_color += "<input type=\"hidden\" id=\"combo_color"+pre+"\" name=\"combo_color"+pre+"\" value=\"\">";
        		rows_talla += "<input type=\"hidden\" id=\"combo_talla"+pre+"\" name=\"combo_talla"+pre+"\" value=\"\">";
        		$('#cmbo_color'+pre).append(rows_color);
        		$('#cmbo_talla'+pre).append(rows_talla);

			}
		}else{
			rows += "<select class=\"form-control\" name=\"combo_2\" id=\"combo_2\" onchange=\"combo_h3(this.value,'"+shop_id+"')\">";
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
	$.post('https://core.enkargo.com.co/services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var rows_color = "";
		var rows_talla = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category"+pre+"\" name=\"category"+pre+"\" value=\""+valor+"\">";
        	$('#cmbo_3'+pre).append(rows);
			document.getElementById('boton'+pre).disabled = false;
			if(response.attribute=="variations"){
				
				$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
			}else{
				rows_color += "<input type=\"hidden\" id=\"combo_color"+pre+"\" name=\"combo_color"+pre+"\" value=\"\">";
        		rows_talla += "<input type=\"hidden\" id=\"combo_talla"+pre+"\" name=\"combo_talla"+pre+"\" value=\"\">";
        		$('#cmbo_color'+pre).append(rows_color);
        		$('#cmbo_talla'+pre).append(rows_talla);

			}
		}else{
			rows += "<select class=\"form-control\" name=\"combo_3\" id=\"combo_3\" onchange=\"combo_h4(this.value,'"+shop_id+"')\">";
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
	$.post('https://core.enkargo.com.co/services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var rows_color = "";
		var rows_talla = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category"+pre+"\" name=\"category"+pre+"\" value=\""+valor+"\">";
        	$('#cmbo_4'+pre).append(rows);
			document.getElementById('boton'+pre).disabled = false;
			if(response.attribute=="variations"){
				
				$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
			}else{
				rows_color += "<input type=\"hidden\" id=\"combo_color"+pre+"\" name=\"combo_color"+pre+"\" value=\"\">";
        		rows_talla += "<input type=\"hidden\" id=\"combo_talla"+pre+"\" name=\"combo_talla"+pre+"\" value=\"\">";
        		$('#cmbo_color'+pre).append(rows_color);
        		$('#cmbo_talla'+pre).append(rows_talla);

			}
		}else{
			rows += "<select class=\"form-control\" name=\"combo_4\" id=\"combo_4\" onchange=\"combo_h5(this.value,'"+shop_id+"')\">";
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
	$.post('https://core.enkargo.com.co/services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var rows_color = "";
		var rows_talla = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category"+pre+"\" name=\"category"+pre+"\" value=\""+valor+"\">";
        	$('#cmbo_5'+pre).append(rows);
			document.getElementById('boton'+pre).disabled = false;
			if(response.attribute=="variations"){
				
				$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
			}else{
				rows_color += "<input type=\"hidden\" id=\"combo_color"+pre+"\" name=\"combo_color"+pre+"\" value=\"\">";
        		rows_talla += "<input type=\"hidden\" id=\"combo_talla"+pre+"\" name=\"combo_talla"+pre+"\" value=\"\">";
        		$('#cmbo_color'+pre).append(rows_color);
        		$('#cmbo_talla'+pre).append(rows_talla);

			}
		}else{
			rows += "<select class=\"form-control\" name=\"combo_5\" id=\"combo_5\" onchange=\"combo_h6(this.value,'"+shop_id+"')\">";
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
	$.post('https://core.enkargo.com.co/services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var rows_color = "";
		var rows_talla = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category"+pre+"\" name=\"category"+pre+"\" value=\""+valor+"\">";
        	$('#cmbo_6'+pre).append(rows);
			$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
		}else{
			rows += "<select class=\"form-control\" name=\"combo_6\" id=\"combo_6\" onchange=\"combo_h7(this.value,'"+shop_id+"')\">";
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
	$.post('https://core.enkargo.com.co/services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var rows_color = "";
		var rows_talla = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category"+pre+"\" name=\"category"+pre+"\" value=\""+valor+"\">";
        	$('#cmbo_7'+pre).append(rows);
			document.getElementById('boton'+pre).disabled = false;
			if(response.attribute=="variations"){
				
				$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
			}else{
				rows_color += "<input type=\"hidden\" id=\"combo_color"+pre+"\" name=\"combo_color"+pre+"\" value=\"\">";
        		rows_talla += "<input type=\"hidden\" id=\"combo_talla"+pre+"\" name=\"combo_talla"+pre+"\" value=\"\">";
        		$('#cmbo_color'+pre).append(rows_color);
        		$('#cmbo_talla'+pre).append(rows_talla);

			}
		}else{
			rows += "<select class=\"form-control\" name=\"combo_7\" id=\"combo_7\" onchange=\"combo_h8(this.value,'"+shop_id+"')\">";
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
	$.post('https://core.enkargo.com.co/services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var rows_color = "";
		var rows_talla = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category"+pre+"\" name=\"category"+pre+"\" value=\""+valor+"\">";
        	$('#cmbo_8'+pre).append(rows);
				document.getElementById('boton'+pre).disabled = false;
			if(response.attribute=="variations"){
				
				$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
			}else{
				rows_color += "<input type=\"hidden\" id=\"combo_color"+pre+"\" name=\"combo_color"+pre+"\" value=\"\">";
        		rows_talla += "<input type=\"hidden\" id=\"combo_talla"+pre+"\" name=\"combo_talla"+pre+"\" value=\"\">";
        		$('#cmbo_color'+pre).append(rows_color);
        		$('#cmbo_talla'+pre).append(rows_talla);

			}
		}else{
			rows += "<select class=\"form-control\" name=\"combo_8\" id=\"combo8\" onchange=\"combo_h9(this.value,'"+shop_id+"')\">";
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
	$.post('https://core.enkargo.com.co/services/meli_manager.php',
		{ 	action  : 'combo_h', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var rows_color = "";
		var rows_talla = "";
		var response = JSON.parse(e); 
		if (response.response==0){
			rows += "<input type=\"hidden\" id=\"category"+pre+"\" name=\"category"+pre+"\" value=\""+valor+"\">";
        	$('#cmbo_9'+pre).append(rows);			
				document.getElementById('boton'+pre).disabled = false;
			if(response.attribute=="variations"){
				$(document).ready(function(){
					combo_color(valor,shop_id);
					combo_talla(valor,shop_id);
				});
			}else{
				rows_color += "<input type=\"hidden\" id=\"combo_color"+pre+"\" name=\"combo_color"+pre+"\" value=\"\">";
        		rows_talla += "<input type=\"hidden\" id=\"combo_talla"+pre+"\" name=\"combo_talla"+pre+"\" value=\"\">";
        		$('#cmbo_color'+pre).append(rows_color);
        		$('#cmbo_talla'+pre).append(rows_talla);

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
	
	$.post('https://core.enkargo.com.co/services/meli_manager.php',
		{ 	action  : 'combo_color', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var response = JSON.parse(e); 
		if (response.response==0){

		}else{
			rows += "<label >Color</label>";
			rows += "<select class=\"form-control\" name=\"combo_color"+pre+"\" id=\"combo_color"+pre+"\">";
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
	
	$.post('https://core.enkargo.com.co/services/meli_manager.php',
		{ 	action  : 'combo_talla', 
			category: valor
		}).fail(function(){ alert('error');
	}).done(function(e){
		var rows = "";
		var response = JSON.parse(e); 
		if (response.response==0){

		}else{
			rows += "<label >Talla</label>";
			rows += "<select class=\"form-control\" name=\"combo_talla"+pre+"\" id=\"combo_talla"+pre+"\">";
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
function publicar_mx(){
	$('#sku2_mx').css({ "box-shadow": "0 0 0px #ccc","border":"1px solid #ccc"});
	valor="";
	if ($('#sku2_mx').val() == 0) {
       	alert('Ingrese SKU');
      	$('#sku2_mx').focus().css({ "box-shadow": "0 0 5px rgba(255,0,0,1)","border":"1px solid rgba(255,0,0,0.8)"});
        return false;
    }else{
		$('#loading').show();
		$.post('https://core.enkargo.com.co/process/meli_create.php',
			{
				action 	: 'publicar_mx',
				sku2_mx	: $('#sku2_mx').val(),
				category_mx	: $('#category_mx').val(),
				color_mx	: $('#combo_color_mx').val(),
				talla_mx	: $('#combo_talla_mx').val(),
				shop_id : 2,
			}).fail(function(){ alert('error');$('#loading').hide();
		}).done(function(e){
				var response = JSON.parse(e);
				if (response.response == 0) {
					$('#loading').hide();
					alertify.alert("Error con Categoria", function () {});
					
				}else if (response.response == 1) {
					$('#loading').hide();
					alertify.alert("No conexcion", function () {});
					
				}else if (response.response == 2) {
					$('#loading').hide();
					alertify.alert("Error Creando", function () {});
					
				}else if (response.response == 3) {
					$('#loading').hide();
					alertify.alert("cuota de 10.000 cumplida", function () {});
					
				}else if (response.response == 4) {
					$('#loading').hide();
					alertify.alert("No se pudo crear", function () {});
					
				}else if (response.response == 5) {
					$('#loading').hide();
					alertify.alert("El producto no optimo para crear", function () {});
					
				}else if (response.response == 6) {
					$('#loading').hide();
					alertify.alert("El producto no disponible", function () {});
					
				}else if (response.response == 7) {
					$('#loading').hide();
					alertify.alert("no se inserto en base", function () {});
					
				}else if (response.response == 8) {
					$('#loading').hide();
					alertify.alert("valor nullo", function () {});
					
				}else{
					$('#loading').hide();
					alertify.alert("Creado mpid es: </b><a href=\""+response.url+"\" target=\"_blank\">"+response.id+"</a>" , function () {});
				}
			});
	}
}
function search_sku_mx(){
	$('#sku1_mx').css({ "box-shadow": "0 0 0px #ccc","border":"1px solid #ccc"});
	valor="";
	//alert($('#sku1_mx').val());
    if ($('#sku1_mx').val() == 0) {
       	alert('Ingrese SKU');
      	$('#sku1_mx').focus();
      	$('#sku1_mx').css({ "box-shadow": "0 0 5px rgba(255,0,0,1)","border":"1px solid rgba(255,0,0,0.8)"});
        return false;
    }else{
	
		$('#loading').show();
		$.post('https://core.enkargo.com.co/services/meli_manager.php',
			{
				action 	: 'search_sku_mx',
				sku1_mx	: $('#sku1_mx').val(),
				shop_id : 2,
			}).done(function(e){
				var response = JSON.parse(e);
				if (response.response == 0) {
					$('#loading').hide();
					alertify.alert("No existe Publicacion para este SKU", function () {});
					
				}else if (response.response == 1) {
					$('#loading').hide();
					alertify.alert("No existe SKU en BD", function () {});
					
				}
				else{
					$('#loading').hide();
					alertify.alert("<b>MPID encontrado </b><a href=\""+response[0].permalink+"\" target=\"_blank\">"+response[0].mpid+"</a>", function () {});
				}
			});
	}
}
function update_mpid_mx(){
	$('#mpid_mx').css({ "box-shadow": "0 0 0px #ccc","border":"1px solid #ccc"});
	//alert($('#mpid_mx').val());
	if ($('#mpid_mx').val() == 0) {
       	alert('Ingrese el MPID de publicacion MAUXI');
      	$('#mpid_mx').focus().css({ "box-shadow": "0 0 5px rgba(255,0,0,1)","border":"1px solid rgba(255,0,0,0.8)"});
        return false;
    }else{
		$('#loading').show();
		$.post('https://core.enkargo.com.co/process/meli_update_by_items.php',
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
}
function paused_mpid_mx(){
	$('#mpid_mx').css({ "box-shadow": "0 0 0px #ccc","border":"1px solid #ccc"});
	if ($('#mpid_mx').val() == 0) {
       	alert('Ingrese el MPID de publicacion MAUXI');
      	$('#mpid_mx').focus().css({ "box-shadow": "0 0 5px rgba(255,0,0,1)","border":"1px solid rgba(255,0,0,0.8)"});
        return false;
    }else{
	$('#loading').show();
	$.post('https://core.enkargo.com.co/process/meli_update_by_items.php',
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
}
function closed_mpid_mx(){
	$('#mpid_mx').css({ "box-shadow": "0 0 0px #ccc","border":"1px solid #ccc"});
	if ($('#mpid_mx').val() == 0) {
       	alert('Ingrese el MPID de publicacion MAUXI');
      	$('#mpid_mx').focus().css({ "box-shadow": "0 0 5px rgba(255,0,0,1)","border":"1px solid rgba(255,0,0,0.8)"});
        return false;
    }else{
	$('#loading').show();
	$.post('https://core.enkargo.com.co/process/meli_update_by_items.php',
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
}
function delete_mpid_mx(){
	$('#mpid_mx').css({ "box-shadow": "0 0 0px #ccc","border":"1px solid #ccc"});
	if ($('#mpid_mx').val() == 0) {
       	alert('Ingrese el MPID de publicacion MAUXI');
      	$('#mpid_mx').focus().css({ "box-shadow": "0 0 5px rgba(255,0,0,1)","border":"1px solid rgba(255,0,0,0.8)"});
        return false;
    }else{
	$('#loading').show();
	$.post('https://core.enkargo.com.co/process/meli_update_by_items.php',
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
}
//ACTION SEARCH QUEEN BEE
function publicar_qb(){
	$('#sku2_qb').css({ "box-shadow": "0 0 0px #ccc","border":"1px solid #ccc"});
	valor="";
	if ($('#sku2_qb').val() == 0) {
       	alert('Ingrese SKU');
      	$('#sku2_qb').focus().css({ "box-shadow": "0 0 5px rgba(255,0,0,1)","border":"1px solid rgba(255,0,0,0.8)"});
        return false;
    }else{
		$('#loading').show();
		$.post('https://core.enkargo.com.co/process/meli_create.php',
			{
				action 	: 'publicar_qb',
				sku2_qb	: $('#sku2_qb').val(),
				category_qb	: $('#category_qb').val(),
				color_qb	: $('#combo_color_qb').val(),
				talla_qb	: $('#combo_talla_qb').val(),
				shop_id : 1,
			}).fail(function(){ alert('error');$('#loading').hide();
		}).done(function(e){
				var response = JSON.parse(e);
				if (response.response == 0) {
					$('#loading').hide();
					alertify.alert("Error con Categoria", function () {});
					
				}else if (response.response == 1) {
					$('#loading').hide();
					alertify.alert("No conexcion", function () {});
					
				}else if (response.response == 2) {
					$('#loading').hide();
					alertify.alert("Error Creando", function () {});
					
				}else if (response.response == 3) {
					$('#loading').hide();
					alertify.alert("cuota de 10.000 cumplida", function () {});
					
				}else if (response.response == 4) {
					$('#loading').hide();
					alertify.alert("No se pudo crear", function () {});
					
				}else if (response.response == 5) {
					$('#loading').hide();
					alertify.alert("El producto no optimo para crear", function () {});
					
				}else if (response.response == 6) {
					$('#loading').hide();
					alertify.alert("El producto no disponible", function () {});
					
				}else if (response.response == 7) {
					$('#loading').hide();
					alertify.alert("no se inserto en base", function () {});
					
				}else if (response.response == 8) {
					$('#loading').hide();
					alertify.alert("valor nullo", function () {});
					
				}else{
					$('#loading').hide();
					alertify.alert("Creado mpid es: </b><a href=\""+response.url+"\" target=\"_blank\">"+response.id+"</a>"  , function () {});
				}
			});
	}
}
function search_sku_qb(){
	$('#sku1_qb').css({ "box-shadow": "0 0 0px #ccc","border":"1px solid #ccc"});
	valor="";
	//alert($('#sku1_mx').val());
    if ($('#sku1_qb').val() == 0) {
       	alert('Ingrese SKU');
      	$('#sku1_qb').focus().css({ "box-shadow": "0 0 5px rgba(255,0,0,1)","border":"1px solid rgba(255,0,0,0.8)"});
        return false;
    }else{
	
		$('#loading').show();
		$.post('https://core.enkargo.com.co/services/meli_manager.php',
			{
				action 	: 'search_sku_qb',
				sku1_qb	: $('#sku1_qb').val(),
				shop_id : 1,
			}).done(function(e){
				var response = JSON.parse(e);
				if (response.response == 0) {
					$('#loading').hide();
					alertify.alert("No existe Publicacion para este SKU", function () {});
					
				}else{
					$('#loading').hide();
					alertify.alert("<b>MPID encontrado </b><a href=\""+response[0].permalink+"\" target=\"_blank\">"+response[0].mpid+"</a>", function () {});
				}
			});
	}
}
function update_mpid_qb(){
	$('#mpid_qb').css({ "box-shadow": "0 0 0px #ccc","border":"1px solid #ccc"});
	//alert($('#mpid_qb').val());
	if ($('#mpid_qb').val() == 0) {
       	alert('Ingrese el MPID de publicacion QUEEN BEE');
      	$('#mpid_qb').focus().css({ "box-shadow": "0 0 5px rgba(255,0,0,1)","border":"1px solid rgba(255,0,0,0.8)"});
        return false;
    }else{
		$('#loading').show();
		$.post('https://core.enkargo.com.co/process/meli_update_by_items.php',
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
}
function paused_mpid_qb(){
	$('#mpid_qb').css({ "box-shadow": "0 0 0px #ccc","border":"1px solid #ccc"});
	if ($('#mpid_qb').val() == 0) {
       	alert('Ingrese el MPID de publicacion QUEEN BEE');
      	$('#mpid_qb').focus().css({ "box-shadow": "0 0 5px rgba(255,0,0,1)","border":"1px solid rgba(255,0,0,0.8)"});
        return false;
    }else{
	$('#loading').show();
	$.post('https://core.enkargo.com.co/process/meli_update_by_items.php',
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
}
function closed_mpid_qb(){
	$('#mpid_qb').css({ "box-shadow": "0 0 0px #ccc","border":"1px solid #ccc"});
	if ($('#mpid_qb').val() == 0) {
       	alert('Ingrese el MPID de publicacion QUEEN BEE');
      	$('#mpid_qb').focus().css({ "box-shadow": "0 0 5px rgba(255,0,0,1)","border":"1px solid rgba(255,0,0,0.8)"});
        return false;
    }else{
	$('#loading').show();
	$.post('https://core.enkargo.com.co/process/meli_update_by_items.php',
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
}
function delete_mpid_qb(){
	$('#mpid_qb').css({ "box-shadow": "0 0 0px #ccc","border":"1px solid #ccc"});
	if ($('#mpid_qb').val() == 0) {
       	alert('Ingrese el MPID de publicacion QUEEN BEE');
      	$('#mpid_qb').focus().css({ "box-shadow": "0 0 5px rgba(255,0,0,1)","border":"1px solid rgba(255,0,0,0.8)"});
        return false;
    }else{
	$('#loading').show();
	$.post('https://core.enkargo.com.co/process/meli_update_by_items.php',
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
}

//+++++++++++++++++++++++++++++++++++ END RAFAEL FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++
