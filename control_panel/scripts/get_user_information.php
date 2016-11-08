<?
if(isset($_GET['admin']) && $_GET['admin'] == "true"){
	$adminid = clean_variable($_GET['adminid'],true);
	$query_get_info = "SELECT `cust_handle`, `cust_id`,`cust_cname`,`cust_email`,`cust_quota`, `cust_due_date`, `cust_service`, `cust_paid` FROM `cust_customers` WHERE `cust_id` = '$adminid'";
} else {
	$query_get_info = "SELECT `cust_handle`, `cust_id`,`cust_cname`,`cust_email`,`cust_quota`, `cust_due_date`, `cust_service`, `cust_paid` FROM `cust_customers` WHERE `cust_session` = '$loginsession[0]'";
}
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
$total_get_info = mysql_num_rows($get_info);
if($total_get_info == 0){
	$GoTo = "index.php";
	header(sprintf("Location: %s", $GoTo));
}
if(isset($_GET['admin'])) $loginsession[0] = $row_get_info['cust_session'];
$CustId = $row_get_info['cust_id'];
$DueDate =	$row_get_info['cust_due_date'];
$CName = $row_get_info['cust_cname'];
$CHandle = $row_get_info['cust_handle'];
$Quota = $row_get_info['cust_quota'];
$Email = $row_get_info['cust_email'];
$SvLvl = $row_get_info['cust_service'];
$UsrPaid = $row_get_info['cust_paid'];

mysql_free_result($get_info);

$query_get_info = "SELECT SUM(`photo_event_images`.`image_size`) AS `image_size` 
	FROM `photo_event_images` 
	INNER JOIN `photo_event_group`
		ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id`
	INNER JOIN `photo_event`
		ON `photo_event`.`event_id` = `photo_event_group`.`event_id`
	WHERE `photo_event_images`.`cust_id` = '$CustId' 
		AND `photo_event_images`.`image_active` = 'y'
		AND `photo_event`.`event_use` = 'y'";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

$MBUsed = $row_get_info['image_size'];
$MBLeft = $Quota-$MBUsed;
$PercUsed = round(($MBUsed/$Quota)*100)/100;

mysql_free_result($get_info);

$query_get_info = "SELECT `invoice_date` FROM `orders_invoice` WHERE `cust_id` = '$CustId' ORDER BY `invoice_id` DESC LIMIT 0,1";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
$SignUpDate = $row_get_info['invoice_date'];
//$DueDate = date("Y-m-d H:i:s", mktime(substr($SignUpDate,11,2),substr($SignUpDate,14,2),substr($SignUpDate,17,2),substr($SignUpDate,5,2),substr($SignUpDate,8,2),substr($SignUpDate,0,4)+1));

mysql_free_result($get_info);
?>
