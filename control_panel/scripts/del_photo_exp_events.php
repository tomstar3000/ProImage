<?php

include $r_path.'security.php';
$items = array();
$items = $_POST['Events_items'];
function delete_vals($value){
	global $cp_connection, $use_ftp, $ftp_server, $ftp_user_name, $ftp_user_pass;
	
	$query_get_groups = "SELECT `group_id` FROM `photo_event_group` WHERE `event_id` = '$value'";
	$get_groups = mysql_query($query_get_groups, $cp_connection) or die(mysql_error());
	
	while($row_get_groups = mysql_fetch_assoc($get_groups)){	
		$tempid = $row_get_groups['group_id'];
		$upd= "UPDATE `photo_event_group` SET `group_use` = 'y' WHERE `group_id` = '$tempid'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		
		$upd= "UPDATE `photo_event_images` SET `image_active` = 'y' WHERE `group_id` = '$tempid'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	} 

	$upd= "UPDATE `photo_event` SET `event_use` = 'y' WHERE `event_id` = '$value'";
	$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `photo_event`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
}
if(count($items)>0){
	foreach ($items as $key => $value){
		delete_vals($value);
	}
}
?>