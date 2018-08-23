<?php
include '/var/www/html/enkargo/config/meli_items.php';
/*
CREATED AT 20/07/2017
BY Ana Rafaela Guere
Funtion to get MELI items details from postgreSQL database
 */
/*---------conexion a la base de datos y access token--------------*/
$i           = 1;
$conn        = new DataBase();
$application = $conn->prepare("SELECT * FROM meli.shop WHERE id = '2';");
$application->execute();
$application = $application->fetchAll();
$meli_item   = new items($application[0]['access_token']);
$translate   = new GoogleTranslate();
$shop        = $application[0]['id'];
/*-------------------secuenciado de Mauxi----------------------*/
$secuence    = $conn->prepare("SELECT * FROM meli.secuences WHERE type = 'getItemsMa';");
$secuence->execute();
$secuence    = $secuence->fetchAll();
$offset      = $secuence[0]['offset_']+300;
$secuence_update = $conn->prepare("UPDATE meli.secuences SET offset_ ='".$offset."' WHERE type = 'getItemsMa';");
/*----------sentencia que me trae los mpid a actualizar------------*/
$secuence_update->execute();
$items_research = $conn->prepare("select mpid,bolborrado from meli.items where bolborrado=0 and mpid in
('MCO445753860',
'MCO448632130',
'MCO451428266',
'MCO456660778',
'MCO456660856',
'MCO445783518',
'MCO446068477',
'MCO446272398',
'MCO446275182',
'MCO446349227',
'MCO446352183',
'MCO446667406',
'MCO446774783',
'MCO447034625',
'MCO447034617',
'MCO447034601',
'MCO447034626',
'MCO447384070',
'MCO447384064',
'MCO447384059',
'MCO447384132',
'MCO447384129',
'MCO447384128',
'MCO447384127',
'MCO447384125',
'MCO447384175',
'MCO447384185',
'MCO447384183',
'MCO447384182',
'MCO447384176',
'MCO450649838',
'MCO451040230',
'MCO451308537',
'MCO451331701',
'MCO453838417',
'MCO453845943',
'MCO454010772',
'MCO454040781',
'MCO454059937',
'MCO454770398',
'MCO454770399',
'MCO454770401',
'MCO454770404',
'MCO454770402',
'MCO454770405',
'MCO454770412',
'MCO454770407',
'MCO454770408',
'MCO454770416',
'MCO454770410',
'MCO454770411',
'MCO454770418',
'MCO454770428',
'MCO454770427',
'MCO454770430',
'MCO454770429',
'MCO454770431',
'MCO454770432',
'MCO454770433',
'MCO454770420',
'MCO454770437',
'MCO454770442',
'MCO454770434',
'MCO454770440',
'MCO454770441',
'MCO454770435',
'MCO454770445',
'MCO454770446',
'MCO454770453',
'MCO454770452',
'MCO454770455',
'MCO454770454',
'MCO454770450',
'MCO454770468',
'MCO454770467',
'MCO454770465',
'MCO454770464',
'MCO454770472',
'MCO454770474',
'MCO454770476',
'MCO454770469',
'MCO454770459',
'MCO454770470',
'MCO454770482',
'MCO454770479',
'MCO454770460',
'MCO454770481',
'MCO454770487',
'MCO454770461',
'MCO454770488',
'MCO454770485',
'MCO454770484',
'MCO454770489',
'MCO454770497',
'MCO454770494',
'MCO454770491',
'MCO454770502',
'MCO454770503',
'MCO454770499',
'MCO454770516',
'MCO454770523',
'MCO454770533',
'MCO454770534',
'MCO454770528',
'MCO454770529',
'MCO454770531',
'MCO454770521',
'MCO454770542',
'MCO454770539',
'MCO454770538',
'MCO454770544',
'MCO454770541',
'MCO454770535',
'MCO454770549',
'MCO454770545',
'MCO454770551',
'MCO454770558',
'MCO454770557',
'MCO454770559',
'MCO454770562',
'MCO454770561',
'MCO454770556',
'MCO454770563',
'MCO454770565',
'MCO454770564',
'MCO454770577',
'MCO454770578',
'MCO454770573',
'MCO454770579',
'MCO454770580',
'MCO454770574',
'MCO454770571',
'MCO454770583',
'MCO454770582',
'MCO454770581',
'MCO454770585',
'MCO454770589',
'MCO454770588',
'MCO454770593',
'MCO454770590',
'MCO454770603',
'MCO454770594',
'MCO454770595',
'MCO454770604',
'MCO454770601',
'MCO454770596',
'MCO454770607',
'MCO454770611',
'MCO454770610',
'MCO454770605',
'MCO454770617',
'MCO454770614',
'MCO454770616',
'MCO454770622',
'MCO454770619',
'MCO454770620',
'MCO454770623',
'MCO454770625',
'MCO454770624',
'MCO456327878',
'MCO453843763',
'MCO455585515',
'MCO456327856',
'MCO456327868',
'MCO456327878',
'MCO456327865',
'MCO456327883',
'MCO456327889',
'MCO456327902',
'MCO456327912',
'MCO456327953',
'MCO456327954',
'MCO456327977',
'MCO456327981',
'MCO456327989',
'MCO456327975',
'MCO456327993',
'MCO456327999',
'MCO456328001',
'MCO456328003',
'MCO456328017',
'MCO456328028',
'MCO456328026',
'MCO456328043',
'MCO456328045',
'MCO456328047',
'MCO456328040',
'MCO456328060',
'MCO456328078',
'MCO456328082',
'MCO456328086',
'MCO456328103',
'MCO456328111',
'MCO456328127',
'MCO456660776',
'MCO456660783',
'MCO456660794',
'MCO456660798',
'MCO456660800',
'MCO456660818',
'MCO456660820',
'MCO456660843',
'MCO456660831',
'MCO456660848',
'MCO456660826',
'MCO456660861',
'MCO456660859',
'MCO456660872',
'MCO456660876',
'MCO456660871',
'MCO456660890',
'MCO456660909',
'MCO456660905',
'MCO456660906',
'MCO456660924',
'MCO456660937',
'MCO456660929',
'MCO456660941',
'MCO456660934',
'MCO456660972',
'MCO456660970',
'MCO456660969');");


$items_research->execute();
$items_research = $items_research->fetchAll();
$items_manager  = new items($application[0]['access_token']);

#$conn->beginTransaction();
#Custom update data
#search by mpid database
echo "Inicio Ma -".date("Y-m-d H:i:s")."\n";
/*---------repetir por cada mpid encontrado--------------*/
foreach ($items_research as $items) {
	$mpid         = $items['mpid'];
	$detail_items = $items_manager->show($mpid);

	/*---------hacer si existe algun estatus-----------*/
	if (isset($detail_items[0]->status)) {
		$status = $detail_items[0]->status;

		/*---------hacer si existe seller valido asociado-----------*/
		if (isset($detail_items[0]->seller_custom_field) && $items['bolborrado'] <> 1) {

			echo $i++ ."-".$items['mpid']." - ".$detail_items[0]->seller_custom_field."\n";

		} else {

			echo $i++ ."-".$items['mpid']."- sku no valido\n";
			
		}
	/*---------se elimina el mpid que no existe-----------*/		
	} else {
		echo $i++ ."-".$mpid."- mpid no found\n";
	}
	
}
#$conn->commit();
$conn->close_con();
echo "Fin -".date("Y-m-d H:i:s")."\n";