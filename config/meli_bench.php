<?php 
include'/var/www/html/enkargo/config/conex_manager.php';
class Benchmark
{
	private $shop_id;
	private $db;

	function __construct($shop_id)
	{
		$this->shop_id = $shop_id;
		$this->db = new Connect();
	}
	function set_comparison_item(){
		$shop = pg_fetch_object(pg_query("SELECT access_token FROM meli.shop WHERE id = 2"));
		$sql_items = "SELECT  * FROM meli.bench_price_comparison WHERE local_price IS NOT NULL AND aws_price IS NOT NULL AND aws_price <> 0 AND external_store_id IS NULL LIMIT 1000";
		$result_items = pg_query($sql_items);
		$site = "MCO";
		$j = 0;
		while ($items = pg_fetch_object($result_items)) {
			$title = urlencode($items->title);
			$info = $this->get_comparison_item($title, $site);
			$compared_item = array();
			$comp_seller_sales_amount = 0;
			$result_mpid = $this->get_items_details($items->mpid, $shop->access_token);
			foreach ($info->results as $key) {
				if ($key->seller->id !== $items->local_store_id) {
					if ($key->sold_quantity > $result_mpid->sold_quantity && $key->sold_quantity > $comp_seller_sales_amount) {
						$compared_item['sales_price'] = $key->price;
						$compared_item['seller_sales_amount'] = $key->sold_quantity;
						$compared_item['sales_dolar_price'] = $key->price/$items->aws_price;
						$compared_item['is_high'] = 'true';
						$compared_item['difference_price'] = ($key->price > $result_mpid->price) ? ($key->price - $result_mpid->price) : ($result_mpid->price - $key->price);
						$compared_item['external_store_id'] = $key->seller->id;
						$compared_item['seller_mpid'] = $key->id;
						$compared_item['local_mpid'] = $items->mpid;
						$compared_item['aws_id'] = $items->aws_id;
						$compared_item['local_price'] = $result_mpid->price;
						$comp_seller_sales_amount = $key->sold_quantity;
					}
				}
			}
			$date = date("Y-m-d H:i:s");
			if (empty($compared_item)) {
				echo "----------------------- No Comparation $j MPID  $result_mpid->id - $date -----------------------\n";
				$sql_update = "UPDATE meli.bench_price_comparison SET sales_amount = $result_mpid->sold_quantity, local_price = $result_mpid->price, update_date = '$date', thumbnail = '$result_mpid->thumbnail' WHERE id = $items->id";

			}else{
				$compared_item = json_decode(json_encode($compared_item), FALSE);
				echo "----------------------- Comparation $j - MPID Local $result_mpid->id - MPID Seller $compared_item->seller_mpid -  $date -----------------------\n";
				$sql_update = "UPDATE meli.bench_price_comparison SET sales_amount = $result_mpid->sold_quantity, local_price = $result_mpid->price, sales_price = $compared_item->sales_price, seller_sales_amount = $compared_item->seller_sales_amount, sales_dolar_price = $compared_item->sales_dolar_price, is_high = $compared_item->is_high, difference_price = $compared_item->difference_price, external_store_id = '$compared_item->external_store_id', update_date = '$date', thumbnail = '$result_mpid->thumbnail' WHERE id = $items->id";
			}
			$source_update = pg_query($sql_update);
			if ($source_update > 0) {
				echo "\tUpdated Item $items->mpid\n";
			}else{
				echo "\tNO updated Item $items->mpid\n";
			}
			$j++;
		}
	}

	function get_comparison_item($title, $site_id){
		$url = "https://api.mercadolibre.com/sites/$site_id/search?q=$title&sort=relevance";
		$ch       = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$show = json_decode(curl_exec($ch));
		curl_close($ch);
		return $show;
	}

	function get_aws_search($title){
		$title = $this->translate($title);
		#$title = urlencode($this->translate($title));
		$url = "https://www.amazon.com/s/ref=nb_sb_noss_2?url=search-alias%3Daps&field-keywords=$title&rh=i%3Aaps%2Ck%3$title";
		$this->search_item($title);
	}

	function translate($text){
		$text = urlencode($text);
		$url = "https://translate.google.com/?hl=&langpair=es|en&text=$text";
		$path = "#(TRANSLATED_TEXT=')(\N+?')#i";
		$path_del = "<textarea id=source name=text wrap=SOFT tabindex=0 dir=\"ltr\" spellcheck=\"false\" autocapitalize=\"off\" autocomplete=\"off\" autocorrect=\"off\">";
		$response = file_get_contents($url);
		preg_match($path, $response,$return);
		$traduction = substr($return[2],0,-1);
		return $traduction;

	}


	function set_items_details($type){
		switch ($type) {
			case 1:
			$sql = "SELECT mpid FROM meli.bench_local_items";
			break;
			
			case 2:
			$sql = "SELECT mpid, shop FROM meli.bench_shops_items";
				# code...
			break;
		}
		$shop = pg_fetch_object(pg_query("SELECT access_token FROM meli.shop WHERE id = 1;"));
		$result_items = pg_query($sql);
		$i = 0;
		while ($item = pg_fetch_object($result_items)) {
			$info = $this->get_items_details($item->mpid, $shop->access_token);
			switch ($type) {
				case 1:
				$sql_update = "UPDATE meli.bench_local_items SET sales = '$info->sold_quantity' WHERE mpid = '$info->id'";
				break;

				case 2:
				$sql_update = "UPDATE meli.bench_shops_items SET sale_amount = '$info->sold_quantity', thumbnail = '$info->thumbnail', stock = $info->available_quantity WHERE mpid = '$info->id' AND shop = '$item->shop'";
				break;
			}
			$result = pg_query($sql_update);
			$date = date('Y-m-d H:i:s');
			if ($result > 0) {
				echo "$i - Item  $item->mpid - $date\n";
			}else{
				echo "$i - Item $item->mpid Info NO updated - $date\n";	    
			}
			$i++;
		}
	}

	function get_items_details($mpid, $access_token){
		$url = "https://api.mercadolibre.com/items/$mpid?access_token=$access_token";
		$ch  = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$show = json_decode(curl_exec($ch));
		curl_close($ch);
		return $show;   
	}


	function compare_items(){
		$sql_local = "SELECT mpid, title, price, shop_id FROM meli.bench_local_items WHERE sales is not null";
		$sql_sellers = "SELECT id FROM meli.bench_sellers;";
		$result_seller = pg_query($sql_sellers);
		$result_local = pg_query($sql_local);
		$i = 0;
		while ($seller = pg_fetch_object($result_seller)) {
			$sql_other = "select mpid, title, price, sale_amount from meli.bench_shops_items where shop = '$seller->id' order by sale_amount desc limit 10";
			$result_other = pg_query($sql_other);
			$seller_mpid = 0;
			while ($item = pg_fetch_object($result_other)) {
				$percent = 0;
				$j = 1;
				while ($local_item = pg_fetch_object($result_local)) {
					similar_text($item->title, $local_item->title, $percent);		    
					if ($percent > 80) {
						$seller_mpid = $item->mpid;
					}
				}
				if($seller_mpid !== 0){
					$sql_seller_update = "UPDATE meli.bench_shops_items SET is_local = 'true' WHERE mpid = '$seller_mpid'; ";
					pg_query($sql_seller_update);
					echo "$i - $seller_mpid  - is local\n";
				}else{
					echo "$i - $item->mpid - no local \n";		    
				}
				$i++;
			}
		}
	}

	function set_items_visits($type){
		switch ($type) {
			case 1:
			$sql = "SELECT mpid FROM meli.bench_local_items LIMIT 1";
			break;
			case 2:
			$sql = "SELECT mpid FROM meli.bench_shops_items";
			break;
		}
		$result = pg_query($sql);
		$start_date = '2018-01-01';
		$end_date = date('Y-m-d');
		$i = 1;
		while ($info = pg_fetch_object($result)) {
			$visit = $this->get_items_visits($start_date, $end_date, $info->mpid);
			switch ($type) {
				case 1:
				$update = "UPDATE meli.bench_local_items SET visits = '$visit->total_visits' WHERE mpid = '$info->mpid';";
				break;
				case 2:
				$update = "UPDATE meli.bench_shops_items SET visits = '$visit->total_visits' WHERE mpid = '$info->mpid';";
				break;
			}
			$result_update = pg_query($update);
			if ($result_update > 0) {
				echo "$i - Item $info->mpid - visits: $visit->total_visits\n";
			}else{
				echo "$i - Item $info->mpid no update \n";	    
			}
			$i++;

		}
	}


	function get_items_visits($start_date, $end_date, $mpid){
		$url = "https://api.mercadolibre.com/items/$mpid/visits?date_from=$start_date&date_to=$end_date";
		$ch       = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$show = json_decode(curl_exec($ch));
		curl_close($ch);
		return $show;   
	}

	function set_top_items(){
		$sql_query = pg_query("SELECT id FROM meli.bench_sellers;");
		$i = 1;
		$site_id = "MCO";
		$offset = 0;
		$seller_counter = 1;
		while ($seller = pg_fetch_object($sql_query)) {
			$final_result  = array();
			$info = $this->get_seller_items($site_id,$seller->id, 0);
			$info2 = $this->get_seller_items($site_id,$seller->id, 50);
			array_push($final_result, $info->results);
			array_push($final_result, $info2->results);
			$j = 1;
			foreach ($final_result as $key) {
				foreach ($key as $val) {
					echo "Total $i: ";
					echo "Seller $seller_counter : $j - $seller->id ";
					$search = pg_fetch_object(pg_query("SELECT mpid FROM meli.bench_shops_items WHERE mpid = '$val->id' AND shop = '$seller->id';"));
					$date = date("Y-m-d H:i:m");
					if (!isset($search->mpid)) {
						$title = pg_escape_string(utf8_encode($val->title));
						$sql = "INSERT INTO meli.bench_shops_items(mpid, title, price, sale_amount, permalink, is_local, shop)
						VALUES ('$val->id', '$title', '$val->price', '$val->sold_quantity', '$val->permalink', 'false', '$seller->id');";
						$result = pg_query($sql);
						if ($result > 0) {
							echo "MPID $val->id Insert Ok  - $date\n";
						}else{
							echo "MPID $val->id NO Insert  - $date\n";	    
						}
					}else{
						echo "MPID $search->mpid Inserted   - $date\n";
					}
					$i++;
					$j++;
				}
			}
			$seller_counter++;
		}
	}

	function set_seller_total_stock(){
		$sql_query = pg_query("SELECT id FROM meli.bench_sellers;");
		$i = 1;
		$site_id = "MCO";
		while ($seller = pg_fetch_object($sql_query)) {
			$info = $this->get_seller_items($site_id,$seller->id, 0);
			$total = $info->paging->total;
			$sql = "UPDATE meli.bench_sellers SET total_stock = $total WHERE id = '$seller->id'";
			$date = date("Y-m-d H:i:m");
			$result = pg_query($sql);
			if ($result > 0) {
				echo "$i - Seller $seller->id Info updated - $date\n";
			}else{
				echo "$i - Seller $seller->id Info NO updated - $date\n";	    
			}
			$i++;
		}
	}

	function get_seller_items($site_id, $seller_id, $offset){
		$url = "https://api.mercadolibre.com/sites/$site_id/search?seller_id=$seller_id&sort=relevance&offset=$offset";
		$ch       = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$show = json_decode(curl_exec($ch));
		curl_close($ch);
		return $show;
	}

	function set_info_sellers_daily_sales(){
		$sql_query = pg_query("SELECT id, transactions_completed FROM meli.bench_sellers;");
		$i = 1;
		while ($seller = pg_fetch_object($sql_query)) {
			$info = $this->get_info_sellers($seller->id);
			$transactions_completed = $info->seller_reputation->transactions->completed;
			$total_sales = $transactions_completed - $seller->transactions_completed;
			$date = date("Y-m-d H:i:m");
			$visit = $this->get_info_sellers_visits($date,$seller->id);
			$sql = "INSERT INTO meli.bench_sales_day (seller, sales_date, amount, visits) VALUES ($seller->id, '$date', $total_sales,$visit->total_visits);";
			$result = pg_query($sql);
			if ($result > 0) {
				echo "$i $info->nickname - Fecha: $date  - $total_sales \n";
			}else{
				echo "$i - Seller $seller->id Info NO created - $date\n";	    
			}
			$i++;
		}
	}

	function set_info_sellers_daily_visits($date){
		$sql_query = pg_query("SELECT id FROM meli.bench_sellers;");
		$i = 1;
		while ($seller = pg_fetch_object($sql_query)) {
			$info = $this->get_info_sellers_visits($date,$seller->id);
			$sql = "UPDATE meli.bench_sales_day SET visits = '$info->total_visits' WHERE seller = $seller->id AND sales_date >= '$date' AND visits is null;";
			$result = pg_query($sql);
			if ($result > 0) {
				echo "$i $seller->id - Fecha: $date - visitas: $info->total_visits\n";
			}else{
				echo "$i - Seller $seller->id Info NO created - $date\n";	    
			}
			$i++;
		}
	}
	/*    
	  Function for set users details at database
	*/
	  function set_info_sellers(){
	  	/*Is Nuñ for identify new sellers added manually*/
	  	$sql_query = pg_query("SELECT id FROM meli.bench_sellers;");
	  	$i = 1;
	  	while ($seller = pg_fetch_object($sql_query)) {
	  		$info = $this->get_info_sellers($seller->id);
	  		$level_id = $info->seller_reputation->level_id;
	  		$transactions_cancel = $info->seller_reputation->transactions->canceled;
	  		$transactions_completed = $info->seller_reputation->transactions->completed;
	  		$transactions_total = $info->seller_reputation->transactions->total;
	  		$registration_date = date("Y-m-d H:i:m",strtotime($info->registration_date));
	  		$date = date("Y-m-d H:i:m");
	  		$sql = "UPDATE meli.bench_sellers  SET nick_name='$info->nickname', registration_date='$registration_date', user_type='$info->user_type', points='$info->points', permalink='$info->permalink', level_id='$level_id', transactions_cancel='$transactions_cancel', transactions_completed='$transactions_completed', transactions_total='$transactions_total', update_date = '$date' WHERE id = '$seller->id';";
	  		$result = pg_query($sql);
	  		if ($result > 0) {
	  			echo "$i - Seller $seller->id Info updated - $date\n";
	  		}else{
	  			echo "$i - Seller $seller->id Info NO updated - $date\n";	    
	  		}
	  		$i++;
	  	}
	  }

	 /*    
	  CURL function i get user daily visits Mercadolibre
	*/
	  function get_info_sellers_visits($base_date,$seller_id){
		$date = $base_date;#date('Y-m-d');
		$date = strtotime('-1 day', strtotime($date));
		$start_date = date('Y-m-d', $date)."T00:00:00.000-00:00";
		$end_date = $base_date."T00:00:00.000-00:00";
		$show_url = "https://api.mercadolibre.com/users/$seller_id/items_visits?date_from=$start_date&date_to=$end_date";
		$ch       = curl_init();
		curl_setopt($ch, CURLOPT_URL, $show_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$show = json_decode(curl_exec($ch));
		curl_close($ch);
		return $show;
	}
	/*    
	  CURL function i get user services Mercadolibre
	*/
	  function get_info_sellers($seller_id){
	  	$show_url = "https://api.mercadolibre.com/users/$seller_id";
	  	$ch       = curl_init();
	  	curl_setopt($ch, CURLOPT_URL, $show_url);
	  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
	  	$show = json_decode(curl_exec($ch));
	  	curl_close($ch);
	  	return $show;
	  }

	/*    
	  Function to search Top sellers by Master Mercadolibre categories
	*/

	  function set_sellers(){
	  	$result = pg_fetch_object(pg_query("SELECT access_token FROM meli.shop WHERE id = 1;"));
	  	$result_categories = pg_query("SELECT id FROM meli.category_master WHERE padre <> '0';");
	  	$site_id = "MCO";
	  	$i = 1;
	  	while ($category = pg_fetch_object($result_categories)) {
	  		$response = get_sellers_curl($site_id, $category->id, $result->access_token);
	  		foreach ($response->results as $key) {
	  			$id =  $key->seller->id;
	  			$seller = pg_fetch_object(pg_query("SELECT id FROM meli.bench_sellers WHERE id = '$id'"));
	  			if (isset($seller->id)) {
	  				echo "$i - Seller $seller->id is already include\n";
	  			}else{
	  				$result_query = pg_query("INSERT INTO meli.bench_sellers (id) VALUES ('$id')");
	  				if ($result_query > 0) {
	  					echo "$i - Seller $id is success created\n";			
	  				}else{
	  					echo "$i - Seller $id is NOT success created\n";					    
	  				}
	  			}
	  			$i++;
	  		}
	  	}
	  }



	  function get_sellers_curl($site_id,$category_id,$access_token) {
	  	$show_url = "https://api.mercadolibre.com/sites/$site_id/search?category=$category_id&official_store_id=all&access_token=$access_token";
	  	$ch       = curl_init();
	  	curl_setopt($ch, CURLOPT_URL, $show_url);
	  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
	  	$show = json_decode(curl_exec($ch));
	  	curl_close($ch);
	  	return $show;
	  }
	  
	  function search_item($title) {
	  	$endpoint = "webservices.amazon.com";
	  	$uri = "/onca/xml";
	  	$access_key_id = "AKIAJIM77WK37THIDD2A";
	  	$secret_key = "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";
	  	$params = array(
	  		"Service" => "AWSECommerceService",
	  		"Operation" => "ItemSearch",
	  		"AWSAccessKeyId" => "AKIAJIM77WK37THIDD2A",
	  		"AssociateTag" => "alexarodri-20",
	  		"SearchIndex" => "All",
	  		"Keywords" => $title,
	  		"ResponseGroup" => "Images,ItemAttributes,Offers"
	  	);
	  	if (!isset($params["Timestamp"])) {
	  		$params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
	  	}
	  	ksort($params);
	  	$pairs = array();
	  	foreach ($params as $key => $value) {
	  		array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
	  	}
	  	$canonical_query_string = join("&", $pairs);
	  	$string_to_sign         = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;
	  	$signature              = base64_encode(hash_hmac("sha256", $string_to_sign, $secret_key, true));
	  	$request_url            = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);
	  	$url                    = "http://webservices.amazon.com/onca/xml";

		#Accediendo al url encoding xml
	  	$ch = curl_init();
	  	curl_setopt($ch, CURLOPT_URL, $request_url);
	  	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
	  	$response = curl_exec($ch);
	  	curl_close($ch);
		#$xml = simplexml_load_string($response);
	  	$result = array();
	  	echo "<pre>";
	  	echo $response;
		#print_r($xml->Items->Request->ItemLookupRequest->ItemId);
		#die();
	  }
	}

	$t = new Benchmark(2);
	#$t->get_aws_search("Allied Telesis 16 Puertos Gigabit Websmart");
	#$date = "2018-10-01";
	$t->set_comparison_item();
