<?php
include "/var/www/html/enkargo/config/googleTranslate.php";
include "/var/www/html/enkargo/config/conex_manager.php";
include "/var/www/html/enkargo/services/aws_update.php";


#echo ini_get('post_max_size')."\n";
#ini_set('post_max_size', '512000000');
#echo ini_get('post_max_size')."\n";
#echo ini_get('upload_max_filesize')."\n";
#ini_set('upload_max_filesize', '512000000');
#echo ini_get('upload_max_filesize')."\n";

####################rafael
if ($_POST['action'] == 'search_sku_mx') {
     $resulta="";
     $shop_id  = $_POST['shop_id'];
     $sku           = $_POST['sku1_mx'];
     $orders = array();
     $conn = new Connect();
     $sql = "select * from meli.items where shop_id = ".$shop_id." and aws_id in (Select id from aws.items where sku='".$sku."');";
     #$sql = "SELECT * from meli.items where id=2469355;";
     $result = pg_query($sql);
     while ($item = pg_fetch_array($result)) {
          array_push($orders, $item);        
     }
 
     if (!isset($orders[0]['mpid'])) {
          echo json_encode(array('response'=>0));      
     }else{
          echo json_encode($orders);
     }
}

if ($_POST['action'] == 'search_sku_mx_json') {
     $resulta  ="";
     $shop_id  = $_POST['shop_id'];
     $sku      = $_POST['sku1_mx'];
     $orders   = array();
     $conn     = new Connect();

     $jsondata = file_get_contents('https://core.enkargo.com.co/json/sku.json');
     $data = json_decode($jsondata, true); 
     $validate=0;
     foreach($data as $item)
     {
         if($item->sku == $sku)
         {
             $validate   = 1;
             $id         = $item->id;
             break;
         }
     }
     if ($validate==1){
          $sql = "select * from meli.items where shop_id = ".$shop_id." and aws_id='".$id."');";
          $result = pg_query($sql);
          while ($item = pg_fetch_array($result)) {
               array_push($orders, $item);        
          }

          if (!isset($orders[0]['mpid'])) {
               echo json_encode(array('response'=>0));      
          }else{
               echo json_encode($orders);
          }  
     }else{
          echo json_encode(array('response'=>1));   
     }
}



if ($_POST['action'] == 'search_sku_qb') {
	$resulta="";
     $shop_id  = $_POST['shop_id'];
     $sku           = $_POST['sku1_qb'];
     $orders = array();
     $conn = new Connect();
     $sql = "select * from meli.items where shop_id = ".$shop_id." and aws_id in (Select id from aws.items where sku='".$sku."');";
     #$sql = "SELECT * from meli.items where id=2469355;";
     $result = pg_query($sql);
     while ($item = pg_fetch_array($result)) {
          array_push($orders, $item);        
     }
 
     if (!isset($orders[0]['mpid'])) {
          echo json_encode(array('response'=>0));      
     }else{
          echo json_encode($orders);
     }
}

if ($_POST['action'] == 'combo_p') {
     $orders = array();
     $conn = new Connect();
     $sql = "SELECT id,definition from meli.category_master where padre= '0'";
     $result = pg_query($sql);
     while ($item = pg_fetch_array($result)) {
          array_push($orders, $item);        
     }
     echo json_encode($orders);
}

if ($_POST['action'] == 'combo_h') {
     $categoria     = $_POST['category'];
     $orders   = array();
     $hijos    = validateCategory($categoria);
     if ($hijos->children_categories[0]->id){     
          foreach ($hijos->children_categories as $hijo) {
               $item = array('id'=> $hijo->id,'name'=>$hijo->name);
               array_push($orders, $item);        
          }
          echo json_encode($orders);
     }else{
          echo json_encode(array('response'=>0,'attribute'=>$hijos->attribute_types));         
     }
}

if ($_POST['action'] == 'combo_color') {
     $categoria     = $_POST['category'];
     $attributes_co      = array();
     $variacion  = variation($categoria);
     foreach ($variacion as $var) {
          if($var->id == '11000'){
               foreach ($var->values as $color) {
                    array_push($attributes_co, array('name' => $color->name));  
               }    
               echo json_encode($attributes_co);
          }else{
               #echo json_encode(array('response'=>0));          
          }
     }
}

if ($_POST['action'] == 'combo_talla') {
     $categoria     = $_POST['category'];
     $attributes_ta      = array();
     $variacion  = variation($categoria);
     foreach ($variacion as $var) {
          if($var->name=='Talla'){
               foreach ($var->values as $talla) {
                    array_push($attributes_ta, array( 'name' => $talla->name));      
               }    
               echo json_encode($attributes_ta);
          }else{
               #echo json_encode(array('response'=>0));
          }
     }
}

####################rafael

/*
******************** Estados de Orden según acciones del sistema ********************
    Estatus | Significado
	N   | Negada
	P   | Pendiente
	C   | Cancelada
	G   | Default al crer orden en BD
	B   | Comprada
	NV  | Novedad
	SL  | Enviar Después
	SD  | Enviado

******************** Acciones de usuarios en el sistema ********************
     1) Aprobar órden #(número de órden) Fecha:(día) para COMPRA
     2) Cancelar órden #(número de órden) Tienda:(nombre de tienda) Fecha:(día) 
     3) Actualizar órden #(número de órden) como PENDIENTE  
     4) Cargar nueva nota en órden #(número de órden) Tienda:(nombre de tienda) Fecha:(día) 
     5) Comprar órden #(número de órden) Tienda:(nombre de tienda) Fecha:(día) 
     6) Consultar órdenes #(número de tracking) Fecha:(día) 
     7) Actualizar información de TRACKING Fecha:(día) 
     7) Actualizar información de ORDEN #(numero de órden) Fecha:(día) 
     8) Cargar nueva orden de compra MANUALMENTE #(número de órden AWS) Fecha:(día) 
     9) Cargar nueva GARANTIA #(número de órden MELI) Fecha:(día) 
     10) Ver órdenes PENDIENTES Fecha:(día) 
     11) Ver LISTA de órdenes Fecha:(día) 
     12) Ver órdenes CANCELADAS Fecha:(día) 
     13) Ver órdenes COMPRADAS Fecha:(día) 
     14) Ver órdenes APROBADAS Fecha:(día) 
     15) Ver órdenes PENDIENTES DE ENVIO Fecha:(día) 
     16) Ver órdenes CON NOVEDAD Fecha:(día) 
     17) Ver órdenes CON GARANTIA Fecha:(día) 
     18) Ver órdenes PEDIDOS EXTRA Fecha:(día) 
     19) Ver órdenes ORDENES NO ENVIADAS Fecha:(día) 
*/

     if ($_POST['action'] == 'get_order_not_delivered') {
     	$conn = new Connect();
     	$sql_order = "select * from system.view_sl where autorice = 'SL';";
     	$response = array();
     	$query = pg_query($sql_order);
     	while($result_order = pg_fetch_object($query)){
     		$info = order_by_id($result_order->user_name,$result_order->id_order, $result_order->access_token);	
		#creating PDF label for print
     		$image = explode("~^~", $result_order->image_url);
     		$notes = getNote($result_order->id_order,$result_order->access_token);	
     		$notes_list = array();
     		foreach ($notes[0]->results as $key) {
     			array_push($notes_list,array(
     				'note' => $key->note,
     				'date' => date_format(date_create($key->date_created),"Y-m-d H:i:s")
     			));
     		}
     		$result_shipping = array(
     			"image" => $image[0],
     			"order" => $result_order->id_order,
     			"account" => $result_order->id,
     			"title" => $result_order->title,
     			"price" => $result_order->price,
     			"price_aws" => $result_order->sale_price,
     			"quantity" => $result_order->quantity,
     			"notes" => $notes_list,
     			"comment" => $result_order->comentary,
     			"shipping_mode" => $info->shipping->shipping_mode,
     			"shipping_id" => $info->shipping->id,
     			"buyer_city" => $info->shipping->receiver_address->city->name,
     			"buyer_address" => $info->shipping->receiver_address->address_line,
     			"buyer_fullname" => $info->buyer->first_name." ".$info->buyer->last_name,
     			"buyer_phone" => $info->buyer->phone->number,
     			"seller_id" => $result_order->id,
     			"seller_name" => $result_order->user_name,
     			"order_price" => $info->total_amount,
     			"order_status" => $info->shipping->status,
     			"token" => $result_order->access_token
     		);
     		array_push($response, $result_shipping);
     	}
	#----------------- USER LOG -----------------
     	$user_id = $_POST['user_id'];
     	$activity = "Ver Órdenes NO ENVIADAS";
     	set_user_log($user_id, $activity);
	#----------------- USER LOG -----------------
     	echo json_encode($response);
     }

     if ($_POST['action'] == 'get_order_delivered') {
     	$shop_id = $_POST['shop_id'];
     	$orders = array();
     	$conn = new Connect();
     	$sql = "select * from system.orders where shop_id = '$shop_id' and autorice = 'SD';";
     	$result = pg_query($sql);
     	while ($item = pg_fetch_array($result)) {
     		array_push($orders, $item);		
     	}
     	echo json_encode($orders);
     }


     if ($_POST['action'] == 'shipping_search') {
     	$aws_tracking = $_POST['aws_tracking'];
     	$conn = new Connect();
     	$sql_order = "select * from system.view_sl where tracking_aws ='$aws_tracking' and autorice = 'B';";
     	$response = array();
     	$query = pg_query($sql_order);
     	while($result_order = pg_fetch_object($query)){
     		$info = order_by_id($result_order->user_name,$result_order->id_order, $result_order->access_token);	
		#creating PDF label for print
     		$image = explode("~^~", $result_order->image_url);
               

               
     		$notes = getNote($result_order->id_order,$result_order->access_token);	
     		$notes_list = array();
     		foreach ($notes[0]->results as $key) {
     			array_push($notes_list,array(
     				'note' => $key->note,
     				'date' => date_format(date_create($key->date_created),"Y-m-d H:i:s")
     			));
     		}
     		$result_shipping = array(
     			"image" => $image[0],
     			"order" => $result_order->id_order,
     			"account" => $result_order->id,
     			"title" => $result_order->title,
     			"price" => $result_order->price,
     			"price_aws" => $result_order->sale_price,
     			"quantity" => $result_order->quantity,
     			"notes" => $notes_list,
     			"comment" => $result_order->comentary,
     			"shipping_mode" => $info->shipping->shipping_mode,
     			"shipping_id" => $info->shipping->id,
     			"buyer_city" => $info->shipping->receiver_address->city->name,
     			"buyer_address" => $info->shipping->receiver_address->address_line,
     			"buyer_fullname" => $info->buyer->first_name." ".$info->buyer->last_name,
     			"buyer_phone" => $info->buyer->phone->number,
     			"seller_id" => $result_order->id,
     			"seller_name" => $result_order->user_name,
     			"order_price" => $info->total_amount,
     			"order_status" => $info->shipping->status,
     			"token" => $result_order->access_token
     		);
     		array_push($response, $result_shipping);
     	}
	#----------------- USER LOG -----------------
     	$user_id = $_POST['user_id'];
     	$activity = "Consultar órdenes #($aws_tracking)";
     	set_user_log($user_id, $activity);
	#----------------- USER LOG -----------------
     	echo json_encode($response);
     }

     if($_POST['action'] == 'new_order'){
     	$date = date_create($_POST['create_date']);
     	$create_date = date_format($date,"Y/m/d H:i:s");
     	$who_buy = $_POST['who_buy'];
     	$aws_url = $_POST['aws_url'];
     	$quantity = $_POST['quantity'];
     	$prime = $_POST['prime'];
     	$comentary = $_POST['commentary'];
     	$aws_id_order = $_POST['aws_id_order'];
     	$tracking = $_POST['tracking'];
     	$account = $_POST['account'];
     	$conn = new Connect();
     	$query = "INSERT INTO system.orders (create_date, applicant, url, quantity, prime, comentary, id_order_aws, tracking_aws, cuenta, order_type) VALUES ('$create_date', '$who_buy', '$aws_url', $quantity, '$prime', '$comentary', $aws_id_order, $tracking, '$account', 'M');";
     	$result = pg_query($query);
     	if ($result > 0) {
		#----------------- USER LOG -----------------
     		$user_id = $_POST['user_id'];
     		$activity = "Cargar nueva orden de compra MANUALMENTE #$aws_id_order";
     		set_user_log($user_id, $activity);
		#----------------- USER LOG -----------------
     		echo json_encode(array('response'=>1));
     	}else{
     		echo json_encode(array('response'=>0));		
     	}

     }


     if($_POST['action'] == 'new_warranty'){
     	$create_date = date_format(date_create($_POST['create_date']),"Y-m-d");
     	$aws_id_order = $_POST['aws_id_order'];
     	$meli_id_order = $_POST['meli_id_order'];
     	$meli_account = $_POST['meli_account'];
     	$dolar_price = $_POST['dolar_price'];
     	$reason = $_POST['reason'];
     	$status = $_POST['status'];
     	$conn = new Connect();
     	$query = "INSERT INTO system.warranties (aws_id_order, meli_id_order, meli_account, dolar_price, reason, status, create_date) VALUES ($aws_id_order, $meli_id_order, $meli_account, $dolar_price, '$reason', '$status', '$create_date');";
     	$result = pg_query($query);
     	if ($result > 0) {
		#----------------- USER LOG -----------------
     		$user_id = $_POST['user_id'];
     		$activity = "Cargar nueva GARANTIA #$meli_id_order";
     		set_user_log($user_id, $activity);
		#----------------- USER LOG -----------------
     		echo json_encode(array('response'=>1));
     	}else{
     		echo json_encode(array('response'=>0));		
     	}

     }

     if ($_POST['action'] == 'get_order_aws_warranty') {
     	$orders = array();
     	$conn = new Connect();
     	$sql = "select * from system.warranties;";
     	$result = pg_query($sql);
     	while ($item = pg_fetch_array($result)) {
     		array_push($orders, $item);		
     	}
	#----------------- USER LOG -----------------
     	$user_id = $_POST['user_id'];
     	$activity = "Ver órdenes CON GARANTIA";
     	set_user_log($user_id, $activity);
	#----------------- USER LOG -----------------	
     	echo json_encode($orders);
     }

     if ($_POST['action'] == 'loadFile_aws_extra') {
     	$file = $_FILES['file']['name'];
     	$file_tmp = $_FILES['file']['tmp_name'];
     	$data = [];
     	$target_file   = "/var/www/html/enkargo/docs/".basename($file);
     	$conn = new Connect();
     	move_uploaded_file($_FILES['file']['tmp_name'], $target_file);
     	$fila = 1;
     	if (($gestor = fopen($target_file, "r")) !== FALSE) {
     		while (($datos = fgetcsv($gestor, 0, ";")) !== FALSE) {
     			$numero = count($datos);
     			$fila++;
     			$row = [];
     			for ($c=0; $c < $numero; $c++) {
     				$row[$c] = $datos[$c]; 
     			}
     			array_push($data, $row);
     		}
     		fclose($gestor);
     	}
     	for ($i=1; $i < count($data); $i++) { 
     		$create_date = $data[$i][0];		
     		$applicant = $data[$i][1];		
     		$url = $data[$i][2];		
     		$quantity = $data[$i][3];		
     		$sale_price = $data[$i][4];		
     		$prime = $data[$i][5];		
     		$comentary = $data[$i][6];
     		$id_order_aws = $data[$i][7];		
     		$tracking_aws = $data[$i][8];		
     		$cuenta = $data[$i][9];		
     		$sql = "INSERT INTO system.orders (create_date, applicant, url, quantity, sale_price, prime, comentary, id_order_aws, tracking_aws, cuenta) VALUES($create_date, $applicant, $url, $quantity, $sale_price, $prime, $comentary, $id_order_aws, $tracking_aws, $cuenta);";
     		$result = pg_query($sql);
     	}
     	if ($result > 0) {
     		echo json_encode(array('response'=>1));		
     	}else{
     		echo json_encode(array('response'=>0));		
     	}
     }

     if ($_POST['action'] == 'get_order_aws_extra') {
     	$orders = array();
     	$conn = new Connect();
     	$sql = "select create_date, applicant, url, quantity, sale_price, prime, comentary, id_order_aws, tracking_aws, cuenta from system.orders where order_type = 'M'";
     	$result = pg_query($sql);
     	while ($item = pg_fetch_array($result)) {
     		array_push($orders, $item);		
     	}
	#----------------- USER LOG -----------------
     	$user_id = $_POST['user_id'];
     	$activity = "Ver PEDIDOS EXTRA";
     	set_user_log($user_id, $activity);
	#----------------- USER LOG -----------------	
     	echo json_encode($orders);
     }

     if ($_POST['action'] == 'create_note') {
     	$order = $_POST['id_order'];
     	$text = $_POST['text'];
     	$conn = new Connect();
	#----------------- USER LOG -----------------
     	$user_id = $_POST['user_id'];
     	$activity = "Cargar nueva nota en órden #$order";
     	set_user_log($user_id, $activity);
	#----------------- USER LOG -----------------
     	$client_query = pg_query("select shop_id, id_payer from system.orders where id_order = '$order';");
     	$result_client = pg_fetch_object($client_query);
     	$shop_query = pg_query("select user_name, application_id, access_token from meli.shop where id = $result_client->shop_id;");
     	$result = pg_fetch_object($shop_query);
     	echo json_encode(array('response' => createNote($order, $text,$result->access_token)));
     }

     if ($_POST['action'] == 'get_note') {
     	$order = $_POST['id_order'];
     	$shop_id = $_POST['shop_id'];
     	$conn = new Connect();
     	$shop_query = pg_query("select access_token from meli.shop  where id = $shop_id");	
     	$result = pg_fetch_object($shop_query);
     	$notes = getNote($order,$result->access_token);	
     	$notes_list = array();
     	foreach ($notes[0]->results as $key) {
     		array_push($notes_list,array(
     			'note' => $key->note,
     			'date' => date_format(date_create($key->date_created),"Y-m-d H:i:s")
     		));
     	}
     	echo json_encode($notes_list);
     }

     if ($_POST['action'] == 'get_order') {
     	$shop_id = $_POST['shop_id'];
     	$orders = array();
     	$conn = new Connect();
     	$sql = "select * from system.view_orders where shop_id = '$shop_id' and autorice = 'G';";
     	$result = pg_query($sql);
     	$rows = "";
     	while ($item = pg_fetch_object($result)) {
		#array_push($orders, $item);	
		$comentary = str_replace("\"", "", $item->comentary);
   		$color = "";
                $prime = "";
                $header = "<tr>";
                $alert_color = "white";
                if ($item->quantity > 1) {
                    $header = "<tr style='background-color:#c0ff91'>";
                    $alert_color = "#c0ff91";
                }
                if ($item->unit_price < $item->precio_esp) {
                    $header = "<tr style='background-color:#f9dbdb'>";
                    $alert_color = "#f9dbdb";
                }
                $rows .= $header;

		$rows .= "<td style='width: 90px; word-wrap: break-word;'>".$item->id_order."</td>";
     		$rows .= "<td style='width: 90px; word-wrap: break-word;'>".$item->create_date."</td>";
     		$rows .= "<td style='width: 90px; word-wrap: break-word;'>".$item->sku."</td>";
     		$rows .= "<td style='width: 100px; word-wrap: break-word;'><a href='".$item->permalink."' target='_blank' >".$item->mpid."</a></td>";
     		$rows .= "<td style='width: 90px; word-wrap: break-word;'>$".$item->sale_price."</td>";
     		$rows .= "<td style='width: 90px; word-wrap: break-word;'>$".$item->unit_price."</td>";
     		$rows .= "<td style='width: 90px; word-wrap: break-word;'>$".$item->total_paid."</td>";
               if ($item->quantity != 1){
                    $rows .= "<td style='width: 75px; word-wrap: break-word;'><b style='color:green;'>".$item->quantity."</b></td>";
               }else{
                    $rows .= "<td style='width: 75px; word-wrap: break-word;'>".$item->quantity."</td>";
               }
		 if ($item->avaliable == 'f' || $item->unit_price < $item->precio_esp) {
                    $color = 'orange';
                    $icon  = 'fa fa-exclamation-triangle';
               }else{
                    $color = 'green';
                    $icon  = 'fa fa-thumbs-o-up';
               }
               $msn='OK';
               if($item->avaliable == 'f'){
                    $msn=' No Disponible';
               } else if($item->unit_price < $item->precio_esp){
                    $msn='Valor esperado de compra $'.$item->precio_esp;
               }
               if ($item->is_prime == 1) {
                    $prime = "<br><p class='prime' style='font-size:  10px; margin-top:  11px;  color: #65b4ec; font-family:  sans-serif;'>PRIME</p>";
               }else{
                    $prime = "<br><p class='prime' style='font-size:  10px; margin-top:  11px;  color: red; font-family:  sans-serif;'>NO PRIME</p>";  
               }


     		$rows .= "<td style='width: 80px; word-wrap: break-word; color :  ".$alert_color."; text-align:center; font-size:24px;'>".$item->avaliable."<i style='color : ".$color."' class='".$icon."' title='".$msn."'></i>".$prime."</td>";
     		$rows .= "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver ítem' href = '".$item->url."' target='_blank'><i class='fa fa-eye' style='font-size:24px;'></i></a></td>";
     		$rows .= "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver nota' onclick='ver_nota(\"".$item->id_order."\",\"".$shop_id."\")'>";
     		$rows .= "<i class='fa fa-edit'></i>";
     		$rows .= "</a></td>";
     		if ($item->comentary) {
     			$rows .= "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver comentario' onclick='ver_comentario(\"".$item->id_order."\",\"".$commentary."\")'><i class='fa fa-comments-o' style='color:red; font-size:20px;'></i></a></td>";	
     		}else{
     			$rows .= "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver comentario' onclick='ver_comentario(\"".$item->id_order."\",\"".$item->comentary."\")'><i class='fa fa-comments-o' style='font-size:20px;'></i></a></td>";	
     		}
     		switch ($item->autorice) {
     			case 'R':
     			$rows .= "<td style='width: 150px; word-wrap: break-word; color:  ".$alert_color.";' id='res_".$item->id_order."'>".$item->autorice."<a class='btn' title='Novedad en item' style='background-color:  red;width:  97px;height:  25px; color:white; font-size:9px; margin-top:13px;'>RECHAZADA</a></td>";
     			break;
     			case 'B':
     			$rows .= "<td style='width: 150px; word-wrap: break-word; color: ".$alert_color.";' id='res_".$item->id_order."'>".$item->autorice."<a class='btn' title='Comprada orden' style='background-color:  green; width:  97px;height:  25px; color:white; font-size:9px; margin-top:13px;'>CONFIRMADA</a></td>";
     			break;
     			case 'P':
     			$rows .= "<td style='width: 150px; word-wrap: break-word; color:  ".$alert_color.";' id='res_".$item->id_order."'>".$item->autorice."<a class='btn' title='Comprada orden' style='background-color:  blue; width:  97px;height:  25px; color:white; font-size:9px; margin-top:13px;'>PENDIENTE</a></td>";
     			break;
     			case 'C':
     			$rows .= "<td style='width: 150px; word-wrap: break-word; color:  ".$alert_color.";' id='res_".$item->id_order."'>".$item->autorice."<a class='btn' title='Novedad en item' style='background-color:  red;width:  97px;height:  25px; color:white; font-size:9px; margin-top:13px;'>RECHAZADA</a></td>";
     			break;
     			case 'G':
     			$rows .= "<td style='width: 300px; word-wrap: break-word;color: white; padding-top:12px;' id='res_".$item->id_order."'>".$item->autorice." <a class='btn ".$item->id."' title='Confirmar orden' onclick='confirm_order(\"".$item->id."\",\"".$item->id_order."\")'><i class='fa fa-check-square'style='font-size:20px; color:green;'></i></a><a class='btn ".$item->id."' title='Pendiente por comprar' onclick='pending_order(\"".$item->id."\",\"".$item->id_order."\")'><i class='fa fa-clock-o'style='font-size:20px; color:blue;'></i></a><a class='btn ".$item->id."' title='Rechazar orden' onclick='refuse_order(\"".$item->id_order."\",\"".$item->id_order."\",0)'><i class='fa fa-times-circle'style='font-size:20px; color:red;'></i></a></td>";
     			break;
     			default:
     			$rows .= "<td style='width: 300px; word-wrap: break-word;color:  ".$alert_color.";' id='res_".$item->id_order."'>".$item->autorice." <a class='btn ".$item->id."' title='Confirmar orden' onclick='confirm_order(\"".$item->id."\",\"".$item->id_order."\")'><i class='fa fa-check-square'style='font-size:20px; color:red;'></i></a><a class='btn ".$item->id."' title='Rechazar orden' onclick='refuse_order(\"".$item->id_order."\",\"".$item->id_order."\",0)'><i class='fa fa-times-circle'style='font-size:20px; color:red;'></i></a></td>";
     			break;
     		}
     		$rows .= "</tr>";	
     	}
	#----------------- USER LOG -----------------
     	$user_id = $_POST['user_id'];
     	$activity = "Ver LISTA de órdenes";
     	set_user_log($user_id, $activity);
	#----------------- USER LOG -----------------
     	#echo json_encode($orders);
     	echo $rows;
     }

     if ($_POST['action'] == 'get_order_error') {
     	$shop_id = $_POST['shop_id'];
     	$orders = array();
     	$conn = new Connect();
     	$sql = "select * from system.orders where shop_id = '".$shop_id."' and autorice = 'NV';";
     	$result = pg_query($sql);
     	while ($item = pg_fetch_array($result)) {
     		array_push($orders, $item);		
     	}
	#----------------- USER LOG -----------------
     	$user_id = $_POST['user_id'];
     	$activity = "Ver órdenes CON GARANTIA";
     	set_user_log($user_id, $activity);
	#----------------- USER LOG -----------------
     	echo json_encode($orders);
     }

     if ($_POST['action'] == 'get_order_pending') {
     	$shop_id = $_POST['shop_id'];
     	$orders = array();
     	$conn = new Connect();
     	$sql = "select * from system.orders where shop_id = '".$shop_id."' and autorice = 'P';";
     	$result = pg_query($sql);
     	while ($item = pg_fetch_array($result)) {
     		array_push($orders, $item);		
     	}
	#----------------- USER LOG -----------------
     	$user_id = $_POST['user_id'];
     	$activity = "Ver órdenes PENDIENTES";
     	set_user_log($user_id, $activity);
	#----------------- USER LOG -----------------
     	echo json_encode($orders);
     }

     if ($_POST['action'] == 'get_order_cancel') {
     	$shop_id = $_POST['shop_id'];
     	$orders = array();
     	$conn = new Connect();
     	$sql = "select * from system.orders where shop_id = '".$shop_id."' and autorice = 'R';";
     	$result = pg_query($sql);
     	while ($item = pg_fetch_array($result)) {
     		array_push($orders, $item);		
     	}
	#----------------- USER LOG -----------------
     	$user_id = $_POST['user_id'];
     	$activity = "Ver órdenes CANCELADAS";
     	set_user_log($user_id, $activity);
	#----------------- USER LOG -----------------
     	echo json_encode($orders);
     }

     if ($_POST['action'] == 'get_order_aws') {
     	$shop_id = $_POST['shop_id'];
     	$orders = array();
     	$conn = new Connect();
     	$sql = "select * from system.view_orders where (autorice = 'C' or autorice = 'B') and shop_id = '".$shop_id."'";
     	$result = pg_query($sql);
	$rows ="";
     	while ($item = pg_fetch_object($result)) {
     		#array_push($orders, $item);
		$comentary = str_replace("\"", "", $item->comentary);
     		$color = "";
     		$prime = "";
     		$header = "<tr>";
     		$alert_color = "white";
     		if ($item->quantity > 1) {
     		    $header = "<tr style='background-color:#c0ff91'>";
		    $alert_color = "#c0ff91";					
     		}
     		if ($item->unit_price < $item->precio_esp) {
     		    $header = "<tr style='background-color:#f9dbdb'>"; 
		    $alert_color = "#f9dbdb";					
     		}
     		$rows .= $header; 
     		$rows .= "<td style='width: 90px; word-wrap: break-word;'>".$item->id_order."</td>";
     		$rows .= "<td style='width: 90px; word-wrap: break-word;'>".$item->create_date."</td>";
     		$rows .= "<td style='width: 90px; word-wrap: break-word;'>".$item->sku."</td>";
               if($item->permalink <> null){
     		     $rows .= "<td style='width: 100px; word-wrap: break-word;'><a href='".$item->permalink."' target='_blank' >".$item->mpid."</a></td>";
               }else{
                    $rows .= "<td style='width: 100px; word-wrap: break-word;'>".$item->mpid."</td>";
               }

     		$rows .= "<td style='width: 90px; word-wrap: break-word;'>$".$item->sale_price."</td>";
     		$rows .= "<td style='width: 90px; word-wrap: break-word;'>$".$item->unit_price."</td>";
     		$rows .= "<td style='width: 90px; word-wrap: break-word;'>$".$item->total_paid."</td>";
     		$rows .= "<td style='width: 30px; word-wrap: break-word;'>".$item->quantity."</td>";
     		if ($item->avaliable == 'f' || $item->unit_price < $item->precio_esp) {
                    $color = 'orange';
                    $icon  = 'fa fa-exclamation-triangle';
               }else{
                    $color = 'green';
                    $icon  = 'fa fa-thumbs-o-up';
               }
               $msn='OK';
               if($item->avaliable == 'f'){
                    $msn=' No Disponible';
               } else if($item->unit_price < $item->precio_esp){
                    $msn='Valor esperado de compra $'.$item->precio_esp;    
               }
	       if ($item->is_prime == 1) {
	       	    $prime = "<br><p class='prime' style='font-size:  10px; margin-top:  11px;  color: #65b4ec; font-family:  sans-serif;'>PRIME</p>";
	       }else{
	       	    $prime = "<br><p class='prime' style='font-size:  10px; margin-top:  11px;  color: red; font-family:  sans-serif;'>NO PRIME</p>";		   
	       }
               $rows .= "<td style='width: 80px; word-wrap: break-word; color : ".$alert_color."; text-align:center; font-size:24px;'>".$item->avaliable."<i style='color : ".$color."' class='".$icon."' title='".$msn."'></i>".$prime."</td>";
     		$rows .= "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver ítem' href = '".$item->url."' target='_blank'><i class='fa fa-eye' style='font-size:20px;'></i></a></td>";
     		$rows .= "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver nota' onclick='ver_nota(\"".$item->id_order."\",\"".$shop_id."\")'>";
     		$rows .= "<i class='fa fa-edit'></i>";
     		$rows .= "</a></td>";
     		if ($comentary) {
     			$rows .= "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver comentario' onclick='ver_comentario(\"".$item->id_order."\",\"".$comentary."\")'><i class='fa fa-comments-o' style='color:red; font-size:20px;'></i></a></td>";	
     		}else{
     			$rows .= "<td style='width: 40px; word-wrap: break-word;'><a class='btn' title='Ver comentario' onclick='ver_comentario(\"".$item->id_order."\",\"".$comentary."\")'><i class='fa fa-comments-o' style='font-size:20px;'></i></a></td>";	
     		}
     		$rows .= "<td style='width: 70px; word-wrap: break-word;'><a class='btn' title='Ver detalle ítem' onclick='ver_item_detail(\"".$item->id."\")'><i class='fa fa-file-text-o' style='font-size:20px;'></i></a></td>";
     		switch ($item->autorice) {
     			case 'B':
     			if($item->tracking_aws){
     				$rows .= "<td style='width: 350px; word-wrap: break-word;color: ".$alert_color.";' id='res_".$item->id_order."'>".$item->autorice." <a class='btn ".$item->id."' title='Comprada orden' style='background-color:  green; width:  97px;height:  25px; color:white; font-size:9px; margin-top:13px;'>COMPRADO</a></td>";
     			}else{
     				$rows .= "<td style='width: 350px; word-wrap: break-word;color: ".$alert_color.";' id='res_".$item->id_order."'>".$item->autorice." <a class='btn ".$item->id."' title='Comprada orden sin Información de Tracking' style='background-color:  green; width:  97px;height:  25px; color:white; font-size:9px; margin-top:13px;'>COMPRADO S/T</a></td>";					
     			}
     			break;
     			case 'N':
     			$rows .= "<td style='width: 350px; word-wrap: break-word;color:".$alert_color.";' id='res_".$item->id_order."'>".$item->autorice." <a class='btn ".$item->id."' title='Novedad en item' style='background-color:  red;width:  97px;height:  25px; color:white; font-size:9px; margin-top:13px;'>NOVEDAD</a></td>";
     			break;
     			default:
     			$rows .= "<td style='width: 350px; word-wrap: break-word;color: ".$alert_color.";' id='res_".$item->id_order."'>".$item->autorice."<a style='padding-left: 0px; border-left-width: 2px; padding-right: 0px; border-right-width: 2px;' class='btn ".$item->id."' title='Comprar orden' onclick='buy_order(\"".$item->id."\",\"".$item->id_order."\")'><i class='fa fa-check-square'style='font-size:20px; color:green;'></i></a><a style='padding-left: 0px; border-left-width: 2px; padding-right: 0px; border-right-width: 2px;' class='btn ".$item->id."' title='Cancelar y enviar a órdenes' onclick='refuse_order(\"".$item->id_order."\",\"".$item->id_order."\",4)'><i class='fa fa-window-close'style='font-size:20px; color:red;'></i></a><a style='padding-left: 0px; border-left-width: 2px; padding-right: 0px; border-right-width: 2px;' class='btn ".$item->id."' title='Cancelar orden por novedad' onclick='refuse_order(\"".$item->id_order."\",\"".$item->id_order."\",1)'><i class='fa fa-bell-o'style='font-size:20px; color:#f57b13;'></i></a></td>";
     			break;
     		}
     		$rows .= "</tr>";				
     	}
	#----------------- USER LOG -----------------
     	$user_id = $_POST['user_id'];
     	$activity = "Ver órdenes APROBADAS";
     	set_user_log($user_id, $activity);
	#----------------- USER LOG -----------------
     	echo $rows;
     }

     if ($_POST['action'] == 'confirm_order') {
     	$id_order = $_POST['id_order'];
     	$conn = new Connect();
	#----------------- USER LOG -----------------
     	$user_id = $_POST['user_id'];
     	$activity = "Aprobar órden #$id_order para COMPRA";
     	set_user_log($user_id, $activity);
	#----------------- USER LOG -----------------
     	$result = pg_query("update system.orders set autorice = 'C', update_date ='".date('Y-m-d H:i:s')."' where id = '".$id_order."';");
     	if ($result > 0) {
     		echo json_encode(array('responseA'=>1));
     	}else{
     		echo json_encode(array('responseA'=>0));		
     	}
     }

     if ($_POST['action'] == 'pending_order') {
     	$id_order = $_POST['id_order'];
     	$conn = new Connect();
	#----------------- USER LOG -----------------
     	$user_id = $_POST['user_id'];
     	$activity = "Actualizar órden #$id_order como PENDIENTE";
     	set_user_log($user_id, $activity);
	#----------------- USER LOG -----------------
     	$result = pg_query("update system.orders set autorice = 'P', update_date ='".date('Y-m-d H:i:s')."' where id = '".$id_order."';");
     	if ($result > 0) {
     		echo json_encode(array('responseA'=>1));
     	}else{
     		echo json_encode(array('responseA'=>0));		
     	}
     }

     if ($_POST['action'] == 'refuse_order') {
     	$id_order = $_POST['id_order'];
     	$type = $_POST['type'];
     	$conn = new Connect();
	#----------------- USER LOG -----------------
     	$user_id = $_POST['user_id'];
     	$activity = "Actualizar órden #$id_order como $type";
     	set_user_log($user_id, $activity);
	#----------------- USER LOG -----------------
     	$result = pg_query("update system.orders set autorice = '".$type."', update_date ='".date('Y-m-d H:i:s')."' where id_order = '".$id_order."';");
     	if ($result > 0) {
     		echo json_encode(array('responseR'=>1));
     	}else{
     		echo json_encode(array('responseR'=>0));		
     	}
     }

     if ($_POST['action'] == 'update_comment') {
     	$id_order = $_POST['id_order'];
     	$comment = $_POST['comment'];
     	$conn = new Connect();
     	$result = pg_query("update system.orders set comentary = '".$comment."', update_date ='".date('Y-m-d H:i:s')."' where id_order = '".$id_order."';");
     	if ($result > 0) {
     		echo json_encode(array('response'=>1));
     	}else{
     		echo json_encode(array('response'=>0));		
     	}
     }

     if ($_POST['action'] == 'loadFile') {
     	$file = $_FILES['file']['name'];
     	$file_tmp = $_FILES['file']['tmp_name'];
     	$data = [];
     	$target_file   = "/var/www/html/enkargo/docs/".basename($file);
     	$conn = new Connect();
     	move_uploaded_file($_FILES['file']['tmp_name'], $target_file);
     	$fila = 1;
     	if (($gestor = fopen($target_file, "r")) !== FALSE) {
     		while (($datos = fgetcsv($gestor, 0, ";")) !== FALSE) {
     			$numero = count($datos);
     			$fila++;
     			$row = [];
     			for ($c=0; $c < $numero; $c++) {
     				$row[$c] = $datos[$c]; 
     			}
     			array_push($data, $row);
     		}
     		fclose($gestor);
     	}
     	$regex = "/\(([^)]+)\)/";
     	for ($i=1; $i < count($data); $i++) { 
     		$matches = array();
     		$aws_order_date = $data[$i][0];		
     		$aws_order = $data[$i][1];		
     		$aws_quantity = $data[$i][13];
     		$email_account = $data[$i][17];
     		$aws_shipping_date = $data[$i][18];
                if(empty($aws_shipping_date)){
                    $aws_shipping_date='01/00/00';
               }    		
     		$aws_order_status = $data[$i][25];
     		preg_match_all($regex, $data[$i][26], $matches);
     		$tracking_number = $matches[1][0];
     		$aws_subtotal = (float) str_replace("$","",$data[$i][27]);
     		$aws_subtotal_tax = (float) str_replace("$","",$data[$i][28]);
     		$aws_total = (float) str_replace("$","",$data[$i][29]);
     		$aws_buyer_name = $data[$i][33];
     		$sku = $data[$i][4];
     		$sql = "UPDATE system.orders SET track_status='".$aws_order_status."', date_arrival='".$aws_shipping_date."', update_date ='".date("Y-m-d H:i:s")."' WHERE sku = '".$sku."' AND autorice = 'B' AND id_order_aws = '".$aws_order."';";
     		$result = pg_query($sql);
     		if ($result > 0) {
			#Set tracking number only if item was buy and has the same SKU
     			$sql = "UPDATE system.orders SET  create_date_buy= '".$aws_order_date."', tracking_aws ='".$tracking_number."', track_status='".$aws_order_status."', date_arrival='".$aws_shipping_date."', cuenta='".$email_account."', aws_subtotal='".$aws_subtotal."', aws_total_price='".$aws_total."',aws_buyer_name ='".$aws_buyer_name."', aws_quantity='".$aws_quantity."', aws_subtotal_tax = '".$aws_subtotal_tax."', update_date ='".date("Y-m-d H:i:s")."' WHERE sku = '".$sku."' AND autorice = 'B' AND id_order_aws = '".$aws_order."';";
     			$result = pg_query($sql);
     		}
     	}
     	if ($result > 0) {
		#----------------- USER LOG -----------------
     		$user_id = $_POST['user_id'];
     		$activity = "Actualizar información de TRACKING";
     		set_user_log($user_id, $activity);
		#----------------- USER LOG -----------------
     		echo json_encode(array('response'=>1));
     	}else{
     		echo json_encode(array('response'=>0));		
     	}
     }
     if ($_POST['action'] == 'view_item_detail') {
     	$id = $_POST['id'];
     	$conn = new Connect();
     	$orders = array();
     	$result = pg_query("select * from system.orders where id = '".$id."';");
     	if ($result > 0) {
     		while ($item = pg_fetch_array($result)) {
     			array_push($orders, $item);		
     		}
     		if (empty($orders)) {
     			$orders = array();
     			$result = pg_query("select * from system.orders where id = '".$id."';");
     			if ($result > 0) {
     				while ($item = pg_fetch_array($result)) {
     					array_push($orders, $item);		
     				}
     				echo json_encode($orders);
     				die();
     			}else{
     				echo json_encode(array('response'=>0));		
     				die();
     			}
     		}else{
     			echo json_encode($orders);
     		}
     	}else{
     		echo json_encode(array('response'=>0));		
     	}
     }
     if ($_POST['action'] == 'update_item_detail') {
	$sql = "UPDATE system.orders SET ";
     	$sql .= (isset($_POST['priceMl']) && $_POST['priceMl'] !== "")  ? 'sale_price = '.$_POST['priceMl'].',': '';
     	$sql .= (isset($_POST['quantity']) && $_POST['quantity'] !== "") ? 'quantity = '.$_POST['quantity'].',': '';
     	$sql .= (isset($_POST['statusMl']) && $_POST['statusMl'] !== "") ? 'status = \''.$_POST['statusMl'].'\',' : '';
     	$sql .= (isset($_POST['orderAws']) && $_POST['orderAws'] !== "") ? 'id_order_aws = \''.$_POST['orderAws'].'\',' : '';
     	$sql .= (isset($_POST['buyDate']) && $_POST['buyDate'] !== "") ? 'create_date_buy = \''.$_POST['buyDate'].'\',' : '';
     	$sql .= (isset($_POST['trackingNumber']) && $_POST['trackingNumber'] !== "") ? 'tracking_aws = \''.$_POST['trackingNumber'].'\',' : '';
     	$sql .= (isset($_POST['trackingStatus']) && $_POST['trackingStatus'] !== "") ? 'track_status = \''.$_POST['trackingStatus'].'\',' : '';
     	$sql .= (isset($_POST['arrivalDate']) && $_POST['arrivalDate'] !== "") ? 'date_arrival = \''.$_POST['arrivalDate'].'\',' : '';
     	$sql .= (isset($_POST['awsAccount']) && $_POST['awsAccount'] !== "") ? 'aws_buyer_name = \''.$_POST['awsAccount'].'\',' : '';
     	$sql .= (isset($_POST['comentary']) && $_POST['comentary'] !== "") ? 'comentary = \''.$_POST['comentary'].'\',' : '';
	$sql  = substr($sql,0,-1);
	$sql .= " WHERE id='".$_POST['id']."';";
     	$conn = new Connect();
     	$orders = array();
     	$result = pg_query($sql);
     	if ($result > 0) {
		#----------------- USER LOG -----------------
     		$user_id = $_POST['user_id'];
     		$activity = "Actualizar información de ORDEN #$orderAws";
     		set_user_log($user_id, $activity);
		#----------------- USER LOG -----------------
     		echo json_encode(array('response'=>1));
     	}else{
     		echo json_encode(array('response'=>0));		
     	}
     }
     if ($_POST['action'] == 'buy_order') {
     	$id_order = $_POST['id_order'];
     	$conn = new Connect();
     	$result = pg_query("update system.orders set autorice = 'B', update_date ='".date('Y-m-d H:i:s')."' where id = '".$id_order."';");
     	if ($result > 0) {
		#----------------- USER LOG -----------------
     		$user_id = $_POST['user_id'];
     		$activity = "Comprar órden #$id_order";
     		set_user_log($user_id, $activity);
		#----------------- USER LOG -----------------
     		echo json_encode(array('responseR'=>1));
     	}else{
     		echo json_encode(array('responseR'=>0));		
     	}
     }
#Funcion para el envío automático de mensajes según cambios de estatus
/*
if ($_POST['action'] == 'send_message') {
	$status = 1;
	$shop = 2;
	$order = "1705212823";
	$conn = new Connect();
	$subject ="";
	$message = "Gracias por su compra";
	$date = date('Y-m-d');
	$result = pg_fetch_object(pg_query("select * from meli.shop where id = '".$shop."';"));
	$subject = $result->name;
	#Validación del tipo de mensasje según es tipo de estatus de la orden
	switch ($status) {
		case 1:
		$message ="Hola 😄, muy buen día, espero te encuentres muy bien, mi nombre es Sebastian y voy a acompañarte en todo el proceso de tu compra. 😎 \n

		Primero que todo, gracias por preferirnos, te comentamos que ya está acreditado tu pago 💰 y el numero de compra es el  ,a partir de hoy realizaremos la orden de importación de tu producto, recuerda que el tiempo de entrega es de ✈ 4 a 10 días hábiles (como máximo) ✈ , esto se debe a que trabajamos directamente con la marca en Estados Unidos. 😄\n

		Por favor ten en cuenta que MercadoLibre maneja una fecha de entrega estimada diferente a la nuestra, por lo tanto te llegaran diferentes correos de MercadoLibre preguntándote como va el proceso de tu compra, estos correos solo debes omitirlos, yo te estaré informando todo el tiempo el estado de tu pedido, si tienes alguna duda, pregunta, queja o reclamo, no dudes primero en comunicarte conmigo por este medio o si gustas puedes comunicarte vía teléfono al PBX 7535495 Opción 1 📞 donde te atenderé personalmente para responder todas tus inquietudes. 😄\n

		Gracias nuevamente por tu compra y que tengas un día increíble. 😄";
		break;
		case 2:
		$message ="Hola, muy buenos días, te informamos que tu producto ya esta ingresando a Colombia exitosamente, esperamos poder realizarte el envío del producto lo mas antes posible, es un placer para nosotros poder servirte, por favor has caso omiso a los correo de Mercado Libre con respecto a los tiempos de entrega o \"envio demorado\", esto sucede ya que ellos no saben sobre nuestros tiempos de entrega,recuerda que es de 4 a 10 dias habiles como maximo, muchas gracias por tu comprension y paciencia, espero tengas un excelente día.";
		break;
		case 3:
		$message ="Muy buen día, me alegra informarte que tu producto esta en proceso de nacionalización y estamos a la espera de que llegue a nuestra oficina para hacerte el despacho, nosotros te notificamos cuando esto pase para que estés atento a recibirlo";
		break;
		case 4:
		$message ="Muy buen día,Es un gusto saludarte, te cuento tu producto ya esta en Colombia esta en revisión aduanera pasando los respectivos controles colombianos esperamos que este llegando lo más pronto posible a tu hogar. Gracias por tu paciencia te deseamos un Feliz día.";
		break;
		case 5:
		$message ="Buen día, espero estés muy bien, ya tenemos tu producto listo para ser enviado en nuestras oficinas, lo entregaremos hoy al transportador para que te entreguen en la dirección que nos confirmaste a través de la plataforma, te agradecemos nuevamente por tu compra, y esperamos tenga sun muy buen día.";
		break;
		default:
		$message = "Gracias por su compra";
		break;
	}
	
	$payer_id = pg_fetch_object(pg_query("select o.id_payer,p.first_name, p.last_name from system.orders as o join system.payer as p on p.id_payer = o.id_payer where o.id_order= '$order'"));
	if($payer_id){
		$url = "https://api.mercadolibre.com/messages?access_token=$result->access_token";
		#echo "<pre>";
		#echo $url;
		$messages_structure = array(
			'from'=>array(
				'user_id'=> $result->user_name
			),
			'to' =>array(array(
				'user_id'=> $payer_id->id_payer,
				'resource' => 'orders',
				'resource_id' => $order,
				'site_id' => 'MCO'
			)),
			'subject' => $subject,
			'text' =>array(
				'plain' => $message
			)
		);

		$ch             = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messages_structure));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

		$validation = json_decode(curl_exec($ch));
		echo "<pre>";
		print_r($validation);
		curl_close($ch);
		echo "insert into system.orders_messages (order_id, message_id, send_date) values ('$order','$validation->message_id','$date');";
		if(!$validation->error){
			$resource = pg_query("insert into system.orders_messages (order_id, message_id, send_date) values ('$order','$validation->message_id','$date');");
		}else{
			$resource = pg_query("insert into system.orders_messages (order_id, message_id, send_date) values ('$order','UNABLE SENT','$validation->date');");		
		}
		if($response > 0){
			echo json_encode(array('response'=>1));
		}else{
			echo json_encode(array('response'=>0));		
		}
	}else{
		echo json_encode(array('response'=>0));		
	}
}
#*/
function getNote($id_order,$access_token){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/orders/'.$id_order.'/notes?access_token='.$access_token);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
	$note = json_decode(curl_exec($ch));
	curl_close($ch);

	return $note;
}

function createNote($id_order, $text,$access_token){
	$messages_structure = array('note' => $text);
	$ch             = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/orders/'.$id_order.'/notes?access_token='.$access_token);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messages_structure));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
	$validation = json_decode(curl_exec($ch));
	if(isset($validation->message)){
		return 0;
	}else{
		return 1;
	}
}

function searchOrderShipId($id_order, $access_token){
	echo "https://api.mercadolibre.com/orders/$id_order?access_token=$access_token";die();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/orders/'.$id_order.'?access_token='.$access_token);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
	$order = json_decode(curl_exec($ch));
	curl_close($ch);
	print_r($order);die();
	return $note;
}


function order_by_id($shop,$id, $access_token) {
	$show_url = "https://api.mercadolibre.com/orders/search?seller=".$shop."&q=".$id."&access_token=".$access_token;
	$ch       = curl_init();
	curl_setopt($ch, CURLOPT_URL, $show_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
	$show = json_decode(curl_exec($ch));
	curl_close($ch);
	#return $show->results[0]->shipping;
	return $show->results[0];
}

function validateCategory($category_id) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/categories/'.$category_id);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

	$validation = json_decode(curl_exec($ch));
	curl_close($ch);

	return $validation;
}

function variation($category_id) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/categories/'.$category_id.'/attributes');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

	$validation = json_decode(curl_exec($ch));
	curl_close($ch);
	return $validation;
}

function set_user_log($user_id, $activity){
	$conn = new Connect();
	$date = date("Y/m/d H:i:s");
	$activity = $activity." Fecha:$date";
	$sql = "insert into system.log(user_id, activity, date) values($user_id, '$activity','$date');";
	pg_query($sql);
}
