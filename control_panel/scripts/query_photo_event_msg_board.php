<? if(!isset($r_path)){$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++ )$r_path .= "../";}
$EId = $path[2];
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_3_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete") include $r_path.'scripts/del_photo_event_msg_board.php'; ?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Message Board List</h2>
 <p id="Add"><a href="#" onclick="javascript:set_form('','<? echo implode(',',$path); ?>','add','<? echo $sort; ?>','<? echo $rcrd; ?>');">Add</a></p>
</div>
<div id="Div_Records">
 <?
 $query_get_info = "SELECT `event_num` 
	FROM `photo_event`
	WHERE `event_id` = '$EId'
	LIMIT 0,1";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
 
$FavCode = $row_get_info['event_num'].$CHandle;

mysql_free_result($get_info);

$query_get_info = "SELECT `photo_cust_favories`.*, `photo_cust_favories_message`.`fav_message`, `photo_cust_favories_message`.`fav_message_id`
	FROM `photo_cust_favories`
	LEFT JOIN `photo_cust_favories_message`
		ON (`photo_cust_favories_message`.`fav_id` = `photo_cust_favories`.`fav_id`
			OR `photo_cust_favories_message`.`fav_id` IS NULL)
	WHERE `fav_code` = '$FavCode'
	ORDER BY `photo_cust_favories_message`.`fav_date` DESC";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
$totalRows_get_info = mysql_num_rows($get_info);

$records = array();
$headers = array();
$sortheaders = false;
$div_data = array();
$drop_downs = false;
$headers[0] = "E-mail";
$headers[1] = "Date";
do{
	$count = count($records);
	$records[$count][0] = false;
	$records[$count][1] = $row_get_info['fav_id'].".".$row_get_info['fav_message_id'];
	$records[$count][2] = $row_get_info['fav_email'];
	$records[$count][3] = format_date($row_get_info['fav_date'],"Standard",false,true,false);
	$div_data[$count] = $row_get_info['fav_message'];
} while ($row_get_info = mysql_fetch_assoc($get_info));

mysql_free_result($get_info);
	build_record_3_table('Message_Board','Message Board',$headers,$sortheaders,$records,$div_data,$drop_downs,'Delete Message(s)','Delete','Delete',false,false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);
?>
</div>
