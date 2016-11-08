<?php
//mysql_select_db($database_cp_connection, $cp_connection);
$query_get_items = "SELECT `prod_products`.`prod_name`, `prod_products`.`prod_number`, `orders_invoice_prod`.`invoice_prod_id`, `orders_invoice_prod`.`invoice_prod_qty` AS `invoice_prod_qty` FROM `orders_invoice` LEFT OUTER JOIN `orders_invoice_prod` ON `orders_invoice_prod`.`invoice_id` = `orders_invoice`.`invoice_id` LEFT OUTER JOIN `prod_products` ON `prod_products`.`prod_id` = `orders_invoice_prod`.`prod_id` LEFT OUTER JOIN `sellers` ON `sellers`.`sell_id` = `prod_products`.`sell_id` WHERE `sellers`.`sell_id` = '$man_id'AND `orders_invoice`.`cust_ship_id` = '$ship_id' AND `orders_invoice`.`ship_speed_id` = '$speed_id' AND `orders_invoice`.`invoice_date` >= '$first_date' AND `orders_invoice`.`invoice_date` < '$last_date' ORDER BY `prod_products`.`prod_name` ASC";
$get_items = mysql_query($query_get_items, $cp_connection) or die(mysql_error());
$row_get_items = mysql_fetch_assoc($get_items);
$totalRows_get_items = mysql_num_rows($get_items);
?>