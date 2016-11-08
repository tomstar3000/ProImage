<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$CId = ($path[2] == "SpcMrch") ? $path[3] : $path[2];
$RId = ($path[2] == "SpcMrch") ? $path[5] : $path[4];
$required_string = array();		
$Rel1 = (isset($_POST['Relationship_1'])) ? clean_variable($_POST['Relationship_1'],true) : '';	$required_string[] = "'Relationship_1','1st Client Relationship','RisSelect'";
$Rel2 = (isset($_POST['Relationship_2'])) ? clean_variable($_POST['Relationship_2'],true) : '';	$required_string[] = "'Relationship_2','2nd Client Relationship','RisSelect'";
$FName = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : ''; $required_string[] = "'First_Name','','R'";
$MInt = (isset($_POST['Middle_Initial'])) ? clean_variable($_POST['Middle_Initial'],true) : '';
$LName = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : ''; $required_string[] = "'Last_Name','','R'";
$Add = (isset($_POST['Address'])) ? clean_variable($_POST['Address'],true) : '';
$SApt = (isset($_POST['Suite_Apt'])) ? clean_variable($_POST['Suite_Apt'],true) : '';
$Add2 = (isset($_POST['Address_2'])) ? clean_variable($_POST['Address_2'],true) : '';
$City = (isset($_POST['City'])) ? clean_variable($_POST['City'],true) : '';
$State = (isset($_POST['State'])) ? clean_variable($_POST['State'],true) : '';
$Zip = (isset($_POST['Zip'])) ? clean_variable($_POST['Zip'],true) : '';
$Country = (isset($_POST['Country'])) ? clean_variable($_POST['Country'],true) : '';
$Phone = (isset($_POST['Phone_Number'])) ? clean_variable($_POST['Phone_Number'],true) : '';
$P1 = (isset($_POST['P1'])) ? clean_variable($_POST['P1'],true) : '';
$P2 = (isset($_POST['P2'])) ? clean_variable($_POST['P2'],true) : '';
$P3 = (isset($_POST['P3'])) ? clean_variable($_POST['P3'],true) : '';
$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : ''; $required_string[] = "'Email','','RisEmail'";
$Notes = (isset($_POST['Notes'])) ? clean_variable($_POST['Notes'],true) : '';
$ClntID = (isset($_POST['ClientID'])) ? clean_variable($_POST['ClientID'],true) : '0';

if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == "add" || $cont == "edit")){
	if($ClntID == '0'){
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT `cust_id` FROM `cust_customers` WHERE `cust_email` = '$Email';");
		if($getInfo->TotalRows() > 0){ $ClntID = $getInfo->Rows(); $ClntID="1.".$ClntID[0]['cust_id']; }
	}
	if($ClntID == '0'){
		$addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$addInfo->mysql("INSERT INTO `cust_customers` (`photo_id`, `cust_fname`, `cust_mint`, `cust_lname`, `cust_add`, `cust_add_2`, `cust_suite_apt`, `cust_city`, `cust_state`, `cust_zip`, `cust_country`, `cust_phone`, `cust_email`, `cust_created`, `cust_active`, `cust_photo`) VALUES ('".$CustId."', '".$FName."', '".$MInt."', '".$LName."', '".$Add."', '".$Add2."', '".$SApt."', '".$City."', '".$State."', '".$Zip."', '".$Country."', '".$Phone."', '".$Email."', NOW(), 'y', 'n');");
		
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT `cust_id` FROM `cust_customers` WHERE `photo_id` = '".$CustId."' ORDER BY `cust_id` DESC LIMIT 0,1;");
		$getInfo = $getInfo->Rows();
		$ClntID = $getInfo->Rows(); $ClntID="1.".$ClntID[0]['cust_id'];
	} else {
		$ID = explode(".",$ClntID);
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql("UPDATE `cust_customers` SET `photo_id` = '".$CustId."', `cust_fname` = '".$FName."', `cust_mint` = '".$MInt."', `cust_lname` = '".$LName."', `cust_add` = '".$Add."', `cust_add_2` = '".$Add2."', `cust_suite_apt` = '".$SApt."', `cust_city` = '".$City."', `cust_state` = '".$State."', `cust_zip` = '".$Zip."', `cust_country` = '".$Country."', `cust_phone` = '".$Phone."', `cust_email` = '".$Email."', `cust_modified` = NOW() WHERE `cust_id` = '".$ID[1]."';");
	}
	$ID = explode(".",$ClntID);
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
		$addInfo->mysql("INSERT INTO `cust_customers_relationship` (`cust_id`,`cust_id_2`,`cust_rel`,`cust_rel_2`,`cust_rel_notes`) VALUES ('".$CId."','".$ID[1]."','".$Rel1."','".$Rel2."','".$Notes."');");
		
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT `cust_rel_id` FROM `cust_customers_relationship` WHERE `cust_id` = '".$CId."' ORDER BY `cust_rel_id` DESC LIMIT 0,1;");
		$getInfo = $getInfo->Rows();
		$RId = $getInfo[0]['cust_id'];
		$path[] = $RId;
	} else {		
		$SID1 = $CId; $SID2 = $ID[1];
		if($Id == $CId){ $SID1 = $ID[1]; $SID2 = $CId; $RelT = $Rel1; $Rel1 = $Rel2; $Rel2 = $RelT; unset($RelT); }
		
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql("UPDATE `cust_customers` SET `photo_id` = '".$CustId."' WHERE `cust_id` = '".$SID1."';");
		$updInfo->mysql("UPDATE `cust_customers` SET `photo_id` = '".$CustId."' WHERE `cust_id` = '".$SID2."';");
		
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql("UPDATE `cust_customers_relationship` SET `cust_id` = '".$SID1."', `cust_id_2` = '".$SID2."', `cust_rel` = '".$Rel1."', `cust_rel_2` = '".$Rel2."', `cust_rel_notes` = '".$Notes."' WHERE `cust_rel_id` = '".$RId."';");
	}
	$cont="view";
} else {
	if($cont != "add"){		
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT * FROM `cust_customers_relationship` WHERE `cust_rel_id` = '".$RId."';");
		$getInfo = $getInfo->Rows();
		
		$Rel1 = $getInfo[0]['cust_rel'];
		$Rel2 = $getInfo[0]['cust_rel_2'];
		$Notes = $getInfo[0]['cust_rel_notes'];
		$Id = $getInfo[0]['cust_id_2'];
		$ClntID = "1.".$getInfo[0]['cust_id_2'];
		if($Id == $CId){
			$Rel1 = $getInfo[0]['cust_rel_2'];
			$Rel2 = $getInfo[0]['cust_rel'];
			$Id = $getInfo[0]['cust_id'];
			$ClntID = "1.".$getInfo[0]['cust_id'];
		}
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT * FROM `cust_customers` WHERE `cust_id` = '".$Id."';");
		$getInfo = $getInfo->Rows();
				
		$FName = $getInfo[0]['cust_fname'];
		$MInt = $getInfo[0]['cust_mint'];
		$LName = $getInfo[0]['cust_lname'];
		$Add = $getInfo[0]['cust_add'];
		$Add2 = $getInfo[0]['cust_add2'];
		$SApt = $getInfo[0]['cust_suite_apt'];
		$City = $getInfo[0]['cust_city'];
		$State = $getInfo[0]['cust_state'];
		$Zip = $getInfo[0]['cust_zip'];
		$Country = $getInfo[0]['cust_country'];
		$Phone = $getInfo[0]['cust_phone'];
		$P1 = substr($getInfo[0]['cust_phone'],0,3);
		$P2 = substr($getInfo[0]['cust_phone'],3,3);
		$P3 = substr($getInfo[0]['cust_phone'],6,4);
		$Email = $getInfo[0]['cust_email'];
	}
}

define('NoSave',true);
if(isset($required_string)){ $required_string = implode(",",$required_string);
	$onclick = 'MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}'; }
else 
	$onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
?>