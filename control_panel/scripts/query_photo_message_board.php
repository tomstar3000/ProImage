<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_record_5_table.php';
require_once $r_path.'scripts/fnct_format_phone.php';
if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
} else {
	$Sort = "Event Name";
	$Order = "ASC";
}

$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Event Name"){
	$Sort_val = " ORDER BY `event_name` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Event Number"){
	$Sort_val = " ORDER BY `event_num` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Guestbook Size"){
	$Sort_val = " ORDER BY `questbook_count` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} ?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Event Preset Discount Codes</h2>
</div>
<? $query_get_records = "SELECT `photo_event`.`event_id`, `photo_event`.`event_name`,`photo_event`.`event_num`, COUNT(`photo_quest_book`.`questbook_id`) AS `questbook_count` FROM `photo_event` INNER JOIN `photo_quest_book` ON `photo_quest_book`.`event_id` = `photo_event`.`event_id` WHERE `photo_event`.`cust_id` = '$CustId' AND `photo_event`.`event_use` = 'y' GROUP BY `photo_event`.`event_id`".$Sort_val;
$get_records = mysql_query($query_get_records, $cp_connection) or die(mysql_error());
$row_get_records = mysql_fetch_assoc($get_records);
$totalRows_get_records = mysql_num_rows($get_records);

$records = array();
$headers = array();
$sortheaders = array();
$sortheaders[0] = $Sort;
$sortheaders[1] = $Order;
$div_data = false;
$drop_downs = false;
$headers[0] = "Event Name";
$headers[1] = "Event Number";
$headers[2] = "Guestbook Size";
do{
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = false;
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_records['event_id'];
	$records[$count][2] = $row_get_records['event_name'];
	$records[$count][3] = $row_get_records['event_num'];
	$records[$count][4] = $row_get_records['questbook_count'];
	
} while ($row_get_records = mysql_fetch_assoc($get_records)); 

mysql_free_result($get_records);

build_record_5_table('Guestbook','Guestbook',$headers,$sortheaders,$records,$div_data,$drop_downs,false,false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5,false);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);

?>
