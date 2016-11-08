<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
define("Aurigma Loading", true);
$EId = $path[2];
if($cont == "add" || $cont == "massupload"){
	if(count($path) > 4){
		$PGroupId = $path[(count($path)-1)];
	} else {
		$PGroupId = 0;
	}
} else {
	if(count($path) > 4){
		$PGroupId = $path[(count($path)-2)];
	} else {
		$PGroupId = 0;
	}
}
if(count($path)<5 || $cont == "massupload"){
	$query_get_info = "SELECT `event_num`, `cust_fname`, `cust_lname`, `cust_handle` FROM `photo_event`  INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` WHERE `event_id` = '$EId'";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$row_get_info = mysql_fetch_assoc($get_info);
	
	$Name = $row_get_info['cust_handle'];
	$Event = str_replace(" ","_",$row_get_info['event_num']);
	$Group = false;
} else {
	if($Group === false){
		if(count($path) > 4){
			$GId = $path[4];
		} else {
			$GId = $path[4];
		}
	} else {
		if(count($path) > 4){
			$GId = $path[(count($path)-1)];
		} else {
			$GId = $path[4];
		}
	}
	
	$query_get_info = "SELECT `group_name`, `event_num`, `cust_fname`, `cust_lname`, `cust_handle` FROM `photo_event_group` INNER JOIN `photo_event` ON `photo_event`.`event_id` = `photo_event_group`.`event_id` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` WHERE `group_id` = '$GId'";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$row_get_info = mysql_fetch_assoc($get_info);
	
	$Name = $row_get_info['cust_handle'];
	$Event = str_replace(" ","_",$row_get_info['event_num']);
	//$Group = str_replace(" ","_",$row_get_info['group_name']);
	$Group = $row_get_info['group_name'];
}
mysql_free_result($get_info);
?>
