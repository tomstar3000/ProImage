<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)  $r_path .= "../";}
include $r_path.'security.php';

$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$monthsPaidQuery = "SELECT COUNT(`orders_invoice`.`invoice_id`) AS 'Paid Invoices' FROM `cust_customers`, `orders_invoice` WHERE `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` AND `cust_customers`.`cust_id` = $CustId AND `cust_customers`.`cust_created` <> `orders_invoice`.`invoice_paid_date` ORDER BY `orders_invoice`.`invoice_paid_date` DESC";
$getInfo->mysql( $monthsPaidQuery );

$getInfo = $getInfo->Rows();
$monthsPaid = $getInfo[0]['Paid Invoices'];