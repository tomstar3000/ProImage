<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$Event = (isset($_POST['Event'])) ? clean_variable($_POST['Event'],true) : '0';
$Group = (isset($_POST['Group'])) ? $_POST['Group'] : array("0");
$remove = false;
foreach($Group as $k => $v){
	if($v == "0" || $remove == true){
		unset($Group[$k]);
		$remove = true;
	} else {
		$Group[$k] = clean_variable($v,true);
	}
}
$Images = (isset($_POST['Image_items'])) ? $_POST['Image_items'] : array();

if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && $Group != '0'){	
	$Group = $Group[(count($Group)-1)];
	foreach($Images as $v){
		$upd = "UPDATE `photo_event_images` SET `group_id` = '$Group' WHERE `image_id` = '$v'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	echo '<script type="text/JavaScript">
	document.location.href="'.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'].'";
	</script>';
}
?>