<?php
include '/var/www/html/enkargo/config/googleTranslate.php';
include "/var/www/html/enkargo/config/aws_item.php";
include "/var/www/html/enkargo/config/conex_manager.php";

/**
 * Extraido de http://ecapy.com/reemplazar-la-n-acentos-espacios-y-caracteres-especiales-con-php-actualizada/
 * Reemplaza todos los acentos por sus equivalentes sin ellos
 *
 * @param $string
 *  string la cadena a sanear
 *
 * @return $string
 *  string saneada
 */

#Creando clase de gestión de items
class items {
	public $conn;
	public $token;
	public $connector;

	public function __construct($access_token) {;
		$this->token     = $access_token;
		$this->connector = new Connect();
	}

	public function get_local_item($amount, $category) {
		$data = array('n' => $amount, 'category' => $category);
		$url  = "http://181.58.30.89:8080/CBT_core/services/productsGet.php";
		#Accediendo a la base de datos de items locales
		$items_bd_result = $this->conn->execute__($url, $data);
		return json_decode($items_bd_result);
	}

	public function leaf_category($category_id, $total_aws_category, $category_name) {
		$percent        = 0;
		$match_category = 0;
		$query_category = pg_query("select * from cbt.category_master where padre ='".$category_id."'");
		while ($category = pg_fetch_object($query_category)) {
			for ($i = 0; $i < $total_aws_category; $i++) {
				similar_text($category_name[$i], htmlspecialchars_decode($category->definition), $percent);
				if ($percent > $match_category) {
					$match_category = $percent;
					$category_id    = $category->id;
				}
			}
		}
		$count_category = pg_fetch_object(pg_query("select count(*) from cbt.category_master where padre = '".$category_id."'"));
		if ($count_category->count > 1) {
			return $this->leaf_category($category_id, $total_aws_category, $category_name);

		} else {
			return $category_id;
		}
	}
	public function category_match($product_type, $category_name) {
		$query_meta_category = pg_query("select * from cbt.category_master where padre =0 order by definition asc;");
		$match_meta_category = 0;
		$match_sub_category  = 0;
		$match_category      = 0;
		$percent             = 0;
		if ($product_type == null) {
			if (isset($category_name[0])) {
				$product_type = $category_name[0];
			} else {
				return null;
				exit;
			}
		}
		$category_name      = explode(",", $category_name);
		$total_aws_category = count($category_name);
		while ($meta_category = pg_fetch_object($query_meta_category)) {
			similar_text($product_type, $meta_category->definition, $percent);
			if ($percent > $match_meta_category) {
				$match_meta_category = $percent;
				$meta_category_id    = $meta_category->id;

			}
		};
		if (!isset($meta_category_id)) {
			return null;
			exit;
		}
		$query_sub_category = pg_query("select * from cbt.category_master where padre ='".$meta_category_id."' order by definition asc;");
		$sub_category_id    = 0;
		$percent            = 0;
		while ($sub_category = pg_fetch_object($query_sub_category)) {
			similar_text($product_type, $sub_category->definition, $percent);
			if ($percent > $match_sub_category) {
				$match_sub_category = $percent;
				$sub_category_id    = $sub_category->id;
			}
		}
		$count_category = pg_fetch_object(pg_query("select count(*) from cbt.category_master where padre = '".$sub_category_id."';"));
		if ($count_category->count > 1) {
			return $this->leaf_category($sub_category_id, $total_aws_category, $category_name);

		} else {
			return $sub_category_id;
		}
	}

	public function dimension_converter($dimension, $unit) {
		$converted_dimension = 0;
		if ($unit == 'in') {
			$converted_dimension = ($dimension)*2.54;
		}
		if ($unit == 'cm') {
			$converted_dimension = ($dimension)/2.54;
		}
		return $converted_dimension;

	}

	public function weight_converter($weight, $unit) {
		$converted_weight = 0;
		if ($unit == 'lb') {
			$converted_weight = ($weight/100)*0.4535;
		}
		if ($unit == 'kg') {
			$converted_weight = ($weight/100)/2;
		}
		return $converted_weight;
	}
	public function replace_amazon($string) {
		$to_replace = array("Amazon", "amazon", "Prime", "prime","LIFETIME WARRANTY","100% SATISFACTION GUARANTEED","your money back.", "LIFETIME WARRANTY - 100% SATISFACTION GUARANTEED or your money back.");
		$string     = str_replace($to_replace, ' ', $string);
		return $string;
	} 
	public function price_converter($base_price, $shop, $bol) {
		/*Funcion para el cálculo automático del precio final del producto a publicar*/
		$price_manager = pg_fetch_object(pg_query("SELECT * FROM cbt.price_manager WHERE shop_id = '".$shop."' and active = 't';"));
		$rank1	=	$price_manager->range_1;
		$rank2	=	$price_manager->range_2;
		$rank3	=	$price_manager->range_3;
		$rank4	=	$price_manager->range_4;

		if ($bol==10 && $shop==3){
			$rank1=0.1;
			$rank2=0.1;
			$rank3=0.1;
			$rank4=0.1;
		}
		
		if ($base_price > 0 && $base_price <= 50) {
			$precio = (float) $base_price+5;
			$sub_total  = $precio+($precio*$rank1);
		}
		if ($base_price > 50 && $base_price <= 100) {
			$precio = (float) $base_price+4;
			$sub_total  = $precio+($precio*$rank2);
		}
		if ($base_price > 100 && $base_price <= 150) {
			$precio = (float) $base_price+4;
			$sub_total  = $precio+($precio*$rank3);
		}
		if ($base_price > 150) {
			$precio = (float) $base_price+4;
			$sub_total  = $precio+($precio*$rank4);
		}
		if (isset($sub_total)) {
			$total = $sub_total+($sub_total*0.16);
			return $total;
		} else {
			return null;
		}
	}


	public function price_shipping($base_price, $weight,$shop) {
		/*Funcion para el cálculo automático del precio final del producto a publicar*/
		#echo " peso: ".$weight." - ";	
		if ($shop==3){
			if ($base_price >= 0 ){#&& $base_price <= 20) {
			#echo " - de 0 a 20 - ";
				
				if ($weight > 0 && $weight <= 0.5){
					$shipping = 13.16;
				}
				if ($weight > 0.5 && $weight <= 1){
					$shipping = 14.77;
				}
				if ($weight > 1 && $weight <= 1.5){
					$shipping = 16.62;
				}
				if ($weight > 1.5 && $weight <= 2){
					$shipping = 18.46;
				}
				if ($weight > 2 && $weight <= 2.5){
					$shipping = 19.61;
				}
				if ($weight > 2.5 && $weight <= 3){
					$shipping = 21.20;
				}
				if ($weight > 3 && $weight <= 3.5){
					$shipping = 22.78;
				}
				if ($weight > 3.5 && $weight <= 4){
					$shipping = 24.36;
				}
				if ($weight > 4 && $weight <= 4.5){
					$shipping = 25.95;
				}
				if ($weight > 4.5 && $weight <= 5){
					$shipping = 27.53;
				}
				if ($weight > 5 && $weight <= 5.5){
					$shipping = 28.91;
				}
				if ($weight > 5.5 && $weight <= 6){
					$shipping = 30.30;
				}
				if ($weight > 6 && $weight <= 6.5){
					$shipping = 31.68;
				}
				if ($weight > 6.5 && $weight <= 7){
					$shipping = 33.06;
				}
				if ($weight > 7 && $weight <= 7.5){
					$shipping = 34.45;
				}
				if ($weight > 7.5 ){#&& $weight <= 8){
					$shipping = 35.82;
				}	
			}

		}
		if ($shop==4){
			if ($base_price >= 0 && $base_price <= 20) {
				#echo " - de 0 a 20 - ";
				
				if ($weight > 0 && $weight <= 0.5){
					$shipping = 13.16;
				}
				if ($weight > 0.5 && $weight <= 1){
					$shipping = 14.77;
				}
				if ($weight > 1 && $weight <= 1.5){
					$shipping = 16.62;
				}
				if ($weight > 1.5 && $weight <= 2){
					$shipping = 18.46;
				}
				if ($weight > 2 && $weight <= 2.5){
					$shipping = 19.61;
				}
				if ($weight > 2.5 && $weight <= 3){
					$shipping = 21.20;
				}
				if ($weight > 3 && $weight <= 3.5){
					$shipping = 22.78;
				}
				if ($weight > 3.5 && $weight <= 4){
					$shipping = 24.36;
				}
				if ($weight > 4 && $weight <= 4.5){
					$shipping = 25.95;
				}
				if ($weight > 4.5 && $weight <= 5){
					$shipping = 27.53;
				}
				if ($weight > 5 && $weight <= 5.5){
					$shipping = 28.91;
				}
				if ($weight > 5.5 && $weight <= 6){
					$shipping = 30.30;
				}
				if ($weight > 6 && $weight <= 6.5){
					$shipping = 31.68;
				}
				if ($weight > 6.5 && $weight <= 7){
					$shipping = 33.06;
				}
				if ($weight > 7 && $weight <= 7.5){
					$shipping = 34.45;
				}
				if ($weight > 7.5 ){#&& $weight <= 8){
					$shipping = 35.82;
				}	
			}
			if ($base_price >20 && $base_price <= 50) {

				#echo " - de 20 a 50 - ";
				if ($weight > 0 && $weight <= 0.5){
					$shipping = 9.21;
				}
				if ($weight > 0.5 && $weight <= 1){
					$shipping = 10.34;
				}
				if ($weight > 1 && $weight <= 1.5){
					$shipping = 11.63;
				}
				if ($weight > 1.5 && $weight <= 2){
					$shipping = 12.92;
				}
				if ($weight > 2 && $weight <= 2.5){
					$shipping = 13.73;
				}
				if ($weight > 2.5 && $weight <= 3){
					$shipping = 14.84;
				}
				if ($weight > 3 && $weight <= 3.5){
					$shipping = 15.95;
				}
				if ($weight > 3.5 && $weight <= 4){
					$shipping = 17.05;
				}
				if ($weight > 4 && $weight <= 4.5){
					$shipping = 18.17;
				}
				if ($weight > 4.5 && $weight <= 5){
					$shipping = 19.27;
				}
				if ($weight > 5 && $weight <= 5.5){
					$shipping = 20.24;
				}
				if ($weight > 5.5 && $weight <= 6){
					$shipping = 21.21;
				}
				if ($weight > 6 && $weight <= 6.5){
					$shipping = 22.18;
				}
				if ($weight > 6.5 && $weight <= 7){
					$shipping = 23.14;
				}
				if ($weight > 7 && $weight <= 7.5){
					$shipping = 24.12;
				}
				if ($weight > 7.5 ){#&& $weight <= 8){
					$shipping = 25.07;
				}	
			}
			if ($base_price > 50 ) {

				#echo " - mas de 50 - ";
				if ($weight > 0 && $weight <= 0.5){
					$shipping = 7.90;
				}
				if ($weight > 0.5 && $weight <= 1){
					$shipping = 8.86;
				}
				if ($weight > 1 && $weight <= 1.5){
					$shipping = 9.97;
				}
				if ($weight > 1.5 && $weight <= 2){
					$shipping = 11.08;
				}
				if ($weight > 2 && $weight <= 2.5){
					$shipping = 11.77;
				}
				if ($weight > 2.5 && $weight <= 3){
					$shipping = 12.72;
				}
				if ($weight > 3 && $weight <= 3.5){
					$shipping = 13.67;
				}
				if ($weight > 3.5 && $weight <= 4){
					$shipping = 14.62;
				}
				if ($weight > 4 && $weight <= 4.5){
					$shipping = 15.57;
				}
				if ($weight > 4.5 && $weight <= 5){
					$shipping = 16.52;
				}
				if ($weight > 5 && $weight <= 5.5){
					$shipping = 17.35;
				}
				if ($weight > 5.5 && $weight <= 6){
					$shipping = 18.18;
				}
				if ($weight > 6 && $weight <= 6.5){
					$shipping = 19.01;
				}
				if ($weight > 6.5 && $weight <= 7){
					$shipping = 19.84;
				}
				if ($weight > 7 && $weight <= 7.5){
					$shipping = 20.67;
				}
				if ($weight > 7.5 ){#&& $weight <= 8){
					$shipping = 21.49;
				}	
			}
		}
		#echo " ship-- ".$shipping." - ";
		return $shipping;

	}



	public function prepare_item_amazon($shop, $method, $local_item, $item_category, $video, $quantity, $condition, $array, $origin, $price) {
		if ($array == null) {
			$test       = new amazonManager();
			$translate  = new GoogleTranslate();
			$amz_detail = $test->search_item($local_item);


		} else {
			$amz_detail = $array;
			$local_item = $amz_detail['sku'];
		}
		#if ($amz_detail['is_prime'] == 1) {

		if ($method == 'post') {
			if ($item_category == null) {
				if($amz_detail['color_p_meli']=='Nepes'){
					$amz_detail['product_type']='More Categories';
					$amz_detail['product_category']='For Adults';
				}
				/*if($amz_detail['product_type']=='Apparel'){
					$amz_detail['product_type']='Clothes, Shoes and Bags';
				}*/
				$item_category = $this->category_match($amz_detail['product_type'], $amz_detail['product_category']);
			}
			#echo $item_category;
		}
		if ($video == null) {
			$video = "";
		}
		if ($quantity == null) {
			$quantity = $amz_detail['quantity'];
		}
		if ($condition == null) {
			$condition = $amz_detail['condition'];
		}
		$package_weight     = ($amz_detail['package_weight'] != 0)?$this->weight_converter($amz_detail['package_weight'], 'kg'):1;
		$package_width      = ($amz_detail['item_width'] != 0)?$this->dimension_converter($amz_detail['item_width'], 'in'):1;
		$package_height     = ($amz_detail['package_height'] != 0)?$this->dimension_converter($amz_detail['package_height'], 'in'):1;
		$package_length     = ($amz_detail['package_length'] != 0)?$this->dimension_converter($amz_detail['package_length'], 'in'):1;


		if ($price == null) {
			if (isset($amz_detail['bol'])){
				$bol=$amz_detail['bol'];
			}else{
				$bol=0;
			}


			$sale_price = $this->price_converter($amz_detail['sale_price'], $shop, $bol);
			#echo "precio original: ".$sale_price;
			$shipping   = (float) $this->price_shipping($sale_price,$package_weight,$shop);
			#echo " shipping: ".$shipping." - ";
			$sale_price = $sale_price+$shipping;

		} else {
			$sale_price = (float) $price;
			$shipping   = (float) $this->price_shipping($sale_price,$package_weight,$shop);
			$sale_price = $sale_price+$shipping;
		}
		
		#$custom_description = "Consider the following information.~1. This product is imported, therefore, it is subject to customs inspection of the country of destination.~2. Our estimated delivery time is 6 maximum 15 business days for MEXICO and for BRAZIL it is 8 maximum 20 days.~3. Depending on the country - I have sent and the customs taxes are completely free.~4. Due to company policies and the safety of our customers, we can NOT make changes to purchase invoices or shipping address; If the information registered in your MercadoLibre account is incorrect, be sure to make the change. before making the purchase~5. If you want to check a color or size, ask a question at the bottom and confirm availability; In case there is an error in the publication or we do not have availability, we will make the refund of money.~6. Once the purchase is made, send us a message with the desired specifications (size, color, etc.) - You must be sure of the size you are going to order (no changes are made)~7. You must make sure you want this product before buying it; Once we deliver it to the carrier, it will not be possible to cancel your order.~8. The warranty only covers manufacturing defects, if your product has defects, you must be informed within 5 days after receipt of your product (if you do not report within this time, the warranty will not be effective).~9. We do not assume costs and / or additional expenses for returns.~100% GUARANTEED DELIVERY.";

		$text_extra="\n\n* Remember to read our specifications; Thank you very much for the interest in our product, We hope you have an excellent day - Imppera. \n";

		$custom_description_en = "1. DELIVERY TIME: The delivery date reported by the platform may vary; Being an international supplier, your shipment would be subject to a customs inspection in the country of destination, which could cause your product does not arrive on the date given by the platform.\n~

2. COST SENT & CUSTOMS TAXES - Rates vary according to the country of destination: (For Mexico) Our company will be responsible for shipping costs if your product does not weigh more than 8kg, In case your product exceeds this figure (8kg) The carrier company where the shipment will be made in this case DHL will charge you for value, for the additional weight that your product has; The nationalization costs I have customs taxes; They are fully assumed by our company. (For Brazil) These values ​​will be calculated by Mercado Libre at the time you decide to make the purchase; the costs vary according to the destination city.\n~

3. REGISTERED DATA, SHIPPING ADDRESS AND PURCHASE INVOICE: (If the information in your Free Market profile is not correct, verify this information before making the purchase; According to the Free Market policies, we can not make changes in the information that is registered at the time you made the purchase (Please note that the data that was recorded, are those that will be reflected in the invoice).\n~

4. WARRANTY: The warranty only covers manufacturing defects; If your item has a defect, it must be informed within 5 days after receipt of your product (If you do not report within this time, the warranty will not be effective).\n~

5. NO CHANGES: If you want to buy a specific size or color different from the one you see published, check by means of the questions if we have availability; Once the purchase is made, send us a confirmation message; informing us the desired specifications; in case the size you ordered does not fit properly; Please note that, as an international seller, we can not make changes.\n~";


		$custom_description_es = "1. TIEMPO DE ENTREGA: La fecha de entrega informada por la plataforma puede variar; Al ser un proveedor internacional, su envío estaría sujeto a una inspección de aduana en el país de destino, la cual podría hacer que su producto no le llegue en la fecha dada por la plataforma.\n~

2. COSTO ENVIÓ & IMPUESTOS DE ADUANA - Las tarifas varían de acuerdo al país de destino: (Para México) Nuestra empresa se hará cargo de los costos de envió si tu producto no pesa más de 8kg, En dado caso que tu producto supere esta cifra (8kg) La empresa trasportadora por donde  se hará el envío en este caso DHL te hará el cobro de valor, por el peso adicional que tenga tu producto; Los costos de nacionalización he impuestos en aduana; son asumidos completamente por nuestra empresa. (Para Brasil) Estos valores los calculara Mercado Libre en el momento que decida realizar la compra; los costos varían de acuerdo a la ciudad de destino.\n~

3. DATOS REGISTRADOS, DIRECCIÓN DE ENVÍO Y FACTURA DE COMPRA: (Si la información en su perfil de Mercado Libre no es correcta, verifique estos datos antes de realizar la compra; Según las políticas de Mercado Libre; No podemos realizar cambios en la información que queda registrada en el momento que hizo la compra. (Tenga en cuenta que los datos que quedaron registrados, son los que vera reflejados en la factura).\n~

4. GARANTÍA: La garantía solo cubre los defectos de fabricación; si su artículo tiene un defecto, debe ser informado dentro de los 5 días posteriores a la recepción de su producto (Si no informa dentro de este tiempo, la garantía no será efectiva).\n~

5. NO CAMBIOS: Si desea comprar un tamaño en específico o color diferente al que ve publicado, verifique por medio de las preguntas si tenemos disponibilidad; una vez hecha la compra, envíenos un mensaje de confirmación; informándonos las especificaciones deseadas; en caso de que el tamaño que ordenó no le quede adecuadamente; tenga en cuenta que, como vendedor internacional, no podemos realizar cambios.\n~

6. DEVOLUCIONES DE DINERO: bebe asegurarse de que desea este producto antes de comprarlo; una vez que sea entregado a DHL, no será posible cancelar su pedido. (TENGA EN CUENTA QUE NO ASUMIMOS COSTOS Y/O GASTOS ADICIONALES POR DEVOLUCIONES).";

		$custom_description_pg = "1. PRAZO DE ENTREGA: A data de entrega informada pela plataforma pode variar; Sendo um fornecedor internacional, sua remessa estará sujeita a uma inspeção alfandegária no país de destino, o que poderá fazer com que seu produto não chegue na data indicada pela plataforma.\n~

2. CUSTOS ENVIADOS E IMPOSTOS ADUANEIROS - As tarifas variam de acordo com o país de destino: (Para o México) Nossa empresa será responsável pelos custos de envio se o seu produto não pesa mais de 8kg, caso seu produto exceda este valor (8kg) transportadora onde a remessa será feita neste caso A DHL cobrará valor pelo peso adicional que o produto possui; Os custos de nacionalização tenho impostos alfandegários; Eles são totalmente assumidos pela nossa empresa. (Para o Brasil) Estes valores serão calculados pelo Mercado Libre no momento em que você decide fazer a compra; os custos variam de acordo com a cidade de destino.\n~

3. DADOS REGISTRADOS, ENDEREÇO ​​DE ENVIO E FATURA DE COMPRA: (Se as informações em seu perfil de Mercado Livre não estiverem corretas, verifique estas informações antes de fazer a compra; De acordo com as políticas do Mercado Livre, não podemos fazer alterações nas informações registradas no momento em que você fez a compra (Observe que os dados que foram gravados são aqueles que serão refletidos na fatura).\n~

4. GARANTIA: A garantia cobre apenas defeitos de fabricação; Se o item tiver um defeito, ele deverá ser informado no prazo de 5 dias após o recebimento do produto (se você não informar dentro desse prazo, a garantia não será efetiva).\n~

5. SEM ALTERAÇÕES: Se você deseja comprar um tamanho específico ou cor diferente daquele que você vê publicado, verifique por meio das perguntas se temos disponibilidade; Uma vez que a compra é feita, envie-nos uma mensagem de confirmação; nos informando as especificações desejadas; no caso do tamanho que você pediu não se encaixa corretamente; Por favor note que, como vendedor internacional, não podemos fazer alterações.";

		$ficha_EN="";
		$ficha_ES="";
		$ficha_PG="";
		if (isset($package_weight)){
			$ficha_EN.="\n* PRODUCT WEIGHT: ".($package_weight/100)." lb \n";
			$ficha_ES.="* PESO DEL PRODUCTO:".($package_weight/100)."\n~";
			$ficha_PG.="* PESO DO PRODUTO:".($package_weight/100)."\n~";
		}if (isset($package_height)){
			$ficha_EN.="\n* PRODUCT HEIGHT: ".round($package_height,2)." in \n";
			$ficha_ES.="* ALTO DEL PRODUCTO:".$package_height."\n~";
			$ficha_PG.="* ALTURA DO PRODUTO:".$package_height."\n~";
		}if (isset($package_length)){
			$ficha_EN.="\n* PRODUCT LENGTH: ".round($package_length,2)." in \n";
			$ficha_ES.="* LARGO DEL PRODUCTO:".$package_length."\n~";
			$ficha_PG.="* COMPRIMENTO DO PRODUTO:".$package_length."\n~";
		}if (isset($package_width)){
			$ficha_EN.="\n* PRODUCT WIDTH: ".round($package_width,2)." in \n";
			$ficha_ES.="* ANCHO DEL PRODUCTO:".$package_width."\n~";
			$ficha_PG.="* LARGURA DO PRODUTO:".$package_width."\n~";
		}
		
		$title = eliminar_simbolos(htmlspecialchars_decode($amz_detail['product_title_english']));
		$description        = $title." \n".str_replace(".-", "\n\n* ", htmlspecialchars_decode($amz_detail['specification_english'])).$text_extra.$ficha_EN;		
		if (strlen($title) > 119) {
			$title = substr($title, 0, 120);
			$pos   = strrpos($title, ' ');
			$title = substr($title, 0, $pos);
		}

		if (isset($amz_detail['title_spanish'])){
			$title_spanish = eliminar_simbolos(htmlspecialchars_decode($amz_detail['title_spanish']));		
		}else{
			$title_spanish='';
		}

		$images      = explode("~^~", $amz_detail['image_url']);
		$img_cant    = count($images);
		$item_img    = array();
		if (count($images) > 7) {
			$img_cant = 7;
		}
		for ($j = 0; $j < $img_cant; $j++) {
			array_push($item_img, $images[$j]);
		}
		#array_push($item_img, "http://es.tinypic.com/view.php?pic=33u3nmx&s=9#.Wj16shYU3VM");
		$item_img = implode("~^~", $item_img);

		$attributes=array();

		if(isset($amz_detail['model'])){
			array_push($attributes, array('id' => 'MODEL', 'value_name' => $amz_detail['model'] ));	
		}
		if(isset($amz_detail['brand'])){
			array_push($attributes, array('id' => 'BRAND', 'value_name' => $amz_detail['brand'] ));	
		}

/*
		$buying_mode =  $this->validateCategory($item_category);

		if($buying_mode->attributes_required == "true"){
			$att=" A";
			$attributes_com		=array();
			$variacion = $this->variation_cbt($item_category);
			foreach ($variacion as $var) {
				if($var->id=='10001'){
					if (isset($amz_detail{'color_p_meli'])){	
						foreach ($var->values as $color) {
							$color_tra = $translate -> translate('en', 'es', $amz_detail['color_p_meli']);
							if($amz_detail['color_p_meli']==$color->name){
								array_push($attributes_com, array( 'value_id' => $color->id, 'name' => 'Primary Color','value_name' => $color->name));
							}    	
						}
					}	
				}
				if($var->id=='10000'){
					if (isset($amz_detail['size_meli'])){
						foreach ($var->values as $talla) {
							if($amz_detail['size_meli']==$talla->name){
								array_push($attributes_com, array('value_id' => $talla->id, 'name' => 'Size', 'value_name' => $talla->name));
							}    	
						}
					}
				}
			}
		}

*/
		if ($method == 'post') {
			$post = array(
				'SKU'                                    => $local_item,
				'primary_variation_sku'                  => '',
				'product_type'                           => $amz_detail['product_type'],
				'product_title_english'                  => $title,
				'product_title_spanish'                  => $title_spanish,
				'description_english'					 => (isset($description))?$description:$title,
				'description_portuguese'                 => '',
				'specification_english'                  => $custom_description_en,
				'specification_spanish'                  => $custom_description_es,
				'specification_portuguese'               => $custom_description_pg,
				'category_id'                            => $item_category,
				'brand'                                  => (isset($amz_detail['brand']))?$amz_detail['brand']:'N/B',
				'model'                                  => (isset($amz_detail['model']))?$amz_detail['model']:'Imppera',
				'image_url'                              => $item_img,
				'video_url'                              => $video,
				'country_of_origin'                      => 'US',
				'shipping_from'                          => 'US',
				'UPC'                                    => (isset($amz_detail['ean']))?$amz_detail['ean']:0, 
				'currency'                               => $amz_detail['currency'],
				'sale_price'                             => $sale_price,
				'quantity'                               => 5, #$quantity,
				'merchant_shipping_cost'                 => 0,
				'international_shipping_cost'            => 0,
				'international_shipping_cost_by_country' => '',
				'estimated_delivery_time'                => 1,
				'weight_unit'                            => $amz_detail['weight_unit'],
				'package_weight'                         => $package_weight/100,
				'dimension_unit'                         => 'cm', #$amz_detail['dimension_unit'],
				'package_width'                          => $package_width,
				'package_height'                         => $package_height,
				'package_length'                         => $package_length,
				'condition'                              => 'new',
				'warranty_english'                       => $local_item,#$amz_detail['product_title_english'],
				'warranty_spanish'                       => '',
				'warranty_portuguese'                    => '',
				'translation_required'                   => '1',
				'variation'                              => '',
				'is_primary_variation'                   => 0,
				'attribute_combinations'				 => $attributes,

			);
		}
		$quantity = 3;

		if ($amz_detail['active'] == 'f' || $package_weight == 0 || $sale_price == 0 || $amz_detail['active_cbt'] == 'f' || $amz_detail['bolborrado'] == 1 || $amz_detail['bolborrado'] == 5 || $sale_price == '' || $sale_price == null) {
			$quantity = 0;
		}
		if ($amz_detail['sale_price'] == 0 || $amz_detail['sale_price'] == '' || $amz_detail['sale_price'] == null) {
			$quantity = 0;
		}
		
		if ($quantity == 0) {
			$sale_price = 0;
		}

		
		
		if ($method == 'put') {
			if ($origin == 'front') {
				$post = array(
					'SKU'                   => $local_item,
					'product_title_english' => $title,
					'description_english'	=> ($description == '')?$title:$description,
					'specification_english' => $this->replace_amazon($custom_description),
					'specification_spanish' => $this->replace_amazon($custom_description_es),
					'specification_portuguese' => $this->replace_amazon($custom_description_pg),
					'sale_price'            => $sale_price,
					'quantity'              => $quantity,
					'package_weight'        => $package_weight,
					'warranty_english'      => $local_item,
					'warranty_spanish'      => $local_item,
					'warranty_portuguese'   => $local_item,
					'image_url'             => $item_img,
					'brand'                 => (isset($amz_detail['brand']))?$amz_detail['brand']:'N/B',
					'model'                 => (isset($amz_detail['model']))?$amz_detail['model']:'Imppera',
				);
			} else {
				$post = array(
					'SKU'        			=> $local_item,
					'sale_price' 			=> $sale_price,
					'quantity'   			=> $quantity,
					'description_english'	=> ($description == '')?$title:$description,
					'specification_english' => $this->replace_amazon($custom_description_en),
					'specification_spanish' => $this->replace_amazon($custom_description_es),
					'specification_portuguese' => $this->replace_amazon($custom_description_pg),			
					'warranty_english'      => $local_item,
					'warranty_spanish'      => $local_item,
					'warranty_portuguese'   => $local_item,
					'image_url'             => $item_img,
					'brand'                 => (isset($amz_detail['brand']))?$amz_detail['brand']:'N/B',
					'model'                 => (isset($amz_detail['model']))?$amz_detail['model']:'Imppera',

				);
			}

			if($title_spanish!=''){
				array_push( $post,array('product_title_spanish' => $title_spanish));
			}

		}
	#print_r($post);die();
		return $post;
		#} else {
		#	return 0;
		#}
	}

	public function validate_item($item) {
		$url_products_validate_post = "https://api-cbt.mercadolibre.com/api/SKUs/validate?access_token=".$this->token;
		$ch                         = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_products_validate_post);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $item);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	public function validateCategory($category_id) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api-cbt.mercadolibre.com/api/categories/'.$category_id);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$validation = json_decode(curl_exec($ch));
		curl_close($ch);
		return $validation;
	}

	public function variation_cbt($category_id) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api-cbt.mercadolibre.com/api/categories/'.$category_id.'/attributes');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$validation = json_decode(curl_exec($ch));
		curl_close($ch);
		return $validation;
	}

	public function create_item($item) {
		$url_products_add_post = "https://api-cbt.mercadolibre.com/api/SKUs/?access_token=".$this->token;
		$ch                    = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_products_add_post);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $item);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response);
		return $response;
	}

	public function update_item($item, $mpid) {
		$url_products_update_put = "https://api-cbt.mercadolibre.com/api/SKUs/".$mpid."/?access_token=".$this->token;
		$ch                      = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_products_update_put);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $item);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	public function publish_item($mpid, $contry_to_publish) {
		$url_products_publish_put = "https://api-cbt.mercadolibre.com/api/SKUs/".$mpid."/?access_token=".$this->token;
		$ch                       = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_products_publish_put);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($contry_to_publish));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	public function delete_item($mpid) {
		$url_products_publish_put = "https://api-cbt.mercadolibre.com/api/SKUs/".$mpid."/?access_token=".$this->token;
		$params                   = array("status" => "deleted");
		$ch                       = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_products_publish_put);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	public function get_item($mpid) {
		$url_products_publish_put = "https://api-cbt.mercadolibre.com/api/SKUs/".$mpid."/?access_token=".$this->token;
		$ch                       = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_products_publish_put);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	public function get_orders($status) {
		if ($status==null){
		$url_products_publish_put = "https://api-cbt.mercadolibre.com/api/orders/search/?days=10&page=1&access_token=".$this->token;
		}else{
		$url_products_publish_put = "https://api-cbt.mercadolibre.com/api/orders/search/?days=10&status=".$status."&page=1&access_token=".$this->token;
		}
		$ch                       = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_products_publish_put);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	public function get_orders_by_id($order_id) {
	
		$url_products_publish_put = "https://api-cbt.mercadolibre.com/api/orders/".$order_id."/?access_token=".$this->token;
		$ch                       = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_products_publish_put);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}
/*
$test     = new items('sasdasd');
$category = array('Bath', 'Body Washes');
echo $test->category_match('Health and Beauty', $category);
echo $test->price_converter(10, 1);
 */
function eliminar_simbolos($string) {

	$string = trim($string);

	$string = str_replace(
		array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
		array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
		$string
	);

	$string = str_replace(
		array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
		array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
		$string
	);

	$string = str_replace(
		array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
		array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
		$string
	);

	$string = str_replace(
		array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
		array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
		$string
	);

	$string = str_replace(
		array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
		array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
		$string
	);

	$string = str_replace(
		array('ñ', 'Ñ', 'ç', 'Ç'),
		array('n', 'N', 'c', 'C', ),
		$string
	);

	$string = str_replace(
		array("\\", "¨", "º", "~",
			"#", "@", "|", "!", "\"",
			"·", "$", "/",
			"?", "'", "¡", "(", ")",
			"¿", "[", "^", "<code>", "]",
			"+", "}", "{", "¨", "´",
			">", "< ", ";",
			" "),
		' ',
		$string
	);
	return $string;
}