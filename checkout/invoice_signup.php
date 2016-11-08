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

if(isset($_GET['invoice'])) $encnum = clean_variable($_GET['invoice'],true);

$query_get_photo = "SELECT `cust_customers`.`cust_cname`, `cust_customers`.`cust_due_date`, `cust_customers`.`cust_handle`, `cust_customers`.`cust_email`, `cust_customers`.`cust_add`, `cust_customers`.`cust_suite_apt`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `orders_invoice`.`invoice_num`, `orders_invoice`.`invoice_total`, `orders_invoice`.`invoice_disc`, `orders_invoice`.`invoice_tax`, `orders_invoice`.`invoice_ship`, `orders_invoice`.`invoice_grand`, `orders_invoice`.`invoice_date`, `orders_invoice`.`ship_speed_id` FROM `cust_customers` INNER JOIN `orders_invoice` ON `orders_invoice`.`cust_id` = `cust_customers`.`cust_id` WHERE `orders_invoice`.`invoice_enc` = '$encnum' LIMIT 0,1";
$get_photo = mysql_query($query_get_photo, $cp_connection) or die(mysql_error());
$row_get_photo = mysql_fetch_assoc($get_photo);
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
        : <? echo $row_get_photo['cust_handle']; ?><br />
          <? echo $row_get_photo['cust_add']; ?><br />
          <? echo $row_get_photo['cust_city']." ".$row_get_photo['cust_state']." ".$row_get_photo['cust_zip']; ?></p></td>
      <td align="right"><p>Invoice: <? echo $row_get_photo['invoice_num']; ?><br />
          <? echo date("Y-m-d H:i:s", mktime(substr($row_get_photo['invoice_date'],11,2)-2,substr($row_get_photo['invoice_date'],14,2),substr($row_get_photo['invoice_date'],17,2),substr($row_get_photo['invoice_date'],5,2),substr($row_get_photo['invoice_date'],8,2),substr($row_get_photo['invoice_date'],0,4))); ?> MST</p></td>
    </tr>
  </table>
</div>
<br clear="all" />
<p>&nbsp;</p>
<? $query_get_bill = "SELECT `cust_billing`.`cust_bill_fname`, `cust_billing`.`cust_bill_lname`, `cust_billing`.`cust_bill_add`, `cust_billing`.`cust_bill_suite_apt`, `cust_billing`.`cust_bill_city`, `cust_billing`.`cust_bill_state`, `cust_billing`.`cust_bill_zip`, `cust_customers`.`cust_phone`, `cust_customers`.`cust_email`, `cust_billing`.`cust_bill_ccshort`, `cust_billing`.`cust_bill_exp_month`, `cust_billing`.`cust_bill_year`, `billship_cc_types`.`cc_type_name` FROM `cust_billing` INNER JOIN `orders_invoice` ON `orders_invoice`.`cust_bill_id` = `cust_billing`.`cust_bill_id` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `cust_billing`.`cust_id` LEFT OUTER JOIN `billship_cc_types` ON `billship_cc_types`.`cc_type_id` = `cust_billing`.`cust_bill_cc_type_id` WHERE `orders_invoice`.`invoice_enc` = '$encnum' LIMIT 0,1";
$get_bill = mysql_query($query_get_bill, $cp_connection) or die(mysql_error());
$row_get_bill = mysql_fetch_assoc($get_bill); 
?>
<div id="Information">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <th width="50%"><h1 style="text-align:left;">Billing Information:</h1></th>
      <th width="50%"><h1 style="text-align:left;">&nbsp;</h1></th>
    </tr>
    <tr>
      <td><p><? echo $row_get_bill['cust_bill_fname']." ".$row_get_bill['cust_bill_lname']; ?><br />
          <? echo $row_get_bill['cust_bill_add']; if($row_get_bill['cust_bill_suite_apt'] != "")echo " ".$row_get_bill['cust_bill_suite_apt']; ?><br />
          <? echo $row_get_bill['cust_bill_city']." ".$row_get_bill['cust_bill_state']." ".$row_get_bill['cust_bill_zip']; ?><br />
          <? echo phone_number($row_get_bill['cust_phone']); ?><br />
        <? echo $row_get_bill['cust_email']; ?></p></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>
<br clear="all" />
<hr size="1" />
<p>Payment Type: Prepaid - CC <? echo $row_get_bill['cc_type_name']; ?> **** **** **** <? echo $row_get_bill['cust_bill_ccshort']; ?></p>
<hr size="1" />
<? $query_items = "SELECT `orders_invoice_prod`.`invoice_prod_price`, `orders_invoice_prod`.`invoice_prod_fee`, `prod_products`.`prod_name` FROM `orders_invoice_prod` INNER JOIN `orders_invoice` ON `orders_invoice`.`invoice_id` = `orders_invoice_prod`.`invoice_id` INNER JOIN `prod_products` ON `prod_products`.`prod_id` = `orders_invoice_prod`.`prod_id` WHERE `orders_invoice`.`invoice_enc` = '$encnum' ORDER BY `prod_products`.`prod_name` ASC";
$items = mysql_query($query_items, $cp_connection) or die(mysql_error());

$total_price = 0;
$total_fee = 0;
?>
<div id="Information">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <th><h1 style="text-align:left;">Order Summary</h1></th>
      <th><h1>Monthly Fee</h1></th>
      <th><h1>Setup Fee</h1></th>
      <th><h1>Total</h1></th>
    </tr>
		<? while($row_items = mysql_fetch_assoc($items)){ 
		$total_price += $row_items['invoice_prod_price'];
		$total_fee += $row_items['invoice_prod_fee'];
		?>
    <tr>
      <td><p><? echo $row_items['prod_name']; ?></p></td>
      <td align="center"><p>$<? echo number_format($row_items['invoice_prod_price'],2,".",","); ?></p></td>
      <td align="center"><p>$<? echo number_format($row_items['invoice_prod_fee'],2,".",","); ?></p></td>
      <td align="center"><p>$<? echo number_format((intval($row_items['invoice_prod_price'])+intval($row_items['invoice_prod_fee'])),2,".",","); ?></p></td>
    </tr>
		<? } ?>
  </table>
  <br clear="all" />
</div>

<div id="Information">
  <h1>Total<br clear="all" />
  </h1>
</div>

<div style="float:left; width:300px; padding-left:15px; padding-top:15px;">
  <h1>Renewel Date: <span style="font-weight:normal"><? echo date("m/d/Y", mktime(substr($row_get_photo['cust_due_date'],11,2)-2,substr($row_get_photo['cust_due_date'],14,2),substr($row_get_photo['cust_due_date'],17,2),substr($row_get_photo['cust_due_date'],5,2),substr($row_get_photo['cust_due_date'],8,2),substr($row_get_photo['cust_due_date'],0,4))); ?></span></h1>
</div>
<div style="float:right; width:300px;">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td align="left"><p>Subtotal:<br />
          Discount:<br />
          Setup Fee:<br />
          Shipping:<br />
          Tax:</p>
        <p><strong>Grand Total: </strong><br />
          Amount Charged: </p>
        <p><strong>Balance Due:</strong><br />
        </p></td>
      <td align="right"><p>$<? echo number_format($row_get_photo['invoice_total'],2,".",","); ?><br />
          $<? echo number_format($row_get_photo['invoice_disc'],2,".",","); ?><br />
					$<? echo number_format($total_fee,2,".",","); ?><br />
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
