<?
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$CId = $path[count($path)-1];
$FName = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : '';
$LName = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : '';
$CName = (isset($_POST['Company_Name'])) ? clean_variable($_POST['Company_Name'],true) : '';
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
$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save"){
	if($cont == "add"){
		if($_POST['Time'] != $_SESSION['Time']){
			$_SESSION['Time'] = $_POST['Time'];	
			$add = "INSERT INTO `web_event_contacts` (`event_con_fname`,`event_con_lname`,`event_con_cname`,`event_con_phone`,`event_con_cell`,`event_con_fax`,`event_con_email`) VALUES ('$FName','$LName','$CName','$Phone','$Cell','$Fax','$Email');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
			
			$query_get_last = "SELECT `event_con_id` FROM `web_event_contacts` ORDER BY `event_con_id` DESC LIMIT 0,1";
			$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
			$row_get_last = mysql_fetch_assoc($get_last);
			
			$CId = $row_get_last['event_con_id'];
			array_push($path,$CId);
		}
	} else if($cont == "edit"){
		$upd = "UPDATE `web_event_contacts` SET `event_con_fname` = '$FName',`event_con_lname` = '$LName',`event_con_cname` = '$CName', `event_con_phone` = '$Phone',`event_con_cell` = '$Cell',`event_con_fax` = '$Fax',`event_con_email` = '$Email' WHERE `event_con_id` = '$CId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `web_event_contacts` WHERE `event_con_id` = '$CId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$FName = $row_get_info['event_con_fname'];
		$LName = $row_get_info['event_con_lname'];
		$CName = $row_get_info['event_con_cname'];
		$Phone = $row_get_info['event_con_phone'];
		$P1 = substr($Phone,0,3);
		$P2 = substr($Phone,3,3);
		$P3 = substr($Phone,6,4);
		$Cell = $row_get_info['event_con_cell'];
		$C1 = substr($Cell,0,3);
		$C2 = substr($Cell,3,3);
		$C3 = substr($Cell,6,4);
		$Fax = $row_get_info['event_con_fax'];
		$F1 = substr($Fax,0,3);
		$F2 = substr($Fax,3,3);
		$F3 = substr($Fax,6,4);
		$Email = $row_get_info['event_con_email'];
		
		mysql_free_result($get_info);
	}
}
?>
