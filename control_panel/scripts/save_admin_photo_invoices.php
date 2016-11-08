<?php

if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++) {
        $r_path .= "../";
    }
}
include $r_path . 'security.php';
require_once $r_path . 'scripts/fnct_clean_entry.php';
require_once $r_path . 'scripts/fnct_format_phone.php';
if ($path[1] == "All") {
    $cont = "view";
} else {
    $cont = "edit";
}
$invnum = $path[2];
$QName = (isset($_POST['Quality_Control'])) ? clean_variable($_POST['Quality_Control'], true) : '';
$PName = (isset($_POST['Person_Printing'])) ? clean_variable($_POST['Person_Printing'], true) : '';
$SName = (isset($_POST['Person_Shipping'])) ? clean_variable($_POST['Person_Shipping'], true) : '';
$Refund = (isset($_POST['refund'])) ? (($_POST['refund'] === 'true' || $_POST['refund'] === true) ? true : false) : false;
$RefundDate = date('Y-m-d H:i:s');
if (isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')) {
    $upd = "UPDATE `orders_invoice` 
           SET `invoice_pers_quality` = '$QName',
               `invoice_pers_print` = '$PName',
               `invoice_pers_ship` = '$SName' ";
    if($Refund == true){
        $upd .= ", invoice_refund = 'y', 
            invoice_refund_date = NOW() ";
    } else {
         $upd .= ", invoice_refund = 'n', 
            invoice_refund_date = '0000-00-00 00:00:00' ";
    }
    $upd .= " WHERE `invoice_id` = '$invnum'";
    $updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
} else {
    if ($cont != "add") {
        $query_get_info = "SELECT `invoice_pers_quality`, `invoice_pers_print`, `invoice_pers_ship`, invoice_refund, invoice_refund_date FROM `orders_invoice` WHERE `invoice_id` = '$invnum'";
        $get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
        $row_get_info = mysql_fetch_assoc($get_info);
        $totalRows_get_info = mysql_num_rows($get_info);

        $QName = $row_get_info['invoice_pers_quality'];
        $PName = $row_get_info['invoice_pers_print'];
        $SName = $row_get_info['invoice_pers_ship'];
        $Refund = ($row_get_info['invoice_refund'] == 'y') ? true : false;
        $RefundDate = $row_get_info['invoice_refund_date'];

        mysql_free_result($get_info);
    }
}
$query_get_photo = "(SELECT `cust_customers`.`cust_cname`, `cust_customers`.`cust_email`, `cust_customers`.`cust_add`, `cust_customers`.`cust_suite_apt`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `orders_invoice`.`invoice_accepted_date`, `orders_invoice`.`invoice_num`, `orders_invoice`.`invoice_total`, `orders_invoice`.`invoice_disc`, `orders_invoice`.`invoice_tax`, `orders_invoice`.`invoice_ship`, `orders_invoice`.`invoice_grand`, `orders_invoice`.`invoice_date`, `orders_invoice`.`ship_speed_id`, `orders_invoice`.`invoice_printed_date`, `orders_invoice`.`invoice_comp_date` 
	FROM `cust_customers` 
	INNER JOIN `photo_event_images` 
		ON `photo_event_images`.`cust_id` = `cust_customers`.`cust_id` 
	INNER JOIN `orders_invoice_photo` 
		ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` 
	INNER JOIN `orders_invoice` 
		ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` 
	WHERE `orders_invoice`.`invoice_id` = '$invnum' 
	LIMIT 0,1)
UNION 
	(SELECT `cust_customers`.`cust_cname`, `cust_customers`.`cust_email`, `cust_customers`.`cust_add`, `cust_customers`.`cust_suite_apt`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `orders_invoice`.`invoice_accepted_date`, `orders_invoice`.`invoice_num`, `orders_invoice`.`invoice_total`, `orders_invoice`.`invoice_disc`, `orders_invoice`.`invoice_tax`, `orders_invoice`.`invoice_ship`, `orders_invoice`.`invoice_grand`, `orders_invoice`.`invoice_date`, `orders_invoice`.`ship_speed_id`, `orders_invoice`.`invoice_printed_date`, `orders_invoice`.`invoice_comp_date` 
	FROM `cust_customers` 
	INNER JOIN `photo_event_images` 
		ON `photo_event_images`.`cust_id` = `cust_customers`.`cust_id` 
	INNER JOIN `orders_invoice_border` 
		ON `orders_invoice_border`.`image_id` = `photo_event_images`.`image_id` 
	INNER JOIN `orders_invoice` 
		ON `orders_invoice`.`invoice_id` = `orders_invoice_border`.`invoice_id` 
	WHERE `orders_invoice`.`invoice_id` = '$invnum' 
	LIMIT 0,1)";
$get_photo = mysql_query($query_get_photo, $cp_connection) or die(mysql_error());
$row_get_photo = mysql_fetch_assoc($get_photo);
$speed = $row_get_photo['ship_speed_id'];
?>