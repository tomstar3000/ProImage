<? 
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
define ("Allow Scripts", true);
$is_variable = "invoice";
require_once($r_path.'Connections/cp_connection.php');
mysql_select_db($database_cp_connection, $cp_connection);
if(function_exists("phone_number")===false)require_once($r_path.'scripts/fnct_format_phone.php');
if(function_exists("clean_variable")===false)require_once($r_path.'scripts/fnct_clean_entry.php');
if(isset($_GET['invoice']))$encnum = clean_variable($_GET['invoice'],true);

$query_get_photo = "SELECT `cust_customers`.`cust_cname`, `cust_customers`.`cust_email`, `cust_customers`.`cust_add`, `cust_customers`.`cust_suite_apt`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `orders_invoice`.`invoice_accepted_date`, `orders_invoice`.`invoice_num`, `orders_invoice`.`invoice_total`, `orders_invoice`.`invoice_disc`, `orders_invoice`.`invoice_tax`, `orders_invoice`.`invoice_ship`, `orders_invoice`.`invoice_grand`, `orders_invoice`.`invoice_date`, `orders_invoice`.`ship_speed_id` FROM `cust_customers` INNER JOIN `photo_event_images` ON `photo_event_images`.`cust_id` = `cust_customers`.`cust_id` INNER JOIN `orders_invoice_photo` ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` INNER JOIN `orders_invoice` ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` WHERE `orders_invoice`.`invoice_enc` = '$encnum' LIMIT 0,1";
$get_photo = mysql_query($query_get_photo, $cp_connection) or die(mysql_error());
$row_get_photo = mysql_fetch_assoc($get_photo);
$speed = $row_get_photo['ship_speed_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="shortcut icon" href="http://www.proimagesoftware.com/photoexpress.ico" type="image/x-icon">
<link rel="icon" href="http://www.proimagesoftware.com/photoexpress.ico" type="image/x-icon">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="robots" content="index,follow" />
<meta name="keywords" content=""/>
<meta name="language" content="english"/>
<meta name="author" content="Pro Image Software" />
<meta name="copyright" content="2007" />
<meta name="reply-to" content="info@proimagesoftware.com" />
<meta name="document-rights" content="Copyrighted Work" />
<meta name="document-type" content="Web Page" />
<meta name="document-rating" content="General" />
<meta name="document-distribution" content="Global" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="Pragma" content="no-cache" />
<title>ProImageSoftware</title>
<style type="text/css">
<!--
body, html{
	margin:0px;
	padding:0px;
	font-family: "Times New Roman", Times, serif;
	font-size: 12px;
}
th, h1{
	font-size:14px;
	font-weight:bold;
	font-family: Arial, Helvetica, sans-serif;
	background:#EFEFEF;
}
-->
</style>
</head>
<body>
<div>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td align="left"><p><? echo $row_get_photo['cust_cname']; ?><br />
          <? echo $row_get_photo['cust_add']; ?><br />
          <? echo $row_get_photo['cust_city']." ".$row_get_photo['cust_state']." ".$row_get_photo['cust_zip']; ?></p></td>
      <td align="right"><p>Invoice: <? echo $row_get_photo['invoice_num']; ?><br />
          <? echo date("Y-m-d H:i:s", mktime(substr($row_get_photo['invoice_date'],11,2)-2,substr($row_get_photo['invoice_date'],14,2),substr($row_get_photo['invoice_date'],17,2),substr($row_get_photo['invoice_date'],5,2),substr($row_get_photo['invoice_date'],8,2),substr($row_get_photo['invoice_date'],0,4))); ?> MST<? if($row_get_photo['invoice_accepted_date']!="0000-00-00 00:00:00") echo "<br /> Accepted On: ".date("Y-m-d H:i:s", mktime(substr($row_get_photo['invoice_accepted_date'],11,2)-2,substr($row_get_photo['invoice_accepted_date'],14,2),substr($row_get_photo['invoice_accepted_date'],17,2),substr($row_get_photo['invoice_accepted_date'],5,2),substr($row_get_photo['invoice_accepted_date'],8,2),substr($row_get_photo['invoice_accepted_date'],0,4)))." MST";?></p></td>
    </tr>
  </table>
</div>
<br clear="all" />
<p>&nbsp;</p>
<? $query_get_bill = "SELECT `cust_billing`.`cust_bill_fname`, `cust_billing`.`cust_bill_lname`, `cust_billing`.`cust_bill_add`, `cust_billing`.`cust_bill_suite_apt`, `cust_billing`.`cust_bill_city`, `cust_billing`.`cust_bill_state`, `cust_billing`.`cust_bill_zip`, `cust_customers`.`cust_phone`, `cust_customers`.`cust_email`, `cust_billing`.`cust_bill_ccshort`, `cust_billing`.`cust_bill_exp_month`, `cust_billing`.`cust_bill_year`, `billship_cc_types`.`cc_type_name` FROM `cust_billing` INNER JOIN `orders_invoice` ON `orders_invoice`.`cust_bill_id` = `cust_billing`.`cust_bill_id` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `cust_billing`.`cust_id` INNER JOIN `billship_cc_types` ON `billship_cc_types`.`cc_type_id` = `cust_billing`.`cust_bill_cc_type_id` WHERE `orders_invoice`.`invoice_enc` = '$encnum' LIMIT 0,1";
$get_bill = mysql_query($query_get_bill, $cp_connection) or die(mysql_error());
$row_get_bill = mysql_fetch_assoc($get_bill); 

$query_get_ship = "SELECT `cust_shipping`.`cust_ship_fname`, `cust_shipping`.`cust_ship_lname`, `cust_shipping`.`cust_ship_add`, `cust_shipping`.`cust_ship_suite_apt`, `cust_shipping`.`cust_ship_city`, `cust_shipping`.`cust_ship_state`, `cust_shipping`.`cust_ship_zip` FROM `cust_shipping` INNER JOIN `orders_invoice` ON `orders_invoice`.`cust_ship_id` = `cust_shipping`.`cust_ship_id` WHERE `orders_invoice`.`invoice_enc` = '$encnum' LIMIT 0,1";
$get_ship = mysql_query($query_get_ship, $cp_connection) or die(mysql_error());
$row_get_ship = mysql_fetch_assoc($get_ship); 

if($speed < 0){
	$row_ship_speeds = array();
	$row_ship_speeds['ship_comp_name'] = ($speed == -1) ? "Client to pick up" : "Photographer to pick up";
	$row_ship_speeds['ship_speed_name'] = "";
} else {
$query_ship_speeds = "SELECT `billship_shipping_companies`.`ship_comp_name`, `billship_shipping_speeds`.`ship_speed_name` FROM `billship_shipping_speeds` INNER JOIN `billship_shipping_companies` ON `billship_shipping_companies`.`ship_comp_id` = `billship_shipping_speeds`.`ship_comp_id` WHERE `ship_speed_id` = '$speed'";
$ship_speeds = mysql_query($query_ship_speeds, $cp_connection) or die(mysql_error());
$row_ship_speeds = mysql_fetch_assoc($ship_speeds);
}
?>
<div id="Information">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <th width="50%"><h1 style="text-align:left;">Billing Information:</h1></th>
    <th width="50%"><h1 style="text-align:left;">Shipping Information:</h1></th>
  </tr>
  <tr>
    <td><p><? echo $row_get_bill['cust_bill_fname']." ".$row_get_bill['cust_bill_lname']; ?><br />
        <? echo $row_get_bill['cust_bill_add']; if($row_get_bill['cust_bill_suite_apt'] != "")echo " ".$row_get_bill['cust_bill_suite_apt']; ?><br />
        <? echo $row_get_bill['cust_bill_city']." ".$row_get_bill['cust_bill_state']." ".$row_get_bill['cust_bill_zip']; ?><br />
        <? echo phone_number($row_get_bill['cust_phone']); ?><br />
        <? echo $row_get_bill['cust_email']; ?></p></td>
    <td><p><? echo $row_get_ship['cust_ship_fname']." ".$row_get_ship['cust_ship_lname']; ?><br />
        <? echo $row_get_ship['cust_ship_add']; if($row_get_ship['cust_ship_suite_apt'] != "")echo " ".$row_get_ship['cust_ship_suite_apt']; ?><br />
        <? echo $row_get_ship['cust_ship_city']." ".$row_get_ship['cust_ship_state']." ".$row_get_ship['cust_ship_zip']; ?><br />
        Ship via: 
        <?	echo $row_ship_speeds['ship_comp_name']." ".$row_ship_speeds['ship_speed_name']; ?>
      </p></td>
  </tr>
</table>
<br clear="all" />
<hr size="1" />
<p>Payment Type: Prepaid - CC <? echo $row_get_bill['cc_type_name']; ?> **** **** **** <? echo $row_get_bill['cust_bill_ccshort']; ?><br />
  Expiration: <? echo $row_get_bill['cust_bill_exp_month']."/".$row_get_bill['cust_bill_year']; ?></p>
<hr size="1" />
<? $query_ship_count = "SELECT SUM(`orders_invoice_photo`.`invoice_image_asis`) AS `Total_asis`,  SUM(`orders_invoice_photo`.`invoice_image_bw`) AS `Total_bw`, SUM(`orders_invoice_photo`.`invoice_image_sepia`) AS `Total_sepia`,`orders_invoice_photo`.`invoice_image_price`, `prod_products`.`prod_name` FROM `orders_invoice_photo` INNER JOIN `orders_invoice` ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` INNER JOIN `prod_products` ON `prod_products`.`prod_id` = `orders_invoice_photo`.`invoice_image_size_id` WHERE `orders_invoice`.`invoice_enc` = '$encnum' GROUP BY `orders_invoice_photo`.`invoice_image_size_id` ORDER BY `prod_products`.`prod_name` ASC";
$ship_count = mysql_query($query_ship_count, $cp_connection) or die(mysql_error()); 
$ship_name = array();
$ship_qty = array();
$ship_price = array();
?>
<div id="Information">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <th><h1 style="text-align:left;">Order Summary</h1></th>
      <th> <h1>Sku</h1></th>
      <th> <h1>Total</h1></th>
    </tr>
    <? while($row_ship_count = mysql_fetch_assoc($ship_count)){ ?>
    <tr>
      <td><p>Number of <? echo $row_ship_count['prod_name']; ?>Professional Print's:</p></td>
      <td align="center"><p><? echo ($row_ship_count['Total_asis']+$row_ship_count['Total_bw']+$row_ship_count['Total_sepia'])." ".$row_ship_count['prod_name'];?></p></td>
      <td align="center"><? echo ($row_ship_count['Total_asis']+$row_ship_count['Total_bw']+$row_ship_count['Total_sepia']);?></td>
    </tr>
    <? } ?>
  </table>
  <br clear="all" />
</div>
<? $query_items = "SELECT `orders_invoice_photo`.`invoice_image_price`, `orders_invoice_photo`.`invoice_image_asis`, `orders_invoice_photo`.`invoice_image_bw`, `orders_invoice_photo`.`invoice_image_sepia`, `prod_products`.`cat_id`, `prod_products`.`prod_name`, `prod_products`.`prod_id`, `photo_event_images`.`image_tiny` FROM `orders_invoice_photo` INNER JOIN `orders_invoice` ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` INNER JOIN `prod_products` ON `prod_products`.`prod_id` = `orders_invoice_photo`.`invoice_image_size_id` INNER JOIN `photo_event_images` ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` WHERE `orders_invoice`.`invoice_enc` = '$encnum' ORDER BY `photo_event_images`.`image_tiny`, `prod_products`.`prod_name` ASC";
$items = mysql_query($query_items, $cp_connection) or die(mysql_error());
$total_items = mysql_num_rows($items);
$item_desc = array();
$item_asis = array();
$item_bw = array();
$item_sep = array();
$item_price = array();
?>
<div id="Information">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <th><h1 style="text-align:left;">File</h1></th>
      <th><h1 style="text-align:left;">Image Description </h1></th>
      <th><h1 style="text-align:left;">Sku</h1></th>
      <th><h1>As Is </h1></th>
      <th><h1>B&amp;W </h1></th>
      <th><h1>Sepia</h1></th>
      <th><h1>Price</h1></th>
      <th><h1>Total</h1></th>
    </tr>
    <? $k = 0; $dq_file = array(); while($row_items = mysql_fetch_assoc($items)){
		if($row_items['prod_id'] == 118)$dq_file[118] = true;
		if($row_items['prod_id'] == 119)$dq_file[119] = true;
		$total = 0; ?>
    <tr>
      <td><p><? echo $row_items['image_tiny']; ?></p></td>
      <td> [<? echo $k+1; ?>] <? echo $row_items['prod_name']; ?></td>
      <td align="center"><? echo $row_items['prod_name']; ?> m</td>
      <td align="center"><? echo ($row_items['invoice_image_asis']==0)?"&nbsp;":$row_items['invoice_image_asis']; ?></td>
      <td align="center"><? echo ($row_items['invoice_image_bw']==0)?"&nbsp;":$row_items['invoice_image_bw']; ?></td>
      <td align="center"><? echo ($row_items['invoice_image_sepia']==0)?"&nbsp;":$row_items['invoice_image_sepia']; ?></td>
      <td align="center"><? echo number_format($row_items['invoice_image_price'],2,".",","); ?></td>
      <td align="center"><? $total = ($row_items['invoice_image_asis']*$row_items['invoice_image_price'])+($row_items['invoice_image_bw']*$row_items['invoice_image_price'])+($row_items['invoice_image_sepia']*$row_items['invoice_image_price']);
	echo number_format($total,2,".",",");  ?></td>
    </tr>
    <? $k++; } ?>
  </table>
	<? 
	if(count($dq_file) > 0){
		foreach($dq_file as $k => $v){ /*?>
				<div style="width:10px; height:10px;" onclick="window.open('/checkout/download.php?invoice=<? echo $encnum.md5($k); ?>');"><? //echo $row_items['cat_id']; ?></div>
				<? */ } }  ?>
  <br clear="all" />
</div>
<div id="Information">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <th><h1 style="text-align:left;">Additional Items</h1></th>
      <th><h1>Sku</h1></th>
      <th><h1>Qty</h1></th>
      <th><h1>Price</h1></th>
      <th><h1>Total</h1></th>
    </tr>
  </table>
  <br clear="all" />
</div>
<div id="Information">
  <h1>Total<br clear="all" />
  </h1>
</div>
<div style="float:right; width:300px;">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td align="left"><p>Subtotal:<br />
        Discount:<br />
          Shipping:<br />
          Tax:</p>
        <p><strong>Grand Total: </strong><br />
          Amount Charged: </p>
        <p><strong>Balance Due:</strong><br />
        </p></td>
      <td align="right"><p>$<? echo number_format($row_get_photo['invoice_total'],2,".",","); ?><br />
          $<? echo number_format($row_get_photo['invoice_disc'],2,".",","); ?><br />
          $<? echo number_format($row_get_photo['invoice_ship'],2,".",","); ?><br />
          $<? echo number_format($row_get_photo['invoice_tax'],2,".",","); ?></p>
        <p><strong>$<? echo number_format($row_get_photo['invoice_grand'],2,".",","); ?><br />
          </strong>$<? echo number_format($row_get_photo['invoice_grand'],2,".",","); ?></p>
        <p>$0.00</p></td>
    </tr>
  </table>
</div>
</body>
</html>
