<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_format_date.php';

$CId = $path[2];
$CName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$CCode = (isset($_POST['Code'])) ? clean_variable($_POST['Code'],true) : '';
$CPercent = (isset($_POST['Percent'])) ? clean_variable($_POST['Percent'],true) : '';
$CPrice = (isset($_POST['Price'])) ? clean_variable($_POST['Price'],true) : '';
$CDiscon = (isset($_POST['Disc_Month'])) ? clean_variable($_POST['Disc_Year']."-".$_POST['Disc_Month']."-".$_POST['Disc_Day']." 00:00:00",true) : date("Y-m-d H:i:s");
$CUses = (isset($_POST['Number_of_Uses'])) ? clean_variable($_POST['Number_of_Uses']) : '0';
$CRoll = (isset($_POST['Rolling_Credit'])) ? clean_variable($_POST['Rolling_Credit']) : 'n';
$Error = false;
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	$SDiscon = $CDiscon;
	if($cont == "add"){
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT COUNT(`disc_id`) AS `count_code` FROM `prod_discount_codes` WHERE `disc_code` = '$CCode' AND `cust_id` = '$CustId' AND `disc_use` = 'y';");
		$getInfo = $getInfo->Rows();
		
		if($getInfo[0]['count_code'] == 0){
			$addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$addInfo->mysql("INSERT INTO `prod_discount_codes` (`cust_id`, `disc_name`,`disc_code`,`disc_percent`,`disc_exp`,`disc_price`,`disc_num_uses`, `disc_roll_over`) VALUES ('$CustId','$CName','$CCode','$CPercent','$SDiscon','$CPrice','$CUses','$CRoll');");
			
			$getLast = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$getLast->mysql("SELECT `disc_id` FROM `prod_discount_codes` WHERE `cust_id` = '$CustId' ORDER BY `disc_id` DESC LIMIT 0,1;");
			$getLast = $getLast->Rows();		
			array_push($path,$getLast[0]['disc_id']);
		} else {
			$Error = "That code has already been used";
		}
	} else {
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql("UPDATE `prod_discount_codes` SET `disc_name` = '$CName',`disc_code` = '$CCode',`disc_percent` ='$CPercent',`disc_exp` = '$SDiscon',`disc_price` = '$CPrice',`disc_num_uses` = '$CUses', `disc_roll_over` = '$CRoll' WHERE `disc_id` = '$CId';");
	}
	if(!$Error)$cont = "view";
} else {
	if($cont != "add"){
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT * FROM `prod_discount_codes` WHERE `disc_id` = '$CId';");
		$getInfo = $getInfo->Rows();
		
		$CName = $getInfo[0]['disc_name'];
		$CCode = $getInfo[0]['disc_code'];
		$CPercent = $getInfo[0]['disc_percent'];
		$CPrice = $getInfo[0]['disc_price'];
		$CDiscon = $getInfo[0]['disc_exp'];
		$CUses = $getInfo[0]['disc_num_uses'];
		$CRoll = $getInfo[0]['disc_roll_over'];
	}
}
define('NoSave',true);
if(isset($required_string))
	$onclick = 'MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}';
else 
	$onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
?>