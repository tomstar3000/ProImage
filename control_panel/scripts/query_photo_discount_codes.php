<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_3_table.php';
require_once $r_path.'scripts/fnct_format_date.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")	include $r_path.'scripts/del_photo_discount_codes.php';
if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
} else {
	$Sort = "Code Name";
	$Order = "ASC";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Code Name"){
	$Sort_val = " ORDER BY `disc_name` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Code"){
	$Sort_val = " ORDER BY `disc_code` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Percent"){
	$Sort_val = " ORDER BY `disc_percent` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Exp."){
	$Sort_val = " ORDER BY `disc_exp` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Used"){
	$Sort_val = " ORDER BY `disc_num_uses` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} ?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Discount Codes </h2>
 <p id="Add"><a href="#" onclick="javascript:set_form('','Disc,Disc','add','<? echo $sort; ?>','<? echo $rcrd; ?>');">Add</a></p>
</div>
<div>
 <?php 
	$records = array();
	$headers = array();
	$sortheaders = array();
	$sortheaders[0] = $Sort;
	$sortheaders[1] = $Order;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Code Name";
	$headers[1] = "Code";
	$headers[2] = "Percent";
	$headers[3] = "Exp.";
	$headers[4] = "Used";

	$query_get_info = "SELECT * FROM `prod_discount_codes`  WHERE `prod_id` = '0' AND `cust_id` = '$CustId' AND `disc_use` = 'y'".$Sort_val;
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$row_get_info = mysql_fetch_assoc($get_info);
	do{
		$temp_id = $row_get_info['disc_id'];
		$query_get_used = "SELECT COUNT(`disc_id`) as `code_count` FROM `orders_invoice_codes`  WHERE `disc_id` = '$temp_id'";
		$get_used = mysql_query($query_get_used, $cp_connection) or die(mysql_error());
		$row_get_used = mysql_fetch_assoc($get_used);
		$count = count($records);
		$records[$count][0] = false;
		$records[$count][1] = $row_get_info['disc_id'];
		$records[$count][2] = $row_get_info['disc_name'];
		$records[$count][3] = $row_get_info['disc_code'];
		$records[$count][4] = $row_get_info['disc_percent'];
		$records[$count][5] = format_date($row_get_info['disc_exp'],"Dash",false,true,false);
		$records[$count][6] = $row_get_used['code_count'].' / '.$row_get_info['disc_num_uses'];
		
		mysql_free_result($get_used);
	} while ($row_get_info = mysql_fetch_assoc($get_info)); 
	
	mysql_free_result($get_info);
	build_record_3_table('Discount','Discount Codes',$headers,$sortheaders,$records,$div_data,$drop_downs,'Delete Discount Code(s)','Delete','Delete','btn_delete.jpg', false, "100%", "0", "0","0", false, false, "FFFFFF", "EEEEEE", "DDDDDD", false, false, false, false, true, 'btn_select_all.jpg', 'btn_deselect.jpg');
?>
</div>
