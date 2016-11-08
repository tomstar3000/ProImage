<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$SId = $path[4];
$ManId = $path[2];
$SCareOf = (isset($_POST['Care_Of'])) ? clean_variable($_POST['Care_Of'],true) : '';
$SAdd = (isset($_POST['Address'])) ? clean_variable($_POST['Address'],true) : '';
$SAdd2 = (isset($_POST['Address_2'])) ? clean_variable($_POST['Address_2'],true) : '';
$SSuite = (isset($_POST['Suite/Apt'])) ? clean_variable($_POST['Suite/Apt'],true) : '';
$SCity = (isset($_POST['City'])) ? clean_variable($_POST['City'],true) : '';
$SState = (isset($_POST['State'])) ? $_POST['State'] : '';
$SZip = (isset($_POST['Zip'])) ? clean_variable($_POST['Zip'],true) : '';
$SCount = (isset($_POST['Country'])) ? clean_variable($_POST['Country'],true) : '';
$SDefault = (isset($_POST['Default'])) ? $_POST['Default'] : 'n';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$add = "INSERT INTO `sellers_shipping` (`sell_id`,`sell_care_of`,`sell_ship_add`,`sell_ship_add_2`,`sell_ship_suite_apt`,`sell_ship_city`,`sell_ship_state`,`sell_ship_zip`,`sell_ship_country`,`sell_ship_to`) VALUES ('$ManId','$SCareOf','$SAdd','$SAdd2','$SSuite','$SCity','$SState','$SZip','$SCount','$SDefault');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	} else {
		$upd = "UPDATE `sellers_shipping` SET `sell_id` = '$ManId',`sell_care_of` = '$SCareOf',`sell_ship_add` = '$SAdd',`sell_ship_add_2` = '$SAdd2',`sell_ship_suite_apt` = '$SSuite',`sell_ship_city` = '$SCity',`sell_ship_state` = '$SState',`sell_ship_zip` = '$SZip',`sell_ship_country` = '$SCount',`sell_ship_to` = '$SDefault' WHERE `sell_ship_id` = '$SId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `sellers_shipping` WHERE `sell_ship_id` = '$SId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$ManId = $row_get_info['sell_id'];
		$SCareOf = $row_get_info['sell_care_of'];
		$SAdd = $row_get_info['sell_ship_add'];
		$SAdd2 = $row_get_info['sell_ship_add_2'];
		$SSuite = $row_get_info['sell_ship_suite_apt'];
		$SCity = $row_get_info['sell_ship_city'];
		$SState = $row_get_info['sell_ship_state'];
		$SZip = $row_get_info['sell_ship_zip'];
		$SCount = $row_get_info['sell_ship_country'];
		$SDefault = $row_get_info['sell_ship_to'];
		
		mysql_free_result($get_info);
	}
}
?>