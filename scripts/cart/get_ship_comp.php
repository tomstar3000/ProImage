<?
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include ($r_path.'scripts/cart/security.php');
$query_get_ship_comp = "SELECT * FROM `billship_shipping_companies` WHERE `ship_comp_us` = 'y' ORDER BY `ship_comp_name` ASC";
$get_ship_comp = mysql_query($query_get_ship_comp, $cp_connection) or die(mysql_error());
//$row_get_ship_comp = mysql_fetch_assoc($get_ship_comp);
if(isset($_POST['Shipping_Company'])){
	$ship_comp = $_POST['Shipping_Company'];
} else {
	if(isset($_SESSION['Shipping_Info'])){
		$ship_comp = $_SESSION['Shipping_Info'];
		$ship_comp = $ship_comp[0];
	} else {
		$query_findlast = "SELECT `ship_comp_id` FROM `billship_shipping_companies` WHERE `ship_comp_us` = 'y' ORDER BY `ship_comp_id` ASC LIMIT 0,1";
		$findlast = mysql_query($query_findlast, $cp_connection) or die(mysql_error());
		$row_findlast = mysql_fetch_assoc($findlast);
		$ship_comp = $row_findlast['ship_comp_id'];
	}
}
?>