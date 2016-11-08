<?php
//mysql_select_db($database_cp_connection, $cp_connection);
$query_get_items = "SELECT SUM(`orders_invoice_prod`.`invoice_prod_qty`) AS `invoice_prod_qty`, SUM(`orders_invoice_prod`.`invoice_prod_price`) AS `invoice_prod_price`, `prod_products`.`prod_number`, `prod_products`.`prod_name`, `sellers`.`sell_cname` FROM `orders_invoice` LEFT OUTER JOIN `orders_invoice_prod` ON `orders_invoice_prod`.`invoice_id` = `orders_invoice`.`invoice_id` LEFT OUTER JOIN `prod_products` ON `prod_products`.`prod_id` = `orders_invoice_prod`.`prod_id` LEFT OUTER JOIN `sellers` ON `sellers`.`sell_id` = `prod_products`.`sell_id` WHERE `orders_invoice`.`cust_id` = '$cust_id' AND `orders_invoice`.`invoice_date` >= '$first_date' AND `orders_invoice`.`invoice_date` < '$last_date' GROUP BY `prod_products`.`prod_id` ASC ORDER BY `prod_products`.`prod_name` ASC";
$get_items = mysql_query($query_get_items, $cp_connection) or die(mysql_error());
$row_get_items = mysql_fetch_assoc($get_items);
$totalRows_get_items = mysql_num_rows($get_items);
?>