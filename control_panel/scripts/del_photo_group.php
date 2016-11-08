<?php

include $r_path.'security.php';
$items = array();
$items = $_POST['Groups_items'];
function delete_image_vals($value){
	global $cp_connection, $use_ftp, $ftp_server, $ftp_user_name, $ftp_user_pass;
	$query_get_folders = "SELECT `image_folder` FROM `photo_event_images` WHERE `group_id` = '$value' GROUP BY `group_id`";
	$get_folders = mysql_query($query_get_folders, $cp_connection) or die(mysql_error());
	$row_get_folders = mysql_fetch_assoc($get_folders);

	$index = strrpos($row_get_folders['image_folder'], "/");
	$Folder = substr($row_get_folders['image_folder'],0,$index);	
	
	$index = strrpos($Folder, "/");
	$Folder = substr($Folder,0,$index);
	$Folder = '../'.$r_path.$Folder;
	$Folder = realpath($Folder);
	if($use_ftp === true){
		$conn_id = ftp_connect($ftp_server);
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	}
	require_once($r_path.'scripts/fnct_ftp_rmv_all.php');
	if(is_dir($Folder) && strstr($Folder, 'photographers')!==false){
		if($use_ftp === true){
			ftp_rmAll($conn_id, $Folder);
		} else {
			rmdir($Folder);
		}
	}
	
	$upd= "UPDATE `photo_event_images` SET `image_active` = 'n' WHERE `group_id` = '$value'";
	$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	
	$upd= "UPDATE `photo_event_group` SET `group_use` = 'n' WHERE `group_id` = '$value'";
	$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	
	//$del= "DELETE FROM `photo_event` WHERE `sell_whare_id` = '$value'";
	//$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `photo_event_group`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
}
if(count($items)>0){
	foreach ($items as $key => $value){
		delete_image_vals($value);
	}
}
?>