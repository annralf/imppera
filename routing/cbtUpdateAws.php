<?php
/**
 * URL:http://181.58.30.117/core/routing/cbtUpdateAws.php?access_token=access_token&application_id=application_id&SKU=SKU&product_title_english=product_title_english&specification_english=specification_english&sale_price=sale_price&quantity=quantity&package_weight=package_weight&is_prime=is_prime
 * Method: POST
 */

include_once ('../services/cbt_update.php');
$cbt = new CbtUpdate();
$cbt->update_aws();