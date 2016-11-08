<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
define ("PhotoExpress Pro", true);
include $r_path.'security.php';
require_once($r_path.'../Connections/cp_connection.php');
require_once $r_path.'scripts/fnct_clean_entry.php';
$galleryPath = $r_path."../../photographers/";
$IconPath = "Icon/";
$ThumbnailsPath = "Thumbnails/";
$LargePath = "Large/";
$absGalleryPath = realpath($galleryPath)."/";

$Cust_id = $loginsession[0];
$Event = isset($_GET['event']) ? clean_variable(substr($_GET['event'],10),true) : "Test";
$Parnt_Group_id = isset($_GET['parnt_group_id']) ? clean_variable($_GET['parnt_group_id'],true) : "0";
if($use_ftp === true){
	$file_mrk = $absGalleryPath.$folderName."/".$eventFolderName."/ExtractDump.txt";
	$filePtr = fopen("ftp://".$ftp_user_name.":".$ftp_user_pass."@".$ftp_server.$file_mrk, "a");
} else {
	$file_mrk = $absGalleryPath.$folderName."/".$eventFolderName."/ExtractDump.txt";
	$filePtr = fopen($file_mrk, "a");
}
if($filePtr){
	$data = "--------------------------------------------\n";
	$data .= "Date = ".date("Y-m-d H:i:s")."\n";
	fwrite($filePtr, $data);
	fclose($filePtr);
}

mysql_select_db($database_cp_connection, $cp_connection);
if(isset($_GET['admin']) && $_GET['admin'] == "true"){
	$adminid = clean_variable($_GET['adminid'],true);
	$query_get_info = "SELECT `cust_customers`.`cust_id`, `photo_event`.`event_id`, `photo_event`.`event_num` FROM `cust_customers` INNER JOIN `photo_event` ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` WHERE `cust_customers`.`cust_id` = '$adminid' AND `event_id` = '$Event' AND `event_use` = 'y'";
} else {
	$query_get_info = "SELECT `cust_customers`.`cust_id`, `photo_event`.`event_id`, `photo_event`.`event_num` FROM `cust_customers` INNER JOIN `photo_event` ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` WHERE `cust_session` = '$Cust_id' AND `event_id` = '$Event' AND `event_use` = 'y'";
}
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
$total_get_info = mysql_num_rows($get_info);
if($total_get_info == 0){
	echo "Hacking";
	die();
}
$Cust_id = $row_get_info['cust_id'];
$Event_id = $row_get_info['event_id'];
$Event_name = $row_get_info['event_num'];

mysql_free_result($get_info);

function saveUploadedFiles(){
	global $Event,$Event_name,$Event_id,$Parnt_Group_id,$Cust_id,$galleryPath,$absGalleryPath,$LargePath,$ThumbnailsPath,$IconPath,$cp_connection,$ftp_server,$ftp_user_name,$ftp_user_pass, $use_ftp;
	
	$folderName = isset($_GET['folder']) ? clean_variable($_GET['folder'],true) : "Testing";
	$eventFolderName = $Event_name;
	if(isset($_GET['group'])){
		$GId = clean_variable($_GET['id'],true);
		$groupFolderName = clean_variable(urldecode($_GET['group']));
		$fileName = str_replace("\\","/",clean_variable($_POST['FileName_1']));
		$fileName = explode("/",$fileName);
		$fileName = $fileName[count($fileName)-1];
	}	else {
		if(isset($_POST['Group_Name'])){
			$groupFolderName = clean_variable($_POST['Group_Name']);
			$fileName = str_replace("\\","/",clean_variable($_POST['FileName_1']));
			$fileName = explode("/",$fileName);
			$fileName = $fileName[count($fileName)-1];
		} else {
			$groupFolderName = str_replace("\\","/",clean_variable($_POST['FileName_1']));
			$groupFolderName = explode("/",$groupFolderName);
			$fileName = $groupFolderName[count($groupFolderName)-1];
			$groupFolderName = $groupFolderName[count($groupFolderName)-2];
			if($groupFolderName == "") $groupFolderName = "Unamed Group";
		}
		
		$folder_replace = array("\\","/",":","*","?","<",">","|","'","#");
		$folder_with = array("","","","","","","","","","");
		$groupFolderName = str_replace($folder_replace,$folder_with,$groupFolderName);
		
		$query_get_group = "SELECT `group_id` FROM `photo_event_group` WHERE `event_id` = '$Event_id' AND `group_name` = '$groupFolderName' AND `parnt_group_id` = '$Parnt_Group_id' ORDER BY `group_id` DESC LIMIT 0,1";
		$get_group = mysql_query($query_get_group, $cp_connection) or die(mysql_error());
		$row_get_group = mysql_fetch_assoc($get_group);
		$total_get_group = mysql_num_rows($get_group);
		
		if($total_get_group == 0){
			$add = "INSERT INTO `photo_event_group` (`event_id`,`parnt_group_id`,`group_name`,`group_use`) VALUES ('$Event_id','$Parnt_Group_id','$groupFolderName','y')";
			$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
		
			$query_get_group = "SELECT `group_id` FROM `photo_event_group` WHERE `event_id` = '$Event_id' AND `group_name` = '$groupFolderName' AND `parnt_group_id` = '$Parnt_Group_id' ORDER BY `group_id` DESC LIMIT 0,1";
			$get_group = mysql_query($query_get_group, $cp_connection) or die(mysql_error());
			$row_get_group = mysql_fetch_assoc($get_group);
			$total_get_group = mysql_num_rows($get_group);
		} else {
			$GId = $row_get_group['group_id'];
			$upd= "UPDATE `photo_event_group` SET `group_use` = 'y' WHERE `group_id` = '$GId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		}
		$GId = $row_get_group['group_id'];
	}	
	$ImagePath = substr(md5($folderName),5,10)."/";
	if($use_ftp === true){
		$conn_id = ftp_connect($ftp_server);
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	} 
  if(!is_dir($absGalleryPath.$folderName)){
		if($use_ftp === true){
			ftp_mkdir($conn_id, $absGalleryPath.$folderName);
		} else {
			mkdir($absGalleryPath.$folderName);	
		}
	}
	if(!is_dir($absGalleryPath.$folderName."/".$eventFolderName)){
		if($use_ftp === true){
			ftp_mkdir($conn_id, $absGalleryPath.$folderName."/".$eventFolderName);
		} else {
			mkdir($absGalleryPath.$folderName."/".$eventFolderName);	
		}
	}
	if(!is_dir($absGalleryPath.$folderName."/".$eventFolderName."/".$groupFolderName)){
		if($use_ftp === true){
			ftp_mkdir($conn_id, $absGalleryPath.$folderName."/".$eventFolderName."/".$groupFolderName);
			ftp_mkdir($conn_id, $absGalleryPath.$folderName."/".$eventFolderName."/".$groupFolderName."/".$ImagePath);
			ftp_mkdir($conn_id, $absGalleryPath.$folderName."/".$eventFolderName."/".$groupFolderName."/".$LargePath);
			ftp_mkdir($conn_id, $absGalleryPath.$folderName."/".$eventFolderName."/".$groupFolderName."/".$ThumbnailsPath);
			ftp_mkdir($conn_id, $absGalleryPath.$folderName."/".$eventFolderName."/".$groupFolderName."/".$IconPath);
		} else {	
			mkdir($absGalleryPath.$folderName."/".$eventFolderName."/".$groupFolderName);
			mkdir($absGalleryPath.$folderName."/".$eventFolderName."/".$groupFolderName."/".$ImagePath);
			mkdir($absGalleryPath.$folderName."/".$eventFolderName."/".$groupFolderName."/".$LargePath);
			mkdir($absGalleryPath.$folderName."/".$eventFolderName."/".$groupFolderName."/".$ThumbnailsPath);
			mkdir($absGalleryPath.$folderName."/".$eventFolderName."/".$groupFolderName."/".$IconPath);
		}
	}	

	$absImagePath = realpath($absGalleryPath.$folderName."/".$eventFolderName."/".$groupFolderName."/".$ImagePath)."/";
	$absIconPath = realpath($absGalleryPath.$folderName."/".$eventFolderName."/".$groupFolderName."/".$IconPath)."/";
	$absThumbnailsPath = realpath($absGalleryPath.$folderName."/".$eventFolderName."/".$groupFolderName."/".$ThumbnailsPath)."/";
	$absLargePath = realpath($absGalleryPath.$folderName."/".$eventFolderName."/".$groupFolderName."/".$LargePath)."/";
	
	if (!$_FILES["SourceFile_1"]['size'])	return;
	if (!$_FILES["Thumbnail1_1"]['size'])	return;
	if (!$_FILES["Thumbnail2_1"]['size'])	return;
	if (!$_FILES["Thumbnail3_1"]['size'])	return;
	
	if($filePtr){
		$data .= "************************\n";
		$data .= "****** Successful ******\n";
		$data .= "************************\n";
		$data .= "Groupname = ".$groupFolderName."\n";
		$data .= "FileName = ".$fileName."\n";
		$data .= "** Source **\n";
		$data .= "Temp = ".$_FILES["SourceFile_1"]['tmp_name']."\n";
		$data .= "Size = ".$_FILES["SourceFile_1"]['size']."\n";
		$data .= "Type = ".$_FILES["SourceFile_1"]['type']."\n";
		list($Width, $Height) = getimagesize($_FILES["SourceFile_1"]['tmp_name']);
		$data .= "Deminsions = ".$Width." x ".$Height."\n";		
		$data .= "** File 1 **\n";
		$data .= "Temp = ".$_FILES["Thumbnail1_1"]['tmp_name']."\n";
		$data .= "Size = ".$_FILES["Thumbnail1_1"]['size']."\n";
		$data .= "Type = ".$_FILES["Thumbnail1_1"]['type']."\n";
		list($Width, $Height) = getimagesize($_FILES["Thumbnail1_1"]['tmp_name']);
		$data .= "Deminsions = ".$Width." x ".$Height."\n";
		$data .= "** File 2 **\n";
		$data .= "Temp = ".$_FILES["Thumbnail2_1"]['tmp_name']."\n";
		$data .= "Size = ".$_FILES["Thumbnail2_1"]['size']."\n";
		$data .= "Type = ".$_FILES["Thumbnail2_1"]['type']."\n";
		list($Width, $Height) = getimagesize($_FILES["Thumbnail2_1"]['tmp_name']);
		$data .= "Deminsions = ".$Width." x ".$Height."\n";
		$data .= "** File 3 **\n";
		$data .= "Temp = ".$_FILES["Thumbnail3_1"]['tmp_name']."\n";
		$data .= "Size = ".$_FILES["Thumbnail3_1"]['size']."\n";
		$data .= "Type = ".$_FILES["Thumbnail3_1"]['type']."\n";
		list($Width, $Height) = getimagesize($_FILES["Thumbnail3_1"]['tmp_name']);
		$data .= "Deminsions = ".$Width." x ".$Height."\n";
		fwrite($filePtr, $data);
		fclose($filePtr);
	}
	
	if($use_ftp === true){
		ftp_put($conn_id, $absImagePath.$fileName, $_FILES["SourceFile_1"]['tmp_name'], FTP_BINARY);
		ftp_put($conn_id, $absIconPath.$fileName, $_FILES["Thumbnail1_1"]['tmp_name'], FTP_BINARY);
		ftp_put($conn_id, $absThumbnailsPath.$fileName, $_FILES["Thumbnail2_1"]['tmp_name'], FTP_BINARY);
		ftp_put($conn_id, $absLargePath.$fileName, $_FILES["Thumbnail3_1"]['tmp_name'], FTP_BINARY);
	} else {		
		copy($_FILES["SourceFile_1"]['tmp_name'], $absImagePath.$fileName);
		copy($_FILES["Thumbnail1_1"]['tmp_name'], $absIconPath.$fileName);
		copy($_FILES["Thumbnail2_1"]['tmp_name'], $absThumbnailsPath.$fileName);
		copy($_FILES["Thumbnail3_1"]['tmp_name'], $absLargePath.$fileName);
	}
	$size = $_FILES['SourceFile_1']['size']/1048576;
	$time = time();
	$folder = $galleryPath.$folderName."/".$eventFolderName."/".$groupFolderName."/".$ImagePath;
	$folder = str_replace("../","",$folder);
	$folder = clean_variable($folder, true);
	$fileName = clean_variable($fileName, true);
	$add = "INSERT INTO `photo_event_images` (`cust_id`,`group_id`,`image_tiny`,`image_small`,`image_large`,`image_folder`,`image_size`,`image_time`,`image_active`) VALUES ('$Cust_id','$GId','$fileName','$fileName','$fileName','$folder','$size','$time','y');";
	$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	
	$upd = "UPDATE `photo_event_group` SET `group_updated` = NOW() WHERE `group_id` = '$GId'";
	$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	
	$upd = "UPDATE `photo_event` SET `event_updated` = NOW() WHERE `event_id` = '$Event_id'";
	$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
}
saveUploadedFiles();
?>