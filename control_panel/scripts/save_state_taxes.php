<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$STId = $path[2];
$TState = (isset($_POST['State'])) ? clean_variable($_POST['State'],true) : '';
$TPercent = (isset($_POST['Percent'])) ? clean_variable($_POST['Percent'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$add = "INSERT INTO `billship_tax_states` (`tax_state`,`tax_percent`) VALUES ('$TState','$TPercent');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	} else {
		$upd = "UPDATE `billship_tax_states` SET `tax_state` = '$TState',`tax_percent` = '$TPercent' WHERE `tax_id` = '$STId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `billship_tax_states` WHERE `tax_id` = '$STId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$TState = $row_get_info['tax_state'];
		$TPercent = $row_get_info['tax_percent'];
		
		mysql_free_result($get_info);
	}
}

?>