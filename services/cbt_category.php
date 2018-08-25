<?php
include '../config/conex_manager.php';
#ini_set("display_errors", 0);
#ini_set('error_reporting', E_ALL|E_STRICT);
$conn = new Connect();

echo "<pre>";
$file = fopen('../docs/cbt_categories.csv', 'r');
while ($row = fgetcsv($file)) {
	$id_category = $row[0];
	$name_category = $row[1];
	$meta_category = $row[2];
	$sub_category = explode(">", $row[3]);
	$size = $row[5];
	$color1 = $row[6];
	$color2 = $row[7];
	#Set meta category
	$id_meta_category = pg_fetch_object(pg_query($conn->conn, "SELECT id FROM cbt.meta_category WHERE name = '".$meta_category."'"));
	if($id_meta_category == NULL){
		$id_meta_category = pg_fetch_object(pg_query($conn->conn, "INSERT INTO cbt.meta_category (name) VALUES ('".$meta_category."') RETURNING id;"));
	}
	#Set category
	$category = pg_fetch_object(pg_query($conn->conn, "INSERT INTO cbt.category (id, name, size,first_color, second_color) VALUES ('".$id_category."','".$name_category."','".$size."','".$color1."', '".$color2."') RETURNING id;"));
	#Set sub category
	for ($i = 1; $i < count($sub_category)-1; $i++) {
		$id_sub_category = pg_fetch_object(pg_query($conn->conn, "SELECT id FROM cbt.sub_category WHERE name = '".$sub_category[$i]."'"));
		if($id_sub_category == NULL){
			$id_sub_category = pg_fetch_object(pg_query($conn->conn, "INSERT INTO cbt.sub_category (name, meta_category_id) VALUES ('".$sub_category[$i]."', '".$id_meta_category->id."')  RETURNING id;"));		
		}
		#Set Join categories
		$join = pg_query($conn->conn, "INSERT INTO cbt.category_sub (category_id, sub_category_id) VALUES ('".$id_category."','".$id_sub_category->id."')");
	}
	echo $name_category."\b";
}