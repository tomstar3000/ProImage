<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")include $r_path.'scripts/del_state_taxes.php';
?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('','BillShip,State','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
  <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>State Tax List </p>
</div>
<div id="Div_Records">
  <?php	
	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "State";
	$headers[1] = "Tax Percent";
	
	$query_get_state_taxes = "SELECT * FROM `billship_tax_states` ORDER BY `tax_state` ASC";
	$get_state_taxes = mysql_query($query_get_state_taxes, $cp_connection) or die(mysql_error());
	while ($row_get_state_taxes = mysql_fetch_assoc($get_state_taxes)){
		$count = count($records);
		$records[$count][0][0] = false;
		$records[$count][0][1] = false;
		$records[$count][0][2] = false;
		$records[$count][1] = $row_get_state_taxes['tax_id'];
		$records[$count][2] = $row_get_state_taxes['tax_state'];
		$records[$count][3] = $row_get_state_taxes['tax_percent']."%";
	}
	
	mysql_free_result($get_state_taxes);
	build_record_5_table('State_Taxes','State Taxes',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('State Tax(es)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);
?>
</div>
