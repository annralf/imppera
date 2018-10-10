<?php
include "/var/www/html/enkargo/config/googleTranslate.php";
include "/var/www/html/enkargo/config/meli_bench.php";
include "/var/www/html/enkargo/services/aws_update.php";
$conn = new Connect();
$action = $_POST['action'];
$bench = new Benchmark(2);


switch ($action) {
	case 'get_sellers_detail':
	$sql_oficial = "SELECT id, nick_name, points, level_id, transactions_completed, total_stock, permalink  FROM meli.bench_sellers  WHERE is_oficial = 'true' order by transactions_completed desc";
	$sql_no_oficial = "SELECT id, nick_name, points, level_id, transactions_completed, total_stock, permalink FROM meli.bench_sellers  WHERE is_oficial = 'false' order by transactions_completed desc";
	$sql_top_seller = "SELECT id, nick_name, points, level_id, transactions_completed, total_stock, permalink FROM meli.bench_sellers   order by transactions_completed desc limit 10";
	$result_top_seller = pg_query($sql_top_seller);
	$result_oficial = pg_query($sql_oficial);
	$result_no_oficial = pg_query($sql_no_oficial);
	$table_top_seller = "";
	$table_oficial = "";
	$table_no_oficial = "";
	$counter_oficial = 1;
	$counter_no_oficial = 1;
	$color_percent = 60;
	while ($top_seller = pg_fetch_object($result_top_seller)) {
		$table_top_seller .= "<tr style='background-color: hsl(210, 45%, $color_percent%); cursor:pointer;' title='Ver detalle del vendedor' onclick='set_seller_session_id($top_seller->id)'>";
		$table_top_seller .= "<td style='width: 90px; word-wrap: break-word;'>".$counter_oficial."</td>";
		$table_top_seller .= "<td style='width: 90px; word-wrap: break-word;'>".$top_seller->nick_name."</td>";
		$table_top_seller .= "<td style='width: 90px; word-wrap: break-word;'>".number_format($top_seller->points)."</td>";
		$table_top_seller .= "<td style='width: 90px; word-wrap: break-word;'>".$top_seller->level_id."</td>";
		$table_top_seller .= "<td style='width: 90px; word-wrap: break-word;'>".number_format($top_seller->transactions_completed)."</td>";
		$table_top_seller .= "<td style='width: 90px; word-wrap: break-word;'>".number_format($top_seller->total_stock)."</td>";
		$table_top_seller .= "<td style='width: 90px; word-wrap: break-word;'><a class='btn' title='Ir a Perfil de la Tienda' href = '".$top_seller->permalink."' target='_blank'><i class='fa fa-eye' style='font-size:20px;'></i></a></td>";
		$table_top_seller .= "</tr>";
		$color_percent += 3.8;
		$counter_oficial++;
	}
	while ($seller_oficial = pg_fetch_object($result_oficial)) {
		$table_oficial .= "<tr style='cursor:pointer;' title='Ver detalle del vendedor' onclick='set_seller_session_id($seller_oficial->id)'>";
		$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".$seller_oficial->nick_name."</td>";
		$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".number_format($seller_oficial->points)."</td>";
		$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".$seller_oficial->level_id."</td>";
		$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".number_format($seller_oficial->transactions_completed)."</td>";
		$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".number_format($seller_oficial->total_stock)."</td>";
		$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'><a class='btn' title='Ir a Perfil de la Tienda' href = '".$seller_oficial->permalink."' target='_blank'><i class='fa fa-eye' style='font-size:20px;'></i></a></td>";
		$table_oficial .= "</tr>";
	}
	while ($seller_no_oficial = pg_fetch_object($result_no_oficial)) {
		$table_no_oficial .= "<tr style='cursor:pointer;' title='Ver detalle del vendedor' onclick='set_seller_session_id($seller_no_oficial->id)'>";
		$table_no_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".$seller_no_oficial->nick_name."</td>";
		$table_no_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".number_format($seller_no_oficial->points)."</td>";
		$table_no_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".$seller_no_oficial->level_id."</td>";
		$table_no_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".number_format($seller_no_oficial->transactions_completed)."</td>";
		$table_no_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".number_format($seller_no_oficial->total_stock)."</td>";
		$table_no_oficial .= "<td style='width: 90px; word-wrap: break-word;'><a class='btn' title='Ir a Perfil de la Tienda' href = '".$seller_no_oficial->permalink."' target='_blank'><i class='fa fa-eye' style='font-size:20px;'></i></a></td>";
		$table_no_oficial .= "</tr>";
		$counter_no_oficial++;
	}
	echo json_encode(array('seller_oficial' => $table_oficial, 'seller_no_oficial' => $table_no_oficial, 'top_seller' => $table_top_seller));
	break;	
	case 'get_seller_detail':
	$seller_id = $_POST['seller_id'];
	$sql_seller_info = "SELECT nick_name, TO_CHAR(transactions_completed,'FM999,999,999') AS transactions_completed, TO_CHAR( total_stock,'FM999,999,999') AS total_stock FROM meli.bench_sellers WHERE id = '$seller_id'";
	$seller_info = pg_fetch_object(pg_query($sql_seller_info));
	$sql_seller_items = "SELECT * FROM  meli.bench_shops_items WHERE shop = '$seller_id' ORDER BY sale_amount DESC";
	$seller_items_result = pg_query($sql_seller_items);
	$seller_items_table = "";
	$sql_seller_sales_detail = "select sum(visits) as visitas,sum(amount) as ventas, to_char(sales_date,'Day') as dia , to_char(sales_date,'DD') as dia_n, to_char(sales_date,'MM') as mes, to_char(sales_date,'YYYY') as anio from meli.bench_sales_day where seller = $seller_id group by dia, dia_n, mes, anio order by mes, dia_n asc;";
	$seller_sales_detail_result = pg_query($sql_seller_sales_detail);
	$sales_seller_info = "";
	$sales_seller_detail = "";
	$month_number = array('01','02','03','04','05','06','07','08','09','10','11','12');
	$month_name = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	$day_english = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
	$day_spanish = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
	$year = 0;
	$month = 1;
	$main_detail = array('year' => array(), 'month' => array(), 'days'=> array());
	while ($sales = pg_fetch_object($seller_sales_detail_result)) {
		if ($year !== $sales->anio) {
			$year = $sales->anio;
			array_push($main_detail['year'], $year);		
		}
		if ($month !== $sales->mes) {
			$month = $sales->mes;
			$month_translation = str_replace($month_number, $month_name, $month);
			array_push($main_detail['month'], array('month' => $month_translation, 'year' => $sales->anio));		
		}
		$day_translation = trim(str_replace($day_english, $day_spanish, $sales->dia));		
		$date = "$day_translation $sales->dia_n";
		array_push($main_detail['days'], array('visits' =>$sales->visitas, 'sales' => $sales->ventas, 'day'=> $date, 'month' => $month_translation));
	}

	foreach ($main_detail['year'] as $y) {
		$sales_seller_info .= "<li class='dropdown'>";
		$sales_seller_info .= "<a class='dropdown-toggle' data-toggle='dropdown'>$year<span class='caret'></span></a>";
		$sales_seller_info .= "<ul class='dropdown-menu'>";
		foreach ($main_detail['month'] as $m) {
			if ($m['year'] == $year ) {
				$mo = $m['month'];
				$total_sales = 0;
				$total_visits = 0;
				$sales_seller_info .= "<li><a data-toggle='tab' href='#$mo'>$mo</a></li>";		
				$sales_seller_detail .= "<div id='$mo' class='tab-pane'>";
				$sales_seller_detail .= "<table id='rankin_month_$mo' class='table table-striped table-bordered' width='100%'>";
				$sales_seller_detail .= "<thead>";
				$sales_seller_detail .= "<tr>";
				$sales_seller_detail .= "<th>Fecha</th>";
				$sales_seller_detail .= "<th>Ventas</th>";
				$sales_seller_detail .= "<th>Visitas</th>";
				$sales_seller_detail .= "</tr>";
				$sales_seller_detail .= "</thead>";
				$sales_seller_detail .= "<tbody>";
				foreach ($main_detail['days'] as $d) {
					if ($d['month'] == $m['month']) {
						$dia = $d['day'];
						$visitas = number_format($d['visits']);
						$ventas = number_format($d['sales']);
						$total_sales += $d['sales'];
						$total_visits += $d['visits'];
						$sales_seller_detail .= "<tr>";
						$sales_seller_detail .= "<td style='width: 90px; word-wrap: break-word;'>$dia</td>";
						$sales_seller_detail .= "<td style='width: 90px; word-wrap: break-word;'>$ventas</td>";
						$sales_seller_detail .= "<td style='width: 90px; word-wrap: break-word;'>$visitas</td>";
						$sales_seller_detail .= "</tr>";
					}
				}
				$sales_seller_detail .= "<tr>";
				$total_visits = number_format($total_visits);
				$total_sales = number_format($total_sales);
				$sales_seller_detail .= "<td style='width: 90px; word-wrap: break-word;'><b>TOTAL</b></td>";
				$sales_seller_detail .= "<td style='width: 90px; word-wrap: break-word;'><b>$total_sales</b></td>";
				$sales_seller_detail .= "<td style='width: 90px; word-wrap: break-word;'><b>$total_visits</b></td>";
				$sales_seller_detail .= "</tr>";
				$sales_seller_detail .= "</tbody>";
				$sales_seller_detail .= "</table>";
				$sales_seller_detail .= "</div>";
			}
		}
		$sales_seller_info .= "</ul>";
		$sales_seller_info .= "</li>";
	}

	while ($item = pg_fetch_object($seller_items_result)) {
		$price = number_format($item->price);
		$amount = number_format($item->sale_amount);
		$visits = number_format($item->visits);
		$stock = number_format($item->stock);
		$title = htmlspecialchars_decode($item->title);
		$seller_items_table .= "<tr>";
		$seller_items_table .= "<td style='width: 90px; word-wrap: break-word;'><img style='height: 20vh;' src='$item->thumbnail'></td>";
		$seller_items_table .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$item->mpid</td>";
		$seller_items_table .= "<td style='width: 90px; word-wrap: break-word;'>$title</td>";
		$seller_items_table .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$price COP</td>";
		$seller_items_table .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$amount</td>";
		$seller_items_table .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$visits</td>";
		$seller_items_table .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$stock</td>";
		$seller_items_table .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>-</td>";
		$seller_items_table .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>-</td>";
		$seller_items_table .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>";
		if ($item->is_local) {
			$seller_items_table .= "<a href=' title='Publicado en Tiendas locales'>";
			$seller_items_table .= "<p style='color: white; background-color: #49ec49; text-align: center; font-size: 12px;'>Publicado</p>";
			$seller_items_table .= "</a>";
		}else{
			$seller_items_table .= "<a href=' title='No se encuentra en las publicaciones locales'>";
			$seller_items_table .= "<p style='color: white; background-color: #f35858; text-align: center; font-size: 12px;'>No Publicado</p>";
			$seller_items_table .= "</a>";
		}
		$seller_items_table .= "</td>";
		$seller_items_table .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>";
		if ($item->is_aws) {
			$seller_items_table .= "<a href=' title='Disponible en Amazon'>";
			$seller_items_table .= "<p style='color: white; background-color: #f79530; text-align: center; font-size: 12px;'>Disponible</p>";
			$seller_items_table .= "</a>";				
		}else{
			$seller_items_table .= "<a href=' title='No se encuentra artículo en Amazon'>";
			$seller_items_table .= "<p style='color: white; background-color: #f35858; text-align: center; font-size: 12px;'>No Disponible</p>";
			$seller_items_table .= "</a>";
		}
		$seller_items_table .= "</td>";
		$seller_items_table .= "<td style='width: 100px;word-wrap: break-word; padding-top: 8vh;text-align: center;'>";
		$seller_items_table .= "<a href='' title='Descargar y publicar en portafolio local' style='font-size: 24px; margin-right: 10px; color: #73cbec;'><i class='fa fa-download' aria-hidden='true'></i></a>";
		$seller_items_table .="<a href='$item->permalink' title='Ver publicación en Mercado Libre' style='font-size: 24px; color: #49ec49;'><i class='fa fa-eye' aria-hidden='true'></i></a>";
		$seller_items_table .= "</td>";
		$seller_items_table .= "</tr>";
	}
	echo json_encode(array('seller_info' => $seller_info, 'seller_sales_header' => $sales_seller_info, 'seller_sales_body' => $sales_seller_detail, 'seller_top_items' => $seller_items_table));
	break;
	case 'get_local_items':
	$sql = "SELECT  * FROM meli.comparation_local_items WHERE nick_name IS NOT NULL";
	$query_sql = pg_query($sql);
	$items_qb = "";
	$items_mx = "";
	while ($item = pg_fetch_object($query_sql)) {
		$table_structure = "";
		$table_structure .= "<tr>";
		$table_structure .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'><img style='height: 20vh;' src='$item->thumbnail'></td>";
		$table_structure .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$item->mpid</td>";
		$table_structure .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$item->title</td>";
		$table_structure .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$item->local_price</td>";
		$table_structure .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$item->sales_amount</td>";
		$table_structure .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$item->sku</td>";
		$table_structure .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$item->aws_price</td>";
		$table_structure .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$item->nick_name</td>";
		$table_structure .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$item->seller_mpid</td>";
		$table_structure .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$item->sales_price</td>";
		$table_structure .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$item->seller_sales_amount</td>";
		$table_structure .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$item->sales_dolar_price</td>";
		$table_structure .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$item->difference_price</td>";
		$table_structure .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>";
		if ($item->is_high) {
			$table_structure .= "<a href=' title='El artículo publicado es mas costoso que la competencia'>";
			$table_structure .= "<p style='color: white; background-color: #f35858; text-align: center; font-size: 12px;'>Precio Alto</p>";
			$table_structure .= "</a>";
		}else{
			$table_structure .= "<a href=' title='No posee referencia de competencia'>";
			$table_structure .= "<p style='color: white; background-color: #f35858; text-align: center; font-size: 12px;'>Sin referencia</p>";
			$table_structure .= "</a>";
		}
		$table_structure .="</td>";
		$table_structure .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>";
		$table_structure .= "<a title='Actualizar Precio' style='font-size: 24px; margin-right: 10px; color: #73cbec;' onclick='get_update_price($item->id)'><i class='fa fa-refresh' aria-hidden='true'></i></a>";
		$table_structure .= "</td>";
		$table_structure .= "</tr>";
		switch ($item->local_store_id) {
			case '209935315':
			$items_qb .= $table_structure;
			break;

			case '192538642':
			$items_mx .= $table_structure;
			break;
		}
	}
	echo json_encode(array('items_qb' => $items_qb, 'items_mx' => $items_mx));
	break;
	case 'set_update_price':
	$item_id = $_POST['item_id'];
	$shop = $_POST['shop'];
	$price = $_POST['price'];
	$sql = "SELECT mpid FROM meli.items WHERE id = $item_id";
	$item = pg_fetch_object(pg_query($sql));
	$result = $bench->set_price($item->mpid, $price, $shop);
	echo json_encode(array('response' => $result));
	break;
	case 'get_top_sales':
	$sql_top_sales = "SELECT * FROM meli.top_sales ORDER BY key_word ASC;";
	$query_top_sales = pg_query($sql_top_sales);
	$year = 0;
	$month = 1;
	$month_number = array('01','02','03','04','05','06','07','08','09','10','11','12');
	$month_name = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	$main_detail = array();
	$key_word = "";
	$key_word_array = array();
	$i = 0;
	$j = 0;
	$array_items = array();
	while ($items = pg_fetch_object($query_top_sales)) {
		if ($key_word !== $items->key_word) {
			$key_word = $items->key_word;
			array_push($key_word_array, array('keyword'=> $key_word, 'year' => $items->year, 'id' => $j, 'id_category' => $items->trend_id,'total'=>$items->total));
			$j++;
		}
		array_push($array_items, $items);
	}
	foreach ($key_word_array as $key) {
		$items_content = "";
		$key_word_content = "";
		foreach ($array_items as $k) {
			if ($k->key_word == $key['keyword']) {
				$items_content .= "<tr>";
				#$items_content .= "<td style='width: 90px; word-wrap: break-word;'><img style='height: 20vh;' src='$k->thumbnail'></td>";
				$items_content .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$k->mpid</td>";
				$items_content .= "<td style='width: 90px; word-wrap: break-word;'>$k->title</td>";
				$items_content .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$k->price COP</td>";
				$items_content .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$k->sale_amount</td>";
				$items_content .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$k->visits</td>";
				$items_content .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$k->stock</td>";
				$items_content .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>-</td>";
				$items_content .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>-</td>";
				$items_content .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>";
				if ($k->is_local) {
					$items_content .= "<a href=' title='Publicado en Tiendas locales'>";
					$items_content .= "<p style='color: white; background-color: #49ec49; text-align: center; font-size: 12px;'>Publicado</p>";
					$items_content .= "</a>";
				}else{
					$items_content .= "<a href=' title='No se encuentra en las publicaciones locales'>";
					$items_content .= "<p style='color: white; background-color: #f35858; text-align: center; font-size: 12px;'>No Publicado</p>";
					$items_content .= "</a>";
				}
				$items_content .= "</td>";
				$items_content .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>";
				if ($item->is_aws) {
					$items_content .= "<a href=' title='Disponible en Amazon'>";
					$items_content .= "<p style='color: white; background-color: #f79530; text-align: center; font-size: 12px;'>Disponible</p>";
					$items_content .= "</a>";				
				}else{
					$items_content .= "<a href=' title='No se encuentra artículo en Amazon'>";
					$items_content .= "<p style='color: white; background-color: #f35858; text-align: center; font-size: 12px;'>No Disponible</p>";
					$items_content .= "</a>";
				}
				$items_content .= "</td>";
				$items_content .= "<td style='width: 100px;word-wrap: break-word; padding-top: 8vh;text-align: center;'>";
				$items_content .= "<a href='' title='Descargar y publicar en portafolio local' style='font-size: 24px; margin-right: 10px; color: #73cbec;'><i class='fa fa-download' aria-hidden='true'></i></a>";
				$items_content .="<a href='$k->permalink' title='Ver publicación en Mercado Libre' style='font-size: 24px; color: #49ec49;'><i class='fa fa-eye' aria-hidden='true'></i></a>";
				$items_content .= "</td>";
				$items_content .= "</tr>";
			}
		}
		$month_translation = str_replace($month_number, $month_name, $k->month);
		$year = $key['year'];
		$id = $key['id'];
		$title_table = strtoupper($key['keyword']);
		$id_category = "cat_".$key['id_category'];
		$total_sales = $key['total'];
		$key_word_content .= "<div class='row mauxi_panel' id='$id_category'>";
		$key_word_content .= "<div class='x_panel'>";
		$key_word_content .= "<div class='x_title collapse-link row' data-toggle='tooltip' data-placement='bottom' title='Ver listado de $title_table'>";
		$key_word_content .= "<div class='title col-md-6 col-sm-6 col-xs-6' style='text-align: right;'>";
		$key_word_content .= "<h2>$title_table $month_translation $year</h2>";
		$key_word_content .= "<div class='clearfix'></div>";
		$key_word_content .= "</div>";
		$key_word_content .= "<div class='title col-md-6 col-sm-6 col-xs-6' style='text-align: right;'>";
		$key_word_content .= "<ul style='list-style-type: none; display:inline-flex; font-size:24px;'>";
		$key_word_content .= "<li title='Total de items en esta categoría' style='margin-right:10px;'><a style='background-color:#ededed; border-radius:50%; padding:6px;'>$total_sales</a></li>";
		$key_word_content .= "<li title='Expandir Resultados' style='margin-right:10px;'><a onclick='view_table($id)'><i class='fa fa-caret-down' aria-hidden='true'></i></a></li>";
		$key_word_content .= "<li title='Ocultar Categoría' style='margin-right:10px;'><a onclick='hide_sale(\"$id_category\")'><i class='fa fa-eye-slash' aria-hidden='true'></i></a></li>";
		$key_word_content .= "<li title='Eliminar Categoría' style='margin-right:10px;'><a onclick='delete_sale(\"$id_category\")'><i class='fa fa-trash-o' aria-hidden='true'></i></a></li>";
		$key_word_content .= "</ul>";
		$key_word_content .= "<div class='clearfix'></div>";
		$key_word_content .= "</div>";
		$key_word_content .= "<div class='col-md-2 col-sm-2 col-xs-12' style='text-align: right;'>";
		$key_word_content .= "<img  class=' src=' style='width: 100px;'>";
		$key_word_content .= "</div>";
		$key_word_content .= "<div class='clearfix'></div>";
		$key_word_content .= "</div>";
		$key_word_content .= "<div class='x_content' id='id_$id' style='display:none;'>";
		$key_word_content .= "<p class='text-muted font-12 m-b-30'>";
		$key_word_content .= "</p>";
		$key_word_content .= "<table id='keyword_$i' class='table table-striped table-bordered' width='100%'>";
		$key_word_content .= "<thead>";
		$key_word_content .= "<tr>";
		#$key_word_content .= "<th>#</th>";
		$key_word_content .= "<th>MPID</th>";
		$key_word_content .= "<th>Título</th>";
		$key_word_content .= "<th>Precio en Mercado Libre</th>";
		$key_word_content .= "<th>Cantidad Vendida</th>";
		$key_word_content .= "<th>Cantidad de Visitas</th>";
		$key_word_content .= "<th>Total Stock</th>";
		$key_word_content .= "<th>Precio Amazon</th>";
		$key_word_content .= "<th>Valor del dólar</th>";
		$key_word_content .= "<th>Disponibilidad en tienda local</th>";
		$key_word_content .= "<th>Disponibilidad en tienda Amazon</th>";
		$key_word_content .= "<th>Acciones</th>";
		$key_word_content .= "</tr>";
		$key_word_content .= "</thead>";
		$key_word_content .= "<tbody>";
		$key_word_content .= $items_content;
		$key_word_content .= "</tbody>";
		$key_word_content .= "</table>";
		$key_word_content .= "<div class='clearfix'></div>";
		$key_word_content .= "</div>";
		$key_word_content .= "</div>";
		$key_word_content .= "</div>";
		$main_detail[$i]['name'] = "keyword_$i";
		$main_detail[$i]['content'] = $key_word_content;
		$i++;
	}
	echo json_encode($main_detail);
	break;
	case 'delete_top_sale_category':
	    $id_category = $_POST['id_category'];
	    echo json_encode(array('result' => $bench->delete_top_sale(1,$id_category)));
	break;
}

#set_sellers();
#set_info_sellers();
#set_seller_total_stock();
#set_top_items();
#set_items_visits();
#compare_items();
#set_items_details();

