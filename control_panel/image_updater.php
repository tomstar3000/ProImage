<? 
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
$r_path = "";
for($n=0;$n<$count;$n++){
	$r_path .= "../";
}
define ("PhotoExpress Pro", true);
include $r_path.'security.php';
require_once($r_path.'../Connections/cp_connection.php');
require_once($r_path.'config.php');
mysql_select_db($database_cp_connection, $cp_connection);
require_once $r_path.'scripts/fnct_clean_entry.php';
$IId = clean_variable($_GET['image'],true);
$is_invoice = (isset($_GET['invoice'])) ? clean_variable($_GET['invoice'],true) : "false";
if(!isset($_GET['adminid'])){
	$query_get_info = "SELECT `image_tiny`, `image_folder`, `cust_fname`, `cust_lname` FROM `cust_customers` INNER JOIN `photo_event_images` ON `photo_event_images`.`cust_id` = `cust_customers`.`cust_id` WHERE `image_id` = '$IId' AND `cust_session` = '$loginsession[0]'";
} else {
	$CId = clean_variable($_GET['adminid'],true);
	$query_get_info = "SELECT `image_tiny`, `image_folder`, `cust_fname`, `cust_lname` FROM `cust_customers` INNER JOIN `photo_event_images` ON `photo_event_images`.`cust_id` = `cust_customers`.`cust_id` WHERE `image_id` = '$IId' AND `cust_customers`.`cust_id` = '$CId'";
}
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
$total_get_info = mysql_num_rows($get_info);
if($total_get_info == 0){
	die();
}
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save"){
	require_once $r_path.'scripts/fnct_image_resize.php';
	$Folder = mb_convert_encoding($row_get_info['image_folder'],"UTF-8","HTML-ENTITIES");
	$index = strrpos($Folder,"/");
	$Folder = substr($Folder,0,$index);
	$index = strrpos($Folder,"/");
	$Folder = substr($Folder,0,$index);
	$Folder = "../".$Folder;
	$Icon = $Folder."/Icon";
	$Icon = realpath($Icon);
	$Large = $Folder."/Large";
	$Large = realpath($Large);
	$Thumbnails = $Folder."/Thumbnails";
	$Thumbnails = realpath($Thumbnails);
	$Folder = "../".$r_path.mb_convert_encoding($row_get_info['image_folder'],"UTF-8","HTML-ENTITIES");
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
		if($is_invoice == "false"){
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
		$IIcon = loadimage($MaxSize, $Fname, $Iname, $ISize, $IcTemp, $IType, $Icon, $IcWidth, $IcHeight, false, true, $use_ftp, $conn_id);
		ini_restore ("memory_limit");
		if($IIcon[0] === true){
			$ILarge = array();
			$IThumb = array();
			ini_set("memory_limit","200M");
			$ILarge = loadimage($MaxSize, $Fname, $Iname, $ISize, $LTemp, $IType, $Large, $LWidth, $LHeight, false, true, $use_ftp, $conn_id);
			$IThumb = loadimage($MaxSize, $Fname, $Iname, $ISize, $TTemp, $IType, $Thumbnails, $TWidth, $THeight, false, true, $use_ftp, $conn_id);
			ini_restore ("memory_limit");
			if($ILarge[0] === true && $IThumb[0] === true){
				if($use_ftp === true){
					ftp_put($conn_id, $Folder."/".$Iname, $ITemp, FTP_BINARY);
				} else {
					copy($ITemp, $Folder."/".$Iname);
				}
				unlink($ITemp);
				if($is_invoice == "false"){
					$upd = "UPDATE `photo_event_images` SET `image_tiny` = '$Iname', `image_small` = '$Iname', `image_large` = '$Iname' WHERE `image_id` = '$IId'";
				} else {
					$query_get_info = "SELECT `invoice_id` FROM `orders_invoice` WHERE `invoice_enc` = '$is_invoice'";
					$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
					$row_get_info = mysql_fetch_assoc($get_info);
					$InvId = $row_get_info['invoice_id'];
					$upd = "UPDATE `orders_invoice_photo` SET `invoice_image_image` = '$Iname' WHERE `invoice_id` = '$InvId' AND `image_id` = '$IId'";
				}
				$upd_info = mysql_query($upd, $cp_connection) or die(mysql_error());
			?>
<script type="text/javascript">
			window.opener.document.getElementById('form_action_form').submit();
			window.close();
			</script>
<?		}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Photo Express Digital Studio</title>
<? if($loginsession[1] < 10){ ?>
<link href="/control_panel/stylesheet/PhotoExpressAdmin.css" rel="stylesheet" type="text/css" />
<? } else { print '<link href="/control_panel/stylesheet/PhotoExpress.css" rel="stylesheet" type="text/css" />';}?>
<script type="text/javascript" src="/javascript/center_window.js"></script>
</head>
<body onLoad="center_window();">
<div id="Content" style="width:300px; height:150px; text-align:center;">
  <div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
    <h2>Image Updater </h2>
  </div>
  <div id="File_Loader">
    <? if($IIcon[0] === false || $ILarge[0] === false || $IThumb[0] === false){
  	echo "<p style=\"background-color:#FFFFFF; color:#CC0000; font-align:left;\">";
		if($IIcon[0] === false) echo $IIcon[1]."<br />";
		if($ILarge[0] === false) echo $ILarge[1]."<br />";
		if($IThumb[0] === false) echo $IThumb[1]."<br />";
		echo "</p>";
  } ?>
    <form method="post" action="<? echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" name="Image_Updater" id="Image_Updater" enctype="multipart/form-data">
      <p><? echo $row_get_info['image_tiny']; ?></p>
      <p>
        <input type="file" name="Image" id="Image" />
        <input type="hidden" name="Controller" id="Controller" value="Save" />
        <img src="/control_panel/images/btn_update.jpg" width="131" height="25" onClick="javascript:document.getElementById('Image_Updater').submit();document.getElementById('File_Loader').style.display='none';document.getElementById('Message').style.display='block';" style="cursor:pointer" /> </p>
    </form>
    <p align="center"><a href="javascript:window.close();">Close Window</a></p>
  </div>
  <div id="Message" style="display:none">
    <p style="background-color:#FFFFFF; margin:5px; padding:5px; font-align:left;">Please
      Wait......<br />
      Your new image is being uploaded. </p>
  </div>
</div>
</body>
</html>
