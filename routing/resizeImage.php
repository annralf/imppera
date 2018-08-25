<?php
include_once ('../services/resize_image.php');

$linio = new ReziseImage();
$linio->adapt($_POST['url']);
