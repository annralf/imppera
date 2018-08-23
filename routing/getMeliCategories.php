<?php
include "../config/meli_items.php";
$test = new items(1234);
print_r($test->getCategories());