<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete") include $r_path.'scripts/del_man_bill.php'; ?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
</div>
<div>
<div id="Div_Records">
  <?php
	$query_get_bill = "SELECT * FROM `sellers_billing` WHERE `sell_id` = '$man_id'";
	$get_bill = mysql_query($query_get_bill, $cp_connection) or die(mysql_error());
	$row_get_bill = mysql_fetch_assoc($get_bill);
	$totalRows_get_bill = mysql_num_rows($get_bill);

	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = array();
	$drop_downs = false;
	$headers[0] = "Address";
	$headers[1] = "Credit Card";
	$headers[2] = "Experation Month";
	$headers[3] = "Experation Year";
	
	do{
		$count = count($records);
		$records[$count][0][0] = false;
		$records[$count][0][1] = false;
		$records[$count][0][2] = false;
		$records[$count][1] = $row_get_bill['sell_bill_id'];
		$records[$count][2] = $row_get_bill['sell_bill_add'];
		$records[$count][3] = ($row_get_bill['sell_bill_ccshort']!=0) ? "xxxx-xxxx-xxxx-".$row_get_bill['sell_bill_ccshort']: '&nbsp;';
		$records[$count][4] = ($row_get_bill['sell_bill_ccshort']!=0) ? date("F", mktime(0,0,0,$row_get_bill['sell_bill_exp_month'],1,2000)) : '&nbsp;';
		$records[$count][5] = ($row_get_bill['sell_bill_ccshort']!=0) ? $row_get_bill['sell_bill_exp_year'] : '&nbsp;';
		$temp_div = "";
		if($row_get_bill['sell_bill_suite_apt'] != ""){
			$temp_div .= " Suite/Apt. ".$row_get_bill['sell_bill_suite_apt']."<br />";
		}
		if($row_get_bill['sell_bill_add_2'] != ""){
			$temp_div .= $row_get_bill['sell_bill_add_2']."<br />";
		}
		if($row_get_bill['sell_bill_city'] != ""){
			$temp_div .= $row_get_bill['sell_bill_city'].", ".$row_get_bill['sell_bill_state'].". ".$row_get_bill['sell_bill_zip']."<br />";
		}
		if($row_get_bill['sell_bill_country'] != ""){
			$temp_div .= $row_get_bill['sell_bill_country'];
		}
		$div_data[$count] = $temp_div;
	} while ($row_get_bill = mysql_fetch_assoc($get_bill));
	
	mysql_free_result($get_bill);
	
	build_record_5_table('Billing','Billing',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Billing','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
?>
</div>
