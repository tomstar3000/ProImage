<?
$getUsrInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
if(isset($_GET['admin']) && $_GET['admin'] == "true"){
	$adminid = clean_variable($_GET['adminid'],true);
	$getUsrInfo->mysql("SELECT `cust_handle`, `cust_id`,`cust_cname`,`cust_email`,`cust_quota`, `cust_due_date`, `cust_service`, `cust_paid` FROM `cust_customers` WHERE `cust_id` = '$adminid';");
} else {
	$getUsrInfo->mysql("SELECT `cust_handle`, `cust_id`,`cust_cname`,`cust_email`,`cust_quota`, `cust_due_date`, `cust_service`, `cust_paid`, `cust_created` FROM `cust_customers` WHERE `cust_id` = '$loginsession[2]';");
}

if($getUsrInfo->TotalRows() == 0){ $GoTo = "index.php"; header(sprintf("Location: %s", $GoTo)); }

$getUsrInfo = $getUsrInfo->Rows();
if(isset($_GET['admin'])) $loginsession[0] = $getUsrInfo[0]['cust_session'];
$CustId = $getUsrInfo[0]['cust_id'];
$DueDate = $getUsrInfo[0]['cust_due_date'];
$CName = $getUsrInfo[0]['cust_cname'];
$CHandle = $getUsrInfo[0]['cust_handle'];
$Quota = $getUsrInfo[0]['cust_quota'];
$Email = $getUsrInfo[0]['cust_email'];
$SvLvl = $getUsrInfo[0]['cust_service'];
$UsrPaid = $getUsrInfo[0]['cust_paid'];
$DueDate = $getUsrInfo[0]['cust_due_date'];

$getUsrInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getUsrInfo->mysql("SELECT SUM(`photo_event_images`.`image_size`) AS `image_size` 
	FROM `photo_event_images` 
	INNER JOIN `photo_event_group`
		ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id`
	INNER JOIN `photo_event`
		ON `photo_event`.`event_id` = `photo_event_group`.`event_id`
	WHERE `photo_event_images`.`cust_id` = '$CustId' 
		AND `photo_event_images`.`image_active` = 'y'
		AND `photo_event`.`event_use` = 'y';");
		
$MBUsed = $getUsrInfo->Rows();
$MBUsed = $MBUsed[0]['image_size'];
$MBLeft = $Quota-$MBUsed;
$PercUsed = round(($MBUsed/$Quota)*100)/100;

$getUsrInfo->mysql("SELECT `invoice_date` FROM `orders_invoice` WHERE `cust_id` = '$CustId' ORDER BY `invoice_id` ASC LIMIT 0,1;");
$SignUpDate = $getUsrInfo->Rows();
$SignUpDate = $SignUpDate[0]['invoice_date'];
//$DueDate = date("Y-m-d H:i:s", mktime(substr($InvDate,11,2),substr($InvDate,14,2),substr($InvDate,17,2),substr($InvDate,5,2),substr($InvDate,8,2),substr($InvDate,0,4)+1));
?>
