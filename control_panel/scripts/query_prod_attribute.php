<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete"){
	include $r_path.'scripts/del_prod_attribute.php';
}
?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
</div>
<div>
  <?php
	$query_get_attributes = "SELECT `prod_link_prod_att`.*, `prod_attributes`.`att_name` FROM `prod_link_prod_att` INNER JOIN `prod_attributes` ON `prod_attributes`.`att_id` = `prod_link_prod_att`.`att_id` WHERE `prod_id` = '$PId'";
	$get_attributes = mysql_query($query_get_attributes, $cp_connection) or die(mysql_error());
	$row_get_attributes = mysql_fetch_assoc($get_attributes);
	$totalRows_get_attributes = mysql_num_rows($get_attributes);

	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Attribute Name";
	$headers[1] = "Quantity";
	$parents = array();
	$i_array = array();
	do{
		$count = count($i_array);
		$i_array[$count][0] = $row_get_attributes['link_prod_att_id'];
		$i_array[$count][1] = $row_get_attributes['att_id'];
		$i_array[$count][2] = ($row_get_attributes['link_prod_att_name'] == "") ? $row_get_attributes['att_name'] : $row_get_attributes['link_prod_att_name'];
		find_parents($row_get_attributes['att_id'],0,'prod_attributes','att_part_id','att_id');
	} while ($row_get_attributes = mysql_fetch_assoc($get_attributes)); 
	include $r_path.'fnct_sort_items.php';
	$records = sort_groups($parents, $i_array, "att_name", "prod_attributes", "att_id", "att_part_id", true);
	$records[0][0] = $records[0];
	$records[0][1] = false;
	$records[0][2] = false;
	mysql_free_result($get_attributes);
	
	foreach($records as $key => $value){
		$id = $records[$key][1];
		
		$query_get_attributes = "SELECT `link_prod_att_qty` FROM `prod_link_prod_att` WHERE `link_prod_att_id` = '$id'";
		$get_attributes = mysql_query($query_get_attributes, $cp_connection) or die(mysql_error());
		$row_get_attributes = mysql_fetch_assoc($get_attributes);
		$totalRows_get_attributes = mysql_num_rows($get_attributes);
		
		$records[$key][3] = $row_get_attributes['link_prod_att_qty'];
		
		mysql_free_result($get_attributes);
	}
	build_record_5_table('Attributes','Attributes',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Attribute(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
?>
</div>
