<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_format_date.php';
$CId = $path[2];
$CName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$CCode = (isset($_POST['Code'])) ? clean_variable($_POST['Code'],true) : '';
$CPercent = (isset($_POST['Percent'])) ? clean_variable($_POST['Percent'],true) : '';
$CPrice = (isset($_POST['Price'])) ? clean_variable($_POST['Price'],true) : '';
$CDiscon = (isset($_POST['Discontinue'])) ? clean_variable($_POST['Discontinue']) : '';
$CUses = (isset($_POST['Number_of_Uses'])) ? clean_variable($_POST['Number_of_Uses']) : '0';
$CRoll = (isset($_POST['Rolling_Credit'])) ? clean_variable($_POST['Rolling_Credit']) : 'n';
$Error = false;
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	//mm/dd/yyyy
	//0123456789
	$SDiscon = date("Y-m-d H:i:s",mktime(0,0,0,substr($CDiscon,0,2),substr($CDiscon,3,2),substr($CDiscon,6,4)));
	if($cont == "add"){
		$query_get_info = "SELECT COUNT(`disc_id`) AS `count_code` FROM `prod_discount_codes` WHERE `disc_code` = '$CCode' AND `cust_id` = '$CustId' AND `disc_use` = 'y'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		if($row_get_info['count_code'] == 0){		
			$add = "INSERT INTO `prod_discount_codes` (`cust_id`, `disc_name`,`disc_code`,`disc_percent`,`disc_exp`,`disc_price`,`disc_num_uses`, `disc_roll_over`) VALUES ('$CustId','$CName','$CCode','$CPercent','$SDiscon','$CPrice','$CUses','$CRoll');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		} else {
			$Error = "That code has already been used";
		}
	} else {
		$upd = "UPDATE `prod_discount_codes` SET `disc_name` = '$CName',`disc_code` = '$CCode',`disc_percent` ='$CPercent',`disc_exp` = '$SDiscon',`disc_price` = '$CPrice',`disc_num_uses` = '$CUses', `disc_roll_over` = '$CRoll' WHERE `disc_id` = '$CId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());		
	}
	if(!$Error)$cont = "view";
} else {
	if($cont != "add"){		
		$query_get_info = "SELECT * FROM `prod_discount_codes` WHERE `disc_id` = '$CId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$CName = $row_get_info['disc_name'];
		$CCode = $row_get_info['disc_code'];
		$CPercent = $row_get_info['disc_percent'];
		$CPrice = $row_get_info['disc_price'];
		$CDiscon = format_date($row_get_info['disc_exp'],"Dash",false,true,false);
		$CUses = $row_get_info['disc_num_uses'];
		$CRoll = $row_get_info['disc_roll_over'];
		
		mysql_free_result($get_info);
	}
}

?>