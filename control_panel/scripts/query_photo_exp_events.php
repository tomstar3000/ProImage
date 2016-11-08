<? if(isset($r_path)===false){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
require_once($r_path.'scripts/fnct_record_5_table.php');
require_once($r_path.'scripts/fnct_format_date.php');
if(isset($_POST['Controller']) && $_POST['Controller'] == "Active") require_once($r_path.'scripts/del_photo_exp_events.php');
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
	$Sort_val = " ORDER BY `photo_event`.`event_name` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if($Sort == "Code"){
	$Sort_val = " ORDER BY `photo_event`.`event_num` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Date"){
	$Sort_val = " ORDER BY `photo_event`.`event_date` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Ends"){
	$Sort_val = " ORDER BY `photo_event`.`event_end` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Grps"){
	$Sort_val = " ORDER BY `group_count` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Imgs"){
	$Sort_val = " ORDER BY `image_count` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Used"){
	$Sort_val = " ORDER BY `total_size` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
}

?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" /> </div>
<div>
 <h3>Notice! These Events will be removed from the system in 15 days, and all images will be removed.</h3>
</div>
<div>
 <?
	
$thirtydate1 = date("Y-m-d H:i:s", mktime(0,0,0,date("m"),date("d")-15,date("Y")));
$query_get_info = "SELECT 
	DISTINCT COUNT(`photo_event_images`.`image_id`) AS `image_count`,
	(SELECT count(`group_id`) 
		FROM `photo_event_group` 
		WHERE `photo_event_group`.`event_id` = `photo_event`.`event_id` ) AS `group_count`,
	ROUND((SUM(`photo_event_images`.`image_size`)*100)/100) AS `total_size`,
	`photo_event`.`event_id`,
	`photo_event`.`event_name`,
	`photo_event`.`event_num`,
	`photo_event`.`event_date`,
	`photo_event`.`event_end`,
	`cust_customers`.`cust_handle`
	".$Addselect."
	FROM `photo_event`
	".$Innerjoin."
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event`.`cust_id`
	LEFT JOIN `photo_event_group`
		ON (`photo_event_group`.`event_id` = `photo_event`.`event_id`
		OR `photo_event_group`.`event_id` IS NULL)
	LEFT JOIN `photo_event_images`
		ON (`photo_event_images`.`group_id` = `photo_event_group`.`group_id`
		OR `photo_event_images`.`group_id` IS NULL)
	WHERE `photo_event`.`cust_id` = '$CustId'
		AND `event_use` = 'n'
		AND `event_updated` > '$thirtydate1'
	GROUP BY `photo_event`.`event_id`
	".$Sort_val;
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

$records = array();
$headers = array();
$sortheaders = array();
$sortheaders[0] = $Sort;
$sortheaders[1] = $Order;
$div_data = false;
$drop_downs = false;
$headers[0] = "Event Name";
$headers[1] = "Code";
$headers[2] = "Date";
$headers[3] = "Ends";
$headers[4] = "Grps";
$headers[5] = "Imgs";
$headers[6] = "Used";

do{
	$temp_id = $row_get_info['event_id'];
	
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = false;
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_info['event_id'];
	$records[$count][2] = ((strlen($row_get_info['event_name']) > 30) ?  substr($row_get_info['event_name'],0,30)."..." : $row_get_info['event_name']);
	$records[$count][3] = $row_get_info['event_num'];
	$records[$count][4] = format_date($row_get_info['event_date'],"Dash",false,true,false);
	$records[$count][5] = format_date($row_get_info['event_end'],"Dash",false,true,false);
	$records[$count][6] = $row_get_info['group_count'];
	$records[$count][7] = $row_get_info['image_count'];
	$records[$count][8] = (round($row_get_info['total_size']/1024*100)/100)." GB";
} while ($row_get_info = mysql_fetch_assoc($get_info)); 

mysql_free_result($get_info);

build_record_5_table('Events','Events',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Re-Activate Event(s)?\nOnce Re-activated, change the Event End date\nto prevent the event from expiring again.','Active','Active','btn_reactivate.jpg')), false, "100%", "0", "0","0", false, false, "FFFFFF", "EEEEEE", "DDDDDD","EFEFEF","CDCDCD", true, 'btn_select_all.jpg', 'btn_deselect.jpg',false,false,true,false,false,false);
?>
</div>
