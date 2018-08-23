<?php
/**
 * URL:http:181.58.30.117/enkargo/routing/cbtUpdateLocal.php?xml=lol
 * Method: POST
 */

include_once ('../services/cbt_update.php');
$cbt = new CbtUpdate();
$cbt->update_local();