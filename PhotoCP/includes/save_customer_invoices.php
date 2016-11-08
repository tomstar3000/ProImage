<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';

require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_send_email.php';
if($path[1] == "Open" || $path[1] == "All") $IId = $path[2];
else $IId = $path[4];
$Folder = '../../'.$r_path."toPhatFoto";

$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `cust_customers`.`cust_phone`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_email`, `cust_shipping`.*, `orders_invoice`.* FROM `orders_invoice` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` INNER JOIN `cust_shipping` ON `cust_shipping`.`cust_id` = `orders_invoice`.`cust_id` WHERE `invoice_id` = '$IId';"); 
$getInfo = $getInfo->Rows();

$InvNum = $getInfo[0]['invoice_num'];
$InvEnc = $getInfo[0]['invoice_enc'];
$IncComm = $getInfo[0]['invoice_comments'];
$Date = $getInfo[0]['invoice_date'];
$FName = $getInfo[0]['cust_fname'];
$LName = $getInfo[0]['cust_lname'];
$Phone = $getInfo[0]['cust_phone'];
$Email = $getInfo[0]['cust_email'];
$SFName = $getInfo[0]['cust_ship_fname'];
$SLName = $getInfo[0]['cust_ship_lname'];
$SAdd = $getInfo[0]['cust_ship_add'];
$SSuite = $getInfo[0]['cust_ship_suite_apt'];
$SCity = $getInfo[0]['cust_ship_city'];
$SState = $getInfo[0]['cust_ship_state'];
$SZip = $getInfo[0]['cust_ship_zip'];
$PSale = $getInfo[0]['prod_sale'];
$Total = $getInfo[0]['invoice_total'];
$Sent = $getInfo[0]['invoice_accepted'];

$getImgs = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getImgs->mysql("SELECT * FROM `orders_invoice_photo` INNER JOIN `photo_event_images` ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` WHERE `invoice_id` = '$IId' GROUP BY `orders_invoice_photo`.`image_id`;");

$getBdrs = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getBdrs->mysql("SELECT `orders_invoice_border`.*, `photo_event_images`.*, `prod_products`.`prod_name` FROM `orders_invoice_border`
	INNER JOIN `photo_event_images` 
		ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id` 
	INNER JOIN `prod_products` 
		ON `prod_products`.`prod_id` = `orders_invoice_border`.`border_id` 
	WHERE `invoice_id` = '$IId';"); 

if($cont == "ToLab"){
	$updInv = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$updInv->mysql("UPDATE `orders_invoice` SET `invoice_accepted` = 'p' WHERE `invoice_id` = '$IId';");
	$Error = "Your order has been sent to the lab";
	$Sent = 'y';
}
?>
