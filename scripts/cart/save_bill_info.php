<?
if(isset($pathing)===false){
	require_once('../session_pathing.php');
}
include ($pathing.'scripts/cart/security.php');
$CId = $customer[0];
if($storeCC === false){
	$add = "INSERT INTO `cust_billing` (`cust_id`,`cust_bill_fname`,`cust_bill_lname`,`cust_bill_cname`,`cust_bill_add`,`cust_bill_add_2`,`cust_bill_city`,`cust_bill_state`,`cust_bill_zip`,`cust_bill_ccshort`,`cust_bill_ccv`,`cust_bill_exp_month`,`cust_bill_year`,`cust_bill_cc_type_id`,`cust_bill_default`) VALUES ('$CId','$BFName','$BLName','$BCName','$BAdd','$BAdd2','$BCity','$BState','$BZip','$CC4Num','','$CCM','$CCY','$CCT','$Default');";
} else {	
	$text = clean_variable($CCNum,"Store");
	$add = "INSERT INTO `cust_billing` (`cust_id`,`cust_bill_fname`,`cust_bill_lname`,`cust_bill_cname`,`cust_bill_add`,`cust_bill_add_2`,`cust_bill_city`,`cust_bill_state`,`cust_bill_zip`,`cust_bill_ccnum`,`cust_bill_ccshort`,`cust_bill_ccv`,`cust_bill_exp_month`,`cust_bill_year`,`cust_bill_cc_type_id`,`cust_bill_default`) VALUES ('$CId','$BFName','$BLName','$BCName','$BAdd','$BAdd2','$BCity','$BState','$BZip','$text','$CC4Num','','$CCM','$CCY','$CCT','$Default');";
	unset($text);
}
$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
?>