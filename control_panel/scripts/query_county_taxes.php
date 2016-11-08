<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")include $r_path.'scripts/del_county_taxes.php'; ?>
<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('','BillShip,Count','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
  <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>County Taxes List </p>
</div>
<div id="Div_Records">
  <?php	
	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Zip Code";
	$headers[1] = "Tax Percent";
	
	$query_get_county_taxes = "SELECT * FROM `billship_tax_county` ORDER BY `tax_count_zip` ASC";
	$get_county_taxes = mysql_query($query_get_county_taxes, $cp_connection) or die(mysql_error());
	while ($row_get_county_taxes = mysql_fetch_assoc($get_county_taxes)){
		$count = count($records);
		$records[$count][0][0] = false;
		$records[$count][0][1] = false;
		$records[$count][0][2] = false;
		$records[$count][1] = $row_get_county_taxes['tax_count_id'];
		$records[$count][2] = $row_get_county_taxes['tax_count_zip'];
		$records[$count][3] = $row_get_county_taxes['tax_count_percent']."%";
	} 
	mysql_free_result($get_county_taxes);
	build_record_5_table('County_Taxes','County Taxes',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('County Tax(es)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);
?>
</div>
