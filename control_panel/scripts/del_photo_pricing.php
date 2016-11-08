<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
$items = array();
$items = $_POST['Pricing_items'];
function delete_vals($value){
	global $cp_connection;
	
	$upd = "UPDATE `photo_event_price` SET `photo_price_use` = 'n' WHERE `photo_event_price_id` = '$value'";
	$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `photo_event_price`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
}
if(count($items)>0){
	foreach ($items as $key => $value){
		delete_vals($value);
	}
}
?>