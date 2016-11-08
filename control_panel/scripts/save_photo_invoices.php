<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_send_email.php';

$IId = $path[2];
$Folder = '../../'.$r_path."toPhatFoto";

$query_get_info = "SELECT `cust_customers`.`cust_phone`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_email`, `cust_shipping`.*, `orders_invoice`.* FROM `orders_invoice` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` INNER JOIN `cust_shipping` ON `cust_shipping`.`cust_id` = `orders_invoice`.`cust_id` WHERE `invoice_id` = '$IId'";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

$InvNum = $row_get_info['invoice_num'];
$InvEnc = $row_get_info['invoice_enc'];
$IncComm = $row_get_info['invoice_comments'];
$Date = $row_get_info['invoice_date'];
$FName = $row_get_info['cust_fname'];
$LName = $row_get_info['cust_lname'];
$Phone = $row_get_info['cust_phone'];
$Email = $row_get_info['cust_email'];
$SFName = $row_get_info['cust_ship_fname'];
$SLName = $row_get_info['cust_ship_lname'];
$SAdd = $row_get_info['cust_ship_add'];
$SSuite = $row_get_info['cust_ship_suite_apt'];
$SCity = $row_get_info['cust_ship_city'];
$SState = $row_get_info['cust_ship_state'];
$SZip = $row_get_info['cust_ship_zip'];
$PSale = $row_get_info['prod_sale'];
$Total = $row_get_info['invoice_total'];
$Sent = $row_get_info['invoice_accepted'];

$query_get_image = "SELECT * FROM `orders_invoice_photo` INNER JOIN `photo_event_images` ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` WHERE `invoice_id` = '$IId' GROUP BY `orders_invoice_photo`.`image_id`";
$get_image = mysql_query($query_get_image, $cp_connection) or die(mysql_error());

$query_get_border = "SELECT `orders_invoice_border`.*, `photo_event_images`.*, `prod_products`.`prod_name` FROM `orders_invoice_border`
	INNER JOIN `photo_event_images` 
		ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id` 
	INNER JOIN `prod_products` 
		ON `prod_products`.`prod_id` = `orders_invoice_border`.`border_id` 
	WHERE `invoice_id` = '$IId'";
$get_border = mysql_query($query_get_border, $cp_connection) or die(mysql_error());

if($cont == "ToLab" || (isset($_POST['Controller']) && $_POST['Controller'] == "Send to Lab")){
	
	$upd = "UPDATE `orders_invoice` SET `invoice_accepted` = 'p' WHERE `invoice_id` = '$IId'";
	$upd_info = mysql_query($upd, $cp_connection) or die(mysql_error());
	//$inv_id = $IId;
	//$invnum = $InvNum;
	//$old_path = $r_path;
	//require_once $r_path.'../scripts/cart/send_lab.php';
	//$r_path = $old_path;
}
mysql_free_result($get_info);
?>
