<?php
include "/var/www/html/enkargo/services/cbt_order.php";

$create = new CbtOrders(3);
$create->orders();