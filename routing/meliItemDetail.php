<?php
/**
 * URL:http://181.58.30.117/core/routing/cbtPush.php
 * Method: POST
 */

include_once ('../services/meli_get.php');
$cbt = new meliGet();
$cbt->getItemsDetail();