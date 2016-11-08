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
	$SId = $path[6];
	$Proj_Id = $path[4];
} else {
	$SId = $path[4];
	$Proj_Id = $path[2];
}
$SName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$SPrice = (isset($_POST['Price'])) ? clean_variable($_POST['Price'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$add = "INSERT INTO `proj_supplies` (`proj_id`,`proj_supp_name`,`proj_supp_price`,`proj_supp_date`,`proj_supp_remove`) VALUES ('$Proj_Id','$SName','$SPrice',NOW(),'n');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());	
	} else {
		$upd = "UPDATE `proj_supplies` SET `proj_id` = '$Proj_Id',`proj_supp_name` = '$SName',`proj_supp_price` = '$SPrice' WHERE `proj_supp_id` = '$SId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `proj_supplies` WHERE `proj_supp_id` = '$SId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
			
		$SName = $row_get_info['proj_supp_name'];
		$SPrice = $row_get_info['proj_supp_price'];
		
		mysql_free_result($get_info);
	}
}
?>