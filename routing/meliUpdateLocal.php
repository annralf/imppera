<?php
/**
 * URL:http:181.58.30.117/enkargo/routing/meliUpdateLocal.php?xml=lol
 * Method: POST
 */

include_once ('/var/www/html/enkargo/services/meli_update.php');
$meli = new meliUpdate();
$meli->update_local_csv();
#$meli->update_local();
