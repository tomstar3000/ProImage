<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$IId = $path[2];

$query_get_info = "SELECT `invoice_enc` FROM `orders_invoice` WHERE `invoice_id` = '$IId'";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

$encnum = $row_get_info['invoice_enc'];

$upd = "UPDATE `orders_invoice` SET `invoice_accepted` = 'y', `invoice_accepted_date` = NOW() WHERE `invoice_id` = '$IId'";
$upd_info = mysql_query($upd, $cp_connection) or die(mysql_error());

mysql_free_result($get_info);
?>