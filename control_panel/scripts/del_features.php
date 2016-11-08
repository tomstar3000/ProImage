<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
$items = array();
$items = $_POST['Features_items'];
function delete_vals($value){
	global $cp_connection;
	$del= "DELETE FROM `prod_features` WHERE `feat_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `prod_features`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
}
function find_children_3($value){
	global $cp_connection;
	
	//mysql_select_db($database_cp_connection, $cp_connection);
	$query_find_children = "SELECT `feat_id` FROM `prod_features` WHERE `feat_part_id` = '$value'";
	$find_children = mysql_query($query_find_children, $cp_connection) or die(mysql_error());
	$row_find_children = mysql_fetch_assoc($find_children);
	$totalRows_find_children = mysql_num_rows($find_children);
	
	if($totalRows_find_children != 0){
		do{
			find_children_3($row_find_children['feat_id']);
		} while ($row_find_children = mysql_fetch_assoc($find_children));
	}
	delete_vals($value);
	mysql_free_result($find_children);
}
if(count($items)>0){
	foreach ($items as $key => $value){
		find_children_3($value);
	}
}
?>