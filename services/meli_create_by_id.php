<?php
include '/var/www/html/enkargo/config/meli_items.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';

class meliGet {
	public $conn;
	
	public function __construct() {
		$this->conn      = new DataBase();
		
	}
	public function createItems($application,$id_sku) {

		try {
			
			$k                  = 0;
			$application_detail = $this->conn->prepare("SELECT * FROM meli.shop WHERE id ='".$application."'");
			$application_detail->execute();
			$application_detail = $application_detail->fetchAll();
			
			$items_manager      = new items($application_detail[0]['access_token'],$application_detail[0]['user_name']);

			if ($application == 1) {
				
				$a1="&#x23f0;";
				$a2="&#x23f0;";
				$b="&#9620;";
				$c="&#x23e9;";
				
				$sql="SELECT * from aws.items_valido_qb_view  where id=".$id_sku.";";
				$items           = $this->conn->prepare($sql); 
				#$items           = $this->conn->prepare("SELECT * from aws.items_valido_qb_view  where id in (3870107);");
				$video            = $this->conn->prepare("SELECT * FROM meli.video WHERE shop_id = '".$application_detail[0]['id']."' and id = 4;");
				$video->execute();
				$video = $video->fetchAll();
			}
			if ($application == 2) {
				
				$a1="&#9940;";
				$a2="&#9940;";
				$b="&#9620;";
				$c="&#9989;";
				$sql="SELECT * from aws.items_valido_mx_view  where id=".$id_sku.";";
				$items           = $this->conn->prepare($sql); 
				$video            = $this->conn->prepare("SELECT * FROM meli.video WHERE shop_id = '".$application_detail[0]['id']."' and id = 5;");
				$video->execute();
				$video = $video->fetchAll();
			}
			$items->execute();
			#$this->conn->beginTransaction();
			$items = $items->fetchAll();
			foreach ($items as $item) {
				$item = (object) $item;
				$attributes 		=array();
				$attributes_com		=array();
				$description ="";
				$item_description 	= "";
				$product="";
				$value="";
				$att=null;
				$avaliable_d 	= trim($item->avaliable);
				$time_send ="";
				if($avaliable_d ==1 && $item->is_prime == 1){
					$time_send .= $a1.$a1.$a1.$a1.$a1.$a1.$a1.$a1.$a1.$a1.$a1."\n";
					$time_send .= "&#9; TIEMPOS DE ENTREGA \n";
					$time_send .= "&#9; DE 7 A 10 DÍAS HÁBILES \n";
					$time_send .= $a2.$a2.$a2.$a2.$a2.$a2.$a2.$a2.$a2.$a2.$a2."\n";
				}
				if($avaliable_d ==1 && $item->is_prime == 0){
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

				$valor_prime="\n&#10024;&#8473;&#10024;\n";
				if($item->is_prime == 0){
					$valor_prime="";
				}			
				$title = eliminar_simbolos($item->product_title_english);
				if (strlen($title) >= 200) {
					$pos   = strpos($title, ' ', 150);
					$title = substr($title, 0, $pos);
				}
				$item_title = $title;

				$description_esp = eliminar_simbolos( $item->specification_english);
				if (strlen($description_esp) >= 4900) {
					$pos   = strpos($description_esp, ' ', 4900);
					$description_esp = substr($description_esp, 0, $pos);
				}
				$item_description_p = $description_esp;

				if ($application_detail[0]['id'] == 1) {
					$name_shop 	= "&#8474;UEEN BEE\n\n";
					$description = "";
					$description .= "\n\n";
					$description .= $c."  Descripción del Producto\n";
					$description .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
					$description .= "\n";
					
					$description .= str_replace(".-", "\n", $item_description_p);
					$description .= "\n";
					$description .= "\n";
					$description .= $c."  Ficha Técnica\n";
					$description .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
					$description .= "\n\n";
					$description .= "• Titulo Original =>";
					$description .= eliminar_simbolos($items_manager->replace_amazon($item->product_title_english));
					$description .= "\n";
					$description .= "• Marca => ";
					$description .= $item->brand;
					$description .= "\n";
					$description .= "• Modelo => ";
					$description .= $item->model;
					$description .= "\n";
					$description .= "• Unidad de peso => ";
					$description .= $item->weight_unit;
					$description .= "\n";
					$description .= "• Peso de Paquete => ";
					$description .= $item->package_weight;
					$description .= "\n";
					$description .= "• Ancho => ";
					$description .= $item->item_width;
					$description .= "\n";
					$description .= "• Alto => ";
					$description .= $item->package_height;
					$description .= "\n";
					$description .= "• Largo => ";
					$description .= $item->package_length;
					if($item->clothingsize != ""){
						$description .= "\n";
						$description .= "• Talla => ";
						$description .= $item->clothingsize;
					}
					if($item->color != ""){
						$description .= "\n";
						$description .= "• Color => ";
						$description .= $item->color;
					}
					$description .= "\n\n";
					$description .= "&#11088; ESTE ARTICULO ES IMPORTADO DESDE USA";
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
					$description .= $c."  INFORMACIÓN IMPORTANTE\n";
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
					$date_up  =	"\n\n";
					$date_up .= date("Y-m-d H:i:s")." -C";
				}
				if ($application_detail[0]['id'] == 2) {
					$name_shop 	= "&#x2133;auxi\n\n";
					$description = "";
					$description .= "\n\n";
					$description .= $c."  Descripción del Producto\n";
					$description .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
					$description .= "\n";
					
					$description .= str_replace(".-", "\n", $item_description_p);
					$description .= "\n";
					$description .= "\n";
					$description .= $c."  Ficha Técnica\n";
					$description .= "&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;&#9620;";
					$description .= "\n\n";
					$description .= "• Titulo Original =>";
					$description .= eliminar_simbolos($items_manager->replace_amazon($item->product_title_english));
					$description .= "\n";
					$description .= "• Marca => ";
					$description .= $item->brand;
					$description .= "\n";
					$description .= "• Modelo => ";
					$description .= $item->model;
					$description .= "\n";
					$description .= "• Unidad de peso => ";
					$description .= $item->weight_unit;
					$description .= "\n";
					$description .= "• Peso de Paquete => ";
					$description .= $item->package_weight;
					$description .= "\n";
					$description .= "• Ancho => ";
					$description .= $item->item_width;
					$description .= "\n";
					$description .= "• Alto => ";
					$description .= $item->package_height;
					$description .= "\n";
					$description .= "• Largo => ";
					$description .= $item->package_length;
					if($item->clothingsize != ""){
						$description .= "\n";
						$description .= "• Talla => ";
						$description .= $item->clothingsize;
					}
					if($item->color != ""){
						$description .= "\n";
						$description .= "• Color => ";
						$description .= $item->color;
					}

					$description .= "\n\n";
					$description .= "&#11088; ESTE ARTICULO ES IMPORTADO DESDE USA";
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
					$description .= $c."  INFORMACIÓN IMPORTANTE\n";
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
					$date_up  =	"\n\n";
					$date_up .= date("Y-m-d H:i:s")." -C";
				}

				$weight_pack = $item->package_weight;
				$images      = explode("~^~", $item->image_url);
				$img_cant    = count($images);
				$item_img    = array();
				$item_img_mini=array();
				if (count($images) > 8) {
					$img_cant 	= 7;
				}
				array_push($item_img, array('source' => $images[0]));
				array_push($item_img_mini, $images[0]);
				switch ($application_detail[0]['id']) {
					case '1':
					if($item->is_prime==1 and $avaliable_d == 1){
						#array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/QB4-8.png'));
						#array_push($item_img_mini, 'https://core.enkargo.com.co/img/QB4-8.png');
						array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/QB7-10.png'));
						array_push($item_img_mini, 'https://core.enkargo.com.co/img/QB7-10.png');
					}
					if($item->is_prime==0 and $avaliable_d == 1){
						array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/QB7-10.png'));
						array_push($item_img_mini, 'https://core.enkargo.com.co/img/QB7-10.png');
					}
					if($avaliable_d == 2){
						array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/QB10-15.png'));
						array_push($item_img_mini, 'https://core.enkargo.com.co/img/QB10-15.png');
					}
					break;
					case '2':
					if($item->is_prime==1 and $avaliable_d == 1){
						#array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/Mauxi4-8.png'));
						#array_push($item_img_mini, 'https://core.enkargo.com.co/img/Mauxi4-8.png');
						array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/Mauxi7-10.png'));
						array_push($item_img_mini, 'https://core.enkargo.com.co/img/Mauxi7-10.png');
					}
					if($item->is_prime==0 and $avaliable_d == 1){
						array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/Mauxi7-10.png'));
						array_push($item_img_mini, 'https://core.enkargo.com.co/img/Mauxi7-10.png');
					}
					if($avaliable_d == 2){
						array_push($item_img, array('source' => 'https://core.enkargo.com.co/img/Mauxi10-15.png'));
						array_push($item_img_mini, 'https://core.enkargo.com.co/img/Mauxi10-15.png');
					}
					break;
				}
				for ($j = 1; $j < $img_cant; $j++) {
					array_push($item_img, array('source' => $images[$j]));
					array_push($item_img_mini, $images[$j]);
				}	


				$date_update        = date('Y-m-d H:i:s')." - NEW";
				$application_detail = $this->conn->prepare("SELECT * FROM meli.shop WHERE id ='".$application."'");
				$application_detail->execute();
				$application_detail = $application_detail->fetchAll();
				#$items_manager      = new items($application_detail[0]['access_token'],$application_detail[0]['user_name']);

				$category_id        = $items_manager->category_match_aws($item->product_category,$item->category_meli);
				#$category_id        = $item->category_meli;
				#$items_manager->getCategoriesPredictor($item_title);
				if ($category_id == null) {
					return "category_null";
					$k++;
				} else {
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
					$precio	=	$item->sale_price;
					$precio_p 				= $items_manager->liquidador_pro($precio, $weight_pack,  $item->is_prime,$application_detail[0]['id']);
					$product['price'] =  $precio_p;
					$product['currency_id']         = "COP";
					$buying_mode = $items_manager->validateCategory($category_id);
					

					if($buying_mode->attribute_types == "variations"){
						$att=" A";
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

						}
						if (!empty($attributes_com)){
							
							$value['attribute_combinations'] 	= $attributes_com;
							$value['price'] 					= $product['price'];
							$value['available_quantity'] 		= 10;
							$value['picture_ids']            	= $item_img_mini;
							$value['seller_custom_field']		= $item->sku;

						}
						#	$product['variations']  = $value;
							
						#print_r($product);

					}
					
					
					if (isset($item->model) || $item->model <> null || $item->model <> '' || $item->model <> ' ' ){
						array_push($attributes, array('id' => 'MODEL', 'value_name' => $item->model ));	
					}
					if (isset($item->brand) || $item->brand <> null  || $item->brand <> ''  || $item->brand <> ' ' ){
						array_push($attributes, array('id' => 'BRAND', 'value_name' => $item->brand ));	
					}
					array_push($attributes, array('id' => 'ITEM_CONDITION', 'values_id'=> 2230284));
					
					if (!empty($attributes)){
							$product['attributes'] 	= $attributes;
						}


					$to_shipp = $items_manager->validateCategory_by_user($category_id);
					$valida=0;

					foreach ($to_shipp as $shipp) {
						if ($shipp->mode=='me2'){	$valida=1;	}
					}

					$shipping_mode = $buying_mode->settings->shipping_modes;

					$buying_mode = $buying_mode->settings->buying_modes;
					if (in_array("buy_it_now", $buying_mode)) {
						$buying_mode  = "buy_it_now";
					}else{
						if (in_array("classified", $buying_mode)) {
							$buying_mode  = "classified";
						}else{
							return "category_null";
							break;
						}
					}
					$product['buying_mode']         = $buying_mode;
					$product['available_quantity']  = 10;
					$product['condition']           = "new";
					$product['listing_type_id']     = "gold_special";
					$item_description 				.= $name_shop.$time_send.$description.$time_send.$valor_prime.$date_up ;
					$product['description']         = array('plain_text' => $item_description);
					$product['video_id']            = $video[0]['url'];
					$product['warranty']            = $warranty;
					$product['pictures']            = $item_img;
					$product['seller_custom_field'] = $item->sku;
					

					if ($valida==1){
						$metod = array();
						$mandatoy_free = array('mandatory_free_shipping');
						$product['shipping']            = array('mode'    => 'me2', 'local_pick_up'    => false, 'free_shipping'    => true ,'free_methods' => $metod,'tags' => $mandatoy_free);
					}else{
						$costos=array();
						array_push($costos, array('description' => 'Pagar el Envío en mi Domicilio', 'cost' => 1));
						$product['shipping']            = array('mode'    => 'custom', 'local_pick_up'    => false, 'free_shipping'    => false , 'costs' => $costos);
					}

					if (!empty($value)){
						$product['variations']  = array($value);
					}


					#print_r($product);die();

					$show = $items_manager->validate($product);
					if ($show) {
						if($show->message == "invalid_token"){
							return "no_token";
							die();
						}else{
							unset($product);
							unset($value);
							return "error";
						}
						#print_r($show);#die();
					}else{
						$show = $items_manager->create($product);
						if (!isset($show->error) and isset($show)) {
							$this->conn->exec("INSERT INTO meli.items(mpid, title, seller_id, category_id, price, base_price, sold_quantity,start_time, stop_time, end_time, permalink, status, aws_id, automatic_relist, date_created, last_updated, shop_id, create_date, update_date,video, is_static,bolborrado) VALUES ('".$show->id."', '".htmlspecialchars($show->title, ENT_QUOTES)."', '".$show->seller_id."', '".$show->category_id."', '".$show->price."', '".$show->base_price."', '".$show->sold_quantity."','".$show->start_time."', '".$show->stop_time."', '".$show->end_time."', '".$show->permalink."', '".$show->status."','".$item->id."', '".$show->automatic_relist."', '".$show->date_created."', '".$show->last_updated."', '".$application_detail[0]['id']."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."','".$video[0]['id']."','0',16);");
							unset($product);
							unset($value);
							#if ($application_detail[0]['id'] == 1) {
							$this->conn->exec("UPDATE aws.items SET shop_ml_qb = 1 ,  shop_ml_mx = 1 WHERE sku = '".$item->sku."';");
							
							return array( 'id' => $show->id,'url'=> $show->permalink);
						} else {
							$message = isset($show->message)?htmlspecialchars($show->message, ENT_QUOTES):NULL;
							$code    = isset($show->error)?htmlspecialchars($show->error, ENT_QUOTES):NULL;
							
								#print_r($show);
							$show = $items_manager->validate($product);
							if(	$code == "Daily quota reached per day. (10000)"){
								return "daily_quota";
								
							}else{
								return "not_create";
							}
							unset($product);
							unset($value);
						}
					}
					unset($product);
					unset($value);
				}
				$k++;
			}
		} catch (Exception $e) {
			http_response_code(404);
			
		}
		#$this->conn->commit();
		$this->conn->close_con();
	}
}
/*
$test = new meliGet();
$test->createItems(2);
*/