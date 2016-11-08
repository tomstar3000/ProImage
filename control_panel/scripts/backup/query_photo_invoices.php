<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once($r_path.'scripts/fnct_record_3_table.php');
require_once($r_path.'scripts/fnct_format_date.php');
if(isset($_POST['Controller']) && $_POST['Controller'] == "Send to Lab")require_once($r_path.'scripts/del_photo_invoices.php');
?>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
  <h2>Invoices</h2>
  <p id="Search"><a href="#" onclick="javascript:set_form('','<? echo implode(",",$path); ?>','search','<? echo $sort; ?>','<? echo $rcrd; ?>');">Invoice Search</a></p>
</div>
<div>
  <?
	if($path[1]=="Open"){
$query_get_info = "SELECT `orders_invoice`.*, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, COUNT( `orders_invoice_photo`.`image_id` ) AS `count_image_ids` FROM `photo_event_images` INNER JOIN `orders_invoice_photo` ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` INNER JOIN `orders_invoice` ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` WHERE `photo_event_images`.`cust_id` = '$CustId' AND `invoice_accepted` = 'n' GROUP BY `orders_invoice`.`invoice_id` ORDER BY `invoice_num` ASC"; } else { 
$query_get_info = "SELECT `orders_invoice`.*, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, COUNT( `orders_invoice_photo`.`image_id` ) AS `count_image_ids` FROM `photo_event_images` INNER JOIN `orders_invoice_photo` ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` INNER JOIN `orders_invoice` ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` WHERE `photo_event_images`.`cust_id` = '$CustId' GROUP BY `orders_invoice`.`invoice_id` ORDER BY `invoice_num` ASC"; }
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

$records = array();
$headers = array();
$sortheaders = false;
$div_data = false;
$drop_downs = false;
$headers[0] = "Invoice";
$headers[1] = "Date";
$headers[2] = "Total";
$headers[3] = "Name";
$headers[4] = "Images";
$headers[5] = "&nbsp;";

do{	
	$count = count($records);
	$records[$count][0] = false;
	$records[$count][1] = $row_get_info['invoice_id'];
	$records[$count][2] = $row_get_info['invoice_num'];
	$records[$count][3] = format_date($row_get_info['invoice_date'],"Short",false,true,false);
	$records[$count][4] = "$".number_format($row_get_info['invoice_total'],2,".",",");
	$records[$count][5] = $row_get_info['cust_fname']." ".$row_get_info['cust_lname'];
	$records[$count][6] = $row_get_info['count_image_ids'];
	$records[$count][7] = '<a href="/checkout/invoice.php?invoice='.$row_get_info['invoice_enc'].'" target="_blank">View</a>';
	
} while ($row_get_info = mysql_fetch_assoc($get_info)); 

mysql_free_result($get_info);
if($path[1]=="Open"){
build_record_3_table('Invoices','Invoices',$headers,$sortheaders,$records,$div_data,$drop_downs,'Send Invoice(s) to the Lab','Send to Lab','Send to Lab','btn_lab_2.jpg',false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3, false, false, false, false, true, 'btn_select_all.jpg', 'btn_deselect.jpg');
} else {
build_record_3_table('Invoices','Invoices',$headers,$sortheaders,$records,$div_data,$drop_downs,false,false,false,false,false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,false,false,false,false,false,false);
}
if(is_array($rcrd))$rcrd = implode(",",$rcrd);

?>
</div>
