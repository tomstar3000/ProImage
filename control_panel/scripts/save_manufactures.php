<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_date_format_for_db.php';
$MId = $path[2];
$MName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$MAdd = (isset($_POST['Address'])) ? clean_variable($_POST['Address'],true) : '';
$MAdd2 = (isset($_POST['Address_2'])) ? clean_variable($_POST['Address_2'],true) : '';
$MSuite = (isset($_POST['Suite/Apt'])) ? clean_variable($_POST['Suite/Apt'],true) : '';
$MCity = (isset($_POST['City'])) ? clean_variable($_POST['City'],true) : '';
$MState = (isset($_POST['State'])) ? clean_variable($_POST['State'],true) : '';
$MZip = (isset($_POST['Zip'])) ? clean_variable($_POST['Zip'],true) : '';
$MCount = (isset($_POST['Country'])) ? clean_variable($_POST['Country'],true) : '';
$MPhone = (isset($_POST['Phone_Number'])) ? clean_variable($_POST['Phone_Number'],true) : '';
$P1 = (isset($_POST['P1'])) ? clean_variable($_POST['P1'],true) : '';
$P2 = (isset($_POST['P2'])) ? clean_variable($_POST['P2'],true) : '';
$P3 = (isset($_POST['P3'])) ? clean_variable($_POST['P3'],true) : '';
$MCell = (isset($_POST['Cell_Number'])) ? clean_variable($_POST['Cell_Number'],true) : '';
$C1 = (isset($_POST['C1'])) ? clean_variable($_POST['C1'],true) : '';
$C2 = (isset($_POST['C2'])) ? clean_variable($_POST['C2'],true) : '';
$C3 = (isset($_POST['C3'])) ? clean_variable($_POST['C3'],true) : '';
$MFax = (isset($_POST['Fax_Number'])) ? clean_variable($_POST['Fax_Number'],true) : '';
$F1 = (isset($_POST['F1'])) ? clean_variable($_POST['F1'],true) : '';
$F2 = (isset($_POST['F2'])) ? clean_variable($_POST['F2'],true) : '';
$F3 = (isset($_POST['F3'])) ? clean_variable($_POST['F3'],true) : '';
$MEMail = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';
$MWeb = (isset($_POST['Website'])) ? clean_variable($_POST['Website'],true) : '';
$MDisp = (isset($_POST['Display'])) ? clean_variable($_POST['Display'],true) : '';
$MAct = (isset($_POST['Active'])) ? clean_variable($_POST['Active'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$add = "INSERT INTO `sellers` (`sell_cname`,`sell_add`,`sell_add_2`,`sell_suite_apt`,`sell_city`,`sell_state`,`sell_zip`,`sell_country`,`sell_phone`,`sell_cell`,`sell_fax`,`sell_email`,`sell_website`,`sell_confid`,`sell_active`) VALUES ('$MName','$MAdd','$MAdd2','$MSuite','$MCity','$MState','$MZip','$MCount','$MPhone','$MCell','$MFax','$MEMail','$MWeb','$MDisp','$MAct');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());	
	} else {
		$upd = "UPDATE `sellers` SET `sell_cname` = '$MName',`sell_add` = '$MAdd',`sell_add_2` = '$MAdd2',`sell_suite_apt` = '$MSuite',`sell_city` = '$MCity',`sell_state` = '$MState',`sell_zip` = '$MZip',`sell_country` = '$MCount',`sell_phone` = '$MPhone',`sell_cell` = '$MCell',`sell_fax` = '$MFax',`sell_email` = '$MEMail',`sell_website` = '$MWeb',`sell_confid` = '$MDisp',`sell_active` = '$MAct' WHERE `sell_id` = '$MId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){	
		$query_get_info = "SELECT * FROM `sellers` WHERE `sell_id` = '$MId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$MName = $row_get_info['sell_cname'];
		$MAdd = $row_get_info['sell_add'];
		$MAdd2 = $row_get_info['sell_add_2'];
		$MSuite = $row_get_info['sell_suite_apt'];
		$MCity = $row_get_info['sell_city'];
		$MState = $row_get_info['sell_state'];
		$MZip = $row_get_info['sell_zip'];
		$MCount = $row_get_info['sell_country'];
		$MPhone = $row_get_info['sell_phone'];
		$P1 = substr($MPhone,0,3);
		$P2 = substr($MPhone,3,3);
		$P3 = substr($MPhone,6,4);
		$MCell = $row_get_info['sell_cell'];
		$C1 = substr($MCell,0,3);
		$C2 = substr($MCell,3,3);
		$C3 = substr($MCell,6,4);
		$MFax = $row_get_info['sell_fax'];
		$F1 = substr($MFax,0,3);
		$F2 = substr($MFax,3,3);
		$F3 = substr($MFax,6,4);
		$MEMail = $row_get_info['sell_email'];
		$MWeb = $row_get_info['sell_website'];
		$MDisp = $row_get_info['sell_confid'];
		$MAct = $row_get_info['sell_active'];
		
		mysql_free_result($get_info);
	}
}
?>