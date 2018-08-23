<?php
include '/var/www/html/enkargo/config/meli_items.php';
include '/var/www/html/enkargo/config/conex_manager.php';
require_once '/var/www/html/enkargo/config/pdo_connector.php';
#ini_set("display_errors", 0);
#ini_set('error_reporting', E_ALL|E_STRICT);
class MeliUpdate {
	public $conn;
	public $translate;
	public function __construct() {
		$this->conn      = new Connect();
		$this->translate = new GoogleTranslate();
	}

	public function update_meli($application, $type, $mco) {
		try{
			echo "Update process Begin-".date("Y-m-d H:i:s")."\n";
			#connection with Data base and application details
			$i           = 1;
			$conn        = new DataBase();
			
			$application = $conn->prepare("SELECT * FROM meli.shop WHERE id = '".$application."';");
			$application->execute();
			$application   = $application->fetchAll();
			$shop          = $application[0]['id'];
			$translate     = new GoogleTranslate();
			$items_manager = new items($application[0]['access_token']);
			
			if ($application[0]['id']==1) {
				$id_seq		= 4;
				$warranty 	= "*** TRADUCCIÓN AUTOMÁTICA, VERIFICAR CARACTERISTICAS ANTES DE COMPRAR***
						Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente. No nos hacemos responsables de los costos de envío en garantías NO APLICA GARANTIA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA";
				$shop_ml	= "shop_ml_qb";
				$video_shop = 4;
			}
			if ($application[0]['id']==2) {
				$id_seq		= 5;
				$warranty 	= "*** TRADUCCIÓN AUTOMÁTICA, VERIFICAR CARACTERISTICAS ANTES DE COMPRAR***
						Este es un artículo importado, tiene una garantía de 30 días por defectos de fábrica, la misma no incluye cualquier daño ocasionado por la empresa transportadora. Mauxi eshop no se hace responsable de los costos de envío del producto en caso de aplicar la garantía";
				$shop_ml	= "shop_ml_mx";
				$video_shop = 5;

			}

			if ($type=="unique"){

				$items_research = $conn->prepare("select m.mpid, m.bolborrado from meli.items m where m.shop_id='".$application[0]['id']."' and m.mpid ='".$mco."'");
				#$items_research = $conn->prepare("select m.mpid, m.bolborrado from meli.items m where m.shop_id='".$application[0]['id']."' and price is not null order by m.price desc limit 10000");
				$items_research->execute();
				$items_research = $items_research->fetchAll();
				$conn->close_con();

			}
			if ($type=="massive"){

				#Getting secuences info
				$secuence = $conn->prepare("SELECT * FROM meli.secuences WHERE id = '".$id_seq."';");
				$secuence->execute();
				$secuence        = $secuence->fetchAll();
				$offset          = $secuence[0]['offset_']+5000;
				$secuence_update = $conn->prepare("UPDATE meli.secuences SET offset_ ='".$offset."' WHERE id = '".$id_seq."';");
				$secuence_update->execute();
				#Update resource manager <Get info about MPIDs to update> it can depend of update needle
				$items_research = $conn->prepare("select m.mpid, m.bolborrado from meli.items m where m.shop_id='".$application[0]['id']."' order by m.update_date asc offset '".$secuence[0]['offset_']."' limit '".$secuence[0]['limit_']."'");
				$items_research->execute();
				$items_research = $items_research->fetchAll();
				$conn->close_con();
			}

			#Main item search from MELI resources
			#Iterate around MPIDs found and make respective task
			foreach ($items_research as $items) {
				$temp         = array();
				$mpid         = $items['mpid'];
				$detail_items = $items_manager->show($mpid);

				if (isset($detail_items[0]->id)) {
					switch ($items['bolborrado']) {
						case 1:
							#Close for delete items from meli sentence
						echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- Bolborrado 1 MELI\n";
						$items_manager->paused_item($detail_items[0]->status, $mpid, NULL);
						$conn->exec("UPDATE meli.items SET bolborrado = 1, status= 'closed', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
						break;

						case 2:
							#Paused S3 items from meli sentence
						echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- Item S3 MELI\n";
						$items_manager->paused_item($detail_items[0]->status, $mpid, NULL);
						$conn->exec("UPDATE meli.items SET bolborrado = 2, status= 'paused', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
						break;

						case 3:
							#Delete closed items
						echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- Delete MELI\n";
						$items_manager->paused_item($detail_items[0]->status, $mpid, "delete_item");
						$conn->exec("UPDATE meli.items SET bolborrado = 3, status= 'deleted', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
						break;

						case 9:
							#Delete closed items
						echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- Stock Interno Enkargo\n";
						$conn->exec("UPDATE meli.items SET status= 'Stock', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
						break;

						default:
							#Update items
						$sku  = isset($detail_items[0]->seller_custom_field)?$detail_items[0]->seller_custom_field:null;
						$item = $conn->prepare("SELECT id, sku, image_url,quantity, active, round(cast((package_height/0.393701) as numeric),2) as package_height, round(cast((item_width/0.393701) as numeric),2) as item_width, round(cast((package_length/0.393701) as numeric),2) as package_length, round(cast((item_height/0.393701) as numeric),2) as item_height, round(cast((item_length/0.393701) as numeric),2) as item_length, product_title_english, specification_english, brand, round(cast((package_weight/2.204623)/100 as numeric),2) as package_weight, sale_price, bolborrado, brand, model, weight_unit FROM aws.items WHERE sku = '".$sku."';");
						$item->execute();
						$item = (object) $item->fetch();

						if (!isset($item->id)) {
							echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."-".$sku."-this SKU not exist\n";
							$items_manager->paused_item($detail_items[0]->status, $mpid, NULL);
							$conn->exec("UPDATE meli.items SET bolborrado = 3, status= 'closed', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
							break;
						}
							#Close onLine items and delete at database if SKU is not found
							if ($sku == null) {#Close onLine items and delete at database if SKU is not found
								echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- Items whithout SKU in MELI\n";
								$items_manager->paused_item($detail_items[0]->status, $mpid, "delete_item");
								$conn->exec("UPDATE meli.items SET bolborrado = 3, status= 'no sku', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
								break;
							}
							#Close onLine items and delete at database if Bolborrador from AWS is 1
							if ($item->bolborrado == 1) {
								echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- Bolborrado AWS - ".$sku;
								echo "--- ".$item->bolborrado." ---\n";
								$items_manager->paused_item($detail_items[0]->status, $mpid, NULL);
								$conn->exec("UPDATE meli.items SET bolborrado = 3, status= 'closed', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
								break;
							}
							#Update AWS stock
							$conn->exec("UPDATE aws.items SET ".$shop_ml." = '1' WHERE id = '".$item->id."';");

							if ($item->sale_price == 0) {
								echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- Sale price 0 AWS - ".$sku."\n";
								$items_manager->paused_item($detail_items[0]->status, $mpid, NULL);
								$conn->exec("UPDATE meli.items SET bolborrado = 0, status= 'paused' ,aws_id=".$item->id.", update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
								break;
							}

							if ($item->sale_price > 2500) {
								echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- Price over limit - ".$sku."\n";
								$items_manager->delete_item($detail_items[0]->status, $mpid, NULL);
								$conn->exec("UPDATE meli.items SET bolborrado = 3, status= 'paused' ,aws_id=".$item->id.", update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
								break;
							}

							if ($item->active == 0) {
								echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- Unactive AWS - ".$sku;
								echo "--- ".$item->active." ---\n";
								$items_manager->paused_item($detail_items[0]->status, $mpid, NULL);
								$conn->exec("UPDATE meli.items SET bolborrado = 0, status= 'paused' ,aws_id=".$item->id.", update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
								break;
							}
							if ($item->package_weight >= 25) {
								echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- Weight limit AWS - ".$sku;
								echo "--- ".$item->package_weight." ---\n";
								$items_manager->delete_item($detail_items[0]->status, $mpid, "delete_item");
								$conn->exec("UPDATE meli.items SET bolborrado = 3, status= 'paused' ,aws_id=".$item->id.", update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
								break;
							}
							if ($item->quantity < 1) {
								echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- Without stock AWS - ".$sku;
								echo "--- ".$item->quantity." ---\n";
								$items_manager->paused_item($detail_items[0]->status, $mpid, NULL);
								$conn->exec("UPDATE meli.items SET bolborrado = 0, status= 'paused' ,aws_id=".$item->id.", update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
								break;
							}
							#Working with active items
							#Getting Description variables
							$traslation = $conn->prepare("SELECT title, description FROM corrections.spanish WHERE sku = '".$sku."';");
							$traslation->execute();
							$traslation   = (object) $traslation->fetch();
							$listing_type = $detail_items[0]->listing_type_id;
							$video        = $conn->prepare("SELECT * FROM meli.video WHERE shop_id = '".$shop."' AND id = ".$video_shop.";");
							$video->execute();
							$video = $video->fetch();
							#$images          = explode("~^~", $item->image_url);
							$item_img = array();
							#$item_img_amount = (count($images) > 8)?8:count($images);
							#for ($j = 0; $j < $item_img_amount; $j++) {
							#	array_push($item_img, array('source' => $images[$j]));
							#}
							#if ($listing_type == 'gold_pro') {
							#	$price        = $items_manager->liquidador_pro($item->sale_price, $item->package_weight, $shop);
							#	$listing_type = 'gold_pro';
							#} else {
							$price        = $items_manager->liquidador($item->sale_price, $item->package_weight, $shop);
							$listing_type = 'gold_special';
							#}
							if (isset($traslation->title)) {
								$item_title = $traslation->title;
							} else {
								$item_title = $translate->translate('en', 'es', $item->product_title_english);
								if (strlen($item_title) > 60) {
									$pos        = strpos($item_title, ' ', 50);
									$item_title = substr($item_title, 0, $pos);
								}
							}
							$item_title = $items_manager->replace_amazon($item_title);
							switch ($detail_items[0]->status) {
								case "closed":
								case "inactive":
								$temp                    = array();
									#$temp['title']           = $item_title;
								$temp['status']          = "active";
								$temp['price']           = $price;
								$temp['quantity']        = 8;
								$temp['buying_mode']     = "buy_it_now";
								$temp['listing_type_id'] = $listing_type;
								$temp['video_id']        = $video['url'];
									#$temp['pictures']        = $images;
								$temp['shipping']        = array('mode' => 'me2', 'local_pick_up' => 'true', 'free_shipping' => 'true');
								$temp['condition']       = "new";
								$temp['warranty']        = $warranty;
								$update                  = $items_manager->relist($mpid, $temp);
								if (isset($update->id)) {
									echo $i++ ."-".$items['mpid']."-".date('Y-m-d H:i:s')."- relisted/close -".$update->id."\n";
									$mpid      = $update->id;
									$title     = pg_escape_string(utf8_encode($update->title));
									$permalink = pg_escape_string(utf8_encode($update->permalink));
									$conn->exec("UPDATE meli.items SET mpid = '".$update->id."',title ='".$title."', seller_id = '".$update->seller_id."', category_id = '".$update->category_id."', price = '".$update->price."', sold_quantity = '".$update->sold_quantity."', start_time = '".$update->start_time."', stop_time='".$update->stop_time."', permalink = '".$permalink."', status = '".$update->status."', aws_id = '".$item->id."', automatic_relist = '".$update->automatic_relist."', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$items['mpid']."' and shop_id = '".$application[0]['id']."';");
								} else {
									if (in_array("deleted", $detail_items[0]->sub_status)) {
										$conn->exec("UPDATE meli.items SET bolborrado = 3, status= 'delete', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
										echo $i++ ."-".$items['mpid']."-".date('Y-m-d H:i:s')."- NO relisted/already deleted -".$mpid."\n";
									} else {
										echo $i++ ."-".$items['mpid']."-".date('Y-m-d H:i:s')."- NO relisted/Error -".$mpid."\n";
										$message = isset($update->message)?htmlspecialchars($update->message, ENT_QUOTES):NULL;
										$code    = isset($update->error)?htmlspecialchars($update->error, ENT_QUOTES):NULL;
										$conn->exec("INSERT INTO log.meli (sku,action_,response, executed_at, code_) VALUES ('".$item->sku."','1','".$message."','".date('Y-m-d H:i:s')."','".$code."');");
										$conn->exec("UPDATE meli.items SET status= 'error-c', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$items['mpid']."';");
									}
								}
								break;
								case "paused":
								$temp                       = array();
									#$temp['title']              = $item_title;
								$temp['status']             = "active";
								$temp['price']              = $price;
								$temp['available_quantity'] = 8;
								#$temp['buying_mode']     	= "buy_it_now";
								#$temp['listing_type_id'] 	= $listing_type;
								#$temp['shipping']           = array('mode' => 'me2', 'local_pick_up' => 'true', 'free_shipping' => 'true');
									#$temp['pictures']           = $item_img;
								#$temp['video_id']           = $video['url'];
								$temp['warranty']           = $warranty;
								$update                     = $items_manager->update($mpid, $temp);

								if (isset($update->id)) {
									echo $i++ ."-".$items['mpid']."-".date('Y-m-d H:i:s')."- updated/paused -".$sku." - price: ".$price."\n";
									$title     = pg_escape_string(utf8_encode($update->title));
									$permalink = pg_escape_string(utf8_encode($update->permalink));
									$conn->exec("UPDATE meli.items SET mpid = '".$update->id."',title ='".$title."', seller_id = '".$update->seller_id."', category_id = '".$update->category_id."', price = '".$update->price."', sold_quantity = '".$update->sold_quantity."', start_time = '".$update->start_time."', stop_time='".$update->stop_time."', permalink = '".$permalink."', status = '".$update->status."', aws_id = '".$item->id."', automatic_relist = '".$update->automatic_relist."', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$items['mpid']."' and shop_id = '".$application[0]['id']."';");
								} else {
									echo $i++ ."-".$items['mpid']."-".date('Y-m-d H:i:s')."- NO updated/paused -".$sku." - price: ".$price."\n";
									$message = isset($update->message)?htmlspecialchars($update->message, ENT_QUOTES):NULL;
									$code    = isset($update->error)?htmlspecialchars($update->error, ENT_QUOTES):NULL;
									$conn->exec("INSERT INTO log.meli (sku,action_,response, executed_at, code_) VALUES ('".$item->sku."','1','".$message."','".date('Y-m-d H:i:s')."','".$code."');");
									$conn->exec("UPDATE meli.items SET status= 'error-p', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$items['mpid']."';");
								}
								break;
								case "active":
								$temp                       = array();
									#$temp['title']              = $item_title;
								$temp['price']              = $price;
								$temp['available_quantity'] = 8;
								$temp['shipping']           = array('mode' => 'me2', 'local_pick_up' => 'true', 'free_shipping' => 'true');
									#$temp['pictures']           = $item_img;
								$temp['video_id']           = $video['url'];
								$temp['warranty']           = $warranty;
								$update                     = $items_manager->update($mpid, $temp);
								if (isset($update->id)) {
									echo $i++ ."-".$items['mpid']."-".date('Y-m-d H:i:s')."- updated/active -".$sku." - price: ".$price."\n";
									$title     = pg_escape_string(utf8_encode($update->title));
									$permalink = pg_escape_string(utf8_encode($update->permalink));
									$conn->exec("UPDATE meli.items SET mpid = '".$update->id."',title ='".$title."', seller_id = '".$update->seller_id."', category_id = '".$update->category_id."', price = '".$update->price."', sold_quantity = '".$update->sold_quantity."', start_time = '".$update->start_time."', stop_time='".$update->stop_time."', permalink = '".$permalink."', status = '".$update->status."', aws_id = '".$item->id."', automatic_relist = '".$update->automatic_relist."', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$items['mpid']."' and shop_id = '".$application[0]['id']."';");
								} else {
									echo $i++ ."-".$items['mpid']."-".date('Y-m-d H:i:s')."- NO updated/active -".$detail_items[0]->seller_custom_field." - price: ".$price."\n";
									$message = isset($update->message)?htmlspecialchars($update->message, ENT_QUOTES):NULL;
									$code    = isset($update->error)?htmlspecialchars($update->error, ENT_QUOTES):NULL;
									$conn->exec("INSERT INTO log.meli (sku,action_,response, executed_at, code_) VALUES ('".$item->sku."','1','".$message."','".date('Y-m-d H:i:s')."','".$code."');");
									$conn->exec("UPDATE meli.items SET status= 'error-a', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$items['mpid']."';");
								}
								break;
								case "under_review":
								echo $i++ ."-".$items['mpid']."-".date('Y-m-d H:i:s')."- NO updated/ under_review "."\n";
								$conn->exec("UPDATE meli.items SET status= 'under_review', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$items['mpid']."';");
								break;
							}
							#Banner update
							if (isset($traslation->title)) {
								$title            = $traslation->title;
								$item_description = $traslation->description;
							} else {
								$title            = $translate->translate('en', 'es', $item->product_title_english);
								$item_description = $translate->translate('en', 'es', $item->specification_english);
							}
							$item_description = $items_manager->replace_amazon($item_description);

							if ($application[0]['id']==1) {
								$description      = str_replace(".", "\n", $item_description);
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
								$description .= "••••••••• RECUERDA QUE LOS PRECIOS NO INCLUYEN IVA ••••••••";
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
								$description .= "•••••••••••••••••••••••••••••• Ficha Técnica •••••••••••••••••••••••••••••";
								$description .= "\n";
								$description .= "Titulo Original: ";
								$description .= $items_manager->replace_amazon($item->product_title_english);
								$description .= "\n";
								$description .= "Brand: ";
								$description .= $item->brand;
								$description .= "\n";
								$description .= "Model: ";
								$description .= $item->model;
								$description .= "\n";
								$description .= "Weight Unit: ";
								$description .= $item->weight_unit;
								$description .= "\n";
								$description .= "Package Weight:";
								$description .= $item->package_weight;
								$description .= "\n";
								$description .= "Package Width: ";
								$description .= $item->item_width;
								$description .= "\n";
								$description .= "Package Height: ";
								$description .= $item->package_height;
								$description .= "\n";
								$description .= "Package Length: ";
								$description .= $item->package_length;
								$description .= "\n\n\n\n";
								$description .= date("Y-m-d H:i:s");
							}

							if ($application[0]['id']==2) {

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
								$description .= "\n\n\n\n";
								$description .= date("Y-m-d H:i:s");
							}


							$banner               = array();
							$banner['plain_text'] = $description;
							$update_banner        = $items_manager->banner($mpid, $banner);
							$conn->exec("UPDATE meli.items SET is_descripted= 'true' WHERE mpid = '".$mpid."';");
						}
					} else {
						echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- deleted - mpid no found\n";
						$conn->exec("DELETE FROM meli.items WHERE mpid = '".$mpid."' AND shop_id = '".$application[0]['id']."';");
					}
				}
				$conn->close_con();
				echo "Fin update -".date("Y-m-d H:i:s")."\n";


		} catch (Exception $e) {
			http_response_code(404);
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
		$this->conn->close();


	}

	public function update_local_csv() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type application/json; charset=utf-8');

		try {
			$sql   = "select aws.*, meli.* from aws.items as aws join meli.items as meli on meli.aws_id = aws.id where meli.shop_id = '1' and aws.update_date > '2017-08-03 09:00:00' and active = 't' order by meli.mpid desc;";
			$query = pg_query($sql);
			$i     = 0;
			while ($item = pg_fetch_object($query)) {
				#for ($shop = 1; $shop < 3; $shop++) {
				$shop          = 1;
				$application   = pg_fetch_object(pg_query("SELECT access_token, user_name FROM meli.shop WHERE id = '".$shop."'"));
				$items_manager = new items($application->access_token);
				$result        = $items_manager->show_by_sku($item->sku, $application->user_name);
				if (isset($result->results[0])) {
					echo $i."-mpid-".$item->mpid."-".date("Y-m-d H:i:s")."\n";
					$mpid        = $result->results[0];
					$images      = explode("~^~", $item->image_url);
					$heigth_pack = number_format($item->package_height/0.393701, 2);
					$width_pack  = number_format($item->item_width/0.393701, 2);
					$length_pack = number_format($item->package_length/0.393701, 2);
					$weight_pack = number_format($item->package_weight/2.204623, 2);
					$heigth_item = number_format($item->item_height/0.393701, 2);
					$width_item  = number_format($item->item_width/0.393701, 2);
					$length_item = number_format($item->item_length/0.393701, 2);
					$item_title  = $this->translate->translate('en', 'es', $item->product_title_english);
					if (strlen($item_title) > 61) {
						$pos        = strpos($item_title, ' ', 50);
						$item_title = substr($item_title, 0, $pos);

					}
					$item_img         = array();
					$img_1            = $images[0];
					$img_2            = (isset($images[1]))?$images[1]:"";
					$img_3            = (isset($images[2]))?$images[2]:"";
					$img_4            = (isset($images[3]))?$images[3]:"";
					$img_5            = (isset($images[4]))?$images[4]:"";
					$img_6            = (isset($images[5]))?$images[5]:"";
					$item_description = $this->translate->translate('en', 'es', $item->specification_english);
					$date_update      = date('Y-m-d H:i:s');
					for ($j = 0; $j < count($images); $j++) {
						array_push($item_img, array('source' => $images[$j]));
					}
					$data_description = pg_fetch_object(pg_query("SELECT des.*, tem.* FROM meli.description  AS des INNER JOIN meli.templates AS tem ON des.template_id = tem.id WHERE tem.type='1' AND tem.shop_id='".$shop."';"));
					$description      = $data_description->template;
					eval("\$description = \"$description\";");
					$weight = round((($item->package_weight)/100)/2);
					$video  = pg_fetch_object(pg_query("SELECT * FROM meli.video WHERE shop_id = '".$shop."';"));
					$price  = $items_manager->liquidador($item->sale_price, $item->package_weight, $shop);

					if ($item->quantity >= 3 && $item->active == 't' && $item->sale_price < 1700 && $weight < 25) {
						echo "active\n";
						$temp['title']              = $item_title;
						$temp['price']              = $price;
						$temp['available_quantity'] = 8;
						$temp['buying_mode']        = "buy_it_now";
						$temp['pictures']           = $item_img;
						$temp['condition']          = "new";
						$temp['video_id']           = $video->url;
						if ($shop = 1) {
							$temp['warranty'] = "Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente. No nos hacemos responsables de los costos de envío en garantías NO APLICA GARANTIA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA";
						}
						$update = $items_manager->update($mpid, $temp);
						if (isset($update->status)) {
							if ($update->status == 400) {
								$temp                    = array();
								$temp['title']           = $item_title;
								$temp['price']           = $price;
								$temp['quantity']        = 8;
								$temp['buying_mode']     = "buy_it_now";
								$temp['listing_type_id'] = "gold_special";
								#$temp['text']               = $description;
								$temp['video_id']  = $video->url;
								$temp['pictures']  = $item_img;
								$temp['condition'] = "new";
								if ($shop = 1) {
									$temp['warranty'] = "Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente. No nos hacemos responsables de los costos de envío en garantías NO APLICA GARANTIA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA";
								}
								echo "update\n";
								$update = $items_manager->relist($mpid, $temp);
								if ($update->status != 400) {
									echo $update->id."\n";
									echo "relist\n";
									pg_query("UPDATE meli.items SET mpid='".$update->id."',  price='".$price."', status = '".$update->status."',start_time='".$update->start_time."', stop_time='".$update->stop_time."', end_time='".$update->end_time."', permalink='".$update->permalink."', last_updated='".date('Y-m-d H:i:s')."', update_date='".date("Y-m-d H:i:s")."' WHERE mpid = '".$mpid."'");
									pg_query("INSERT INTO log.meli (sku, status, response, executed_at) VALUES ('".$item->sku."','200','".$mpid."','".date("Y-m-d H:i:s")."')");

								} else {
									pg_query("INSERT INTO log.meli (sku, status, response, executed_at) VALUES ('".$item->sku."','400','".$mpid."','".date("Y-m-d H:i:s")."')");
								}
							}
						} else {
							pg_query("UPDATE meli.items SET mpid='".$update->id."',  price='".$price."', status = '".$update->status."',start_time='".$update->start_time."', stop_time='".$update->stop_time."', end_time='".$update->end_time."', permalink='".$update->permalink."', last_updated='".date('Y-m-d H:i:s')."', update_date='".date("Y-m-d H:i:s")."' WHERE mpid = '".$mpid."'");
							pg_query("INSERT INTO log.meli (sku, status, response, executed_at) VALUES ('".$item->sku."','200','".$mpid."','".date("Y-m-d H:i:s")."')");
						}
					} else {
						$update = $items_manager->update($mpid, array('status' => 'closed'));
						echo "closed\n";
						pg_query("UPDATE meli.items SET mpid='".$mpid."', status = 'closed', update_date='".date("Y-m-d H:i:s")."' WHERE mpid = '".$mpid."'");
					}
				}
				#}
				$i++;
			}
		} catch (Exception $e) {
			$this->conn->close();
			echo json_encode(array('msg' => "Wrong Data base Connection", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
	/****							SET MELI BY LOCAL DATABASE FUNCTIONS 						****/
	#Service updating item from SQLServer Data Base
	public function update_local() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type application/json; charset=utf-8');

		try {
			$xml = utf8_encode($_POST['xml']);
			$xml = html_entity_decode(html_entity_decode($xml));
			$xml = simplexml_load_string($xml);
			if ($xml == NULL) {
				http_response_code(400);
				die(json_encode(array("message" => "Main file not found"), JSON_UNESCAPED_UNICODE));
			}
			$items_manager = new items($xml->connection->access_token);
			#Creating temp array
			$temp = array();
			#Match SKU into PG Data Base
			$meli_item = pg_fetch_object(pg_query("SELECT * FROM meli.items WHERE seller_custom_field = '".$xml->product->SKU."'"));
			if ($meli_item == NULL) {
				http_response_code(400);
				die(json_encode(array("message" => "SKU not found"), JSON_UNESCAPED_UNICODE));
			}
			#Getting CBT item from PG Data Base
			if ($meli_item->templates == NULL) {
				$data_description = pg_fetch_object(pg_query("SELECT des.*, tem.* FROM meli.description  AS des INNER JOIN meli.templates AS tem ON des.template_id = tem.id WHERE tem.type='1' AND tem.shop_id='".$xml->connection->application_id."';"));
			} else {
				$data_description = pg_fetch_object(pg_query("SELECT des.*, tem.* FROM meli.description  AS des INNER JOIN meli.templates AS tem ON des.template_id = tem.id WHERE tem.id ='".$meli_item->templates."';"));

			}
			$date_update = date('y-m-d H:i:s');
			foreach ($xml->product as $item) {
				/*
				if ($item->is_prime != "Y") {
				$temp['status'] = "closed";
				$items_manager->update($meli_item->mpid, $temp);
				http_response_code(201);
				die(json_encode(array("message" => "Update succesfull item deleted"), JSON_UNESCAPED_UNICODE));
				}
				 */
				$heigth_pack = number_format($item->package_height/0.393701, 2);
				$width_pack  = number_format($item->package_width/0.393701, 2);
				$length_pack = number_format($item->package_length/0.393701, 2);
				$weight_pack = number_format($item->package_weight/2.204623, 2);
				$heigth_item = number_format($item->package_height/0.393701, 2);
				$width_item  = number_format($item->package_width/0.393701, 2);
				$length_item = number_format($item->package_length/0.393701, 2);
				$item_title  = $this->translate->translate('en', 'es', $item->product_title_english);
				$item_title  = substr($item_title, 0, 60);
				$item_img    = array();
				$img_1       = $item->images->url[0];
				$img_2       = $item->images->url[1];
				$img_3       = $item->images->url[2];
				$img_4       = $item->images->url[3];
				$img_5       = $item->images->url[4];
				$img_6       = $item->images->url[5];
				for ($j = 0; $j < count($item->images->url); $j++) {
					array_push($item_img, array('source' => $item->images->url[$j]));
				}
				if (!$meli_item->is_descripted) {
					$description = $data_description->template;
					eval("\$description = \"$description\";");
				}
				$price       = $items_manager->liquidador($item->sale_price, $item->package_weight, 1);
				$category_id = $items_manager->getCategoriesPredictor($item_title);
				$category    = $items_manager->validateCategory($category_id);

				$temp['title']              = $item_title;
				$temp['price']              = $price;
				$temp['available_quantity'] = ($item->quantity == 0)?10:$item->quantity;
				$temp['text']               = $description;
				$temp['video_id']           = $data_description->video;
				$temp['pictures']           = $item_img;
				if ($item->status == "closed") {
					$update = $items_manager->relist($meli_item->mpid, $temp);
					if (!isset($update)) {
						pg_query($this->conn->conn, "UPDATE meli.items SET mpid='".$update->id."', title='".$item_title."', price='".$price."', start_time='".$update->start_time."', stop_time='".$update->stop_time."', end_time='".$update->end_time."', permalink='".$update->permalink."', last_updated='".date('y-m-d H:i:s')."', update_date='".date('y-m-d H:i:s')."' WHERE id = '".$meli_item->id."'");
						$this->conn->close();
						http_response_code(202);
						die(json_encode(array("message" => "Update succesfull"), JSON_UNESCAPED_UNICODE));
					} else {
						http_response_code(404);
						die(json_encode(array('message' => 'Something Wrong re list', 'detail' => $update)));
					}
				} else {
					$update = $items_manager->update($meli_item->mpid, $temp);
					pg_query($this->conn->conn, "UPDATE meli.items SET title='".$item_title."', price='".$price."', last_updated='".date('y-m-d H:i:s')."', update_date='".date('y-m-d H:i:s')."' WHERE id = '".$meli_item->id."'");
					if (!isset($update)) {
						$this->conn->close();
						http_response_code(202);
						die(json_encode(array("message" => "Update succesfull"), JSON_UNESCAPED_UNICODE));
					} else {
						http_response_code(404);
						die(json_encode(array('message' => 'Something Wrong open', 'detail' => $update)));
					}
				}
			}} catch (Exception $e) {
			$this->conn->close();
			echo json_encode(array('msg' => "Wrong Data base Connection", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
	public function update_local_base() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type application/json; charset=utf-8');

		try {
			$xml = $_POST['xml'];
			$xml = simplexml_load_string($xml);
			#$xml = simplexml_load_file("../docs/cbt.xml");
			if ($xml == NULL) {
				http_response_code(400);
				die(json_encode(array("message" => "Main file not found"), JSON_UNESCAPED_UNICODE));
			}
			$items_manager = new items($xml->connection->access_token);
			#Creating temp array
			$temp = array();
			#Match SKU into PG Data Base
			$meli_item = pg_fetch_object(pg_query("SELECT * FROM meli.items WHERE seller_custom_field = '".$xml->product->SKU."'"));
			if ($meli_item == NULL) {
				http_response_code(400);
				die(json_encode(array("message" => "SKU not found"), JSON_UNESCAPED_UNICODE));
			}
			#Getting CBT item from PG Data Base
			if ($meli_item->templates == NULL) {
				$data_description = pg_fetch_object(pg_query("SELECT des.*, tem.* FROM meli.description  AS des INNER JOIN meli.templates AS tem ON des.template_id = tem.id WHERE tem.type='1' AND tem.shop_id='".$xml->connection->application_id."';"));
			} else {
				$data_description = pg_fetch_object(pg_query("SELECT des.*, tem.* FROM meli.description  AS des INNER JOIN meli.templates AS tem ON des.template_id = tem.id WHERE tem.id ='".$meli_item->templates."';"));

			}
			$date_update = date('y-m-d H:i:s');
			foreach ($xml->product as $item) {
				if ($item->is_prime != "Y") {
					$temp['status'] = "closed";
					$items_manager->update($meli_item->mpid, $temp);
					http_response_code(201);
					die(json_encode(array("message" => "Update succesfull item deleted"), JSON_UNESCAPED_UNICODE));
				}
				$heigth_pack = number_format($item->package_height/0.393701, 2);
				$width_pack  = number_format($item->package_width/0.393701, 2);
				$length_pack = number_format($item->package_length/0.393701, 2);
				$weight_pack = number_format($item->package_weight/2.204623, 2);
				$heigth_item = number_format($item->package_height/0.393701, 2);
				$width_item  = number_format($item->package_width/0.393701, 2);
				$length_item = number_format($item->package_length/0.393701, 2);
				$item_title  = $this->translate->translate('en', 'es', $item->product_title_english);
				$item_title  = substr($item_title, 0, 60);
				$item_img    = array();
				$img_1       = $item->images->url[0];
				$img_2       = $item->images->url[1];
				$img_3       = $item->images->url[2];
				$img_4       = $item->images->url[3];
				$img_5       = $item->images->url[4];
				$img_6       = $item->images->url[5];
				for ($j = 0; $j < count($item->images->url); $j++) {
					array_push($item_img, array('source' => $item->images->url[$j]));
				}
				if (!$meli_item->is_descripted) {
					$description = $data_description->template;
					eval("\$description = \"$description\";");
				}
				$price       = $items_manager->liquidador($item->sale_price, $item->package_weight, $xml->connection->application_id);
				$category_id = $items_manager->getCategoriesPredictor($item_title);
				$category    = $items_manager->validateCategory($category_id);

				$temp['title']              = $item_title;
				$temp['price']              = $price;
				$temp['available_quantity'] = ($item->quantity == 0)?10:$item->quantity;
				$temp['text']               = $description;
				$temp['video_id']           = $data_description->video;
				$temp['pictures']           = $item_img;
				if ($item->status == "closed") {
					$update = $items_manager->relist($meli_item->mpid, $temp);
					if (!isset($update)) {
						pg_query($this->conn->conn, "UPDATE meli.items SET mpid='".$update->id."', title='".$item_title."', price='".$price."', start_time='".$update->start_time."', stop_time='".$update->stop_time."', end_time='".$update->end_time."', permalink='".$update->permalink."', last_updated='".date('y-m-d H:i:s')."', update_date='".date('y-m-d H:i:s')."' WHERE id = '".$meli_item->id."'");
						$this->conn->close();
						http_response_code(202);
						die(json_encode(array("message" => "Update succesfull"), JSON_UNESCAPED_UNICODE));
					} else {
						http_response_code(404);
						die(json_encode(array('message' => 'Something Wrong')));
					}
				} else {
					$update = $items_manager->update($meli_item->mpid, $temp);
					pg_query($this->conn->conn, "UPDATE meli.items SET title='".$item_title."', price='".$price."', last_updated='".date('y-m-d H:i:s')."', update_date='".date('y-m-d H:i:s')."' WHERE id = '".$meli_item->id."'");
					if (!isset($update)) {
						$this->conn->close();
						http_response_code(202);
						die(json_encode(array("message" => "Update succesfull"), JSON_UNESCAPED_UNICODE));
					} else {
						http_response_code(404);
						die(json_encode(array('message' => 'Something Wrong')));
					}
				}
			}} catch (Exception $e) {
			$this->conn->close();
			echo json_encode(array('msg' => "Wrong Data base Connection", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
	/****							SET CBT BY AWS SERVICE FUNCTIONS 						****/
	#Service updating item from AWS Service
	public function update_aws() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type application/json; charset=utf-8');
		try {
			$access_token                  = $_POST['access_token'];
			$application_id                = $_POST['application'];
			$item['SKU']                   = $_POST['SKU'];
			$item['product_title_english'] = $_POST['product_title_english'];
			$item['specification_english'] = $_POST['specification_english'];
			$item['sale_price']            = $_POST['sale_price'];
			$item['quantity']              = $_POST['quantity'];
			$item['package_weight']        = $_POST['package_weight'];
			$item['is_prime']              = $_POST['is_prime'];
			$items_manager                 = new items($access_token);
			$response                      = array();
			#Match SKU into PG Data Base
			$aws_item = pg_fetch_object(pg_query("SELECT * FROM aws.items WHERE sku = '".$item['SKU']."'"));
			#Getting CBT item from PG Data Base
			$cbt_item = pg_fetch_object(pg_query("SELECT id, mpid FROM cbt.items WHERE aws_id = '".$aws_item->id."'"));
			if ($item['is_prime'] !== 1) {
				$items_manager->delete_item($cbt_item->mpid);
				http_response_code(201);
				die(json_encode(array("message" => "Update succesfull item deleted"), JSON_UNESCAPED_UNICODE));
			}
			$response = $items_manager->update_item($item, $cbt_item->mpid);
			if (!isset($response->error)) {
				pg_query($this->conn->conn, "UPDATE cbt.items SET title='".$response->product_title_english."', price='".$response->sale_price."', status='".$response->status."', update_date='".date('y-m-d H:i:s')."' WHERE id = '".$cbt_item->id."'");
				$this->conn->close();
				http_response_code(202);
				die(json_encode(array("message" => "Update succesfull"), JSON_UNESCAPED_UNICODE));
			}
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}

}
