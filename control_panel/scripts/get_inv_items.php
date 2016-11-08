<?php
//mysql_select_db($database_cp_connection, $cp_connection);
$query_get_items = "SELECT `orders_invoice_prod`.`invoice_prod_id`, `orders_invoice_prod`.`invoice_prod_qty`, `orders_invoice_prod`.`invoice_prod_price`, `prod_products`.`prod_number`, `prod_products`.`prod_name`, `sellers`.`sell_cname` FROM `orders_invoice_prod` LEFT OUTER JOIN `prod_products` ON `prod_products`.`prod_id` = `orders_invoice_prod`.`prod_id` LEFT OUTER JOIN `sellers` ON `sellers`.`sell_id` = `prod_products`.`sell_id` WHERE `invoice_id` = '$inv_id' ORDER BY `sell_cname` ASC, `prod_products`.`prod_name` ASC";
$get_items = mysql_query($query_get_items, $cp_connection) or die(mysql_error());
$row_get_items = mysql_fetch_assoc($get_items);
$totalRows_get_items = mysql_num_rows($get_items);
?>