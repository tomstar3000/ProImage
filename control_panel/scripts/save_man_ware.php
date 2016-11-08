<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$WId = $path[4];
$ManId = $path[2];
$WAdd = (isset($_POST['Address'])) ? clean_variable($_POST['Address'],true) : '';
$WAdd2 = (isset($_POST['Address_2'])) ? clean_variable($_POST['Address_2'],true) : '';
$WSuite = (isset($_POST['Suite/Apt'])) ? clean_variable($_POST['Suite/Apt'],true) : '';
$WCity = (isset($_POST['City'])) ? clean_variable($_POST['City'],true) : '';
$WState = (isset($_POST['State'])) ? clean_variable($_POST['State'],true) : '';
$WZip = (isset($_POST['Zip'])) ? clean_variable($_POST['Zip'],true) : '';
$WCount = (isset($_POST['Country'])) ? clean_variable($_POST['Country'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$add = "INSERT INTO `sellers_wharehouse` (`sell_id`,`sell_whare_add`,`sell_whare_add2`,`sell_whare_suite`,`sell_whare_city`,`sell_whare_state`,`sell_whare_zip`,`sell_whare_country`) VALUES ('$ManId','$WAdd','$WAdd2','$WSuite','$WCity','$WState','$WZip','$WCount');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	} else {
		$upd = "UPDATE `sellers_wharehouse` SET `sell_id` = '$ManId', `sell_whare_add` = '$WAdd',`sell_whare_add2` = '$WAdd2',`sell_whare_suite` = '$WSuite',`sell_whare_city` = '$WCity',`sell_whare_state` = '$WState',`sell_whare_zip` = '$WZip',`sell_whare_country` = '$WCount' WHERE `sell_whare_id` = '$WId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `sellers_wharehouse` WHERE `sell_whare_id` = '$WId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$ManId = $row_get_info['sell_id'];
		$WAdd = $row_get_info['sell_whare_add'];
		$WAdd2 = $row_get_info['sell_whare_add_2'];
		$WSuite = $row_get_info['sell_whare_suite_apt'];
		$WCity = $row_get_info['sell_whare_city'];
		$WState = $row_get_info['sell_whare_state'];
		$WZip = $row_get_info['sell_whare_zip'];
		$WCount = $row_get_info['sell_whare_country'];
		
		mysql_free_result($get_info);
	}
}
?>