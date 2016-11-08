<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
$items = array();
$items = $_POST['Event_Locations_items'];
function delete_vals($value, $accpt_decline){
	global $cp_connection;
	
	$del= "DELETE FROM `web_event_locations` WHERE `event_loc_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `web_event_locations`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
}
if(count($items)>0){
	foreach ($items as $key => $value){
		delete_vals($value, $accpt_decline);
	}
}
?>