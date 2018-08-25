var path = new Array();
var template = 1;
var categories = new Array();
var skus = new Array();
var mpids = new Array()
var video;
var type;

function set_banner(){
  window.type = 'banner';
}

function upload_video(){
  window.type = 'video';
  window.video = $('#video_url').val();
  $('#video_url').val("");
  $('#video_display').attr('src', '');
}

function display_video(element){
  $('#video_display').attr('src', $(element).val()+'?autoplay=1');
}
function open_modal(){
   $(".video_manager").modal("show");
}

function add_plus_mpid() {
  var mpid = $("#mpid_id").val();
  var tr ="<tr>\
            <td>"+mpid+"</td>\
           </tr>";
  $('#t_mpids').append(tr);
  window.mpids.push(mpid);
}

function add_plus_sku() {
  var sku = $("#sku_id").val();
  var tr ="<tr>\
            <td>"+sku+"</td>\
           </tr>";
  $('#t_skus').append(tr);
  window.skus.push(sku);
}

function add_plus_item(id_item) {
  var cat_id = $(id_item).attr('data-sub-cat-id');
  var name = $(id_item).attr('data-cat_name');
  var tr ="<tr>\
            <td>"+name+"</td>\
           </tr>";
  $('#t_categories').append(tr);
  window.categories.push(cat_id);
}

function load_img(element,canvas_name){
  console.log('aqui');
  var img_id =$(element).attr('data-img-name');
  var url = URL.createObjectURL(event.target.files[0]);
  $('#'+canvas_name).attr('src', url);
  window.path.push($(element)[0].files[0]);
  for(var i in window.path){
  	window['t'+i] = i;
  }
}

function save(){	
	  var data = new FormData();
    data.append('application',$('#application_id').val());
    data.append('categories',window.categories);
    data.append('skus',window.skus);
    data.append('mpids',window.mpids);
    if(window.type == 'banner'){
      for(var i in window.path){
        data.append('img'+i,window.path[i]);        
        data.append('action','save_description');
        data.append('template',window.template);
       }      
    }
    if(window.type == 'video'){
        data.append('action','save_video');
        data.append('video',window.video);
    }
    $.ajax({
      url : '../services/meli_description.php',
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      type: 'POST'
    },function(){
      $('.loading_gif').show();
      $('.title_count').hide();
    }).done(function (data) {
      if (data == 1) {    
        alert('Registrado con éxito');
      }else{
        alert('Ha ocurrido un problema al procesar la información');
      }
    });
    
    
 }
function get_template(url,id_template){
	window.template = id_template;
	$('#template_base').load(url,function(){
	});
}

function select_category() {
	$('.amz_item_detail_modal').show();
}

function search_bysku(){
  $('#bysku').show();
}

function search_byid(){
  $('#byid').show();
}

function get_categories() {
 $.get('https://api.mercadolibre.com/sites/MCO/categories',{
  }).done(function(e){
   	   window.panel = "";
       window.panel += '<div class="panel-group">';
       window.panel += '<div class="panel panel-default">';
    for(var res in e){
       window.panel += '<div class="panel-heading">';
       window.panel += '<h4 class="panel-title">';
       window.panel += "<div class='col-xs-1 col-md-1 col-sm-1' style='cursor:pointer' data-sub-cat-id = '"+e[res].id+"' data-main-cat-id='' data-cat_name ='"+e[res].name+"' data-sel-type ='multiple'onclick='add_plus_item(this)'><span class='glyphicon glyphicon-plus' title='Añadir a lista'></div>";
       window.panel += '<a data-toggle="collapse" href="#collapse_'+e[res].id+'">';
       window.panel += '   ';
       window.panel += e[res].name;
       window.panel += '</a>';
       window.panel += '</h4>';
       window.panel += '</div>';
       window.panel += '<div id="collapse_'+e[res].id+'" class="panel-collapse collapse in">';
       window.panel += '<ul class="list-group">';    
      $.get('https://api.mercadolibre.com/categories/'+e[res].id).done(function(resp){
          window.test = "";
          for(var j in resp['children_categories']){
            window.test += '<li class="list-group-item" id="sub_'+resp['children_categories'][j].id+'">';
            window.test += '<div class="row">';
            window.test += '<div class="col-xs-4 col-md-4 col-sm-4">'+resp['children_categories'][j].name+'</div>';
            window.test += "<div class='col-xs-1 col-md-1 col-sm-1' style='cursor:pointer' data-sub-cat-id = '"+resp['children_categories'][j].id+"' data-main-cat-id='"+resp['path_from_root'][0].id+"' data-cat_name ='"+resp['children_categories'][j].name+"' data-sel-type ='multiple'onclick='add_plus_item(this)'><span class='glyphicon glyphicon-plus' title='Añadir a lista'></div>";
            window.test += '</div>';
            window.test += '</li>';
          }
          $('#collapse_'+resp['id']+'>ul').append(window.test);
      });
       window.panel += '</ul>';
       window.panel += '</div>';
    }
       window.panel += '</div>';
       window.panel += '</div>';
    $('.panel-group').append(window.panel);
    $('.collapse').collapse()

  });
}
function art_category(id_category) {
  $('.list_items').empty();
  localStorage.setItem('panel_categories',$('.panel-group').html());
  window.list = '<ul class="list-group">';
  $('body').css('cursor','wait');
  $.get('settins.php',{
    'action' : 'get_all_sub_categories',
    'main_id' : $(id_category).attr('data-main-cat-id'),
    'child_id' : $(id_category).attr('data-sub-cat-id'),
  }).done(function(e){
    var result = JSON.parse(e);
    $('.panel-group').empty();
    $('body').css('cursor','default');
    for(var det in result['items']){
        window.list += '<li class="list-group-item">';
        window.list += '<div class="row">';
        window.list += '<div class="col-xs-4 col-md-4 col-sm-4">'+result['items'][det]['title']+'</div>';
        window.list += '<div class="col-xs-4 col-md-4 col-sm-4" data-item-id ="'+result['items'][det]['id']+'" data-sel-type ="single" onclick="add_item(this)"><span class="glyphicon glyphicon-plus" title="Add to list"></div>';
        window.list += '</div>';
        window.list += '</li>';
    }
    window.list += '</ul>';
  $('#cerrar_modal').replaceWith('<button type="button"  id="cerrar_modal" class="btn btn-default" onclick="volver_categories()"><span class="glyphicon glyphicon-chevron-left"></span> Volver</button>');
  $('.list_items').append(window.list);
  });
}
$(document).ready(function(){
	$('#template_base').load("../templates/template1.html",function(){
	});
	$('.cancelar').click(function() {
       $('.modal').hide();
    });
    get_categories();
});