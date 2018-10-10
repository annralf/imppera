
//+++++++++++++++++++++++++++++++++++ START RAFAEL FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++

function show_asig_tareas(){
	hidde_panel_tarea();
	$("#tablero_asignar").show();
	$("#tablero_today").hide();
	$("#tablero_today").hide();
	$("#tablero_week").hide();
	$("#tablero_check").hide();
	$("#tablero_no_check").hide();
	$("#tablero_panel").hide();
	$("#tablero_asig").hide();
	$("#tablero_cumpli").hide();
	$("#tablero_archivo").hide();
}

function show_today(){
	$("#tablero_asignar").hide();
	$("#tablero_today").show();
	$("#tablero_today").show();
	$("#tablero_week").hide();
	$("#tablero_check").hide();
	$("#tablero_no_check").hide();
	$("#tablero_panel").hide();
	$("#tablero_asig").hide();
	$("#tablero_cumpli").hide();
	$("#tablero_archivo").hide();
	list_tarea();
	list_porcent();
}
function show_week(){
	$("#tablero_asignar").hide();
	$("#tablero_today").hide();
	$("#tablero_today").hide();
	$("#tablero_week").show();
	$("#tablero_check").hide();
	$("#tablero_no_check").hide();
	$("#tablero_panel").hide();
	$("#tablero_asig").hide();
	$("#tablero_cumpli").hide();
	$("#tablero_archivo").hide();
	list_tarea();
	list_porcent();
}
function show_check(){
	$("#tablero_asignar").hide();
	$("#tablero_today").hide();
	$("#tablero_today").hide();
	$("#tablero_week").hide();
	$("#tablero_check").show();
	$("#tablero_no_check").hide();
	$("#tablero_panel").hide();
	$("#tablero_asig").hide();
	$("#tablero_cumpli").hide();
	$("#tablero_archivo").hide();
	list_tarea_term();
}
function show_cumpl(){
	$("#tablero_asignar").hide();
	$("#tablero_today").hide();
	$("#tablero_today").hide();
	$("#tablero_week").hide();
	$("#tablero_cumpli").show();
	$("#tablero_check").hide();
	$("#tablero_no_check").hide();
	$("#tablero_panel").hide();
	$("#tablero_asig").hide();
	$("#tablero_archivo").hide();
	list_tarea_cumplidas();
}
function show_asig(){
	$("#tablero_asignar").hide();
	$("#tablero_today").hide();
	$("#tablero_today").hide();
	$("#tablero_week").hide();
	$("#tablero_asig").show();
	$("#tablero_check").hide();
	$("#tablero_no_check").hide();
	$("#tablero_panel").hide();
	$("#tablero_cumpli").hide();
	$("#tablero_archivo").hide();
	list_tarea_asig();
}
function show_no_check(){
	$("#tablero_asignar").hide();
	$("#tablero_today").hide();
	$("#tablero_today").hide();
	$("#tablero_week").hide();
	$("#tablero_check").hide();
	$("#tablero_no_check").show();
	$("#tablero_panel").hide();
	$("#tablero_asig").hide();
	$("#tablero_cumpli").hide();
	$("#tablero_archivo").hide();
	list_tarea_no_cumplidas();
}
function show_panel(){
	$("#tablero_asignar").hide();
	$("#tablero_today").hide();
	$("#tablero_today").hide();
	$("#tablero_week").hide();
	$("#tablero_check").hide();
	$("#tablero_no_check").hide();
	$("#tablero_panel").show();
	$("#tablero_asig").hide();
	$("#tablero_cumpli").hide();
	$("#tablero_archivo").hide();
}
function show_archivo(){
	$("#tablero_asignar").hide();
	$("#tablero_today").hide();
	$("#tablero_today").hide();
	$("#tablero_week").hide();
	$("#tablero_check").hide();
	$("#tablero_no_check").hide();
	$("#tablero_panel").hide();
	$("#tablero_asig").hide();
	$("#tablero_cumpli").hide();
	$("#tablero_archivo").show();
	armar_archivo();
}
function tarea_file(id_t){
	var e = new FormData();
	console.log($('input:file')[0].files[0]);
	e.append('action','loadFile_t');
	e.append('file',$('input:file')[0].files[0]);  
	e.append('user_id',sessionStorage.getItem('id'));
	e.append('id_t',$(id_t).attr('id_t'));
	$(".loading").show();
	$.ajax({
		url : '../services/meli_manager.php',
		data: e,
		cache: false,
		contentType: false,
		processData: false,
		type: 'POST'
	}).done(function (e) {
		$(".loading").hide();
		var response = JSON.parse(e);
		if (response.response == "1") {          
			alert('Cargado con éxito!');
			list_tarea();
		}else{
			alert('Ha ocurrido un error al procesar la información');
		}
	});
}
function archivo(evt) {
	  var files = evt.target.files; // FileList object

	  // Obtenemos la imagen del campo "file".
	  for (var i = 0, f; f = files[i]; i++) {
	    //Solo admitimos imágenes.
	    if (!f.type.match('image.*')) {
	        continue;
	    }

	    var reader = new FileReader();

	    reader.onload = (function(theFile) {
	        return function(e) {
	          // Insertamos la imagen
	         document.getElementById("list").innerHTML = ['<img class="thumb" src="', e.target.result,'" title="', escape(theFile.name), '"/>'].join('');
	        };
	    })(f);

	    reader.readAsDataURL(f);
	  }
}

function ver_comentario(id_t) {	
	var id_t=id_t;
	var row="";

	$.post('../services/meli_manager.php',{
		action 	: 'list_tarea_by_id',
		id_t 	: id_t,
	}).done(function(e){
		var response = JSON.parse(e);
		if (response.response == 0) {
			alert("Ha ocurrido un error al procesar la información");
		}else{
			row="<textarea class='form-control' rows='5' id='comment_local'>"+response[0].comentary+"</textarea>"
			$('#comentaries').empty();
			$('#comentaries').append(row);
			$(".item_comentary_modal").modal("show");
			$(".save_comment").attr('id_t',id_t);	
		}
	});
}

function ver_descrip(id_t) {	
	var id_t=id_t;
	$.post('../services/meli_manager.php',{
		action 	: 'list_tarea_by_id',
		id_t 	: id_t,
	}).done(function(e){
		var response = JSON.parse(e);
		if (response.response == 0) {
			alert("Ha ocurrido un error al procesar la información");
		}else{
			$('#asig_by').empty();
			$('#expire').empty();
			$('#asig_by').append("Asignado por: "+response[0].nombre);
			$('#expire').append("Fecha expiracion: "+response[0].asig_date);
			$('#descrip_local').text(response[0].description);
			$(".item_descrip_modal").modal("show");
		}
	});
}

function ver_file(id_t,file) {	
	//$('#input-b1').val(file);
	var thumb = ""; 
	var extension="";																																																																
	$("#list").empty();
	if (file == "null"){
	}else{
		extension = file.split('.').pop();
		if (extension=='jpg' || extension=='jpeg' || extension=='png' || extension=='gif' ){
			thumb="<img class='thumb' src='"+file+"'' title='Vista_previa'/>";
		}else{
			thumb="<a href='"+file+"' title='Vista_previa'>"+file+"</a>";
		}
		$("#list").append(thumb);
	}
	$(".item_file_modal").modal("show");
	$(".save_file").attr('id_t',id_t);	
}

function ver_file_preview(id_t,file) {	
	//$('#input-b1').val(file);
	var thumb = ""; 
	var extension="";
	$("#list2").empty();
	if (file == "null"){
	}else{
		extension = file.split('.').pop();
		if (extension=='jpg' || extension=='jpeg' || extension=='png' || extension=='gif' ){
			thumb="<img class='thumb' src='"+file+"'' title='Vista_previa'/>";
		}else{
			thumb="<a href='"+file+"' title='Vista_previa'>"+file+"</a>";
		}

		$("#list2").append(thumb);
	}
	$(".item_file_preview").modal("show");
}
function update_comment(id_t){
	$.post('../services/meli_manager.php',{
		action : 'update_comment_t',
		id_t : $(id_t).attr('id_t'),
		comment : $('#comment_local').val(),
		user_id: sessionStorage.getItem('id')
	}).done(function(e){
		var response = JSON.parse(e);
		if (response.response == 1) {
			alert("Actualizado con éxito");
			///$('#comment_local').text();
			list_tarea();
		}else{
			alert("Ha ocurrido un error al procesar la información");
		}
		$(".item_comentary_modal").modal("hide");

	});
}
function check_tarea(){
	$.post('../services/meli_manager.php',{
		action : 'check_tarea',
		user_id: sessionStorage.getItem('id')
	}).done(function(e){
		var response = JSON.parse(e);
		if (response.response == 1) {
		}else{
			alert("Ha ocurrido un error actualizando");
		}
	});
}
function end_tarea(id_t){
	var isGood=confirm("Desea Culminar la tarea?");
	if(isGood){
		$.post('../services/meli_manager.php',{
			action : 'end_tarea',
			id_t   : id_t,
			user_id: sessionStorage.getItem('id')
		}).done(function(e){
			var response = JSON.parse(e);
			if (response.response == 1) {
				alert("Tarea Cumplida");
				list_tarea();
				list_porcent();
			}else{
				alert("Ha ocurrido un error al procesar la información");
			}
		});
	}else{
		alert("Tarea no Confirmada")
	}
}

function good_tarea(id_t){
	var isGood=confirm("Desea aprobar la tarea ?");
	if(isGood){
		$.post('../services/meli_manager.php',{
			action : 'good_tarea',
			
			id_t   : id_t,
			user_id: sessionStorage.getItem('id')
		}).done(function(e){
			var response = JSON.parse(e);
			if (response.response == 1) {
				alert("Tarea aprobada");
				list_tarea_cumplidas();
				list_tarea_no_cumplidas();
			}else{
				alert("Ha ocurrido un error al procesar la información");
			}
		});
	}else{
		alert("Tarea no Confirmada")
	}
}

function update_fecha(id_t,fecha,type){
	var isGood=confirm("Desea reprogramar la tarea ?");
	if(isGood){
		$.post('../services/meli_manager.php',{
			action : 'update_fecha',
			fecha  : fecha,
			type   : type,
			id_t   : id_t,
			user_id: sessionStorage.getItem('id')
		}).done(function(e){
			var response = JSON.parse(e);
			if (response.response == 1) {
				alert("Tarea reprogramada");
				list_tarea_asig();
				list_tarea_term();
				list_tarea_cumplidas();
				list_tarea_no_cumplidas();
			}else{
				alert("Ha ocurrido un error al procesar la información");
			}
		});
	}else{
		alert("Tarea no programada")
	}
}

function archivar_tarea(id_t){
	
	$.post('../services/meli_manager.php',{
		action : 'archi_tarea',
		id_t   : id_t,
		user_id: sessionStorage.getItem('id')
	}).done(function(e){
		var response = JSON.parse(e);
		if (response.response == 1) {
			alert("Tarea archivada");
			list_tarea_cumplidas();
			list_tarea_no_cumplidas();
		}else{
			alert("Ha ocurrido un error al procesar la información");
		}
	});
}

function bad_tarea(id_t){
	var isGood=confirm("Desea reprobar la tarea?");
	if(isGood){
		$.post('../services/meli_manager.php',{
			action : 'bad_tarea',
			id_t   : id_t,
			user_id: sessionStorage.getItem('id')
		}).done(function(e){
			var response = JSON.parse(e);
			if (response.response == 1) {
				alert("Tarea reprobada");
				list_tarea_cumplidas();
				list_tarea_no_cumplidas();
			}else{
				alert("Ha ocurrido un error al procesar la información");
			}
		});
	}else{
		alert("Tarea no Confirmada")
	}
}

function update_file(id_t){


	$.post('../services/meli_manager.php',{
		action : 'update_file_t',
		id_t : $(id_t).attr('id_t'),
		comment : $('#files').val(),
		user_id: sessionStorage.getItem('id')
	}).done(function(e){
		var response = JSON.parse(e);
		if (response.response == 1) {
			alert("Actualizado con éxito");
			$('#comment_local').val();
			list_tarea();
		}else{
			alert("Ha ocurrido un error al procesar la información");
		}
		$(".item_file_modal").modal("hide");

	});
}

function uppriority(id_t,porc){
	$.post('../services/meli_manager.php',{
		action : 'add_avance',
		id_t : id_t,
		valor : porc,
	}).done(function(e){
		var response = JSON.parse(e);
		if (response.response == 1) {
			list_tarea();
		}else{
			alert("Ha ocurrido un error al procesar la información");
		}
	});

}
//######################tareas#######################

function show_panel_proyecto(){
	$('#form_proyect').show();
}

function hidde_panel_proyecto(){
	$('#form_proyect').hide();
}

function show_panel_tarea(){
	$('#form_tarea').show();
	$('#job').hide();	
}

function hidde_panel_tarea(){
	$('#name_user').empty();
	$('#icon_priority').css('color','');
	$('#new_tarea').val('');
	$('#priority_asig').val('');
	$('#user_asig').val('');
	$('#color_asig').val('');
	$('#form_tarea').hide();
}

function show_panel_tarea_p(){
	$('#form_tarea_p').show();
}

function hidde_panel_tarea_p(){
	$('#form_tarea_p').hide();
}


function add_proyect(){
	$.post('../services/meli_manager.php',
		{ 	action  : 'add_proyect', 
			name  	: $('#name_proyect').val(),
			color 	: $('#color_proyect').val(),
			user  	: sessionStorage.getItem('id'),
		}).fail(function(){ alert('error add proyect');
	}).done(function(e){
		var response = JSON.parse(e); 
		if (response.response==1){
			list_proyect();
		}else{
			alert('proyecto no creado');
        }
	});
}


function eliminar_tarea(id_t){
	var isGood=confirm("Desea Eliminar la tarea ?");
	if(isGood){
		$.post('../services/meli_manager.php',
			{ 	action  : 'delete_tarea', 
				id_t  	: id_t,
			}).fail(function(){ alert('error delete tarea');
		}).done(function(e){
			var response = JSON.parse(e); 
			if (response.response==1){
				list_tarea();
				list_tarea_no_cumplidas();
				list_tarea_cumplidas();
				list_tarea_term();
				list_tarea_asig();
			}else{
				alert('no se pudo eliminar la tarea');
	        }
		});
	}else{
		alert("Tarea no Eliminada")
	}
}

function add_tarea(){
	var bol="false";
	$('.ads_Checkbox:checked').each(function(){ 
		bol="true";
	});
	if($('#new_tarea').val() == ""){
		alert("El campo tarea no puede estar vacio");
	}else if(bol == "false"){
		alert("Debe asignar un usuario");
	}else if($('#priority_asig').val() == ""){
		alert("Debe asignar una prioridad");
	}else{	
		$('.ads_Checkbox:checked').each(function(){        
	        var values = $(this).val();
			$.post('../services/meli_manager.php',
				{ 	action  	: 'add_tarea', 
					fecha 		: $('#fecha').val(),
					name  		: $('#new_tarea').val(),
					description : $('#des_tarea').val(),
					priority 	: $('#priority_asig').val(),
					user  		: sessionStorage.getItem('id'),
					user_asig	: values,
					proyect_asig: $('#proyect_asig').val(),
					color_asig	: $('#color_asig').val(),
				}).fail(function(){ alert('error add tarea');
			}).done(function(e){
				var response = JSON.parse(e); 
				if (response.response==1){
					
				}else{
					alert('Tarea no creada');
		        }
			});
		});

		$('#content_v').show();
		$('#content_a').show();
		$('#content_r').show();
		$('#new_tarea').val('');
		$('#des_tarea').val('');
		$('#fecha').val('');
		$('#name_user').empty();
		$('#form_tarea').hide();
		list_user_tarea();
		list_tarea();
		list_tarea_asig();
	}
}



function list_proyect(){
	$.post('../services/meli_manager.php',
		{ 	action  : 'list_proyect', 
			user  	: sessionStorage.getItem('id'),
		}).fail(function(){ alert('error list proyect');
	}).done(function(e){
		var rows="";
		var response = JSON.parse(e); 
		if (response.response == 0){
			rows += "<p>No hay proyectos</p>";
        	$('#list_proyect').append(rows);
		}else{

			rows += "<table width='100%'>";
	        for(var i in response){
	        	rows += "<tr width='100%'>";
	        	rows += "<td width='90%'><i class='fa fa-circle' style='font-size:15px;padding-right: 10px;color:"+response[i].color+"'></i><span style='cursor:pointer;' onclick='tareas_proyecto(\""+response[i].id+"\",\""+response[i].name+"\")'>"+response[i].name+"</span></td>";
	            rows += "<td width='10%'> <div class='dropdown'> <a class='fa fa-ellipsis-h dropdown-toggle' data-toggle='dropdown'></a>  <ul class='dropdown-menu pull-right' role='menu' aria-labelledby='dLabel'>";
	            rows += "<li><i class='fa fa-trash' ><a style='font-size:15px;padding-right: 10px;margin-left:10px'>Elimina</a></i></li>";
	            rows += "<li><i class='fa fa-folder-open-o' style=''><a style='font-size:15px;padding-right: 10px;margin-left:5px'>Archivar</a></i></li>";
	            rows += "</ul>  </div></td>";
	        	//rows += "<td width='30%'> <div class='dropdown'> <button class='btn dropdown-toggle sr-only' type='button' id='dropdownMenu1' data-toggle='dropdown'> Menú desplegable <span class='caret'></span> </button>						 						  <ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu1'>						    <li role='presentation'>						      <a role='menuitem' tabindex='-1' href='#'>Acción</a>						    </li>						    <li role='presentation'>						      <a role='menuitem' tabindex='-1' href='#'>Otra acción</a>						    </li>						    <li role='presentation'>						      <a role='menuitem' tabindex='-1' href='#'>Otra acción más</a>						    </li>						    <li role='presentation' class='divider'> </li> <li role='presentation'> <a role='menuitem' tabindex='-1' href='#'>Acción separada</a>	</li> </ul>	</div> </td>";
	        	rows += "</tr>"; 
	        }
	        rows += "</tr></table>";
	        $('#form_proyect').hide();
	        $('#list_proyect').empty();
        	$('#list_proyect').append(rows);
        }
	});
}


function list_porcent(){
	$.post('../services/meli_manager.php',
		{ 	action  : 'list_porcent', 
			user  	: sessionStorage.getItem('id'),
		}).fail(function(){ alert('error list proyect');
	}).done(function(e){
		var rows="";
		var color="";
		var rowsp="";
		var rowsm="";
		var rowsc="";
		var rowsr="";
		var rowss="";
		var rowsa="";
		var porcent=0;
		var total=0;
		var total_c=0;
		var recha =0;
		var response = JSON.parse(e); 
		if (response.response == 0){
			rows += "0%";
			color='#cd5832';
			$('#porcent').empty();
        	$('#porcent').append(rows);
        	$('#medal').css("background",color)
        	if(sessionStorage.getItem('id')==38){
        		$('#medal').css("margin-left","80px");
				$('#medal').css("background","#F400A1");	
        	}
		}else{
			var jefe1=0;
			var jefe2=0;
			var porcent=0;
        	var porcent1=0;
        	var porcent2=0;
			var total=0;
			var total1=0;
			var totala1=0;
			var totals1=0;
			var totalc1=0;
			var totalr1=0;
			var total2=0;
			var totala2=0;
			var totals2=0;
			var totalc2=0;
			var totalr2=0;
			var rowsm="";
			var rowsa="";
			var rowsr="";
			var rowsc="";
			var rowss="";
			var color="#E57200";
	        for(var i in response){
		       	if(response[i].jerarquia==1){
	        		jefe1=1;
	        		total1=total1+parseFloat(response[i].total);
	        		if (response[i].status=="C"){
	        			totala1 =totala1+parseFloat(response[i].total);
		        	}
		        	if (response[i].status=="T"){
		        		totals1 =totals1+parseFloat(response[i].total);
		        	}
		        	if (response[i].status=="G"){
		        		totalc1 =totalc1+parseFloat(response[i].total);
		        	}
		        	if (response[i].status=="B"){
		        		totalr1=totalr1+parseFloat(response[i].total);
		        	}
		        	if (response[i].status=="NT"){
		        		totalr1=totalr1+parseFloat(response[i].total);
		        	}
	        	}
	        	if(response[i].jerarquia==2){
	        		jefe2=1;
	        		total2=total2+parseFloat(response[i].total);	
	        		if (response[i].status=="C"){
	        			totala2 =totala2+parseFloat(response[i].total);
		        	}
		        	if (response[i].status=="T"){
		        		totals2 =totals2+parseFloat(response[i].total);
		        	}
		        	if (response[i].status=="G"){
		        		totalc2 =totalc2+parseFloat(response[i].total);
		        	}
		        	if (response[i].status=="B"){
		        		totalr2=totalr2+parseFloat(response[i].total);
		        	}
		        	if (response[i].status=="NT"){
		        		totalr2=totalr2+parseFloat(response[i].total);
		        	}
	        	}
        	}
        	total = parseFloat(total1+total2);
        	rowsm ="tareas en el mes: "+parseFloat(total);
    		rowsa ="Asignadas: "+parseFloat(totala1+totala2);
    		rowsc ="Cumplidas: "+parseFloat(totalc1+totalc2);
    		rowsr ="Rechazadas: "+parseFloat(totalr1+totalr2);
    		rowss ="Sin calificar: "+parseFloat(totals1+totals2);
	        if(total==0){
	        	rowsp ="0%";
	        	porcent=0;
	        }else{
	        	if(jefe1==0 && jefe2==1){
	        		porcent = (totalc2 / total )*100;
	        		rowsp =Math.round(porcent)+"%";
	        	}else 
	        	if(jefe1==1 && jefe2==0){
	        		porcent = (totalc1 / total )*100;
	        		rowsp =Math.round(porcent)+"%";
	        	}else 
	        	if(jefe1==1 && jefe2==1){
	        		porcent1 = (totalc1 / total1 )*70;
	        		porcent2 = (totalc2 / total2 )*30;
	        		porcent=porcent1+porcent2;
	        		rowsp =Math.round(porcent)+"%";
	        	}
	    	}

	    	if(porcent>=85){
	    		color='#b9b8b5';
	    	}
	    	if(porcent<85){
	    		color='#FFD700 ';
	    	}
	    	if(porcent<75){
	    		color='#cd5832';
	    	}
		    $('#medal').css("background",color)
	        $('#porcent').empty();
	        $('#porcent_m').empty();
	        $('#porcent_a').empty();
	        $('#porcent_c').empty();
	        $('#porcent_r').empty();
        	$('#porcent_s').empty();
        	$('#porcent').append(rowsp);
	        $('#porcent_m').append(rowsm);
	        $('#porcent_a').append(rowsa);
	        $('#porcent_c').append(rowsc);
	        $('#porcent_r').append(rowsr);
        	$('#porcent_s').append(rowss);
        	chech_menu();
        }
	});
}

function list_all_porcent(mes){
	var rows="";
	$.post('../services/meli_manager.php',
		{ 	action  : 'list_all_porcent',
			user  	: sessionStorage.getItem('id'), 
			mes 	: mes,
		}).fail(function(){ alert('error all list proyect');
	}).done(function(e){
		var response = JSON.parse(e); 
		if (response.response == 0){
			rows += "0$";
        	alert(rows);
		}else{

			var jefe1=0;
			var jefe2=0;
	        for(var i in response){
	        	var porcent=0;
	        	var porcent1=0;
	        	var porcent2=0;
				var total=0;
				var total1=0;
				var totala1=0;
				var totals1=0;
				var totalc1=0;
				var totalr1=0;
				var total2=0;
				var totala2=0;
				var totals2=0;
				var totalc2=0;
				var totalr2=0;
				var rowsm="";
				var rowsa="";
				var rowsr="";
				var rowsc="";
				var rowss="";
				var color="#E57200";

	        	for(var y in response[i].tareas){
		        	if(response[i].tareas[y].jerarquia==1){
		        		jefe1=1;
		        		total1=total1+parseFloat(response[i].tareas[y].total);
		        		if (response[i].tareas[y].status=="C"){
		        			totala1 =totala1+parseFloat(response[i].tareas[y].total);
			        	}
			        	if (response[i].tareas[y].status=="T"){
			        		totals1 =totals1+parseFloat(response[i].tareas[y].total);
			        	}
			        	if (response[i].tareas[y].status=="G"){
			        		totalc1 =totalc1+parseFloat(response[i].tareas[y].total);
			        	}
			        	if (response[i].tareas[y].status=="B"){
			        		totalr1=totalr1+parseFloat(response[i].tareas[y].total);
			        	}
			        	if (response[i].tareas[y].status=="NT"){
			        		totalr1=totalr1+parseFloat(response[i].tareas[y].total);
			        	}
		        	}
		        	if(response[i].tareas[y].jerarquia==2){
		        		jefe2=1;
		        		total2=total2+parseFloat(response[i].tareas[y].total);	
		        		if (response[i].tareas[y].status=="C"){
		        			totala2 =totala2+parseFloat(response[i].tareas[y].total);
			        	}
			        	if (response[i].tareas[y].status=="T"){
			        		totals2 =totals2+parseFloat(response[i].tareas[y].total);
			        	}
			        	if (response[i].tareas[y].status=="G"){
			        		totalc2 =totalc2+parseFloat(response[i].tareas[y].total);
			        	}
			        	if (response[i].tareas[y].status=="B"){
			        		totalr2=totalr2+parseFloat(response[i].tareas[y].total);
			        	}
			        	if (response[i].tareas[y].status=="NT"){
			        		totalr2=totalr2+parseFloat(response[i].tareas[y].total);
			        	}
		        	}
	        	}

	        	total = parseFloat(total1+total2);
	        	rowsm ="tareas en el mes: "+parseFloat(total);
        		rowsa ="Asignadas: "+parseFloat(totala1+totala2);
        		rowsc ="Cumplidas: "+parseFloat(totalc1+totalc2);
        		rowsr ="Rechazadas: "+parseFloat(totalr1+totalr2);
        		rowss ="Sin calificar: "+parseFloat(totals1+totals2);
		        
		        if(total==0){
		        	rowsp ="0%";
		        	porcent=0;
		        }else{
		        	if(jefe1==0 && jefe2==1){
		        		porcent = (totalc2 / total )*100;
		        		rowsp =Math.round(porcent)+"%";
		        	}else 
		        	if(jefe1==1 && jefe2==0){
		        		porcent = (totalc1 / total )*100;
		        		rowsp =Math.round(porcent)+"%";
		        	}else 
		        	if(jefe1==1 && jefe2==1){
		        		porcent1 = (totalc1 / total1 )*70;
		        		porcent2 = (totalc2 / total2 )*30;
		        		porcent=porcent1+porcent2;
		        		rowsp =Math.round(porcent)+"%";
		        	}
		    	}

		    	if(porcent>=85){
		    		color='#b9b8b5';
		    	}
		    	if(porcent<85){
		    		color='#FFD700 ';
		    	}
		    	if(porcent<75){
		    		color='#cd5832';
		    	}

		    	rows += "<div class='ficha_u'>";
	        	rows += "<table  width='300px'>";
		        rows += " <tbody>";
		        rows += "    <tr>";
		        rows += "      <td colspan='2'><h3 style='text-align: center'>"+response[i].name+" "+response[i].last_name+"</h3></td>";
		        rows += "    </tr>";
		        rows += "    <tr>";
		        rows += "      <td height='180px' ><div style='width: 130px;height: 130px;background:"+color+" ;border-radius: 60px;margin-top: 10px' >";
		        rows += "            <img src="+response[i].avatar+"   width='110' height='110' alt='' style='margin-left: 10px;margin-top: 10px; border-radius: 60px'>";
		        rows += "          </div>";
		        rows += "      </td>";
		        rows += "      <td>";
		        rows += "        <div style='width: 100%;height: 140px;float:left;text-align: left'>";
		        rows += "          <div style='font-size: 40px'>";
		        rows += rowsp;
		        rows += "          </div>";
		        rows += "          <div>";
		        rows += rowsm;
		        rows += "          </div>";
		        rows += "          <div >";
		        rows += rowsa;
		        rows += "          </div>";
		        rows += "          <div >";
		        rows += rowsc;
		        rows += "          </div>";
		        rows += "          <div >";
		        rows += rowsr;
		        rows += "          </div>";
		        rows += "          <div >";
		        rows += rowss;
		        rows += "          </div>";
		        rows += "        </div>";
		        rows += "      </td>";
		        rows += "    </tr>";
		        rows += "  </tbody>";
		        rows += "</table>"; 
		        rows += "</div>"; 
		        var jefe1=0;
				var jefe2=0;
		    }
		    $('#panel_user').empty();
		    $('#panel_user').append(rows);
        }
	});
}

function chech_menu(){
	$.post('../services/meli_manager.php',
		{ 	action  : 'chech_menu', 
			user  	: sessionStorage.getItem('id'),
		}).fail(function(){ alert('error list proyect');
	}).done(function(e){
		var response = JSON.parse(e); 
		if (response.response == 0){
			rows += "0$";
        	$('#porcent').append(rows);
		}else{
			if (response[0].jerarquia==1){
				$('#medal').css("margin-left","80px");
				$('#medal').css("background","#F400A1");
		        $('#porcent').hide();
		        $('#porcent_m').hide();
		        $('#porcent_a').hide();
		        $('#porcent_c').hide();
		        $('#porcent_r').hide();
	        	$('#porcent_s').hide();
	        	$('#menu_today').hide();
	        	$('#menu_week').hide();
			}
			if (response[0].jerarquia==3){
				$('#menu_asig').css('visibility','hidden');
				$('#menu_tareaok').hide();
				$('#menu_tareanook').hide();
				$('#menu_tareacump').hide();
	        	$('#menu_tareaasig').hide();
	        	$('#menu_archivo').hide();
			}	       
        }
	});
}


function list_tarea(){
	$.post('../services/meli_manager.php',
		{ 	action  : 'list_tarea', 
			user  	: sessionStorage.getItem('id'),
		}).fail(function(){ alert('error list tarea');
	}).done(function(e){
		var rows = "";
		var rowsv = "";
		var rowsr = "";
		var rowsa = "";
		var tokenv = 0;
		var tokenr = 0;
		var tokena = 0;
		var color;
		var response = JSON.parse(e);
		if (response.response == 0){
			rows += "<p>No hay Tareas</p>";
        	$('#info_t').empty();
        	$('#info_t').append(rows);
        	$('#job').show()
        	$('#info_t1').empty();
        	$('#info_t1').append(rows);
        	$('#job1').show();
        	$('#count_r').empty();
			$('#count_m').empty();
			$('#count_r').append('0');
			$('#count_m').append('0');
			$('#bell1').hide();
		}else{
			$('#bell').hide();
 			$('#info_t').empty();
 			$('#job').hide();
 			$('#job1').hide();
 			var count_a=0;
 			var count_r=0;

 			rowsv += "<tr ><td>Tareas del mes</td><td>Acciones</td></tr>";
 			rowsa += "<tr ><td>Tareas de la semana</td><td>Acciones</td></tr>";
 			rowsr += "<tr ><td>Tareas para hoy</td><td>Acciones</td></tr>";
			for(var i in response){
				var colorc=""
				var colorf="";
				var thumb="";
				var conteor="";
				var conteov="";

				if (response[i].comentary) {
						colorc="blue";
				}else{
					colorc="#292929"
				}
				if (response[i].file) {
					colorf="blue";
				}else{
					colorf="#292929";
				}

				if(response[i].priority== 3 ){
					tokenr=1;
					count_r=count_r+1;
					rowsr += "<tr style='background-color:"+response[i].color+";'>";
					rowsr += "<td style='width: 85%; '><i class='fa fa-navicon' style='font-size:18px;color:#292929' onclick='ver_descrip(\""+response[i].id+"\")'></i><span style='color:#292929;margin-left:10px;font-weight: bold;font-size:15px'>"+response[i].tarea+"</span><span style='float:right'>"+response[i].avance+"%</span></td>";
					rowsr += "<td style='width: 15%; '>";
						rowsr += "<div class='dropdown'> <a class='btn' title='Ver comentario' onclick='ver_comentario(\""+response[i].id+"\")'>	<i class='fa fa-comments-o' 	style='font-size:15px;color:"+colorc+";'></i></a>";	
					 	rowsr += "<a class='btn' title='Adjuntar Archivo' onclick='ver_file(\""+response[i].id+"\",\""+response[i].file+"\")'>			<i class='fa fa-folder-open-o' 	style='font-size:15px;color:"+colorf+";'></i></a>";
					 	rowsr += "<a class='btn' title='Terminar Tarea' onclick='end_tarea(\""+response[i].id+"\")'>									<i class='fa fa-check-square-o' style='font-size:15px;color:#292929;'></i></a>";
					 	rowsr += "<a class='dropdown-toggle btn' data-toggle='dropdown' title='Porcentaje de avance' ><i class='fa fa-line-chart' style='font-size:15px;color:#292929;'></i></a>  ";
                        rowsr += "    <ul class='dropdown-menu pull-right' role='menu' aria-labelledby='dLabel' style='width: 70px;'>";
                        rowsr += "      <li style='font-size: 20px;background: #fff'>";
                        rowsr += "      <input type='range' min='0' max='100' step='0' onchange='uppriority(\""+response[i].id+"\",this.value)'>";
                        rowsr += "      </li>";
                        rowsr += "    </ul></div> ";
					rowsr += "</td>";	
					rowsr += "</tr>";
				}if(response[i].priority== 2 ){
					tokena=1;
					count_a=count_a+1;
					rowsa += "<tr style='background-color:"+response[i].color+";'>";
					rowsa += "<td style='width: 85%;'><i class='fa fa-navicon' style='font-size:18px;color:#292929' onclick='ver_descrip(\""+response[i].id+"\")'></i><span style='color:#292929;margin-left:10px;font-weight: bold;font-size:15px'>"+response[i].tarea+"</span><span style='float:right'>"+response[i].avance+"%</span></td>";
					rowsa += "<td style='width: 15%;'>";
						rowsa += "<div class='dropdown'> <a class='btn' title='Ver comentario' onclick='ver_comentario(\""+response[i].id+"\")'>	<i class='fa fa-comments-o' 	style='font-size:15px;color:"+colorc+";'></i></a>";	
					 	rowsa += "<a class='btn' title='Adjuntar Archivo' onclick='ver_file(\""+response[i].id+"\",\""+response[i].file+"\")'>			<i class='fa fa-folder-open-o' 	style='font-size:15px;color:"+colorf+";'></i></a>";
					 	rowsa += "<a class='btn' title='Terminar Tarea' onclick='end_tarea(\""+response[i].id+"\")'>									<i class='fa fa-check-square-o' style='font-size:15px;color:#292929;'></i></a>";
						rowsa += "<a class='dropdown-toggle btn' data-toggle='dropdown' title='Porcentaje de avance' ><i class='fa fa-line-chart' style='font-size:15px;color:#292929;'></i></a>  ";
                        rowsa += "    <ul class='dropdown-menu pull-right' role='menu' aria-labelledby='dLabel' style='width: 70px;'>";
                        rowsa += "      <li style='font-size: 20px;background: #fff'>";
                        rowsa += "      <input type='range' min='0' max='100' step='0' onchange='uppriority(\""+response[i].id+"\",this.value)'>";
                        rowsa += "      </li>";
                        rowsa += "    </ul></div> ";
					rowsa += "</td>";
					rowsa += "</tr>";
				}if(response[i].priority== 1 ){
					tokenv=1;
					count_a=count_a+1;
					rowsv += "<tr style='background-color:"+response[i].color+";'>";
					rowsv += "<td style='width: 85%;'><i class='fa fa-navicon' style='font-size:18px;color:#292929' onclick='ver_descrip(\""+response[i].id+"\")'></i><span style='color:#292929;margin-left:10px;font-weight: bold;font-size:15px'>"+response[i].tarea+"</span><span style='float:right'>"+response[i].avance+"%</span></td>";
					rowsv += "<td style='width: 15%;'>";
						rowsv += "<div class='dropdown'> <a class='btn' title='Ver comentario' onclick='ver_comentario(\""+response[i].id+"\")'>	<i class='fa fa-comments-o' 	style='font-size:15px;color:"+colorc+";'></i></a>";	
					 	rowsv += "<a class='btn' title='Adjuntar Archivo' onclick='ver_file(\""+response[i].id+"\",\""+response[i].file+"\")'>			<i class='fa fa-folder-open-o' 	style='font-size:15px;color:"+colorf+";'></i></a>";
					 	rowsv += "<a class='btn' title='Terminar Tarea' onclick='end_tarea(\""+response[i].id+"\")'>									<i class='fa fa-check-square-o' style='font-size:15px;color:#292929;'></i></a>";
						rowsv += "<a class='dropdown-toggle btn' data-toggle='dropdown' title='Porcentaje de avance' ><i class='fa fa-line-chart' style='font-size:15px;color:#292929;'></i></a>  ";
                        rowsv += "    <ul class='dropdown-menu pull-right' role='menu' aria-labelledby='dLabel' style='width: 70px;'>";
                        rowsv += "      <li style='font-size: 20px;background: #fff'>";
                        rowsv += "      <input type='range' min='0' max='100' step='0' onchange='uppriority(\""+response[i].id+"\",this.value)'>";
                        rowsv += "      </li>";
                        rowsv += "    </ul></div> ";
					rowsv += "</td>";
					rowsv += "</tr>";
				}		
			}

			if(tokenr==1){
				conteor=count_r;
				$('#bell').show();
				$('#count_r').empty();
				$('#count_r').append(conteor);
				$('#content_r').show();
				$('#list_tarea_r > tbody').empty();
				$('#list_tarea_r > tbody').append(rowsr);

			}
			if(tokena==1){
				conteov=count_a;
				$('#count_m').empty();
				$('#count_m').append(conteov);
				$('#content_a').show();
				$('#list_tarea_a > tbody').empty();
				$('#list_tarea_a > tbody').append(rowsa);
			}
			if(tokenv==1){
				conteov=count_a;
				$('#count_m').empty();
				$('#count_m').append(conteov);
				$('#content_v').show();
			    $('#list_tarea_v > tbody').empty();
				$('#list_tarea_v > tbody').append(rowsv);
			}
		}
	});
}

function armar_estrucctura(type){
	var type=type;
	var row="";
	var titulo="";
	$.post('../services/meli_manager.php',
		{ 	action  : 'armar_e', 
			user  	: sessionStorage.getItem('id'),
			type 	: type,
		}).fail(function(){ alert('error list tarea');
	}).done(function(e){
		var response = JSON.parse(e);
		if (response.response == 0){
			row += "<p>No hay Tareas</p>";
        	if(type==1){
        		$('#count_t').empty();
				$('#count_t').append('0');
				$('#endtareas').empty();
				$('#endtareas').append(row);
			}
			if(type==2){
				$('#count_t').empty();
				$('#count_t').append('0');
				$('#endtareasbad').empty();
				$('#endtareasbad').append(row);
			}
			if(type==3){
				$('#count_t').empty();
				$('#count_t').append('0');
				$('#asigtareas').empty();
				$('#asigtareas').append(row);
			}
			if(type==4){
				$('#count_t').empty();
				$('#count_t').append('0');
				$('#cumplitareas').empty();
				$('#cumplitareas').append(row);
			}
		}else{
			var count_t=0;
			var count_c=0;
			var count_n=0;
			var count_a=0;
			for(var i in response){
				row +="<div class='row "+response[i].user_name+" tabl_t'>";
	            row +="    <div class='x_panel'>";
	            row +="      <div class='x_title collapse-link row' data-toggle='tooltip' data-placement='bottom' title='Ver listado de tareas de '>";
	            row +="        <div class=''>";
	            row +="           <img  class='foto' src='"+response[i].avatar+"' style='width: 100px;border-radius: 60px;margin-left:20px'><span class='title_t'> Tareas de "+response[i].name+" "+response[i].last_name+"</span >";
	            row +="          <div class='clearfix'></div>";
	            row +="        </div>";
	            row +="        <div class='clearfix'></div>";
	            row +="      </div>";
	            row +="      <div class='x_content' display='' style='display: block;'>";
	            row +="        <table id='list_"+response[i].user_asig+"' class='table table-striped table-bordered' width='100%'>";
	            row +="          <tbody>";
	            if(type==1){
	            	row +="				<tr ><td>Tareas Terminadas</td><td>Acciones</td></tr>";
				}
				if(type==2){
	            	row +="				<tr ><td>Tareas No Cumplidas</td><td>Acciones</td></tr>";
				}
				if(type==3){
	            	row +="				<tr ><td>Tareas Asignadas</td><td>Acciones</td></tr>";
				}
				if(type==4){
	            	row +="				<tr ><td>Tareas Cumplidas</td><td>Acciones</td></tr>";
				}
	            for(var y in response[i].tareas){
	            	var colorc=""
					var colorf="";
					var thumb="";
					var dato="";
					if (response[i].tareas[y].comentary) {	colorc="blue";	}else{	colorc="#292929"	}
					if (response[i].tareas[y].file) {		colorf="blue";	}else{	colorf="#292929";	}
					if (response[i].tareas[y].status== 'C')		{ 	dato=response[i].tareas[y].avance+"%";}
					if (response[i].tareas[y].status== 'B') 	{	dato="Rechazada";	}
					if (response[i].tareas[y].status== 'NT') 	{	dato="Expirada";	}
					row += "<tr style='background-color:"+response[i].tareas[y].color+";'>";
					row += "<td style='width: 80%; ' title='"+dato+"'><i class='fa fa-navicon' style='font-size:18px;color:#292929' onclick='ver_descrip(\""+response[i].tareas[y].id+"\")'></i><span style='color:#292929;margin-left:10px;font-weight: bold;font-size:15px'>"+response[i].tareas[y].tarea+"</span><span style='margin-right:10px;float: right'>"+dato+"</span></td>";
					row += "<td style='width: 20%; '>";
						row += "<a class='btn' title='Ver comentario' onclick='ver_comentario(\""+response[i].tareas[y].id+"\")'>	<i class='fa fa-comments-o' 	style='font-size:15px;color:"+colorc+";'></i></a>";	
					 	row += "<a class='btn' title='Adjuntar Archivo' onclick='ver_file_preview(\""+response[i].tareas[y].id+"\",\""+response[i].tareas[y].file+"\")'>			<i class='fa fa-eye' 	style='font-size:15px;color:"+colorf+";'></i></a>";
					 	if(type==1){
					 		count_t=count_t+1;
						 	row += "<a class='btn' title='Cumplida' onclick='good_tarea(\""+response[i].tareas[y].id+"\")'>									<i class='fa fa-thumbs-o-up' style='font-size:15px;color:#292929;'></i></a>";
						 	row += "<a class='btn' title='Rechazada' onclick='bad_tarea(\""+response[i].tareas[y].id+"\")'>									<i class='fa fa-thumbs-o-down' style='font-size:15px;color:#292929;'></i></a>";
						 	
						}
						if(type==2){
							count_n=count_n+1;
							row += "<a class='fa fa-clock-o dropdown-toggle btn' data-toggle='dropdown' style='color:black;'></a>  ";
                            row += "<ul class='dropdown-menu pull-right' role='menu' aria-labelledby='dLabel' style='width: 160px;'>";
                            row += "  <li style='font-size: 12px;background: #fff'>";
                            row += "    <input type='date' name='fechar' id='fechar' onchange='update_fecha(\""+response[i].tareas[y].id+"\",this.value,\"2\")'>";
                            row += "  </li>";
                            row += "</ul>";

							//row += "<a class='btn' title='Reprogramar' onclick='reprogramar_tarea(\""+response[i].tareas[y].id+"\")'>	<i class='fa fa-clock-o' style='font-size:15px;color:#292929;'></i></a>";
							row += "<a class='btn' title='eliminar' onclick='eliminar_tarea(\""+response[i].tareas[y].id+"\")'>	<i class='fa fa-times' style='font-size:15px;color:#292929;'></i></a>";
							row += "<a class='btn' title='Archivar' onclick='archivar_tarea(\""+response[i].tareas[y].id+"\")'>	<i class='fa fa-download' style='font-size:15px;color:#292929;'></i></a>";
						}
						if(type==3){
							count_a=count_a+1;
							row += "<a class='fa fa-clock-o dropdown-toggle btn' data-toggle='dropdown' style='color:black;'></a>  ";
                            row += "<ul class='dropdown-menu pull-right' role='menu' aria-labelledby='dLabel' style='width: 160px;'>";
                            row += "  <li style='font-size: 12px;background: #fff'>";
                            row += "    <input type='date' name='fechar' id='fechar' onchange='update_fecha(\""+response[i].tareas[y].id+"\",this.value,\"1\")'>";
                            row += "  </li>";
                            row += "</ul>";
							//row += "<a class='btn' title='Reprogramar' onclick='reprogramar_tarea(\""+response[i].tareas[y].id+"\")'>	<i class='fa fa-clock-o' style='font-size:15px;color:#292929;'></i></a>";
							row += "<a class='btn' title='eliminar' onclick='eliminar_tarea(\""+response[i].tareas[y].id+"\")'>	<i class='fa fa-times' style='font-size:15px;color:#292929;'></i></a>";
						}
						if(type==4){
							count_c=count_c+1;
							row += "<a class='btn' title='Archivar' onclick='archivar_tarea(\""+response[i].tareas[y].id+"\")'>	<i class='fa fa-download' style='font-size:15px;color:#292929;'></i></a>";
						}
					row += "</td>";	
					row += "</tr>";

	            }
	            row +="          </tbody>";
	            row +="        </table>";
	            row +="        <div class='clearfix'></div>";
	            row +="      </div>";
	            row +="    </div>";
	            row +="</div>";
			}
			if(type==1){
				$('#count_t').empty();
				$('#count_t').append(count_t);
				$('#endtareas').empty();
				$('#endtareas').append(row);
			}
			if(type==2){
				$('#count_n').empty();
				$('#count_n').append(count_n);
				$('#endtareasbad').empty();
				$('#endtareasbad').append(row);
			}
			if(type==3){
				$('#count_a').empty();
				$('#count_a').append(count_a);
				$('#asigtareas').empty();
				$('#asigtareas').append(row);
			}
			if(type==4){
				$('#count_c').empty();
				$('#count_c').append(count_c);
				$('#cumplitareas').empty();
				$('#cumplitareas').append(row);
			}
		}	
	});
}


function armar_archivo(){
	var type=type;
	var row="";
	var titulo="";
	$.post('../services/meli_manager.php',
		{ 	action  : 'armar_arch', 
			user  	: sessionStorage.getItem('id'),
		}).fail(function(){ alert('error list tarea');
	}).done(function(e){
		var response = JSON.parse(e);
		if (response.response == 0){
			row += "<p>No hay Tareas</p>";
       
        		$('#count_arch').empty();
				$('#count_arch').append('0');
				$('#archtareas').empty();
				$('#archtareas').append(row);
		}else{
			var count_arch=0;
			for(var i in response){
				row +="<div class='row "+response[i].user_name+" tabl_t'>";
	            row +="    <div class='x_panel'>";
	            row +="      <div class='x_title collapse-link row' data-toggle='tooltip' data-placement='bottom' title='Ver listado de tareas de '>";
	            row +="        <div class=''>";
	            row +="           <img  class='foto' src='"+response[i].avatar+"' style='width: 100px;border-radius: 60px;margin-left:20px'><span class='title_t'> Tareas de "+response[i].name+" "+response[i].last_name+"</span >";
	            row +="          <div class='clearfix'></div>";
	            row +="        </div>";
	            row +="        <div class='clearfix'></div>";
	            row +="      </div>";
	            row +="      <div class='x_content' display='' style='display: block;'>";
	            row +="        <table id='list_"+response[i].user_asig+"' class='table table-striped table-bordered' width='100%'>";
	            row +="          <tbody>";
	            row +="				<tr ><td>Tareas Archivadas</td><td>Acciones</td></tr>";
	            for(var y in response[i].tareas){
	            	var colorc=""
					var colorf="";
					var thumb="";
					var dato="";
					count_arch=count_arch+1;
					if (response[i].tareas[y].comentary) {	colorc="blue";	}else{	colorc="#292929"	}
					if (response[i].tareas[y].file) {		colorf="blue";	}else{	colorf="#292929";	}
					if (response[i].tareas[y].status== 'G')		{ 	dato="Cumplida";}
					if (response[i].tareas[y].status== 'B') 	{	dato="Rechazada";	}
					if (response[i].tareas[y].status== 'NT') 	{	dato="Expirada";	}
					row += "<tr style='background-color:"+response[i].tareas[y].color+";'>";
					row += "<td style='width: 80%; ' title='"+dato+"'><i class='fa fa-navicon' style='font-size:18px;color:#292929' onclick='ver_descrip(\""+response[i].tareas[y].id+"\")'></i><span style='color:#292929;margin-left:10px;font-weight: bold;font-size:15px'>"+response[i].tareas[y].tarea+"</span><span style='margin-right:10px;float: right'>"+dato+"</span></td>";
					row += "<td style='width: 20%; '>";
					row += "<a class='btn' title='Ver comentario' onclick='ver_comentario(\""+response[i].tareas[y].id+"\")'>	<i class='fa fa-comments-o' 	style='font-size:15px;color:"+colorc+";'></i></a>";	
					row += "<a class='btn' title='Adjuntar Archivo' onclick='ver_file_preview(\""+response[i].tareas[y].id+"\",\""+response[i].tareas[y].file+"\")'>			<i class='fa fa-eye' 	style='font-size:15px;color:"+colorf+";'></i></a>";
					row += "</td>";	
					row += "</tr>";
	            }
	            row +="          </tbody>";
	            row +="        </table>";
	            row +="        <div class='clearfix'></div>";
	            row +="      </div>";
	            row +="    </div>";
	            row +="</div>";
			}
			
			$('#count_arch').empty();
			$('#count_arch').append(count_arch);
			$('#archtareas').empty();
			$('#archtareas').append(row);
		
		}	
	});
}

function reprogramar_tarea(id_t){
	var id_t=id_t;
	var rows="";
	$.post('../services/meli_manager.php',
		{ 	action  : 'list_tarea_by_id', 
			id_t  	: id_t,
		}).fail(function(){ alert('error reprogram tarea');
	}).done(function(e){
		var rows="";
		var response = JSON.parse(e); 
		if (response.response == 0){
			alert('error reprogramar');
		}else{
			show_asig_tareas();
			show_panel_tarea()
			rows +="<div style='float:left;'>  Asignado a: <a style='text-transform: uppercase;'>"+response[0].name+" "+response[0].last_name+" </a><i class='fa fa-times' title='Quitar Usuario' onclick=quitar_u()></i></div>";
			$('#name_user').empty();
			$('#name_user').append(rows);
			$('#icon_priority').css('color',response[0].color);
			$('#new_tarea').val(response[0].tarea);
			$('#priority_asig').val(response[0].priority);
			$('#user_asig').val(response[0].user_asig);
			$('#color_asig').val(response[0].color);
        }
	});
}

function llenar_estrucctura(){
	$.post('../services/meli_manager.php',
		{ 	action  : 'llenar_e', 
			user  	: sessionStorage.getItem('id'),
		}).fail(function(){ alert('error list tarea');
	}).done(function(e){
		var rows = "";
		var color;
		var response = JSON.parse(e);
		if (response.response == 0){
			rows += "<p>No hay Tareas</p>";
        	$('#info_t').empty();
        	$('#info_t').append(rows);
		}else{
 			$('#info_t').empty();

 			rows += "<tr ><td>Tareas Culminadas</td><td>Acciones</td></tr>";
			for(var i in response){

				var colorc=""
				var colorf="";
				var thumb="";

				if (response[i].comentary) {
						colorc="blue";
				}else{
					colorc="black"
				}
				if (response[i].file) {
					colorf="blue";
				}else{
					colorf="black";
				}
				rows += "<tr style='background-color:"+response[i].color+";'>";
				rows += "<td style='width: 80%; '><i class='fa fa-circle-o' style='font-size:18px;color:black'></i><span style='color:black;margin-left:10px;font-weight: bold;font-size:15px'>"+response[i].tarea+"</span></td>";
				rows += "<td style='width: 20%; '>";
					rows += "<a class='btn' title='Ver comentario' onclick='ver_comentario(\""+response[i].id+"\")'>	<i class='fa fa-comments-o' 	style='font-size:15px;color:"+colorc+";'></i></a>";	
				 	rows += "<a class='btn' title='Adjuntar Archivo' onclick='ver_file(\""+response[i].id+"\",\""+response[i].file+"\")'>			<i class='fa fa-folder-open-o' 	style='font-size:15px;color:"+colorf+";'></i></a>";
				 	rows += "<a class='btn' title='Terminar Tarea' onclick='end_tarea(\""+response[i].id+"\")'>									<i class='fa fa-check-square-o' style='font-size:15px;color:green;'></i></a>";
				rows += "</td>";	
				rows += "</tr>";
			}
			$('#content_r').show();
			$('#list_tarea_r > tbody').empty();
			$('#list_tarea_r > tbody').append(rows);
		}
	});
}

function list_tarea_term(){
	armar_estrucctura(1);
}
function list_tarea_no_cumplidas(){
	armar_estrucctura(2);
}
function list_tarea_asig(){
	armar_estrucctura(3);
}
function list_tarea_cumplidas(){
	armar_estrucctura(4);
}

function tareas_proyecto(id_p,name_p){
	$('#tablero_today').hide();
	$('#tablero_proyect').show();
	var id_proyecto=id_p;
	var name_proyecto=name_p;
	var title ="";
	title += name_proyecto;
	$('#title_proyect').empty();
	$('#title_proyect').append(title);

	$.post('../services/meli_manager.php',
		{ 	action  : 'list_tarea_by_proyect', 
			user  	: sessionStorage.getItem('id'),
			id_proyecto    : id_proyecto,
		}).fail(function(){ alert('error tarea proyect');
	}).done(function(e){
		var rows = "";
		var color;
		var response = JSON.parse(e);
		if (response.response == 0){
			rows += "<p>No hay Tareas para este Proyecto</p>";
        	$('#info_p').empty();
        	$('#info_p').append(rows);
        	$('#job').show()
			$('#content_p').hide();
		}else{
 			$('#info_p').empty();
 			$('#content_p').show();
			for(var i in response){
				rows += "<tr style='background-color:"+response[i].priority+";'>";
				rows += "<td style='width: 85%; word-wrap: break-word;'><span style='color:black'>"+response[i].tarea+"</span></td>";
				rows += "<td style='width: 15%; word-wrap: break-word;'> <div class='dropdown'> <a class='fa fa-ellipsis-h dropdown-toggle' data-toggle='dropdown'></a>  <ul class='dropdown-menu pull-right' role='menu' aria-labelledby='dLabel' style=''>";
		        rows += "<li><i class='fa fa-trash'><a>Elimina</a></i></li>";
		        rows += "</ul>  </div></td>";
				rows += "</tr>";		
			}
		    $('#form_tarea_p').hide();
		    $('#list_tarea_p > tbody').empty();
			$('#list_tarea_p > tbody').append(rows);
			init_DataTables_local("list_tarea_p");
		}
	});
}

function list_proyect_tarea(){
	$.post('../services/meli_manager.php',
		{ 	action  : 'list_proyect', 
			user  	: sessionStorage.getItem('id'),
		}).fail(function(){ alert('error list proyect tarea');
	}).done(function(e){
		var rows="";
		var response = JSON.parse(e); 
		if (response.response == 0){
			rows += "<li>No hay proyectos</li>";
        	$('#list_proyect_tarea').append(rows);
		}else{

	        for(var i in response){
	        	rows += "<li><i class='fa fa-circle' style='font-size:15px;padding-left: 10px;color:"+response[i].color+"'></i><span style='cursor:pointer;padding-left:10px;' onclick='select_proyect("+response[i].id+",\""+response[i].name+"\")'>"+response[i].name+"</span></li>";
	        }
        	$('#list_proyect_tarea').append(rows);
        }
	});
}

function list_user_tarea(){
	var user="";
	var sub="";
	user= sessionStorage.getItem('user_type');
	if(user==5){
		sub=5;
	}else
	if(user==3){
		sub=8;
	}else
	if(user==4){
		sub=9;
	} else
	if(user==1){
		sub=2;
	}else
	if(user==7){
		sub=10;
	}else{
		sub=0;
	}
	$.post('../services/meli_manager.php',
		{ 	action  : 'list_user_tarea', 
			user  	: sessionStorage.getItem('id'),
			sub 	: sub,
		}).fail(function(){ alert('error list user tarea');
	}).done(function(e){
		var rows="";
		var response = JSON.parse(e); 
		if (response.response == 0){
			rows += "<li>No hay Usuarios</li>";
        	$('#list_user_tarea').append(rows);
		}else{
			rows += "<li> <div class='container'>";
	        for(var i in response){
	        	rows += "<div class='col-xs-3 col-sm-3 col-md-3 nopad text-center'>";
	        	rows += "<label class='image-checkbox'>";
				rows += "<img class='img-responsive' src='"+response[i].avatar+"' />";
				rows += "<input type='checkbox' name='image[]' class='ads_Checkbox' value='"+response[i].id+"' />";
				rows += "<i class='fa fa-check hidden'></i>"
				rows += "</label>";
				rows += "</div>";
	        	//rows += "<li><i class='fa fa-circle' style='font-size:15px;padding-left: 10px;color:green'></i><span style='cursor:pointer;padding-left:10px;' onclick='select_user("+response[i].id+",\""+response[i].name+" "+response[i].last_name+"\")'>"+response[i].name+" "+response[i].last_name+"</span></li>";
	        	//rows += "<img src='"+response[i].avatar+"' width='80px' height='80px' style='padding:10px;border-radius:45px' onclick='select_user("+response[i].id+",\""+response[i].name+" "+response[i].last_name+"\")' title='"+response[i].name+" "+response[i].last_name+"'>";
	        	//"<i class='fa fa-circle' style='font-size:15px;padding-left: 10px;color:green'></i><span style='cursor:pointer;padding-left:10px;' onclick='select_user("+response[i].id+",\""+response[i].name+" "+response[i].last_name+"\")'>"+response[i].name+" "+response[i].last_name+"</span></li>";
	        }
	        rows += "</div></li>";
	        $('#list_user_tarea').empty();
        	$('#list_user_tarea').append(rows);
        }
	});
}

function select_priority(value,color){
	var valor=value;
	var color=color;
	var rows="";
	$('#priority_asig').empty();
	var rows="";
	$('#priority_asig').val(valor);
	$('#color_asig').val(color);
	$('#icon_priority').css("color",color)
}

function select_proyect(value,namep){
	var valor=value;
	var name =namep;
	var rows="";
	$('#proyect_asig').empty();
	$('#proyect_asig').val(valor);
	rows +="<div style='float:left;'>  Proyecto: <a style='text-transform: uppercase;'>"+name+" </a><i class='fa fa-times' title='Quitar Proyecto' onclick=quitar_p()></i></div>";
	$('#name_proye').empty();
	$('#name_proye').append(rows);
}

function select_user(value,namep){
	var valor=value;
	var name =namep;
	var rows="";
	$('#user_asig').empty();
	$('#user_asig').val(valor);
	rows +="<div style='float:left;'>  Asignado a: <a style='text-transform: uppercase;'>"+name+" </a><i class='fa fa-times' title='Quitar Usuario' onclick=quitar_u()></i></div>";
	$('#name_user').empty();
	$('#name_user').append(rows);
}

function quitar_u(){
	$('#name_user').empty();
	$('#user_asig').empty();
}

function quitar_p(){
	$('#name_proye').empty();
	$('#proyect_asig').empty();
}




//###########################################
function combo_p() {
	$.post('../services/meli_manager.php',
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
	$.post('../services/meli_manager.php',
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
	$.post('../services/meli_manager.php',
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
	$.post('../services/meli_manager.php',
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
	$.post('../services/meli_manager.php',
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
	$.post('../services/meli_manager.php',
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
	$.post('../services/meli_manager.php',
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
	$.post('../services/meli_manager.php',
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
	$.post('../services/meli_manager.php',
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
	$.post('../services/meli_manager.php',
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
	
	$.post('../services/meli_manager.php',
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
	
	$.post('../services/meli_manager.php',
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
		$.post('../process/meli_create.php',
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
		$.post('../services/meli_manager.php',
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
		$.post('../process/meli_update_by_items.php',
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
	$.post('../process/meli_update_by_items.php',
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
	$.post('../process/meli_update_by_items.php',
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
	$.post('../process/meli_update_by_items.php',
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
		$.post('../process/meli_create.php',
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
		$.post('../services/meli_manager.php',
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
		$.post('../process/meli_update_by_items.php',
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
	$.post('../process/meli_update_by_items.php',
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
	$.post('../process/meli_update_by_items.php',
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
	$.post('../process/meli_update_by_items.php',
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
