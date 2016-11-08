<?php if(isset($r_path)===false){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
require_once($r_path.'scripts/fnct_record_5_table.php');
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")require_once($r_path.'scripts/del_photo_group.php');
if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
} else {
	$Sort = "Group Name";
	$Order = "ASC";
}
if(count($path) > 4){
	$EId = $path[2];
	$PGroupId = $path[(count($path)-1)];
	$header = "Sub-Groups";
} else {
	$PGroupId = 0;
	$header = "Groups";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Group Name"){
	$Sort_val = " ORDER BY `group_name` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Imgs"){
	$Sort_val = " ORDER BY `image_count` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Grps"){
	$Sort_val = " ORDER BY `group_count` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} ?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Listed <? echo $header; ?> </h2>
 <p id="Add"><a href="#" onclick="javascript:set_form('','<?php echo implode(",",$path); ?>','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');">Add</a></p>
</div>
<div>
 <?php
$query_get_info = "SELECT `photo_event_group`.`group_id`,`photo_event_group`.`group_name`, COUNT(`photo_event_images`.`image_id`) AS `image_count`, (SELECT count(`photo_event_sub_group`.`group_id`) 
		FROM `photo_event_group` AS `photo_event_sub_group`
		WHERE `photo_event_sub_group`.`parnt_group_id` = `photo_event_group`.`group_id` 
			AND `photo_event_sub_group`.`group_use` = 'y') AS `group_count`
	FROM `photo_event_group` 
	LEFT JOIN `photo_event_images`
		ON (`photo_event_images`.`group_id` = `photo_event_group`.`group_id`
		OR `photo_event_images`.`group_id` IS NULL)
		AND `photo_event_images`.`image_active` = 'y'
	WHERE `event_id` = '$EId'
		AND `parnt_group_id` = '$PGroupId'
		AND `group_use` = 'y'
	GROUP BY `photo_event_group`.`group_id`
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
$headers[0] = "Group Name";
$headers[1] = "Imgs";
if(count($path)<7)$headers[2] = "Grps";
do{
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = false;
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_info['group_id'];
	$records[$count][2] = $row_get_info['group_name'];
	$records[$count][3] = $row_get_info['image_count'];
	if(count($path)<7)$records[$count][4] = $row_get_info['group_count'];
} while ($row_get_info = mysql_fetch_assoc($get_info)); 

mysql_free_result($get_info);
$temp_path = $path;

build_record_5_table('Groups',$header,$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Move Group(s)','Move','Move','btn_moveto.jpg'),array('Delete Group(s)','Delete','Delete','btn_delete.jpg')), false, "100%", "0", "0","0", false, false,"FFFFFF","EEEEEE","DDDDDD","EFEFEF","CDCDCD",true,'btn_select_all.jpg', 'btn_deselect.jpg');
?>
</div>
