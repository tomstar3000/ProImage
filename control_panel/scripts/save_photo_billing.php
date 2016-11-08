<?  if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$temp_path = $r_path;
$r_path .= "../";
require_once($r_path.'scripts/cart/encrypt.php');
$r_path = $temp_path;

$FName = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : '';
$MInt = (isset($_POST['Middle_Initial'])) ? clean_variable($_POST['Middle_Initial'],true) : '';
$LName = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : '';
$Suffix = (isset($_POST['Suffix'])) ? clean_variable($_POST['Suffix'],true) : '';
$CName = (isset($_POST['Company_Name'])) ? clean_variable($_POST['Company_Name'],true) : '';
$Add = (isset($_POST['Address'])) ? clean_variable($_POST['Address'],true) : '';
$Add2 = (isset($_POST['Address_2'])) ? clean_variable($_POST['Address_2'],true) : '';
$SApt = (isset($_POST['Suite_Apt'])) ? clean_variable($_POST['Suite_Apt'],true) : '';
$City = (isset($_POST['City'])) ? clean_variable($_POST['City'],true) : '';
$State = (isset($_POST['State'])) ? clean_variable($_POST['State'],true) : '';
$Zip = (isset($_POST['Zip'])) ? clean_variable($_POST['Zip'],true) : '';
$Country = (isset($_POST['Country'])) ? clean_variable($_POST['Country'],true) : '';
$CType = (isset($_POST['Type_of_Card'])) ? clean_variable($_POST['Type_of_Card'],true) : '';
$CCNum = (isset($_POST['Credit_Card_Number'])) ? clean_variable($_POST['Credit_Card_Number'],true) : '';
$CC4Num = substr($CCNum,-4,4);
if($CCNum != ""){
	$CCNum = encrypt_data($CCNum);
	$addthis = ", `cust_bill_ccnum` = '$CCNum', `cust_bill_ccshort` = '$CC4Num' ";
} else {
	$addthis = "";
}
$CCV = (isset($_POST['CCV_Code'])) ? clean_variable($_POST['CCV_Code'],true) : '';
$CCM = (isset($_POST['Experation_Month'])) ? clean_variable($_POST['Experation_Month'],true) : '';
$CCY = (isset($_POST['Experation_Year'])) ? clean_variable($_POST['Experation_Year'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && $cont == 'edit'){
	if($cont == "edit"){
		$upd = "UPDATE `cust_billing` SET `cust_bill_fname` = '$FName',`cust_bill_mname` = '$MInt',`cust_bill_lname` = '$LName',`cust_bill_suffix` = '$Suffix',`cust_bill_cname` = '$CName',`cust_bill_add` = '$Add',`cust_bill_add_2` = '$Add2',`cust_bill_suite_apt` = '$SApt',`cust_bill_city` = '$City',`cust_bill_state` = '$State',`cust_bill_zip` = '$Zip',`cust_bill_counry` = '$Country',`cust_bill_ccv` = '$CCV',`cust_bill_exp_month` = '$CCM',`cust_bill_year` = '$CCY',`cust_bill_cc_type_id` = '$CType'".$addthis." WHERE `cust_id` = '$CustId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	$query_get_info = "SELECT * FROM `cust_billing` WHERE `cust_id` = '$CustId'";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$row_get_info = mysql_fetch_assoc($get_info);
	
	$FName = $row_get_info['cust_bill_fname'];
	$MInt = $row_get_info['cust_bill_mname'];
	$LName = $row_get_info['cust_bill_lname'];
	$Suffix = $row_get_info['cust_bill_suffix'];
	$CName = $row_get_info['cust_bill_cname'];
	$Add = $row_get_info['cust_bill_add'];
	$Add2 = $row_get_info['cust_bill_add_2'];
	$SApt = $row_get_info['cust_bill_suite_apt'];
	$City = $row_get_info['cust_bill_city'];
	$State = $row_get_info['cust_bill_state'];
	$Zip = $row_get_info['cust_bill_zip'];
	$Country = $row_get_info['cust_bill_counry'];
	$CType = $row_get_info['cust_bill_cc_type_id'];
	$CC4Num = $row_get_info['cust_bill_ccshort'];
	$CCV = $row_get_info['cust_bill_ccv'];
	$CCM = $row_get_info['cust_bill_exp_month'];
	$CCY = $row_get_info['cust_bill_year'];
	
	mysql_free_result($get_info);
}
?>
