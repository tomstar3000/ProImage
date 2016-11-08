<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$CId = $path[4];
$ManId = $path[2];
$CFName = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : '';
$CMInt = (isset($_POST['Middle_Int'])) ? clean_variable($_POST['Middle_Int'],true) : '';
$CLName = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : '';
$Phone = (isset($_POST['Phone_Number'])) ? clean_variable($_POST['Phone_Number'],true) : '';
$P1 = (isset($_POST['P1'])) ? clean_variable($_POST['P1'],true) : '';
$P2 = (isset($_POST['P2'])) ? clean_variable($_POST['P2'],true) : '';
$P3 = (isset($_POST['P3'])) ? clean_variable($_POST['P3'],true) : '';
$Cell = (isset($_POST['Cell_Number'])) ? clean_variable($_POST['Cell_Number'],true) : '';
$C1 = (isset($_POST['C1'])) ? clean_variable($_POST['C1'],true) : '';
$C2 = (isset($_POST['C2'])) ? clean_variable($_POST['C2'],true) : '';
$C3 = (isset($_POST['C3'])) ? clean_variable($_POST['C3'],true) : '';
$Fax = (isset($_POST['Fax_Number'])) ? clean_variable($_POST['Fax_Number'],true) : '';
$F1 = (isset($_POST['F1'])) ? clean_variable($_POST['F1'],true) : '';
$F2 = (isset($_POST['F2'])) ? clean_variable($_POST['F2'],true) : '';
$F3 = (isset($_POST['F3'])) ? clean_variable($_POST['F3'],true) : '';
$CEmail = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$add = "INSERT INTO `sellers_contact` (`sell_id`,`sell_cont_fname`,`sell_cont_mint`,`sell_cont_lname`,`sell_cont_phone`,`sell_cont_cell`,`sell_cont_fax`,`sell_cont_email`) VALUES ('$ManId','$CFName','$CMInt','$CLName','$Phone','$Cell','$Fax','$CEmail');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	} else {
		$upd = "UPDATE `sellers_contact` SET `sell_id` = '$ManId',`sell_cont_fname` = '$CFName',`sell_cont_mint` = '$CMInt',`sell_cont_lname` = '$CLName',`sell_cont_phone` = '$Phone',`sell_cont_cell` = '$Cell',`sell_cont_fax` = '$Fax',`sell_cont_email` = '$CEmail' WHERE `sell_cont_id` = '$CId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());	
	}
	$cont = "view";
} else {
	if($cont != "add"){		
		$query_get_info = "SELECT * FROM `sellers_contact` WHERE `sell_cont_id` = '$CId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$ManId = $row_get_info['sell_id'];
		$CFName = $row_get_info['sell_cont_fname'];
		$CMInt = $row_get_info['sell_cont_mint'];
		$CLName = $row_get_info['sell_cont_lname'];
		$Phone = $row_get_info['sell_cont_phone'];
		$P1 = substr($Phone,0,3);
		$P2 = substr($Phone,3,3);
		$P3 = substr($Phone,6,4);
		$Cell = $row_get_info['sell_cont_cell'];
		$C1 = substr($Cell,0,3);
		$C2 = substr($Cell,3,3);
		$C3 = substr($Cell,6,4);
		$Fax = $row_get_info['sell_cont_fax'];
		$F1 = substr($Fax,0,3);
		$F2 = substr($Fax,3,3);
		$F3 = substr($Fax,6,4);
		$CEmail = $row_get_info['sell_cont_email'];
		
		mysql_free_result($get_info);
	}
}
?>