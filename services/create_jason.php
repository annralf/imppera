<?php
include_once '/var/www/html/enkargo/config/pdo_connector.php';
/*#
/**
* 
*/
class aws_json {
	public $conn;
	public $k;
	public $items;

	function __construct()
	{
		$this->conn = new DataBase();
		$this->k    = 0;
	#**************************************************** Main *******************************************************************
	}
	
	function execute_json($sentence){
		echo "Json process Begin-".date("Y-m-d H:i:s")."*********************************\n";
		#SQL sentence to items update at aws.items table
		$query = $this->conn->prepare($sentence);
		$query->execute();
		#$this->conn->close_con();
		$this->k = 0;		
		$data=array();		
		foreach ($query->fetchAll() as $result) {
			$this->k++;
			$id_aws = $result['id'];
			$sku 	= $result['sku'];

			$json = array('id' => $id_aws,'sku'=>$sku);
			array_push($data, $json);

			echo $this->k." - ".$sku.".json creado \n";
		}
		$jsonencoded = json_encode($data,JSON_UNESCAPED_UNICODE);

		$fh = fopen("/var/www/html/enkargo/json/sku.json", 'w');
		fwrite($fh, $jsonencoded);
		fclose($fh);	

		$this->conn->close_con();

		echo "end - ".date("Y-m-d H:i:s")."-----------------\n";
	}
}

$update_var = new aws_json();
$update_var->execute_json("select id,sku from aws.items where bolborrado not in (1);");
