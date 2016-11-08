<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++)$r_path .= "../";
define('Allow Scripts', true);

ob_start();

$r_path = "/srv/proimage/current/";
require_once($r_path.'scripts/fnct_send_email.php');
require_once $r_path.'scripts/fnct_phpmailer.php';
require_once($r_path.'Connections/cp_connection.php');
mysql_select_db($database_cp_connection, $cp_connection);

$query_get_inv = "SELECT `invoice_id`, `invoice_num`, invoice_enc FROM `orders_invoice` WHERE `invoice_accepted` = 'p'";
$get_inv = mysql_query($query_get_inv, $cp_connection) or die(mysql_error());

echo "Building Images<br />";
while($row_get_inv = mysql_fetch_assoc($get_inv)){
	$inv_id = $row_get_inv['invoice_id'];
	$invnum = $row_get_inv['invoice_num'];
	$encnum = $row_get_inv['invoice_enc'];
	echo $inv_id." - ".$invnum."<br />";
	include($r_path.'scripts/cart/send_lab.php');
	unset($inv_id);
	unset($invnum);
	unset($encnum);
}

$page = ob_get_contents();
ob_end_clean();

send_email("development@proimagesoftware.com", "development@proimagesoftware.com", "Photoexpress CronJob", $page, true, false, false);
?>