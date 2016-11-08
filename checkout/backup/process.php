<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
$r_path = "";
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);

session_start();
$cart = $_SESSION['cart'];
$cart = explode("[+]",$cart);
$photographer = $_SESSION['photo'];
$code = $_SESSION['code'];
$is_variable = "process";

require_once ($r_path.'scripts/fnct_format_phone.php');
require_once ($r_path.'scripts/fnct_get_variable.php');
require_once ($r_path.'scripts/fnct_clean_entry.php');
require_once ($r_path.'scripts/cart/save_cust_info.php');
$is_creditcard = true;
$is_gateway = "Authorize_AIM";
$is_live = true;
$is_error = false;
$is_process = true;
$is_type = "AUTH_ONLY";
$is_method = "CC";
$approval = false;
$discName = "";
$total = clean_variable($_POST['Total'],true);
$discount = clean_variable($_POST['Discount'],true);
$shipping = clean_variable($_POST['Shipping'],true);
$extra = 0;
$tax = clean_variable($_POST['Tax'],true);
$grandtotal = $total-$discount+$shipping+$extra+$tax;
$CCNum = $CheckOut[0]['CCNum'];
$CC4Num =  $CheckOut[0]['CC4Num'];
$Default = 'n';
$CCV = $CheckOut[0]['CCV'];
$CCY = $CheckOut[0]['CCY'];
$CCM = $CheckOut[0]['CCM'];
if(strlen($CCM) == 1){
	$CCM = "0".$CCM;
}
$CCT = $CheckOut[0]['CCT'];
$BFName = $CheckOut[0]['BFName'];
$BLName = $CheckOut[0]['BLName'];
$BCName = "";
$BAdd = $CheckOut[0]['BAdd'];
$BAdd2 = $CheckOut[0]['BAdd2'];
$BSuite = $CheckOut[0]['BSuite'];
$BCity = $CheckOut[0]['BCity'];
$BState = $CheckOut[0]['BState'];
$BZip = $CheckOut[0]['BZip'];
$BCount = $CheckOut[0]['BCount'];
$Phone = $CheckOut[2]['Phone'];
$Mobile = "";
$Fax = "";
$Work = "";
$Ext = "";
$Email = $CheckOut[2]['Email'];
$SFName = $CheckOut[1]['SFName'];
$SLName = $CheckOut[1]['SLName'];
$SCName = "";
$SAdd = $CheckOut[1]['SAdd'];
$SAdd2 = $CheckOut[1]['SAdd2'];
$SSuite = $CheckOut[1]['SSuite'];
$SCity = $CheckOut[1]['SCity'];
$SState = $CheckOut[1]['SState'];
$SZip = $CheckOut[1]['SZip'];
$SCount = $CheckOut[1]['SCount'];
$discId = $CheckOut[2]['disId'];
$discode = $CheckOut[2]['discode'];
$Comm = $CheckOut[2]['Comm'];
$ip = $_SERVER['REMOTE_ADDR'];

if($is_process && $grandtotal > 0){
	require_once ($r_path.'scripts/cart/merchant_ini.php');
} else {
	$approval = true;
}
if($approval){
	require_once($r_path.'Connections/cp_connection.php');
	mysql_select_db($database_cp_connection, $cp_connection);
	require_once($r_path.'scripts/cart/get_ship_comp.php');
	require_once($r_path.'scripts/cart/get_ship_speeds.php');
	$add = "INSERT INTO `cust_customers` (`cust_fname`,`cust_lname`,`cust_add`,`cust_suite_apt`,`cust_city`,`cust_state`,`cust_zip`,`cust_country`,`cust_phone`,`cust_email`,`cust_ip`,`cust_online`) VALUES ('$BFName','$BLName','$BAdd','$BSuite','$BCity','$BState','$BZip','$BCount','$Phone','$Email','$ip','n');";
	$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
	
	$query_get_last = "SELECT `cust_id` FROM `cust_customers` WHERE `cust_lname` = '$BLName' ORDER BY `cust_id` DESC LIMIT 0,1";
	$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
	$row_get_last = mysql_fetch_assoc($get_last);
	
	$cust_id = $row_get_last['cust_id'];
	
	$add = "INSERT INTO `cust_billing` (`cust_id`,`cust_bill_fname`,`cust_bill_lname`,`cust_bill_add`,`cust_bill_suite_apt`,`cust_bill_city`,`cust_bill_state`,`cust_bill_zip`,`cust_bill_counry`,`cust_bill_ccnum`,`cust_bill_ccshort`,`cust_bill_ccv`,`cust_bill_exp_month`,`cust_bill_year`,`cust_bill_cc_type_id`,`cust_bill_default`) VALUES ('$cust_id','$BFName','$BLName','$BAdd','$BSuite','$BCity','$BState','$BZip','$BCount','$CCNum','$CC4Num','$CCV','$CCM','$CCY','$CCT','n');";
	$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
	
	$query_get_last = "SELECT `cust_bill_id` FROM `cust_billing` WHERE `cust_id` = '$cust_id' ORDER BY `cust_bill_id` DESC LIMIT 0,1";
	$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
	$row_get_last = mysql_fetch_assoc($get_last);
	
	$bill_id = $row_get_last['cust_bill_id'];
	
	$add = "INSERT INTO `cust_shipping` (`cust_id`,`cust_ship_fname`,`cust_ship_lname`,`cust_ship_add`,`cust_ship_suite_apt`,`cust_ship_city`,`cust_ship_state`,`cust_ship_zip`,`cust_ship_country`,`cust_ship_default`) VALUES ('$cust_id','$SFName','$SLName','$SAdd','$SSuite','$SCity','$SState','$SZip','$SCount','n');";
	$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
	
	$query_get_last = "SELECT `cust_ship_id` FROM `cust_shipping` WHERE `cust_id` = '$cust_id' ORDER BY `cust_ship_id` DESC LIMIT 0,1";
	$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
	$row_get_last = mysql_fetch_assoc($get_last);
	
	$ship_id = $row_get_last['cust_ship_id'];
	
	$query_get_last = "SELECT `invoice_id` FROM `orders_invoice` ORDER BY `invoice_id` DESC LIMIT 0,1";
	$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
	$row_get_last = mysql_fetch_assoc($get_last);
	
	$invnum = intval($row_get_last['invoice_id'])+7001;
	$encnum = md5($invnum);
	
	$add = "INSERT INTO `orders_invoice` (`cust_id`,`cust_bill_id`,`cust_ship_id`,`invoice_num`,`invoice_enc`,`invoice_total`,`invoice_disc`,`invoice_tax`,`invoice_ship`,`invoice_grand`,`invoice_date`,`ship_speed_id`,`invoice_transaction`,`invoice_paid_date`,`invoice_paid`,`invoice_comments`) VALUES ('$cust_id','$bill_id','$ship_id','$invnum','$encnum','$total','$discount','$tax','$shipping','$grandtotal',NOW(),'$speed','$tran_id',NOW(),'y','$Comm');";
	$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
	
	$query_get_last = "SELECT `invoice_id` FROM `orders_invoice` WHERE `cust_id` = '$cust_id' ORDER BY `invoice_id` DESC LIMIT 0,1";
	$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
	$row_get_last = mysql_fetch_assoc($get_last);
	
	$inv_id = $row_get_last['invoice_id'];
	
	if($discode!== false && $discount > 0){
		$add = "INSERT INTO `orders_invoice_codes` (`invoice_id`,`disc_total`,`disc_id`) VALUES ('$inv_id','$discount','$discId');";
		$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
	}
	
	$query_get_price = "SELECT `photo_event_price`.`photo_price`, `photo_event_price`.`photo_color`, `photo_event_price`.`photo_blk_wht`, `photo_event_price`.`photo_sepia`, `photo_event`.`photo_to_lab` FROM `cust_customers` INNER JOIN `photo_event` ON `photo_event`.`cust_id` = `cust_customers`.`cust_id` INNER JOIN `photo_event_price` ON `photo_event_price`.`photo_event_price_id` = `photo_event`.`event_price_id` WHERE `cust_handle` = '$photographer' AND `event_num` = '$code' GROUP BY `photo_event_price_id`";
	$get_price = mysql_query($query_get_price, $cp_connection) or die(mysql_error());
	$row_get_price = mysql_fetch_assoc($get_price);
	$price = explode(",",$row_get_price['photo_price']);
	mysql_free_result($get_price);
	foreach($cart as $k => $v){ 
		$cart_id = explode("-",$v);
		$cart_items = explode(",",$cart_id[1]);
		foreach($price as $key => $value){
			$cart_ids = explode(":",$cart_items[$key]);
			$cart_qty = explode(".",$cart_ids[1]);
			$temp_price = explode(":",$value);
			$query_get_info = "SELECT `prod_name`, `prod_price` FROM `prod_products` WHERE `prod_id` = '$temp_price[0]'";
			$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
			$row_get_info = mysql_fetch_assoc($get_info);
			$total_get_info = mysql_num_rows($get_info);
			if($total_get_info > 0 && ($cart_qty[0]>0 || $cart_qty[1]>0 || $cart_qty[2]>0)){
				$prod_cost = $row_get_info['prod_price'];
				$add = "INSERT INTO `orders_invoice_photo` (`invoice_id`,`image_id`,`invoice_image_price`,`invoice_image_cost`,`invoice_image_size_id`,`invoice_image_asis`,`invoice_image_bw`,`invoice_image_sepia`,`invoice_image_sale`) VALUES ('$inv_id','$cart_id[0]','$temp_price[1]','$prod_cost','$temp_price[0]','$cart_qty[0]','$cart_qty[1]','$cart_qty[2]','n');";
				$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
			}
		}
	}	
	$query_get_email = "SELECT `cust_email` FROM `cust_customers` INNER JOIN `photo_event` ON `photo_event`.`cust_id` = `cust_customers`.`cust_id` WHERE `cust_handle` = '$photographer' AND `event_num` = '$code'";
	$get_email = mysql_query($query_get_email, $cp_connection) or die(mysql_error());
	$row_get_email = mysql_fetch_assoc($get_email);
	
	ob_start();
	require_once($r_path.'checkout/invoice.php');
	$page = ob_get_contents();
	ob_end_clean();
	
	$Email = $Email;
	$reciever = $row_get_email['cust_email'];
	
	require_once $r_path.'scripts/fnct_phpmailer.php';
	$toChad = "To Photographer: ".$invnum." ".$reciever."\n";
	$mail = new PHPMailer();
	$mail -> Host = "smtp.proimagesoftware.com";
	$mail -> IsHTML(true);
	$mail -> IsSendMail();
	$mail -> From = $reciever;
	$mail -> FromName = $reciever;
	$mail -> AddAddress($Email);
	$mail -> Subject = "Invoice";
	$mail -> Body = $page;
	$mail -> Send();
	$Emailsent = true;
	
	
	$toChad .= "To Customer: ".$Email."\n";
	$mail = new PHPMailer();
	$mail -> Host = "smtp.proimagesoftware.com";
	$mail -> IsHTML(true);
	$mail -> IsSendMail();
	$mail -> From = "orders@proimagesoftware.com";
	$mail -> FromName = "orders@proimagesoftware.com";
	$mail -> AddAddress($reciever);
	$mail -> Subject = "Invoice";
	$mail -> Body = $page;
	$mail -> Send();
	$Emailsent = true;
	
	if($row_get_price['photo_to_lab'] == "y"){
	
		$lab = "info@photoexpresspro.com";
		$toChad .= "To Lab: ".$lab."\n";	
		$mail = new PHPMailer();
		$mail -> Host = "smtp.proimagesoftware.com";
		$mail -> IsHTML(true);
		$mail -> IsSendMail();
		$mail -> From = $reciever;
		$mail -> FromName = $reciever;
		$mail -> AddAddress($lab);
		$mail -> Subject = "Invoice To Lab";
		$mail -> Body = $page;
		$mail -> Send();
		$Emailsent = true;
		
		require_once($r_path.'scripts/cart/send_lab.php');
	}
	$mail = new PHPMailer();
	$mail -> Host = "smtp.proimagesoftware.com";
	//$mail -> IsHTML(true);
	$mail -> IsSendMail();
	$mail -> From = "info@photoexpress.com";
	$mail -> FromName = "info@photoexpress.com";
	$mail -> AddAddress("development@proimagesoftware.com");
	$mail -> Subject = "Debugging";
	$mail -> Body = $toChad;
	$mail -> Send();
	$Emailsent = true;
		
	session_destroy();
	$GoTo = "/checkout/thankyou.php?Photographer=".$photographer."&invoice=".$encnum;
	header(sprintf("Location: %s", $GoTo));
} else {
	$cart = implode("[+]",$cart);
	
	$GoTo = "checkout.php?error=".$message;
	header(sprintf("Location: %s", $GoTo));
}
?>