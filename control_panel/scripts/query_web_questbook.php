<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
require_once ($r_path."scripts/fnct_format_date.php");
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete") include $r_path.'scripts/del_web_questbook.php';
?>
<div id="Form_Header">
  <div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<? echo implode(',',$path); ?>','add','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
  <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Guestbook List </p>
</div>
<div id="Div_Records">
<?
$query_get_records = "SELECT * 
	FROM `web_guestbook`
	ORDER BY `guestbook_date` DESC";
$get_records = mysql_query($query_get_records, $cp_connection) or die(mysql_error());

	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Name";
	$headers[1] = "Date";
	
	while ($row_get_records = mysql_fetch_assoc($get_records)){
		$count = count($records);
		$records[$count][0][0] = false;
		$records[$count][0][1] = false;
		$records[$count][0][2] = false;
		$records[$count][1] = $row_get_records['guestbook_id'];
		$records[$count][2] = $row_get_records['guestbook_fname']." ".$row_get_records['guestbook_lname'];
		$records[$count][3] = format_date($row_get_records['guestbook_date'],"Short",false,true,false);
	} 
	
	mysql_free_result($get_records);
	 
	build_record_5_table('Questbook','Questbook',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Questbook Entry(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
	if(is_array($rcrd)) $rcrd = implode(",",$rcrd);
?>
</div>