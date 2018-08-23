var result = Array();

function user_delete(id_user){
	$.post('../services/user_manager.php', {
		action: 'delete_user',
		id:  result[id_user].id
	}).done(function (data) {
		if (data == 1) {            
        	alert('Usuario eliminado con éxito!');
        	location.reload();
        }else{
            alert('Ha ocurrido un error al procesar la información');
        }
	});
}
function user_detail(id_user){
	$('.user_form').prop('disabled', 'true');
	$('#avatar').hide();
	$('.rol_select').hide();
	$('.password').hide();
	$('.rol_input').show();
	$('.password').hide();
	$('#avatarImg').attr('src', result[id_user].avatar);
	$('#name').val(window.result[id_user].name);
	$('#lastname').val(window.result[id_user].last_name);
	$('#idCard').val(window.result[id_user].id_card);
	$('#userName').val(window.result[id_user].user_name);
	$('#shop').val(window.result[id_user].shop_name);
	$('#user_btn').html('<i class="fa fa-check"></i> Ok').attr({'onclick':'','data-dismiss':'modal'});
	$('.cancel_modal').hide();
}
function user_detail_edit(id_user){
	$('.password').hide();
	$('#avatarImg').attr('src', result[id_user].avatar);
	$('#name').val(window.result[id_user].name);
	$('#lastname').val(window.result[id_user].last_name);
	$('#idCard').val(window.result[id_user].id_card);
	$('#userName').val(window.result[id_user].user_name);
	$('#user_btn').html('<i class="fa fa-refresh"></i> Actualizar').attr({'onclick':'create_user(2,'+result[id_user].id+')','data-dismiss':'modal'});
}
function load_avatar(element){
  
}

function create_user(type,id){
	var password = CryptoJS.MD5($('#password').val()).toString();
	var data = new FormData();
	var action;
	if(type == 1){
		action = 'save_user';
	}
	if(type == 2){
		action = 'update_user';
	}
	if (id != null) {
		id = id;
	}
    data.append('action',action);
    data.append('id',id);
    data.append('name',$('#name').val());
    data.append('lastname',$('#lastname').val());
    data.append('idCard', $('#idCard').val());
    data.append('userName', $('#userNameUser').val());
    data.append('password', password);
    data.append('shop', $('.rol_select').val());
    $.ajax({
        url : '../services/user_manager.php',
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST'
    }).done(function (data) {
        if (data == 1) {            
        	alert('Usuario creado con éxito!');
        	location.reload();
        }else{
            alert('Ha ocurrido un error al procesar la información');
        }
});
}

function get_users(){
	$.post('../routing/systemUsers.php', 
		{
			action: 'get'
		}).done(function(e){
			window.result = e;
			var table = "";
			var user_type;
			for(var i in result){
				switch (result[i].user_type) {
					case "1":
						user_type = "Compras";
						break;
					case "2":
						user_type = "Despacho";
						break;
					case "3":
						user_type = "Mauxi Manager";
						break;
					case "4":
						user_type = "Queen Bee Manager";
						break;
					case "5":
						user_type = "Administrador";
						break;
					case "6":
						user_type = "Publicador";
						break;
				}
				table += "<tr>";
                table += "<td>"+i+"</td>";
                table += "<td>"+result[i].user_name+"</td>";
                table += "<td>"+result[i].name+" "+result[i].last_name+"</td>";
                table += "<td>"+user_type+"</td>";
                table += "<td>";
	            table += "<a class='btn' title='Ver detalles de usuario' data-toggle='modal' data-target='.user_add'\
	            	     onclick='user_detail("+i+")'>";
	            table += "<i class='fa fa-eye'></i> ";
	            table += "</a>";
				table += "<a class='btn' title='Editar información de usuario' data-toggle='modal' data-target='.user_add'\
	            	     onclick='user_detail_edit("+i+")'>";
	            table += "<i class='fa fa-edit'></i>";
	            table += "</a>";
	            table += "<a class='btn' title='Eliminar usuario' onclick='user_delete("+i+")'>";
	            table += "<i class='fa fa-trash'></i>";
	            table += "</a>";
	            table += "</td>";
			}
			$("#users_list").append(table);
			init_DataTables_local("users_list");

		}).fail(function(){
			alert('Error cargando elementos!');
		});
}

$(document).ready(function(){
	get_users();
});