<?php

$data = file_get_contents("/var/www/html/enkargo/docs/categoriesMexico.json");
$products = json_decode($data, true);

foreach ($products as $product) {
    print_r($product);
    die();
}