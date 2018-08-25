#!/usr/bin/php
<?php
include '/var/www/html/enkargo/config/meli_items.php';

$conn        = new DataBase();

if (empty($argv[1])) die("The json file name or URL is missed\n");
$jsonFilename = $argv[1];

$json = file_get_contents($jsonFilename);
$array = json_decode($json,true);
$f = fopen('php://output', 'w');

$firstLineKeys = false;
$b=1;
foreach ($array as $line)
{
$a=0;	#var_dump($line);
	$line = array_values($line);
	$id= $line[0];
	$name=$line[1];
	$padre=$line[2];
	$hijos=$line[3];
	#echo $id."-".$name."\n";
	
	foreach ($padre as $array_)
	{
		$a++;
		
	}

    $a--;

	if ($a == 0){
		$application = $conn->prepare("insert into cbt.category_master (id,padre,definition) values (".$line[0].",0,'".$line[1]."'');");
		$application->execute();
		echo $b."- id=".$line[0]."- padre_id= 0 - nombre =".$line[1]."\n";
	}else{
		$application = $conn->prepare("insert into cbt.category_master (id,padre,definition) values (".$line[0].",".$line[2][count($padre)-2]['id'].",'".$line[1]."');");
		$application->execute();

		echo $b."- id=".$line[0]."- padre_id=".$line[2][count($padre)-2]['id']."- nombre =".$line[1]."\n";
	}

	$b++;
	
	/*
	if (empty($firstLineKeys))
	{
		$firstLineKeys = array_keys($line);
		fputcsv($f, $firstLineKeys);
		$firstLineKeys = array_flip($firstLineKeys);
	}
	// Using array_merge is important to maintain the order of keys acording to the first element
	fputcsv($f, array_merge($firstLineKeys, $line));
	*/
}
?>