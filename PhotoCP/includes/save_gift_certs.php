<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_format_date.php';
if($path[0] == "Evnt"){
	$CId = $path[4];
} else {
	$CId = $path[2];
}
if($cont != "add"){
	$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getInfo->mysql("SELECT `event_id` FROM `photo_event_disc` WHERE `disc_id` = '$CId';");
	if($getInfo->totalRows() > 0){
		$CEID = $getInfo->Rows(); $CEID = $CEID[0]['event_id']; } else $CEID = '0';
} else $CEID = '0';
	
$SEID = (isset($_POST['Event'])) ? clean_variable($_POST['Event'],true) : (($path[0]=='Evnt' && $cont != 'view')? $path[2] : '0');
$CName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$CCode = (isset($_POST['Code'])) ? clean_variable($_POST['Code'],true) : '';
$CEmail = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';
$CPrice = (isset($_POST['Price'])) ? clean_variable($_POST['Price'],true) : '';
$Error = false;
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	$SDiscon = date("Y-m-d H:i:s",mktime(0,0,0,substr($CDiscon,0,2),substr($CDiscon,3,2),substr($CDiscon,6,4)));
	if($cont == "add"){
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT COUNT(`disc_id`) AS `count_code` FROM `prod_discount_codes` WHERE `disc_code` = '$CCode' AND `cust_id` = '$CustId' AND `disc_use` = 'y';");
		$getInfo = $getInfo->Rows();
		
		if($getInfo[0]['count_code'] == 0){
			$addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$addInfo->mysql("INSERT INTO `prod_discount_codes` (`cust_id`, `disc_name`,`disc_code`,`disc_price`,`disc_roll_over`,`disc_email`,`disc_type`) VALUES ('$CustId','$CName','$CCode','$CPrice','y','$CEmail','g');");
			
			$getLast = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$getLast->mysql("SELECT `disc_id` FROM `prod_discount_codes` WHERE `cust_id` = '$CustId' ORDER BY `disc_id` DESC LIMIT 0,1;");
			$getLast = $getLast->Rows();		
			array_push($path,$getLast[0]['disc_id']);
		} else {
			$Error = "That code has already been used";
		}
	} else {
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql("UPDATE `prod_discount_codes` SET `disc_name` = '$CName',`disc_code` = '$CCode',`disc_price` = '$CPrice', `disc_email` = '$CEmail' WHERE `disc_id` = '$CId';");
		if(intval($SEID) == 0){
			$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$updInfo->mysql("DELETE FROM `photo_event_disc` WHERE `disc_id` = '$CId' AND `event_id` = '$CEID';");
			$updInfo->mysql("OPTIMIZE TABLE `photo_event_disc`;");
			$CEID = $SEID;
		} else if($CEID != $SEID){
			$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$updInfo->mysql("DELETE FROM `photo_event_disc` WHERE `disc_id` = '$CId' AND `event_id` = '$CEID';");
			$updInfo->mysql("OPTIMIZE TABLE `photo_event_disc`;");
			$updInfo->mysql("INSERT INTO `photo_event_disc` (`disc_id`,`event_id`,`disc_type`) VALUES ('$CId','$SEID','g');");
			$CEID = $SEID;
		}
	}
	if(!$Error)$cont = "view";
} else {
	if($cont != "add"){
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT * FROM `prod_discount_codes` WHERE `disc_id` = '$CId';");
		$getInfo = $getInfo->Rows();
		
		$CName = $getInfo[0]['disc_name'];
		$CCode = $getInfo[0]['disc_code'];
		$CEmail = $getInfo[0]['disc_email'];
		$CPrice = $getInfo[0]['disc_price'];
		
		$SEID = $CEID;
	}
}
define('NoSave',true);
if(isset($required_string))
	$onclick = 'MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}';
else 
	$onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
?>