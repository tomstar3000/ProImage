<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_record_5_table.php';
require_once $r_path.'scripts/fnct_format_phone.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")include $r_path.'scripts/del_web_event_con.php';
?>

<div id="Form_Header">
 <div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('','Web,Evnt_Cont','add','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
 <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
 <p>Event Contact List </p>
</div>
<div id="Div_Records">
 <?
$query_get_info = "SELECT * FROM `web_event_contacts` ORDER BY `event_con_lname`, `event_con_fname` ASC";
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
	$records[$count][1] = $row_get_info['event_con_id'];
	$records[$count][2] = $row_get_info['event_con_lname'].", ".$row_get_info['event_con_fname'];
	$div_data[$count] = "";
	if($row_get_info['event_con_phone'] != 0){
		$div_data[$count] .= "p) ".phone_number($row_get_info['event_con_phone'])."<br />";
	}
	if($row_get_info['event_con_cell'] != 0){
		$div_data[$count] .= "c) ".phone_number($row_get_info['event_con_cell'])."<br />";
	}
	if($row_get_info['event_con_fax'] != 0){
		$div_data[$count] .= "f) ".phone_number($row_get_info['event_con_fax'])."<br />";
	}
	if($row_get_info['event_con_email']){
		$div_data[$count] .= "<a href=\"mailto:".$row_get_info['event_con_email']."\">".$row_get_info['event_con_email']."</a><br />";
	}
}

mysql_free_result($get_info);
build_record_5_table('Event_Contacts','Event Contacts',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Event Contact(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);
?>
</div>
