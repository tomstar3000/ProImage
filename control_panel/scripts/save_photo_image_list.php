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

$EId = $path[2];
$ImageId = (isset($_POST['Image_item'])) ? clean_variable($_POST['Image_item'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save"){	
	$upd = "UPDATE `photo_event` SET `event_image` = '$ImageId' WHERE `event_id` = '$EId'";
	$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	$cont = "view";
}
?>