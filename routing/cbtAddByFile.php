<?php
/**
 * URL:http://181.58.30.117/core/routing/cbtAddLocal.php?application=idApplication&acccess_token=acccess_token&asin=asin&category_id=category_id(optional)
 * Method: POST
 */

include_once ('../services/cbt.php');
$cbt = new Cbt();
$cbt->add_by_file();