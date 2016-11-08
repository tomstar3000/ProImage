<?
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$CId = $path[count($path)-1];
$Name = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$Add = (isset($_POST['Address'])) ? clean_variable($_POST['Address'],true) : '';
$Add2 = (isset($_POST['Address_2'])) ? clean_variable($_POST['Address_2'],true) : '';
$SApt = (isset($_POST['Suite_Apt'])) ? clean_variable($_POST['Suite_Apt'],true) : '';
$City = (isset($_POST['City'])) ? clean_variable($_POST['City'],true) : '';
$State = (isset($_POST['State'])) ? clean_variable($_POST['State'],true) : '';
$Zip = (isset($_POST['Zip'])) ? clean_variable($_POST['Zip'],true) : '';
$Country = (isset($_POST['Country'])) ? clean_variable($_POST['Country'],true) : '';
$Phone = (isset($_POST['Phone_Number'])) ? clean_variable($_POST['Phone_Number'],true) : '';
$P1 = (isset($_POST['P1'])) ? clean_variable($_POST['P1'],true) : '';
$P2 = (isset($_POST['P2'])) ? clean_variable($_POST['P2'],true) : '';
$P3 = (isset($_POST['P3'])) ? clean_variable($_POST['P3'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save"){
	if($cont == "add"){
		if($_POST['Time'] != $_SESSION['Time']){
			$_SESSION['Time'] = $_POST['Time'];	
			$add = "INSERT INTO `web_event_locations` (`event_loc_name`,`event_loc_add`,`event_loc_add_2`,`event_loc_suite_apt`,`event_loc_city`,`event_loc_state`, `event_loc_zip`, `event_loc_country`, `event_loc_phone`) VALUES ('$Name','$Add','$Add2','$SApt','$City','$State','$Zip','$Country','$Phone');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
			
			$query_get_last = "SELECT `event_loc_id` FROM `web_event_locations` ORDER BY `event_loc_id` DESC LIMIT 0,1";
			$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
			$row_get_last = mysql_fetch_assoc($get_last);
			
			$CId = $row_get_last['event_loc_id'];
			array_push($path,$CId);
		}
	} else if($cont == "edit"){
		$upd = "UPDATE `web_event_locations` SET `event_loc_name` = '$Name',`event_loc_add` = '$Add',`event_loc_add_2` = '$Add2', `event_loc_suite_apt` = '$SApt',`event_loc_city` = '$City',`event_loc_state` = '$State',`event_loc_zip` = '$Zip', `event_loc_country` = '$Country', `event_loc_phone` = '$Phone' WHERE `event_loc_id` = '$CId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `web_event_locations` WHERE `event_loc_id` = '$CId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$Name = $row_get_info['event_loc_name'];
		$Add = $row_get_info['event_loc_add'];
		$Add2 = $row_get_info['event_loc_add_2'];
		$SApt = $row_get_info['event_loc_suite_apt'];
		$City = $row_get_info['event_loc_city'];
		$State = $row_get_info['event_loc_state'];
		$Zip = $row_get_info['event_loc_zip'];
		$Country = $row_get_info['event_loc_country'];
		$Phone = $row_get_info['event_loc_phone'];
		$P1 = substr($Phone,0,3);
		$P2 = substr($Phone,3,3);
		$P3 = substr($Phone,6,4);
		
		mysql_free_result($get_info);
	}
}
?>
