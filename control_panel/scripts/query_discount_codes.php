<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; 	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")	include $r_path.'scripts/del_discount_codes.php'; ?>

<div id="Form_Header">
 <div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('','Prod,Discount','add','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
 <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
 <p>Discount Code List </p>
</div>
<div id="Div_Records">
 <? 
	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Code Name";
	$headers[1] = "Code";
	$headers[2] = "Percent";
	$headers[3] = "Price";
	$headers[4] = "Buy / Get";
	$headers[5] = "Exp.";
	$headers[6] = "Used";

	$query_get_info = "SELECT * FROM `prod_discount_codes`  WHERE `cust_id` = '0' ORDER BY `disc_name` ASC";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$row_get_info = mysql_fetch_assoc($get_info);
	do{
		$temp_id = $row_get_info['disc_id'];
		$query_get_used = "SELECT COUNT(`disc_id`) as `code_count` FROM `orders_invoice_codes`  WHERE `disc_id` = '$temp_id'";
		$get_used = mysql_query($query_get_used, $cp_connection) or die(mysql_error());
		$row_get_used = mysql_fetch_assoc($get_used);
		$count = count($records);
		$records[$count][0][0] = false;
		$records[$count][0][1] = false;
		$records[$count][0][2] = false;
		$records[$count][1] = $row_get_info['disc_id'];
		$records[$count][2] = $row_get_info['disc_name'];
		$records[$count][3] = $row_get_info['disc_code'];
		$records[$count][4] = $row_get_info['disc_percent'];
		$records[$count][5] = $row_get_info['disc_price'];
		$records[$count][6] = $row_get_info['disc_item'].' / '.$row_get_info['disc_for'];
		$records[$count][7] = $row_get_info['disc_exp'];
		$records[$count][8] = $row_get_used['code_count'].' / '.$row_get_info['disc_num_uses'];
		
		mysql_free_result($get_used);
	} while ($row_get_info = mysql_fetch_assoc($get_info)); 
	
	mysql_free_result($get_info);
	build_record_5_table('Discount','Discount Codes',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Discount Code(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3);
?>
</div>
