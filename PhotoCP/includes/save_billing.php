<?  if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$temp_path = $r_path;
$r_path .= "../";
require_once($r_path.'scripts/cart/encrypt.php');
$r_path = $temp_path;

$FName = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : '';
$MInt = (isset($_POST['Middle_Initial'])) ? clean_variable($_POST['Middle_Initial'],true) : '';
$LName = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : '';
$Suffix = (isset($_POST['Suffix'])) ? clean_variable($_POST['Suffix'],true) : '';
$CName = (isset($_POST['Company_Name'])) ? clean_variable($_POST['Company_Name'],true) : '';
$Add = (isset($_POST['Address'])) ? clean_variable($_POST['Address'],true) : '';
$Add2 = (isset($_POST['Address_2'])) ? clean_variable($_POST['Address_2'],true) : '';
$SApt = (isset($_POST['Suite_Apt'])) ? clean_variable($_POST['Suite_Apt'],true) : '';
$City = (isset($_POST['City'])) ? clean_variable($_POST['City'],true) : '';
$State = (isset($_POST['State'])) ? clean_variable($_POST['State'],true) : '';
$Zip = (isset($_POST['Zip'])) ? clean_variable($_POST['Zip'],true) : '';
$Country = (isset($_POST['Country'])) ? clean_variable($_POST['Country'],true) : '';

if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && $cont == 'edit'){
	if($cont == "edit"){		
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql("UPDATE `cust_billing` SET `cust_bill_fname` = '$FName',`cust_bill_mname` = '$MInt',`cust_bill_lname` = '$LName',`cust_bill_suffix` = '$Suffix',`cust_bill_cname` = '$CName',`cust_bill_add` = '$Add',`cust_bill_add_2` = '$Add2',`cust_bill_suite_apt` = '$SApt',`cust_bill_city` = '$City',`cust_bill_state` = '$State',`cust_bill_zip` = '$Zip',`cust_bill_counry` = '$Country' WHERE `cust_id` = '$CustId';");
	}
	$cont = "view";
} else {
	$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getInfo->mysql("SELECT * FROM `cust_billing` WHERE `cust_id` = '$CustId';");
	$getInfo = $getInfo->Rows();
	
	$FName = $getInfo[0]['cust_bill_fname'];
	$MInt = $getInfo[0]['cust_bill_mname'];
	$LName = $getInfo[0]['cust_bill_lname'];
	$Suffix = $getInfo[0]['cust_bill_suffix'];
	$CName = $getInfo[0]['cust_bill_cname'];
	$Add = $getInfo[0]['cust_bill_add'];
	$Add2 = $getInfo[0]['cust_bill_add_2'];
	$SApt = $getInfo[0]['cust_bill_suite_apt'];
	$City = $getInfo[0]['cust_bill_city'];
	$State = $getInfo[0]['cust_bill_state'];
	$Zip = $getInfo[0]['cust_bill_zip'];
	$Country = $getInfo[0]['cust_bill_counry'];
}
define('NoSave',true);
if(isset($required_string))
	$onclick = 'MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}';
else 
	$onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
?>
