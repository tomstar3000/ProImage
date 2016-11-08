<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);
//http://www.proimagesoftware.com/checkout/download.php?invoice=32b9e74c8f60958158eba8d1fa37297107e1cd7dca89a1678042477183b7ac3f
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_zip.php');
if(isset($_GET['invoice']))$encnum = clean_variable($_GET['invoice'],true);
$downloadnum = substr($encnum,32,-32);
$filesize = substr($encnum,-32);
if($filesize=="5ef059938ba799aaa845e1c2e8a762bd") {
	$filesize = "Large";
	$filename = "Low Res";
} else if($filesize=="07e1cd7dca89a1678042477183b7ac3f"){
	$filesize = "EncryptFolder";
	$filename = "Hi Res";
} else {
	$filesize = "Large";
	$filename = "Low Res";
}
$encnum = substr($encnum,0,32);
mysql_select_db($database_cp_connection, $cp_connection);
$filename = "digital_download_".$filename.".zip";
$size = 0;
$files = array();
$query_get_photo = "SELECT `photo_event_images`.* FROM `photo_event_images` INNER JOIN `orders_invoice_photo` ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` INNER JOIN `orders_invoice` ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` WHERE `orders_invoice`.`invoice_enc` = '$encnum' ORDER BY `image_tiny` ASC";
$get_photo = mysql_query($query_get_photo, $cp_connection) or die(mysql_error());
while($row_get_photo = mysql_fetch_assoc($get_photo)){
	$index = strrpos($row_get_photo['image_folder'], "/");
	$Folder = substr($row_get_photo['image_folder'],0,$index);
	$index = strrpos($Folder, "/");
	$Folder = substr($Folder,0,$index);
	if($filesize == "EncryptFolder"){
		array_push($files, $photographerFolder.$row_get_photo['image_folder'].$row_get_photo['image_tiny']);
		$size += (filesize($photographerFolder.$row_get_photo['image_folder'].$row_get_photo['image_tiny'])/1048576);
	} else {
		array_push($files, $photographerFolder.$Folder."/".$filesize."/".$row_get_photo['image_tiny']);
		$size += (filesize($photographerFolder.$Folder."/".$filesize."/".$row_get_photo['image_tiny'])/1048576);
	}
}
var_dump($files);
die();

$size += (50*count($files));
$size = round($size);
$dwnspd = 56/1024; // 56kb in megabytes
$ziper = new zipfile();

//ini_set("memory_limit",$size."M");
ini_set("memory_limit","500M");
ini_set("max_execution_time",round(($size/$dwnspd)));
set_time_limit(0);
$ziper->addFiles(array_slice($files,0,5), false);  //array of files, destination Directory

sleep (1);
header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=".$filename);
header("Pragma: no-cache");
header("Expires: 0");

echo $ziper->file();

ini_restore ("memory_limit");
ini_restore ("max_execution_time");
?>
