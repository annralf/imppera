<?php
include_once '/var/www/html/enkargo/config/pdo_connector.php';


class items {
	public $access_token;
	public $user_name;
	public $source;
	public $target;
	public $categories_list = array();
	public $conn;
	

	public function __construct($access_token,$user_name) {
		$this->access_token = $access_token;
		$this->user_name 	= $user_name;
		$this->conn         = new DataBase();
		$this->source       = 'en';
		$this->target       = 'es';
		
	}
	public function getAws($asin) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://core.enkargo.com.co/core_enkargo/services/getAmzDet.php?asin='.$asin);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$amz = json_decode(curl_exec($ch));
		curl_close($ch);
		return $amz;
	}

	public function leaf_category($category_id, $total_aws_category, $category_name) {
		$percent        = 0;
		$match_category = 0;
		$query_category = $this->conn->prepare("select * from meli.category_master where padre ='".$category_id."'");
		$query_category->execute();
		$query_category = $query_category->fetchAll();
		if ($total_aws_category == 1) {
			foreach ($query_category as $category) {
				similar_text($category_name[0], htmlspecialchars_decode($category['definition']), $percent);
				if ($percent > $match_category) {
					$match_category = $percent;
					$category_id    = $category['id'];
				}
			}
			return $category_id;
		}else{
			foreach ($query_category as $category) {
				for ($i = 0; $i < $total_aws_category; $i++) {
					similar_text($category_name[$i], htmlspecialchars_decode($category['definition']), $percent);
					if ($percent > $match_category) {
						$match_category = $percent;
						$category_id    = $category['id'];
						#echo $category_id."\n";
					}
				}
			}}
			$count_category = $this->conn->prepare("select count(*) from meli.category_master where padre = '".$category_id."';");
			$count_category->execute();
			$count_category = $count_category->fetch();
			#echo $count_category['count'];
			if ($count_category['count'] > 3) {
				#echo $category_id."count-\n";
				return $this->leaf_category($category_id, $total_aws_category, $category_name);

			} else {
				#die('lol');
				return $category_id;
			}
		}
		public function category_match_aws($category_name,$meli_padre_id) {
			$match_meta_category = 0;
			$match_sub_category  = 0;
			$match_category      = 0;
			$percent             = 0;	
			$category_name      = explode(",", $category_name);
			$total_aws_category = count($category_name);				
			$sub_category_id    = $meli_padre_id;
			$percent            = 0;
			$count_category = $this->conn->prepare("select count(*) from meli.category_master where padre = '".$meli_padre_id."';");
			$count_category->execute();
			$count_category = $count_category->fetch();
			if ($count_category['count'] > 1) {
				$root = $this->leaf_category($meli_padre_id, $total_aws_category, $category_name);
			} else {
				$root = $sub_category_id;
			}
			$category_id = $this->leaf_category($meli_padre_id, $total_aws_category, $category_name);
			$root = $this->validateCategory($category_id);
			$last = 0;

			if(isset($root->children_categories)){
				if (count($root->children_categories) > 0) {
					foreach ($root->children_categories as $key => $value) {
						similar_text($root->name, $value->name, $last);
						if ($last > $match_category) {
							$match_category = $last;
							$category_id    = $value->id;
						}
					}
					$child = $this->validateCategory($category_id);
					if (count($child->children_categories) > 0) {
						foreach ($child->children_categories as $key_ => $value_) {
							if ($value_->name == "Otros") {
								$category_id = $value_->id;
							}else{
								$category_id = $value_->id;							
							}
						}
					}
					$last = 0;
					$match_category = 0;
					$last_ = $this->validateCategory($category_id);
					if (count($last_->children_categories) > 0) {
						foreach ($last_->children_categories as $key_ => $valueL) {
							similar_text("Otr", $valueL->name, $last);
							if ($last > $match_category) {
								$match_category = $last;
								$category_id    = $valueL->id;
							}
						}
					}
					return $category_id;
				}else{
					return $category_id;
				}
			}else{
				return $category_id;
			}
		}

		public function validateCategory($category_id) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/categories/'.$category_id);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

			$validation = json_decode(curl_exec($ch));
			curl_close($ch);

			return $validation;
		}

		public function validateCategory_by_user($category_id) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/users/'.$this->user_name.'/shipping_modes?category_id='.$category_id);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

			$validation = json_decode(curl_exec($ch));
			curl_close($ch);

			return $validation;
		}

		public function getChildCategories($id_sub, $sub) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/categories/'.$sub);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
			$child_categories = json_decode(curl_exec($ch));
			curl_close($ch);
			foreach ($child_categories->path_from_root as $root) {
				$root_query = pg_fetch_object(pg_query("INSERT INTO meli.category_sub(sub_category, child_category) VALUES ( '".$id_sub."', '".$root->id."') RETURNING id;"));
			}
			foreach ($child_categories->children_categories as $child) {
				$child_query = pg_fetch_object(pg_query("INSERT INTO meli.child_category(id_category, name) VALUES ('".$child->id."', '".$child->name."') RETURNING id;"));
				$ch          = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/categories/'.$child->id);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
				$child_categories_ = json_decode(curl_exec($ch));
				curl_close($ch);
			}
			return 1;
		}

		public function getCategories() {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/sites/MCO/categories');
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
			$main_categories = json_decode(curl_exec($ch));
			curl_close($ch);
			foreach ($main_categories as $cat) {
				$main_query = pg_fetch_object(pg_query("INSERT INTO meli.main_category(id_category, name) VALUES ('".$cat->id."', '".$cat->name."') RETURNING id;"));
				$ch         = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/categories/'.$cat->id);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
				$sub_categories = json_decode(curl_exec($ch));
				curl_close($ch);
				foreach ($sub_categories->children_categories as $sub) {
					$sub_query = pg_fetch_object(pg_query("INSERT INTO meli.sub_category(id_category, name, main_cat_id) VALUES ('".$sub->id."', '".$sub->name."', '".$main_query->id."') RETURNING id;"));
					$ch        = curl_init();
					curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/categories/'.$sub->id);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
					$child_categories = json_decode(curl_exec($ch));
					curl_close($ch);
					foreach ($child_categories->path_from_root as $root) {
						$root_query = pg_fetch_object(pg_query("INSERT INTO meli.category_sub(sub_category, child_category) VALUES ( '".$sub_query->id."', '".$root->id."') RETURNING id;"));
					}
					foreach ($child_categories->children_categories as $child) {
						$child_query = pg_fetch_object(pg_query("INSERT INTO meli.child_category(id_category, name) VALUES ('".$child->id."', '".$child->name."') RETURNING id;"));
						$ch          = curl_init();
						curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/categories/'.$child->id);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
						$child_categories_ = json_decode(curl_exec($ch));
						curl_close($ch);
					}
				}
			}
			return $main_categories;
		}

		public function getCategoriesPredictor($title) {
			$main_categories_url = "https://api.mercadolibre.com/sites/MCO/category_predictor/predict?title=".urlencode($title);
			$ch                  = curl_init();
			curl_setopt($ch, CURLOPT_URL, $main_categories_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
			$ml_category = json_decode(curl_exec($ch));
			curl_close($ch);
			$category_id = isset($ml_category->id)?$ml_category->id:NULL;
			return $category_id;
		}

		public function liquidador($price, $package_weight, $shop) {
			$converted_price = 0;
			$price           = ceil($price);
			$weight          = ceil($package_weight);
			if($weight<1){
				$weight=1;
			}
			if ($weight == 0 ){
				$weight =1;
			}

			$dollar          = 5700;
			$weight_price    = 15000;
		#$weight          = ($package_weight/100)/2;
		#Povisional price calculator 20/07/2017
		#Conditional 1
		#if ($price <= 10 && $weight <= 2) {
			if ($price <= 10) {	
			#$final_price = 71800;
				$base_p = $price*5800;
				$final_price = ($base_p+(($base_p*11)/100))+(13000*$weight);
			}
			else
			{

			/*if ($price <= 10 && $weight > 2) {
				$final_price = 71800+(($weight-2)*13000);			
			} */
			
			if ($price > 10 && $weight <= 10 && $price <= 100) {
				$weight_price = 13000;
			}

			if ($price > 100) {
				$dollar = 5200;
				if ($weight <= 10) {
					$weight_price = 13000;
				}
			}

			if ($weight >= 50) {
				$weight_price = 25000;
			}
			$final_price = ($price*$dollar)+($weight*$weight_price);
		}
		$mod         = $final_price%1000;
		$final_price = ($final_price+($mod < (1000/2)?-$mod:1000-$mod))-201;
		return (int) $final_price;
	}

	public function liquidador_pro($price, $package_weight, $prime,$shop) {
		$pricel         = ceil($price);
		$weight        = $package_weight;
		$mer_env =0;
		$env_int =0;



		#echo $weight."-------------------"; 
		#minima de venta 3$ 
		if ($weight <  1){	$weight =1;	}
		if ($weight == 0){	$weight =1;	}
		if ($pricel <  3){	$pricel =3;	}
		if ($pricel == 0){	$pricel =3;	}
		$dollar          = 0;
		#$weight_price    = 15000;
		#se asugna el precio del $
		if ($pricel >= 100) 	{	$dollar = 4800;	}
		if ($pricel <= 99) 	{	$dollar = 4900;	}
		if ($pricel <= 50) 	{	$dollar = 5000;	}
		if ($pricel <= 30) 	{	$dollar = 5200;	}
		if ($pricel <= 20) 	{	$dollar = 5400;	}
		if ($pricel <= 11) 	{	$dollar = 5600;	}
		if ($pricel <= 7) 	{	$dollar = 6800;	}

		#se le suma el shippind de amazon
		if($prime == 0){
			if ($pricel >= 51) {	$shipp = 40000;	}
			if ($pricel <= 50) {	$shipp = 24000;	}
		}else{
			$shipp=0;
		}
		#se asigna el precio de envio nacional
		if($weight < 2){
			$mer_env=3000;
		}else{
			if($weight  > 110){	$mer_env=30000;	}
			if($weight <= 110){	$mer_env=23000;	}
			if($weight <= 88){	$mer_env=15000;	}
			if($weight <= 66){	$mer_env=14000;	}
			if($weight <= 44){	$mer_env=11000;	}
			if($weight <= 22){	$mer_env=7300;	}
			if($weight <= 11){	$mer_env=4300;	}
		}
		$env_int = ($weight)*5000;

		$final_price = ($pricel*$dollar)+$mer_env+$env_int+$shipp;
		//echo "Precio final: ".$final_price."\nPrecio en $: ".$price."\nPeso libras: ".$weight."\nEnvio Int: ".$env_int."\nEnvio Nac: ".$mer_env."\nInversa: ".$inversa;
		$mod         = $final_price%1000;
		if ($shop==1){
			$final_price = ($final_price-$mod)+1599;
		}
		if ($shop==2){
			$final_price = ($final_price-$mod)+1533;
		}
		

		if($shop==3){
			$pricem 	= $price;
			$peso 	= 0;
			if ($pricem > 100) 	{	$dollar = 139;	}
			if ($pricem <= 100) {	$dollar = 124;	}
			if ($pricem <= 70) 	{	$dollar = 74;	}
			if ($pricem <= 50) 	{	$dollar = 52;	}
			if ($pricem <= 19) 	{	$dollar = 38;	}
			if ($pricem <= 10) 	{	$dollar = 32;	}
			if ($pricem <= 7) 	{	$dollar = 30;	}

			if($weight>1){
				$peso=$weight*7;
			}
			$final_price = ($pricem+$dollar+$peso)*21.48;	
		}
		
		return (int) $final_price;
	}

	public function variation($category_id) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/categories/'.$category_id.'/attributes');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

		$validation = json_decode(curl_exec($ch));
		curl_close($ch);

		return $validation;
	}

	public function validate($item) {
		$validation_url = "https://api.mercadolibre.com/items/validate?access_token=".$this->access_token;
		$ch             = curl_init();
		curl_setopt($ch, CURLOPT_URL, $validation_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($item));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

		$validation = json_decode(curl_exec($ch));
		curl_close($ch);
		return $validation;
	}
	public function banner($item_id, $item) {
		$update_url = "https://api.mercadolibre.com/items/".$item_id."/description?access_token=".$this->access_token;
		$ch         = curl_init();
		$item       = json_encode($item);
		curl_setopt($ch, CURLOPT_URL, $update_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $item);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$update = json_decode(curl_exec($ch));
		curl_close($ch);
		return $update;
	}

	public function update($item_id, $item) {
		$update_url = "https://api.mercadolibre.com/items/".$item_id."?access_token=".$this->access_token;
		$ch         = curl_init();
		$item       = json_encode($item);
		curl_setopt($ch, CURLOPT_URL, $update_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $item);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$update = json_decode(curl_exec($ch));
		curl_close($ch);
		return $update;
	}

	public function relist($item_id, $item) {
		$update_url = "https://api.mercadolibre.com/items/".$item_id."/relist?access_token=".$this->access_token;
		$ch         = curl_init();
		$item       = json_encode($item);
		curl_setopt($ch, CURLOPT_URL, $update_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $item);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$update = json_decode(curl_exec($ch));
		curl_close($ch);
		return $update;
	}
	public function create($item) {
		$show_url = "https://api.mercadolibre.com/items?access_token=".$this->access_token;
		$ch       = curl_init();
		curl_setopt($ch, CURLOPT_URL, $show_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($item));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$show = json_decode(curl_exec($ch));
		curl_close($ch);
		return $show;
	}

	public function show($item) {
		$show_url = "https://api.mercadolibre.com/items?ids=".$item."?access_token=".$this->access_token;
		$ch       = curl_init();
		curl_setopt($ch, CURLOPT_URL, $show_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$show = json_decode(curl_exec($ch));
		curl_close($ch);
		return $show;
	}


	public function visits($item,$star_time) {


		$show_url = "https://api.mercadolibre.com/items/".$item."/visits?date_from=".$star_time."T23:59:59Z&date_to=".date('Y-m-d',time())."T00:00:00.000Z";
		$ch       = curl_init();
		curl_setopt($ch, CURLOPT_URL, $show_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$show = json_decode(curl_exec($ch));
		curl_close($ch);
		return $show;
	}

	public function show_by_sku($item, $shop) {
		$show_url = "https://api.mercadolibre.com/users/".$shop."/items/search?sku=".$item."&access_token=".$this->access_token;
		$ch       = curl_init();
		curl_setopt($ch, CURLOPT_URL, $show_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$show = json_decode(curl_exec($ch));
		curl_close($ch);
		return $show;
	}


	public function order_recent($shop) {
		$show_url = "https://api.mercadolibre.com/orders/search/recent?seller=".$shop."&sort=date_desc&access_token=".$this->access_token;
		$ch       = curl_init();
		curl_setopt($ch, CURLOPT_URL, $show_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$show = json_decode(curl_exec($ch));
		curl_close($ch);
		return $show;
	}

	public function shipping_by_id($id) {
		$show_url = "https://api.mercadolibre.com/shipments/".$id."?access_token=".$this->access_token;
		$ch       = curl_init();
		curl_setopt($ch, CURLOPT_URL, $show_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$show = json_decode(curl_exec($ch));
		curl_close($ch);
		return $show;
	}

	public function order_by_id($shop,$id) {
		$show_url = "https://api.mercadolibre.com/orders/search?seller=".$shop."&q=".$id."&access_token=".$this->access_token;
		$ch       = curl_init();
		curl_setopt($ch, CURLOPT_URL, $show_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$show = json_decode(curl_exec($ch));
		curl_close($ch);
		return $show;
	}

	public function label_by_ship($id) {
		$show_url = "https://api.mercadolibre.com/shipment_labels?shipment_ids=".$id."&savePdf=Y&access_token=".$this->access_token;
		return $show_url;
	}

	public function send_message($status, $shop, $order, $access_token, $name, $user_name){
		$message = "Gracias por su compra";
		$date = date('Y-m-d H:i:s');
		$subject = $name;
		#Validación del tipo de mensasje según es tipo de estatus de la orden
		switch ($status) {
			case 1:
			$message ="Hola 😄, muy buen día, espero te encuentres muy bien, mi nombre es Sebastian y voy a acompañarte en todo el proceso de tu compra. 😎 \n
			Primero que todo, gracias por preferirnos, te comentamos que ya está acreditado tu pago 💰 y el numero de compra es el  ,a partir de hoy realizaremos la orden de importación de tu producto, recuerda que el tiempo de entrega es de ✈ 7 a 10 días hábiles (como máximo) ✈ , esto se debe a que trabajamos directamente con la marca en Estados Unidos. 😄\n
			Por favor ten en cuenta que MercadoLibre maneja una fecha de entrega estimada diferente a la nuestra, por lo tanto te llegaran diferentes correos de MercadoLibre preguntándote como va el proceso de tu compra, estos correos solo debes omitirlos, yo te estaré informando todo el tiempo el estado de tu pedido, si tienes alguna duda, pregunta, queja o reclamo, no dudes primero en comunicarte conmigo por este medio o si gustas puedes comunicarte vía teléfono al PBX 7535495 Opción 1 📞 donde te atenderé personalmente para responder todas tus inquietudes. 😄\n
			Gracias nuevamente por tu compra y que tengas un día increíble. 😄";
			break;
			case 2:
			$message ="Hola, muy buenos días, te informamos que tu producto ya esta ingresando a Colombia exitosamente, esperamos poder realizarte el envío del producto lo mas antes posible, es un placer para nosotros poder servirte, por favor has caso omiso a los correo de Mercado Libre con respecto a los tiempos de entrega o \"envio demorado\", esto sucede ya que ellos no saben sobre nuestros tiempos de entrega,recuerda que es de 4 a 10 dias habiles como maximo, muchas gracias por tu comprension y paciencia, espero tengas un excelente día.";
			break;
			case 3:
			$message ="Muy buen día, me alegra informarte que tu producto esta en proceso de nacionalización y estamos a la espera de que llegue a nuestra oficina para hacerte el despacho, nosotros te notificamos cuando esto pase para que estés atento a recibirlo";
			break;
			case 4:
			$message ="Muy buen día,Es un gusto saludarte, te cuento tu producto ya esta en Colombia esta en revisión aduanera pasando los respectivos controles colombianos esperamos que este llegando lo más pronto posible a tu hogar. Gracias por tu paciencia te deseamos un Feliz día.";
			break;
			case 5:
			$message ="Buen día, espero estés muy bien, ya tenemos tu producto listo para ser enviado en nuestras oficinas, lo entregaremos hoy al transportador para que te entreguen en la dirección que nos confirmaste a través de la plataforma, te agradecemos nuevamente por tu compra, y esperamos tenga sun muy buen día.";
			break;
			default:
			$message = "Gracias por su compra";
			break;
		}

		$payer_id =$this->conn->prepare("select o.id_payer,p.first_name, p.last_name from system.orders as o join system.payer as p on p.id_payer = o.id_payer where o.id_order= '$order'");
		$payer_id->execute();
		$payer_id = $payer_id->fetch();
		if($payer_id){
			$order_message = $this->conn->prepare("select * from system.orders_messages where order_id = '$order' and status = '$status';");
			$order_message->execute();
			$order_message = $order_message->fetch();
			if ($order_message['id']) {
				return "Not sent message duplicated";
			}else{
				$url = "https://api.mercadolibre.com/messages?access_token=$access_token";
				$messages_structure = array(
					'from'=>array(
						'user_id'=> $user_name
					),
					'to' =>array(array(
						'user_id'=> $payer_id['id_payer'],
						'resource' => 'orders',
						'resource_id' => $order,
						'site_id' => 'MCO'
					)),
					'subject' => $subject,
					'text' =>array(
						'plain' => $message
					)
				);
				$ch             = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messages_structure));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
				$validation = json_decode(curl_exec($ch));
				curl_close($ch);
				$message_id = $validation[0]->message_id;
				if(!isset($validation->error)){
					$resource = $this->conn->exec("insert into system.orders_messages (order_id, message_id, send_date,status) values ('$order','$message_id','$date','$status');");
					return "Success stored message Id:"+$message_id;
				}else{
					$resource = $this->conn->exec("insert into system.orders_messages (order_id, message_id, send_date) values ('$order','UNABLE SENT','$date','$status');");		
					return "Error at send message";
				}
			}
		}else{
			return "Not sent";
		}
	}

	public function search_item($params) {

	}

	public function paused_item($status, $mpid, $type) {
		$temp = array();
		if ($status != "closed") {
			$result = $this->update($mpid, array('status' => 'paused'));
		}
		return 1;
	}

	public function delete_item($status, $mpid, $type) {
		$temp = array();
		if ($type == "delete_item") {
			$this->update($mpid, array('deleted' => 'true'));
			$this->conn->exec("DELETE from meli.items where mpid ='".$mpid."';");

		} else {
			if ($status != "closed") {
				$result = $this->update($mpid, array('status' => 'closed'));
			}
		}
		return 1;
	}


	public function replace_amazon($string) {
		$to_replace = array("Amazon", "amazon", "Prime", "prime","LIFETIME WARRANTY","100% SATISFACTION GUARANTEED","your money back.", "LIFETIME WARRANTY - 100% SATISFACTION GUARANTEED or your money back.");
		$string     = str_replace($to_replace, ' ', $string);
		return $string;
	}
}
#$test = new items(1234);
#print_r($test->liquidador(150, 0.78, 1));
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
			"·", "$", "%", "/",
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