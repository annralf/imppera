<?php
include '../services/aws_item.php';

#Service instance
$aws_item = new aws_item();
if ($_POST['action'] == "get") {
	$aws_item->get();
}
if ($_POST['action'] == "getItems") {
	$aws_item->getItems();
}