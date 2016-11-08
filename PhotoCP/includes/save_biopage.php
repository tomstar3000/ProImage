<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_image_resize.php';

$cust_id = $CustId;
$Folder = "../photographers";
$Folder = realpath($Folder);
$Folder .= "/".$CHandle;

$MaxSize = 3200000; //Maximum Files Sizes that can be loaded
$IcWidth = 135;
$IcHeight = 135;

$Desc = (isset($_POST['Page_Text'])) ? clean_variable($_POST['Page_Text']) : '';
$Icon = "";
$Iconv = (isset($_POST['Icon_val'])) ? clean_variable($_POST['Icon_val'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save"){
	if($use_ftp == true){
		$conn_id = ftp_connect($ftp_server);
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	}
	if (is_uploaded_file($_FILES['Icon']['tmp_name'])){
		if(!is_dir($Folder)){
			if($use_ftp === true){
				ftp_mkdir($conn_id, $Folder);
			} else {
				mkdir($Folder);	
			}
		}
		$Fname = $_FILES['Icon']['name'];
		$Iname = "Bio_Image.jpg";
		$ISize = $_FILES['Icon']['size'];
		$ITemp = $_FILES['Icon']['tmp_name'];
		$IType = $_FILES['Icon']['type'];
		
		ini_set("memory_limit","100M");
		$Icon = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Folder, $IcWidth, $IcHeight, false, true, $use_ftp, $conn_id);
		ini_restore ("memory_limit");
	} else {
		$Icon[0] = true;
		$Icon[1] = $Iconv;
	}
	if($Icon[0]){
		$Icon = $Icon[1];
		$text = clean_variable($Desc,'Store');		
		
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql("UPDATE `cust_customers` SET `cust_desc_2` = '$text',`cust_thumb` = '$Icon' WHERE `cust_id` = '$cust_id';");
	} else {
		$message = "";
		if($Icon[0]){
			$message += $Icon[1];
		}
		$Icon = $Icon[1];
	}
} else {
	$getUsrInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getUsrInfo->mysql("SELECT `cust_desc_2`, `cust_thumb` FROM `cust_customers` WHERE `cust_id` = '$cust_id';");
	$getUsrInfo = $getUsrInfo->Rows();
	
	$Desc = $getUsrInfo[0]['cust_desc_2'];
	$Icon = "";
	$Iconv = $getUsrInfo[0]['cust_thumb'];
}
define('NoSave',true);
if(isset($required_string))
	$onclick = 'MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}';
else 
	$onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
?>