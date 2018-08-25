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
				$secuence = $this->conn->prepare("SELECT * FROM meli.secuences WHERE type = 'createQb';");
				$secuence->execute();
				$secuence = $secuence->fetchAll();
				$items           = $this->conn->prepare("SELECT * from aws.items_valido_qb_view  where update_date > '2018-02-15 18:00:00' and category_meli is not null and product_category is not null and product_category <> '' OFFSET '".$secuence[0]['offset_']."' LIMIT '".$secuence[0]['limit_']."';");
				#$offset          = $secuence[0]['offset_']+400;
				#$secuence_update = $this->conn->prepare("UPDATE meli.secuences SET offset_ ='".$offset."' WHERE type = 'createQb';");
				#$secuence_update->execute();
				$data_description = $this->conn->prepare("SELECT * FROM meli.templates  WHERE shop_id='".$application_detail[0]['id']."' and id=7;");
				$data_description->execute();
				$data_description = (object) $data_description->fetch();
				$video            = $this->conn->prepare("SELECT * FROM meli.video WHERE shop_id = '".$application_detail[0]['id']."' and id = 4;");
				$video->execute();
				$video = $video->fetchAll();
			}
			if ($application == 2) {
				echo "Inicio de carga Mauxi - ".date("Y-m-d H:i:s")."\n";
				$secuence = $this->conn->prepare("SELECT * FROM meli.secuences WHERE type = 'createMa';");
				$secuence->execute();
				$secuence = $secuence->fetchAll();
				$items           = $this->conn->prepare("SELECT * from aws.items_valido_mx_view  where update_date > '2018-02-15 18:00:00' and category_meli is not null and product_category is not null and product_category <> '' OFFSET '".$secuence[0]['offset_']."' LIMIT '".$secuence[0]['limit_']."';");
				$offset          = $secuence[0]['offset_']+400;
				$secuence_update = $this->conn->prepare("UPDATE meli.secuences SET offset_ ='".$offset."' WHERE type = 'createMa';");
				$secuence_update->execute();
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
				$description ="";
				$product="";
				$value="";
				$att=null;
				
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

					$description .= "\n";
					$description .= "\n";
					$description .= "••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••";
					$description .= "\n";
					$description .= "•••••••••••••••••••••••••••••••• QUEEN BEE ••••••••••••••••••••••••••••••";
					$description .= "\n";
					$description .= "••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••";
					$description .= "\n";
					$description .= "\n";
					$description .= "•••••••••• ESTE ES UN ARTICULO IMPORTADO DESDE USA ••••••••";
					$description .= "\n";
					$description .= "\n";
					$description .= "••••••••••••••••••••••••• TIEMPO DE ENTREGA ••••••••••••••••••••••••••";
					$description .= "\n";
					$description .= "DE 4 A 10 DÍAS HÁBILES, LUEGO DE CONFIRMADA LA COMPRA";
					$description .= "\n";
					$description .= "\n";
					$description .= "\n";
					$description .= "•••••••••••••••••••••••••••••••• GARANTÍA ••••••••••••••••••••••••••••••••";
					$description .= "\n";
					$description .= "Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente.";
					$description .= "\n";
					$description .= "\n"; 
					$description .= "No nos hacemos responsables de los costos de envío en garantías.";
					$description .= "\n";
					$description .= "\n";
					$description .= "NO APLICA GARANTÍA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA, ESTA EMPRESA ES QUIEN DEBERA ENCARGARSE DE RESPONDER POR EL VALOR DEL ARTICULO QUE HA SIDO AFECTADO.";
					$description .= "\n";
					$description .= "\n";
					$description .= "Recuerda que somos **Queen Bee Tienda Online**";
					$description .= "\n";
					$description .= "\n";
					$description .= "•••••••••••••••••••••••••• MÉTODOS DE ENVÍO •••••••••••••••••••••••••";
					$description .= "\n";
					$description .= "Recuerda que los métodos de envío cambian según la categoría de la publicación, los envíos gratis y a cobro varían según los siguientes parámetros:";
					$description .= "\n";
					$description .= "\n";
					$description .= "1 - Si esta publicación está marcada con “Envío Gratis” este se enviará totalmente gratis en compras superiores a $70.000 según el convenio de Mercado Envíos";
					$description .= "\n";
					$description .= "\n";
					$description .= "2 - Si esta publicación está marcada como “Acordar con el Vendedor”, el costo del envío no esta incluido, así que este valor se cobrara a Contra Entrega, (Los costos pueden variar según la ciudad, el peso y volumen del producto).";
					$description .= "\n";
					$description .= "\n";
					$description .= "3 - Al elegir el “Retiro en Domicilio” no acelera el proceso de entrega, ya que igual el producto debe ser importado desde los Estados Unidos";
					$description .= "\n";
					$description .= "\n";
					$description .= "••••••••••••••••••••• INFORMACIÓN IMPORTANTE •••••••••••••••••••••";
					$description .= "\n";
					$description .= "\n";
					$description .= "Recuerda que antes de comprar, puedes hacer uso de la herramienta de preguntas, en donde un asesor te liberara todas tus dudas y así puedas estar totalmente confiado de lo que estas comprando.";
					$description .= "\n";
					$description .= "\n";
					$description .= "Vale la pena recordarte que al ser un producto importado y al ser distribuido a varios países en Latinoamérica, la disponibilidad del producto puede variar, al igual que las tallas y colores están sujetas a la disponibilidad, en algunos casos puede que el valor del producto por cuestiones de tamaño, color o proveedor pueda Cambiar.";
					$description .= "\n";
					$description .= "\n";
					$description .= "Recuerda que el precio publicado pertenece a la talla y color descritos en la siguiente tabla. Otras tallas y colores pueden llegar a variar en su precio.";
					$description .= "\n";
					$description .= "\n";
					$description .= "••••••••••••••••••••• Descripción del Producto •••••••••••••••••••••";
					$description .= "\n";
					$description .= eliminar_simbolos($item_description);
					$description .= "\n";
					$description .= "\n";
					$description .= "•••••••••••••••••••••••••••••• Ficha Técnica •••••••••••••••••••••••••••••";
					$description .= "\n";
					$description .= "\n";
					$description .= "• Titulo Original:";
					$description .= eliminar_simbolos($items_manager->replace_amazon($item->product_title_english));
					$description .= "\n";
					$description .= "• Marca: ";
					$description .= eliminar_simbolos($item->brand);
					$description .= "\n";
					$description .= "• Modelo: ";
					$description .= eliminar_simbolos($item->model);
					$description .= "\n";
					$description .= "• Unidad de peso: ";
					$description .= $item->weight_unit;
					$description .= "\n";
					$description .= "• Peso: ";
					$description .= $item->package_weight;
					$description .= "\n";
					$description .= "• Ancho: ";
					$description .= $item->item_width;
					$description .= "\n";
					$description .= "• Alto: ";
					$description .= $item->package_height;
					$description .= "\n";
					$description .= "• Largo: ";
					$description .= $item->package_length;
					if($item->clothingsize != ""){
						$description .= "\n";
						$description .= "• Tamaño: ";
						$description .= eliminar_simbolos($item->clothingsize);
					}
					if($item->color != ""){
						$description .= "\n";
						$description .= "• Color: ";
						$description .= eliminar_simbolos($item->color);
					}
					$description .= "\n\n\n\n";
					$description .= date("Y-m-d H:i:s")." -C";
				}
				if ($application_detail[0]['id'] == 2) {
					$description .= "\n";
					$description .= "••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••";
					$description .= "\n";
					$description .= "•••••••••••••••••••••••••••••• MAUXI E-SHOP ••••••••••••••••••••••••••••";
					$description .= "\n";
					$description .= "••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••";
					$description .= "\n";
					$description .= "\n";
					$description .= "•••••••••• ESTE ES UN ARTICULO IMPORTADO DESDE USA ••••••••";
					$description .= "\n";
					$description .= "\n";
					$description .= "••••••••••••••••••••••••• TIEMPO DE ENTREGA ••••••••••••••••••••••••••";
					$description .= "\n";
					$description .= "DE 4 A 10 DÍAS HÁBILES, LUEGO DE CONFIRMADA LA COMPRA";
					$description .= "\n";
					$description .= "\n";
					$description .= "\n";
					$description .= "•••••••••••••••••••••••••••••••• GARANTÍA ••••••••••••••••••••••••••••••••";
					$description .= "\n";
					$description .= "Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente.";
					$description .= "\n";
					$description .= "\n"; 
					$description .= "No nos hacemos responsables de los costos de envío en garantías.";
					$description .= "\n";
					$description .= "\n";
					$description .= "NO APLICA GARANTÍA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA, ESTA EMPRESA ES QUIEN DEBERA ENCARGARSE DE RESPONDER POR EL VALOR DEL ARTICULO QUE HA SIDO AFECTADO.";
					$description .= "\n";
					$description .= "\n";
					$description .= "Recuerda que somos **Mauxi E-Shop**";
					$description .= "\n";
					$description .= "\n";
					$description .= "•••••••••••••••••••••••••• MÉTODOS DE ENVÍO •••••••••••••••••••••••••";
					$description .= "\n";
					$description .= "Recuerda que los métodos de envío cambian según la categoría de la publicación, los envíos gratis y a cobro varían según los siguientes parámetros:";
					$description .= "\n";
					$description .= "\n";
					$description .= "1 - Si esta publicación está marcada con “Envío Gratis” este se enviará totalmente gratis en compras superiores a $70.000 según el convenio de Mercado Envíos";
					$description .= "\n";
					$description .= "\n";
					$description .= "2 - Si esta publicación está marcada como “Acordar con el Vendedor”, el costo del envío no esta incluido, así que este valor se cobrara a Contra Entrega, (Los costos pueden variar según la ciudad, el peso y volumen del producto).";
					$description .= "\n";
					$description .= "\n";
					$description .= "3 - Al elegir el “Retiro en Domicilio” no acelera el proceso de entrega, ya que igual el producto debe ser importado desde los Estados Unidos";
					$description .= "\n";
					$description .= "\n";
					$description .= "••••••••••••••••••••• INFORMACIÓN IMPORTANTE •••••••••••••••••••••";
					$description .= "\n";
					$description .= "\n";
					$description .= "Recuerda que antes de comprar, puedes hacer uso de la herramienta de preguntas, en donde un asesor te liberara todas tus dudas y así puedas estar totalmente confiado de lo que estas comprando.";
					$description .= "\n";
					$description .= "\n";
					$description .= "Vale la pena recordarte que al ser un producto importado y al ser distribuido a varios países en Latinoamérica, la disponibilidad del producto puede variar, al igual que las tallas y colores están sujetas a la disponibilidad, en algunos casos puede que el valor del producto por cuestiones de tamaño, color o proveedor pueda Cambiar.";
					$description .= "\n";
					$description .= "\n";
					$description .= "Recuerda que el precio publicado pertenece a la talla y color descritos en la siguiente tabla. Otras tallas y colores pueden llegar a variar en su precio.";
					$description .= "\n";
					$description .= "\n";
					$description .= "••••••••••••••••••••• Descripción del Producto •••••••••••••••••••••";
					$description .= "\n";
					$description .= eliminar_simbolos($item_description);
					$description .= "\n";
					$description .= "\n";
					$description .= "•••••••••••••••••••••••••••••• Ficha Técnica •••••••••••••••••••••••••••••";
					$description .= "\n";
					$description .= "\n";
					$description .= "• Titulo Original:";
					$description .= eliminar_simbolos($items_manager->replace_amazon($item->product_title_english));
					$description .= "\n";
					$description .= "• Marca: ";
					$description .= eliminar_simbolos($item->brand);
					$description .= "\n";
					$description .= "• Modelo: ";
					$description .= eliminar_simbolos($item->model);
					$description .= "\n";
					$description .= "• Unidad de peso: ";
					$description .= $item->weight_unit;
					$description .= "\n";
					$description .= "• Peso: ";
					$description .= $item->package_weight;
					$description .= "\n";
					$description .= "• Ancho: ";
					$description .= $item->item_width;
					$description .= "\n";
					$description .= "• Alto: ";
					$description .= $item->package_height;
					$description .= "\n";
					$description .= "• Largo: ";
					$description .= $item->package_length;
					if($item->clothingsize != ""){
						$description .= "\n";
						$description .= "• Tamaño: ";
						$description .= eliminar_simbolos($item->clothingsize);
					}
					if($item->color != ""){
						$description .= "\n";
						$description .= "• Color: ";
						$description .= eliminar_simbolos($item->color);
					}
					$description .= "\n\n\n\n";
					$description .= date("Y-m-d H:i:s")." -C";
				}
				$weight_pack = $item->package_weight;
				$images      = explode("~^~", $item->image_url);
				$img_cant    = count($images);
				$item_img    = array();
				$item_img_mini=array();
				if (count($images) > 8) {
					$img_cant = 8;
				}
				for ($j = 0; $j < $img_cant; $j++) {
					array_push($item_img, array('source' => $images[$j]));
					array_push($item_img_mini, $images[$j]);
				}
				$date_update        = date('Y-m-d H:i:s')." - NEW";
				$application_detail = $this->conn->prepare("SELECT * FROM meli.shop WHERE id ='".$application."'");
				$application_detail->execute();
				$application_detail = $application_detail->fetchAll();
				$items_manager      = new items($application_detail[0]['access_token']);
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
					$buying_mode = $items_manager->validateCategory($category_id);
					if($buying_mode->attribute_types == "variations"){
						$att=" A";
						$attributes 		=array();
						$attributes_com		=array();
						$variacion = $items_manager->variation($category_id);
						foreach ($variacion as $var) {
							if($var->id=='11000'){
								if (isset($item->color_p_meli)){	
									foreach ($var->values as $color) {
										if($item->color_p_meli==$color->name){
											array_push($attributes_com, array( 'value_id' => $color->id, 'name' => 'Color Primario','value_name' => $color->name));
								        }    	
									}
								}	
							}
							if($var->name=='Talla'){
								if (isset($item->size_meli)){
									foreach ($var->values as $talla) {
										if($item->size_meli==$talla->name){
											array_push($attributes_com, array('value_id' => $talla->id, 'name' => 'Talla', 'value_name' => $talla->name));
								        }    	
									}
								}	
							}
							if($var->id=="MODEL"){
								if (isset($item->model) || $item->model <> null ){
									array_push($attributes, array('id' => 'MODEL', 'value_name' => $item->model ));	
								}
							}
							if($var->id=="BRAND"){
								if (isset($item->brand) || $item->brand <> '' ){
									array_push($attributes, array('id' => 'BRAND', 'value_name' => $item->brand ));	
								}
							}
							if($var->id=="ITEM_CONDITION"){
							    array_push($attributes, array('id' => 'ITEM_CONDITION', 'values_id'=> 2230284));
							}	
						}
						if (!empty($attributes_com)){
						
							$value['attribute_combinations'] 	= $attributes_com;
							$value['price'] 					= $product['price'];
							$value['available_quantity'] 		= 8;
							$value['picture_ids']            	= $item_img_mini;

						}
						#	$product['variations']  = $value;
						if (!empty($attributes)){
							#$value['attributes'] 	= $attributes;
						}
					
						#print_r($product);
					}


					#if ($buying_mode->attribute_types == "none") {

						$buying_mode = $buying_mode->settings->buying_modes;
						if (in_array("buy_it_now", $buying_mode)) {
							$buying_mode  = "buy_it_now";
						}else{
							if (in_array("classified", $buying_mode)) {
								$buying_mode  = "classified";
							}else{
								echo "category not matching\n";
								break;
							}
						}
						$product['buying_mode']         = $buying_mode;
						$product['available_quantity']  = 10;
						$product['condition']           = "new";
						$product['listing_type_id']     = "gold_special";
						$product['description']         = array('plain_text' => $description);
						$product['video_id']            = $video[0]['url'];
						$product['warranty']            = $warranty;
						$product['pictures']            = $item_img;
						$product['seller_custom_field'] = $item->sku;
						$product['shipping']            = array('mode'    => 'me2', 'local_pick_up'    => true, 'free_shipping'    => false);
						if (!empty($value)){
							$product['variations']  = array($value);
						}
						$show = $items_manager->validate($product);
						if ($show) {
							#print_r($product);
							echo $k.$att."- \t".$item->sku." - NOT Created - Need attributes for category: ".$category_id." - ".date("Y-m-d H:i:s")."\n";
						}else{
							$show = $items_manager->create($product);
							if (!isset($show->error) and isset($show)) {
								echo $k.$att."- \t".$item->sku." - Created - ".$show->id."-".date("Y-m-d H:i:s")."\n";
								$this->conn->exec("INSERT INTO meli.items(mpid, title, seller_id, category_id, price, base_price, sold_quantity,start_time, stop_time, end_time, permalink, status, aws_id, automatic_relist, date_created, last_updated, shop_id, create_date, update_date,video,template, is_static) VALUES ('".$show->id."', '".htmlspecialchars($show->title, ENT_QUOTES)."', '".$show->seller_id."', '".$show->category_id."', '".$show->price."', '".$show->base_price."', '".$show->sold_quantity."','".$show->start_time."', '".$show->stop_time."', '".$show->end_time."', '".$show->permalink."', '".$show->status."','".$item->id."', '".$show->automatic_relist."', '".$show->date_created."', '".$show->last_updated."', '".$application_detail[0]['id']."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."','".$video[0]['id']."','".$data_description->id."','0');");
									unset($product);
									unset($value);
								if ($application_detail[0]['id'] == 1) {
									$this->conn->exec("UPDATE aws.items SET shop_ml_qb = 1 WHERE sku = '".$item->sku."';");
								}
								if ($application_detail[0]['id'] == 2) {
									$this->conn->exec("UPDATE aws.items SET shop_ml_mx = 1 WHERE sku = '".$item->sku."';");
								}
							} else {
								$message = isset($show->message)?htmlspecialchars($show->message, ENT_QUOTES):NULL;
								$code    = isset($show->error)?htmlspecialchars($show->error, ENT_QUOTES):NULL;
								echo $k.$att."- \t".$item->sku." - NOT Created - ".date("Y-m-d H:i:s")."-Error-.".$code."-Message.".$message."\n";
								$show = $items_manager->validate($product);
								print_r($show);
								$this->conn->exec("INSERT INTO log.meli (sku,action_,response, executed_at, code_) VALUES ('".$item->sku."','1','".$message."','".date("Y-m-d H:i:s")."','".$code."');");
								unset($product);
								unset($value);
							}
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
/*
$test = new meliGet();
$test->createItems(2);
*/