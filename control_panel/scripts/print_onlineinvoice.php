<?php require_once('../../Connections/cp_connection.php'); ?>
<?php
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
$r_path = "";
for($n=0;$n<$count;$n++){
	$r_path .= "../";
}
mysql_select_db($database_cp_connection, $cp_connection);
define ("<?php echo $AevNet_Path; ?> CONTROL PANEL", true);
include $r_path.'config.php';
include $r_path.'security.php';
 
$total = 0;
if(count($Proj_id_items)){
	$s_array = $Proj_id_items;
	sort($s_array);
	$s_id = array_shift($s_array)-1;
	unset($s_array);
} else {
	$s_id = 0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $AevNet_Path; ?>: Control Panel</title>
<link href="../stylesheet/<?php echo $AevNet_Path; ?>.css" rel="stylesheet" type="text/css" />

</head>
<body>
<?php 
$inv_enc = (isset($_GET['id']) && !isset($_SESSION['invoice_number'])) ? $_GET['id'] : 0;

// Get Invoice Information
include ($r_path.'scripts/scripts/cart/get_invoice.php');
$items = true;
// Get Member Information
include ($r_path.'scripts/scripts/cart/get_member_info.php');
// Get Billing Information
include ($r_path.'scripts/scripts/cart/get_billing_info.php');
// Get Shipping Information
include ($r_path.'scripts/scripts/cart/get_shipping_info.php');

// Get Cart Items
include ($r_path.'scripts/scripts/cart/get_invoice_items.php');
include ($r_path.'scripts/scripts/cart/get_ship_speeds.php');

// Display Shopping Cart
include ($r_path.'scripts/scripts/cart/disp_cart_info.php');

?>
</body>
</html>
