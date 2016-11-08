<?php

include $r_path.'security.php';
$items = array();
$items = $_POST['Discount_items'];
function delete_vals($value){
	global $cp_connection;
	$del= "DELETE FROM `prod_discount_codes` WHERE `disc_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `prod_discount_codes`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
}
if(count($items)>0){
	foreach ($items as $key => $value){
		delete_vals($value);
	}
}
?>