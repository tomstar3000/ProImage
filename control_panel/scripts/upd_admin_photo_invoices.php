<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';

require_once($r_path.'scripts/fnct_clean_entry.php');
$items = array();
$items = $_POST['Invoices_items'];
$qtys = $_POST['Quality'];
$print = $_POST['Printer'];
$ship = $_POST['Shipper'];
function delete_vals($value, $qty, $print, $ship){
	global $cp_connection, $path;
	if($path[1] == "Open"){
		$upd = "UPDATE `orders_invoice` SET `invoice_pers_quality` = '$qty',`invoice_pers_print` = '$print' WHERE `invoice_id` = '$value'";
	} else if ($path[1] == "Ship"){
		$upd = "UPDATE `orders_invoice` SET `invoice_pers_quality` = '$qty',`invoice_pers_ship` = '$ship' WHERE `invoice_id` = '$value'";
	}
	$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `orders_invoice`";
	//$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
}
if(count($items)>0){
	foreach ($items as $key => $value){
		delete_vals($value, clean_variable($qtys[$key]), clean_variable($print[$key]), clean_variable($ship[$key]));
	}
}
?>