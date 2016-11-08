<?
include $r_path.'security.php';
$items = array();
$items = $_POST['Web_Pages_items'];
function delete_vals($value){
	global $cp_connection;
	$del= "DELETE FROM `web_review_navigation` WHERE `nav_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$query_get_page = "SELECT `page_id` FROM `web_review_pages` WHERE `nav_id` = '$value'";
	$get_page = mysql_query($query_get_page, $cp_connection) or die(mysql_error());
	$row_get_page = mysql_fetch_assoc($get_page);
	
	$del= "DELETE FROM `web_review_pages` WHERE `nav_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$page_id = $row_get_page['page_id'];
	
	$del= "DELETE FROM `web_review_page_text` WHERE `page_id` = '$page_id'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	mysql_free_result($get_page);
	
	$optimize = "OPTIMIZE TABLE `web_review_navigation`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `web_review_pages`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `web_review_page_text`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
}
if(count($items)>0)	foreach ($items as $key => $value) delete_vals($value);
?>