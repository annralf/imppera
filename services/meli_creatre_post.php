<?php
include '/var/www/html/enkargo/config/meli_items.php';

class meliGet {
	public $conn;
	public $translate;
	public function __construct() {
		$this->conn      = new DataBase();
		$this->translate = new GoogleTranslate();
	}
	public function createItems($application) {
		try {
			$k                  = 0;
			$application_detail = $this->conn->prepare("SELECT * FROM meli.shop WHERE id ='".$application."'");
			$application_detail->execute();
			$application_detail = $application_detail->fetchAll();
			$items_manager      = new items($application_detail[0]['access_token']);

			if ($application == 1) {
				echo "Inicio de carga Queen Bee - ".date("Y-m-d H:i:s")."\n";
				$items           = $this->conn->prepare("select * from aws.items_valido_qb_view  where update_date > '2018-02-15 18:00:00' and category_meli is not null OFFSET '".$secuence[0]['offset_']."' LIMIT '".$secuence[0]['limit_']."';");
			}
			if ($application == 2) {
				echo "Inicio de carga Mauxi - ".date("Y-m-d H:i:s")."\n";
				$items           = $this->conn->prepare("select p.aws_id,p.array_meli,p.status,p.shop_id,a.sku from meli.pre_charge p join aws.items a on p.aws_id=a.id where p.aws_id in (2163992,3062553,2841213,3055952,2920409,2901276,2130989) and p.shop_id=2;");
			}

			$items->execute();
			#$this->conn->beginTransaction();
			$items = $items->fetchAll();

			foreach ($items as $item) {
				$item = (object) $item;
				$product = json_decode($item->array_meli);
				#print_r($product);
				$show = $items_manager->create($product);
					if (!isset($show->error) and isset($show)) {
						echo $k."- \t".$item->sku." - Created - ".$show->id."-".date("Y-m-d H:i:s")."\n";
						$this->conn->exec("INSERT INTO meli.items(mpid, aws_id, shop_id, create_date, update_date) VALUES ('".$show->id."','".$items->aws_id."', '".$items->shop_id."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."');");

						if ($application_detail[0]['id'] == 1) {
							$this->conn->exec("UPDATE aws.items SET shop_ml_qb = 1 WHERE sku = '".$item->sku."';");
						}
						if ($application_detail[0]['id'] == 2) {
							$this->conn->exec("UPDATE aws.items SET shop_ml_mx = 1 WHERE sku = '".$item->aws_id."';");
						}
					} else {
						$message = isset($show->message)?htmlspecialchars($show->message, ENT_QUOTES):NULL;
						$code    = isset($show->error)?htmlspecialchars($show->error, ENT_QUOTES):NULL;
						echo $k."- \t".$item->sku." - NOT Created - ".date("Y-m-d H:i:s")."-Error-.".$code."-Message.".$message."\n";
						$this->conn->exec("INSERT INTO log.meli (sku,action_,response, executed_at, code_) VALUES ('".$item->sku."','1','".$message."','".date("Y-m-d H:i:s")."','".$code."');");
					}
				$k++;
			}
		
		} catch (Exception $e) {
			http_response_code(404);
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
		#$this->conn->commit();
		echo "Fin de carga - ".date("Y-m-d H:i:s")."\n";
		$this->conn->close_con();
	}

	public function bannerUpdate($shop_id) {
		$application = $this->conn->prepare("SELECT id, access_token, name FROM meli.shop WHERE id = '".$shop_id."';");
		$application->execute();
		$application = $application->fetch();
		$meli_item   = new items($application['access_token']);
		$secuence    = $this->conn->prepare("SELECT offset_, limit_ FROM meli.secuences WHERE type = 'bannerMa';");
		$secuence->execute();
		$secuence       = $secuence->fetch();
		$items_research = $this->conn->prepare("select m.mpid, m.create_date from meli.items as m join aws.items as a on a.id = m.aws_id where a.specification_english is not null and m.shop_id = '".$shop_id."' order by m.create_date asc offset '".$secuence->offset_."' limit 100000");
		$items_research->execute();
		$items_research  = $items_research->fetchAll();
		$offset          = $secuence['offset_']+100000;
		$update_secuence = $this->conn->prepare("update meli.items set offset_ = '".$offset."' where type = 'bannerMa';");
		$update_secuence->execute();
		$items_manager = new items($application['access_token']);
		$array         = array();
		$i             = 0;
		$this->conn->close_con();
		foreach ($items_research as $items) {
			foreach ($items_manager->show($items['mpid']) as $detail_items) {
				echo $i."-".$detail_items->id." - ".date('Y-m-d H:i:s')."\n";
				$item = $this->conn->prepare("SELECT product_title_english, specification_english FROM aws.items WHERE sku = '".$detail_items->seller_custom_field."';");
				$item->execute();
				$item             = (object) $item->fetch();
				$item_description = $this->translate->translate('en', 'es', $item->specification_english);
				if ($shop_id == 1) {
					$description      = str_replace(".", "\n", $item_description);
					$description .= "\n";
					$description .= "\n";
					$description .= "\n";
					$description .= "****************** ESTE ES UN ARTICULO IMPORTADO DESDE USA******************";
					$description .= "\n";
					$description .= "\n";
					$description .= "*************************** TIEMPO DE ENTREGA ***************************";
					$description .= "\n";
					$description .= "DE 4 A 10 DÍAS HÁBILES, LUEGO DE CONFIRMADA LA COMPRA";
					$description .= "\n";
					$description .= "\n";
					$description .= "************************************ GARANTÍA ***********************************";
					$description .= "\n";
					$description .= "Los artículos importados tienen garantía de 30 días por defectos de 
					fabrica únicamente. No nos hacemos responsables de los costos de envío en garantías 
					NO APLICA GARANTÍA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA ESTA EMPRESA ES QUIEN DEBERA ENCARGARSE DE RESPONDER POR EL VALOR DEL ARTICULO QUE HA SIDO AFECTADO.
					Recuerda que somos **Queen Bee Tienda Online**";
					$description .= "\n";
					$description .= "\n";
					$description .= "***************MÉTODOS DE ENVÍO***************";
					$description .= "\n";
					$description .= "Recuerda que los métodos de envío cambian según la categoría de la publicación, los envíos gratis y a cobro varían según los siguientes parámetros:";
					$description .= "\n";
					$description .= "1 - Si esta publicación está marcada con “Envío Gratis” este se enviará totalmente gratis en compras superiores a $70.000 según el convenio de Mercado Envíos";
					$description .= "\n";
					$description .= "2 - Si esta publicación está marcada como “Acordar con el Vendedor”, el costo del envío no esta incluido, así que este valor se cobrara a Contra Entrega, (Los costos pueden variar según la ciudad, el peso y volumen del producto).";
					$description .= "\n";
					$description .= "3 - Al elegir el “Retiro en Domicilio” no acelera el proceso de entrega, ya que igual el producto debe ser importado desde los Estados Unidos";
					$description .= "\n";
					$description .= "\n";
					$description .= "*************INFORMACIÓN IMPORTANTE***************";
					$description .= "\n";
					$description .= "Recuerda que antes de comprar, puedes hacer uso de la herramienta de preguntas, en donde un asesor te liberara todas tus dudas y así puedas estar totalmente confiado de lo que estas comprando.
					Vale la pena recordarte que al ser un producto importado y al ser distribuido a varios países en Latinoamérica, la disponibilidad del producto puede variar, al igual que las tallas y colores están sujetas a la disponibilidad, en algunos casos puede que el valor del producto por cuestiones de tamaño, color o proveedor pueda Cambiar.";
					$description .= "\n";
					$description .= "\n";
					$description .= "****Ficha Técnica****";
					$description .= "\n";
					$description .= "Titulo Original:";
					$description .= $items_manager->replace_amazon($item->product_title_english);
					$description .= "\n";
					$description .= "Brand:";
					$description .= $item->brand;
					$description .= "\n";
					$description .= "Model:";
					$description .= $item->model;
					$description .= "\n";
					$description .= "Weight Unit:";
					$description .= $item->weight_unit;
					$description .= "\n";
					$description .= "Package Weight:";
					$description .= $item->package_weight;
					$description .= "\n";
					$description .= "Package Width:";
					$description .= $item->item_width;
					$description .= "\n";
					$description .= "Package Height:";
					$description .= $item->package_height;
					$description .= "\n";
					$description .= "Package Length:";
					$description .= $item->package_length;
				}
				if ($shop_id == 2) {
					$description      = str_replace(".", "\n", $item_description);
					$description .= "\n";
					$description .= "\n";
					$description .= "\n";
					$description .= "****************** ESTE ES UN ARTICULO IMPORTADO DESDE USA******************";
					$description .= "\n";
					$description .= "\n";
					$description .= "*************************** TIEMPO DE ENTREGA ***************************";
					$description .= "\n";
					$description .= "DE 4 A 10 DÍAS HÁBILES, LUEGO DE CONFIRMADA LA COMPRA";
					$description .= "\n";
					$description .= "\n";
					$description .= "************************************ GARANTÍA ***********************************";
					$description .= "\n";
					$description .= "Los artículos importados tienen garantía de 30 días por defectos de 
					fabrica únicamente. No nos hacemos responsables de los costos de envío en garantías 
					NO APLICA GARANTIA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA
					Recuerda que somos **Mauxi E-Shop**";
					$description .= "\n";
					$description .= "\n";
					$description .= "***************MÉTODOS DE ENVÍO***************";
					$description .= "\n";
					$description .= "Recuerda que los métodos de envío cambian según la categoría de la publicación, los envíos gratis y a cobro varían según los siguientes parámetros:";
					$description .= "\n";
					$description .= "1 - Si esta publicación está marcada con “Envío Gratis” este se enviará totalmente gratis en compras superiores a 70.000 según el convenio de Mercado Envíos";
					$description .= "\n";
					$description .= "2 - Si esta publicación está marcada como “Acordar con el Vendedor”, el costo del envío no esta incluido, así que este valor se cobrara a Contra Entrega, (Los costos pueden variar según la ciudad, el peso y volumen del producto).";
					$description .= "\n";
					$description .= "\n";
					$description .= "*************INFORMACIÓN IMPORTANTE***************";
					$description .= "\n";
					$description .= "Recuerda que antes de comprar, puedes hacer uso de la herramienta de preguntas, en donde un asesor te liberara todas tus dudas y así puedas estar totalmente confiado de lo que estas comprando.
					Vale la pena recordarte que al ser un producto importado y al ser distribuido a varios países en Latinoamérica, la disponibilidad del producto puede variar, al igual que las tallas y colores están sujetas a la disponibilidad, en algunos casos puede que el valor del producto por variaciones de tamaño, color o proveedor pueda variar.";
					$description .= "\n";
					$description .= "Al elegir el “Retiro en Domicilio” no acelera el proceso de entrega, ya que igual el producto debe ser importado desde los Estados Unidos";
					$description .= "\n";
					$description .= "\n";
					$description .= "****Ficha Técnica****";
					$description .= "\n";
					$description .= "Titulo Original:";
					$description .= $items_manager->replace_amazon($item->product_title_english);
					$description .= "\n";
					$description .= "Brand:";
					$description .= $item->brand;
					$description .= "\n";
					$description .= "Model:";
					$description .= $item->model;
					$description .= "\n";
					$description .= "Weight Unit:";
					$description .= $item->weight_unit;
					$description .= "\n";
					$description .= "Package Weight:";
					$description .= $item->package_weight;
					$description .= "\n";
					$description .= "Package Width:";
					$description .= $item->item_width;
					$description .= "\n";
					$description .= "Package Height:";
					$description .= $item->package_height;
					$description .= "\n";
					$description .= "Package Length:";
					$description .= $item->package_length;
				}
				$product['plain_text'] = $description;
				$update                = $items_manager->banner($items['mpid'], $product);
				$item                  = $this->conn->exec("update meli.items set is_descripted = 'true', update_date = '".date('Y-m-d H:i:s')."' where mpid = '".$items['mpid']."' and shop_id = '".$shop_id."';");
				$i++;
			}
		}
		$this->conn->close_con();
	}
}