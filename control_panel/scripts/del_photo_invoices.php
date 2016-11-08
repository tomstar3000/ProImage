<?php
include $r_path.'security.php';
$items = array();
$items = $_POST['Invoices_items'];
function delete_vals($value){
	global $cp_connection;
	
	$upd = "UPDATE `orders_invoice` SET `invoice_accepted` = 'p' WHERE `invoice_id` = '$value'";
	$upd_info = mysql_query($upd, $cp_connection) or die(mysql_error());
}
if(count($items)>0){
	foreach ($items as $k => $v) delete_vals($v);
}
?>