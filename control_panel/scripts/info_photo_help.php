<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
	$r_path = "";
	for($n=0;$n<$count;$n++) $r_path .= "../";
}
$query_get_info = "SELECT `page_text_text` FROM `web_pages` INNER JOIN `web_page_text` ON `web_page_text`.`page_id` = `web_pages`.`page_id` WHERE `web_pages`.`page_id` = '2'";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
?>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
  <h2><strong>Event Manager Help</strong></h2>
</div>
<div>
  <? echo $row_get_info['page_text_text']; ?>
</div>
