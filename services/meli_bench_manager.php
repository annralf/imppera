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
		while ($item = pg_fetch_object($seller_items_result)) {
			$local_item = "NO";
			if ($item->is_local) {
				$local_item = "SI";
			}
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word;'>".$item->mpid."</td>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word;'>".$item->title."</td>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word;'>".$item->sale_amount."</td>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word;'>".$item->visits."</td>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word;'>No Disponible</td>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word;'>No Disponible</td>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word;'>No Disponible</td>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word;'>".$local_item."</td>";
			$seller_items_table .= "<td style='width: 90px; word-wrap: break-word;'>Funcionalidades</td>";
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

