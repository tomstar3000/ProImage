<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Groups_controller']) && $_POST['Groups_controller'] == "true"){
	include 'scripts/del_groups.php';
}
if(isset($_GET['id'])){
	$proj_id = $_GET['id'];
} else {
	$proj_id = 0;
}
ob_start();
?>
<div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?is_info=true&cont=add&projid=<?php echo $proj_id; ?>&<?php echo $_SERVER['QUERY_STRING']; ?>';" />
  <?php if($proj_id != 0){ ?>
  <?php include 'scripts/save_timesheet.php'; ?>
  &nbsp;&nbsp;&nbsp;<img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?is_info=true&cont=edit&id=<?php echo $timesheet_id; ?>&<?php echo $_SERVER['QUERY_STRING']; ?>';" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?sec=Product&btn=Group&id=".$GPId; ?>'" />
  <?php } ?>
</div>
<?php
$btn_add = ob_get_contents();
ob_end_clean(); ?>
<div id="Div_Records">
  <?php if($group_pid != 0){ ?>
  <div id="Info">
    <?php include 'forms/group_info.php'; ?>
  </div>
  <?php
}
$query_get_groups = "SELECT * FROM `prod_groups` WHERE `group_part_id` = '$group_pid'";
$get_groups = mysql_query($query_get_groups, $cp_connection) or die(mysql_error());
$row_get_groups = mysql_fetch_assoc($get_groups);
$totalRows_get_groups = mysql_num_rows($get_groups);

	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Group Name";
	$headers[1] = "Sub-Groups";
	$n = 0;
	do{
		$gid = $row_get_groups['group_id'];
		
		$query_get_record_count = "SELECT `group_id` FROM `prod_groups` WHERE `group_part_id` = '$gid'";
		$get_record_count = mysql_query($query_get_record_count, $cp_connection) or die(mysql_error());
		$row_get_record_count = mysql_fetch_assoc($get_record_count);
		$totalRows_get_record_count = mysql_num_rows($get_record_count);
		
		$records_count = $totalRows_get_record_count;
		
		mysql_free_result($get_record_count);
		
		$records[$n][0][0] = false;
		$records[$n][0][1] = false;
		$records[$n][0][2] = false;
		$records[$n][1] = $row_get_groups['group_id'];
		$records[$n][2] = $row_get_groups['group_name'];
		$records[$n][3] = $records_count;
		
		$n++;
	} while ($row_get_groups = mysql_fetch_assoc($get_groups)); 
	
	mysql_free_result($get_groups);
	
	build_record_5_table('Groups', 'Groups',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Group(s)', 'Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
?>
</div>
