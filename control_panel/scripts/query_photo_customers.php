<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_record_5_table.php';
require_once $r_path.'scripts/fnct_format_phone.php';

if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
} else {
	$Sort = "Customer Name";
	$Order = "ASC";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Customer Name"){
	$Sort_val = " ORDER BY `cust_customers`.`cust_lname` ".$Order.", `cust_customers`.`cust_fname` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Address"){
	$Sort_val = " ORDER BY `cust_customers`.`cust_city` ".$Order.",`cust_customers`.`cust_state` ".$Order.",`cust_customers`.`cust_zip` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Phone Number"){
	$Sort_val = " ORDER BY `cust_phone` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "E-mail"){
	$Sort_val = " ORDER BY `cust_email` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} ?>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
  <h2>Customer List </h2>
  <p id="Search"><a href="#" onclick="javascript:set_form('','<? echo implode(",",$path); ?>','search','<? echo $sort; ?>','<? echo $rcrd; ?>');">Customer Search</a></p>
</div>
<? 
$query_get_records = "SELECT `cust_customers`.`cust_id`,`cust_customers`.`cust_fname`,`cust_customers`.`cust_lname`,`cust_customers`.`cust_city`,`cust_customers`.`cust_state`,`cust_customers`.`cust_zip`,`cust_customers`.`cust_phone`,`cust_customers`.`cust_email` FROM `photo_event_images` INNER JOIN `orders_invoice_photo` ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` INNER JOIN `orders_invoice` ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` WHERE `photo_event_images`.`cust_id` = '$CustId' GROUP BY `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_email`".$Sort_val;
$get_records = mysql_query($query_get_records, $cp_connection) or die(mysql_error());
$row_get_records = mysql_fetch_assoc($get_records);
$totalRows_get_records = mysql_num_rows($get_records);

$records = array();
$headers = array();
$sortheaders = array();
$sortheaders[0] = $Sort;
$sortheaders[1] = $Order;
$div_data = false;
$drop_downs = false;
$headers[0] = "Customer Name";
$headers[1] = "Address";
$headers[2] = "Phone Number";
$headers[3] = "E-mail";
do{
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = false;
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_records['cust_id'];
	$records[$count][2] = $row_get_records['cust_lname'].", ".$row_get_records['cust_fname'];
	$records[$count][3] = $row_get_records['cust_city']." ".$row_get_records['cust_state']." ".$row_get_records['cust_zip'];
	$records[$count][4] = phone_number($row_get_records['cust_phone']);
	$records[$count][5] = $row_get_records['cust_email'];
	
} while ($row_get_records = mysql_fetch_assoc($get_records)); 

mysql_free_result($get_records);

build_record_5_table('Customers','Customers',$headers,$sortheaders,$records,$div_data,$drop_downs,false,false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5,false);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);

?>