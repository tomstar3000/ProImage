<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_record_5_table.php';
require_once $r_path.'scripts/fnct_format_date.php';
$date = $path[2];
$lowdate = date("Y-m-d H:i:s",mktime(0,0,0,substr($date,5,2),substr($date,8,2),substr($date,0,4)));
$highdate = date("Y-m-d H:i:s",mktime(0,0,0,substr($date,5,2),(substr($date,8,2)+1),substr($date,0,4)));
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")include $r_path.'scripts/del_web_event_con.php';
?>

<div id="Form_Header">
 <div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('','Web,Evnt,<? echo $date; ?>','add','<? echo $sort; ?>','<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,2)); ?>','calendar','<? echo $sort; ?>','<? echo $rcrd; ?>');" /></div>
 <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
 <input type="hidden" name="Month" id="Month" value="<? echo substr($date,5,2); ?>">
 <input type="hidden" name="Year" id="Year" value="<? echo substr($date,0,4); ?>">
 <p>Events For <? echo format_date($date,"DayLong",false,true,false); ?></p>
</div>
<div id="Div_Records">
 <?
$query_get_info = "SELECT * FROM `web_events` WHERE `event_start` >= '$lowdate' AND `event_start` < '$highdate' ORDER BY `event_start`, `event_name` ASC";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());

$records = array();
$headers = array();
$sortheaders = false;
$div_data = false;
$drop_downs = false;
$headers[0] = "Event Name";
$headers[1] = "Starts";
$headers[2] = "Ends";

while ($row_get_info = mysql_fetch_assoc($get_info)){
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = false;
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_info['event_id'];
	$records[$count][2] = $row_get_info['event_name'];
	$records[$count][3] = format_date($row_get_info['event_start'],"Short","Standard",true,true); 
	$records[$count][4] = format_date($row_get_info['event_end'],"Short","Standard",true,true); 
}

mysql_free_result($get_info);
build_record_5_table('Events','Events',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Event(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);
?>
</div>
