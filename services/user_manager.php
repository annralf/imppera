<?php
include '../config/conex_manager.php';
$conn = new Connect();
if ($_POST['action'] == 'save_user') {
	$img_load    = $img['name'];
	$createQuery = "INSERT INTO system.users(name, last_name, id_card, user_name, password, user_type, create_date, update_date)VALUES ('".$_POST['name']."', '".$_POST['lastname']."', '".$_POST['idCard']."', '".$_POST['userName']."', '".$_POST['password']."', 
     '".$_POST['shop']."', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."');";
	try {
		pg_query($createQuery);
		echo 1;
	} catch (Exception $e) {
		echo 0;
	}
}
if ($_POST['action'] == 'update_user') {
	$user      = pg_fetch_object(pg_query("SELECT u.*, shop.name as shop_name FROM system.users AS u INNER JOIN system.shop as shop ON shop.id = u.shop WHERE u.id = '".$_POST['id']."';"));
	$name      = ($_POST['name'] != $user->name)?$_POST['name']:$user->name;
	$last_name = ($_POST['lastname'] != $user->last_name)?$_POST['lastname']:$user->last_name;
	$id_card   = ($_POST['idCard'] != $user->id_card)?$_POST['idCard']:$user->id_card;
	$user_name = ($_POST['userName'] != $user->user_name)?$_POST['userName']:$user->user_name;
	$password  = ($_POST['password'] != $user->password)?$_POST['password']:$user->password;
	$shop      = ($_POST['shop'] != $user->shop)?$_POST['shop']:$user->shop;
	$date      = date('Y-m-d H:i:s');
	try {
		$update_sql = "UPDATE system.users  SET name='".$name."', last_name='".$last_name."', id_card='".$id_card."', user_name='".$user_name."', password='".$password."', shop='".$shop."', update_date='".$date."' WHERE id='".$_POST['id']."';";
		pg_query($update_sql);
		echo 1;
	} catch (Exception $e) {
		echo 0;
	}

}
if ($_POST['action'] == 'delete_user') {
	try {
		pg_query("DELETE FROM system.users WHERE id='".$_POST['id']."';");
		echo 1;
	} catch (Exception $e) {
		echo 0;
	}

}
if ($_POST['action'] == 'get_user') {
	try {
		$user = pg_fetch_object(pg_query("SELECT u.* FROM system.users AS u WHERE u.user_name = '".$_POST['userName']."' AND u.password = '".$_POST['password']."';"));
		if ($user) {
			    $activity = "Inicio de Sesión";
			    $date = date("Y/m/d H:i:s");
			    $activity = $activity." Fecha:$date";
			    $sql = "insert into system.log(user_id, activity, date) values($user->id, '$activity','$date');";
			    pg_query($sql);
			echo json_encode($user);
		} else {
			echo 2;
		}
	} catch (Exception $e) {
		echo 0;
	}

}

if ($_POST['action'] == 'logout_user') {
    #----------------- USER LOG -----------------
    $user_id = $_POST['user_id'];
    $activity = "Cerrar Sesión";
    $date = date("Y/m/d H:i:s");
    $activity = $activity." Fecha:$date";
    $sql = "insert into system.log(user_id, activity, date) values($user_id, '$activity','$date');";
    pg_query($sql);
}