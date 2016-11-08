<?php
//mysql_select_db($database_cp_connection, $cp_connection);
$query_get_attrib = "SELECT `prod_attributes`.`att_name` FROM `orders_invoice_spec` INNER JOIN `prod_attributes` ON `prod_attributes`.`att_id` = `orders_invoice_spec`.`spec_id` WHERE `invoice_prod_id` = '$item_id' ORDER BY `att_name` ASC";
$get_attrib = mysql_query($query_get_attrib, $cp_connection) or die(mysql_error());
$row_get_attrib = mysql_fetch_assoc($get_attrib);
$totalRows_get_attrib = mysql_num_rows($get_attrib);

if($need_spcl){
	//mysql_select_db($database_cp_connection, $cp_connection);
	$query_get_special = "SELECT `prod_special_delivery`.`spec_del_name` FROM `orders_invoice_del` INNER JOIN `prod_special_delivery` ON `prod_special_delivery`.`spec_del_id` = `orders_invoice_del`.`spec_del_id` WHERE `invoice_prod_id` = '$item_id' ORDER BY `spec_del_name` ASC";
	$get_special = mysql_query($query_get_special, $cp_connection) or die(mysql_error());
	$row_get_special = mysql_fetch_assoc($get_special);
	$totalRows_get_special = mysql_num_rows($get_special);
}
?>
