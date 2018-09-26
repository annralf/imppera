<?php
include '/var/www/html/enkargo/services/meli_update_logic.php';
include_once "/var/www/html/enkargo/services/aws_update_action.php";


if ($_POST['action'] == 'update_mpid_mx') {
	$resp='';
	$mpid 	= "MCO".$_POST['mpid_mx'];

	$update_var  = new aws_update('AKIAJIM77WK37THIDD2A','iTSOXDgktw7Kwk3pvDcdcfmt0aePp9TTpAnG0OPg','alexarodri-20');
	$update_var->execute_update("select id,upper(sku) as sku from aws.items where id in (select aws_id from meli.items where mpid='".$mpid."');","massive");
	$create = new MeliUpdate(2,'unique',null);
	$resp = $create->update($mpid,null);
	echo json_encode(array("response"=>$resp));	
	#$create->consulta('MCO451462704');
}
if ($_POST['action'] == 'paused_mpid_mx') {
	$mpid 	= "MCO".$_POST['mpid_mx'];
	$create = new MeliUpdate(2,'unique',null);
	$resp = $create->update($mpid,2);
	echo json_encode(array('response'=>$resp));		
	#$create->consulta('MCO451462704');
}
if ($_POST['action'] == 'closed_mpid_mx') {
	$mpid 	= "MCO".$_POST['mpid_mx'];
	$create = new MeliUpdate(2,'unique',null);
	$resp = $create->update($mpid,4);
	echo json_encode(array('response'=>$resp));		
	#$create->consulta('MCO451462704');
}
if ($_POST['action'] == 'delete_mpid_mx') {
	$mpid 	= "MCO".$_POST['mpid_mx'];
	$create = new MeliUpdate(2,'unique',null);
	$resp = $create->update($mpid,1);
	echo json_encode(array('response'=>$resp));		
	#$create->consulta('MCO451462704');
}
if ($_POST['action'] == 'update_mpid_qb') {
	$resp='';
	$mpid 	= "MCO".$_POST['mpid_qb'];

	$update_var  = new aws_update('AKIAJIM77WK37THIDD2A','iTSOXDgktw7Kwk3pvDcdcfmt0aePp9TTpAnG0OPg','alexarodri-20');
	$update_var->execute_update("select id,upper(sku) as sku from aws.items where id in (select aws_id from meli.items where mpid='".$mpid."');","massive");
	
	$create = new MeliUpdate(1,'unique',null);
	$resp = $create->update($mpid,null);
	echo json_encode(array("response"=>$resp));	
	#$create->consulta('MCO451462704');
}
if ($_POST['action'] == 'paused_mpid_qb') {
	$mpid 	= "MCO".$_POST['mpid_qb'];
	$create = new MeliUpdate(1,'unique',null);
	$resp = $create->update($mpid,2);
	echo json_encode(array('response'=>$resp));		
	#$create->consulta('MCO451462704');
}
if ($_POST['action'] == 'closed_mpid_qb') {
	$mpid 	= "MCO".$_POST['mpid_qb'];
	$create = new MeliUpdate(1,'unique',null);
	$resp = $create->update($mpid,4);
	echo json_encode(array('response'=>$resp));		
	#$create->consulta('MCO451462704');
}
if ($_POST['action'] == 'delete_mpid_qb') {
	$mpid 	= "MCO".$_POST['mpid_qb'];
	$create = new MeliUpdate(1,'unique',null);
	$resp = $create->update($mpid,1);
	echo json_encode(array('response'=>$resp));		
	#$create->consulta('MCO451462704');
}


#$create = new MeliUpdate(1,'unique',null);
#$create->update('MCO479701557',null);
#$create->consulta('MCO473233128');