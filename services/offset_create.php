<?php
include '/var/www/html/enkargo/config/meli_items.php';
$conn      = new DataBase();
$secuence_update = $conn->prepare("UPDATE meli.secuences SET offset_ = 0 WHERE type IN ('createQb','createMa','getItemsQB','getItemsMa') ;");
$secuence_update->execute();
$conn->close_con();

$secuence_updatecbt = $conn->prepare("UPDATE cbt.secuences SET offset_ = 0;");
$secuence_updatecbt->execute();
$conn->close_con();