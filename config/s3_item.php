<?php

require('/var/www/html/enkargo/config/semantics3-php/lib/Semantics3.php');

class Semantics3{
	public $s3_access_key_id;
	public $s3_secret_key;
	
    public function __construct() {
		$this->s3_access_key_id = "SEM309F0495396265473E70D35B9FC63214F";
		$this->s3_secret_key    = "ZjJjYzAwYzk5YWY0MDc5ZGU2ZjY5OWFhZDNjMjJiNjQ";
		
	}

	#buscar items
    public static function search($item)
    {
        if (!$item) {
            return ['code' => 400, 'message' => 'Search request missing a product.'];
        }
        $key = "SEM309F0495396265473E70D35B9FC63214F";
        $secret = "ZjJjYzAwYzk5YWY0MDc5ZGU2ZjY5OWFhZDNjMjJiNjQ";
        $requestor = new Semantics3_Products($key, $secret);
        # Build the request
        $requestor->products_field("search", $item);
        # Run the request
        $results = json_decode($requestor->get_products());
        $requestor->clear_query();
        $total=$results->total_results_count;
        # View the results of the request
        return (int) $total;
    }

    #buscar items offset
     public static function search_offset($item,$offset)
    {
        $result            = array();
        if (!$item) {
            return ['code' => 400, 'message' => 'Search request missing a product.'];
        }
        
        $key = "SEM309F0495396265473E70D35B9FC63214F";
        $secret = "ZjJjYzAwYzk5YWY0MDc5ZGU2ZjY5OWFhZDNjMjJiNjQ";
        $requestor = new Semantics3_Products($key, $secret);
        # Build the request
        $requestor->products_field("search", $item);
        $requestor->products_field( "offset", $offset );
        $requestor->products_field("site", "amazon.com");
        $requestor->products_field("activeproductsonly", "1");
        #$requestor->products_field("sort", "price","asc");
        
        # Run the request
        $results = json_decode($requestor->get_products());

        if (isset($results->results)) {
            foreach ($results->results as $root) {
                foreach ($root->sitedetails as $site) {
                if(!empty($root->upc)){
                    $item_detail          = array();
                    $item_detail['upc']   = (string) $root->upc;
                    $item_detail['title'] = (string) $root->name;
                    $item_detail['url']   = (string) $site->url;
                    $item_detail['sku']   = (string) $site->sku;
                    $item_detail['seller']  = (string) $site->name;
                    array_push($result, $item_detail);
                    }
                    # code...

                }

            }

        }
        $requestor->clear_query();
        # View the results of the request
        return $result;
    }

    #buscar por marcas
    public static function brand($brand)
    {
        if (!$brand) {
            return ['code' => 400, 'message' => 'Search request missing a product.'];
        }
        $key = env($this->s3_access_key_id);
        $secret = env($this->s3_secret_key);
        $requestor = new Semantics3_Products($key, $secret);
        # Build the request
        $requestor->products_field("brand", $brand);
        # Run the request
        $results = $requestor->get_products();
        $requestor->clear_query();
        # View the results of the request
        return $results;
    }

    #buscar por upc
    public static function upc($item)
    {
        if (!$item) {
            return ['code' => 400, 'message' => 'Search request missing a product.'];
        }
        $key = env($this->s3_access_key_id);
        $secret = env($this->s3_secret_key);
        $requestor = new Semantics3_Products($key, $secret);
        # Build the request
        $requestor->products_field("upc", $item);
        # Run the request
        $results = $requestor->get_products();
        $requestor->clear_query();
        # View the results of the request
        return $results;
    }

    #buscar items por sitio web
    public static function site_query($item, $site)
    {
        if (!$item && !$site) {
            return ['code' => 400, 'message' => 'Requires an item and a website to search from.'];
        }
        $key = env($this->s3_access_key_id);
        $secret = env($this->s3_secret_key);
        $requestor = new Semantics3_Products($key, $secret);
        # Build the request
        $requestor->products_field("search", $item);
        $requestor->products_field("site", $site);
        # Run the request
        $results = $requestor->get_products();
        $requestor->clear_query();
        # View the results of the request
        return $results;
    }

    #buscar categorias
    public static function categories($cat)
    {
        if (!$cat) {
            return ['code' => 400, 'message' => 'Search request missing a category.'];
        }
        $key = env($this->s3_access_key_id);
        $secret = env($this->s3_secret_key);
        $requestor = new Semantics3_Products($key, $secret);
        $requestor->categories_field("name", $cat);
        $results = $requestor->get_categories();
        $requestor->clear_query();
        return $results;
    }
}