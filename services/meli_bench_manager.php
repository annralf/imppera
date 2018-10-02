<?php
include "/var/www/html/enkargo/config/googleTranslate.php";
include "/var/www/html/enkargo/config/conex_manager.php";
include "/var/www/html/enkargo/services/aws_update.php";
$conn = new Connect();
$action = $_POST['action'];


switch ($action) {
	case 'get_sellers_detail':
		$sql_oficial = "SELECT nick_name, points, level_id, transactions_completed, total_stock, permalink  FROM meli.bench_sellers  WHERE is_oficial = 'true' order by transactions_completed desc";
		$sql_no_oficial = "SELECT nick_name, points, level_id, transactions_completed, total_stock, permalink FROM meli.bench_sellers  WHERE is_oficial = 'false' order by transactions_completed desc";
		$sql_top_seller = "SELECT nick_name, points, level_id, transactions_completed, total_stock, permalink FROM meli.bench_sellers   order by transactions_completed desc limit 10";
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
			$table_top_seller .= "<tr style='background-color: hsl(210, 45%, $color_percent%);'>";
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
			$table_oficial .= "<tr>";
			$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".$seller_oficial->nick_name."</td>";
			$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".number_format($seller_oficial->points)."</td>";
			$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".$seller_oficial->level_id."</td>";
			$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".number_format($seller_oficial->transactions_completed)."</td>";
			$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".number_format($seller_oficial->total_stock)."</td>";
			$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'><a class='btn' title='Ir a Perfil de la Tienda' href = '".$seller_oficial->permalink."' target='_blank'><i class='fa fa-eye' style='font-size:20px;'></i></a></td>";
			$table_oficial .= "</tr>";
		}
		while ($seller_no_oficial = pg_fetch_object($result_no_oficial)) {
			$table_no_oficial .= "<tr>";
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
		$sql_seller_info = "SELECT nick_name, transactions_completed, total_stock FROM meli.bench_sellers WHERE id = $seller_id";
		$seller_info = pg_fetch_object(pg_query($sql_seller_info));
		$sql_seller_items = "SELECT * FROM  meli.bench_shops_items WHERE shop = $seller_id";
		$seller_items_result = pg_query($sql_seller_items);
		$seller_items_table = "";
		$sql_seller_sales_detail = "select sum(visits) as visitas,sum(amount) as ventas, to_char(sales_date,'Day') as dia , to_char(sales_date,'DD') as dia_n, to_char(sales_date,'MM') as mes, to_char(sales_date,'YYYY') as anio from meli.bench_sales_day where seller = $seller_id group by dia, dia_n, mes, anio order by mes desc;";
		$seller_sales_detail_result = pg_query($sql_seller_sales_detail);
		$sales_seller_info = "";
		$year = 0;
		$month = 0;
		while ($sales = pg_fetch_object($seller_sales_detail_result)) {
			$year = $sales->anio;
			$sales_seller_info .= "<li class='dropdown'>";
			$sales_seller_info .= "<a class='dropdown-toggle' data-toggle='dropdown'>2018 <span class='caret'></span></a>";
		        $sales_seller_info .= "<ul class='dropdown-menu'>";
			$sales_seller_info .= "<li><a href='#lol'>Enero</a></li>";
			$sales_seller_info .= "<li><a href=''>Febrero</a></li>";
			$sales_seller_info .= "</ul>";
			$sales_seller_info .= "</li>";
		}
		while ($item = pg_fetch_object($seller_items_result)) {
			$seller_items_table .= "<tr>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word;'><img style='height: 20vh;' src='$item->thumbnail'></td>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$item->mpid</td>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word;'>$item->title</td>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$number_format($item->price) COP</td>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$number_format($item->sale_amount)</td>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$number_format($item->visits)</td>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>$number_format($item->stock)</td>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>-</td>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word; padding-top: 8vh;'>-</td";
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
			$seller_items_table .= "<a href="" title='Descargar y publicar en portafolio local' style='font-size: 24px; margin-right: 10px; color: #73cbec;'><i class='fa fa-download' aria-hidden='true'></i></a>";
                        $seller_items_table .="<a href=' title='Ver publicación en Mercado Libre' style='font-size: 24px; color: #49ec49;'><i class='fa fa-eye' aria-hidden='true'></i></a>";
                        $seller_items_table .= "</td>";
		       $seller_items_table .= "</tr>";
		}
		break;
}

#set_sellers();
#set_info_sellers();
#set_seller_total_stock();
#set_top_items();
#set_items_visits();
#compare_items();
#set_items_details();

