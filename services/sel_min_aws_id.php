<?php
/*Testing Update functions unsincronous*/
include '/var/www/html/enkargo/config/meli_items.php';
include '/var/www/html/enkargo/config/aws_item.php';
include '/var/www/html/enkargo/config/conex_manager.php';
/**
* 
*/
class Meliup_aws_id
{
	public $shop;
	public $conn;
	public $meli;
	function __construct($shop)
	{	
		$this->conn = new DataBase();
		$application = $this->conn->prepare("select * from meli.shop where id = '".$shop."';");
		$application->execute();
		$this->shop = $application->fetchAll();
		$this->meli = new items($this->shop[0]['access_token']);
	}

	function orders(){

		echo "#### inicio de update ####";

			$sql = "SELECT m.id,m.aws_id,a.sku FROM meli.items as m join aws.items as a on a.id =m.aws_id WHERE m.shop_id='".$this->shop[0]['id']."' and m.aws_id is not null and a.sku in (select sku from aws.items group by sku having count(*)>1);";
			$order_exist = $this->conn->prepare($sql);
			$order_exist->execute();
			#$order_exist = $order_exist->fetchObject();
			$n=1;
			foreach ($order_exist as $valor) {

				$aws_id	=$valor['aws_id'];
				$sku	=$valor['sku'];
				$id     =$valor['id'];

				$sql2 = "SELECT min(id) as id FROM aws.items WHERE sku='".$sku."';";
				$min_sku = $this->conn->prepare($sql2);
				$min_sku->execute();
				$min_sku = $min_sku->fetchObject();

				$upd =	"UPDATE meli.items set aws_id ='".$min_sku->id."' where id =".$id.";";

				$this->conn->exec($upd);
				echo $n."-\taws_id-".$aws_id."-->".$min_sku->id."\n";
				$n++;
			}
		$this->conn->close_con();
	}
}
#Test section
$test = new Meliup_aws_id(1);
$test->orders();