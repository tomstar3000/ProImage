<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete"){
	include $r_path.'scripts/del_prod_sel_group.php';
}
?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
</div>
<div>
  <?php
	$query_get_selection = "SELECT `prod_link_prod_sel_group`.`link_prod_sel_group_id`, `prod_selections`.* FROM `prod_selections` INNER JOIN `prod_link_prod_sel_group` ON `prod_link_prod_sel_group`.`sel_id` = `prod_selections`.`sel_id` WHERE `prod_link_prod_sel_group`.`prod_id` = '$PId' ORDER BY `sel_name` ASC";
	$get_selection = mysql_query($query_get_selection, $cp_connection) or die(mysql_error());
	$row_get_selection = mysql_fetch_assoc($get_selection);
	$totalRows_get_selection = mysql_num_rows($get_selection);

	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Selection Group Name";
	do{
		$count = count($records);
		$records[$count][0][0] = false;
		$records[$count][0][1] = false;
		$records[$count][0][2] = false;
		$records[$count][1] = $row_get_selection['link_prod_sel_group_id'];
		$records[$count][2] = $row_get_selection['sel_name'];
	} while ($row_get_selection = mysql_fetch_assoc($get_selection)); 
	
	mysql_free_result($get_selection);

	build_record_5_table('Selection Groups','Selection Groups',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Selection Group(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
?>
</div>
