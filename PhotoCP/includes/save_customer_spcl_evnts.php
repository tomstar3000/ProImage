<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$CId = ($path[2] == "SpcMrch") ? $path[3] : $path[2];
$EId = ($path[2] == "SpcMrch") ? $path[5] : $path[4];
$required_string = array();
$Name = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : ''; 								$required_string[] = "'Name','','R'";
$Date = (isset($_POST['Event_Month'])) ? clean_variable($_POST['Event_Year']."-".$_POST['Event_Month']."-".$_POST['Event_Day']." 00:00:00",true) : date("Y-m-d H:i:s");
$Freg = (isset($_POST['Frequency'])) ? clean_variable($_POST['Frequency'],true) : '0';
$EvntNot = (isset($_POST['Email_Notification'])) ? clean_variable($_POST['Email_Notification'],true) : 'n';
$EvntTme = (isset($_POST['Notification_Time'])) ? clean_variable($_POST['Notification_Time'],true) : '1';
$EmailNotes = (isset($_POST['Email_Notes'])) ? clean_variable($_POST['Email_Notes'],"Store") : '';

if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == "add" || $cont == "edit")){
	if($cont == "add"){
		if($path[2] == "SpcMrch"){
			$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$getInfo->mysql("SELECT * FROM `photo_invoices` WHERE `invoice_id` = '$CId';");
			$getInfo = $getInfo->Rows();
					
			$FName = $getInfo[0]['fname'];
			$MInt = '';
			$LName = $getInfo[0]['lname'];
			$Add = $getInfo[0]['add'];
			$Add2 = $getInfo[0]['add2'];
			$SApt = $getInfo[0]['suite_apt'];
			$City = $getInfo[0]['city'];
			$State = $getInfo[0]['state'];
			$Zip = $getInfo[0]['zip'];
			$Country = $getInfo[0]['country'];
			$Phone = $getInfo[0]['phone'];
			$P1 = substr($getInfo[0]['phone'],0,3);
			$P2 = substr($getInfo[0]['phone'],3,3);
			$P3 = substr($getInfo[0]['phone'],6,4);
			$Email = $getInfo[0]['email'];
			
			$addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$addInfo->mysql("INSERT INTO `cust_customers` (`photo_id`, `cust_fname`, `cust_mint`, `cust_lname`, `cust_add`, `cust_add_2`, `cust_suite_apt`, `cust_city`, `cust_state`, `cust_zip`, `cust_country`, `cust_phone`, `cust_email`, `cust_created`, `cust_active`, `cust_photo`) VALUES ('".$CustId."', '".$FName."', '".$MInt."', '".$LName."', '".$Add."', '".$Add2."', '".$SApt."', '".$City."', '".$State."', '".$Zip."', '".$Country."', '".$Phone."', '".$Email."', NOW(), 'y', 'n');");
			
			$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$getInfo->mysql("SELECT `cust_id` FROM `cust_customers` WHERE `photo_id` = '".$CustId."' ORDER BY `cust_id` DESC LIMIT 0,1;");
			$getInfo = $getInfo->Rows();
			$CId = $getInfo[0]['cust_id'];
			$path[2] = $CId;
			$path[3] = 'SpclEvnts';
			$path = array_slice($path,0,4);
		} else {
			$addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$addInfo->mysql("UPDATE `cust_customers` SET `photo_id` = '".$CustId."' WHERE `cust_id` = '".$CId."';");
		}
				
		$addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$addInfo->mysql("INSERT INTO `cust_customers_special_date` (`cust_id`, `cust_date_name`, `cust_date_date`, `cust_date_freg`, `cust_date_rmd`, `cust_date_rmd_time`, `cust_date_msg`) VALUES ('$CId', '$Name', '$Date', '$Freg', '$EvntNot', '$EvntTme', '$EmailNotes'); ");
		
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT `cust_date_id` FROM `cust_customers_special_date` WHERE `cust_id` = '".$CId."' ORDER BY `cust_date_id` DESC LIMIT 0,1;");
		$getInfo = $getInfo->Rows();
		$EId = $getInfo[0]['cust_date_id'];
		$path[] = $EId;
	} else {
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql("UPDATE `cust_customers` SET `photo_id` = '".$CustId."' WHERE `cust_id` = '".$CId."';");
			
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql("UPDATE `cust_customers_special_date` SET `cust_date_name` = '$Name', `cust_date_date` = '$Date', `cust_date_freg` = '$Freg', `cust_date_rmd` = '$EvntNot', `cust_date_rmd_time` = '$EvntTme', `cust_date_msg` = '$EmailNotes' WHERE `cust_date_id` = '$EId'; ");
	}
	$cont="view";
} else {
	if($cont != "add"){		
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT * FROM `cust_customers_special_date` WHERE `cust_date_id` = '$EId'; ");
		$getInfo = $getInfo->Rows();
		
		$Name = $getInfo[0]['cust_date_name'];
		$Date = $getInfo[0]['cust_date_date'];
		$Freg = $getInfo[0]['cust_date_freg'];
		$EvntNot = $getInfo[0]['cust_date_rmd'];
		$EvntTme = $getInfo[0]['cust_date_rmd_time'];
	}
}

define('NoSave',true);
if(isset($required_string)){ $required_string = implode(",",$required_string);
	$onclick = 'MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}'; }
else 
	$onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
?>