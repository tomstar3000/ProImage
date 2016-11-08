<?php if(isset($r_path)===false){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
require_once($r_path.'scripts/fnct_record_5_table.php');
require_once($r_path.'scripts/fnct_format_date.php');
if(isset($_POST['Controller']) && $_POST['Controller'] == "Send to Lab")require_once($r_path.'scripts/del_photo_invoices.php');
?>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
  <h2>Invoices</h2>
</div>
<div>
<?php
$query_get_id = "SELECT `cust_fname` ,`cust_lname` ,`cust_email` FROM `cust_customers` WHERE `cust_id` = '$CId'";
$get_id = mysql_query($query_get_id, $cp_connection) or die(mysql_error());
$row_get_id = mysql_fetch_assoc($get_id);
$temp_fname = $row_get_id['cust_fname'];
$temp_lname = $row_get_id['cust_lname'];
$temp_email = $row_get_id['cust_email'];

$query_get_info = "(SELECT `orders_invoice`.*, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, COUNT( `orders_invoice_photo`.`image_id` ) AS `count_image_ids` 
	FROM `photo_event_images` 
	INNER JOIN `orders_invoice_photo` 
		ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` 
	INNER JOIN `orders_invoice` 
		ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` 
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` 
	WHERE `cust_customers`.`cust_fname` = '$temp_fname' 
		AND `cust_customers`.`cust_lname` = '$temp_lname' 
		AND `cust_customers`.`cust_email` = '$temp_email' 
	GROUP BY `orders_invoice`.`invoice_id`)
UNION
	(SELECT `orders_invoice`.*, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, COUNT( `orders_invoice_border`.`image_id` ) AS `count_image_ids` 
	FROM `photo_event_images` 
	INNER JOIN `orders_invoice_border` 
		ON `orders_invoice_border`.`image_id` = `photo_event_images`.`image_id` 
	INNER JOIN `orders_invoice` 
		ON `orders_invoice`.`invoice_id` = `orders_invoice_border`.`invoice_id` 
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` 
	WHERE `cust_customers`.`cust_fname` = '$temp_fname' 
		AND `cust_customers`.`cust_lname` = '$temp_lname' 
		AND `cust_customers`.`cust_email` = '$temp_email' 
	GROUP BY `orders_invoice`.`invoice_id` )
	ORDER BY `invoice_num` ASC";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

$records = array();
$headers = array();
$sortheaders = false;
$div_data = false;
$drop_downs = false;
$headers[0] = "Invoice Number";
$headers[1] = "Date";
$headers[2] = "Total";
$headers[3] = "Customer Name";
$headers[4] = "Number of Images";
$headers[5] = "&nbsp;";

do{	
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = false;
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_info['invoice_id'];
	$records[$count][2] = $row_get_info['invoice_num'];
	$records[$count][3] = format_date($row_get_info['invoice_date'],"Short",false,true,false);
	$records[$count][4] = "$".number_format($row_get_info['invoice_total'],2,".",",");
	$records[$count][5] = $row_get_info['cust_fname']." ".$row_get_info['cust_lname'];
	$records[$count][6] = $row_get_info['count_image_ids'];
	$records[$count][7] = '<a href="/checkout/invoice.php?invoice='.$row_get_info['invoice_enc'].'" target="_blank">View</a>';
} while ($row_get_info = mysql_fetch_assoc($get_info)); 

mysql_free_result($get_info);

build_record_5_table('Invoices','Invoices',$headers,$sortheaders,$records,$div_data,$drop_downs,false,false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5,false,false);

if(is_array($rcrd))$rcrd = implode(",",$rcrd);

?>
</div>
