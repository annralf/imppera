<?php 
/**
 * URL:http://181.58.30.117/core/routing/cbtDelete.php?access_token=access_token&mpid=mpid
 * Method: POST
*/

include_once('../services/cbt.php');
$cbt = new Cbt();
$cbt->delete();