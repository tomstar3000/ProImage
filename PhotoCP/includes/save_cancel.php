<? if(isset($r_path)===false){ 	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; }
define ("Allow Scripts", true);
$is_creditcard = true;
$is_gateway = "Authorize_ARB";
$is_capture = "CANCEL";
$is_live = true;
$is_error = false;
$is_process = true;
$approval = false;
$Error = false;

include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_send_email.php';

$CId = $CustId;
if(isset($_POST['Controller']) && $_POST['Controller'] == "Cancel"){
		
	$approval = false;
	$reciever = "info@proimagesoftware.com";
	$Email = $row_get_exp['cust_email'];
	//$Email = "development@proimagesoftware.com";
	$subject = "Pro Image Software: Membership Cancel";
	$getCust = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getCust->mysql("SELECT cust_customers.cust_subscription_number FROM cust_customers WHERE `cust_id` = '$CId' LIMIT 0,1;");
	
	if($getCust->TotalRows() > 0){		
		$getCustRows = $getCust->Rows();
		$subscription_id = $getCustRows[0]['cust_subscription_number'];
		
		$is_gateway = "Authorize_ARB";
		$is_capture = "CANCEL";
		$is_error = false;
	
		$subscription_id = '12333847';
		$is_capture = "STATUS";
		include ($r_path.'scripts/cart/merchant_ini.php');
			
		if($is_process == true){
			$old_path = $r_path;
			$r_path .= "../";
			include ($r_path.'scripts/cart/merchant_ini.php');
			$r_path = $old_path;
			$Error = $response;
		} else {
			$subscription_id = '123456789';	
		}
			
		$getCust->mysql("UPDATE `cust_customers` SET cust_canceled = 'y' WHERE `cust_id` = '$CId';");
				
		$Notice = "Sorry to see you leaving ProImageSoftware.com. You will be able to continue using your account until your subscription ends.".$eol.$eol." Sincerely Pro Image Software.";
		$Canceled = 'y';
		send_email($reciever, $Email, $subject, $Notice, false, false, false);
	}
} else {
	$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getInfo->mysql("SELECT cust_canceled FROM cust_customers WHERE `cust_id` = '$CustId';");
	$getInfo = $getInfo->Rows();
	
	$Canceled = $getInfo[0]['cust_canceled'];
	if($Canceled == 'y'){
		$Notice = "Your account has been canceled. You will be able to continue using your account until your subscription ends.".$eol.$eol." Sincerely Pro Image Software.";
	}
}
?>