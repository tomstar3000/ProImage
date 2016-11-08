<?php
include $r_path.'security.php';
$InvDate = $_GET['id'];

$first_date = $InvDate." 00:00:00";
$last_date = date("Y-m-d H:i:s", mktime(0,0,0,substr($InvDate,5,2),substr($InvDate,8,2)+1,substr($InvDate,0,4)));

$query_get_invoice_info = "SELECT `orders_invoice`. * , `cust_customers`.`cust_fname` , `cust_customers`.`cust_lname` , `cust_customers`.`cust_cname` FROM `orders_invoice` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` WHERE `invoice_date` >= '$first_date' AND `invoice_date` < '$last_date' ORDER BY `cust_lname` ASC , `cust_fname` ASC , `invoice_date` ASC";
$get_invoice_info = mysql_query($query_get_invoice_info, $cp_connection) or die(mysql_error());
$row_get_invoice_info = mysql_fetch_assoc($get_invoice_info);
$totalRows_get_invoice_info = mysql_num_rows($get_invoice_info);
		
?>
