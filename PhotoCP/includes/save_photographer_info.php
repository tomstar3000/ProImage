<?  if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$PhotoID = $path[2];
$FName = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : '';
$MInt = (isset($_POST['Middle_Initial'])) ? clean_variable($_POST['Middle_Initial'],true) : '';
$LName = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : '';
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
$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';

if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$addInfo->mysql("INSERT INTO `photo_photographers` (`cust_id`,`photo_fname`,`photo_lname`,`photo_add`,`photo_add2`,`photo_suiteapt`,`photo_city`,`photo_state`,`photo_zip`,`photo_country`,`photo_phone`,`photo_email`,`photo_use`) VALUES ('$CustId','$FName','$LName','$Add','$Add2','$SApt','$City','$State','$Zip','$Country','$Phone','$Email','y');");
		
		$getLast = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getLast->mysql("SELECT `photo_id` FROM `photo_photographers` WHERE `cust_id` = '$CustId' ORDER BY `photo_id` DESC LIMIT 0,1;");
		$getLast = $getLast->Rows();		
		array_push($path,$getLast[0]['photo_id']);
		
	} else {
		$upd = "UPDATE `photo_photographers` SET `photo_fname` = '$FName',`photo_lname` = '$LName',`photo_add` = '$Add',`photo_add2` = '$Add2',`photo_suiteapt` = '$SApt',`photo_city` = '$City',`photo_state` = '$State',`photo_zip` = '$Zip',`photo_country` = '$Country',`photo_phone` = '$Phone',`photo_email` = '$Email' WHERE `photo_id` = '$PhotoID'";
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql($upd);
	} 
	$cont = "view";
} else {
	if($cont != "add"){	
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT * FROM `photo_photographers` WHERE `photo_id` = '$PhotoID';");
		$getInfo = $getInfo->Rows();
				
		$FName = $getInfo[0]['photo_fname'];
		$LName = $getInfo[0]['photo_lname'];
		$Add = $getInfo[0]['photo_add'];
		$Add2 = $getInfo[0]['photo_add2'];
		$SApt = $getInfo[0]['photo_suiteapt'];
		$City = $getInfo[0]['photo_city'];
		$State = $getInfo[0]['photo_state'];
		$Zip = $getInfo[0]['photo_zip'];
		$Country = $getInfo[0]['photo_country'];
		$Phone = $getInfo[0]['photo_phone'];
		$P1 = substr($getInfo[0]['photo_phone'],0,3);
		$P2 = substr($getInfo[0]['photo_phone'],3,3);
		$P3 = substr($getInfo[0]['photo_phone'],6,4);
		$Email = $getInfo[0]['photo_email'];
	}
}
define('NoSave',true);
if(isset($required_string))
	$onclick = 'MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}';
else 
	$onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
?>
