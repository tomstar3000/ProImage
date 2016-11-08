<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++)$r_path .= "../";
define('Allow Scripts', true);
require_once($r_path.'Connections/cp_connection.php');
mysql_select_db($database_cp_connection, $cp_connection);

$invoices_array = array("1052","1071","1137","1142","1146","1148","1160");
$invoicesnums_array = array("8052","8071","8137","8142","8146","8148","8160");

foreach($invoices_array as $k => $v){
	$inv_id = $v;
	$invnum = $invoicesnums_array[$k];
	echo $inv_id." - ".$invnum."<br />";
	include($r_path.'scripts/cart/send_lab.php');
}

?>