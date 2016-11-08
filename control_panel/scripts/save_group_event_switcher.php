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
$Groups = (isset($_POST['Groups_items'])) ? $_POST['Groups_items'] : array();
function findChildrenGroups($ID,$Event){
	global $cp_connection;
	$query_get_info = "SELECT `group_id`
		FROM `photo_event_group`
		WHERE `parnt_group_id` = '$ID' AND `group_use` = 'y'";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$total_get_info = mysql_num_rows($get_info);
	if($total_get_info > 0){
		while($row_get_info = mysql_fetch_assoc($get_info)){
			$v = $row_get_info['group_id'];
			
			$upd = "UPDATE `photo_event_group` SET `event_id` = '$Event' WHERE `group_id` = '$v'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
			findChildrenGroups($v,$Event);
		}
	} else {
		return;
	}
}
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && $Event != '0'){
	$parntGroupId = $Group[(count($Group)-1)];
	foreach($Groups as $v){
		if($v != $parntGroupId){
			$upd = "UPDATE `photo_event_group` SET `event_id` = '$Event', `parnt_group_id` = '$parntGroupId' WHERE `group_id` = '$v'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
			findChildrenGroups($v,$Event);
		}
	}
	echo '<script type="text/JavaScript">
	document.location.href="'.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'].'";
	</script>';
}
?>