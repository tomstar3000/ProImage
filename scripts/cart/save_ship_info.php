<?
if(isset($pathing)===false){
	require_once('../session_pathing.php');
}
include ($pathing.'scripts/cart/security.php');
$CId = $customer[0];
$add = "INSERT INTO `cust_shipping` (`cust_id`,`cust_ship_fname`,`cust_ship_lname`,`cust_ship_cname`,`cust_ship_add`,`cust_ship_add_2`,`cust_ship_city`,`cust_ship_state`,`cust_ship_zip`,`cust_ship_default`) VALUES ('$CId','$SFName','$SLName','$SCName','$SAdd','$SAdd2','$SCity','$SState','$SZip','$Default');";
$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
?>