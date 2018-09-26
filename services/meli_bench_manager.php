<?php
include "/var/www/html/enkargo/config/googleTranslate.php";
include "/var/www/html/enkargo/config/conex_manager.php";
include "/var/www/html/enkargo/services/aws_update.php";
$conn = new Connect();
$action = "casa";

echo $action;

/*


switch ($action) {
	case 'get_sellers_detail':
		echo "aqui";
		$sql_oficial = "SELECT nick_name, points, level_id, transactions_completed, total_stock, permalink  FROM meli.bench_sellers  WHERE is_oficial = 'true'";
		$sql_no_oficial = "SELECT nick_name, points, level_id, transactions_completed, total_stock, permalink FROM meli.bench_sellers  WHERE is_oficial = 'false'";
		$result_oficial = pg_query($sql_oficial);
		$result_no_oficial = pg_query($sql_no_oficial);
		$table_oficial = "";
		$table_no_oficial = "";
		while ($seller_oficial = pg_fetch_object($result_oficial)) {
			$table_oficial .= "<tr>";
			$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".$seller_oficial->nick_name."</td>";
			$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".$seller_oficial->points."</td>";
			$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".$seller_oficial->level_id."</td>";
			$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".$seller_oficial->transactions_completed."</td>";
			$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".$seller_oficial->total_stock."</td>";
			$table_oficial .= "<td style='width: 90px; word-wrap: break-word;'><a class='btn' title='Ver ítem' href = '".$seller_oficial->permalink."' target='_blank'><i class='fa fa-eye' style='font-size:20px;'></i></a></td>";
			$table_oficial .= "</tr>";
		}
		while ($seller_no_oficial = pg_fetch_object($result_no_oficial)) {
			$table_no_oficial .= "<tr>";
			$table_no_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".$seller_no_oficial->nick_name."</td>";
			$table_no_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".$seller_no_oficial->points."</td>";
			$table_no_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".$seller_no_oficial->level_id."</td>";
			$table_no_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".$seller_no_oficial->transactions_completed."</td>";
			$table_no_oficial .= "<td style='width: 90px; word-wrap: break-word;'>".$seller_no_oficial->total_stock."</td>";
			$table_no_oficial .= "<td style='width: 90px; word-wrap: break-word;'><a class='btn' title='Ver ítem' href = '".$seller_no_oficial->permalink."' target='_blank'><i class='fa fa-eye' style='font-size:20px;'></i></a></td>";
			$table_no_oficial .= "</tr>";
		}
		echo json_encode(array('seller_oficial' => 'lol'));
		break;	
	default:
		# code...
		break;
}

*/
#set_sellers();
#set_info_sellers();
#set_seller_total_stock();
#set_top_items();
#set_items_visits();
#compare_items();
#set_items_details();

