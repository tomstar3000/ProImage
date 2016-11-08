<?php
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$EId = $path[2];
if(count($path) > 5){
	$GId = (isset($path[(count($path)-1)]))?$path[(count($path)-1)]:'';
} else {
	$GId = (isset($path[4]))?$path[4]:'';
}
if(count($path) > 4){
	$PGroupId = $path[(count($path)-1)];
} else {
	$PGroupId = 0;
}
$GName = (isset($_POST['Group_Name'])) ? clean_variable($_POST['Group_Name'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && !isset($deactive) && $deactive != true){
	if($cont == "add"){
		$add = "INSERT INTO `photo_event_group` (`event_id`,`parnt_group_id`,`group_name`,`group_short`,`group_desc`,`group_updated`) VALUES ('$EId','$PGroupId','$GName','','',NOW());";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		
		$query_get_last = "SELECT `group_id` FROM `photo_event_group` WHERE `event_id` = '$EId' AND `parnt_group_id` = '$PGroupId' ORDER BY `group_id` DESC LIMIT 0,1";
		$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
		$row_get_last = mysql_fetch_assoc($get_last);
		$GId = $row_get_last['group_id'];
		array_push($path,$GId);
	} else if($cont == "edit"){
		$upd = "UPDATE `photo_event_group` SET `group_name` = '$GName',`group_short` ='',`group_desc` = '',`group_updated` = NOW() WHERE `group_id` = '$GId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `photo_event_group` WHERE `group_id` = '$GId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$Name = $row_get_info['cust_handle'];
		$Event = str_replace(" ","_",$row_get_info['event_num']);
		$GName = $row_get_info['group_name'];
		
		mysql_free_result($get_info);
	}
}
?>