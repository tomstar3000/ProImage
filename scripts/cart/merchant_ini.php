<?
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'scripts/security.php';
if($is_creditcard){
	if($is_gateway == "YourPay_Curl"){
		//******************** Begin Your Pay Processing  ****************************
		include $pathing."scripts/libraries/lphp.php";
		$mylphp = new lphp;
		$myorder["host"] = "secure.linkpt.net";
		$myorder["port"] = "1129";
		$myorder["keyfile"] = $pathing."/1001170715.pem"; # Change this to the name and location of your certificate file
		$myorder["configfile"] = "111111111"; # Change this to your store number
		
		$myorder["ordertype"] = "SALE";
		$myorder["result"] = "DECLINE"; # For a test, set result to GOOD, DECLINE, or DUPLICATE. Go Live LIVE
		require_once $pathing.'scripts/cart/encrypt.php';
		$CCNum = decrypt_data($CCNum);
		$myorder["cardnumber"] = $CCNum;
		$myorder["cardexpmonth"] = $CCM;
		$myorder["cardexpyear"] = substr($CCY,2,2);
		$myorder["cvmindicator"] = "provided";
		$myorder["cvmvalue"] = $CCV;
		$myorder["subtotal"] = $total;
		$myorder["tax"] = $tax;
		$myorder["shipping"] = ($shipping+$extra);
		$myorder["vattax"] = "0.00";
		$myorder["chargetotal"] = $grandtotal;
		$myorder["name"] = $BFName." ".$BLName;
		$myorder["company"] = $BCName;
		$myorder["address1"] = $BAdd;
		$myorder["address2"] = $BAdd2;
		$myorder["city"] = $BCity;
		$myorder["state"] = $BState;
		$myorder["zip"] = $BZip; # Required for AVS. If not provided, transactions will downgrade.
		switch($BCount){
			case "USA":
				$myorder["country"] = "US";
				break;
			case "CAN":
				$myorder["country"] = "CA";
				break;
			case "GBR":
				$myorder["country"] = "GB";
				break;
			default:
				$myorder["country"] = "US";
				break;
		}
		$myorder["phone"] = $Phone;
		$myorder["fax"] = $Fax;
		$myorder["email"] = $Email;
		$myorder["addrnum"] = $BAddNum; # Required for AVS. If not provided, transactions will downgrade.
		$myorder["debugging"] = "true"; # for development only - not intended for production	use
		# Send transaction. Use one of two possible methods #
		// $result = $mylphp->process($myorder); # use shared library model
		$result = $mylphp->curl_process($myorder); # use curl methods
		
		if(!is_array($result)){
			$result = "<?xml version='1.0'?>
		<!DOCTYPE chapter SYSTEM \"/just/a/test.dtd\" [
		<!ENTITY plainEntity \"FOO entity\">
		]><responce>".$result."</responce>";
				
			include ($pathing.'scripts/fnct_xml_parser.php');
		
			$xmlparse = &new ParseXML;
			$result = $xmlparse->GetXMLTree($result);
			$xmlparsed = true;
		}
		if(!$xmlparsed){
			$approval = $result['r_approved'];
			$message = $result['r_message'];
			$error =  $result['r_error'];
		} else {
			$approval = $result['RESPONCE'][0]['R_APPROVED'][0]['VALUE'];
			$message = $result['RESPONCE'][0]['R_MESSAGE'][0]['VALUE'];
			$error = $result['RESPONCE'][0]['R_ERROR'][0]['VALUE'];
			$tran_id = $result['RESPONCE'][0]['R_ORDERNUM'][0]['VALUE'];
		}
		if($approval == "APPROVED"){
			$approval = true;
		} else {
			$approval = false;
		}
		$errorcode = false;
		//******************** End Authorize.net Processing *********************
	} else if ($is_gateway == "Authorize_AIM"){
		$DEBUGGING					= 1;				# Display additional information to track down problems
		$TESTING					= 1;				# Set the testing flag so that transactions are not live
		$ERROR_RETRIES				= 2;				# Number of transactions to post if soft errors occur
		
		$auth_net_login_id			= "7vD6j9VF4G";
		$auth_net_tran_key			= "7h8Jp399GV64DtbR";
		//$auth_net_url				= "https://test.authorize.net/gateway/transact.dll";
		//$auth_net_url				= "https://certification.authorize.net/gateway/transact.dll";
		//$auth_net_url				= "https://secure.authorize.net/gateway/transact.dll";
    //$auth_net_url       = "https://secure2.authorize.net/gateway/transact.dll";
		if($is_capture == "AUTH_CAPTURE"){
			$auth_net_type = "AUTH_CAPTURE"; // AUTH_CAPTURE, AUTH_ONLY, CAPTURE_ONLY, CREDIT, VOID, PRIOR_AUTH_CAPTURE
		} else if($is_capture == "AUTH_ONLY"){
			$auth_net_type = "AUTH_ONLY";
		} else if($is_capture == "CAPTURE_ONLY"){
			$auth_net_type = "CAPTURE_ONLY";
		} else if($is_capture == "CREDIT"){
			$auth_net_type = "CREDIT";
		} else if($is_capture == "VOID"){
			$auth_net_type = "VOID";
		} else if($is_capture == "PRIOR_AUTH_CAPTURE"){
			$auth_net_type = "PRIOR_AUTH_CAPTURE";
		} else {
			$auth_net_type = "AUTH_CAPTURE";
		}
		if($is_method = "CC"){
			$auth_net_method = "CC";		 // CC, ECHECK
		} else if($is_method = "CHECK"){
			$auth_net_method = "ECHECK";
		} else {
			$auth_net_method = "CC";
		}
		if($is_live === false){
			$auth_net_test = "TRUE"; 		 // True, False
			//$auth_net_url = "https://certification.authorize.net/gateway/transact.dll";
			//$auth_net_url = "https://secure.authorize.net/gateway/transact.dll";
      $auth_net_url = "https://secure2.authorize.net/gateway/transact.dll";
			if($is_error === false){
				$auth_net_card = "4007000000027"; // Card Valid
			} else {
				$auth_net_card = "4222222222222"; // Card Error
			}
		} else {
			$auth_net_test = "FALSE"; 		 // True, False
			// $auth_net_url = "https://secure.authorize.net/gateway/transact.dll";
      $auth_net_url = "https://secure2.authorize.net/gateway/transact.dll";
			require_once $r_path.'scripts/cart/encrypt.php';
			$auth_net_card = decrypt_data($CCNum);
		}
		if(isset($CCV) && $CCV != 0) $auth_net_ccv = $CCV;
		$auth_net_exp = $CCM.substr($CCY,2,2);
		$auth_net_desc = "";
		$auth_net_amount = $grandtotal;
		$auth_net_fname = $BFName;
		$auth_net_lname = $BLName;
		$auth_net_add = $BAdd;
		$auth_net_city = $BCity;
		$auth_net_state = $BState;
		$auth_net_zip = $BZip;
		switch($BCount){
			case "USA":
				$auth_net_country = "US";
				break;
			case "CAN":
				$auth_net_country = "CA";
				break;
			case "GBR":
				$auth_net_country = "GB";
				break;
			default:
				$auth_net_country = "US";
				break;
		}
		$authnet_values				= array
		(
			"x_login"					=> $auth_net_login_id,
			"x_version"					=> "3.1",
			"x_delim_char"				=> "|",
			"x_delim_data"				=> "TRUE",
			"x_url"						=> "FALSE",
			"x_type"					=> $auth_net_type,
			"x_method"					=> $auth_net_method,
			"x_tran_key"				=> $auth_net_tran_key,
			"x_test_request"			=> $auth_net_test,
			"x_relay_response"			=> "FALSE",
			"x_card_num"				=> $auth_net_card,
			"x_exp_date"				=> $auth_net_exp,
			"x_description"				=> $auth_net_desc,
			"x_amount"					=> $auth_net_amount,
			"x_first_name"				=> $auth_net_fname,
			"x_last_name"				=> $auth_net_lname,
			"x_address"					=> $auth_net_add,
			"x_city"					=> $auth_net_city,
			"x_state"					=> $auth_net_state,
			"x_zip"						=> $auth_net_zip,
			"x_country"					=> $auth_net_country
		);
		
		if(isset($auth_net_ccv))
			$authnet_values["x_card_code"] = $auth_net_ccv;
		
		$fields = "";
		foreach($authnet_values as $key => $value) $fields .= "$key=".urlencode($value)."&";
		$ch = curl_init($auth_net_url);
		// $ch = curl_init("https://certification.authorize.net/gateway/transact.dll"); 
		// $ch = curl_init("https://secure.authorize.net/gateway/transact.dll"); 
		curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
		### curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
		$responce = "";
		$approval = false;
		$message = "";
		$error = "";
		$tran_id = "";
		$responce = curl_exec($ch); //execute post and get results
		curl_close ($ch);
		
		$responce = explode("|",$responce);
		$approval = $responce[0];
		$message = $responce[3];
		$error = $responce[2];
		$tran_id = $responce[37];
		switch($approval){
			case 1:
				$approval = true;
				break;
			case 2:
				$approval = false;
				break;
			case 3:
				$approval = false;
				break;
			case 4:
				$approval = false;
				break;
		}
		$errorcode = false;
		//return $approval;
		
	/**
	* Added new ARB method using AuthroizeNet classes version 1.1.8
	* @link http://developer.authorize.net/downloads
	* @version 1.1.8
	
	require_once 'anet_php_sdk/AuthorizeNet.php';
    define("AUTHORIZENET_API_LOGIN_ID", "YOURLOGIN");
    define("AUTHORIZENET_TRANSACTION_KEY", "YOURKEY");
    $subscription                          = new AuthorizeNet_Subscription;
    $subscription->name                    = "PHP Monthly Magazine";
    $subscription->intervalLength          = "1";
    $subscription->intervalUnit            = "months";
    $subscription->startDate               = "2011-03-12";
    $subscription->totalOccurrences        = "12";
    $subscription->amount                  = "12.99");
    $subscription->creditCardCardNumber    = "6011000000000012";
    $subscription->creditCardExpirationDate= "2018-10";
    $subscription->creditCardCardCode      = "123";
    $subscription->billToFirstName         = "Rasmus";
    $subscription->billToLastName          = "Doe";

    // Create the subscription.
    $request = new AuthorizeNetARB;
    $response = $request->createSubscription($subscription);
    $subscription_id = $response->getSubscriptionId();


	*/
	} else if ($is_gateway == "Authorize_ARB"){
		$DEBUGGING					= 1;				# Display additional information to track down problems
		$TESTING					= 1;				# Set the testing flag so that transactions are not live
		$ERROR_RETRIES				= 2;				# Number of transactions to post if soft errors occur
		
		require_once $r_path.'includes/Authorize.Net.1.1.8/AuthorizeNet.php';		
		define("AUTHORIZENET_API_LOGIN_ID", "7vD6j9VF4G");
		define("AUTHORIZENET_TRANSACTION_KEY", "7h8Jp399GV64DtbR");
		define("AUTHORIZENET_SANDBOX", false);
		
		$subscription = new AuthorizeNet_Subscription;

		if($is_capture == "SUBSCRIBE" || $is_capture == "UPDATE"){
			$subscription->name = $BFName." ".$BLName;
			$subscription->intervalLength = $ReNewInterval;
			$subscription->intervalUnit = $ReNewUnit;
			$subscription->startDate = $ReNewStart;
			$subscription->totalOccurrences = $ReNewTotalOccurance;
			$subscription->amount = $grandtotal;
			$subscription->creditCardExpirationDate = $CCY."-".sprintf("%02s",$CCM);
			$subscription->creditCardCardCode = $CCV;
			$subscription->billToFirstName = $BFName;
			$subscription->billToLastName = $BLName;
			$subscription->billToAddress = $BAdd;
			$subscription->billToCity = $BCity;
			$subscription->billToState = $BState;
			$subscription->billToZip = $BZip;
			switch($BCount){
				case "USA":
					$subscription->billToCountry = "US";
					break;
				case "CAN":
					$subscription->billToCountry = "CA";
					break;
				case "GBR":
					$subscription->billToCountry = "GB";
					break;
				default:
					$subscription->billToCountry = "US";
					break;
			}
						
			if($is_live === false){
				$subscription->creditCardCardNumber = "4007000000027"; // Card Valid
			} else {
				require_once $r_path.'scripts/cart/encrypt.php';
				$subscription->creditCardCardNumber = trim(decrypt_data($CCNum));
			}
			$request = new AuthorizeNetARB;
			if($is_capture == "UPDATE"){
				$response = $update_request->updateSubscription($subscription_id, $subscription);
			} else {
				$response = $request->createSubscription($subscription);
				$subscription_id = $response->getSubscriptionId();
			}
		} else if($is_capture == "CANCEL"){
			$cancellation = new AuthorizeNetARB;
			$response = $cancellation->cancelSubscription($subscription_id);
		} else if($is_capture == "STATUS"){
			$status_request = new AuthorizeNetARB;
			$response = $status_request->getSubscriptionStatus($subscription_id);
			$status = (string) $response->xml->status;
		}
	}
}
?>