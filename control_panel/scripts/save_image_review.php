<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$IId = array();
$IFile = array();
$IFolder = array();
$IName = array();
$IDesc = array();
$EId = $path[2];
$GId = $path[4];
$IId = (isset($_POST['Id'])) ? $_POST['Id'] : array();
$IFile = (isset($_POST['File'])) ? $_POST['File'] : array();
$IFolder = (isset($_POST['Folder'])) ? $_POST['Folder'] : array();
$IName = (isset($_POST['Name'])) ? $_POST['Name'] : array();
if(isset($_POST['Controller']) && ($_POST['Controller'] == "SaveComplete" || $_POST['Controller'] == "SaveLoad")){
	if($cont == "review"){
		foreach($IId as $k => $v){
			$name = clean_variable($IName[$k],true);
			$text = clean_variable($_POST['Description_'.$k],'Store');
			$upd = "UPDATE `photo_event_images` SET `image_name` = '$name',`image_desc` = '$text' WHERE `image_id` = '$v'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		}
		if($_POST['Controller'] == "SaveComplete"){
			$cont = "query";
		} else {
			$cont = "upload";
		}
	}
} else {
	if($cont != "add"){
		$time = clean_variable($_GET['time'],true);
		$query_get_info = "SELECT * FROM `photo_event_images` WHERE `group_id` = '$GId' AND `image_time` > '$time'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		do{
			array_push($IId,$row_get_info['image_id']);
			array_push($IFile,$row_get_info['image_tiny']);
			array_push($IFolder,$row_get_info['image_folder']);
			array_push($IName,$row_get_info['image_name']);
			array_push($IDesc,$row_get_info['image_desc']);
		} while($row_get_info = mysql_fetch_assoc($get_info));
		
		mysql_free_result($get_info);
	}
}
?>