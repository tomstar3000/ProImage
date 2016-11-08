<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../";
define ("PhotoExpress Pro", true);
include $r_path.'security.php';
require_once($r_path.'../Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_sql_processor.php');
require_once($r_path.'scripts/fnct_clean_entry.php');

$IId = clean_variable($_POST['Image_Id'],true);
$InvId = (isset($_POST['Invoice_Id'])) ? clean_variable($_POST['Invoice_Id'],true) : false;
$CId = clean_variable($_POST['Customer_Id'],true);

$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `image_tiny`, `image_folder`, `cust_fname`, `cust_lname` FROM `cust_customers` INNER JOIN `photo_event_images` ON `photo_event_images`.`cust_id` = `cust_customers`.`cust_id` WHERE `image_id` = '$IId' AND `cust_customers`.`cust_id` = '$CId';");

if($getInfo->TotalRows() == 0){ ?>
<script type="text/javascript">parent.document.send_Msg('',false,null,null);</script>
<? die(); }
$getInfo = $getInfo->Rows();

if(isset($_POST['Controller']) && $_POST['Controller'] == "true" && $InvId != false){
	require_once($r_path.'scripts/fnct_image_resize.php');
	$Folder = mb_convert_encoding($getInfo[0]['image_folder'],"UTF-8","HTML-ENTITIES");
	
	$index = strrpos($Folder,"/");
	$Folder = substr($Folder,0,$index);
	$index = strrpos($Folder,"/");
	$Folder = substr($Folder,0,$index);
	$Folder = "../".$r_path.$Folder;
	$Icon = $Folder."/Icon";
	$Icon = realpath($Icon);
	$Large = $Folder."/Large";
	$Large = realpath($Large);
	$Thumbnails = $Folder."/Thumbnails";
	$Thumbnails = realpath($Thumbnails);
	$Folder = "../".$r_path.mb_convert_encoding($getInfo[0]['image_folder'],"UTF-8","HTML-ENTITIES");
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
		if($InvId == "false"){
			$Iname = $Fname;
		} else {
			$Iname = "upd_".$Fname;
		}
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
		ini_set("memory_limit","200M");
		$ILarge = loadimage($MaxSize, $Fname, $Iname, $ISize, $LTemp, $IType, $Large, $LWidth, $LHeight, false, true, $use_ftp, $conn_id);
		//$IIcon = loadimage($MaxSize, $Fname, $Iname, $ISize, $IcTemp, $IType, $Icon, $IcWidth, $IcHeight, false, true, $use_ftp, $conn_id);
		ini_restore ("memory_limit");
		if($ILarge[0] === true){
		//if($IIcon[0] === true){
			//$ILarge = array();
			//$IThumb = array();
			//ini_set("memory_limit","200M");
			//$ILarge = loadimage($MaxSize, $Fname, $Iname, $ISize, $LTemp, $IType, $Large, $LWidth, $LHeight, false, true, $use_ftp, $conn_id);
			//$IThumb = loadimage($MaxSize, $Fname, $Iname, $ISize, $TTemp, $IType, $Thumbnails, $TWidth, $THeight, false, true, $use_ftp, $conn_id);
			//ini_restore ("memory_limit");
			//if($ILarge[0] === true && $IThumb[0] === true){
				if($use_ftp === true){
					ftp_put($conn_id, $Folder."/".$Iname, $ITemp, FTP_BINARY);
				} else {
					copy($ITemp, $Folder."/".$Iname);
				}
				unlink($ITemp);
				$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
				$updInfo->mysql("UPDATE `orders_invoice_photo` SET `invoice_image_image` = '$Iname' WHERE `invoice_id` = '$InvId' AND `image_id` = '$IId';");
			?>
<!-- <script type="text/javascript">parent.document.send_Msg('',false,null,null);</script> -->
<script type="text/javascript">parent.document.location.href = parent.document.location.href;	</script>
<? } } } // } ?>
