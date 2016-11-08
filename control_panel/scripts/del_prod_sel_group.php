<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
$items = array();
$items = $_POST['Selection_Groups_items'];
function delete_vals($value){
	global $cp_connection;
	$query_get_ids = "SELECT `sel_id` FROM `prod_link_prod_sel_group` WHERE `link_prod_sel_group_id` = '$value'";
	$get_ids = mysql_query($query_get_ids, $cp_connection) or die(mysql_error());
	$row_get_ids = mysql_fetch_assoc($get_ids);
	
	$id = $row_get_ids['sel_id'];
	
	mysql_free_result($get_ids);
			
	$del= "DELETE FROM `prod_link_prod_sel` WHERE `sel_id` = '$id'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `prod_link_prod_sel`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$del= "DELETE FROM `prod_link_prod_sel_group` WHERE `link_prod_sel_group_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `prod_link_prod_sel_group`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
}
if(count($items)>0){
	foreach ($items as $key => $value){
		delete_vals($value);
	}
}
?>