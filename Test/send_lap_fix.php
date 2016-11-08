<?


define('DEV_EMAIL', 'chad.serpan@gmail.com');
define('TESTING', true);

$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++)$r_path .= "../";
define('Allow Scripts', true);
$r_path = '/srv/proimage/current/';
require_once($r_path.'Connections/cp_connection.php');
mysql_select_db($database_cp_connection, $cp_connection);

$invoices_array = array("10148");
$invoicesnums_array = array("17148");
$encnum_array = array('a9a7ab1b714b04eff25ce5e783135902');

foreach($invoices_array as $k => $v){
	$inv_id = $v;
	$invnum = $invoicesnums_array[$k];
	$encnum = $encnum_array[$k];
	echo $inv_id." - ".$invnum."<br />";
	include($r_path.'scripts/cart/send_lab.php');
}

?>