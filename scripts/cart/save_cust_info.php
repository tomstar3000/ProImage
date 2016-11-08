<?
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include ($pathing.'scripts/cart/security.php');
require_once ($r_path.'scripts/fnct_get_variable.php');
require_once ($r_path.'scripts/fnct_clean_entry.php');
$Bill_Info = array();
$Ship_Info = array();
$Pers_info = array();
$CheckOut = array();
$message = "";

if((isset($_POST['Controller']) && $_POST['Controller'] == "edit") || $is_variable == "process" || (isset($_GET['error']) && !isset($_POST['Controller']))) {
	session_start();
	$CheckOut = $_SESSION['Check_Out'];
	$message = (isset($_GET['error'])) ? clean_variable($_GET['error'],true) : $message;
} else {
	$Bill_Info['BFName'] = (isset($_POST['Billing_First_Name'])) ? clean_variable($_POST['Billing_First_Name'],true) : "";
	$Bill_Info['BLName'] = (isset($_POST['Billing_Last_Name'])) ? clean_variable($_POST['Billing_Last_Name'],true) : "";
	$Bill_Info['BCName'] = (isset($_POST['Billing_Company'])) ? clean_variable($_POST['Billing_Company'],true) : "";
	$Bill_Info['BAdd'] = (isset($_POST['Billing_Address'])) ? clean_variable($_POST['Billing_Address'],true) : "";
	$Bill_Info['BAdd2'] = (isset($_POST['Billing_Address_2'])) ? clean_variable($_POST['Billing_Address_2'],true) : "";
	$Bill_Info['BSuite'] = (isset($_POST['Billing_SuiteApt'])) ? clean_variable($_POST['Billing_SuiteApt'],true) : "";
	$Bill_Info['BCity'] = (isset($_POST['Billing_City'])) ? clean_variable($_POST['Billing_City'],true) : "";
	$Bill_Info['BState'] = (isset($_POST['Billing_State'])) ? clean_variable($_POST['Billing_State'],true) : "";
	$Bill_Info['BZip'] = (isset($_POST['Billing_Zip'])) ? clean_variable($_POST['Billing_Zip'],true) : "";
	$Bill_Info['BCount'] = (isset($_POST['Billing_Country'])) ? clean_variable($_POST['Billing_Country'],true) : "USA";
	$Bill_Info['CCNum'] = (isset($_POST['Credit_Card_Number'])) ? clean_variable($_POST['Credit_Card_Number'],true) : "";
	$Bill_Info['CC4Num'] = substr($Bill_Info['CCNum'],-4,4);
	require_once $pathing.'scripts/cart/encrypt.php';
	$Bill_Info['CCNum'] = encrypt_data($Bill_Info['CCNum']);
	$Bill_Info['CCV'] = clean_variable($_POST['CCV_Code'],true);
	$Bill_Info['CCT'] = clean_variable($_POST['Credit_Card_Type'],true);
	$Bill_Info['CCY'] = clean_variable($_POST['Expiration_Year'],true);
	$Bill_Info['CCM'] = clean_variable($_POST['Expiration_Month'],true);
	if(isset($_POST['Same_as_Billing']) && $_POST['Same_as_Billing'] == "y"){
		$ship_same = true;
		$Ship_Info['SFName'] = $Bill_Info['BFName'];
		$Ship_Info['SFName'] = $Bill_Info['BFName'];
		$Ship_Info['SLName'] = $Bill_Info['BLName'];
		$Ship_Info['SCName'] = $Bill_Info['BCName'];
		$Ship_Info['SAdd'] = $Bill_Info['BAdd'];
		$Ship_Info['SSuite'] = $Bill_Info['BSuite'];
		$Ship_Info['SAdd2'] = $Bill_Info['BAdd2'];
		$Ship_Info['SCity'] = $Bill_Info['BCity'];
		$Ship_Info['SState'] = $Bill_Info['BState'];
		$Ship_Info['SZip'] = $Bill_Info['BZip'];
		$Ship_Info['SCount'] = $Bill_Info['BCount'];
	} else {
		$ship_same = false;
		$Ship_Info['SFName'] = (isset($_POST['Shipping_First_Name'])) ? clean_variable($_POST['Shipping_First_Name'],true) : "";
		$Ship_Info['SLName'] = (isset($_POST['Shipping_Last_Name'])) ? clean_variable($_POST['Shipping_Last_Name'],true) : "";
		$Ship_Info['SCName'] = (isset($_POST['Shipping_Company'])) ? clean_variable($_POST['Shipping_Company'],true) : "";
		$Ship_Info['SAdd'] = (isset($_POST['Shipping_Address'])) ? clean_variable($_POST['Shipping_Address'],true) : "";
		$Ship_Info['SSuite'] = (isset($_POST['Shipping_SuiteApt'])) ? clean_variable($_POST['Shipping_SuiteApt'],true) : "";
		$Ship_Info['SAdd2'] = (isset($_POST['Shipping_Address_2'])) ? clean_variable($_POST['Shipping_Address_2'],true) : "";
		$Ship_Info['SCity'] = (isset($_POST['Shipping_City'])) ? clean_variable($_POST['Shipping_City'],true) : "";
		$Ship_Info['SState'] = (isset($_POST['Shipping_State'])) ? clean_variable($_POST['Shipping_State'],true) : "";
		$Ship_Info['SZip'] = (isset($_POST['Shipping_Zip'])) ? clean_variable($_POST['Shipping_Zip'],true) : "";
		$Ship_Info['SCount'] = (isset($_POST['Shipping_Country'])) ? clean_variable($_POST['Shipping_Country'],true) : "USA";
	}
	$Pers_info['FName'] = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : "";
	$Pers_info['LName'] = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : "";
	
	$Pers_info['CName'] = (isset($_POST['Company_Name'])) ? clean_variable($_POST['Company_Name'],true) : "";
	$Pers_info['EIN_Id'] = (isset($_POST['EIN_Id'])) ? clean_variable($_POST['EIN_Id'],true) : "";
	$Pers_info['Reseller_Id'] = (isset($_POST['Reseller_Id'])) ? clean_variable($_POST['Reseller_Id'],true) : "";
	$Pers_info['Address'] = (isset($_POST['Address'])) ? clean_variable($_POST['Address'],true) : "";
	$Pers_info['Address_2'] = (isset($_POST['Address_2'])) ? clean_variable($_POST['Address_2'],true) : "";
	$Pers_info['City'] = (isset($_POST['City'])) ? clean_variable($_POST['City'],true) : "";
	$Pers_info['State'] = (isset($_POST['State'])) ? clean_variable($_POST['State'],true) : "";
	$Pers_info['Zip'] = (isset($_POST['Zip'])) ? clean_variable($_POST['Zip'],true) : "";
	$Pers_info['Phone'] = (isset($_POST['Phone_Number'])) ? clean_variable($_POST['Phone_Number'],true) : "";
	$Pers_info['Mobile'] = (isset($_POST['Mobile_Number'])) ? clean_variable($_POST['Mobile_Number'],true) : "";
	$Pers_info['Work'] = (isset($_POST['Work_Number'])) ? clean_variable($_POST['Work_Number'],true) : "";
	$Pers_info['Fax'] = (isset($_POST['Fax_Number'])) ? clean_variable($_POST['Fax_Number'],true) : "";
	$Pers_info['Email'] = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : "";
	$Pers_info['discodes'] = (isset($_POST['Discount_Codes'])) ? clean_variable($_POST['Discount_Codes'],true) : $disc;
	$Pers_info['discode'] = (isset($_POST['Discount_Code'])) ? clean_variable($_POST['Discount_Code'],true) : "";
	$Pers_info['discPrice'] = 0;
	$Pers_info['discPerc'] = 0;
	$Pers_info['discProd'] = 0;
	$Pers_info['discQty'] = 0;
	$Pers_info['Comm'] = (isset($_POST['Comments'])) ? clean_variable($_POST['Comments'],true) : "";
	$discode = $Pers_info['discode'];
	$discodes = $Pers_info['discodes'];
	if($discodes != 0){
		if($discodes == -1 && strlen(trim($discode)) > 0){
			$query_get_disc = "SELECT `prod_discount_codes`.* 
	FROM `prod_discount_codes` 
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `prod_discount_codes`.`cust_id` 
	WHERE `disc_code` = '$discode' 
		AND `cust_handle` = '$photographer' 
		AND `disc_use` = 'y' 
	ORDER BY `disc_id` DESC LIMIT 0,1";
		} else {
			$query_get_disc = "SELECT `prod_discount_codes`.*
				FROM `prod_discount_codes` 
				WHERE `disc_id` = '$discodes' 
					AND `disc_use` = 'y' 
				ORDER BY `disc_id` DESC LIMIT 0,1";
		}
		$get_disc = mysql_query($query_get_disc, $cp_connection) or die(mysql_error());
		$row_get_disc = mysql_fetch_assoc($get_disc);
		$total_get_disc = mysql_num_rows($get_disc);
		if($total_get_disc == 0){
			$message = "You have entered an invalid discount code";
			$is_enabled = true;
		} else {
			$Pers_info['disId'] = $row_get_disc['disc_id'];
			$discId = $Pers_info['disId'];
			$Pers_info['disname'] = $row_get_disc['disc_name'];
			
			$discode = $row_get_disc['disc_code'];
			$Pers_info['discode'] = $discode;
			
			$n = 0;	$total = 0; $Count = 0;
			foreach($cart as $k => $v){ 
				$cart_id = explode("-",$v);
				$cart_items = explode(",",$cart_id[1]);
				if($cart_id[2] == "I"){
					$realkey = 0;
					foreach($price as $key => $value){
						$cart_ids = explode(":",$cart_items[$k]);
						$cart_qty = explode(".",$cart_ids[1]);
						$temp_price = explode(":",$value);
						$total += $temp_price[1]*$cart_qty[0]+$temp_price[1]*$cart_qty[1]+$temp_price[1]*$cart_qty[2];
						if($temp_price[0] == $row_get_disc['prod_id'] && $cart_ids[0] == $row_get_disc['prod_id']){
							$Count += $cart_qty[0]+$cart_qty[1]+$cart_qty[2];
							$Price = $temp_price[1];
						}
						$realkey++; 
					}
				} else {
					foreach($cart_items as $key => $value){
						$cart_ids = explode(":",$value);
						$cart_qty = explode(".",$cart_ids[1]);
						$total += urldecode($cart_ids[3])*$cart_qty[0]+urldecode($cart_ids[3])*$cart_qty[1]+urldecode($cart_ids[3])*$cart_qty[2];
						if($cart_ids[0] == $row_get_disc['prod_id']){
							$Count += $cart_qty[0]+$cart_qty[1]+$cart_qty[2];
							$Price = $cart_ids[3];
						}
					}
				}
			}
			if($total > $row_get_disc['disc_limit'] || $row_get_disc['disc_limit'] == 0){
				//yyyy-mm-dd HH:ii:ss
				//0123456789012345678
				$testdate = date("Y-m-d H:i:s", mktime(substr($row_get_disc['disc_exp'],11,2),substr($row_get_disc['disc_exp'],14,2),substr($row_get_disc['disc_exp'],17,2),substr($row_get_disc['disc_exp'],5,2),substr($row_get_disc['disc_exp'],8,2),substr($row_get_disc['disc_exp'],0,4)));
				if($testdate < date("Y-m-d H:i:s", mktime(0,0,0,date("m"),date("d"),date("Y"))) && $row_get_disc['disc_exp'] != "0000-00-00 00:00:00"){
					$message = "You have entered an invalid discount code";
					$is_enabled = true;
					$_SESSION['disc'] = "null";
				} else if($row_get_disc['disc_num_uses'] !=0 && $row_get_disc['disc_num_used'] >= $row_get_disc['disc_num_uses']) {
					$message = "You have entered an invalid discount code";
					$is_enabled = true;
					$_SESSION['disc'] = "null";
				} else if($row_get_disc['disc_used'] == "y") {
					$message = "You have entered an invalid discount code";
					$is_enabled = true;
					$_SESSION['disc'] = "null";
				} else if($row_get_disc['disc_item'] > 0) {
					if($Count > 0){
						$Count = floor($Count/$row_get_disc['disc_item']);
						if($row_get_disc['disc_mult'] == "y"){
							$Pers_info['discPrice'] = $Price*($Count*$row_get_disc['disc_for']);
							$Pers_info['discPerc'] = 0;
							$Pers_info['discProd'] = $row_get_disc['prod_id'];
							$Pers_info['discQty'] = ($Count*$row_get_disc['disc_for']);
							$_SESSION['disc'] = $row_get_disc['disc_id'];
						} else {
							$Pers_info['discPrice'] = $Price*$row_get_disc['disc_for'];
							$Pers_info['discPerc'] = 0;
							$Pers_info['discProd'] = $row_get_disc['prod_id'];
							$Pers_info['discQty'] = $row_get_disc['disc_for'];
							$_SESSION['disc'] = $row_get_disc['disc_id'];
						}
					} else {
						$Pers_info['discPrice'] = 0;
						$Pers_info['discPerc'] = 0;
						$Pers_info['discProd'] = 0;
						$Pers_info['discQty'] = 0;
						$_SESSION['disc'] = "null";
					}
					unset($Count);
					unset($Price);
				} else if($row_get_disc['disc_roll_over'] == "y") {
					$query_get_total = "SELECT SUM(`disc_total`) AS `disc_total` FROM `orders_invoice_codes` WHERE `disc_id` = '$discId'";
					$get_total = mysql_query($query_get_total, $cp_connection) or die(mysql_error());
					$row_get_total = mysql_fetch_assoc($get_total);
					$discTotal = $row_get_total['disc_total'];
					if($discTotal >= $row_get_disc['disc_price']){
						$message = "You have entered an invalid discount code";
						$is_enabled = true;
					} else {
						$Pers_info['discPrice'] = $row_get_disc['disc_price']-$discTotal;
						$Pers_info['discPerc'] = 0;
						$Pers_info['discProd'] = 0;
						$Pers_info['discQty'] = 0;
						$_SESSION['disc'] = $row_get_disc['disc_id'];
					}
				} else {
					if($row_get_disc['disc_price'] > 0){
						$Pers_info['discPrice'] = $row_get_disc['disc_price'];
						$Pers_info['discPerc'] = 0;
						$Pers_info['discProd'] = 0;
						$Pers_info['discQty'] = 0;
						$_SESSION['disc'] = $row_get_disc['disc_id'];
					} else {
						$Pers_info['discPrice'] = 0;
						$Pers_info['discProd'] = 0;
						$Pers_info['discQty'] = 0;
						$Pers_info['discPerc'] = $row_get_disc['disc_percent'];
						$_SESSION['disc'] = $row_get_disc['disc_id'];
					}
				}
			} else {
				$message = "You don't have enough items in the cart.";
				$_SESSION['disc'] = "null";
			}	
		} 
		unset($cart_id);
		unset($cart_items);
		unset($items);
		unset($qtys);
		unset($total);
		unset($cart_ids);
		unset($cart_qty);
		unset($temp_price);
	} else {
		$Pers_info['disname'] = false;
	}
	
	$CheckOut[0] = $Bill_Info;
	$CheckOut[1] = $Ship_Info;
	$CheckOut[2] = $Pers_info;
	
	//session_start();
	
	if(!isset($_SESSION['Check_Out'])){
		session_register("Check_Out");
	}
	$_SESSION['Check_Out'] = $CheckOut;
	unset($Bill_Info);
	unset($Ship_Info);
	unset($Pers_info);
}

/*
unset($CheckOut);
$GoTo = "review.php";
header(sprintf("Location: %s", $GoTo));
*/

?>