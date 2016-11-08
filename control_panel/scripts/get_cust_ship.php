<?php
//mysql_select_db($database_cp_connection, $cp_connection);
$query_get_ship_info = "SELECT * FROM `cust_shipping` WHERE `cust_ship_id` = '$ship_id'";
$get_ship_info = mysql_query($query_get_ship_info, $cp_connection) or die(mysql_error());
$row_get_ship_info = mysql_fetch_assoc($get_ship_info);
$totalRows_get_ship_info = mysql_num_rows($get_ship_info);
?>