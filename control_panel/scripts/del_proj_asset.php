<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
$items = array();
$items = $_POST['Assets_items'];
function delete_vals($value){
	global $cp_connection;
	$query_get_remove = "SELECT `proj_asset_remove` FROM `proj_assets` WHERE `proj_asset_id` = '$value'";
	$get_remove = mysql_query($query_get_remove, $cp_connection) or die(mysql_error());
	$row_get_remove = mysql_fetch_assoc($get_remove);	
	
	$new_val = ($row_get_remove['proj_asset_remove'] == "y")?"n":"y";
	
	$upd = "UPDATE `proj_assets` SET `proj_asset_remove` = '$new_val' WHERE `proj_asset_id` = '$value'";
	$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	
	mysql_free_result($row_get_remove);
}
if(count($items)>0){
	foreach ($items as $key => $value){
		delete_vals($value);
	}
}
?>