<?php 
/**
 * URL:http://181.58.30.117/core/routing/cbtAddAws.php?application=idApplication&items=items(1-*array)&asin=asin&access_token=access_token
 * Method: POST
*/

include_once('../services/cbt.php');
$cbt = new Cbt();
$cbt->add_aws();