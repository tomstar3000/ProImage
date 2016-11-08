<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_image_resize.php';

$cust_id = $CustId;
$Folder = "../photographers";
$Folder = realpath($Folder);
$Folder .= "/".$CHandle;

$MaxSize = 3200000; //Maximum Files Sizes that can be loaded
$IWidth = 725;
$IHeight = 50;

$Company = (isset($_POST['Company_Name'])) ? clean_variable($_POST['Company_Name'],true) : '';
$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';
$Work = (isset($_POST['Work_Number'])) ? clean_variable($_POST['Work_Number'],true) : '';
$W1 = substr($Work,0,3);
$W2 = substr($Work,3,3);
$W3 = substr($Work,6,4);
$Ext = (isset($_POST['Extension'])) ? clean_variable($_POST['Extension'],true) : '';
$Image = "";
$Imagev = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save"){
	if($use_ftp == true){
		$conn_id = ftp_connect($ftp_server);
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	}
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		if(!is_dir($Folder)){
			if($use_ftp == true){
				ftp_mkdir($conn_id, $Folder);
			} else {
				mkdir($Folder);	
			}
		}
		$Fname = $_FILES['Image']['name'];
		$Iname = "Footer.jpg";
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		
		ini_set("memory_limit","100M");
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Folder, $IWidth, $IHeight, true, true, $use_ftp, $conn_id);
		ini_restore ("memory_limit");
	} else {
		$Image[0] = true;
		$Image[1] = $Imagev;
	}
	if($Image[0]){
		$Image = $Image[1];
		$Imagev = $Image;
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql("UPDATE `cust_customers` SET `cust_fcname` = '$Company', `cust_fwork` = '$Work', `cust_fext` = '$Ext', `cust_femail` = '$Email', `cust_icon` = '$Image' WHERE `cust_id` = '$cust_id';");
	} else {
		$message = "";
		if($Image[0]){
			$message += $Image[1]+"\n";
		}
		$Image = $Image[1];
		$Imagev = $Image;
	}
} else {
	$getUsrInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getUsrInfo->mysql("SELECT `cust_cname`, `cust_work`, `cust_ext`, `cust_email`, `cust_fcname`, `cust_fwork`, `cust_fext`, `cust_femail`, `cust_icon` FROM `cust_customers` WHERE `cust_id` = '$cust_id' LIMIT 0,1;");
	$getUsrInfo = $getUsrInfo->Rows();

	$Company = ($getUsrInfo[0]['cust_fcname']!="")?$getUsrInfo[0]['cust_fcname']:$getUsrInfo[0]['cust_cname'];
	$Email = ($getUsrInfo[0]['cust_femail']!="")?$getUsrInfo[0]['cust_femail']:$getUsrInfo[0]['cust_email'];
	$Work = ($getUsrInfo[0]['cust_fwork']!="0")?$getUsrInfo[0]['cust_fwork']:$getUsrInfo[0]['cust_work'];
	//1234567890
	//0123456789
	$W1 = substr($Work,0,3);
	$W2 = substr($Work,3,3);
	$W3 = substr($Work,6,4);
	$Ext = ($getUsrInfo[0]['cust_fext']!="0")?$getUsrInfo[0]['cust_fext']:$getUsrInfo[0]['cust_ext'];
	$Image = "";
	$Imagev = $getUsrInfo[0]['cust_icon'];
}
define('NoSave',true);
if(isset($required_string))
	$onclick = 'MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}';
else 
	$onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
?>