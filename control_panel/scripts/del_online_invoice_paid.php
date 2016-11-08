<?php
include $r_path.'security.php';
$items = array();
$items = $_POST['On_Line_Invoices_items'];
function delete_vals($value){
	global $cp_connection;
	
	$upd = "UPDATE `orders_invoice` SET `invoice_paid` = 'y' WHERE `invoice_enc` = '$value'";
	$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		
	$optimize = "OPTIMIZE TABLE `orders_invoice`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
}
if(count($items)>0){
	foreach ($items as $key => $value){
		delete_vals($value);
	}
}
?>