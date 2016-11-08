<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
$r_path = ""; $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);

session_start();
$cart = $_SESSION['cart'];
$cart = explode("[+]",$cart);
$photographer = $_SESSION['photo'];
$code = $_SESSION['code'];
$disc = $_SESSION['disc'];
$qemail = $_SESSION['qemail'];

$cookieName = 'PhotoExpress_'.$code.$photographer;
$is_variable = "process";

require_once ($r_path.'scripts/fnct_format_phone.php');
require_once ($r_path.'scripts/fnct_get_variable.php');
require_once ($r_path.'scripts/fnct_clean_entry.php');
require_once ($r_path.'scripts/cart/save_cust_info.php');
$is_creditcard = true;
$is_gateway = "Authorize_AIM";
$is_live = (!defined("TESTING")||TESTING==false)?true:false; // Set to True
$is_error = false;
$is_process = (!defined("TESTING")||TESTING==false)?true:false; // Set to True
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
$discodes = $CheckOut[2]['discodes'];
$discprod = $CheckOut[2]['discProd'];
$disccnt = $CheckOut[2]['discQty'];
$Comm = $CheckOut[2]['Comm'];
$ip = $_SERVER['REMOTE_ADDR'];

$ship_info = $_SESSION['Shipping_Info'];
$ship_comp = $ship_info[0];
$speed = $ship_info[1];
		
$OldBCount = $BCount;
if($is_process && $grandtotal > 0){
	require_once ($r_path.'scripts/cart/merchant_ini.php');
} else {
	$approval = true;
}
unset($CCNum);
unset($CheckOut[0]['CCNum']);
$BCount = $OldBCount;
if($approval){
	
	require_once($r_path.'Connections/cp_connection.php');
	require_once $r_path.'scripts/fnct_phpmailer.php';
	mysql_select_db($database_cp_connection, $cp_connection);
	
	$query_photo_info = "SELECT `cust_customers`.`cust_id`, `cust_customers`.`cust_due_date`, `cust_customers`.`cust_handle`, `cust_customers`.`cust_email`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`
		FROM `cust_customers`
		INNER JOIN `photo_event` 
			ON `photo_event`.`cust_id` = `cust_customers`.`cust_id` 
		WHERE `cust_handle` = '$photographer' 
			AND `event_use` = 'y' 
			AND `event_num` = '$code'
			AND `cust_due_date` = '0000-00-00 00:00:00';";	
	$get_photo_info = mysql_query($query_photo_info, $cp_connection) or die(mysql_error());
	$row_photo_info = mysql_fetch_assoc($get_photo_info);
	$total_photo_info = mysql_num_rows($get_photo_info);
	if($total_photo_info > 0 && $row_photo_info['cust_due_date'] == "0000-00-00 00:00:00"){
		$bill_date = date("Y-m-d h:i:s",mktime(0,0,0,date("m")+1,date("d"),date("Y")));
		$update_photo_info = "UPDATE `cust_customers` SET `cust_due_date` = '$bill_date' WHERE `cust_id` = '".$row_photo_info['cust_id']."';";
		$update_photo_info = mysql_query($update_photo_info, $cp_connection) or die(mysql_error());
		
		$msg = $row_photo_info['cust_handle'].": ".$row_photo_info['cust_fname']." ".$row_photo_info['cust_lname']." (".$row_photo_info['cust_email'].") has recieved their first purchase";
		$mail = new PHPMailer();
		$mail -> Host = PI_SMTP;
		$mail -> IsSendMail();
		$mail -> IsHTML(false);
		$mail -> From = PI_EMAIL;
		$mail -> FromName = PI_EMAIL;
		$mail -> AddAddress(NEW_PHOTOGRAPHER);
		$mail -> Subject = "First Order";
		$mail -> Body = $msg;
		$mail -> Send();
	}
	
	$add = "INSERT INTO `cust_customers` (`cust_fname`,`cust_lname`,`cust_add`,`cust_suite_apt`,`cust_city`,`cust_state`,`cust_zip`,`cust_country`,`cust_phone`,`cust_email`,`cust_ip`,`cust_online`) VALUES ('$BFName','$BLName','$BAdd','$BSuite','$BCity','$BState','$BZip','$BCount','$Phone','$Email','$ip','n');";
	$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
	
	$query_get_last = "SELECT `cust_id` FROM `cust_customers` WHERE `cust_lname` = '$BLName' ORDER BY `cust_id` DESC LIMIT 0,1";
	$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
	$row_get_last = mysql_fetch_assoc($get_last);
	
	$cust_id = $row_get_last['cust_id'];
	
	$add = "INSERT INTO `cust_billing` (`cust_id`,`cust_bill_fname`,`cust_bill_lname`,`cust_bill_add`,`cust_bill_suite_apt`,`cust_bill_city`,`cust_bill_state`,`cust_bill_zip`,`cust_bill_counry`,`cust_bill_ccnum`,`cust_bill_ccshort`,`cust_bill_ccv`,`cust_bill_exp_month`,`cust_bill_year`,`cust_bill_cc_type_id`,`cust_bill_default`) VALUES ('$cust_id','$BFName','$BLName','$BAdd','$BSuite','$BCity','$BState','$BZip','$BCount','','$CC4Num','','','','$CCT','n');";
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
	$encnum = $_GET['invoice'] = md5($invnum);
	
	$add = "INSERT INTO `orders_invoice` (`cust_id`,`cust_bill_id`,`cust_ship_id`,`invoice_num`,`invoice_enc`,`invoice_total`,`invoice_disc`,`invoice_tax`,`invoice_ship`,`invoice_grand`,`invoice_date`,`ship_speed_id`,`invoice_transaction`,`invoice_paid_date`,`invoice_paid`,`invoice_comments`) VALUES ('$cust_id','$bill_id','$ship_id','$invnum','$encnum','$total','$discount','$tax','$shipping','$grandtotal',NOW(),'$speed','$tran_id',NOW(),'y','$Comm');";
	$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
	
	$query_get_last = "SELECT `invoice_id` FROM `orders_invoice` WHERE `cust_id` = '$cust_id' ORDER BY `invoice_id` DESC LIMIT 0,1";
	$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
	$row_get_last = mysql_fetch_assoc($get_last);
	
	$inv_id = $row_get_last['invoice_id'];
	
	if($discode!== false && $discount > 0){
		if($discodes == -1){
			$add = "INSERT INTO `orders_invoice_codes` (`invoice_id`,`disc_total`,`disc_id`,`cust_code`,`prod_id`,`item_count`) VALUES ('$inv_id','$discount','$discId','y','$discprod','$disccnt');";
		} else {	
			$add = "INSERT INTO `orders_invoice_codes` (`invoice_id`,`disc_total`,`disc_id`,`cust_code`,`prod_id`,`item_count`) VALUES ('$inv_id','$discount','$discId','n','$discprod','$disccnt');";
		}
		$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
	}
	$query_get_price = "SELECT `photo_event_price`.`photo_price`, `photo_event_price`.`photo_color`, `photo_event_price`.`photo_blk_wht`, `photo_event_price`.`photo_sepia`, `photo_event`.`photo_to_lab`, `photo_event`.`photo_at_lab`, `photo_event`.`photo_at_photo`, `photo_event`.`photo_ship_stud`, `photo_event`.`photo_color_crt`
		FROM `cust_customers`
		INNER JOIN `photo_event` 
			ON `photo_event`.`cust_id` = `cust_customers`.`cust_id` 
		INNER JOIN `photo_event_price` 
			ON `photo_event_price`.`photo_event_price_id` = `photo_event`.`event_price_id` 
		WHERE `cust_handle` = '$photographer' 
			AND `event_use` = 'y' 
			AND `event_num` = '$code'";
	$get_price = mysql_query($query_get_price, $cp_connection) or die(mysql_error());
	$row_get_price = mysql_fetch_assoc($get_price);
	$price = explode(",",$row_get_price['photo_price']);
	mysql_free_result($get_price);
	
	if($row_get_price['photo_ship_stud'] != 'n' || $row_get_price['photo_color_crt'] != 'n'){
		$Inst = '';
		if($row_get_price['photo_ship_stud'] == 'y') $Inst .= "SPST ";
		if($row_get_price['photo_color_crt'] == 'y') $Inst .= "CLST ";
		$upd = "UPDATE `orders_invoice` SET `invoice_instruction` = '$Inst' WHERE `invoice_id` = '$inv_id'";
		$upd_info = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
		
	foreach($cart as $k => $v){ 
		$cart_id = explode("-",$v);
		$cart_items = explode(",",$cart_id[1]);
		if($cart_id[2] == "I"){
			foreach($cart_items as $key => $value){
				$cart_ids = explode(":",$value);
				$cart_qty = explode(".",$cart_ids[1]);
				$cart_ids = $cart_ids[0];
				foreach($price as $v2){
					$PId = explode(":",$v2);
					if($PId[0] == $cart_ids){
						$CartId = $PId[0];
						$CartPrice = $PId[1];
						break;
					}
				}
				$query_get_info = "SELECT `prod_price`, `prod_serial` FROM `prod_products` WHERE `prod_id` = '$CartId' AND `prod_use` = 'y'";
				$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
				$row_get_info = mysql_fetch_assoc($get_info);
				$total_get_info = mysql_num_rows($get_info);
				if($total_get_info > 0){ $realkey++;
					if($cart_qty[0] || $cart_qty[1] || $cart_qty[2]){
						if($dq_file == false && ($row_get_info['prod_serial'] == "099" || $row_get_info['prod_serial'] == "100")) $dq_file = true;
						$prod_cost = $row_get_info['prod_price'];
						$add = "INSERT INTO `orders_invoice_photo` (`invoice_id`,`image_id`,`invoice_image_price`,`invoice_image_cost`,`invoice_image_size_id`,`invoice_image_asis`,`invoice_image_bw`,`invoice_image_sepia`,`invoice_image_sale`) VALUES ('$inv_id','$cart_id[0]','$CartPrice','$prod_cost','$CartId','$cart_qty[0]','$cart_qty[1]','$cart_qty[2]','n');";
						$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
					}
				}
			}
		} else {
			$Specs = explode(",",implode("-",array_slice($cart_id,4)));
			foreach($cart_items as $key => $value){
				$cart_ids = explode(":",$value);
				$cart_qty = explode(".",$cart_ids[1]);
				$query_get_info = "SELECT `prod_name`, `prod_price` FROM `prod_products` WHERE `prod_id` = '".$cart_ids[0]."'";
				$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
				$row_get_info = mysql_fetch_assoc($get_info);
				$total_get_info = mysql_num_rows($get_info);
				if($cart_qty[0]>0 || $cart_qty[1]>0 || $cart_qty[2]>0){ if($dq_file == false) $dq_file = true;
					$prod_cost = $row_get_info['prod_price'];
					$add = "INSERT INTO `orders_invoice_border` (`invoice_id`,`image_id`,`invoice_image_price`,`invoice_image_cost`,`invoice_image_size_id`,`invoice_image_asis`,`invoice_image_bw`,`invoice_image_sepia`,`invoice_image_sale`,`border_id`,`invoice_horz`,`invoice_imgW`,`invoice_imgH`,`invoice_bordW`,`invoice_bordH`,`invoice_imgX`,`invoice_imgY`,`invoice_text`,`invoice_textX`,`invoice_textY`,`invoice_font`,`invoice_size`,`invoice_color`,`invoice_bold`,`invoice_italic`) VALUES ('$inv_id','$cart_id[0]','".urldecode($cart_ids[3])."','$prod_cost','$cart_ids[0]','$cart_qty[0]','$cart_qty[1]','$cart_qty[2]','n','$Specs[0]','$Specs[1]','".urldecode($Specs[2])."','".urldecode($Specs[3])."','".urldecode($Specs[4])."','".urldecode($Specs[5])."','".urldecode($Specs[6])."','".urldecode($Specs[7])."','$Specs[8]','".urldecode($Specs[9])."','".urldecode($Specs[10])."','$Specs[11]','$Specs[12]','$Specs[13]','$Specs[14]','$Specs[15]');";
					$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
				}
			}
		}
	}	
	$query_get_email = "SELECT `cust_email`, `cust_handle` FROM `cust_customers` 
		INNER JOIN `photo_event` 
			ON `photo_event`.`cust_id` = `cust_customers`.`cust_id` 
		WHERE `cust_handle` = '$photographer' 
			AND `event_num` = '$code'";
	$get_email = mysql_query($query_get_email, $cp_connection) or die(mysql_error());
	$row_get_email = mysql_fetch_assoc($get_email);
	
	ob_start();
	require_once($r_path.'checkout/invoice.php');
	$page = ob_get_contents();
	ob_end_clean();
	/*
	if($dq_file == true){ 
		$Handle = $row_get_email['cust_handle'];
		$BioImage = "Logo.jpg";
		if(is_file($r_path."photographers/".$Handle."/".$BioImage)){
			list($BioWidth, $BioHeight) = getimagesize($r_path."photographers/".$Handle."/".$BioImage);
			$BioImage = "photographers/".$Handle."/".$BioImage;
			if($BioWidth > 700){
				$Ration = 700/$BioWidth; $BioWidth = 700; $BioHeight = $BioHeight*$Ration;
			}
		} else $BioImage = false;
		
		ob_start();
		require_once($r_path.'checkout/digital_download_2.php');
		$page2 = ob_get_contents();
		ob_end_clean();
	}*/
	// $Email = $Email;
	$reciever = $row_get_email['cust_email'];
	
	$toChad = "To Photographer: ".$invnum." ".$reciever."\n";
	$mail = new PHPMailer();
	$mail -> Host = PI_SMTP;
	$mail -> IsHTML(true);
	$mail -> IsSendMail();
	$mail -> Sender = PI_EMAIL;
	$mail -> Hostname = PI_HOSTNAME;
	$mail -> From = $reciever;
	$mail -> FromName = $reciever;
	if(TESTING)
		$mail -> AddAddress(DEV_EMAIL);
	else {
		$mail -> AddAddress($Email);
		$mail -> AddBCC(DEV_EMAIL);
	}
	$mail -> Subject = "Invoice";
	$mail -> Body = $page;
	$mail -> Send();
	$Emailsent = true;
	/*
	if($dq_file == true){ 
		$mail = new PHPMailer();
		$mail -> Host = "smtp.proimagesoftware.com";
		$mail -> IsHTML(true);
		$mail -> IsSendMail();
		$mail -> Sender = "info@proimagesoftware.com";
		$mail -> Hostname = "proimagesoftware.com";
		$mail -> From = $reciever;
		$mail -> FromName = $reciever;
		$mail -> AddAddress($Email);
		$mail -> Subject = "Your Digital Download";
		$mail -> Body = $page2;
		$mail -> Send();
		$Emailsent = true;
	}
	*/
	$toChad .= "To Customer: ".$Email."\n";
	$mail = new PHPMailer();
	$mail -> Host = PI_SMTP;
	$mail -> IsHTML(true);
	$mail -> IsSendMail();
	$mail -> Sender = PI_EMAIL;
	$mail -> Hostname = PI_HOSTNAME;
	$mail -> From = PI_EMAIL;
	$mail -> FromName = "Orders at ProImage Software";
	if(TESTING)
		$mail -> AddAddress(DEV_EMAIL);
	else {
		$mail -> AddAddress($reciever);
		$mail -> AddBCC(DEV_EMAIL);
	}
	$mail -> Subject = "Invoice";
	$mail -> Body = $page;
	$mail -> Send();
	$Emailsent = true;
	
	if($row_get_price['photo_to_lab'] == "y"){
	
//		$lab = "support@proimagesoftware.com";
//		$toChad .= "To Lab: ".$lab."\n";	
//		$mail = new PHPMailer();
//		$mail -> Host = "smtp.proimagesoftware.com";
//		$mail -> IsHTML(true);
//		$mail -> IsSendMail();
//		$mail -> Sender = "info@proimagesoftware.com";
//		$mail -> Hostname = "proimagesoftware.com";
//		$mail -> From = $reciever;
//		$mail -> FromName = $reciever;
//		$mail -> AddAddress($lab);
//		$mail -> Subject = "Invoice To Lab";
//		$mail -> Body = $page;
//		$mail -> Send();
//		$Emailsent = true;
		
		//require_once($r_path.'scripts/cart/send_lab.php');
		$upd = "UPDATE `orders_invoice` SET `invoice_accepted` = 'p' WHERE `invoice_id` = '$inv_id'";
		$upd_info = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$mail = new PHPMailer();
	$mail -> Host = PI_SMTP;
	//$mail -> IsHTML(true)
	$mail -> IsSendMail();
	$mail -> Sender = PI_EMAIL;
	$mail -> Hostname = PI_HOSTNAME;
	$mail -> From = PI_EMAIL;
	$mail -> FromName = PI_EMAIL;
	$mail -> AddAddress(DEV_EMAIL);
	$mail -> Subject = "Debugging";
	$mail -> Body = $toChad;
	$mail -> Send();
	$Emailsent = true;
	
	session_destroy();
	setcookie($cookieName,"",time()-3600);
	
	$query_check = "SELECT `fav_id` FROM `photo_cust_favories` WHERE `fav_code` = '".$code.$photographer."' AND `fav_email` = '$qemail' AND `fav_occurance` = '2' ORDER BY `fav_id` DESC LIMIT 0,1";
	$check = mysql_query($query_check, $cp_connection) or die(mysql_error());
	$total_check = mysql_num_rows($check);
	
	if($total_check == 0){
		$query_check = "INSERT INTO `photo_cust_favories` (`fav_code`,`fav_email`,`fav_occurance`,`fav_date`,`fav_others`,`fav_cart`) VALUES ('".$code.$photographer."','$qemail','2',NOW(),'y','0');";
	} else {
		$row_check = mysql_fetch_assoc($check);
		$query_check = "UPDATE `photo_cust_favories` SET `fav_cart` = '0' WHERE `fav_id` = '".$row_check['fav_id']."';";
	}
	$check = mysql_query($query_check, $cp_connection) or die(mysql_error());

	$GoTo = "/checkout/thankyou.php?Photographer=".$photographer."&invoice=".$encnum;
	header(sprintf("Location: %s", $GoTo));
} else {
	$cart = implode("[+]",$cart);
	
	$GoTo = "checkout.php?error=".$message;
	header(sprintf("Location: %s", $GoTo));
}
?>