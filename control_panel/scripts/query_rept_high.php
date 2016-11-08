<?php
include $r_path.'security.php';

$pathing = implode(",",$pathing);
$date = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d")-31, date("Y")));
$query_get_reports = "SELECT `cust_id`, `invoice_date` FROM `orders_invoice` WHERE `invoice_online` = 'y' AND `invoice_date` >= '$date' ORDER BY `invoice_date` DESC";
$get_reports = mysql_query($query_get_reports, $cp_connection) or die(mysql_error());
$row_get_reports = mysql_fetch_assoc($get_reports);
$totalRows_get_reports = mysql_num_rows($get_reports);

$records = array();
$headers = array();
$sortheaders = false;
$div_data = false;
$drop_downs = false;
$headers[0] = "Date";
$headers[1] = "Manufactures";
$headers[2] = "Customers";
$headers[3] = "Items";
$headers[4] = "Total";
$headers[5] = "Grand Total";
$is_date = 0;
do{
	if(($row_get_reports['invoice_date'] <= $first_date || $row_get_reports['invoice_date'] >= $last_date) || $is_date == 0){
		$is_date = substr($row_get_reports['invoice_date'],0 ,10);
		$first_date = $is_date." 00:00:00";
		$last_date = date("Y-m-d H:i:s", mktime(0,0,0,substr($is_date,5,2),substr($is_date,8,2)+1,substr($is_date,0,4)));
		$count = count($records);
		
		$query_get_items = "SELECT SUM(`invoice_prod_qty`) AS `total_items` FROM `orders_invoice` INNER JOIN `orders_invoice_prod` ON `orders_invoice_prod`.`invoice_id` = `orders_invoice`.`invoice_id` WHERE `invoice_online` = 'y' AND `invoice_date` >= '$first_date' AND `invoice_date` < '$last_date'";
		$get_items = mysql_query($query_get_items, $cp_connection) or die(mysql_error());
		$row_get_items = mysql_fetch_assoc($get_items);
		$totalRows_get_items = mysql_num_rows($get_items);
		
		$query_get_manufactures = "SELECT `prod_products`.`sell_id` FROM `orders_invoice` INNER JOIN `orders_invoice_prod` ON `orders_invoice_prod`.`invoice_id` = `orders_invoice`.`invoice_id` INNER JOIN `prod_products` ON `prod_products`.`prod_id` = `orders_invoice_prod`.`prod_id` WHERE `invoice_online` = 'y' AND `invoice_date` >= '$first_date' AND `invoice_date` < '$last_date' GROUP BY `sell_id`";
		$get_manufactures = mysql_query($query_get_manufactures, $cp_connection) or die(mysql_error());
		$row_get_manufactures = mysql_fetch_assoc($get_manufactures);
		$totalRows_get_manufactures = mysql_num_rows($get_manufactures);
		
		$query_get_customers = "SELECT `cust_id` FROM `orders_invoice` WHERE `invoice_online` = 'y' AND `invoice_date` >= '$first_date' AND `invoice_date` < '$last_date' GROUP BY `cust_id`";
		$get_customers = mysql_query($query_get_customers, $cp_connection) or die(mysql_error());
		$row_get_customers = mysql_fetch_assoc($get_customers);
		$totalRows_get_customers = mysql_num_rows($get_customers);
		
		$query_get_total = "SELECT SUM(`invoice_total`) AS `total_invoice`, SUM(`invoice_grand`) AS `grand_invoice` FROM `orders_invoice` WHERE `invoice_online` = 'y' AND `invoice_date` >= '$first_date' AND `invoice_date` < '$last_date'";
		$get_total = mysql_query($query_get_total, $cp_connection) or die(mysql_error());
		$row_get_total = mysql_fetch_assoc($get_total);
		$totalRows_get_total = mysql_num_rows($get_total);
		
		$records[$count][0] = false;
		$records[$count][1] = $is_date;
		$records[$count][2] = $is_date;
		$records[$count][3] = $totalRows_get_manufactures;
		$records[$count][4] = $totalRows_get_manufactures;
		$records[$count][5] = $row_get_items['total_items'];
		$records[$count][6] = "$".number_format($row_get_total['total_invoice'],2,".",",");
		$records[$count][7] = "$".number_format($row_get_total['grand_invoice'],2,".",",");
		
		mysql_free_result($get_items);
		mysql_free_result($get_manufactures);
		mysql_free_result($get_customers);
		mysql_free_result($get_total);
	}
	
} while ($row_get_reports = mysql_fetch_assoc($get_reports)); 

mysql_free_result($get_reports);

build_record_table('Hybrid_Reports', 'Hybrid Reports', '', '', false, '', '', '', $headers, $sortheaders, $records, $div_data, $drop_downs, $_SERVER['PHP_SELF']."?Path=".$pathing."&Sort=".$Sorting, 'Open', '', '', 0, 0, 0, '', '100%', '', '',$Rec_Style_1, $Rec_Style_2, $Rec_Style_3, $btn_add, $hdr_img, "Record_Drop_Down");
?>
</div>