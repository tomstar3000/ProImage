<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$Prod_id = $path[2];
$CId = $path[4];
$CName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$CCode = (isset($_POST['Code'])) ? clean_variable($_POST['Code'],true) : '';
$CPercent = (isset($_POST['Percent'])) ? clean_variable($_POST['Percent'],true) : '';
$CPrice = (isset($_POST['Price'])) ? clean_variable($_POST['Price'],true) : '';
$CDiscon = (isset($_POST['Discontinue'])) ? clean_variable($_POST['Discontinue']) : '';
$CUses = (isset($_POST['Number_of_Uses'])) ? clean_variable($_POST['Number_of_Uses']) : '0';
$Error = false;
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$query_get_info = "SELECT COUNT(`disc_id`) AS `count_code` FROM `prod_discount_codes` WHERE `disc_code` = '$CCode'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		if($row_get_info['count_code'] == 0){		
			$add = "INSERT INTO `prod_discount_codes` (`prod_id`,`disc_name`,`disc_code`,`disc_percent`,`disc_exp`,`disc_price`,`disc_num_uses`) VALUES ('$Prod_id','$CName','$CCode','$CPercent','$CDiscon','$CPrice','$CUses');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		} else {
			$Error = "That code has already been used";
		}
	} else {
		$upd = "UPDATE `prod_discount_codes` SET `disc_name` = '$CName',`disc_code` = '$CCode',`disc_percent` ='$CPercent',`disc_exp` = '$CDiscon',`disc_price` = '$CPrice',`disc_num_uses` = '$CUses' WHERE `disc_id` = '$CId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());		
	}
	if(!$Error)$cont = "view";
} else {
	if($cont != "add"){		
		$query_get_info = "SELECT * FROM `prod_discount_codes` WHERE `disc_id` = '$CId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$CName = $row_get_info['disc_name'];
		$CCode = $row_get_info['disc_code'];
		$CPercent = $row_get_info['disc_percent'];
		$CPrice = $row_get_info['disc_price'];
		$CDiscon = $row_get_info['disc_exp'];
		$CUses = $row_get_info['disc_num_uses'];
		
		mysql_free_result($get_info);
	}
}

?>