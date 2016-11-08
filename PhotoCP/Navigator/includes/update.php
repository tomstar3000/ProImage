<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../";
$r_path = "../";
require_once $r_path.'includes/cp_connection.php';
require_once $r_path.'scripts/encrypt.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_image_resize.php';

$IID = clean_variable($_POST['Image_Id'],true);

$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `image_tiny`, `image_folder`, `cust_fname`, `cust_lname` FROM `cust_customers` INNER JOIN `photo_event_images` ON `photo_event_images`.`cust_id` = `cust_customers`.`cust_id` WHERE `image_id` = '$IID';");
if($getInfo->TotalRows() == 0) $_POST['Controller'] = "false";
if(isset($_POST['Controller']) && $_POST['Controller'] == "true"){
	ini_set("memory_limit","200M");
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../";
	$getInfo = $getInfo->Rows();
	$Folder = mb_convert_encoding($getInfo[0]['image_folder'],"UTF-8","HTML-ENTITIES");
	$index = strrpos($Folder,"/");
	$Folder = substr($Folder,0,$index);
	$index = strrpos($Folder,"/");
	$Folder = substr($Folder,0,$index);
	$Folder = $r_path.$Folder;
	$Icon = $Folder."/Icon";
	$Icon = realpath($Icon);
	$Large = $Folder."/Large";
	$Large = realpath($Large);
	$Thumbnails = $Folder."/Thumbnails";
	$Thumbnails = realpath($Thumbnails);
	$Folder = $r_path.mb_convert_encoding($getInfo[0]['image_folder'],"UTF-8","HTML-ENTITIES");
	$Folder = realpath($Folder);
		
	$MaxSize = 20971520; //Maximum Files Sizes that can be loaded
	$IcWidth = 195;
	$IcHeight = 195;
	$LWidth = 960;
	$LHeight = 640;
	$TWidth = 630;
	$THeight = 630;
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		$Fname = $_FILES['Image']['name'];
		$Fname = eregi_replace("[^A-Za-z0-9\.]","",$Fname);
		$Iname = $Fname;
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IcTemp = $_FILES['Image']['tmp_name'];
		$LTemp = $_FILES['Image']['tmp_name'];
		$TTemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		list($IWidth, $IHeight) = getimagesize($ITemp);
		$IIcon = array();
		if($use_ftp === true){
			$conn_id = ftp_connect($ftp_server);
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
		}
		
		$ILarge = loadimage($MaxSize, $Fname, $Iname, $ISize, $LTemp, $IType, $Large, $LWidth, $LHeight, false, true, $use_ftp, $conn_id);
		if($ILarge[0] === true){
			if($use_ftp === true){
				ftp_put($conn_id, $Folder."/".$Iname, $ITemp, FTP_BINARY);
			} else {
				copy($ITemp, $Folder."/".$Iname);
			}
			unlink($ITemp);
			$Update = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$Update->mysql("UPDATE `photo_event_images` SET `image_tiny` = '$Iname', `image_small` = '$Iname', `image_large` = '$Iname' WHERE `image_id` = '$IID';");
		}
	}	ini_restore ("memory_limit");
} ?>
<script type="text/javascript">parent.document.getElementById('Navigator').contentWindow.Org_Rplc_Img_Done();</script>