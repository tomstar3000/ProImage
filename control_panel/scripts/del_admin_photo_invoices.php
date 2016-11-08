<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_phpmailer.php';
$items = array();
$items = $_POST['Invoices_items'];
$ids = $_POST['Ids'];
$qtys = $_POST['Quality'];
$print = $_POST['Printer'];
$ship = $_POST['Shipper'];
if (strtoupper(substr(PHP_OS,0,3)=='WIN')) { 
	$eol="\r\n"; 
} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) { 
	$eol="\r"; 
} else { 
	$eol="\n"; 
}
function delete_vals($value, $qty, $print, $ship){
	global $cp_connection, $path, $eol;
	if($path[1] == "Open"){
		$upd = "UPDATE `orders_invoice` SET `invoice_pers_quality` = '$qty',`invoice_pers_print` = '$print' WHERE `invoice_id` = '$value'";
		$upd2 = "UPDATE `orders_invoice` SET `invoice_printed` = 'y',`invoice_printed_date` = NOW() WHERE `invoice_id` = '$value'";
		$orderIs = "Printed";
	} else if ($path[1] == "Ship"){
		$upd = "UPDATE `orders_invoice` SET `invoice_pers_quality` = '$qty',`invoice_pers_ship` = '$ship' WHERE `invoice_id` = '$value'";
		$upd2 = "UPDATE `orders_invoice` SET `invoice_comp` = 'y',`invoice_comp_date` = NOW() WHERE `invoice_id` = '$value'";
		$orderIs = "Shipped";
	}
	$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	$upd2info = mysql_query($upd2, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `orders_invoice`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
	$query_get_info = "SELECT `cust_customers`.`cust_fname`,`cust_customers`.`cust_lname`,`cust_customers`.`cust_email`,`orders_invoice`.`invoice_num`, `photo_customers`.`cust_handle` AS `photo_handle`, `photo_customers`.`cust_email` AS `photo_email`
		FROM `cust_customers`
		INNER JOIN `orders_invoice`
			ON `orders_invoice`.`cust_id` = `cust_customers`.`cust_id`
		INNER JOIN `orders_invoice_photo` 
			ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id`
		INNER JOIN `photo_event_images`
			ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id`
		INNER JOIN `cust_customers` AS `photo_customers`
			ON `photo_customers`.`cust_id` = `photo_event_images`.`cust_id`
		WHERE `orders_invoice`.`invoice_id` = '$value'
		GROUP BY `orders_invoice`.`invoice_id`
		LIMIT 0,1";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$row_get_info = mysql_fetch_assoc($get_info);
	$msg = "Dear ".$row_get_info['cust_fname']." ".$row_get_info['cust_lname'].$eol.$eol;
	$msg .= "Thank you for your recent order #".$row_get_info['invoice_num']." from ".$row_get_info['photo_handle'].".".$eol;
	if($orderIs == "Printed"){
		$subject = "Order has been printed";
		$msg .= "This email is to inform you that your order has been printed, checked for quality, is packaged and will be shipped out shortly. You will receive another email when this order is shipped out. Should you have any questions regarding this order please feel free to email us at info@proimagesoftware.com and please reference your order number.".$eol;
	} else if($orderIs == "Shipped"){
		$subject = "Order has been shipped";
		$msg .= "This email is to inform you that your order has been shipped out. You will receive another email with a tracking number from the carrier you selected for shipping. Should you have any questions regarding this order please feel free to email us at info@proimagesoftware.com and please reference your order number.".$eol;
	}
	$msg .= "Thank you,".$eol;
	$msg .= "Pro Image Staff";
	
	$mail = new PHPMailer();
	//$mail -> IsSMTP();
	$mail -> Host = "smtp.proimagesoftware.com";
	$mail -> IsHTML(false);
	$mail -> IsSendMail();
	$mail -> From = 'info@proimagesoftware.com';
	$mail -> FromName = "Pro Image";
	$mail -> AddAddress($row_get_info['cust_email']);
	$mail -> Subject = $subject;
	$mail -> Body = $msg;
	$mail -> Send();
}
if(count($items)>0){
	foreach ($items as $key => $value){
		$temp_key = array_search($value, $ids);
		delete_vals($value, clean_variable($qtys[$temp_key]), clean_variable($print[$temp_key]), clean_variable($ship[$temp_key]));
	}
}
if($path[1] == "Open"){
	$path[1] = "Ship";
} else if ($path[1] == "Ship"){
	$path[1] = "All";
}
?>