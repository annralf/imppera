<?php
/*Testing Update functions unsincronous*/
include '/var/www/html/enkargo/config/meli_items.php';
#include '/var/www/html/enkargo/config/pdo_connector.php';
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
		try {
			
			echo "Update process Begin-".date("Y-m-d H:i:s")."\n";
			$updated_items = "";
			$description = "";
			$i = 0;
			switch ($this->shop[0]['id']) {
				case '1':
				$id_seq		= 4;
				$warranty 	= "*** TRADUCCIÓN AUTOMÁTICA, VERIFICAR CARACTERISTICAS ANTES DE COMPRAR***
				Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente. No nos hacemos responsables de los costos de envío en garantías NO APLICA GARANTIA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA";
				$shop_ml	= "shop_ml_qb";
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
				$description .= "*************INFORMACIÓN IMPORTANTE***************";
				$description .= "\n";
				$description .= "**** RECUERDA QUE LOS PRECIOS NO INCLUYEN IVA ******";
				$description .= "\n";
				$description .= "Recuerda que antes de comprar, puedes hacer uso de la herramienta de preguntas, en donde un asesor te liberara todas tus dudas y así puedas estar totalmente confiado de lo que estas comprando.
				Vale la pena recordarte que al ser un producto importado y al ser distribuido a varios países en Latinoamérica, la disponibilidad del producto puede variar, al igual que las tallas y colores están sujetas a la disponibilidad, en algunos casos puede que el valor del producto por cuestiones de tamaño, color o proveedor pueda Cambiar.";
				$description .= "\n";
				$description .= "\n";
				$description .= "****Ficha Técnica****";
				$description .= "\n";
				$description .= "Titulo Original:";
				$video_shop = 4;
				break;
				case '2':
				$id_seq		= 5;
				$warranty 	= "*** TRADUCCIÓN AUTOMÁTICA, VERIFICAR CARACTERISTICAS ANTES DE COMPRAR***
				Este es un artículo importado, tiene una garantía de 30 días por defectos de fábrica, la misma no incluye cualquier daño ocasionado por la empresa transportadora. Mauxi eshop no se hace responsable de los costos de envío del producto en caso de aplicar la garantía";
				$shop_ml	= "shop_ml_mx";
				$description .= "\n";
				$description .= "\n";
				$description .= "\n";
				$description .= "\n";			
				$description .= "******* ESTE ES UN ARTICULO IMPORTADO DESDE USA********";
				$description .= "\n";
				$description .= "\n";
				$description .= "*************** TIEMPO DE ENTREGA ***************";
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
				$description .= "******** RECUERDA QUE LOS PRECIOS NO INCLUYEN IVA ******";
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
				$video_shop = 5;
				break;
			}
			switch ($this->type) {
				case 'unique':
				$meli_item = $this->conn->prepare("select aws_id from meli.items where mpid = '".$alias."';");
				$meli_item->execute();
				$meli_item = $meli_item->fetchAll();
				$item = $this->conn->prepare("SELECT DISTINCT id, sku, image_url,quantity, active, round(cast((package_height/0.393701) as numeric),2) as package_height, round(cast((item_width/0.393701) as numeric),2) as item_width, round(cast((package_length/0.393701) as numeric),2) as package_length, round(cast((item_height/0.393701) as numeric),2) as item_height, round(cast((item_length/0.393701) as numeric),2) as item_length, product_title_english, specification_english, brand, round(cast((package_weight/2.204623)/100 as numeric),2) as package_weight, sale_price, bolborrado, brand, model, weight_unit FROM aws.items WHERE id = '".$meli_item[0]['aws_id']."';");
				$item->execute();
				$item = (object) $item->fetch();
				break;

				case 'massive':
				$secuence = $this->conn->prepare("SELECT * FROM meli.secuences WHERE id = '".$id_seq."';");
				$secuence->execute();
				$secuence        = $secuence->fetchAll();
				$offset          = $secuence[0]['offset_']+$alias;
				$meli_offset     = 0;
				$meli_limit      = $alias;
				$this->conn->exec("UPDATE meli.secuences SET offset_ ='".$offset."' WHERE id = '".$id_seq."';");

				$sql = "SELECT a.id as id, a.sku, a.image_url,a.quantity, a.active, round(cast((a.package_height/0.393701) as numeric),2) as package_height, round(cast((a.item_width/0.393701) as numeric),2) as item_width, round(cast((a.package_length/0.393701) as numeric),2) as package_length, round(cast((a.item_height/0.393701) as numeric),2) as item_height, round(cast((a.item_length/0.393701) as numeric),2) as item_length, a.product_title_english, a.specification_english, a.brand, round(cast((a.package_weight/2.204623)/100 as numeric),2) as package_weight, a.sale_price, a.bolborrado as aws_bolborrado, a.brand, a.model, a.weight_unit, m.mpid, m.bolborrado as meli_bolborrado, a.is_prime as prime FROM aws.items as a JOIN meli.items as m on a.id = m.aws_id where m.shop_id = '".$this->shop[0]['id']."' order by m.update_date asc offset '".$meli_offset."' limit '".$meli_limit."';";
				$item = $this->conn->prepare($sql);
				$item->execute();
				$item = $item->fetchAll();
				$this->conn->close_con();
				break;	
			}
			#Getting Video id
			$video = $this->conn->prepare("select * from meli.video where shop_id = '".$this->shop[0]['id']."' and id = '".$video_shop."';");
			$video->execute();
			$video = $video->fetchAll();
			#
			#update array
			$update_array = array();
			$update_array['Available_quantity'] = 8;
			$update_array['shipping']        = array('mode' => 'me2', 'local_pick_up' => 'false', 'free_shipping' => 'true');
			$update_array['warranty'] = $warranty;

			$listing_type = 'gold_special';
			#Manager Item


			$this->conn->beginTransaction();

			foreach ($item as $items) {

				$mpid         = $items['mpid'];
				$updated_items .= "'".$mpid."',";
				$detail_items = $this->meli->show($mpid);
				$item_description = "";
				if (isset($detail_items->status)) {
					echo "Error de conexion ".$detail_items->message."\n";
					break;
				}
				#check MELI local avaliability
				if(isset($detail_items[0]->id)){
					$item_status = $detail_items[0]->status;
					switch ($items['meli_bolborrado']) {
						case 1:
								#Close for delete items from meli sentence
							echo $i++ ."\t-".$mpid."-".date('Y-m-d H:i:s')."- Bolborrado 1 MELI\n";
							$items_manager->paused_item($detail_items[0]->status, $mpid, NULL);
							$this->conn->exec("UPDATE meli.items SET bolborrado = 1, status= 'closed', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
						break;

						case 2:
								#Paused S3 items from meli sentence
							echo $i++ ."\t-".$mpid."-".date('Y-m-d H:i:s')."- Item S3 MELI\n";
							$items_manager->paused_item($detail_items[0]->status, $mpid, NULL);
							$this->conn->exec("UPDATE meli.items SET bolborrado = 2, status= 'paused', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
						break;

						case 3:
								#Delete closed items
							echo $i++ ."\t-".$mpid."-".date('Y-m-d H:i:s')."- Delete MELI\n";
							$items_manager->paused_item($detail_items[0]->status, $mpid, "delete_item");
							$this->conn->exec("UPDATE meli.items SET bolborrado = 3, status= 'deleted', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
						break;

						case 9:
								#Delete closed items
							echo $i++ ."\t-".$mpid."-".date('Y-m-d H:i:s')."- Stock Interno Enkargo\n";
							$this->conn->exec("UPDATE meli.items SET status= 'Stock', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
						break;
						default:
						#Default cases
						#Check AWS Avaliability
						switch ($items['aws_bolborrado']) {
							case '1':
								echo $i++ ."\t-".$mpid."-".date('Y-m-d H:i:s')."- Bolborrado AWS - ".$items['sku']."\n";
								$this->meli->paused_item($item_status, $mpid, NULL);
								$this->conn->exec("UPDATE meli.items SET bolborrado = 3, status= 'closed', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");					
							break;

							default:
							#Check AWS price Avaliability
							if ($items['sale_price'] == 0) {
								echo $i++ ."\t-".$mpid."-".date('Y-m-d H:i:s')."- Sale price 0 AWS - ".$items['sku']."\n";
								$this->meli->paused_item($item_status, $mpid, NULL);
								$this->conn->exec("UPDATE meli.items SET bolborrado = 0, status= 'paused' ,aws_id='".$items['id']."', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
							}
							#Check AWS Price Limit
							elseif ($items['sale_price'] > 2500) {
								echo $i++ ."\t-".$mpid."-".date('Y-m-d H:i:s')."- Price over limit - ".$items['sku']."\n";
								$this->meli->paused_item($item_status, $mpid, NULL);
								$this->conn->exec("UPDATE meli.items SET bolborrado = 3, status= 'paused' ,aws_id=".$items['id'].", update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
							}
							#Check AWS Prime
							elseif ($items['prime'] = 0) {
								echo $i++ ."\t-".$mpid."-".date('Y-m-d H:i:s')."- no prime - ".$items['sku']."\n";
								$this->meli->paused_item($item_status, $mpid, NULL);
								$this->conn->exec("UPDATE meli.items SET bolborrado = 3, status= 'paused' ,aws_id=".$items['id'].", update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
							}						
							#Check AWS Status
							elseif ($items['active'] == 0) {
								echo $i++ ."\t-".$mpid."-".date('Y-m-d H:i:s')."- Unactive AWS - ".$items['sku']."\n";
								$this->meli->paused_item($item_status, $mpid, NULL);
								$this->conn->exec("UPDATE meli.items SET bolborrado = 3, status= 'paused' ,aws_id=".$items['id'].", update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
							}
							#Check AWS Weight
							elseif ($items['package_weight'] >= 25) {
								echo $i++ ."\t-".$mpid."-".date('Y-m-d H:i:s')."- Weight limit AWS - ".$items['sku']."\n";
								$this->meli->delete_item($item_status, $mpid, "delete_item");
								$this->conn->exec("UPDATE meli.items SET bolborrado = 3, status= 'paused' ,aws_id=".$items['id'].", update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
							}
							#Check AWS Quantity
							elseif ($items['quantity'] < 1) {
								echo $i++ ."\t-".$mpid."-".date('Y-m-d H:i:s')."- Without stock AWS - ".$items['sku']."\n";
								$this->meli->paused_item($item_status, $mpid, NULL);
								$this->conn->exec("UPDATE meli.items SET bolborrado = 0, status= 'paused' ,aws_id=".$items['id'].", update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
							}else{

							#Set MELI price
								$update_array['price'] = $this->meli->liquidador($items['sale_price'], $items['package_weight'], $this->shop[0]['id']);
							#Check MELI Status
								switch ($item_status) {
									case "closed":
									case "inactive":
									$update_array['status'] = "active";
									$update = $this->meli->relist($mpid, $update_array);
									if (isset($update->id)) {
										echo $i++ ."-".$items['mpid']."-".date('Y-m-d H:i:s')."- relisted/close -".$update->id."\n";
										$mpid      = $update->id;
										$title     = pg_escape_string(utf8_encode($update->title));
										$permalink = pg_escape_string(utf8_encode($update->permalink));
										$this->conn->exec("UPDATE meli.items SET mpid = '".$update->id."',title ='".$title."', seller_id = '".$update->seller_id."', category_id = '".$update->category_id."', price = '".$update->price."', sold_quantity = '".$update->sold_quantity."', start_time = '".$update->start_time."', stop_time='".$update->stop_time."', permalink = '".$permalink."', status = '".$update->status."', aws_id = '".$items['id']."', automatic_relist = '".$update->automatic_relist."', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$items['mpid']."' and shop_id = '".$application[0]['id']."';");
									} else {
										if (in_array("deleted", $detail_items[0]->sub_status)) {
											$this->conn->exec("UPDATE meli.items SET bolborrado = 3, status= 'delete', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$mpid."';");
											echo $i++ ."-".$items['mpid']."-".date('Y-m-d H:i:s')."- NO relisted/already deleted -".$mpid."\n";
										} else {
											echo $i++ ."-".$items['mpid']."-".date('Y-m-d H:i:s')."- NO relisted/Error -".$mpid."\n";
											$message = isset($update->message)?htmlspecialchars($update->message, ENT_QUOTES):NULL;
											$code    = isset($update->error)?htmlspecialchars($update->error, ENT_QUOTES):NULL;
											$this->conn->exec("INSERT INTO log.meli (sku,action_,response, executed_at, code_) VALUES ('".$items['sku']."','1','".$message."','".date('Y-m-d H:i:s')."','".$code."');");
											$this->conn->exec("UPDATE meli.items SET status= 'error-c', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$items['mpid']."';");
										}
									}
									break;
									case 'paused':
									$update_array['status'] = "active";
									$update_array['listing_type_id'] = $listing_type;
									$update_array['video_id']        = $video['url'];
									$update = $this->meli->update($mpid, $update_array);
									if (isset($update->id)) {
										echo $i++ ."-".$items['mpid']."-".date('Y-m-d H:i:s')."- updated/paused -".$items['sku']." - price: ".$price."\n";
										$title     = pg_escape_string(utf8_encode($update->title));
										$permalink = pg_escape_string(utf8_encode($update->permalink));
										$this->conn->exec("UPDATE meli.items SET mpid = '".$update->id."',title ='".$title."', seller_id = '".$update->seller_id."', category_id = '".$update->category_id."', price = '".$update->price."', sold_quantity = '".$update->sold_quantity."', start_time = '".$update->start_time."', stop_time='".$update->stop_time."', permalink = '".$permalink."', status = '".$update->status."', aws_id = '".$items['id']."', automatic_relist = '".$update->automatic_relist."', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$items['mpid']."' and shop_id = '".$application[0]['id']."';");
									} else {
										echo $i++ ."-".$items['mpid']."-".date('Y-m-d H:i:s')."- NO updated/paused -".$items['sku']." - price: ".$price."\n";
										$message = isset($update->message)?htmlspecialchars($update->message, ENT_QUOTES):NULL;
										$code    = isset($update->error)?htmlspecialchars($update->error, ENT_QUOTES):NULL;
										$this->conn->exec("INSERT INTO log.meli (sku,action_,response, executed_at, code_) VALUES ('".$items['sku']."','1','".$message."','".date('Y-m-d H:i:s')."','".$code."');");
										$this->conn->exec("UPDATE meli.items SET status= 'error-p', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$items['mpid']."';");
									}
									case 'active':								
									$item_description = $this->translate->translate('en', 'es', $items['specification_english']);
									$description_detail = "";
									$description_detail .= $this->meli->replace_amazon($items['product_title_english']);
									$description_detail .= "\n";
									$description_detail .= "Brand:";
									$description_detail .= $items['brand'];
									$description_detail .= "\n";
									$description_detail .= "Model:";
									$description_detail .= $items['model'];
									$description_detail .= "\n";
									$description_detail .= "Weight Unit:";
									$description_detail .= $items['weight_unit'];
									$description_detail .= "\n";
									$description_detail .= "Package Weight:";
									$description_detail .= $items['package_weight'];
									$description_detail .= "\n";
									$description_detail .= "Package Width:";
									$description_detail .= $items['item_width'];
									$description_detail .= "\n";
									$description_detail .= "Package Height:";
									$description_detail .= $items['package_height'];
									$description_detail .= "\n";
									$description_detail .= "Package Length:";
									$description_detail .= $items['package_length'];
									$description_detail .= "\n\n\n\n";
									$description_detail .= date("Y-m-d H:i:s");
									$item_description .= $description.$description_detail;
									$array_description = array('plain_text'=>$item_description);
									$update_description = $this->meli->banner($mpid, $array_description);
									$update = $this->meli->update($mpid, $update_array);
									if (isset($update->id)) {
										echo $i++ ."-".$items['mpid']."-".date('Y-m-d H:i:s')."- updated/active -".$items['sku']." - price: ".$price."\n";
										$title     = pg_escape_string(utf8_encode($update->title));
										$permalink = pg_escape_string(utf8_encode($update->permalink));
										$this->conn->exec("UPDATE meli.items SET mpid = '".$update->id."',title ='".$title."', seller_id = '".$update->seller_id."', category_id = '".$update->category_id."', price = '".$update->price."', sold_quantity = '".$update->sold_quantity."', start_time = '".$update->start_time."', stop_time='".$update->stop_time."', permalink = '".$permalink."', status = '".$update->status."', aws_id = '".$items['id']."', automatic_relist = '".$update->automatic_relist."', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$items['mpid']."' and shop_id = '".$application[0]['id']."';");
									} else {
										echo $i++ ."-".$items['mpid']."-".date('Y-m-d H:i:s')."- NO updated/active -".$detail_items[0]->seller_custom_field." - price: ".$price."\n";
										$message = isset($update->message)?htmlspecialchars($update->message, ENT_QUOTES):NULL;
										$code    = isset($update->error)?htmlspecialchars($update->error, ENT_QUOTES):NULL;
										$this->conn->exec("INSERT INTO log.meli (sku,action_,response, executed_at, code_) VALUES ('".$items['sku']."','1','".$message."','".date('Y-m-d H:i:s')."','".$code."');");
										$this->conn->exec("UPDATE meli.items SET status= 'error-a', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$items['mpid']."';");
									}
									break;
									case "under_review":
									echo $i++ ."\t-".$items['mpid']."-".date('Y-m-d H:i:s')."- NO updated/ under_review "."\n";
									$this->conn->exec("UPDATE meli.items SET status= 'under_review', update_date = '".date('Y-m-d H:i:s')."' WHERE mpid = '".$items['mpid']."';");
									break;
								}
							}
							break;
						}
						break;
					}
					
				}else{
					echo $i++ ."\t-".$mpid."-".date('Y-m-d H:i:s')."- deleted - mpid no found\n";
				}
			}

			$this->conn->commit();
			echo "Fin commit-".date('Y-m-d H:i:s')."***********************************************\n";
			$this->conn->close_con();

		} catch (Exception $e) {
			http_response_code(404);
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
		$this->conn->close();
	}
}
#Test section
#$test = new MeliUpdate(2,'massive');
#$test->update(100);