<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete"){
	include $r_path.'scripts/del_prod_specs.php';
}
?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
</div>
<div>
  <?php
	$query_get_specs = "SELECT * FROM `prod_link_prod_spec` WHERE `prod_id` = '$PId'";
	$get_specs = mysql_query($query_get_specs, $cp_connection) or die(mysql_error());
	$row_get_specs = mysql_fetch_assoc($get_specs);
	$totalRows_get_specs = mysql_num_rows($get_specs);

	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Spec Name";
	$headers[1] = "Quantity";
	$n = 0;
	$a = 0;
	$parents = array();
	$i_array = array();
	do{
		$count = count($i_array);
		$i_array[$count][0] = $row_get_specs['link_prod_spec_id'];
		$i_array[$count][1] = $row_get_specs['spec_id'];
		$i_array[$count][2] = $row_get_specs['link_prod_spec_name'];
		find_parents($row_get_specs['spec_id'],0,'prod_specs','spec_part_id','spec_id');
	} while ($row_get_specs = mysql_fetch_assoc($get_specs));
	include $r_path.'fnct_sort_items.php';
	$records = sort_groups($parents, $i_array, "spec_name", "prod_specs", "spec_id","spec_part_id", false);
	mysql_free_result($get_specs);
	$records[0][0] = $records[0];
	$records[0][1] = false;
	$records[0][2] = false;
	foreach($records as $key => $value){
		$id = $records[$key][1];
		
		$query_get_specs = "SELECT `link_prod_spec_qty` FROM `prod_link_prod_spec` WHERE `link_prod_spec_id` = '$id'";
		$get_specs = mysql_query($query_get_specs, $cp_connection) or die(mysql_error());
		$row_get_specs = mysql_fetch_assoc($get_specs);
		$totalRows_get_specs = mysql_num_rows($get_specs);
		
		$records[$key][3] = $row_get_specs['link_prod_spec_qty'];
		
		mysql_free_result($get_specs);
	}
	build_record_5_table('Specs','Specs',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Spec(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
?>
</div>
