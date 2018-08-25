<?php
include '/var/www/html/enkargo/config/meli_items.php';
include "/var/www/html/enkargo/config/conex_manager.php";

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
				#$items           = $this->conn->prepare("SELECT * from aws.items_valido_qb_view  where update_date > '2018-02-15 18:00:00' and category_meli is not null and product_category is not null and product_category <> '' and id not in (select aws_id from meli.pre_charge) OFFSET '0' LIMIT '30000';");
				$items           = $this->conn->prepare("SELECT * from aws.items where sku in ('B001QWUFLU','B06WRXM4J4','B01HUTDJX8','B06X1CQ5V1','B06X9Y56PD','B01LDZ1MFW','B004IZA46S','B013JI5KD6','B01M6YANTN');");

				$data_description = $this->conn->prepare("SELECT * FROM meli.templates  WHERE shop_id='".$application_detail[0]['id']."' and id=7;");
				$data_description->execute();
				$data_description = (object) $data_description->fetch();
				$video            = $this->conn->prepare("SELECT * FROM meli.video WHERE shop_id = '".$application_detail[0]['id']."' and id = 4;");
				$video->execute();
				$video = $video->fetchAll();
			}
			if ($application == 2) {
				echo "Inicio de carga Mauxi - ".date("Y-m-d H:i:s")."\n";

				#$items           = $this->conn->prepare("SELECT * from aws.items_valido_mx_view  where update_date > '2018-02-15 18:00:00'  and category_meli is not null and product_category is not null and product_category <> '' and id not in (select aws_id from meli.pre_charge) OFFSET '0' LIMIT '30000';");

				$items           = $this->conn->prepare("SELECT * from aws.items where sku='B01LXL0RZA'");

				$data_description = $this->conn->prepare("SELECT * FROM meli.templates  WHERE shop_id='".$application_detail[0]['id']."' and id=8;");
				$data_description->execute();
				$data_description = (object) $data_description->fetch();
				$video            = $this->conn->prepare("SELECT * FROM meli.video WHERE shop_id = '".$application_detail[0]['id']."' and id = 5;");
				$video->execute();
				$video = $video->fetchAll();
			}
			$items->execute();
			#$this->conn->beginTransaction();
			$items = $items->fetchAll();
			foreach ($items as $item) {
				$item = (object) $item;
				echo $k."- \t".$item->sku;
				#echo $k."-".$item->sku."\n";
				$title = eliminar_simbolos($item->product_title_english);
				if (strlen($title) >= 200) {
					$pos   = strpos($title, ' ', 150);
					$title = substr($title, 0, $pos);
				}
				$item_title = $this->translate->translate('en', 'es', $title);
				/*Functions about descriotion plain text*/
				$item_description = $this->translate->translate('en', 'es', $item->specification_english);
				if ($application_detail[0]['id'] == 1) {
					$description  = str_replace(array("'", "."),array(" ", "\n"), $item_description);
					$description .= "\n";
					$description .= "\n";
					$description .= "\n";
					$description .= "\n";
					$description .= "***** ESTE ES UN ARTICULO IMPORTADO DESDE USA *****";
					$description .= "\n";
					$description .= "\n";
					$description .= "***************** TIEMPO DE ENTREGA ****************";
					$description .= "\n";
					$description .= "DE 4 A 10 DÍAS HÁBILES, LUEGO DE CONFIRMADA LA COMPRA";
					$description .= "\n";
					$description .= "\n";
					$description .= "********************* GARANTÍA *********************";
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
					$description .= "************INFORMACIÓN IMPORTANTE**************";
					$description .= "\n";
					$description .= "*** RECUERDA QUE LOS PRECIOS NO INCLUYEN IVA ***";
					$description .= "\n";
					$description .= "Recuerda que antes de comprar, puedes hacer uso de la herramienta de preguntas, en donde un asesor te liberara todas tus dudas y así puedas estar totalmente confiado de lo que estas comprando.
					Vale la pena recordarte que al ser un producto importado y al ser distribuido a varios países en Latinoamérica, la disponibilidad del producto puede variar, al igual que las tallas y colores están sujetas a la disponibilidad, en algunos casos puede que el valor del producto por cuestiones de tamaño, color o proveedor pueda Cambiar.";
					$description .= "\n";
					$description .= "Recuerda que el precio publicado pertenece a la talla y color descritos en la siguiente tabla. Otras tallas y colores pueden llegar a variar en su precio.";
					$description .= "\n";					
					$description .= "\n";
					$description .= "****Ficha Técnica****";
					$description .= "\n";
					$description .= "Titulo Original:";
					$description .= eliminar_simbolos($items_manager->replace_amazon($item->product_title_english));
					$description .= "\n";
					$description .= "Marca: ";
					$description .= eliminar_simbolos($item->brand);
					$description .= "\n";
					$description .= "Modelo: ";
					$description .= eliminar_simbolos($item->model);
					$description .= "\n";
					$description .= "Unidad de peso: ";
					$description .= $item->weight_unit;
					$description .= "\n";
					$description .= "Peso: ";
					$description .= $item->package_weight;
					$description .= "\n";
					$description .= "Ancho: ";
					$description .= $item->item_width;
					$description .= "\n";
					$description .= "Alto: ";
					$description .= $item->package_height;
					$description .= "\n";
					$description .= "Largo: ";
					$description .= $item->package_length;
					if($item->clothingsize != ""){
						$description .= "\n";
						$description .= "Tamaño: ";
						$description .= eliminar_simbolos($item->clothingsize);
					}
					if($item->color != ""){
						$description .= "\n";
						$description .= "Color: ";
						$description .= eliminar_simbolos($item->color);
					}

				}
				if ($application_detail[0]['id'] == 2) {
					$description  = str_replace(array("'", "."),array(" ", "\n"), $item_description);
					$description .= "\n";
					$description .= "\n";
					$description .= "\n";
					$description .= "\n";			
					$description .= "**** ESTE ES UN ARTICULO IMPORTADO DESDE USA ****";
					$description .= "\n";
					$description .= "\n";
					$description .= "************** TIEMPO DE ENTREGA ***************";
					$description .= "\n";
					$description .= "DE 4 A 10 DÍAS HÁBILES, LUEGO DE CONFIRMADA LA COMPRA";
					$description .= "\n";
					$description .= "\n";
					$description .= "******************* GARANTÍA *******************";
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
					$description .= "**** RECUERDA QUE LOS PRECIOS NO INCLUYEN IVA ****";
					$description .= "\n";
					$description .= "Recuerda que antes de comprar, puedes hacer uso de la herramienta de preguntas, en donde un asesor te liberara todas tus dudas y así puedas estar totalmente confiado de lo que estas comprando.
					Vale la pena recordarte que al ser un producto importado y al ser distribuido a varios países en Latinoamérica, la disponibilidad del producto puede variar, al igual que las tallas y colores están sujetas a la disponibilidad, en algunos casos puede que el valor del producto por variaciones de tamaño, color o proveedor pueda variar.";
					$description .= "\n";
					$description .= "Al elegir el “Retiro en Domicilio” no acelera el proceso de entrega, ya que igual el producto debe ser importado desde los Estados Unidos";
					$description .= "\n";
					$description .= "Recuerda que el precio publicado pertenece a la talla y color descritos en la siguiente tabla. Otras tallas y colores pueden llegar a variar en su precio.";
					$description .= "\n";					
					$description .= "\n";
					$description .= "****Ficha Técnica****";
					$description .= "\n";
					$description .= "Titulo Original:";
					$description .= eliminar_simbolos($items_manager->replace_amazon($item->product_title_english));
					$description .= "\n";
					$description .= "Marca: ";
					$description .= eliminar_simbolos($item->brand);
					$description .= "\n";
					$description .= "Modelo: ";
					$description .= eliminar_simbolos($item->model);
					$description .= "\n";
					$description .= "Unidad de peso: ";
					$description .= $item->weight_unit;
					$description .= "\n";
					$description .= "Peso: ";
					$description .= $item->package_weight;
					$description .= "\n";
					$description .= "Ancho: ";
					$description .= $item->item_width;
					$description .= "\n";
					$description .= "Alto: ";
					$description .= $item->package_height;
					$description .= "\n";
					$description .= "Largo: ";
					$description .= $item->package_length;
					if($item->clothingsize != ""){
						$description .= "\n";
						$description .= "Tamaño: ";
						$description .= eliminar_simbolos($item->clothingsize);
					}
					if($item->color != ""){
						$description .= "\n";
						$description .= "Color: ";
						$description .= eliminar_simbolos($item->color);
					}
				}

				$weight_pack = $item->package_weight;
				$images      = explode("~^~", $item->image_url);
				$img_cant    = count($images);
				$item_img    = array();
				if (count($images) > 8) {
					$img_cant = 8;
				}
				for ($j = 0; $j < $img_cant; $j++) {
					array_push($item_img, array('source' => $images[$j]));
				}
				$date_update        = date('Y-m-d H:i:s')." - NEW";
				$application_detail = $this->conn->prepare("SELECT * FROM meli.shop WHERE id ='".$application."'");
				$application_detail->execute();
				$application_detail = $application_detail->fetchAll();
				$items_manager      = new items($application_detail[0]['access_token']);
				#echo $item->product_category."-".$item->category_meli;
				$category_id        = $items_manager->category_match_aws($item->product_category,$item->category_meli);

				#$category_id        = $item->category_meli;
				#$items_manager->getCategoriesPredictor($item_title);
				if ($category_id == null) {
					echo $k."-\t".$item->sku." - category_id not found\n";
					$this->conn->exec("INSERT INTO log.meli (sku,action_,response, executed_at) VALUES ('".$item->sku."','1','category_id not found','".date("Y-m-d H:i:s")."');");
					$k++;
				} else {
					$category = $items_manager->validateCategory($category_id);
					$warranty;
					if ($application_detail[0]['id'] == 1) {
						$warranty = "*** TRADUCCIÓN AUTOMÁTICA, VERIFICAR CARACTERISTICAS ANTES DE COMPRAR***
						Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente. No nos hacemos responsables de los costos de envío en garantías NO APLICA GARANTIA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA";
					}
					if ($application_detail[0]['id'] == 2) {
						$warranty = "*** TRADUCCIÓN AUTOMÁTICA, VERIFICAR CARACTERISTICAS ANTES DE COMPRAR***
						Este es un artículo importado, tiene una garantía de 30 días por defectos de fábrica, la misma no incluye cualquier daño ocasionado por la empresa transportadora. Mauxi eshop no se hace responsable de los costos de envío del producto en caso de aplicar la garantía";
					}
					if (strlen($item_title) >= 60) {
						$pos        = strpos($item_title, ' ', 40);
						$item_title = substr($item_title, 0, $pos);
					}
					$product['title']               = eliminar_simbolos($item_title);
					$product['category_id']         = $category_id;
					$product['domain_id']           = "articulo";
					$product['price']               = $items_manager->liquidador($item->sale_price, $weight_pack, $application_detail[0]['id']);
					$product['currency_id']         = "COP";
					$product['buying_mode']         = "buy_it_now";
					$product['available_quantity']  = 10;
					$product['condition']           = "new";
					$product['listing_type_id']     = "gold_special";
					$product['description']         = array('plain_text' => $description);
					$product['video_id']            = $video[0]['url'];
					$product['warranty']            = $warranty;
					$product['pictures']            = $item_img;
					$product['seller_custom_field'] = $item->sku;
					$product['shipping']            = array('mode'    => 'me2', 'local_pick_up'    => 'true', 'free_shipping'    => 'true');
					$product['location']            = array('country' => array('name' => 'Colombia'), 'state' => array('name' => 'Bogota D.C'), 'city' => array('name' => 'Bogota D.C'));
					#$show                           = $items_manager->create($product);
					$sql	=	"INSERT INTO meli.pre_charge(aws_id,array_meli,shop_id,status) VALUES (".$item->id.",'".json_encode($product)."',".$application_detail[0]['id'].",'generated');";
					$valor = $this->conn->exec($sql);
					if ($valor == 1){
						echo " - generado - ".date("Y-m-d H:i:s")."\n";

					}else{
						echo " - NO generado - ".date("Y-m-d H:i:s")."\n";

						echo $sql;
						die();

					}

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
	public function loadItems($item, $shop_id,$access_token){
		$items_manager = new items($access_token);
		$show = $items_manager->create($item);
		if (!isset($show->error) and isset($show)) {
			echo $item->seller_custom_field." - Created - ".$show->id."-".date("Y-m-d H:i:s")."\n";
			$this->conn->exec("INSERT INTO meli.items(mpid, title, seller_id, category_id, price, base_price, sold_quantity,start_time, stop_time, end_time, permalink, status, aws_id, automatic_relist, date_created, last_updated, shop_id, create_date, update_date,video,template, is_static) VALUES ('".$show->id."', '".htmlspecialchars($show->title, ENT_QUOTES)."', '".$show->seller_id."', '".$show->category_id."', '".$show->price."', '".$show->base_price."', '".$show->sold_quantity."','".$show->start_time."', '".$show->stop_time."', '".$show->end_time."', '".$show->permalink."', '".$show->status."','".$item->id."', '".$show->automatic_relist."', '".$show->date_created."', '".$show->last_updated."', '".$shop_id."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."','".$item->video_id."','".$data_description->id."','0');");
			if ($shop_id == 1) {
				$this->conn->exec("UPDATE aws.items SET shop_ml_qb = 1 WHERE sku = '".$item->seller_custom_field."';");
			}
			if ($shop_id == 2) {
				$this->conn->exec("UPDATE aws.items SET shop_ml_mx = 1 WHERE sku = '".$item->seller_custom_field."';");
			}
		} else {
			$message = isset($show->message)?htmlspecialchars($show->message, ENT_QUOTES):NULL;
			$code    = isset($show->error)?htmlspecialchars($show->error, ENT_QUOTES):NULL;
			echo $item->seller_custom_field." - NOT Created - ".date("Y-m-d H:i:s")."-Error-.".$code."-Message.".$message."\n";
			$this->conn->exec("INSERT INTO log.meli (sku,action_,response, executed_at, code_) VALUES ('".$item->seller_custom_field."','1','".$message."','".date("Y-m-d H:i:s")."','".$code."');");
		}
	}
}
/*
$test = new meliGet();
$conn      = new DataBase();
$application_detail = $conn->prepare("SELECT * FROM meli.shop WHERE id =1");
$application_detail->execute();
$application_detail = $application_detail->fetchAll();
$product = $conn->prepare("SELECT array_meli FROM meli.pre_charge WHERE shop_id =1 limit 3");
$product->execute();
$product = $product->fetchAll();
$i = 1;
foreach ($product as $key) {
	$product = json_decode($key[0]);
	echo $i."-";
	$test->loadItems($product, $application_detail[0]['id'],$application_detail[0]['access_token']);
	$i++;
}*/
#$test->createItems(1);