<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$RId = $path[4];
$ManId = $path[2];
$RFName = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : '';
$RMInt = (isset($_POST['Middle_Int'])) ? clean_variable($_POST['Middle_Int'],true) : '';
$RLName = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : '';
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
$REmail = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$add = "INSERT INTO `sellers_reps` (`sell_id`,`sell_rep_fname`,`sell_rep_mint`,`sell_rep_lname`,`sell_rep_phone`,`sell_rep_cell`,`sell_rep_fax`,`sell_rep_email`) VALUES ('$ManId','$RFName','$RMInt','$RLName','$Phone','$Cell','$Fax','$REmail');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	} else {
		$upd = "UPDATE `sellers_reps` SET `sell_id` = '$ManId',`sell_rep_fname` = '$RFName',`sell_rep_mint` = '$RMInt',`sell_rep_lname` = '$RLName',`sell_rep_phone` = '$Phone',`sell_rep_cell` = '$Cell',`sell_rep_fax` = '$Fax',`sell_rep_email` = '$REmail' WHERE `sell_rep_id` = '$RId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `sellers_reps` WHERE `sell_rep_id` = '$RId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$ManId = $row_get_info['sell_id'];
		$RFName = $row_get_info['sell_rep_fname'];
		$RMInt = $row_get_info['sell_rep_mint'];
		$RLName = $row_get_info['sell_rep_lname'];
		$Phone = $row_get_info['sell_rep_phone'];
		$P1 = substr($Phone,0,3);
		$P2 = substr($Phone,3,3);
		$P3 = substr($Phone,6,4);
		$Cell = $row_get_info['sell_rep_cell'];
		$C1 = substr($Cell,0,3);
		$C2 = substr($Cell,3,3);
		$C3 = substr($Cell,6,4);
		$Fax = $row_get_info['sell_rep_fax'];
		$F1 = substr($Fax,0,3);
		$F2 = substr($Fax,3,3);
		$F3 = substr($Fax,6,4);
		$REmail = $row_get_info['sell_rep_email'];
		
		mysql_free_result($get_info);
	}
}
?>