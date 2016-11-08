<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete") include $r_path.'scripts/del_web_news.php';
?>
<div id="Form_Header">
  <div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<? echo implode(',',$path); ?>','add','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
  <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>News List </p>
</div>
<div id="Div_Records">
<?
$query_get_records = "SELECT `web_news_info`.`news_info_id`, `web_news_info`.`news_info_header_1`, `web_review_navigation`.`nav_name` 
	FROM `web_news_info` 
	LEFT OUTER JOIN `web_review_navigation` 
		ON `web_review_navigation`.`nav_id` = `web_news_info`.`news_info_page`
	WHERE `news_info_press` = 'n'
	ORDER BY `web_review_navigation`.`nav_name`, `web_news_info`.`news_info_header_1` ASC";
$get_records = mysql_query($query_get_records, $cp_connection) or die(mysql_error());

	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "News Header";
	$headers[1] = "News Page";
	
	while ($row_get_records = mysql_fetch_assoc($get_records)){
		$count = count($records);
		$records[$count][0][0] = false;
		$records[$count][0][1] = false;
		$records[$count][0][2] = false;
		$records[$count][1] = $row_get_records['news_info_id'];
		$records[$count][2] = substr($row_get_records['news_info_header_1'],0,25);
		$records[$count][3] = $row_get_records['nav_name'];
	} 
	
	mysql_free_result($get_records);
	 
	build_record_5_table('Web_News','Website News',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete New(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
	if(is_array($rcrd)) $rcrd = implode(",",$rcrd);
?>
</div>