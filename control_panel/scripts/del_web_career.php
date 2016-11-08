<?
include $r_path.'security.php';
$items = array();
$items = $_POST['Careers_items'];
function delete_vals($value){
	global $cp_connection;
	$del= "DELETE FROM `web_careers` WHERE `career_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$del= "DELETE FROM `web_career_req` WHERE `career_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `web_careers`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `web_career_req`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
}
if(count($items)>0){
	foreach ($items as $key => $value){
		delete_vals($value);
	}
}
?>