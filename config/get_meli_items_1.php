<?php
include "/var/www/html/enkargo/config/conex_manager.php";
include '/var/www/html/enkargo/config/pdo_connector.php';
include "/var/www/html/enkargo/config/meli.php";

$conn        = pg_connect('host=185.44.66.53 port=5432 dbname=enkargo user=u_enkargo password=#enkargo#');

$application = pg_query($conn, "SELECT * FROM meli.shop WHERE id = '2';");
$application_det = pg_fetch_object($application);
$meli            = new Meli($application_det->application_id, $application_det->secret_key, $application_det->access_token, $application_det->refresh_access_token);

$result_items_id = array();
$order=array('last_updated_desc');#'stop_time_asc','stop_time_desc','start_time_asc','start_time_desc','available_quantity_asc','available_quantity_desc','sold_quantity_asc','sold_quantity_desc','price_asc','price_desc','last_updated_desc','last_updated_asc','total_sold_quantity_asc','stdClass Object','total_sold_quantity_desc','stdClass Object','inventory_id_asc');

$params_items_list = array(
		'search_type'  => "scan",
		'access_token' => $application_det->access_token,
		'status'	   => "active",
		'labels'	   => "with_bids",
		'orders' 	   => "sold_quantity_desc",#last_updated_desc
	);

$scroll = $meli->get('/users/'.$application_det->user_name.'/items/search', $params_items_list);

echo "INICIO - ".date("Y-m-d H:i:s")." - total de MPID: ".$scroll['body']->paging->total."\n";

$offset          = 0;
$i               = 0;

while ($offset < 1000 ){ # $scroll['body']->paging->total ) {
echo $offset."\n";
	$params_items = array(
		#'search_type'  		=> "scan",
		'access_token' 		=> $application_det->access_token,
		#'scroll_id'    		=> $scroll['body']->scroll_id,
		'status'	   		=> "active",
		'labels'		   	=> "with_bids",
		'orders' 	   		=> "sold_quantity_desc",
		#'offset'			=> $offset,
		#'tags' 	   	    => "gold_special"
	);

	$result_items = $meli->get('/users/'.$application_det->user_name.'/items/search', $params_items);
	#print_r($result_items);die();
	$nuevos ="";
	$actualizados ="";
	#if($offset >41200){
	foreach ($result_items['body']->results as $mpid) {
		$query_mpid = pg_query($conn, "SELECT mpid FROM meli.items WHERE mpid = '".$mpid."';");
		$mpid_id    = pg_fetch_object($query_mpid);
		if (!isset($mpid_id->mpid)) {
			$nuevos .="('".$mpid."','2','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."',''),";
			#pg_query($conn, "INSERT INTO m_checkstatus(conn, identifier)eli.items(mpid, shop_id, create_date, update_date,status) VALUES ('".$mpid."','1','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."','sin_description')");
			echo $i." Insertado - ".$mpid." - ".date("Y-m-d H:i:s")."- OFFSET-".$offset."\n";
		} else {
			$actualizados .="'".$mpid."',";
			echo $i."- Actualizado - ".$mpid." - ".date("Y-m-d H:i:s")."-OFFSET-".$offset."\n";
			#pg_query($conn, "UPDATE meli.items set status='con_ventas' where mpid ='".$mpid."';");
		}
		$i++;

	}
	#}
	$nuevos 		= substr($nuevos, 0, -1);
	$actualizados 	= substr($actualizados, 0, -1);
	if($nuevos <> ""){
		pg_query($conn, "INSERT INTO meli.items(mpid, shop_id, create_date, update_date,status) VALUES ".$nuevos.";");
	}
	if($actualizados <> ""){
		pg_query($conn, "UPDATE meli.items set shop_id = 2,status='con_ventas' where mpid in (".$actualizados.");");
	}

	$offset += 50;
	
	$application = pg_query($conn, "SELECT * FROM meli.shop WHERE id = '2';");
	$application_det = pg_fetch_object($application);
	$meli            = new Meli($application_det->application_id, $application_det->secret_key, $application_det->access_token, $application_det->refresh_access_token);
}
#}
