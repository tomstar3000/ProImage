<?php if(isset($r_path)===false){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
require_once($r_path.'scripts/fnct_record_5_table.php');
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")	require_once($r_path.'scripts/del_photo_pricing.php');
if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
} else {
	$Sort = "Pricing Name";
	$Order = "ASC";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Pricing Name"){
	$Sort_val = " ORDER BY `price_name` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
}
?>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
  <h2>Event Pricing</h2>
  <p id="Add"><a href="#" onclick="javascript:set_form('','Price,All','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');">Add</a></p>
</div>
<div>
  <?php
$query_get_info = "SELECT * FROM `photo_event_price` WHERE `cust_id` = '$CustId' AND `photo_price_use` = 'y'".$Sort_val;
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

$records = array();
$headers = array();
$sortheaders = array();
$sortheaders[0] = $Sort;
$sortheaders[1] = $Order;
$div_data = false;
$drop_downs = false;
$headers[0] = "Pricing Name";

do{
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = false;
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_info['photo_event_price_id'];
	$records[$count][2] = $row_get_info['price_name'];
} while ($row_get_info = mysql_fetch_assoc($get_info)); 

mysql_free_result($get_info);

build_record_5_table('Pricing','Pricing',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Pricing','Delete','Delete','btn_delete.jpg')), false, "100%", "0", "0","0", false, false,"FFFFFF","EEEEEE","DDDDDD","EFEFEF","CDCDCD",true,'btn_select_all.jpg','btn_deselect.jpg');
?>
</div>
