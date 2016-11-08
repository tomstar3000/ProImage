<?
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include ($r_path.'scripts/cart/security.php');
function findtax($state, $total){
	global $cp_connection;
	
	$query_get_tax = "SELECT `tax_percent` FROM `billship_tax_states` WHERE `tax_state` = '$state'";
	$get_tax = mysql_query($query_get_tax, $cp_connection) or die(mysql_error());
	$row_get_tax = mysql_fetch_assoc($get_tax);
	$totalRows_get_tax = mysql_num_rows($get_tax);
	
	if($totalRows_get_tax != 0){
		return $total * ($row_get_tax['tax_percent']/100);
	} else {
		return 0;
	}
	mysql_free_result($get_tax);

}
function findcountytax($zip, $total){
	global $cp_connection;
	
	$query_get_tax_zip = "SELECT `tax_count_percent` FROM `billship_tax_county` WHERE `tax_count_zip` = '$zip'";
	$get_tax_zip = mysql_query($query_get_tax_zip, $cp_connection) or die(mysql_error());
	$row_get_tax_zip = mysql_fetch_assoc($get_tax_zip);
	$totalRows_get_tax_zip = mysql_num_rows($get_tax_zip);
	
	if($totalRows_get_tax_zip != 0){
		return $total * ($row_get_tax_zip['tax_count_percent']/100);
	} else {
		return 0;
	}
	
	mysql_free_result($get_tax_zip);
}
?>