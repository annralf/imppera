<?php
include '../services/system_users.php';

#Service instance
$system_users = new system_users();
if ($_POST['action'] == "create") {
	$system_users->post();
}
if ($_POST['action'] == "get") {
	$system_users->get();
}
if ($_POST['action'] == "update") {
	$system_users->put();
}
if ($_POST['action'] == "delete") {
	$system_users->delete();
}