<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")include $r_path.'scripts/del_web_event_loc.php';
?>

<div id="Form_Header">
 <div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('','Web,Evnt_Loc','add','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
 <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
 <p>Event Location List </p>
</div>
<div id="Div_Records">
 <?
$query_get_info = "SELECT * FROM `web_event_locations` ORDER BY `event_loc_name` ASC";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());

$records = array();
$headers = array();
$sortheaders = false;
$div_data = array();
$drop_downs = false;
$headers[0] = "Contact Name";

while ($row_get_info = mysql_fetch_assoc($get_info)){
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = false;
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_info['event_loc_id'];
	$records[$count][2] = $row_get_info['event_loc_name'];
	$div_data[$count] = "";
	if($row_get_info['event_loc_add']){
		$div_data[$count] .= $row_get_info['event_loc_add']." ".$row_get_info['event_loc_suite_apt']."<br />";
		if($row_get_info['event_loc_add_2'])	$div_data[$count] .= $row_get_info['event_loc_add_2']."<br />";
		$div_data[$count] .= $row_get_info['event_loc_city'].", ".$row_get_info['event_loc_state'].". ".$row_get_info['event_loc_zip']."<br />";
		if($row_get_info['event_loc_country']) $div_data[$count] .= $row_get_info['event_loc_country']."<br />";
	}
}

mysql_free_result($get_info);
build_record_5_table('Event_Locations','Event Locations',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Event Location(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);
?>
</div>
