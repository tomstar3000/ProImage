<? 
require_once('../Connections/cp_connection.php');

$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$accountDisabledQuery = "SELECT `cust_canceled`, `cust_active`, `cust_del`, `cust_hold` FROM `cust_customers` WHERE `cust_photo` = 'y' AND `cust_id` = $CustId GROUP BY `cust_id` ORDER BY `cust_handle` ASC";

$getInfo = mysql_query( $accountDisabledQuery, $cp_connection);
$row_get_info = mysql_fetch_assoc($getInfo);

$isCancelled = $row_get_info['cust_canceled'] == 'y';
$isInactive = $row_get_info['cust_active'] == 'n';
$isDeleted = $row_get_info['cust_del'] == 'y';
$isOnHold = $row_get_info['cust_hold'] == 'y';

$isAccountDisabled = $isCancelled || $isInactive || $isDeleted || $isOnHold;