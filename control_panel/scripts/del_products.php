<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
$items = array();
$items = $_POST['Products_items'];
function delete_vals($value){
	global $cp_connection;
	$del= "UPDATE `prod_products` SET `prod_use` = 'n' WHERE `prod_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$del= "DELETE FROM `prod_link_prod_att` WHERE `prod_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$del= "DELETE FROM `prod_link_prod_feat` WHERE `prod_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$del= "DELETE FROM `prod_link_prod_group` WHERE `prod_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$del= "DELETE FROM `prod_link_prod_rel` WHERE `prod_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$del= "DELETE FROM `prod_link_prod_sel` WHERE `prod_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$del= "DELETE FROM `prod_link_prod_spec` WHERE `prod_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$del= "DELETE FROM `prod_ratings` WHERE `prod_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$del= "DELETE FROM `prod_reviews` WHERE `prod_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `prod_products`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `prod_link_prod_att`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `prod_link_prod_feat`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `prod_link_prod_group`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `prod_link_prod_rel`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `prod_link_prod_sel`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `prod_link_prod_spec`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `prod_ratings`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `prod_reviews`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
}
if(count($items)>0){
	foreach ($items as $key => $value){
		delete_vals($value);
	}
}
?>