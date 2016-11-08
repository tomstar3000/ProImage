<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
$items = array();
$items = $_POST['Manufactures_items'];
function delete_vals($value){
	global $cp_connection;
	$del= "DELETE FROM `sellers` WHERE `sell_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$del= "DELETE FROM `sellers_billing` WHERE `sell_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$del= "DELETE FROM `sellers_contact` WHERE `sell_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$del= "DELETE FROM `sellers_shipping` WHERE `sell_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$del= "DELETE FROM `sellers_reps` WHERE `sell_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$del= "DELETE FROM `sellers_sales` WHERE `sell_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$del= "DELETE FROM `sellers_wharehouse` WHERE `sell_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `sellers`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `sellers_billing`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `sellers_contact`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `sellers_shipping`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `sellers_reps`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `sellers_sales`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `sellers_wharehouse`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
}
if(count($items)>0){
	foreach ($items as $key => $value){
		delete_vals($value);
	}
}
?>