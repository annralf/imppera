<?php
include '/var/www/html/enkargo/config/meli_items.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';


echo "Update process Begin-".date("Y-m-d H:i:s")."\n";
#connection with Data base and application details
$i           = 1;
$conn        = new DataBase();
$application = $conn->prepare("SELECT * FROM meli.shop WHERE id = '2';");
$application->execute();
$application   = $application->fetchAll();
$shop          = $application[0]['id'];
$translate     = new GoogleTranslate();
$items_manager = new items($application[0]['access_token']);
#Getting secuences info
$secuence = $conn->prepare("SELECT * FROM meli.secuences WHERE type = 'getItemsMa';");
$secuence->execute();
$secuence        = $secuence->fetchAll();
$offset          = $secuence[0]['offset_']+35000;
$secuence_update = $conn->prepare("UPDATE meli.secuences SET offset_ ='".$offset."' WHERE type = 'getItemsMa';");
$secuence_update->execute();
#Update resource manager <Get info about MPIDs to update> it can depend of update needle
$items_research = $conn->prepare("select m.mpid, m.bolborrado from meli.items m where m.shop_id='".$application[0]['id']."' order by m.update_date asc offset '".$secuence[0]['offset_']."' limit '".$secuence[0]['limit_']."'");
#$items_research = $conn->prepare("select m.mpid, m.bolborrado from meli.items m join aws.items as a on a.id = m.aws_id where m.shop_id='".$application[0]['id']."' and m.bolborrado = 2;");
$items_research->execute();
$items_research = $items_research->fetchAll();
$conn->close_con();

#Main item search from MELI resources
#Iterate around MPIDs found and make respective task
foreach ($items_research as $items) {
	$temp         = array();
	$mpid         = $items['mpid'];
	$detail_items = $items_manager->show($mpid);
	#sleep(1);
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

				if ($sku == null) {#Close onLine items and delete at database if SKU is not found
					echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- SKU not found AWS or Item Deleted at MELI\n";
					$items_manager->paused_item($detail_items[0]->status, $mpid, "delete_item");
					$conn->exec("UPDATE meli.items SET bolborrado = 3, status= 'delete', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
					break;
				}
				if ($item->bolborrado == 1) {#Close onLine items and delete at database if Bolborrador from AWS is 1
					echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- Bolborrado AWS - ".$sku;
					echo "--- ".$item->bolborrado." ---\n";
					$items_manager->paused_item($detail_items[0]->status, $mpid, NULL);
					$conn->exec("UPDATE meli.items SET bolborrado = 3, status= 'closed', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
					break;
				}
				#Update AWS stock
				$conn->exec("UPDATE aws.items SET shop_ml_mx = '1' WHERE id = '".$item->id."';");
				if ($item->sale_price == 0) {
					echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- Sale price 0 AWS - ".$sku."\n";
					$items_manager->paused_item($detail_items[0]->status, $mpid, NULL);
					$conn->exec("UPDATE meli.items SET bolborrado = 2, status= 'closed' ,aws_id=".$item->id.", update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
					break;
				}
				if ($item->active == 0) {
					echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- Unactive AWS - ".$sku;
					echo "--- ".$item->active." ---\n";
					$items_manager->paused_item($detail_items[0]->status, $mpid, NULL);
					$conn->exec("UPDATE meli.items SET bolborrado = 2, status= 'closed' ,aws_id=".$item->id.", update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
					break;
				}
				if ($item->package_weight >= 25) {
					echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- Weight limit AWS - ".$sku;
					echo "--- ".$item->package_weight." ---\n";
					$items_manager->delete_item($detail_items[0]->status, $mpid, "delete_item");
					$conn->exec("UPDATE meli.items SET bolborrado = 2, status= 'closed' ,aws_id=".$item->id.", update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
					break;
				}
				if ($item->quantity < 1) {
					echo $i++ ."-".$mpid."-".date('Y-m-d H:i:s')."- Without stock AWS - ".$sku;
					echo "--- ".$item->quantity." ---\n";
					$items_manager->paused_item($detail_items[0]->status, $mpid, NULL);
					$conn->exec("UPDATE meli.items SET bolborrado = 2, status= 'closed' ,aws_id=".$item->id.", update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
					break;
				}
				#Working with active items
				#Getting Description variables
				$traslation = $conn->prepare("SELECT title, description FROM corrections.spanish WHERE sku = '".$sku."';");
				$traslation->execute();
				$traslation   = (object) $traslation->fetch();
				$listing_type = $detail_items[0]->listing_type_id;
				$video        = $conn->prepare("SELECT * FROM meli.video WHERE shop_id = '".$shop."' AND id = 5;");
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
					$temp['warranty']        = "Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente. No nos hacemos responsables de los costos de envío en garantías NO APLICA GARANTIA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA";
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
					$temp['shipping']           = array('mode' => 'me2', 'local_pick_up' => 'true', 'free_shipping' => 'true');
						#$temp['pictures']           = $item_img;
					$temp['video_id']           = $video['url'];
					$temp['warranty']           = "Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente. No nos hacemos responsables de los costos de envío en garantías NO APLICA GARANTIA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA";
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
					$temp['warranty']           = "Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente. No nos hacemos responsables de los costos de envío en garantías NO APLICA GARANTIA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA";
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
	echo "Fin -".date("Y-m-d H:i:s")."\n";