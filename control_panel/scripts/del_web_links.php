<?
include $r_path.'security.php';
$items = array();
$items = $_POST['Links_items'];
function delete_vals($value){
	global $cp_connection;
	$del= "DELETE FROM `web_links` WHERE `web_link_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `web_links`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
}
if(count($items)>0)	foreach ($items as $key => $value) delete_vals($value);
?>