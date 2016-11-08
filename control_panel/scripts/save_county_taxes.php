<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$CTId = $path[2];
$TCounty = (isset($_POST['Zip_Code'])) ? clean_variable($_POST['Zip_Code'],true) : '';
$TPercent = (isset($_POST['Percent'])) ? clean_variable($_POST['Percent'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$add = "INSERT INTO `billship_tax_county` (`tax_count_zip`,`tax_count_percent`) VALUES ('$TCounty','$TPercent');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	} else {
		$upd = "UPDATE `billship_tax_county` SET `tax_count_zip` = '$TCounty',`tax_count_percent` = '$TPercent' WHERE `tax_count_id` = '$CTId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){	
		$query_get_info = "SELECT * FROM `billship_tax_county` WHERE `tax_count_id` = '$CTId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$TCounty = $row_get_info['tax_count_zip'];
		$TPercent = $row_get_info['tax_count_percent'];
		
		mysql_free_result($get_info);
	}
}
?>