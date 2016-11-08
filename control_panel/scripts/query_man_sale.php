<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete") include $r_path.'scripts/del_man_rep.php'; ?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
</div>
<div id="Div_Records">
  <?php
	$query_get_sale = "SELECT * FROM `sellers_sales` WHERE `sell_id` = '$man_id'";
	$get_sale = mysql_query($query_get_sale, $cp_connection) or die(mysql_error());
	$row_get_sale = mysql_fetch_assoc($get_sale);
	$totalRows_get_sale = mysql_num_rows($get_sale);

	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Name";
	$headers[1] = "Phone";
	$headers[2] = "Mobile";
	$headers[3] = "Fax";
	$headers[4] = "E-mail";
	
	do{
		$count = count($records);
		$records[$count][0][0] = false;
		$records[$count][0][1] = false;
		$records[$count][0][2] = false;
		$records[$count][1] = $row_get_sale['sell_sales_id'];
		$records[$count][2] = $row_get_sale['sell_sales_lname'].", ".$row_get_sale['sell_sales_fname'];
		$records[$count][3] = ($row_get_sale['sell_sales_phone'] > 0) ? phone_number($row_get_sale['sell_sales_phone']) : "&nbsp;";
		$records[$count][4] = ($row_get_sale['sell_sales_cell'] > 0) ? phone_number($row_get_sale['sell_sales_cell']) : "&nbsp;";
		$records[$count][5] = ($row_get_sale['sell_sales_fax'] > 0) ? phone_number($row_get_sale['sell_sales_fax']) : "&nbsp;";
		$records[$count][6] = ($row_get_sale['sell_sales_email'] == "") ? "&nbsp;" : $row_get_sale['sell_sales_email'];
		
	} while ($row_get_sale = mysql_fetch_assoc($get_sale));
	
	mysql_free_result($get_sale);
	
	build_record_5_table('Sales_Representatives','Sales Representatives',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Sales Representative(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
?>
</div>
