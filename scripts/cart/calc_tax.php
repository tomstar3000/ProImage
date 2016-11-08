<?
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include ($r_path.'scripts/cart/security.php');
include ($r_path.'scripts/cart/fnct_find_tax.php');
$temptotal = $total-$discount+$shipping;
$tax = findtax($CheckOut[0]['BState'],$temptotal);
$tax += findcountytax($CheckOut[0]['BZip'],$temptotal);
?>