<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
if($path[3] == "Proj"){
	$AId = $path[6];
	$Proj_Id = $path[4];
} else {
	$AId = $path[4];
	$Proj_Id = $path[2];
}
$AName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$APrice = (isset($_POST['Price'])) ? clean_variable($_POST['Price'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$add = "INSERT INTO `proj_assets` (`proj_id`,`proj_asset_name`,`proj_asset_price`,`proj_asset_date`,`proj_asset_remove`) VALUES ('$Proj_Id','$AName','$APrice',NOW(),'n');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	} else {
		$upd = "UPDATE `proj_assets` SET `proj_id` = '$Proj_Id',`proj_asset_name` = '$AName',`proj_asset_price` = '$APrice' WHERE `proj_asset_id` = '$AId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `proj_assets` WHERE `proj_asset_id` = '$AId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$AName = $row_get_info['proj_asset_name'];
		$APrice = $row_get_info['proj_asset_price'];
		
		mysql_free_result($get_info);
	}
}
?>