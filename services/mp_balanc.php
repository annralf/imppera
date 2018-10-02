<?php
include '/var/www/html/enkargo/config/mp_items.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';
/*
 * 
*/
class MPbalance
{
	public $shop;
	public $conn;
	public $mepa;
	
	public $conn_sql;
	function __construct($shop)
	{
		$this->conn = new DataBase();
		$application = $this->conn->prepare("select * from meli.shop where id = '".$shop."';");
		$application->execute();
		$this->shop = $application->fetchAll();
		$this->mepa = new MePa($this->shop[0]['access_token']);	
		//$this->mepa = new MePa($this->shop[0]['application_id'],$this->shop[0]['secret_key']);	
	}

	function balance($alias){
		echo "Inicio -".date("Y-m-d H:i:s")."******************************\n";
		$detail_items 	= $this->mp->balanc($this->shop[0]['user_name']);
		print_r($detail_items);
		$this->conn->close_con();
		echo "Fin update -".date("Y-m-d H:i:s")."******************************\n";
	}

	function gen_report(){
		echo "Inicio -".date("Y-m-d H:i:s")."******************************\n";
		$detail_items 	= $this->mepa->report();
		print_r($detail_items);
		$this->conn->close_con();
		echo "Fin update -".date("Y-m-d H:i:s")."******************************\n";
	}

	function list_report(){
		echo "Inicio -".date("Y-m-d H:i:s")."******************************\n";
		$detail_items 	= $this->mepa->ver_report();
		print_r($detail_items);
		$this->conn->close_con();

		echo "Fin update -".date("Y-m-d H:i:s")."******************************\n";
	}

	function print_report(){
		echo "Inicio -".date("Y-m-d H:i:s")."******************************\n";
		$detail_items 	= $this->mepa->ver_report();
		$result = $detail_items['response'];
		$detail_items 	= $this->mepa->get_url_print($result[0]['file_name']);
		echo $detail_items;
		$this->conn->close_con();

		echo "\nFin update -".date("Y-m-d H:i:s")."******************************\n";
	}

	function pay($alias){
		echo "Inicio -".date("Y-m-d H:i:s")."******************************\n";
		$detail_items 	= $this->mepa->payment();
		print_r($detail_items);
		$this->conn->close_con();

		echo "Fin update -".date("Y-m-d H:i:s")."******************************\n";
	}
	
	function pay_by_id($alias,$id_order){
		$detail_items 	= $this->mepa->payment_by_id($alias);
		//print_r($detail_items);die();
		$id_payment = $detail_items->id;
		$date_created = $detail_items->date_created;
		$date_approved = $detail_items->date_approved;
		$date_last_updated = $detail_items->date_last_updated;
		$money_release_date = $detail_items->money_release_date;  
		$title = str_replace('\'','\'\'',$detail_items->description);
		$id_order = $id_order;
		$cuotas = $detail_items->installments;
		$id_payer = $detail_items->payer->id;
		$payment_method  = $detail_items->payment_method_id;
		$paymnt_type = $detail_items->payment_type_id;
		$shipping_amount  = $detail_items->shipping_amount;
		$status  	= $detail_items->status;
		$status_detail  = $detail_items->status_detail;
		$transaction_amount  = $detail_items->transaction_amount; #monto de la transaccion
		$transaction_amount_refund  = $detail_items->transaction_amount_refunded; #monto rechazado
		$net_amount  = $detail_items->transaction_details->net_received_amount; # neto recibido
		$total_paid_amount = $detail_items->transaction_details->total_paid_amount; #monto total pagado
		$fee = $detail_items->fee_details[0]->amount; #porcentaje cobrado por ml


		if ($status== 'cancelled' || $status== 'rejected'){
			$sql="INSERT into system.mp (date_created,date_last_updated,title,id_order,cuotas,id_payer,payment_method,paymnt_type,shipping_amount,status,status_detail,transaction_amount,transaction_amount_refund,net_amount,total_paid_amount,fee_amount) values ('$date_created','$date_last_updated','$title','$id_order','$cuotas', '$id_payer', '$payment_method',  '$paymnt_type', '$shipping_amount',  '$status',  	'$status_detail',  '$transaction_amount',  '$transaction_amount_refund', '$net_amount',  '$total_paid_amount', '$fee' );";
			$application = $this->conn->prepare($sql);
			$application->execute();
			echo "\t".date("Y-m-d H:i:s")." - Pago Cancelado o Rechazado ".$alias." - ".$detail_items->status."\n";
		}else{
			$sql="INSERT into system.mp (date_created,date_approved,date_last_updated,money_release_date,title,id_order,cuotas,id_payer,payment_method,paymnt_type,shipping_amount,status,status_detail,transaction_amount,transaction_amount_refund,net_amount,total_paid_amount,fee_amount) values ('$date_created','$date_approved', '$date_last_updated', '$money_release_date','$title','$id_order','$cuotas', '$id_payer', '$payment_method',  '$paymnt_type', '$shipping_amount',  '$status',  	'$status_detail',  '$transaction_amount',  '$transaction_amount_refund', '$net_amount',  '$total_paid_amount', '$fee' );";
			$application = $this->conn->prepare($sql);
			$application->execute();
			echo "\t".date("Y-m-d H:i:s")." - Insertado - ".$alias." - ".$detail_items->status."\n";
		}	
	}

	function check_pay_by_id($alias,$id_pay,$status){
		$detail_items 	= $this->mepa->payment_by_id($alias);
		$id_payment = $detail_items->id;
		$status_a  	= $detail_items->status;
		$status_detail  = $detail_items->status_detail;
		
		if ($status <> $status){
			$sql="UPDATE system.mp set status='$status_a' where id=$id_pay;";
			$application = $this->conn->prepare($sql);
			$application->execute();
			echo "\t".date("Y-m-d H:i:s")." - Pago Actualizado - ".$alias." - ".$detail_items->status."\n";
		}else{
			echo "\t".date("Y-m-d H:i:s")." - Pago sin cambios - ".$alias." - ".$detail_items->status."\n";
		}	
	}
	
}







#Test section
/*$test = new MPbalance(1);
$conn = new DataBase();
//$test->balance(1000);
//$test->list_report();


$sql="select id,id_payments from system.orders where shop_id=1 and id not in (select id_order from system.mp);";
$item = $conn->prepare($sql);
$item->execute();
$item = $item->fetchAll();

$i=1;
foreach ($item as $items) {
	echo $i;
	$valor = $test->pay_by_id($items['id_payments'],$items['id']);
	$i++;
}
//$test->list_report();
//$test->print_report();*/