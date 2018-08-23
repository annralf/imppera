<?php
include '/var/www/html/enkargo/config/meli_items.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';
#include_once '/var/www/html/enkargo/services/aws_update.php';
#include '/var/www/html/enkargo/config/conex_manager.php';
/*
 * 
*/
class MeliUpdate
{
	public $shop;
	public $type;
	public $conn;
	public $meli;
	
	public $conn_sql;
	function __construct($shop, $type,$group)
	{
		#$update_var  	= new aws_update("AKIAIIIRMA23I5V5K6OA","zclSWetDwNJLyPw64Ipg+7k7Jzl0P9pHZtJZ4HqQ","karengonza10-20");

		$this->conn = new DataBase();
		$application = $this->conn->prepare("select * from meli.shop where id = '".$shop."';");
		$application->execute();
		$this->shop = $application->fetchAll();
		$this->meli = new items($this->shop[0]['access_token'],$this->shop[0]['user_name']);
		$this->type = $type;
		$this->group = $group;
		
	}

	function consulta($alias){
		$detail_items	="";
		$detail_items 	= $this->meli->show($alias);

		if (isset($detail_items->error)){

			return 0;
		}
		if($detail_items[0]->seller_id == $this->shop[0]['user_name']){
			if (isset($detail_items[0]->seller_custom_field)){

				$seller = $detail_items[0]->seller_custom_field;
				return $seller;
			}else{

				return 1;
			}
		}else{

			return 2;
		}
	}
	function update($alias,$index){
		
		$updated_items = "";
		$description   = "";
		$avaliable_d   = "";
		$i = 0;
		switch ($this->shop[0]['id']) {
			case '1':
				#23f0
				$a1="&#x23f0;";
				$a2="&#x23f0;";
				$b="&#9620;";
				$c="&#x23e9;";

				$id_seq		= 4;
				$warranty 	= "*** TRADUCCIÓN AUTOMÁTICA, VERIFICAR CARACTERISTICAS ANTES DE COMPRAR***
				Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente. No nos hacemos responsables de los costos de envío en garantías NO APLICA GARANTIA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA";
				$shop_ml	= "shop_ml_qb";

				$name_shop 	= "&#8474;UEEN BEE\n\n";

				$description .= "&#x2708; ESTE ARTICULO ES IMPORTADO DESDE USA";
				$description .= "\n";
				$description .= "\n";
				$description .= $c."  MÉTODOS DE ENVÍO\n";
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
				$description .= "&#9995;  RETRACTO  &#10071;&#10071;&#10071;\n";
				$description .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
				$description .= "\n";
				$description .= "* En caso de Retracto, es decir que ya no desee el producto que compro, puede realizar la devolución en un tiempo no mayor a 5 días después de entregado, Para esto el cliente deberá pagar el costo de retorno del producto a Estados Unidos, este varia según el peso y el tamaño del articulo comprado, el producto debe ser devuelto en el mismo estado en el que se entrego, caja y empaque original y sin ningún signo de uso, de no ser así NO SERA ACEPTADO EL RETRACTO.";
				$description .= "\n";
				$description .= "\n";
				$description .= $c."  GARANTÍA \n";
				$description .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
				$description .= "\n";
				$description .= "* Nuestros proveedores nos brindan 30 días de garantía directa, la cual extendemos y respetamos con nuestros clientes, esta garantía solamente cubre daños y defectos de fabrica, no aplica garantía para productos con defectos POR MALA MANIPULACION.";
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
				$description .= $c."  INFORMACIÓN IMPORTANTE\n";
				$description .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10102; Recuerda que antes de comprar, puedes hacer uso de la herramienta de preguntas, en donde un asesor te liberara todas tus dudas y así puedas estar totalmente confiado de lo que estas comprando.";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10103; Es importante recordar que al ser un producto importado y al ser distribuido a varios países en Latinoamérica, la DISPONIBILIDAD Y PRECIO del producto puede variar DIARIAMENTE, al igual que las tallas y colores están sujetas a la disponibilidad, en algunos casos puede que el valor del producto por cuestiones de tamaño, color o proveedor pueda cambiar ya que diariamente se actualizan de forma AUTOMÁTICA los precios con los de nuestros proveedores.";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10104; Por favor indícanos tu talla, color o Referencia por medio de las preguntas para confirmarte la Disponibilidad de la misma antes de que realices la compra. También una vez comprado el producto puedes confirmar esta información por medio de los mensajes de la plataforma. Recuerda que el precio publicado pertenece a la talla y color descritos en la ficha tecnica. Otras tallas y colores pueden llegar a variar en su precio.";
				$description .= "\n";
				$description .= "\n";
				$video_shop = 4;
				break;
			case '2':
				$a1="&#9940;";
				$a2="&#9940;";
				$b="&#9620;";
				$c="&#9989;";

				$id_seq		= 5;
				$warranty 	= "*** TRADUCCIÓN AUTOMÁTICA, VERIFICAR CARACTERISTICAS ANTES DE COMPRAR***
				Este es un artículo importado, tiene una garantía de 30 días por defectos de fábrica, la misma no incluye cualquier daño ocasionado por la empresa transportadora. Mauxi eshop no se hace responsable de los costos de envío del producto en caso de aplicar la garantía";
				$shop_ml	= "shop_ml_mx";
				$name_shop 	= "&#x2133;auxi\n\n";

				$description .= "&#x2708; ESTE ARTICULO ES IMPORTADO DESDE USA";
				$description .= "\n";
				$description .= "\n";
				$description .= $c."  MÉTODOS DE ENVÍO\n";
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
				$description .= "&#9995;  RETRACTO  &#10071;&#10071;&#10071;\n";
				$description .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
				$description .= "\n";
				$description .= "* En caso de Retracto, es decir que ya no desee el producto que compro, puede realizar la devolución en un tiempo no mayor a 5 días después de entregado, Para esto el cliente deberá pagar el costo de retorno del producto a Estados Unidos, este varia según el peso y el tamaño del articulo comprado, el producto debe ser devuelto en el mismo estado en el que se entrego, caja y empaque original y sin ningún signo de uso, de no ser así NO SERA ACEPTADO EL RETRACTO.";
				$description .= "\n";
				$description .= "\n";
				$description .= $c."  GARANTÍA \n";
				$description .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
				$description .= "\n";
				$description .= "* Nuestros proveedores nos brindan 30 días de garantía directa, la cual extendemos y respetamos con nuestros clientes, esta garantía solamente cubre daños y defectos de fabrica, no aplica garantía para productos con defectos POR MALA MANIPULACION.";
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
				$description .= $c."  INFORMACIÓN IMPORTANTE\n";
				$description .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10102; Recuerda que antes de comprar, puedes hacer uso de la herramienta de preguntas, en donde un asesor te liberara todas tus dudas y así puedas estar totalmente confiado de lo que estas comprando.";
				$description .= "\n";
				$description .= "\n";
				$description .= "&#10103; Es importante recordar que al ser un producto importado y al ser distribuido a varios países en Latinoamérica, la DISPONIBILIDAD Y PRECIO del producto puede variar DIARIAMENTE, al igual que las tallas y colores están sujetas a la disponibilidad, en algunos casos puede que el valor del producto por cuestiones de tamaño, color o proveedor pueda cambiar ya que diariamente se actualizan de forma AUTOMÁTICA los precios con los de nuestros proveedores.";
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
				
				$valida_a='';
				$meli_item = $this->conn->prepare("SELECT aws_id from meli.items where mpid ='".$alias."' and shop_id='".$this->shop[0]['id']."';");
				$meli_item->execute();
				$meli_item = $meli_item->fetchAll();
				if(empty($meli_item)){

					$consulta_mpid = $this->consulta($alias);
					if($consulta_mpid == '0'){
						$valida_a =0;
						break;
					}elseif($consulta_mpid == '2'){
						 $valida_a =2;
						 break;
					}elseif($consulta_mpid == '1'){
						$insert_meli = $this->conn->prepare("INSERT into meli.items (mpid,aws_id,shop_id,bolborrado) values ('".$alias."',3074144,".$this->shop[0]['id'].",1);");
						$insert_meli->execute();
					}else{
						$aws_item = $this->conn->prepare("SELECT id from aws.items where sku = '".$consulta_mpid."';");
						$aws_item->execute();
						$aws_item = $aws_item->fetchAll();
						if(empty($aws_item)){
							$insert_aws = $this->conn->prepare("INSERT into aws.items (sku,bolborrado) values ('".$consulta_mpid."',16) RETURNING id;");
							$insert_aws->execute();
							$insert_aws = $insert_aws->fetchAll();
							
							include_once '/var/www/html/enkargo/process/aws_update_16.php';
							$insert_meli = $this->conn->prepare("INSERT into meli.items (mpid,aws_id,shop_id,bolborrado) values ('".$alias."',".$insert_aws[0]['id'].",".$this->shop[0]['id'].",16);");
							$insert_meli->execute();
						}else{
							$insert_meli = $this->conn->prepare("INSERT into meli.items (mpid,aws_id,shop_id,bolborrado) values ('".$alias."',".$aws_item[0]['id'].",".$this->shop[0]['id'].",16);");
							$insert_meli->execute();
						}
					}
				$meli_item = $this->conn->prepare("SELECT aws_id from meli.items where mpid = '".$alias."' and shop_id='".$this->shop[0]['id']."';");
				$meli_item->execute();
				$meli_item = $meli_item->fetchAll();
				}
				if ($index==null){

				}else{
					$this->conn->exec("UPDATE meli.items set bolborrado=".$index." WHERE mpid = '".$alias."' and shop_id='".$this->shop[0]['id']."';");
				}

				$sql ="SELECT a.id, a.sku, a.image_url,a.quantity, a.active, round(cast((a.package_height/0.393701) as numeric),2) as package_height, round(cast((a.item_width/0.393701) as numeric),2) as item_width, round(cast((a.package_length/0.393701) as numeric),2) as package_length, round(cast((a.item_height/0.393701) as numeric),2) as item_height, round(cast((a.item_length/0.393701) as numeric),2) as item_length, a.product_title_english, a.specification_english , a.brand, round(cast(a.package_weight/100 as numeric),2) as package_weight, a.sale_price, a.bolborrado as aws_bolborrado, a.brand, a.model, a.weight_unit,m.id as id_meli, m.mpid, m.bolborrado as meli_bolborrado, a.is_prime as prime, a.clothingsize, a.color, a.avaliable, a.title_spanish, a.specification_spanish,a.flag	from aws.items as a join meli.items as m on a.id=m.aws_id where m.shop_id =  '".$this->shop[0]['id']."' and a.id = '".$meli_item[0]['aws_id']."' and m.mpid = '".$alias."';";
				
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
				$meli_offset     = 0;
				$meli_limit      = $alias;
				$this->conn->exec("UPDATE meli.secuences SET offset_ ='".$offset."' WHERE id = '".$id_seq."';");
				
				$upd ="update meli.items set update_date = '".date('Y-m-d H:i:s')."' where shop_id =  '".$this->shop[0]['id']."' and mpid in (select mpid from meli.items where shop_id = '".$this->shop[0]['id']."' and bolborrado=".$this->group." order by update_date asc offset '0' limit '1000') returning  update_date";

				#$upd ="update meli.items set update_date = '".date('Y-m-d H:i:s')."' where shop_id =  '".$this->shop[0]['id']."' and mpid in (select mpid from meli.items where shop_id = '".$this->shop[0]['id']."' and bolborrado in (1,3) order by update_date asc offset '0' limit '".$meli_limit."') returning  update_date";

				$fec = $this->conn->prepare($upd);
				$fec->execute();
				$fec = $fec->fetchAll();

				$fecha_upd=$fec[0]['update_date'];

				$sql = "SELECT a.id, a.sku, a.image_url,a.quantity, a.active, round(cast((a.package_height/0.393701) as numeric),2) as package_height, round(cast((a.item_width/0.393701) as numeric),2) as item_width, round(cast((a.package_length/0.393701) as numeric),2) as package_length, round(cast((a.item_height/0.393701) as numeric),2) as item_height, round(cast((a.item_length/0.393701) as numeric),2) as item_length, a.product_title_english, a.specification_english , a.brand, round(cast(a.package_weight/100 as numeric),2) as package_weight, a.sale_price, a.bolborrado as aws_bolborrado, a.brand, a.model, a.weight_unit,m.id as id_meli, m.mpid, m.bolborrado as meli_bolborrado, a.is_prime as prime, a.clothingsize, a.color, a.avaliable ,a.title_spanish,a.specification_spanish,a.flag from aws.items as a join meli.items as m on a.id=m.aws_id where m.shop_id =  '".$this->shop[0]['id']."' and  m.update_date ='".$fecha_upd."';";

				$item = $this->conn->prepare($sql);
				$item->execute();
				$item = $item->fetchAll();
				break;	
		}

		#Getting Video id
		$video = $this->conn->prepare("select * from meli.video where shop_id = '".$this->shop[0]['id']."' and id = '".$video_shop."';");
		$video->execute();
		$video = $video->fetchAll();
		#
		#update array
		$update_array = array();
		unset($update_array);
		#$update_array['available_quantity'] = 8;

		#$update_array['shipping']        = array('mode' => 'me2', 'local_pick_up' => false, 'free_shipping' => true);
		
		#$reseteo_array['shipping']        = array('mode' => 'me1', 'local_pick_up' => false, 'free_shipping' => true);
		#$update_array['warranty'] = $warranty;

		#Manager Item
		if ($valida_a=='0'){
			return "error_c1";
		}elseif($valida_a=='2'){
			return "seller";
		}else{
			if(empty($item)){
				return "error_v";
			}else{
				foreach ($item as $items) {
					$id_meli			= $items['id_meli'];
					$mpid         		= $items['mpid'];
					$updated_items 		.= "'".$mpid."',";
					$item_description 	= "";
					$avaliable_d 	= trim($items['avaliable']);
					$images      	= explode("~^~", $items['image_url']);
					$img_cant    	= count($images);
					$item_img   	= array();
					$time 			= "";
					if (count($images) > 8) {
						$img_cant 	= 7;
					}
					array_push($item_img, array('source' => $images[0]));
					switch ($this->shop[0]['id']) {
						case '1':
						if($items['prime']==1 and $avaliable_d == 1){
							#array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/QB4-8.png'));
							array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/QB7-10.png'));
							$time =  'https://core.enkargo.com.co/img/QB7-10.png';
						}
						if($items['prime']==0 and $avaliable_d == 1){
							array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/QB7-10.png'));
							$time =  'https://core.enkargo.com.co/img/QB7-10.png';
						}
						if($avaliable_d == 2){
							array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/QB10-15.png'));
							$time =  'https://core.enkargo.com.co/img/QB10-15.png';
						}
						break;
						case '2':
						if($items['prime']==1 and $avaliable_d == 1){
							#array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/Mauxi4-8.png'));
							array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/Mauxi7-10.png'));
							$time =  'https://core.enkargo.com.co/img/Mauxi7-10.png';
						}
						if($items['prime']==0 and $avaliable_d == 1){
							array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/Mauxi7-10.png'));
							$time =  'https://core.enkargo.com.co/img/Mauxi7-10.png';
						}
						if($avaliable_d == 2){
							array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/Mauxi10-15.png'));
							$time = 'https://core.enkargo.com.co/img/Mauxi10-15.png';
						}
						break;
					}
					for ($j = 1; $j < $img_cant; $j++) {
						array_push($item_img, array('source' => $images[$j]));
					}
					
					$detail_items	="";
					$detail_items 	= $this->meli->show($mpid);

					
					if (isset($detail_items->error)) {
						#$this->conn->exec("DELETE from meli.items where id='".$id_meli."' and shop_id='".$this->shop[0]['id']."';");
						#break;
						return "error_c2";
					}elseif(isset($detail_items[0]->id)){

						$tag='';
						foreach ($detail_items[0]->tags as $tags) {
							$tag .= $tags.",";
						}
						$tag = substr($tag, 0, -1);
						
						#$this->conn->exec("UPDATE meli.items set title='".$detail_items[0]->title."',category_id='".$detail_items[0]->category_id."',price='".$detail_items[0]->price."',sold_quantity='".$detail_items[0]->sold_quantity."',permalink='".$detail_items[0]->permalink."', tags='".$tag."' where id='".$id_meli."';");

						#if ($detail_items[0]->shipping->mode=='not_specified'){

							$buying_mode = $this->meli->validateCategory_by_user($detail_items[0]->category_id);
							$valida=0;
							foreach ($buying_mode as $shipp) {
								if ($shipp->mode=='me2'){	$valida=1;	}
							}

							if ($valida==1){
								#$methot=array();
								$update_array['shipping'] = array('mode'    => 'me2', 'local_pick_up'    => false, 'free_shipping'    => true);
							}else{
								$costos=array();
								array_push($costos, array('description' => 'Pagar el Envío en mi Domicilio', 'cost' => 1 ));
								$update_array['shipping'] = array('mode'    => 'custom', 'local_pick_up'    => false, 'free_shipping'    => false , 'costs' => $costos);
							}
							
							if ($detail_items[0]->shipping->mode=='not_specified'){
								$costos=array();
								array_push($costos, array('description' => 'Pagar el Envío en mi Domicilio', 'cost' => 1 ));
								$update_array['shipping'] = array('mode'    => 'custom', 'local_pick_up'    => false, 'free_shipping'    => false , 'costs' => $costos);
							}else{
								unset($update_array['shipping']);
							}
						#}

						
						#$update_array['shipping'] = array('mode'    => 'me2', 'local_pick_up'    => false, 'free_shipping'    => true);

						$item_status = $detail_items[0]->status;
						switch ($items['meli_bolborrado']) {
							case 1:
								
								$this->meli->delete_item($item_status, $mpid, null);
								$this->conn->exec("UPDATE meli.items SET bolborrado = 3 WHERE id='".$id_meli."';");
								return "deleted";
								break;
							case 2:
								$prueba=array();
								$prueba['available_quantity'] 	= 0;
								$update = $this->meli->update($mpid, $prueba);
								#$this->meli->paused_item($item_status, $mpid, NULL);
								$this->conn->exec("UPDATE meli.items SET bolborrado = 16 WHERE id='".$id_meli."';");
								return "paused";
								break;
							case 3:
								
								$this->meli->delete_item($item_status, $mpid, "delete_item");
								break;
							case 4:
								
								$this->meli->delete_item($item_status, $mpid, null);
								$this->conn->exec("UPDATE meli.items SET bolborrado = 16 where id='".$id_meli."';");
								return "closed";
								break;
							case 9:
							
							break;
							default:
							switch ($items['aws_bolborrado']) {
								case '1':
								
								$this->meli->delete_item($item_status, $mpid, NULL);
								$this->conn->exec("UPDATE meli.items SET bolborrado = 3 where id='".$id_meli."';");	
								return "deleted";				
								break;
								default:
								#Check AWS price Avaliability
								if ($items['sale_price'] == 0) {
									
									$this->meli->paused_item($item_status, $mpid, NULL);
									return "paused";
								}
								#Check AWS Price Limit
								elseif ($items['sale_price'] > 2500) {
									
									$this->meli->delete_item($item_status, $mpid, NULL);
									$this->conn->exec("UPDATE meli.items SET bolborrado = 3, aws_id = ".$items['id']." WHERE id='".$id_meli."';");
									return "deleted";
								}
								elseif ($items['active'] == 0) {
									
									$this->meli->paused_item($item_status, $mpid, NULL);
									return "paused";
								}
								#Check AWS Weight
								elseif ($items['package_weight'] >= 110) {
									
									$this->meli->delete_item($item_status, $mpid, null);
									$this->conn->exec("UPDATE meli.items SET bolborrado = 3 WHERE id='".$id_meli."';");
									return "deleted";
								}
								#Check AWS Weight
								elseif ($items['package_weight'] == 0 && $items['sale_price'] >= 80) {
									
									$this->meli->delete_item($item_status, $mpid, null);
									$this->conn->exec("UPDATE meli.items SET bolborrado = 3 where id='".$id_meli."';");
									return "deleted";
								}
								#Check AWS Quantity
								elseif ($items['quantity'] < 1) {
									
									$this->meli->paused_item($item_status, $mpid, NULL);
									return "paused";
								}else{
								#Set MELI price
									$valor_prime="\n&#10024;&#8473;&#10024;\n";
									if($items['prime']==0){
										$valor_prime="";
									}
									$precio	=	$items['sale_price'];
									$precio_p =  $this->meli->liquidador_pro($precio, $items['package_weight'], $items['prime'],$this->shop[0]['id']);
									$update_array['price'] =  $precio_p;
									$value['price'] =  $precio_p;

								#Check MELI Status
									switch ($item_status) {
										case "closed":
										case "inactive":
											$update_array['status'] 			= "active";
											$update_array['listing_type_id']	= "gold_special";
											$update_array['quantity'] = 8;
											$update = $this->meli->relist($mpid, $update_array);
											if (isset($update->id)) {
												
												$this->conn->exec("UPDATE meli.meli set mpid='".$update->id."' where id='".$id_meli."';");
												return "relist";
											}else{
												if (in_array("deleted", $detail_items[0]->sub_status)) {
													
													$this->conn->exec("DELETE from meli.items where id='".$id_meli."' and shop_id='".$this->shop[0]['id']."';");
												} else {
													
													#$this->conn->exec("INSERT INTO log.meli (sku,response,executed_at,code_) values ('".$mpid."','NO relisted/Error','".date("Y-m-d H:i:s")."','".$update->message."');");
												}
												return "error_active";
											}
											unset($update_array['status']);
											unset($update_array['listing_type_id']);
											unset($update_array['quantity']);
											break;
										case 'paused':
											#$update_array['status'] = "active";
											$update_array['available_quantity'] = 8;
											if (isset($detail_items[0]->variations[0]->id)){
												#$update_array = array();
												unset($update_array['price']);
												unset($update_array['pictures']);
												unset($update_array['available_quantity']);
												$value['id'] 					= $detail_items[0]->variations[0]->id;
												$value['available_quantity'] 	= 8;
												$update_array['variations']		= array($value);
											}
											$update = $this->meli->update($mpid, $update_array);
											if (isset($update->id)) {
												
												return "update";
											} else {								
												#$this->conn->exec("INSERT INTO log.meli (sku,response,executed_at,code_) values ('".$mpid."','NO updated/paused','".date("Y-m-d H:i:s")."','".$update->message."');");
												return "error_active";
											}

											unset($update_array['available_quantity']);
											break;
										case 'active':
											if (isset($items['title_spanish'])){
												$item_title = $items['title_spanish'];
												if (strlen($item_title) >= 60) {
														$pos        = strpos($item_title, ' ', 40);
														$item_title = substr($item_title, 0, $pos);
												}
												$update_array['title'] = eliminar_simbolos($item_title);
											}else{
												$item_title = $items['product_title_english'];
												if (strlen($item_title) >= 60) {
														$pos        = strpos($item_title, ' ', 40);
														$item_title = substr($item_title, 0, $pos);
												}
												$update_array['title'] = eliminar_simbolos($item_title);
											}

											if (isset($items['specification_spanish'])){
												$especificacion = $items['specification_spanish'];
											} else {
												$especificacion = $items['specification_english'];
											}
											$time_send ="";
											if($avaliable_d ==1  && $items['prime'] == 1){
												$time_send .= $a1.$a1.$a1.$a1.$a1.$a1.$a1.$a1.$a1.$a1.$a1."\n";
												$time_send .= "&#9; TIEMPOS DE ENTREGA \n";
												$time_send .= "&#9; DE 7 A 10 DÍAS HÁBILES \n";
												$time_send .= $a2.$a2.$a2.$a2.$a2.$a2.$a2.$a2.$a2.$a2.$a2."\n";
											}
											if($avaliable_d ==1 && $items['prime'] == 0){
												$time_send .= $a1.$a1.$a1.$a1.$a1.$a1.$a1.$a1.$a1.$a1.$a1."\n";
												$time_send .= "&#9; TIEMPOS DE ENTREGA \n";
												$time_send .= "&#9; DE 7 A 10 DÍAS HÁBILES \n";
												$time_send .= $a2.$a2.$a2.$a2.$a2.$a2.$a2.$a2.$a2.$a2.$a2."\n";
											}
											if($avaliable_d == 2  ){
												$time_send .= $a1.$a1.$a1.$a1.$a1.$a1.$a1.$a1.$a1.$a1.$a1."\n";
												$time_send .= "&#9; TIEMPOS DE ENTREGA \n";
												$time_send .= "&#9; DE 10 A 15 DÍAS HÁBILES \n";
												$time_send .= $a2.$a2.$a2.$a2.$a2.$a2.$a2.$a2.$a2.$a2.$a2."\n";
											}
											$description_detail = "";
											$description_detail .= "\n\n";
											$description_detail .= $c."  Descripción del Producto\n";
											$description_detail .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
											$description_detail .= "\n";
											
											$description_detail .= str_replace(".-", "\n", substr($especificacion,0,4950));
											$description_detail .= "\n";
											$description_detail .= "\n";
											$description_detail .= $c."  Ficha Técnica\n";
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
											$description_detail .= "• Ancho de Paquete => ";
											$description_detail .= $items['item_width'];
											$description_detail .= "\n";
											$description_detail .= "• Alto de Paquete => ";
											$description_detail .= $items['package_height'];
											$description_detail .= "\n";
											$description_detail .= "• Largo de Paquete => ";
											$description_detail .= $items['package_length'];
											if($items['clothingsize'] != ""){
												$description_detail .= "\n";
												$description_detail .= "• Talla o Tamaño => ";
												$description_detail .= $items['clothingsize'];
											}
											if($items['color'] != ""){
												$description_detail .= "\n";
												$description_detail .= "• Color Primario => ";
												$description_detail .= $items['color'];
											}

											$description_detail .= "\n\n";
											$date_up  =	"\n\n";
											$date_up .= date("Y-m-d H:i:s")." -A";

											$item_description .= $name_shop.$time_send.$description_detail.$description.$time_send.$valor_prime.$date_up ;
											$array_description = array('plain_text'=>$item_description);
											$update_description = $this->meli->banner($mpid, $array_description);
											
											if (!empty($item_img)){
												$update_array['pictures'] = $item_img;
												$value['pictures'] = $item_img;
											}

											if (isset($detail_items[0]->variations[0]->id)){
												#$update_array = array();
												unset($update_array['price']);
												unset($update_array['pictures']);
												$value['id'] = $detail_items[0]->variations[0]->id;
												$update_array['variations']= array($value);
																				}

											$attributes= array();

				
											if (isset($items['model'])){
												array_push($attributes, array('id' => 'MODEL', 'value_name' => $items['model'] ));	
											}
											if (isset($items['brand'])){
												array_push($attributes, array('id' => 'BRAND', 'value_name' => $items['brand'] ));	
											}
											array_push($attributes, array('id' => 'ITEM_CONDITION', 'values_id'=> 2230284));
											
											if (!empty($attributes)){
													$update_array['attributes'] 	= $attributes;
												}

											#if($items['flag'] == 1){
											#}
											unset($update_array['title']);

											if (empty($items['image_url'])){
												$img_ex = array();
												unset($update_array['pictures']);

												$total_img = count($detail_items[0]->pictures);
												array_push($img_ex, array('id' => $detail_items[0]->pictures[0]->id));
												array_push($img_ex, array('source' => $time));
												for ( $r=2 ; $r<$total_img ; $r++ ) {
													array_push($img_ex, array('id' => $detail_items[0]->pictures[$r]->id));
												}
												$update_array['pictures'] = $img_ex;	
											}

											if ($detail_items[0]->shipping->mode=='me2'){
												unset($update_array['shipping']);
											}
																			#$update = $this->meli->update($mpid, $reseteo_array);
											$update = $this->meli->update($mpid, $update_array);

											if (isset($update->id)) {
												
												return "update";
													#$this->conn->exec("UPDATE meli.items set price=".$precio_p." where mpid='".$mpid."' and shop_id='".$this->shop[0]['id']."';");
											} else {
																					
												#$this->conn->exec("INSERT INTO log.meli (sku,response,executed_at,code_) values ('".$mpid."','NO updated/active','".date("Y-m-d H:i:s")."','".$update->message."');");
												return "error_active";
											}
											break;
										case "under_review":
										
										return "review";
										break;
									}
									unset($update_array);
									unset($value);
								}
								break;
							}
							break;
						}
						
					}else{
						
						return "deleted";
					}
				}
			}
		}
		#$updated_items = substr($updated_items, 0, (strlen($updated_items)-1));
		
		#pg_query("update meli.items set update_date = '".date('Y-m-d H:i:s')."' where mpid in (".$updated_items.");");
		#$this->conn_sql->close();
		$this->conn->close_con();
		
	}
}
#Test section
#$test = new MeliUpdate(2,'massive');
#$test->update(1000);