<?php
/*Set Meli categories to aws new items*/
include '/var/www/html/enkargo/config/meli_items.php';
include '/var/www/html/enkargo/config/conex_manager.php';
/**
* 
*/
class MeliCategory
{
	public $conn;
	public $meli;
	public $translate;
	function __construct()
	{
		$this->conn = new DataBase();
		$this->meli = new items(null);
		$this->translate = new GoogleTranslate();
	}

	function set($limit){
		$padre=

		$sql = "select a.id, a.category_p, a.product_category, c.padre_id_meli from aws.items as a 
		join aws.category as c on c.product_type = a.category_p where a.category_p is not null 
		and a.category_p is not null and category_meli ='' and a.shop_ml_mx <> 1 and a.shop_ml_qb <> 1 limit '".$limit."';";
		$items = $this->conn->prepare($sql);
		$items->execute();
		$items = $items->fetchAll();
		$i= 0;
		foreach ($items as $value) {
			#$category = $this->meli->category_match_aws($value['product_category'],$value['padre_id_meli']);
			$this->conn->exec("update aws.items set category_meli ='".$value['padre_id_meli']."', update_date = '".date('Y-m-d H:i:s')."' where id = '".$value['id']."';");
			echo $i."-".$value['id'].date('Y-m-d H:i:s')."-".$value['padre_id_meli']."\n";
			$i++;
		}
		$this->conn->close_con();
		echo "Fin -".date('Y-m-d H:i:s')."\n";
	}
}
#Testing
/*
$test = new MeliCategory();
echo "Inicio-".date('Y-m-d H:i:s')."\n";
$test->set(10);
*/