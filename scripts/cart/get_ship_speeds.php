<?
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include ($r_path.'scripts/cart/security.php');
$query_ship_speeds = "SELECT * FROM `billship_shipping_speeds` WHERE `ship_comp_id` = '$ship_comp' AND `ship_speed_us` = 'y' ORDER BY `ship_speed_id` ASC";
$ship_speeds = mysql_query($query_ship_speeds, $cp_connection) or die(mysql_error());
if(isset($_POST['Shipping_Speed']) && $_POST['Shipping_Company'] == $_POST['Shipping_Company_id'] && $is_variable != "invoice"){
	if($ship_comp == 7){
		$speed = -1;
		$shipping = 0;
	} else if($ship_comp == 8){
		$speed = -2;
		$shipping = 0;
	} else {
		$speed = $_POST['Shipping_Speed'];
	}
	$ship_info = array();
	$ship_info[0] = $ship_comp;
	$ship_info[1] = $speed;
	$_SESSION['Shipping_Info'] = $ship_info;
} else if((!isset($_POST['Shipping_Speed']) || !isset($_SESSION['Shipping_Info']) || $_POST['Shipping_Company'] != $_POST['Shipping_Company_id'])  && $is_variable != "invoice" && $ship_comp < 7) {
	$query_findlast = "SELECT `ship_speed_id` FROM `billship_shipping_speeds` WHERE `ship_comp_id` = '$ship_comp' AND `ship_speed_us` = 'y' ORDER BY `ship_speed_id` DESC LIMIT 0,1";
	$findlast = mysql_query($query_findlast, $cp_connection) or die(mysql_error());
	$row_findlast = mysql_fetch_assoc($findlast);

	$speed = $row_findlast['ship_speed_id'];
	
	mysql_free_result($findlast);
	
	$ship_info = array();
	$ship_info[0] = $ship_comp;
	$ship_info[1] = $speed;
	if(!isset($_SESSION['Shipping_Info'])){
		session_register("Shipping_Info");
	}
	$_SESSION['Shipping_Info'] = $ship_info;
} else if($is_variable != "invoice") {
	$ship_info = $_SESSION['Shipping_Info'];
	if($ship_comp == 7){
		$speed = -1;
		$ship_info[0] = $ship_comp;
		$ship_info[1] = $speed;
		$shipping = 0;
	} else if($ship_comp == 8){
		$speed = -2;
		$ship_info[0] = $ship_comp;
		$ship_info[1] = $speed;
		$shipping = 0;
	} else {
		$speed = $ship_info[1];
	}
	$_SESSION['Shipping_Info'] = $ship_info;
}
?>
