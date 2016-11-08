<? if(isset($r_path)===false){ 	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; }
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
	
	require_once($r_path.'../scripts/cart/encrypt.php');
	$SvLvl = clean_variable($_POST['Service_Level'],true);
	$CCNum = clean_variable($_POST['Credit_Card_Number'],true);
	$CCV = clean_variable($_POST['CCV_Code'],true);
	$CCM = clean_variable($_POST['Experation_Month'],true);
	$CCY = clean_variable($_POST['Experation_Year'],true);
	$CC4Num = substr($CCNum,-4,4);
	$CCNum = encrypt_data($CCNum);
	$CType = clean_variable($_POST['Type_of_Card'],true);
  $holdAccount = 'n';
	
	$approval = false;
	$reciever = "info@proimagesoftware.com";
	$Email = $row_get_exp['cust_email'];
	$subject = "Pro Image Software: Membership Renewel";
	$getBill = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getBill->mysql("SELECT cust_billing.*, cust_customers.cust_subscription_number
					FROM `cust_billing`
					LEFT JOIN cust_customers ON (cust_customers.cust_id = cust_billing.cust_id)
					WHERE cust_billing.`cust_id` = '$CId' LIMIT 0,1;");
	if($getBill->TotalRows() > 0){
		$getCost = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getCost->mysql("SELECT * FROM `prod_products` WHERE `prod_id` = '$SvLvl' LIMIT 0,1;");
		$getCost = $getCost->Rows();
		$recurring = $getCost[0]['prod_year'];
		if($recurring == "y"){
			$bill_date = date("Y-m-d h:i:s",mktime(0,0,0,date("m"),date("d"),date("Y")+1));
		} else {
			$bill_date = date("Y-m-d h:i:s",mktime(0,0,0,date("m")+1,date("d"),date("Y")));
		}
		$total = $getCost[0]['prod_price'];
		$fee = $getCost[0]['prod_fee'];
		$quota = $getCost[0]['prod_qty']*1024;
		$fee = 0;
		$grandtotal = $total;
		if($grandtotal > 0){
			$getBill = $getBill->Rows();
			$BId = $getBill[0]['cust_id'];
			$subscription_id = $getBill[0]['cust_subscription_number'];
			$BFName = $getBill[0]['cust_bill_fname'];
			$BMName = $getBill[0]['cust_bill_mname'];
			$BLName = $getBill[0]['cust_bill_lname'];
			$BSuffix = $getBill[0]['cust_bill_suffix'];
			$BCName = $getBill[0]['cust_bill_cname'];
			$BAdd = $getBill[0]['cust_bill_add'];
			$BSuiteApt = $getBill[0]['cust_bill_suite_apt'];
			$BAdd2 = $getBill[0]['cust_bill_add_2'];
			$BCity = $getBill[0]['cust_bill_city'];
			$BState = $getBill[0]['cust_bill_state'];
			$BZip = $getBill[0]['cust_bill_zip'];
			$BCount = $getBill[0]['cust_bill_counry'];
			if($is_process){
				$old_path = $r_path;
				$r_path .= "../";
				include ($r_path.'scripts/cart/merchant_ini.php');
				$r_path = $old_path;
				$Error = $message;
			} else {
				$approval = true;
			}
		} else {
			$approval = true;
		}
	} else {
		$approval = false;
		$Error = "We were unable to find your records.";
	}
	if($approval == true){
		$is_gateway = "Authorize_ARB";
		if(empty($subscription_id) == false){
			$is_capture = "STATUS";
			if($is_process == true){
				$old_path = $r_path;
				$r_path .= "../";
				include ($r_path.'scripts/cart/merchant_ini.php');
				$r_path = $old_path;
			}
			switch(strtolower($status)){
				case 'suspended':
				case 'active':
					$is_capture = "UPDATE";
					break;					
				default:
					$is_capture = "SUBSCRIBE";	
					break;
			}
		} else {
			$is_capture = "SUBSCRIBE";	
		}
		$is_error = false;
		
		if($recurring == "y"){
			$ReNewInterval = 12;
		} else {
			$ReNewInterval = 1;
		}
		$ReNewUnit = 'months';
		$ReNewTotalOccurance = 9999;
		$ReNewStart = date("Y-m-d", strtotime($bill_date));
		
		if($is_process == true){
			$old_path = $r_path;
			$r_path .= "../";
			include ($r_path.'scripts/cart/merchant_ini.php');
			$r_path = $old_path;
			$Error = $response;
		} else {
			$subscription_id = '123456789';	
		}
		
		switch($SvLvl){
			case 9:
			case 328:
				$rev = 15;
				break;
			case 10:
			case 329:
				$rev = 10;
				break;
			case 11:
				$rev = 7;
				break;
			case 344: 
				$rev = 17; break;
			case 345: 
				$rev = 12; break;
			case 347: 
				$rev = 9; break;
			case 348: 
				$rev = 9; break;
      case 358:
        $rev = 17;
        $holdAccount = 'y';
      break;
			default:
				$rev = 9; break;
		}
		$getLast = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getLast->mysql("SELECT `invoice_id` AS `invoice_num` FROM `orders_invoice` ORDER BY `invoice_id` DESC LIMIT 0,1;");
		$getLast = $getLast->Rows();
		
		$inv_num = ($getLast[0]['invoice_num']+101).date("Ymd");
		$inv_enc = md5($inv_num);
		
		$addInv = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$addInv->mysql("INSERT INTO `orders_invoice` (`cust_id`,`cust_bill_id`,`invoice_num`,`invoice_enc`,`invoice_rev`,`invoice_total`,`invoice_date`,`invoice_transaction`,`invoice_paid`,`invoice_paid_date`,`invoice_comp`,`invoice_comp_date`,`invoice_online`) VALUES ('$CId','$BId','$inv_num','$inv_enc','$rev','$total',NOW(),'$tran_id','y',NOW(),'y',NOW(),'y');");
		
		$getLast = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getLast->mysql("SELECT `invoice_id` FROM `orders_invoice` WHERE `cust_id` = '$CId' ORDER BY `invoice_id` DESC LIMIT 0,1;");
		$getLast = $getLast->Rows();
		
		$inv_id = $getLast[0]['invoice_id'];
		
		$addInv = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$addInv->mysql("INSERT INTO `orders_invoice_prod` (`invoice_id`,`prod_id`,`invoice_prod_sale`,`invoice_prod_price`,`invoice_prod_fee`,`invoice_prod_qty`) VALUES ('$inv_id','$SvLvl','n','$total','$fee','1');");
		
		$updInv = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);		
		$updInv->mysql("UPDATE `cust_customers` SET `cust_due_date` = '$bill_date', cust_subscription_number = '$subscription_id', cust_canceled = 'n', `cust_paid` = 'y', `cust_active` = 'y', `cust_hold` = '$holdAccount', `cust_service` = '$SvLvl', `cust_rev` = '$rev', `cust_quota` = '$quota' WHERE `cust_id` = '$CId';");
				
		$Notice = "Thank you for contining your service with ProImageSoftware.com. We have charged $".number_format($total,2,".",",")." to your credit card ending in ".$CC4Num.$eol.$eol." Sincerely Pro Image Software.";
		
		send_email($reciever, $Email, $subject, $Notice, false, false, false);
	}
}
?>