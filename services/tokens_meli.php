<?php
include '/var/www/html/enkargo/config/meli_items.php';
/*
CREATED AT 20/07/2017
BY Ana Rafaela Guere
Funtion to get MELI items details from postgreSQL database
 */
$conn        = new DataBase();
$application = $conn->prepare("SELECT * FROM meli.shop WHERE id = '1';");
$application->execute();
$application = $application->fetchAll();
echo "<pre>";
print_r($application);