<?
if(isset($pathing)===false){
	require_once('../session_pathing.php');
}
include ($pathing.'scripts/cart/security.php');
function calculate_ship_FEDEX($width,$height,$depth,$weight,$speed){
	global $cp_connection;

	if($weight == 0 || $weight  == ""){
		$weight = 10;
	}
	//mysql_select_db($database_cp_connection, $cp_connection);
	$query_get_ship_price = "SELECT `ship_rate` FROM `billship_shipping_rates` WHERE `ship_speed_id` = '$speed' AND `ship_rate_weight` = '$weight'";
	$get_ship_price = mysql_query($query_get_ship_price, $cp_connection) or die(mysql_error());
	$row_get_ship_price = mysql_fetch_assoc($get_ship_price);
	//$totalRows_get_ship_price = mysql_num_rows($get_ship_price);
	
	$rate = $row_get_ship_price['ship_rate'];
	
	mysql_free_result($get_ship_price);
	
	return $rate;
}

?>