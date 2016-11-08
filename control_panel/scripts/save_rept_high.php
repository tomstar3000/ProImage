<?php
include $r_path.'security.php';
$InvDate = $_GET['id'];

$first_date = $InvDate." 00:00:00";
$last_date = date("Y-m-d H:i:s", mktime(0,0,0,substr($InvDate,5,2),substr($InvDate,8,2)+1,substr($InvDate,0,4)));

$query_get_invoice_info = "SELECT `orders_invoice`.`invoice_id`, `sellers`.`sell_cname`, `sellers`.`sell_id` FROM `orders_invoice` INNER JOIN `orders_invoice_prod` ON `orders_invoice_prod`.`invoice_id` = `orders_invoice`.`invoice_id` INNER JOIN `prod_products` ON `prod_products`.`prod_id` = `orders_invoice_prod`.`prod_id` INNER JOIN `sellers` ON `sellers`.`sell_id` = `prod_products`.`sell_id` WHERE `invoice_date` >= '$first_date' AND `invoice_date` < '$last_date' GROUP BY `sellers`.`sell_cname` ASC";
$get_invoice_info = mysql_query($query_get_invoice_info, $cp_connection) or die(mysql_error());
$row_get_invoice_info = mysql_fetch_assoc($get_invoice_info);
$totalRows_get_invoice_info = mysql_num_rows($get_invoice_info);
		
?>
