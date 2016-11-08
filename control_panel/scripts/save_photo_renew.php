<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
define ("Allow Scripts", true);
$is_creditcard = true;
$is_gateway = "Authorize_AIM";
$is_live = true;
$is_error = false;
$is_process = true;
$is_capture = "AUTH_CAPTURE";
$is_method = "CC";
$approval = false;
$Error = false;

include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_send_email.php';

$CId = $CustId;
if(isset($_POST['Controller']) && $_POST['Controller'] == "Renew"){
	$SvLvl = clean_variable($_POST['Service_Level'],true);
	$approval = false;
	$reciever = "info@proimagesoftware.com";
	$Email = $row_get_exp['cust_email'];
	//$Email = "development@proimagesoftware.com";
	$subject = "Pro Image Software: Membership Renewel";
	$query_get_billing = "SELECT * FROM `cust_billing` WHERE `cust_id` = '$CId' LIMIT 0,1";
	$get_billing = mysql_query($query_get_billing, $cp_connection) or die(mysql_error());
	$row_get_billing = mysql_fetch_assoc($get_billing);
	$total_get_billing = mysql_num_rows($get_billing);
	if($total_get_billing > 0){
		$query_get_cost = "SELECT * FROM `prod_products` WHERE `prod_id` = '$SvLvl' LIMIT 0,1";
		$get_cost = mysql_query($query_get_cost, $cp_connection) or die(mysql_error());
		$row_get_cost = mysql_fetch_assoc($get_cost);
		$recurring = $row_get_cost['prod_year'];
		if($recurring == "y"){
			$bill_date = date("Y-m-d h:i:s",mktime(0,0,0,date("m"),date("d"),date("Y")+1));
		} else {
			$bill_date = date("Y-m-d h:i:s",mktime(0,0,0,date("m")+1,date("d"),date("Y")));
		}
		$total = $row_get_cost['prod_price'];
		$fee = $row_get_cost['prod_fee'];
		$quota = $row_get_cost['prod_qty']*1024;
		$fee = 0;
		//$grandtotal = $total+$fee;
		$grandtotal = $total;
		if($grandtotal > 0){
			$BId = $row_get_billing['cust_id'];
			$BFName = $row_get_billing['cust_bill_fname'];
			$BMName = $row_get_billing['cust_bill_mname'];
			$BLName = $row_get_billing['cust_bill_lname'];
			$BSuffix = $row_get_billing['cust_bill_suffix'];
			$BCName = $row_get_billing['cust_bill_cname'];
			$BAdd = $row_get_billing['cust_bill_add'];
			$BSuiteApt = $row_get_billing['cust_bill_suite_apt'];
			$BAdd2 = $row_get_billing['cust_bill_add_2'];
			$BCity = $row_get_billing['cust_bill_city'];
			$BState = $row_get_billing['cust_bill_state'];
			$BZip = $row_get_billing['cust_bill_zip'];
			$BCount = $row_get_billing['cust_bill_counry'];
			$CCNum = $row_get_billing['cust_bill_ccnum'];
			$CC4Num = $row_get_billing['cust_bill_ccshort'];
			$CCV = $row_get_billing['cust_bill_ccv'];
			$CType = $row_get_billing['cust_bill_cc_type_id'];
			$CCM = $row_get_billing['cust_bill_exp_month'];
			$CCY = $row_get_billing['cust_bill_year'];
			if($is_process){
				$old_path = $r_path;
				$r_path .= "../";
				require_once ($r_path.'scripts/cart/merchant_ini.php');
				$r_path = $old_path;
			} else {
				$approval = true;
			}
		} else {
			$approval = true;
		}
	} else {
		$approval = false;
		$message = "We were unable to find your records.";
	}
	if($approval == true){
		$query_get_last = "SELECT `invoice_id` AS `invoice_num` FROM `orders_invoice` ORDER BY `invoice_id` DESC LIMIT 0,1";
		$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
		$row_get_last = mysql_fetch_assoc($get_last);
		$inv_num = ($row_get_last['invoice_num']+101).date("Ymd");
		$inv_enc = md5($inv_num);
		mysql_free_result($get_last);
								
		$add = "INSERT INTO `orders_invoice` (`cust_id`,`cust_bill_id`,`invoice_num`,`invoice_enc`,`invoice_total`,`invoice_date`,`invoice_transaction`,`invoice_paid`,`invoice_paid_date`,`invoice_comp`,`invoice_comp_date`,`invoice_online`) VALUES ('$CId','$BId','$inv_num','$inv_enc','$total',NOW(),'$tran_id','y',NOW(),'y',NOW(),'y');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		
		$query_get_last = "SELECT `invoice_id` FROM `orders_invoice` WHERE `cust_id` = '$CId' ORDER BY `invoice_id` DESC LIMIT 0,1";
		$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
		$row_get_last = mysql_fetch_assoc($get_last);
		$inv_id = $row_get_last['invoice_id'];
		mysql_free_result($get_last);
		
		$add = "INSERT INTO `orders_invoice_prod` (`invoice_id`,`prod_id`,`invoice_prod_sale`,`invoice_prod_price`,`invoice_prod_fee`,`invoice_prod_qty`) VALUES ('$inv_id','$SvLvl','n','$total','$fee','1');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());	
		
    $upd = "UPDATE `cust_customers` SET `cust_due_date` = '$bill_date', `cust_paid` = 'y', `cust_active` = 'y', `cust_hold` = 'n', `cust_service` = '$SvLvl', `cust_rev` = '$fee', `cust_quota` = '$quota' WHERE `cust_id` = '$CId';";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		
		$message = "Thank you for contining your service with ProImageSoftware.com. We have charged $".number_format($total,2,".",",")." to your credit card ending in ".$CC4Num.$eol.$eol."Sincerely Pro Image Software.";
		
		send_email($reciever, $Email, $subject, $message, false, false, false);
	}
}
?>