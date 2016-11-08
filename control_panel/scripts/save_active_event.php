<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++) $r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

if($cont == "add")$CPId = $path[count($path)-1];
$Handle = (isset($_POST['Handle'])) ? clean_variable($_POST['Handle'],true) : '';
$Event = (isset($_POST['Event_Number'])) ? clean_variable($_POST['Event_Number'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && $cont == 'edit'){
	$query_get_info = "SELECT `event_id` FROM `photo_event` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` WHERE `event_num` = '$Event' AND `cust_handle` = '$Handle'";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$row_get_info = mysql_fetch_assoc($get_info);
	$totalRows_get_info = mysql_num_rows($get_info);
	if($totalRows_get_info > 0){
		$EId = $row_get_info['event_id'];
		$upd = "UPDATE `photo_event` SET `event_use` = 'y' WHERE `event_id` = '$EId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		
		$query_get_info = "SELECT `group_id` FROM `photo_event_group` WHERE `event_id` = '$EId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		while($row_get_info = mysql_fetch_assoc($get_info)){
			$GId = $row_get_info['group_id'];
			
			$upd = "UPDATE `photo_event_group` SET `group_use` = 'y' WHERE `group_id` = '$GId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());	
			
			$upd = "UPDATE `photo_event_images` SET `image_active` = 'y' WHERE `group_id` = '$GId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());		
		}
		$Error = "Event has been re-activated";
	} else {
		$Error = "Cannot find this event";
	}
}
?>