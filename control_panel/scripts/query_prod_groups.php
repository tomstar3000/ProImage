<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete"){
	include $r_path.'scripts/del_prod_groups.php';
}
?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
</div>
<div>
  <?php
	$query_get_groups = "SELECT * FROM `prod_link_prod_group` INNER JOIN `prod_groups` ON `prod_groups`.`group_id` = `prod_link_prod_group`.`group_id` WHERE  `prod_id` = '$PId'";
	$get_groups = mysql_query($query_get_groups, $cp_connection) or die(mysql_error());
	$row_get_groups = mysql_fetch_assoc($get_groups);
	$totalRows_get_groups = mysql_num_rows($get_groups);

	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Group Name";
	$parents = array();
	$i_array = array();
	do{
		$count = count($i_array);
		$i_array[$count][0] = $row_get_groups['link_prod_group_id'];
		$i_array[$count][1] = $row_get_groups['group_id'];
		$i_array[$count][2] = $row_get_groups['group_name'];
		find_parents($row_get_groups['group_id'],0,'prod_groups','group_part_id','group_id');
	} while ($row_get_groups = mysql_fetch_assoc($get_groups)); 
	include $r_path.'scripts/fnct_sort_items.php';
	$records = sort_groups($parents,$i_array,"group_name","prod_groups","group_id","group_part_id",true);
	$records[0][0] = $records[0];
	$records[0][1] = false;
	$records[0][2] = false;
	mysql_free_result($get_groups);
	
	build_record_5_table('Groups','Groups',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Group(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
?>
</div>
