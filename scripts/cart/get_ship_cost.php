<?
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include ($r_path.'scripts/cart/security.php');
$query_ship_cost = "SELECT `ship_speed_price` FROM `billship_shipping_speeds` WHERE `ship_speed_id` = '$speed'";
$ship_cost = mysql_query($query_ship_cost, $cp_connection) or die(mysql_error());
$row_ship_cost = mysql_fetch_assoc($ship_cost);

$shipping = $row_ship_cost['ship_speed_price'];
?>