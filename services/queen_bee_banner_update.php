<?php
include '/var/www/html/enkargo/services/meli_create.php';
/*
CREATED AT 20/07/2017
BY Ana Rafaela Guere
Function to get MELI items details from postgreSQL database
 */

$banner = new meliGet();
$banner->bannerUpdate(1);