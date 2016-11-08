<?php
/*
this class is meant to process credit cards
with different gateways passing to another gateway just changing one line.

Fabrizio Parrella
09/10/2004 - version 1
09/20/2004 - version 1.1		updated the POST function
09/24/2004 - version 1.2		now is possible pass ONLY one authorization type
11/23/2004 - version 1.2.1		fixed few bugs in the authorization for PNP
12/08/2004 - version 1.2.2		added a more human authorization type
02/22/2005 - version 1.2.3  	fixing the retrieving error for authorize.net
								tested authorize.net
								fixed fields name
								added a more human authorization mode
								retrieving the authorization code from authorize.net
03/05/2005 - version 1.2.4		added user IP
								fixed some curl errors
								added a parameter for curl - CERT_FILE - path for the crt certificate
									(function set_crt)
								added phone e fax to the customer information
								added some extra 'default' fields for authorize.net
								added a better error report
								if a value is set to NULL, that parameter will not be passed to the gateway
03/14/2005 - version 1.2.4.1	moved the IP parameter from USER to CUSTOMER
								removed a debug print
								fixed a variable name
								cleaned code
03/14/2005 - version 1.2.5		added two more gateways:
									planetpay
									quickcommerce
04/03/2005 - version 1.2.6		added iBill gateway
								modified set_order function
								added a get_transactionnum function (usefull for some gateways)
07/31/2005 - version 1.2.6.1	added more plugnpay variables
08/02/2005 - version 1.2.6.2	fixed a bug for the expiration date when the card is only marked
								modified the succesion that the informations are POSTED to the gateway
08/02/2005 - version 1.2.6.3	fixed the shipping address
09/22/2005 - version 1.2.7		added paynet
09/22/2005 - version 1.2.7.1	added and finised currency
								added a parameter to force the method to use to process (curl, curl_ext, fsockopen)
09/25/2005 - version 1.2.7.2	new function set_valuta
09/26/2005 - version 1.2.7.3	fixed paynet charge only function
10/10/2005 - version 1.2.7.4	added function get_gateway and _set_gateway to retrieve the data for the gateway
10/17/2005 - version 1.2.7.5	fixed paynet cced extra setting problem
10/17/2005 - version 1.2.7.6	fixed minor problem with paynet and plug n pay
01/11/2006 - version 1.3		added skipjack as new gateway
01/25/2006 - version 1.3.0.1	tested and finished skipjack gateway
02/12/2006 - version 1.3.1		added more functionality for viaklix  (Nathan Hyde)
								improved set_error function to include the gateway error  (Nathan Hyde)


supported API:
	- Plug and Pay	-	finish
	- Autorize.net	-	finish
	- paynet		-	finish
	- skipjack		-	finish
	- ViaKlix		-	finish
	- iBill			-	needs testing
	- planetpay		-	needs testing
	- quickcommerce	-	needs testing


----------------------------------------------
Example:

$bibEC_ccp = new bibEC_processCard('plug_n_pay');
$bibEC_ccp->save_log($file);	// the name of a LOG FILE
$bibEC_ccp->set_user($cc_user, $cc_password, $cc_key, $admin_email);
$bibEC_ccp->set_customer($fname, $lname, $address, $city, $state, $zip, $country, $phone, $fax, $email);//can be passed the IP as last field, optional
$bibEC_ccp->set_ship_to($fname, $lname, $address, $city, $state, $zip, $country, $phone, $fax);
$bibEC_ccp->set_ccard($name_on_card, $type, $number, $expmm, $expyy, $cvv);
$bibEC_ccp->set_valuta('USD', '$');
$bibEC_ccp->set_order($total_cart, $order_number, $description, 'auth', NULL, NULL, NULL);	//the last 5 fields are:
																							//	mode
																							//	authcode
																							//	transnum
																							//  currency code
																							//  currency simbol

//I am going to set extra fields if the gateway needs them

//$extra['ipaddress']	= $_SERVER['REMOTE_ADDR'];	//not necessary anymore from version 1.2.4
$extra['app-level']		= 1;		// ONLY FOR PLUG_N_PAY
									// 0 Anything Goes. No transaction is rejected based on AVS 
									// 1 Requires a match of Zip Code or Street Address, but will allow cards where the address information is not available. (Only 'N' responses will be voided) 
									// 2 Reserved For Special Requests 
									// 3 Requires match of Zip Code or Street Address. All other transactions voided; including those where the address information is not available. 
									// 4 Requires match of Street Address or a exact match (Zip Code and Street Address). All other transactions voided; including those where the address information is not available. 
									// 5 Requires exact match of Zip Code and Street Address.  All other transactions voided; including those where the address information is not available. 
									// 6 Requires exact match of Zip Code and Street Address, but will allows cards where the address information is not available. 
$bibEC_ccp->set_extra($extra);	//I need to pass an array

if(!$bibEC_ccp->process()){
	print_r($bibEC_ccp->get_error());
} else {
	//save the order!!!!
	//printing the authorization code
	echo $bibEC_ccp->get_authorization();
	echo 'HERE I HAVE TO SAVE THE CART, SEND EMAILS AROUND, DELETE CREDIT CARD INFO';
}
//if I want, I can print what I retrieve from the gateway

print_r($bibEC_ccp->get_answer());

print_r($bibEC_ccp->get_log());

//if I have a file with the LOG I can retrieve all the log with this :
print_r($bibEC_ccp->get_log_all());

//--------------------------------------------------

TODO:
	- insert more gateways/API (PAYPAL?)
	- support:  http://www.billthru.com/  (I.E.:http://www.zend.com/codex.php?id=1342&single=1)
	- implement GET_ANSWER to return an array
	- give a look at http://snpwebdesign.com/easyauthnet/easyauthnetdocs.htm for auth.net
	
*/

class bibEC_processCard {
	var $gateway_accepted = array('plug_n_pay','authorize_net','viaklix', 'planetpay', 'quickcommerce', 'ibill', 'paynet', 'skipjack');
	var $gateway;
	
	var $card = array();
	var $user = array();
	var $customer = array();
	var $ship_to = array();
	var $order = array();
	var $valuta = array();
	
	var $error = array();
	var $log = array();
	var $file_log = '';
	
	var $received = array();
	var $authorization = '';
	var $transnum = '';
	
	var $CURL_PATH = '/usr/local/bin/curl';
	var $CERT_FILE = '';	//if you have the crt file, u can specify it here for CURL
	
	var $plug_n_pay = array(
					  'name'		=> 'plug_n_pay',
					  'page'		=> 'https://pay1.plugnpay.com/payment/pnpremote.cgi',
					  'method'		=> 'post',
					  'force_method'=> '',	//this will force to use: fsockopen, curl_ext, curl

					  'u_user'		=> 'publisher-name',
					  'u_password'	=> 'publisher-password',
					  'u_key'		=> '',
					  'u_email'		=> 'publisher-email',
					  
					  'o_amount'	=> 'card-amount',
					  'o_orderID'	=> 'orderID',
					  'o_description'	=> '',
					  'o_authtype'	=> 'authtype',
					  'o_mode'		=> 'mode',	//what needs to be done to the credit card, process, void, return/refund
					  'o_authcode'	=> '',
					  'o_transnum'	=> '',

					  'v_currency'	=> 'currency',
					  'v_currency_simbol'	=> 'currency_simbol',
					  
					  'c_fname'		=> 'card_name',
					  'c_lname'		=> '',
					  'c_address'	=> 'card-address1',
					  'c_city'		=> 'card-city',
					  'c_state'		=> 'card-state',
					  'c_zip'		=> 'card-zip',
					  'c_country'	=> 'card-country',
					  'c_phone'		=> '',
					  'c_fax'		=> '',
					  'c_ip'		=> 'ipaddress',
					  'c_email'		=> 'email',

					  's_fname'		=> 'shipname',
					  's_lname'		=> '',
					  's_address'	=> 'address1',
					  's_city'		=> 'city',
					  's_state'		=> 'state',
					  's_zip'		=> 'zip',
					  's_country'	=> 'country',
					  's_phone'		=> '',
					  's_fax'		=> '',

					  'cc_name'		=> 'card-name',
					  'cc_type'		=> 'card-type',
					  'cc_number'	=> 'card-number',
					  'cc_expmm'	=> '',
					  'cc_expyy'	=> '',
					  'cc_exp'		=> 'card-exp',
					  'cc_cvv'		=> 'card-cvv',
					  'cc_auth'		=> '',
					  
					  'cc_expyyform'=> 'yy',	//accept yy or yyyy, this is the format of the year for the system, 2 or 4 numbers
					  'cc_expform'	=> 'm/y',	//this accept: m = month y = year.  it is the format of the expiration date if the system doesn't support separate month and year
					  
					  'extra'		=> array(
									   //from here set extra fields with the format:
									   // 'field' => 'value'
									   //as require from the API
									   'shipinfo' => '1',
									   'easycart' => '1',
									   'dontsndmail' => 'no',									   
					  				   )
					  );

	var $ibill = array(
					  'name'		=> 'ibill',
					  'page'		=> 'https://secure.ibill.com/cgi-win/ccard/tpcard.exe',
					  'method'		=> 'post',
					  'force_method'=> '',	//this will force to use: fsockopen, curl_ext, curl

					  'u_user'		=> 'reqtype',
					  'u_password'	=> 'password',
					  'u_key'		=> 'account',
					  'u_email'		=> '',
					  
					  'o_amount'	=> 'amount',
					  'o_orderID'	=> 'crefnum',
					  'o_description'	=> '',
					  'o_authtype'	=> 'saletype',
					  'o_mode'		=> '',	//what needs to be done to the credit card, process, void, return/refund
					  'o_authcode'	=> 'authcode',
					  'o_transnum'	=> 'transnum',

					  'v_currency'	=> '',
					  'v_currency_simbol'	=> '',
					  
					  'c_fname'		=> '',
					  'c_lname'		=> '',
					  'c_address'	=> 'address1',
					  'c_city'		=> '',
					  'c_state'		=> '',
					  'c_zip'		=> 'zipcode',
					  'c_country'	=> '',
					  'c_phone'		=> '',
					  'c_fax'		=> '',
					  'c_ip'		=> 'ipaddress',
					  'c_email'		=> '',

					  's_fname'		=> '',
					  's_lname'		=> '',
					  's_address'	=> '',
					  's_city'		=> '',
					  's_state'		=> '',
					  's_zip'		=> '',
					  's_country'	=> '',
					  's_phone'		=> '',
					  's_fax'		=> '',

					  'cc_name'		=> 'noc',
					  'cc_type'		=> '',
					  'cc_number'	=> 'cardnum',
					  'cc_expmm'	=> '',
					  'cc_expyy'	=> '',
					  'cc_exp'		=> 'cardexp',
					  'cc_cvv'		=> 'card-cvv',
					  
					  'cc_expyyform'=> 'yy',	//accept yy or yyyy, this is the format of the year for the system, 2 or 4 numbers
					  'cc_expform'	=> 'my',	//this accept: m = month y = year.  it is the format of the expiration date if the system doesn't support separate month and year
					  'cc_auth'		=> '',

					  'extra'		=> array(
									   //from here set extra fields with the format:
									   // 'field' => 'value'
									   //as require from the API
					  				   )
					  );

	var $authorize_net = array(
					  'name'		=> 'authorize_net',
					  // 'page'		=> 'https://secure.authorize.net/gateway/transact.dll',
            'page'    => 'https://secure2.authorize.net/gateway/transact.dll',
					  'method'		=> 'post',
					  'force_method'=> '',	//this will force to use: fsockopen, curl_ext, curl

					  'u_user'		=> 'x_login',
					  'u_password'	=> 'x_password',
					  'u_key'		=> 'x_tran_key',
					  'u_email'		=> 'x_email',
					  
					  'o_amount'	=> 'x_amount',
					  'o_orderID'	=> 'x_invoice_number',
					  'o_description'	=> 'x_description',
					  'o_authtype'	=> 'x_type',
					  'o_mode'		=> 'x_trans_id',			//what needs to be done to the credit card, process, void, return/refund
					  'o_authcode'	=> '',
					  'o_transnum'	=> '',

					  'v_currency'	=> '',
					  'v_currency_simbol'	=> '',
					  
					  'c_fname'		=> 'x_first_name',
					  'c_lname'		=> 'x_last_name',
					  'c_address'	=> 'x_address',
					  'c_city'		=> 'x_city',
					  'c_state'		=> 'x_state',
					  'c_zip'		=> 'x_zip',
					  'c_country'	=> 'x_country',
					  'c_phone'		=> 'x_phone',
					  'c_fax'		=> 'x_fax',
					  'c_ip'		=> 'x_customer_ip',
					  'c_email'		=> '',

					  's_fname'		=> 'x_ship_to_first_name',
					  's_lname'		=> 'x_ship_to_last_name',
					  's_address'	=> 'x_ship_to_address',
					  's_city'		=> 'x_ship_to_city',
					  's_state'		=> 'x_ship_to_state',
					  's_zip'		=> 'x_ship_to_zip',
					  's_country'	=> 'x_ship_to_country',
					  's_phone'		=> '',
					  's_fax'		=> '',

					  'cc_name'		=> '',
					  'cc_type'		=> '',
					  'cc_number'	=> 'x_card_num',
					  'cc_expmm'	=> '',
					  'cc_expyy'	=> '',
					  'cc_exp'		=> 'x_exp_date',
					  'cc_cvv'		=> 'x_card_code',
					  'cc_auth'		=> '',
					  
					  'cc_expyyform'=> 'yyyy',	//accept yy or yyyy, this is the format of the year for the system, 2 or 4 numbers
					  'cc_expform'	=> 'my',	//this accept: m = month y = year.  it is the format of the expiration date if the system doesn't support separate month and year

					  'extra'		=> array(
									   //from here set extra fields with the format:
									   // 'field' => 'value'
									   //as require from the API
									   'x_delim_data'	=> 'TRUE',
									   'x_echo_data'	=> 'TRUE',
									   'x_delim_char'	=> ',',
									   'x_encap_char'	=> '"',
									   'x_method'		=> 'CC', //CC, ECHECK
									   'x_test_request'	=> 'FALSE',	//TRUE, FALSE - test mode
									   'x_version'		=> '3.1',
									   'x_ADC_URL'		=> 'false',
									   

					  				   )
					  );

	var $planetpay = array(
					  'name'		=> 'planetpay',
					  'page'		=> 'https://secure.planetpay.com/gateway/transact.dll',
					  'method'		=> 'post',
					  'force_method'=> '',	//this will force to use: fsockopen, curl_ext, curl

					  'u_user'		=> 'x_login',
					  'u_password'	=> 'x_password',
					  'u_key'		=> 'x_tran_key',
					  'u_email'		=> 'x_email',
					  
					  'o_amount'	=> 'x_amount',
					  'o_orderID'	=> 'x_invoice_number',
					  'o_description'	=> 'x_description',
					  'o_authtype'	=> 'x_type',
					  'o_mode'		=> 'x_trans_id',			//what needs to be done to the credit card, process, void, return/refund
					  'o_authcode'	=> '',
					  'o_transnum'	=> '',

					  'v_currency'	=> '',
					  'v_currency_simbol'	=> '',
					  
					  'c_fname'		=> 'x_first_name',
					  'c_lname'		=> 'x_last_name',
					  'c_address'	=> 'x_address',
					  'c_city'		=> 'x_city',
					  'c_state'		=> 'x_state',
					  'c_zip'		=> 'x_zip',
					  'c_country'	=> 'x_country',
					  'c_phone'		=> 'x_phone',
					  'c_fax'		=> 'x_fax',
					  'c_ip'		=> 'x_customer_ip',
					  'c_email'		=> '',

					  's_fname'		=> 'x_ship_to_first_name',
					  's_lname'		=> 'x_ship_to_last_name',
					  's_address'	=> 'x_ship_to_address',
					  's_city'		=> 'x_ship_to_city',
					  's_state'		=> 'x_ship_to_state',
					  's_zip'		=> 'x_ship_to_zip',
					  's_country'	=> 'x_ship_to_country',
					  's_phone'		=> '',
					  's_fax'		=> '',

					  'cc_name'		=> '',
					  'cc_type'		=> '',
					  'cc_number'	=> 'x_card_num',
					  'cc_expmm'	=> '',
					  'cc_expyy'	=> '',
					  'cc_exp'		=> 'x_exp_date',
					  'cc_cvv'		=> 'x_card_code',
					  'cc_auth'		=> '',
					  
					  'cc_expyyform'=> 'yyyy',	//accept yy or yyyy, this is the format of the year for the system, 2 or 4 numbers
					  'cc_expform'	=> 'my',	//this accept: m = month y = year.  it is the format of the expiration date if the system doesn't support separate month and year

					  'extra'		=> array(
									   //from here set extra fields with the format:
									   // 'field' => 'value'
									   //as require from the API
									   'x_delim_data'	=> 'TRUE',
									   'x_echo_data'	=> 'TRUE',
									   'x_delim_char'	=> ',',
									   'x_encap_char'	=> '"',
									   'x_method'		=> 'CC', //CC, ECHECK
									   'x_test_request'	=> 'FALSE',	//TRUE, FALSE - test mode
									   'x_version'		=> '3.1',
									   'x_ADC_URL'		=> 'false',
									   

					  				   )
					  );

	var $quickcommerce = array(
					  'name'		=> 'quickcommerce',
					  'page'		=> 'https://secure.quickcommerce.com/gateway/transact.dll',
					  'method'		=> 'post',
					  'force_method'=> '',	//this will force to use: fsockopen, curl_ext, curl

					  'u_user'		=> 'x_login',
					  'u_password'	=> 'x_password',
					  'u_key'		=> 'x_tran_key',
					  'u_email'		=> 'x_email',
					  
					  'o_amount'	=> 'x_amount',
					  'o_orderID'	=> 'x_invoice_number',
					  'o_description'	=> 'x_description',
					  'o_authtype'	=> 'x_type',
					  'o_mode'		=> 'x_trans_id',			//what needs to be done to the credit card, process, void, return/refund
					  'o_authcode'	=> '',
					  'o_transnum'	=> '',

					  'v_currency'	=> '',
					  'v_currency_simbol'	=> '',
					  
					  'c_fname'		=> 'x_first_name',
					  'c_lname'		=> 'x_last_name',
					  'c_address'	=> 'x_address',
					  'c_city'		=> 'x_city',
					  'c_state'		=> 'x_state',
					  'c_zip'		=> 'x_zip',
					  'c_country'	=> 'x_country',
					  'c_phone'		=> 'x_phone',
					  'c_fax'		=> 'x_fax',
					  'c_ip'		=> 'x_customer_ip',
					  'c_email'		=> '',

					  's_fname'		=> 'x_ship_to_first_name',
					  's_lname'		=> 'x_ship_to_last_name',
					  's_address'	=> 'x_ship_to_address',
					  's_city'		=> 'x_ship_to_city',
					  's_state'		=> 'x_ship_to_state',
					  's_zip'		=> 'x_ship_to_zip',
					  's_country'	=> 'x_ship_to_country',
					  's_phone'		=> '',
					  's_fax'		=> '',

					  'cc_name'		=> '',
					  'cc_type'		=> '',
					  'cc_number'	=> 'x_card_num',
					  'cc_expmm'	=> '',
					  'cc_expyy'	=> '',
					  'cc_exp'		=> 'x_exp_date',
					  'cc_cvv'		=> 'x_card_code',
					  'cc_auth'		=> '',
					  
					  'cc_expyyform'=> 'yyyy',	//accept yy or yyyy, this is the format of the year for the system, 2 or 4 numbers
					  'cc_expform'	=> 'my',	//this accept: m = month y = year.  it is the format of the expiration date if the system doesn't support separate month and year

					  'extra'		=> array(
									   //from here set extra fields with the format:
									   // 'field' => 'value'
									   //as require from the API
									   'x_delim_data'	=> 'TRUE',
									   'x_echo_data'	=> 'TRUE',
									   'x_delim_char'	=> ',',
									   'x_encap_char'	=> '"',
									   'x_method'		=> 'CC', //CC, ECHECK
									   'x_test_request'	=> 'FALSE',	//TRUE, FALSE - test mode
									   'x_version'		=> '3.1',
									   'x_ADC_URL'		=> 'false',
									   

					  				   )
					  );

	var $viaklix = array(
					  'name'		=> 'viaklix',
					  'page'		=> 'https://www.viaklix.com/process.asp',
					  'method'		=> 'post',
					  'force_method'=> '',	//this will force to use: fsockopen, curl_ext, curl

					  'u_user'		=> 'ssl_user_id', // changed from ssl_merchant_ID
					  'u_password'	=> 'ssl_pin',
					  'u_key'		=> 'ssl_merchant_id',
					  'u_email'		=> 'ssl_merchant_email',
					  
					  'o_amount'	=> 'ssl_amount',
					  'o_orderID'	=> 'ssl_invoice_number',
					  'o_description'	=> 'ssl_description',
					  'o_authtype'	=> '',
					  'o_mode'		=> 'ssl_transaction_type',			//what needs to be done to the credit card: SALE, CREDIT, FORCE
					  'o_authcode'	=> '',
					  'o_transnum'	=> '',

					  'v_currency'	=> '',
					  'v_currency_simbol'	=> '',
					  
					  'c_fname'		=> 'ssl_first_name',
					  'c_lname'		=> 'ssl_last_name',
					  'c_address'	=> 'ssl_avs_address',
					  'c_city'		=> 'ssl_city',
					  'c_state'		=> 'ssl_state',
					  'c_zip'		=> 'ssl_avs_zip',
					  'c_country'	=> 'ssl_country',
					  'c_phone'		=> 'ssl_phone',
					  'c_fax'		=> '',
					  'c_ip'		=> '',
					  'c_email'		=> 'ssl_email',

					  's_fname'		=> 'ssl_ship_to_first_name',
					  's_lname'		=> 'ssl_ship_to_last_name',
					  's_address'	=> 'ssl_ship_to_address',
					  's_city'		=> 'ssl_ship_to_city',
					  's_state'		=> 'ssl_ship_to_state',
					  's_zip'		=> 'ssl_ship_to_zip',
					  's_country'	=> 'ssl_ship_to_country',
					  's_phone'		=> '',
					  's_fax'		=> '',

					  'cc_name'		=> '',
					  'cc_type'		=> '',
					  'cc_number'	=> 'ssl_card_number',
					  'cc_expmm'	=> '',
					  'cc_expyy'	=> '',
					  'cc_exp'		=> 'ssl_exp_date',
					  'cc_cvv'		=> 'ssl_cvv2cvc2',
					  'cc_auth'		=> '',
					  
					  'cc_expyyform'=> 'yy',	//accept yy or yyyy, this is the format of the year for the system, 2 or 4 numbers
					  'cc_expform'	=> 'my',	//this accept: m = month y = year.  it is the format of the expiration date if the system doesn't support separate month and year

					  'extra'		=> array(
									   //from here set extra fields with the format:
									   // 'field' => 'value'
									   //as require from the API
									   'ssl_cvv2'				=> 'present',	//present , bypassed , not present , illegible
									   'ssl_do_customer_email'	=> "FALSE",	//TRUE , FALSE
									   'ssl_do_merchant_email'	=> "FALSE",	//TRUE , FALSE
									   'ssl_result_format'		=> "ASCII",	//ASCII , HTML
									   'ssl_show_form'			=> "FALSE",	//TRUE , FALSE
									   'ssl_test_mode'			=> "FALSE",	//TRUE , FALSE		-- test mode
									   'ssl_transaction_type'	=> "SALE",	//SALE , CREDIT , FORCE
									   'ssl_salestax'			=> 0,
									   'ssl_customer_code'		=> 0
					  				   )
					  );
	var $paynet = array(
					  'name'		=> 'paynet',
					  'page'		=> 'https://www.paynet.no:8210/if2',
					  'method'		=> 'get',
					  'force_method'=> 'curl',	//this will force to use: fsockopen, curl_ext, curl

					  'u_user'		=> 'agrnr',
					  'u_password'	=> '',
					  'u_key'		=> '',
					  'u_email'		=> '',
					  
					  'o_amount'	=> 'totam',
					  'o_orderID'	=> '',
					  'o_description'	=> '',
					  'o_authtype'	=> 'type',
					  'o_mode'		=> 'type',			//what needs to be done to the credit card, process, void, return/refund
					  'o_authcode'	=> 'authnr',
					  'o_transnum'	=> 'tref',

					  'v_currency'	=> 'curry',
					  'v_currency_simbol'	=> '',
					  
					  'c_fname'		=> '',
					  'c_lname'		=> '',
					  'c_address'	=> '',
					  'c_city'		=> '',
					  'c_state'		=> '',
					  'c_zip'		=> '',
					  'c_country'	=> 'ccic',
					  'c_phone'		=> '',
					  'c_fax'		=> '',
					  'c_ip'		=> '',
					  'c_email'		=> '',

					  's_fname'		=> '',
					  's_lname'		=> '',
					  's_address'	=> '',
					  's_city'		=> '',
					  's_state'		=> '',
					  's_zip'		=> '',
					  's_country'	=> '',
					  's_phone'		=> '',
					  's_fax'		=> '',

					  'cc_name'		=> '',
					  'cc_type'		=> '',
					  'cc_number'	=> 'ccnr',
					  'cc_expmm'	=> '',
					  'cc_expyy'	=> '',
					  'cc_exp'		=> 'ccex',
					  'cc_cvv'		=> 'ccode',
					  'cc_auth'		=> 'auth',
					  
					  'cc_expyyform'=> 'yy',	//accept yy or yyyy, this is the format of the year for the system, 2 or 4 numbers
					  'cc_expform'	=> 'my',	//this accept: m = month y = year.  it is the format of the expiration date if the system doesn't support separate month and year

					  'extra'		=> array(
									   //from here set extra fields with the format:
									   // 'field' => 'value'
									   //as require from the API
									   'chpc'				=> '9',	//card holder present code
									   'cced'				=> NULL,  //
					  				   )
					  );
	var $skipjack = array(
					  'name'		=> 'skipjack',
					  'page'		=> 'https://www.skipjackic.com/scripts/EvolvCC.dll?AuthorizeAPI',
					  'method'		=> 'post',
					  'force_method'=> 'curl',	//this will force to use: fsockopen, curl_ext, curl

					  'u_user'		=> 'Serialnumber',
					  'u_password'	=> '',
					  'u_key'		=> 'szDeveloperSerialNumber',
					  'u_email'		=> '',
					  
					  'o_amount'	=> 'Transactionamount',
					  'o_orderID'	=> 'Ordernumber',
					  'o_description'	=> 'comment',
					  'o_authtype'	=> '',
					  'o_mode'		=> '',			//what needs to be done to the credit card, process, void, return/refund
					  'o_authcode'	=> '',
					  'o_transnum'	=> 'trackdata',

					  'v_currency'	=> '',
					  'v_currency_simbol'	=> '',
					  
					  'c_fname'		=> 'sjname',
					  'c_lname'		=> '',
					  'c_address'	=> 'Streetaddress',
					  'c_city'		=> 'City',
					  'c_state'		=> 'State',
					  'c_zip'		=> 'Zipcode',
					  'c_country'	=> 'Country',
					  'c_phone'		=> 'Shiptophone',
					  'c_fax'		=> 'Fax',
					  'c_ip'		=> '',
					  'c_email'		=> 'Email',

					  's_fname'		=> 'Shiptoname',
					  's_lname'		=> '',
					  's_address'	=> 'Shiptostreetaddress',
					  's_city'		=> 'Shiptocity',
					  's_state'		=> 'Shiptostate',
					  's_zip'		=> 'Shiptozipcode',
					  's_country'	=> 'Shiptocountry',
					  's_phone'		=> 'Shiptophone',
					  's_fax'		=> 'Shiptofax',

					  'cc_name'		=> '',
					  'cc_type'		=> '',
					  'cc_number'	=> 'Accountnumber',
					  'cc_expmm'	=> 'Month',
					  'cc_expyy'	=> 'Year',
					  'cc_exp'		=> '',
					  'cc_cvv'		=> 'cvv2',
					  'cc_auth'		=> '',
					  
					  'cc_expyyform'=> 'yy',	//accept yy or yyyy, this is the format of the year for the system, 2 or 4 numbers
					  'cc_expform'	=> 'my',	//this accept: m = month y = year.  it is the format of the expiration date if the system doesn't support separate month and year

					  'extra'		=> array(
									   //from here set extra fields with the format:
									   // 'field' => 'value'
									   //as require from the API
									   'Orderstring'	=>'item~item~0~1~N~||',
					  				   )
					  );
	
	function bibEC_processCard($gateway){
		$this->error['fatal']	= false;	//this can be a WARNING(false) or a FATAL(true) error
		$this->error['number']	= 0;
		$this->error['text']	= '';

		if(in_array($gateway,$this->gateway_accepted)){
			$this->gateway = $gateway;
			$this->set_log('Class initialized - gateway: '.$gateway);
		} else {
			$this->set_error(1001,'gateway not supported, please use: '.implode(',',$this->gateway_accepted),true);
		}
	}
	
	function set_user($user, $password, $key, $email){
		$this->user['u_user']		= $user;
		$this->user['u_password']	= $password;
		$this->user['u_key']		= $key;
		$this->user['u_email']		= $email;
		$this->set_log('user set');
	}
	function set_valuta($currency, $simbol){
		$this->valuta['v_currency'] = $currency;
		$this->valuta['v_currency_simbol'] = $simbol;
		$this->set_log('valuta set');
	}
	
	function set_ccard($name, $type, $number, $expmm, $expyy, $cvv, $cc_auth=''){
		$this->card['cc_name']		= $name;
		$this->card['cc_type']		= $type;
		$this->card['cc_number']	= ereg_replace( "[^0-9]", "", $number);;
		$this->card['cc_expmm']		= $expmm;
		$this->card['cc_expyy']		= $expyy;
		$this->card['cc_cvv']		= $cvv;
		$this->card['cc_auth']		= $cc_auth;	//not always needed.	
		$key = $this->gateway;
		$gateway = $this->$key;
		$this->card['cc_expyyform']		= $gateway['cc_expyyform'];
		$this->card['cc_expform']		= $gateway['cc_expform'];
		$this->set_log('card set');
	}
	
	function set_customer($fname, $lname, $address, $city, $state, $zip, $country, $phone, $fax, $email, $ip = ''){
		$this->customer['c_fname']		= $fname;
		$this->customer['c_lname']		= $lname;
		$this->customer['c_address']	= $address;
		$this->customer['c_city']		= $city;
		$this->customer['c_state']		= $state;
		$this->customer['c_zip']		= $zip;
		$this->customer['c_country']	= $country;
		$this->customer['c_phone']		= $phone;
		$this->customer['c_fax']		= $fax;
		$this->customer['c_email']		= $email;
		if($ip==''){
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$this->user['c_ip']			= $ip;
		$this->set_log('customer set');
	}
	
	function set_ship_to($fname, $lname, $address, $city, $state, $zip, $country, $phone, $fax){
		$this->ship_to['s_fname']		= $fname;
		$this->ship_to['s_lname']		= $lname;
		$this->ship_to['s_address']		= $address;
		$this->ship_to['s_city']		= $city;
		$this->ship_to['s_state']		= $state;
		$this->ship_to['s_zip']			= $zip;
		$this->ship_to['s_country']		= $country;
		$this->ship_to['s_phone']		= $phone;
		$this->ship_to['s_fax']			= $fax;
		$this->set_log('ship_to set');
	}
	
	function set_order($amount, $orderID, $description, $authtype, $mode, $authcode, $transnum, $v_currency='', $v_currency_simbol=''){
		$this->order['o_amount']			= number_format($amount,2,'.','');
		$this->order['o_orderID']			= $orderID;
		$this->order['o_description']		= $description;
		/*
		possible authtype:
			1 -> auth		= check and authorize the CC
			2 -> charge		= check and process the CC
		
		possible mode:
			1 -> process		= process a preauth card
			2 -> void			= void the transaction
			3 -> return			= return / refund the transaction
		*/
		$this->order['o_authtype']			= $authtype;
		$this->order['o_mode']				= $mode;
		$this->order['o_authcode']			= $authcode;
		$this->order['o_transnum']			= $transnum;
		$this->order['v_currency']			= $v_currency;
		$this->order['v_currency_simbol']	= $v_currency_simbol;
		$this->set_log('order set');
	}

	function set_extra($extra){
		//with this function is possible set extra fields required
		//input		: ARRAY
		//output	: NOTHING
		if(is_array($extra)){
			$key = $this->gateway;
			$gateway = $this->$key;
			$gateway['extra'] = array_merge($gateway['extra'],$extra);
			$this->$key = $gateway;
			$this->set_log('extra fields set');
		} else {
			$this->set_error(1002,'extra fields not set, `set_extra` accept only array as input', true);
		}
	}
	
	function _format_year($yy, $yyform){
		//I set the dates as they need to be set
		if($yy!='' && $yyform!=''){
			switch($yyform){
				case 'yy':
					$yy = substr('00'.$yy,-2);
					break;
				case 'yyyy':
					$yy = substr('20'.$yy,-4);
					break;
			}
		}
		return $yy;
	}

	function get_gateway(){
		//return the gateway setting, best if called after that a credit card has been processed
		$this->_set_gateway();
		$key = $this->gateway;
		return $this->$key;
	}

	function _set_gateway(){
		//this function set and return the data for the selected gateway
		$key = $this->gateway;
		$gateway = $this->$key;
		//building the array of values that I need to send
		if(isset($this->card['cc_expyy']) && $this->card['cc_expmm']){
			$this->card['cc_expyy'] = $this->_format_year($this->card['cc_expyy'], $gateway['cc_expyyform']);
			if($this->card['cc_expmm']!='' && $this->card['cc_expyy']!='' && $this->card['cc_expform']!=''){
				$this->card['cc_exp'] = $gateway['cc_expform'];
				$this->card['cc_exp'] = str_replace('m',$this->card['cc_expmm'],$this->card['cc_exp']);
				$this->card['cc_exp'] = str_replace('y',$this->card['cc_expyy'],$this->card['cc_exp']);
			}
		}

		$data = array();
		foreach($gateway as $key=>$value){
			if($key=='extra'){
				if(!empty($value)){
					foreach($value as $key1=>$value1){
						$data[$key1] = $value1;
					}
				}
			}elseif($value!='' && !is_null($value)){
				if($key == 'o_authtype'){
					switch($this->gateway){
						case 'plug_n_pay':
//							possible authtype:
//								1 -> authonly		= check and authorize the CC
//								2 -> authpostauth	= check and process the CC
							switch($this->order[$key]){
								case 1:
								case 'auth':
									$this->order[$key] = 'authonly';
									break;
								case 2:
								case 'charge':
									$this->order[$key] = 'authpostauth';
									break;
								case 0:
								case '':
									unset($this->order[$key]);
									break;
							}
							break;
						case 'ibill':
//							possible authtype:
//								1 -> preauth		= check and authorize the CC
//								2 -> sale			= check and process the CC
//								3 -> ticket			= process a CC that has already been authorize
							switch($this->order[$key]){
								case 1:
								case 'auth':
									$this->order[$key] = 'preauth';
									break;
								case 2:
								case 'charge':
									$this->order[$key] = 'sale';
									break;
								case 3:
								case 'ticket':
									$this->order[$key] = 'ticket';
									break;
								case 0:
								case '':
									unset($this->order[$key]);
									break;
							}
							break;
						case 'authorize_net':
//							possible authtype:
//								1 -> AUTH_ONLY		= check and authorize the CC
//								2 -> AUTH_CAPTURE	= check and process the CC
//								3 -> CAPTURE_ONLY	= capture only the information
//								4 -> PRIOR_AUTH_CAPTURE			= process a preauth card
//								5 -> VOID						= void the transaction
//								6 -> CREDIT						= return / refund the transaction
							switch($this->order[$key]){
								case 1:
								case 'auth':
									$this->order[$key] = 'AUTH_ONLY';
									break;
								case 2:
								case 'charge':
									$this->order[$key] = 'AUTH_CAPTURE';
									break;
								case 3:
								case 'capture':
									$this->order[$key] = 'CAPTURE_ONLY';
									break;
								case 1:
								case 'process':
									$this->order[$key] = 'PRIOR_AUTH_CAPTURE';
									break;
								case 2:
								case 'void':
									$this->order[$key] = 'VOID';
									break;
								case 3:
								case 'credit':
									$this->order[$key] = 'CREDIT';
									break;
								case 0:
								case '':
									unset($this->order[$key]);
									break;
							}
							break;
						case 'planetpay':
						case 'quickcommerce':
//							possible authtype:
//								1 -> AUTH_ONLY		= check and authorize the CC
//								2 -> AUTH_CAPTURE	= check and process the CC
//								3 -> CAPTURE_ONLY	= capture only the information
							switch($this->order[$key]){
								case 1:
								case 'auth':
									$this->order[$key] = 'AUTH_ONLY';
									break;
								case 2:
								case 'charge':
									$this->order[$key] = 'AUTH_CAPTURE';
									break;
								case 3:
								case 'capture':
									$this->order[$key] = 'CAPTURE_ONLY';
									break;
								case 0:
								case '':
									unset($this->order[$key]);
									break;
							}
							break;
						case 'viaklix':
							break;
						case 'paynet':
//							possible authtype:
//								1 -> auth			= check and authorize the CC
//								2 -> sale			= check and process the CC
//								3 -> 				= capture only the information
							switch($this->order[$key]){
								case 1:
								case 'auth':
									$this->order[$key] = 'auth';
									break;
								case 2:
								case 'charge':
									$this->order[$key] = 'sale';
									break;
								case 3:
								case 'capture':
									$this->order[$key] = '';
									break;
								case 0:
								case '':
									unset($this->order[$key]);
									break;
							}
							break;
						case '':
//							possible authtype:
//								1 -> auth			= check and authorize the CC
//								2 -> sale			= check and process the CC
//								3 -> 				= capture only the information
							switch($this->order[$key]){
								case 1:
								case 'auth':
									$this->order[$key] = 'auth';
									break;
								case 2:
								case 'charge':
									$this->order[$key] = 'sale';
									break;
								case 3:
								case 'capture':
									$this->order[$key] = '';
									break;
								case 0:
								case '':
									unset($this->order[$key]);
									break;
							}
							break;
					}
				}elseif($key == 'o_mode'){
					switch($this->gateway){
						case 'plug_n_pay':
//							possible mode:
//								1 -> mark			= process a preauth card
//								2 -> void			= void the transaction
//								3 -> return			= return / refund the transaction
							switch($this->order[$key]){
								case 1:
								case 'process':
									$this->order[$key] = 'mark';
									break;
								case 2:
								case 'void':
									$this->order[$key] = 'void';
									break;
								case 3:
								case 'credit':
									$this->order[$key] = 'return';
									break;
								case 0:
								case '':
									unset($this->order[$key]);
									break;
							}
							break;
						case 'ibill':
							break;
						case 'authorize_net':
							break;
						case 'planetpay':
						case 'quickcommerce':
//							possible mode:
//								1 -> PRIOR_AUTH_CAPTURE			= process a preauth card
//								2 -> VOID						= void the transaction
//								3 -> CREDIT						= return / refund the transaction
							switch($this->order[$key]){
								case 1:
								case 'process':
									$this->order[$key] = 'PRIOR_AUTH_CAPTURE';
									break;
								case 2:
								case 'void':
									$this->order[$key] = 'VOID';
									break;
								case 3:
								case 'credit':
									$this->order[$key] = 'CREDIT';
									break;
								case 0:
								case '':
									unset($this->order[$key]);
									break;
							}
							break;
						case 'viaklix':
							break;
						case 'paynet':
//							possible mode:
//								1 -> sale			= process a preauth card
//								2 -> voidsale		= void the transaction
//								3 -> refund			= return / refund the transaction
							switch($this->order[$key]){
								case 1:
								case 'process':
									$this->order[$key] = 'saletransaction';
									unset($this->order['o_authtype']);
									break;
								case 2:
								case 'void':
									$this->order[$key] = 'voidsale';
									unset($this->order['o_authtype']);
									break;
								case 3:
								case 'credit':
									$this->order[$key] = 'refund';
									unset($this->order['o_authtype']);
									break;
								case 0:
								case '':
									unset($this->order[$key]);
									break;
							}
							break;
						case 'skipjack':
							break;
					}
				}
				if(substr($key,0,2)=='o_'		&& isset($this->order[$key])){		$data[$value] = $this->order[$key];
				}elseif(substr($key,0,2)=='u_'	&& isset($this->user[$key])){		$data[$value] = $this->user[$key];
				}elseif(substr($key,0,2)=='c_'	&& isset($this->customer[$key])){	$data[$value] = $this->customer[$key];
				}elseif(substr($key,0,2)=='s_'	&& isset($this->ship_to[$key])){	$data[$value] = $this->ship_to[$key];
				}elseif(substr($key,0,3)=='cc_'	&& isset($this->card[$key])){		$data[$value] = $this->card[$key];
				}elseif(substr($key,0,2)=='v_'	&& isset($this->valuta[$key])){		$data[$value] = $this->valuta[$key];
				}
			}
		}
		if($gateway['name']=='paynet'){
			//if cced is not set and I am doing a sale, I need the encripted cced from the gateway server
			if(is_null($gateway['extra']['cced']) || $gateway['extra']['cced'] == ''){
				unset($gateway['extra']['cced']);
				unset($data['cced']);
			}
			if(!isset($gateway['extra']['cced']) && 
					(
						(isset($this->order['o_authtype']) &&
							($this->order['o_authtype'] == 'sale' || $this->order['o_authtype'] == 'auth')
						) && (
							isset($this->order['o_mode']) && $this->order['o_mode'] != 'saletransaction' ||
							!isset($this->order['o_mode'])
						)
					)
				){
				//get the cced
				$old_data = $data;
				$data[$gateway['o_authtype']] = 'encode';
				$err = $this->_SendIt($data, $gateway['page'], $gateway['method'], $gateway['force_method']);
				$a = explode('&', $err[0]);
				$i = 0;
				$err = '';
				while ($i < count($a)) {
					$b = split('=', $a[$i]);
					switch($b[0]){
						case 'cced':
							$gateway['extra']['cced'] = htmlspecialchars(urldecode($b[1]));
							$old_data['cced'] = $gateway['extra']['cced'];	//extras
							$this->{$this->gateway}['extra']['cced'] = $old_data['cced'];
							$i=count($a);
							break;
						case 'errm':
						case 'errh':
							$err = trim($err).' '.$b[1];
							break;
					}
					$i++;
				}
				if($err!=''){
					$this->set_error(6001,htmlspecialchars(urldecode($err)),true,htmlspecialchars(urldecode($err)));
				}
				$data = $old_data;
			}
		}
		return $data;
	}

	function process(){
		//before processing the card, I need to check if there is a fatal error
		if($this->error['fatal']==true){
			//I return the error
			return false;
		}
		$err = array();

		$key = $this->gateway;
		$gateway = $this->$key;
		$data = $this->_set_gateway();
		
		if($this->error['fatal']==true){
			//I return the error
			return false;
		}
//			var_dump($data);die();


		$err = $this->_SendIt($data, $gateway['page'], $gateway['method'], $gateway['force_method']);
//		echo '<pre>';print_r($data);echo '</pre>';
//		print_r($err);
		$return = false;
		if (!empty($err) && is_array($err)){	//no errors, I can parse and see if everything was ok
			$this->received = $err;
			switch($gateway['name']){
				case 'plug_n_pay':
					// decode the result page and put it into transaction_array
					$pnp_result_page = implode("\n", $err);
					$pnp_result_decoded = urldecode($pnp_result_page);
					$pnp_temp_array = split('&',$pnp_result_decoded);
					$pnp_transaction_array = array();
					foreach ($pnp_temp_array as $entry) {
						list($name,$value) = split('=',$entry);
						$pnp_transaction_array[$name] = $value."";
					}
					if(!isset($pnp_transaction_array['FinalStatus'])){
						$pnp_transaction_array['FinalStatus'] = '';
					}
					if ($pnp_transaction_array['FinalStatus'] == "success") {
						$return = true;
						if(isset($pnp_transaction_array['auth-code'])){
							$this->authorization = $pnp_transaction_array['auth-code'];
						}
					} elseif ($pnp_transaction_array['FinalStatus'] == "badcard") {
						$this->set_error(2001,$pnp_transaction_array['MErrMsg'], true, $pnp_transaction_array['FinalStatus']);
					} elseif ($pnp_transaction_array['FinalStatus'] == "fraud") {
						$this->set_error(2002,$pnp_transaction_array['MErrMsg'], true, $pnp_transaction_array['FinalStatus']);
					} elseif ($pnp_transaction_array['FinalStatus'] == "problem") {
						$this->set_error(2003,$pnp_transaction_array['MErrMsg'], true, $pnp_transaction_array['FinalStatus']);
					} else {
						// this should not happen
						$this->set_error(2101,'ERROR : FinalStatus='.$pnp_transaction_array['FinalStatus']."\n".$pnp_transaction_array['MErrMsg'], true);
					}
					break;
				case 'ibill':
					$tmp = explode('","',substr($err[0],1,-1));	//I explode the answer excluding the first and last char
																//there will be an array with at least 69 fields
					if($tmp[0]=='authorized') {	//authorized, declined
						$this->authorization = $tmp[1];
						$this->transnum = $tmp[4];
						if(!isset($this->order['o_orderID']) || $this->order['o_orderID']==''){
							$this->order['o_orderID'] = $tmp[4];
						}
						$return = true;
					}elseif($tmp[0]=='declined') {		//authorized, declined
						$this->set_error(5001,$tmp[1],true, $tmp[0]);
					}else{
						if(count($tmp)<3){
							$tmp[3] = $tmp[0];
							$tmp[0] = 0;
						}
						// this should not happen
						$this->set_error(5101,'ERROR : '.$tmp[0].' - '.$tmp[3], true, $tmp[0]);
					}
					break;
				case 'authorize_net':
				case 'planetpay':
				case 'quickcommerce':
					$tmp = explode('","',substr($err[0],1,-1));	//I explode the answer excluding the first and last char
																//there will be an array with at least 69 fields
					if($tmp[0]==1) {	//1 = approved, 2 = declined, 3 = error
						$this->authorization = $tmp[4];
						$this->order['o_orderID'] = $tmp[6];
						$this->transnum = $tmp[6];
						$return = true;
					}elseif($tmp[0]==2) {	//1 = approved, 2 = declined, 3 = error
						$this->set_error(3001,$tmp[3],true, $tmp[0]);
					}elseif($tmp[0]==3) {	//1 = approved, 2 = declined, 3 = error
						$this->set_error(3002,$tmp[3],true, $tmp[0]);
					}else{
						if(count($tmp)<3){
							$tmp[3] = $tmp[0];
							$tmp[0] = 0;
						}
						// this should not happen
						$this->set_error(3101,'ERROR : '.$tmp[0].' - '.$tmp[3], true, $tmp[0]);
					}
					break;
				case 'viaklix':
					// we're looking for the following keys:
					// ssl_result_message  string  text version of the result); may be APPROVED on success
					// ssl_result  int  error or success result code
					// ssl_approval_code  string  the auth code
					// ssl_txn_id  string  the transaction number
					
					parse_str(implode('&',$err),$err);
					// if we got a form or some other such junk, just trigger the error
					if (true == isset($err['ssl_result']) && 0 === (int)$err['ssl_result']) {
						$this->authorization = $err['ssl_approval_code'];
						$this->transnum = $err['ssl_txn_id'];
						$return = true;
					} else {
						if (true == isset($err['ssl_result'])) {
							$this->set_error(4001,'An error occured while processing your card.',true,$err['ssl_result_message'],$err['ssl_result']);
						} else {
							$this->set_error(4001,'An error occured while processing your card.',true);
						}
					}
					break;
				case 'paynet':
					$a = explode('&', $err[0]);
					$i = 0;
					$err = '';
					while ($i < count($a)) {
						$tmp = split('=', $a[$i]);
						switch($tmp[0]){
							case 'errm':
							case 'errh':
								$err = trim($err).' '.$tmp[1];
								break;
							case 'tref':
								$this->transnum = htmlspecialchars(urldecode($tmp[1]));
								break;
							case 'authnr':
								//authorization code
								$this->authorization = htmlspecialchars(urldecode($tmp[1]));
								$return = true;
								break;
						}
						$i++;
					}
					if($err!=''){
						$this->set_error(6002,htmlspecialchars(urldecode($err)),true,htmlspecialchars(urldecode($err)));
					}

					break;
				case 'skipjack':
					$a = explode('","',substr($err[0],1,-1));
					$b = explode('","',substr($err[1],1,-1));
					$allok	= 'szReturnCode';
					$answer	= 'szIsApproved';
					$answerok = '1';
					$auth	= 'AUTHCODE';
					$tnum	= 'szSerialNumber';
					$mserr	= 'szAuthorizationDeclinedMessage';
					$err	= '';
					if($b[array_search($allok,$a)]){
						if($b[array_search($answer,$a)]==$answerok){
							$this->transnum		 = $b[array_search($tnum,$a)];
							$this->authorization = $b[array_search($auth,$a)];
							$return = true;
						} else {
							//declined
	//						$this->set_error(7001,$tmp[3],true, $tmp[0]);
							$err = $b[array_search($mserr,$a)];
							if($err=='')   $err = 'Card Declined';
						}
					} else {
						//there is a problem
						$err = $b[array_search($mserr,$a)];
					}
					if($err!=''){
						$this->set_error(7002,htmlspecialchars(urldecode($err)),true,htmlspecialchars(urldecode($err)));
					}
					break;
			}
			if($this->error['fatal'] == true){
				return false;
			} else {
				return $return;
			}
		}
	}
	
	/**
	 * 
	 * @param int num error number (repackaging of the gateway error?)
	 * @param string text error text
	 * @param boolean type true=FATAL ; false=WARNING ; default: false
	 * @param string gateway_err the error string returned by the gateway
	 * @param int gateway_num the error number as returned by the gateway
	 * @return null
	 */
	function set_error($num, $text, $type=false, $gateway_err = '', $gateway_num=''){
		//if fatal is set to TRUE, the card will not be processed
		//and it will be returned the error
		$this->error['fatal']	= $type;	//this can be a WARNING(false) or a FATAL(true) error
		$this->error['number']	= $num;
		$this->error['text']	= $text;
		$this->error['gerror']	= $gateway_err;
		$this->error['gnumber'] = $gateway_num;
		$this->set_log('ERROR '.$num.' : '.$text."\n");
	}
	
	/**
	 * @param string text message to add to the log array
	 * @return null
	 */
	function set_log($text){
		$this->log[] = $text;
		if($this->file_log !=''){
			if(file_exists($this->file_log)){
				if($fp = @fopen($this->file_log,'a')){
					fwrite($fp,$text);
					fclose($fp);
				} else {
					$this->set_error(1003,'impossible to write to the LOG file. The file state change during the page elaboration', true);
				}
			} else {
				$this->set_error(1004,'impossible to find to the LOG file. The file has been deleted during the page elaboration', true);
			}
		}
	}

	/**
	 * gets this->log array
	 * @return array this->log messages
	 */
	function get_log(){
		//this return the session error
		return $this->log;
	}

	/**
	 * gets all lines from file_log as array
	 * 
	 * @return array array of lines from logfile
	 */
	function get_log_all(){
		//this function retrieve all the log file as an ARRAY
		if(file_exists($this->file_log)){
			if($return  = @file($this->file_log)){
				$this->set_error(0003,'impossible to read the LOG file', false);
				return array();
			} else {
				return $return;
			}
		} else {
			$this->set_error(0004,'the LOG file doesn\'t exist', false);
			return array();
		}
	}
	
	function get_authorization(){
		//after that a card has been processed,
		//sometime it is possible retrieve the authorization code.
		return $this->authorization;
	}
	
	function get_transactionnum(){
		//after that a card has been processed,
		//sometime it is possible retrieve the transaction number (often is the same than the authorization code).
		return $this->transnum;
	}
	
	function get_order_id(){
		//after that a card has been processed,
		//sometime it is possible retrieve the order id if assign by the gateway.
		return $this->order['o_orderID'];
	}
	
	function get_answer(){
		//TODO: implement this function to return an array
		return implode('',$this->received);
	}

	function get_error(){
		return $this->error;
	}
	
	function save_log($file){
		//this function set if the log needs or not to be saved on a file
		if($file!=''){
			if(file_exists($file)){
				if($fp = @fopen($file, 'a')){
					$this->set_error(0002,'impossible to write to the LOG file', false);
				} else {
					fclose($fp);
					$this->file_log = $file;
				}
			} else {
				$fp = @fopen($file,'w');
				@fwrite($fp,'bibEC_processcard LOG');
				@fclose($fp);
			}
			if(!file_exists($file)){
				$this->set_error(0001,'impossible to create the LOG file', false);
			} else {
				$this->file_log = $file;
			}
		} else {
			$this->file_log = '';
		}
	}
	
	function set_curl($path){
		//this function needs the FULL PATH of curl: '/usr/local/bin/curl'
		$this->CURL_PATH = $path;
	}

	function set_crt($path){
		//this function needs the FULL PATH of curl: '/usr/local/bin/curl'
		$this->CERT_FILE = $path;
	}

	function _SendIt($DataStream, $URL, $how = 'POST', $force=''){
/*
		// Strip http:// from the URL if present
		$OLDURL = $URL;
		$URL = ereg_replace("^http://", "", $URL);
		if ($URL != $OLDURL) {
			$HTTP_S = "http://";
			$port = 80;
		} else {
			$URL = ereg_replace("^https://", "", $URL);
			if ($URL != $OLDURL) {
				$HTTP_S = "ssl://";
				$port = 443;
		} 
		// Separate into Host and URI
		$host = substr($URL, 0, strpos($URL, "/"));
		$uri = strstr($URL, "/");
*/
		$parsed = parse_url($URL);
		if(strtolower($parsed['scheme']) == 'http'){
			$HTTP_S = 'http://';
			$port = '80';
		} else {
			$HTTP_S = 'ssl://';
			$port = '443';
		}
		if(isset($parsed['port']) && $parsed['port']>0){
			$port = $parsed['port'];
		}
		$host	= $parsed['host'];
		$uri	= $parsed['path'].(isset($parsed['query'])?'?'.$parsed['query']:'').(isset($parsed['fragment'])?'#'.$parsed['fragment']:'');

		$ReqBody = '';

		foreach($DataStream as $key=>$val){
			if ($ReqBody!='') $ReqBody .= '&';
			$ReqBody .= $key.'='.urlencode($val);
		} 
		$ContentLength = strlen($ReqBody);
		if ((function_exists('version_compare')
			&& version_compare(phpversion(), '4.3.0', '>=')
			&& extension_loaded('openssl')
			&& $force=='')
			|| $force=='fsockopen') {

			$this->set_log('_SendIt(): php ssl');
			$in = @fsockopen($HTTP_S.$host, $port, $errno, $errstr, 60);

			if (!$in) {
				$this->set_error(9201,'Impossible to connect to host: '.$host, true);
				return array();
			} else {
				$ReqHeader = strtoupper($how).' '.$uri." HTTP/1.0\r\n".
							 'Host: '.$host."\r\n".
							 'Referer:'.$_SERVER['HTTP_HOST']."\r\n".
							 "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20061010 Firefox/2.0\r\n".
							 "Content-Type: application/x-www-form-urlencoded\r\n".
							 'Content-Length: '.$ContentLength."\r\n".
							 "Connection: close\r\n".
							 "\r\n".
							 $ReqBody."\n";
				fputs($in, $ReqHeader);
				
			}
			$line = "";
			while (fgets($in, 4096) != "\r\n");

			while ($line = @fgets($in, 4096)){
				$return[] = $line;
			}
	
			fclose($in);
			return $return;
		}elseif ((extension_loaded('curl') && $force=='') || $force=='curl_ext') {
			$this->set_log('_SendIt(): curl ext');

//			if($HTTP_S = 'ssl://') $HTTP_S = 'https://';
//			$ch = curl_init($HTTP_S.$host.'?');

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $URL); 
			curl_setopt($ch, CURLOPT_HEADER, 0); 
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $ReqBody); 
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			if(!extension_loaded('openssl')){
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  // this makes it so you don’t need to have a certificate
			}
			if($this->CERT_FILE!=''){
				curl_setopt($ch, CURLOPT_CAINFO, $this->CERT_FILE); // if you have the crt file, u can specify it here, You don’t need the line above if u set this.
			}
			$tmp = curl_exec($ch); 
			if(curl_errno($ch)>0){
				$this->set_error(9101,curl_error($ch), true);
				return array();
			}
			curl_close($ch);

			$lines = explode("\n", $tmp);

			return $lines;
		} elseif($force == '' || $force == 'curl') {
			$this->set_log('_SendIt(): curl bin');
			if($HTTP_S == 'ssl://') $HTTP_S = 'https://';
			
			if(strtolower($how)=='post'){
				$exec_str = $this->CURL_PATH.' -m 120 -d "'.$ReqBody.'" "'.$HTTP_S.$host.':'.$port.$uri.'" -L';
			} else {
				$exec_str = $this->CURL_PATH.' -m 120 "'.$HTTP_S.$host.':'.$port.$uri.'?'.$ReqBody.'" -L';
			}
			if($this->CERT_FILE!=''){
				$exec_str .= ' --cert "'.$this->CERT_FILE.'"';
			}
			exec($exec_str, $ret_arr, $ret_num);

			if ($ret_num != 0) {
				$this->set_error(9301,'Error while executing: '.$exec_str, true);
				return array();
			}

			if (!is_array($ret_arr)) {
				$this->set_error(9302,'Error while executing: '.$exec_str.' - '.'$ret_arr is not an array', true);
				return array();
			}
			return $ret_arr;
		}
	} 
}
?>