<?php
/*Testing Update functions unsincronous*/
include '/var/www/html/enkargo/config/meli_items.php';
include '/var/www/html/enkargo/config/conex_manager.php';
/**
* 
*/
class MeliUpdate
{
	public $shop;
	public $type;
	public $conn;
	public $meli;
	public $translate;
	public $conn_sql;
	function __construct($shop, $type)
	{
		$this->conn = new DataBase();
		$application = $this->conn->prepare("select * from meli.shop where id = '".$shop."';");
		$application->execute();
		$this->shop = $application->fetchAll();
		$this->meli = new items($this->shop[0]['access_token']);
		$this->type = $type;
		$this->translate = new GoogleTranslate();
	}

	function update($alias){
		echo "Update process Begin-".date("Y-m-d H:i:s")."*********************************\n";
		$updated_items = "";
		$description = "";
		$i = 0;
		switch ($this->shop[0]['id']) {
			case '1':
				$id_seq		 = 4;
				$warranty 	 = "*** TRADUCCIÓN AUTOMÁTICA, VERIFICAR CARACTERISTICAS ANTES DE COMPRAR*** Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente. No nos hacemos responsables de los costos de envío en garantías NO APLICA GARANTIA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA";
				$shop_ml	 = "shop_ml_qb";

				$name_shop 	= "&#8474;UEEN BEE\n\n";
				$description .= "&#11088; ESTE ARTICULO ES IMPORTADO DESDE USA";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#9989;  MÉTODOS DE ENVÍO\n";
				$description .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
				$description .= "\n";
				$description .= "Recuerda que los métodos de envío cambian según la categoría de la publicación, los envíos gratis y a cobro varían según los siguientes parámetros:";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10102; Si esta publicación está marcada con “Envío Gratis” este se enviará totalmente gratis en compras superiores a $70.000 según el convenio de Mercado Envíos";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10103; Si esta publicación está marcada como “Acordar con el Vendedor”, el costo del envío no esta incluido, así que este valor se cobrara a Contra Entrega, (Los costos pueden variar según la ciudad, el peso y volumen del producto).";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10104; Al elegir el “Retiro en Domicilio” no acelera el proceso de entrega, ya que igual el producto debe ser importado desde los Estados Unidos";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#9989;  GARANTÍA \n";
				$description .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
				$description .= "\n";
				$description .= "* Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente.";
				$description .= "\n";
				$description .= "\n"; 
				$description .= "* No nos hacemos responsables de los costos de envío en garantías.";
				$description .= "\n";
				$description .= "\n";
				$description .= "* NO APLICA GARANTÍA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA, ESTA EMPRESA ES QUIEN DEBERA ENCARGARSE DE RESPONDER POR EL VALOR DEL ARTICULO QUE HA SIDO AFECTADO.";
				$description .= "\n";
				$description .= "\n";
				$description .= "* Recuerda que somos **Queen Bee Tienda Online**";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#9989;  INFORMACIÓN IMPORTANTE\n";
				$description .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10102; Recuerda que antes de comprar, puedes hacer uso de la herramienta de preguntas, en donde un asesor te liberara todas tus dudas y así puedas estar totalmente confiado de lo que estas comprando.";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10103; Vale la pena recordarte que al ser un producto importado y al ser distribuido a varios países en Latinoamérica, la disponibilidad del producto puede variar, al igual que las tallas y colores están sujetas a la disponibilidad, en algunos casos puede que el valor del producto por cuestiones de tamaño, color o proveedor pueda Cambiar.";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10104; Por favor indícanos tu talla, color o Referencia por medio de las preguntas para confirmarte la Disponibilidad de la misma antes de que realices la compra. También una vez comprado el producto puedes confirmar esta información por medio de los mensajes de la plataforma. Recuerda que el precio publicado pertenece a la talla y color descritos en la ficha tecnica. Otras tallas y colores pueden llegar a variar en su precio.";
				$description .= "\n";
				$description .= "\n";
				$video_shop  = 4;
				break;
			case '2':
				$id_seq		  = 5;
				$warranty 	  = "*** TRADUCCIÓN AUTOMÁTICA, VERIFICAR CARACTERISTICAS ANTES DE COMPRAR*** Este es un artículo importado, tiene una garantía de 30 días por defectos de fábrica, la misma no incluye cualquier daño ocasionado por la empresa transportadora. Mauxi eshop no se hace responsable de los costos de envío del producto en caso de aplicar la garantía";
				$shop_ml	  = "shop_ml_mx";
				$name_shop 	= "&#x2133;auxi\n\n";
				$description .= "&#11088; ESTE ARTICULO ES IMPORTADO DESDE USA";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#9989;  MÉTODOS DE ENVÍO\n";
				$description .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
				$description .= "\n";
				$description .= "Recuerda que los métodos de envío cambian según la categoría de la publicación, los envíos gratis y a cobro varían según los siguientes parámetros:";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10102; Si esta publicación está marcada con “Envío Gratis” este se enviará totalmente gratis en compras superiores a $70.000 según el convenio de Mercado Envíos";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10103; Si esta publicación está marcada como “Acordar con el Vendedor”, el costo del envío no esta incluido, así que este valor se cobrara a Contra Entrega, (Los costos pueden variar según la ciudad, el peso y volumen del producto).";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10104; Al elegir el “Retiro en Domicilio” no acelera el proceso de entrega, ya que igual el producto debe ser importado desde los Estados Unidos";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#9989;  GARANTÍA \n";
				$description .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
				$description .= "\n";
				$description .= "* Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente.";
				$description .= "\n";
				$description .= "\n"; 
				$description .= "* No nos hacemos responsables de los costos de envío en garantías.";
				$description .= "\n";
				$description .= "\n";
				$description .= "* NO APLICA GARANTÍA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA, ESTA EMPRESA ES QUIEN DEBERA ENCARGARSE DE RESPONDER POR EL VALOR DEL ARTICULO QUE HA SIDO AFECTADO.";
				$description .= "\n";
				$description .= "\n";
				$description .= "* Recuerda que somos **Mauxi E-Shop**";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#9989;  INFORMACIÓN IMPORTANTE\n";
				$description .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10102; Recuerda que antes de comprar, puedes hacer uso de la herramienta de preguntas, en donde un asesor te liberara todas tus dudas y así puedas estar totalmente confiado de lo que estas comprando.";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10103; Vale la pena recordarte que al ser un producto importado y al ser distribuido a varios países en Latinoamérica, la disponibilidad del producto puede variar, al igual que las tallas y colores están sujetas a la disponibilidad, en algunos casos puede que el valor del producto por cuestiones de tamaño, color o proveedor pueda Cambiar.";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10104; Por favor indícanos tu talla, color o Referencia por medio de las preguntas para confirmarte la Disponibilidad de la misma antes de que realices la compra. También una vez comprado el producto puedes confirmar esta información por medio de los mensajes de la plataforma. Recuerda que el precio publicado pertenece a la talla y color descritos en la ficha tecnica. Otras tallas y colores pueden llegar a variar en su precio.";
				$description .= "\n";
				$description .= "\n";
				$video_shop = 5;
				break;
		}
		switch ($this->type) {
			case 'unique':
				$meli_item = $this->conn->prepare("select mpid from meli.items where mpid = '".$alias."';");
				$meli_item->execute();
				$meli_item = $meli_item->fetchAll();

				if(empty($meli_item)){
					echo "no posee sku asociado en Data Base\n";
					die();
				}

				$sql ="SELECT a.id, a.sku, a.image_url,a.quantity, a.active, round(cast((a.package_height/0.393701) as numeric),2) as package_height, round(cast((a.item_width/0.393701) as numeric),2) as item_width, round(cast((a.package_length/0.393701) as numeric),2) as package_length, round(cast((a.item_height/0.393701) as numeric),2) as item_height, round(cast((a.item_length/0.393701) as numeric),2) as item_length, a.product_title_english, a.specification_english, a.brand, round(cast((a.package_weight/2.204623)/100 as numeric),2) as package_weight, a.sale_price, a.bolborrado as aws_bolborrado, a.brand, a.model, a.weight_unit, m.mpid, m.bolborrado as meli_bolborrado, a.is_prime as prime, a.clothingsize, a.color, a.avaliable	from aws.items as a join meli.items as m on a.id=m.aws_id where m.shop_id =  '".$this->shop[0]['id']."' and a.id = '".$meli_item[0]['aws_id']."';";

				$item = $this->conn->prepare($sql);
				$item->execute();
				$item = $item->fetchAll();
				#$this->conn->close_con();
				break;
			case 'massive':
				$secuence = $this->conn->prepare("SELECT * FROM meli.secuences WHERE id = '".$id_seq."';");
				$secuence->execute();
				$secuence        = $secuence->fetchAll();
				$offset          = $secuence[0]['offset_']+$alias;
				$meli_offset     = $secuence[0]['offset_'];
				$meli_limit      = $alias;
				$this->conn->exec("UPDATE meli.secuences SET offset_ ='".$offset."' WHERE id = '".$id_seq."';");

				#$sql="SELECT a.id, a.sku, a.image_url,a.quantity, a.active, round(cast((a.package_height/0.393701) as numeric),2) as package_height, round(cast((a.item_width/0.393701) as numeric),2) as item_width, round(cast((a.package_length/0.393701) as numeric),2) as package_length, round(cast((a.item_height/0.393701) as numeric),2) as item_height, round(cast((a.item_length/0.393701) as numeric),2) as item_length, a.product_title_english, a.specification_english, a.brand, round(cast((a.package_weight/2.204623)/100 as numeric),2) as package_weight, a.sale_price, a.bolborrado as aws_bolborrado, a.brand, a.model, a.weight_unit, m.mpid, m.bolborrado as meli_bolborrado, a.is_prime as prime, a.clothingsize, a.color	from aws.items as a join meli.items as m on a.id=m.aws_id where m.shop_id =  '".$this->shop[0]['id']."' and m.mpid='MCO448410084';";

				$sql = "SELECT a.id, a.sku, a.image_url,a.quantity, a.active, round(cast((a.package_height/0.393701) as numeric),2) as package_height, round(cast((a.item_width/0.393701) as numeric),2) as item_width, round(cast((a.package_length/0.393701) as numeric),2) as package_length, round(cast((a.item_height/0.393701) as numeric),2) as item_height, round(cast((a.item_length/0.393701) as numeric),2) as item_length, a.product_title_english, a.specification_english, a.brand, round(cast((a.package_weight/2.204623)/100 as numeric),2) as package_weight, a.sale_price, a.bolborrado as aws_bolborrado, a.brand, a.model, a.weight_unit, m.mpid, m.bolborrado as meli_bolborrado, a.is_prime as prime, a.clothingsize, a.color, a.avaliable from aws.items as a join meli.items as m on a.id=m.aws_id where m.shop_id =  '".$this->shop[0]['id']."' and a.avaliable is not null ORDER BY m.update_date asc offset '".$meli_offset."' limit '".$meli_limit."';";
				$item = $this->conn->prepare($sql);
				$item->execute();
				$item = $item->fetchAll();
				#$this->conn->close_con();
				break;	
		}
		$video = $this->conn->prepare("select * from meli.video where shop_id = '".$this->shop[0]['id']."' and id = '".$video_shop."';");
		$video->execute();
		$video = $video->fetchAll();
		$update_array = array();
		unset($update_array);
		$update_array['shipping']        = array('mode' => 'me2', 'local_pick_up' => true, 'free_shipping' => false);

		$reseteo_array['shipping']        = array('mode' => 'me1', 'local_pick_up' => true, 'free_shipping' => false);

		$update_array['warranty'] = $warranty;
		$campo_id	 		=(string)" when 'DEFAULT' then 'DEFAULT' ";
		$campo_up_dat 		=(string)" when 'DEFAULT' then CURRENT_TIMESTAMP(0) ";
		$campo_price		=(string)" when 'DEFAULT' then 0 ";
		$campo_bolborrado	=(string)" when 'DEFAULT' then 0 ";
		$campo_status		=(string)" when 'DEFAULT' then 'N/A' ";
		$total_mpids		='';
		foreach ($item as $items) {
			$mpid         		= $items['mpid'];
			$updated_items 		.= "'".$mpid."',";
			$item_description 	= "";
			$avaliable_d 	= trim($items['avaliable']);
			$images      	= explode("~^~", $items['image_url']);
			$img_cant    	= count($images);
			$item_img   	= array();
			if (count($images) > 8) {
				$img_cant 	= 7;
			}
			array_push($item_img, array('source' => $images[0]));
			switch ($this->shop[0]['id']) {
				case '1':
					if($items['prime']==1 and $avaliable_d == 1){
						array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/QB4-8.png'));
					}
					if($items['prime']==0 and $avaliable_d == 1){
						array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/QB7-10.png'));
					}
					if($avaliable_d == 2){
						array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/QB10-15.png'));
					}
					break;
				case '2':
					if($items['prime']==1 and $avaliable_d == 1){
						array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/Mauxi4-8.png'));
					}
					if($items['prime']==0 and $avaliable_d == 1){
						array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/Mauxi7-10.png'));
					}
					if($avaliable_d == 2){
						array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/Mauxi10-15.png'));
					}
					break;
			}
			for ($j = 1; $j < $img_cant; $j++) {
				array_push($item_img, array('source' => $images[$j]));
			}
			$description_esp = eliminar_simbolos($items['specification_english']);
			if (strlen($description_esp) >= 4900) {
				$pos   = strpos($description_esp, ' ', 4900);
				$description_esp = substr($description_esp, 0, $pos);
			}
			$description_esp = $this->translate->translate('en', 'es', $description_esp);
				


			$detail_items 		= $this->meli->show($mpid);
			if (isset($detail_items->error)) {
				echo $i++ ."\t- Error de conexion ".$detail_items->message." - ".$mpid."\n";
				$this->conn->exec("DELETE from meli.items where mpid='".$mpid."' and shop_id='".$this->shop[0]['id']."';");
			}else{

				if(isset($detail_items[0]->id)){
					$item_status = $detail_items[0]->status;
					switch ($items['meli_bolborrado']) {
						case 1:
							echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Bolborrado 1 MELI\n";
							$this->meli->delete_item($item_status, $mpid, null);
							$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
							$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
							$campo_price		.=(string)" when '".$mpid."' then 0 ";
							$campo_bolborrado	.=(string)" when '".$mpid."' then 3 ";
							$campo_status		.=(string)" when '".$mpid."' then 'closed' ";
							break;
						case 3:
							echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Delete MELI\n";
							$this->meli->delete_item($item_status, $mpid, "delete_item");
							$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
							$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
							$campo_price		.=(string)" when '".$mpid."' then 0 ";
							$campo_bolborrado	.=(string)" when '".$mpid."' then 3 ";
							$campo_status		.=(string)" when '".$mpid."' then 'deleted' ";
							break;
						case 9:
							echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Stock Interno Enkargo\n";
							break;
						default:
							switch ($items['aws_bolborrado']) {
								case '1':
									echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Bolborrado 1 AWS - ".$items['sku']."\n";
									$this->meli->delete_item($item_status, $mpid, NULL);
									$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
									$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
									$campo_price		.=(string)" when '".$mpid."' then 0 ";
									$campo_bolborrado	.=(string)" when '".$mpid."' then 3 ";
									$campo_status		.=(string)" when '".$mpid."' then 'closed' ";				
									break;
								case '5':
									echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Sale price 0 AWS or no Prime - ".$items['sku']."\n";
									$this->meli->paused_item($item_status, $mpid, NULL);
									$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
									$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
									$campo_price		.=(string)" when '".$mpid."' then 0 ";
									$campo_bolborrado	.=(string)" when '".$mpid."' then 0 ";
									$campo_status		.=(string)" when '".$mpid."' then 'paused' ";			
									break;
								default:
									if ($items['sale_price'] == 0) {
										echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Sale price 0 AWS - ".$items['sku']."\n";
										$this->meli->paused_item($item_status, $mpid, NULL);
										$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
										$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
										$campo_price		.=(string)" when '".$mpid."' then 0 ";
										$campo_bolborrado	.=(string)" when '".$mpid."' then 0 ";
										$campo_status		.=(string)" when '".$mpid."' then 'paused' ";
									}
									elseif ($items['sale_price'] > 2500) {
										echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Price over limit - ".$items['sku']."\n";
										$this->meli->delete_item($item_status, $mpid, NULL);
										$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
										$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
										$campo_price		.=(string)" when '".$mpid."' then 0 ";
										$campo_bolborrado	.=(string)" when '".$mpid."' then 3 ";
										$campo_status		.=(string)" when '".$mpid."' then 'closed' ";
									}
									/*elseif ($items['prime'] = 0) {
										echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - no prime - ".$items['sku']."\n";
										$this->meli->paused_item($item_status, $mpid, NULL);
										$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
										$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
										$campo_price		.=(string)" when '".$mpid."' then 0 ";
										$campo_bolborrado	.=(string)" when '".$mpid."' then 0 ";
										$campo_status		.=(string)" when '".$mpid."' then 'paused' ";
									}*/					
									elseif ($items['active'] == 0) {
										echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Unactive AWS - ".$items['sku']."\n";
										$this->meli->paused_item($item_status, $mpid, NULL);
										$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
										$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
										$campo_price		.=(string)" when '".$mpid."' then 0 ";
										$campo_bolborrado	.=(string)" when '".$mpid."' then 0 ";
										$campo_status		.=(string)" when '".$mpid."' then 'paused' ";
									}
									elseif ($items['package_weight'] >= 40) {
										echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Weight limit AWS - ".$items['sku']."\n";
										$this->meli->delete_item($item_status, $mpid, null);
										$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
										$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
										$campo_price		.=(string)" when '".$mpid."' then 0 ";
										$campo_bolborrado	.=(string)" when '".$mpid."' then 3 ";
										$campo_status		.=(string)" when '".$mpid."' then 'closed' ";
									}
									elseif ($items['quantity'] < 1) {
										echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Without stock AWS - ".$items['sku']."\n";
										$this->meli->paused_item($item_status, $mpid, NULL);
										$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
										$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
										$campo_price		.=(string)" when '".$mpid."' then 0 ";
										$campo_bolborrado	.=(string)" when '".$mpid."' then 0 ";
										$campo_status		.=(string)" when '".$mpid."' then 'paused' ";
									}else{
										$ship=0;
										$valor_prime="\n&#10024;&#8473;&#10024;\n";
										if($items['prime']==0){
											$ship=6;
											$valor_prime="";
										}
										$precio	=	$items['sale_price']+$ship;
										$precio_p =  $this->meli->liquidador($precio, $items['package_weight'], $this->shop[0]['id']);
										$update_array['price'] =  $precio_p;
										switch ($item_status) {
											case "closed":
											case "inactive":
												$update_array['status'] 			= "active";
												$update_array['listing_type_id']	= "gold_special";
												$update_array['quantity'] = 8;
												$update = $this->meli->relist($mpid, $update_array);
												if (isset($update->id)) {
													echo $i++ ."\t- ".$items['mpid']." - ".date('Y-m-d H:i:s')." - relisted/close -".$update->id."\n";
													$campo_id	 		.=(string)" when '".$mpid."' then '".$update->id."' ";
													$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
													$campo_price		.=(string)" when '".$mpid."' then ".$precio_p." ";
													$campo_bolborrado	.=(string)" when '".$mpid."' then 0 ";
													$campo_status		.=(string)" when '".$mpid."' then 'relisted' ";
												} else {
													if (in_array("deleted", $detail_items[0]->sub_status)) {
														echo $i++ ."\t- ".$items['mpid']." - ".date('Y-m-d H:i:s')." - NO relisted/already deleted -".$mpid."\n";
														$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
														$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
														$campo_price		.=(string)" when '".$mpid."' then 0 ";
														$campo_bolborrado	.=(string)" when '".$mpid."' then 3 ";
														$campo_status		.=(string)" when '".$mpid."' then 'deleted' ";
													} else {
														echo $i++ ."\t- ".$items['mpid']." - ".date('Y-m-d H:i:s')." - NO relisted/Error -".$mpid;
														echo "\n";
														echo $update->message."\n";
														$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
														$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
														$campo_price		.=(string)" when '".$mpid."' then ".$precio_p."  ";
														$campo_bolborrado	.=(string)" when '".$mpid."' then 0 ";
														$campo_status		.=(string)" when '".$mpid."' then 'error close-active' ";
													}
												}
												unset($update_array['status']);
												unset($update_array['listing_type_id']);
												unset($update_array['quantity']);
												break;
											case 'paused':
												$update_array['status'] = "active";
												$update = $this->meli->update($mpid, $update_array);
												if (isset($update->id)) {
													echo $i++ ."\t- ".$items['mpid']." - ".date('Y-m-d H:i:s')." - updated/paused -".$items['sku']." - price: ".$update_array['price']."\n";
													$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
													$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
													$campo_price		.=(string)" when '".$mpid."' then ".$precio_p." ";
													$campo_bolborrado	.=(string)" when '".$mpid."' then 0 ";
													$campo_status		.=(string)" when '".$mpid."' then 'active' ";
												} else {
													echo $i++ ."\t- ".$items['mpid']." - ".date('Y-m-d H:i:s')." - NO updated/paused -".$items['sku']." - price: ".$update_array['price'];
													echo "\n";
													echo $update->message."\n";
													$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
													$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
													$campo_price		.=(string)" when '".$mpid."' then ".$precio_p."  ";
													$campo_bolborrado	.=(string)" when '".$mpid."' then 0 ";
													$campo_status		.=(string)" when '".$mpid."' then 'error pause-active' ";
												}
												break;
											case 'active':
												$time_send ="";
												if($avaliable_d ==1 && $items['prime'] == 1){
													$time_send .= "&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;\n";
													$time_send .= "&#9; TIEMPOS DE ENTREGA \n";
													$time_send .= "&#9; DE 4 A 8 DÍAS HÁBILES \n";
													$time_send .= "&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;\n";
												}
												if($avaliable_d ==1 && $items['prime'] == 0){
													$time_send .= "&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;\n";
													$time_send .= "&#9; TIEMPOS DE ENTREGA \n";
													$time_send .= "&#9; DE 7 A 10 DÍAS HÁBILES \n";
													$time_send .= "&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;\n";
												}
												if($avaliable_d == 2  ){
													$time_send .= "&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;\n";
													$time_send .= "&#9; TIEMPOS DE ENTREGA \n";
													$time_send .= "&#9; DE 10 A 15 DÍAS HÁBILES \n";
													$time_send .= "&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;&#9940;\n";
												}
												$description_detail = "";
												$description_detail .= "\n\n";
												$description_detail .= "&#9989;  Descripción del Producto\n";
												$description_detail .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
												$description_detail .= "\n";
												$description_detail .= str_replace(".-", "\n", $this->translate->translate('en', 'es', $description_esp));
												$description_detail .= "\n";
												$description_detail .= "\n";
												$description_detail .= "&#9989;  Ficha Técnica\n";
												$description_detail .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
												$description_detail .= "\n\n";
												$description_detail .= "• Titulo Original =>";
												$description_detail .= $this->meli->replace_amazon($items['product_title_english']);
												$description_detail .= "\n";
												$description_detail .= "• Marca => ";
												$description_detail .= $items['brand'];
												$description_detail .= "\n";
												$description_detail .= "• Modelo => ";
												$description_detail .= $items['model'];
												$description_detail .= "\n";
												$description_detail .= "• Unidad de peso => ";
												$description_detail .= $items['weight_unit'];
												$description_detail .= "\n";
												$description_detail .= "• Peso de Paquete => ";
												$description_detail .= $items['package_weight'];
												$description_detail .= "\n";
												$description_detail .= "• Ancho => ";
												$description_detail .= $items['item_width'];
												$description_detail .= "\n";
												$description_detail .= "• Alto => ";
												$description_detail .= $items['package_height'];
												$description_detail .= "\n";
												$description_detail .= "• Largo => ";
												$description_detail .= $items['package_length'];
												if($items['clothingsize'] != ""){
													$description_detail .= "\n";
													$description_detail .= "• Talla => ";
													$description_detail .= $items['clothingsize'];
												}
												if($items['color'] != ""){
													$description_detail .= "\n";
													$description_detail .= "• Color => ";
													$description_detail .= $items['color'];
												}

												$description_detail .= "\n\n";
												$date_up  =	"\n\n";
												$date_up .= date("Y-m-d H:i:s")." -A";
												$item_description .= $name_shop.$time_send.$description_detail.$description.$time_send.$valor_prime.$date_up ;
												$array_description = array('plain_text'=>$item_description);
												$update_description = $this->meli->banner($mpid, $array_description);
												#echo $mpid."-".print_r($update_array); 
												if (!empty($item_img)){
													$update_array['pictures'] = $item_img;
												}
												if($precio_p <= 70799){	
													$update = $this->meli->update($mpid, $reseteo_array);
												}
												$update = $this->meli->update($mpid, $update_array);
												if (isset($update->id)) {
													echo $i++ ."\t- ".$items['mpid']." - ".date('Y-m-d H:i:s')." - updated/active - ".$items['sku']." - price: ".$update_array['price']."\n";
													$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
													$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
													$campo_price		.=(string)" when '".$mpid."' then ".$precio_p." ";
													$campo_bolborrado	.=(string)" when '".$mpid."' then 0 ";
													$campo_status		.=(string)" when '".$mpid."' then 'active' ";
												} else {

													echo $i++ ."\t- ".$items['mpid']." - ".date('Y-m-d H:i:s')." - NO updated/active - ".$items['sku']." - price: ".$update_array['price'];
													echo "\n";
													echo $update->message."\n";
													$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
													$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
													$campo_price		.=(string)" when '".$mpid."' then ".$precio_p."  ";
													$campo_bolborrado	.=(string)" when '".$mpid."' then 0 ";
													$campo_status		.=(string)" when '".$mpid."' then 'error actived-active' ";
												}
												break;
											case "under_review":
												echo $i++ ."\t- ".$items['mpid']." - ".date('Y-m-d H:i:s')." - NO updated/ under_review "."\n";
												$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
												$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
												$campo_price		.=(string)" when '".$mpid."' then ".$precio_p." ";
												$campo_bolborrado	.=(string)" when '".$mpid."' then 0 ";
												$campo_status		.=(string)" when '".$mpid."' then 'under_review' ";
												break;
										}
								}
								break;
						}
						break;
					}
					
				}else{
					echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - deleted - mpid no found\n";
					$campo_id	 		.=(string)" when '".$mpid."' then '".$mpid."' ";
					$campo_up_dat 		.=(string)" when '".$mpid."' then CURRENT_TIMESTAMP(0) ";
					$campo_price		.=(string)" when '".$mpid."' then 0 ";
					$campo_bolborrado	.=(string)" when '".$mpid."' then 0 ";
					$campo_status		.=(string)" when '".$mpid."' then 'not exist' ";
				}
				$total_mpids 	.= "'".$mpid."',";
			}
			usleep(500000);
		}
		
		$total_mpids = substr($total_mpids, 0, -1);
		$sql 	=(string)"update meli.items SET 
		mpid =(CASE mpid ".$campo_id." END), 
		update_date =(CASE mpid ".$campo_up_dat." END),	
		price =(CASE mpid ".$campo_price." END),	
		bolborrado =(CASE mpid ".$campo_bolborrado." END), 
		status = (CASE mpid ".$campo_status." END)

		WHERE mpid in (".$total_mpids.");";

		#echo $sql;
		$this->conn->exec($sql);
		echo "\n\n\nFin update -".date("Y-m-d H:i:s")."***************************************************************\n\n\n";
		$this->conn->close_con();

	}
}
#Test section
#$test = new MeliUpdate(2,'massive');
#$test->update(500);