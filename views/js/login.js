 function set_menu(){
  var user = sessionStorage.getItem('user_type');
  switch (user) {
    case '3':
    case '4':
    if (user == 4) {
      $(".queen_panel").show();
      console.log('Queen Manager');
    }
    if (user == 3) {
      console.log('Mauxi Manager');
      $(".mauxi_panel").show();
    }
    $('[menu=compras]').show();
    $('[menu=ordenes]').show();
    $('[menu=tracking]').show();
    $('[menu=user]').hide();
    $('[menu=bechmark]').hide();
    $('[menu=tarea]').show();
    break;
    case '1':
    $('[menu=ordenes]').hide();
    $('[menu=tracking]').show();                      
    $('[menu=user]').hide();
    $(".queen_panel").show();
    $(".mauxi_panel").show();
    $('[menu=offer]').hide();
    $('[menu=tarea]').show();
    $('[menu=bechmark]').hide();
    break;
    case '2':
    $('[menu=ordenes]').hide();
    $('[menu=compras]').hide();                      
    $('[menu=user]').hide();
    $(".queen_panel").show();
    $(".mauxi_panel").show();
    $('[menu=tarea]').show();
    $('[menu=offer]').hide();
    $('[menu=bechmark]').hide();
    break;
    case '5':
    $(".queen_panel").show();
    $(".mauxi_panel").show();
    $('[menu=tarea]').show();
    break;
    case '6':
    $('[menu=compras]').hide();
    $('[menu=ordenes]').hide();
    $('[menu=tracking]').hide();
    $('[menu=user]').hide();
    $('[menu=offer]').hide();
    $('[menu=tarea]').show();
    $('[menu=bechmark]').hide();
    break;
    case '7':
    $('[menu=compras]').hide();
    $('[menu=ordenes]').hide();
    $('[menu=tracking]').show();                      
    $('[menu=user]').hide();
    $(".queen_panel").show();
    $(".mauxi_panel").show();
    $('[menu=offer]').hide();
    $('[menu=tarea]').show();
    $('[menu=bechmark]').hide();
    break;
    case '8':
    $('[menu=compras]').hide();
    $('[menu=ordenes]').hide();
    $('[menu=tracking]').hide();
    $('[menu=user]').hide();
    $(".mauxi_panel").show();
    $(".queen_panel").hide();
    $('[menu=offer]').show();
    $('[menu=tarea]').show();
    $('[menu=bechmark]').hide();
    break;
    case '9':
    $('[menu=compras]').hide();
    $('[menu=ordenes]').hide();
    $('[menu=tracking]').hide();
    $('[menu=user]').hide();
    $(".queen_panel").show();
    $(".mauxi_panel").hide();
    $('[menu=offer]').show();
    $('[menu=tarea]').show();
    $('[menu=bechmark]').hide();
    break;
    case '10':
    $('[menu=compras]').hide();
    $('[menu=ordenes]').hide();
    $('[menu=tracking]').hide();
    $('[menu=user]').hide();
    $('[menu=offer]').hide();
    $('[menu=tarea]').show();
    $('[menu=bechmark]').hide();
    break;
    case '11':
    $('[menu=compras]').hide();
    $('[menu=ordenes]').hide();
    $('[menu=tracking]').hide();
    $('[menu=user]').hide();
    $('[menu=offer]').hide();
    $('[menu=tarea]').show();
    $('[menu=bechmark]').hide();
    break;
  }
}

function check_user(){
  if(sessionStorage.getItem('usuario')){
    $('body').show();
    $('#user_name').text(sessionStorage.getItem('usuario',sessionStorage.getItem('user_name')));
    $('#user_avatar').attr('src',sessionStorage.getItem('avatar',sessionStorage.getItem('avatar')));
    $('#nombre_c').text(sessionStorage.getItem('nombre_c',sessionStorage.getItem('nombre_c')));
  }else{
    $(location).attr('href','https://core.enkargo.com.co/views/');        
  } }

  function logout(){
   sessionStorage.clear();
   $.post('../services/user_manager.php', {
    action: 'logout_user',
    user_id: sessionStorage.getItem('id')
  });
  $(location).attr('href','https://core.enkargo.com.co/views/');
}

function user_login(){
 var password = CryptoJS.MD5($('#password').val()).toString();	
 $.post('../services/user_manager.php', {
  action: 'get_user',
  userName : $('#userName').val(),
  password : password,
  user_id: sessionStorage.getItem('id')
}).done(function (data) {
  if (data == 2) {
    alert('El usuario/contraseña no coinciden');
    location.reload();
  }else if (data == 0){
    alert('Ha ocurrido un error al procesar la información');
    location.reload();
  }else { 
    response = JSON.parse(data);
    sessionStorage.setItem('avatar',response.avatar);
    sessionStorage.setItem('id',response.id);
    sessionStorage.setItem('usuario',response.user_name);
    sessionStorage.setItem('user_type',response.user_type);
    sessionStorage.setItem('nombre_c',response.name+" "+response.last_name);
    $(location).attr('href','https://core.enkargo.com.co/views/main.html');
  }
});
}
