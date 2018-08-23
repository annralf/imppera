<?php

include "/var/www/html/enkargo/services/aws_update_action.php";
include_once '/var/www/html/enkargo/services/meli_create_by_id.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';

if ($_POST['action'] == "publicar_mx") {
	$shop_id 	= $_POST['shop_id']; 
	$category 	= $_POST['category_mx'];
	$sku 		= $_POST['sku2_mx'];
	$color		= $_POST['color_mx'];
	$talla		= $_POST['talla_mx'];
	$orders 	= array();
	$conn = new DataBase();


	$sql="INSERT INTO aws.items (sku, bolborrado, category_meli, color_p_meli, size_meli) VALUES ('".$sku."', '16', '".$category."', '".$color."', '".$talla."') RETURNING id;";
	$result = $conn->prepare($sql);
	$result->execute();
	$result = $result->fetchAll();
	$id_sku = $result[0]['id'];

	if ($id_sku>0) {
		$update_var  = new aws_update('AKIAI3H6L6IHLGZ7VXWA','xJi2wZ/sxg3nBvD0dBnvyO5DyTdizPDjRnqXwq3u','Santiespi2000-20');
		$update = $update_var->execute_update("select id,upper(sku) as sku from aws.items where id =".$id_sku." ;","massive");
		if($update=="create"){
			$create = new meliGet();
			$index = $create->createItems(2,$id_sku);

			if($index=="category_null"){
				echo json_encode(array('response'=>0));
				
			}else if($index=="no_token"){
				echo json_encode(array('response'=>1));
				
			}else if($index=="error"){
				echo json_encode(array('response'=>2));
				
			}else if($index=="daily_quota"){
				echo json_encode(array('response'=>3));
				
			}else if($index=="not_create"){
				echo json_encode(array('response'=>4));
				
			}else if($index==null){
				echo json_encode(array('response'=>8));
				
			}else{
				echo json_encode($index);
			}
		}
		if($update=="no_create_1"){
			echo json_encode(array('response'=>5));
		}
		if($update=="no_create_2"){
			echo json_encode(array('response'=>6));
		}
	}else{
		echo json_encode(array('response'=>7));		
	}
}

if ($_POST['action'] == "publicar_qb") {
	$shop_id 	= $_POST['shop_id']; 
	$category 	= $_POST['category_qb'];
	$sku 		= $_POST['sku2_qb'];
	$color		= $_POST['color_qb'];
	$talla		= $_POST['talla_qb'];
	$orders 	= array();
	$conn = new DataBase();


	$sql="INSERT INTO aws.items (sku, bolborrado, category_meli, color_p_meli, size_meli) VALUES ('".$sku."', '16', '".$category."', '".$color."', '".$talla."') RETURNING id;";
	$result = $conn->prepare($sql);
	$result->execute();
	$result = $result->fetchAll();
	$id_sku = $result[0]['id'];

	if ($id_sku>0) {
		$update_var  = new aws_update('AKIAI3H6L6IHLGZ7VXWA','xJi2wZ/sxg3nBvD0dBnvyO5DyTdizPDjRnqXwq3u','Santiespi2000-20');
		$update = $update_var->execute_update("select id,upper(sku) as sku from aws.items where id =".$id_sku." ;","massive");
		if($update=="create"){
			$create = new meliGet();
			$index = $create->createItems(1,$id_sku);

			if($index=="category_null"){
				echo json_encode(array('response'=>0));
				
			}else if($index=="no_token"){
				echo json_encode(array('response'=>1));
				
			}else if($index=="error"){
				echo json_encode(array('response'=>2));
				
			}else if($index=="daily_quota"){
				echo json_encode(array('response'=>3));
				
			}else if($index=="not_create"){
				echo json_encode(array('response'=>4));
				
			}else if($index==null){
				echo json_encode(array('response'=>8));
				
			}else{
				echo json_encode($index);
			}
		}
		if($update=="no_create_1"){
			echo json_encode(array('response'=>5));
		}
		if($update=="no_create_2"){
			echo json_encode(array('response'=>6));
		}
	}else{
		echo json_encode(array('response'=>7));		
	}
}


