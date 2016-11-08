<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_format_date.php';
require_once $r_path.'scripts/encrypt.php';
require_once $r_path.'scripts/emogrifier.php';
require_once $r_path.'scripts/fnct_phpmailer.php';
$MId = $path[2];

$is_process = true;
if (strtoupper(substr(PHP_OS,0,3))=='WIN') $eol="\r\n"; else if (strtoupper(substr(PHP_OS,0,3))=='MAC')	$eol="\r"; else $eol="\n";
function FindCSS($CSSLink){
	global $CSS, $Template, $r_path;
	$path_parts = pathinfo($CSSLink);
	$path = $path_parts['basename'];
	$path_parts = pathinfo($Template);
	$path = $path_parts['dirname']."/".$path;
	$Handle = fopen($r_path.$path, "r") or die("Failed Opening ".$r_path.$path);
	while (!feof($Handle)) $CSS .= fread($Handle, 8192);
	fclose($Handle);
	return "";
}
function FindCSS2($StyleSheet){
	global $CSS;
	$CSS .= $StyleSheet;
	return "";
}
function cleanUpHTML($text) {
	$text = ereg_replace(" style=[^>]*","", $text);
	return ($text);
}

$required_string = array();
$EID = (isset($_POST['Event'])) ? clean_variable($_POST['Event'],true) : ''; 												//$required_string[] = "'Event','','RisSelect'";
$FName = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : '';						$required_string[] = "'First_Name','','R'";
$LName = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : '';							$required_string[] = "'Last_Name','','R'";
$Phone = (isset($_POST['Phone_Number'])) ? clean_variable($_POST['Phone_Number'],true) : '';				$required_string[] = "'Phone_Number','','R'";
$P1 = (isset($_POST['P1'])) ? clean_variable($_POST['P1'],true) : '';
$P2 = (isset($_POST['P2'])) ? clean_variable($_POST['P2'],true) : '';
$P3 = (isset($_POST['P3'])) ? clean_variable($_POST['P3'],true) : '';
$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';											$required_string[] = "'Email','','RisEmail'";
$Add = (isset($_POST['Address'])) ? clean_variable($_POST['Address'],true) : '';										$required_string[] = "'Address','','R'";
$SApt = (isset($_POST['Suite_Apt'])) ? clean_variable($_POST['Suite_Apt'],true) : '';
$Add2 = (isset($_POST['Address_2'])) ? clean_variable($_POST['Address_2'],true) : '';
$City = (isset($_POST['City'])) ? clean_variable($_POST['City'],true) : '';													$required_string[] = "'City','','R'";
$State = (isset($_POST['State'])) ? clean_variable($_POST['State'],true) : '';											$required_string[] = "'State','','R'";
$Zip = (isset($_POST['Zip'])) ? clean_variable($_POST['Zip'],true) : '';														$required_string[] = "'Zip','','R'";
$Country = (isset($_POST['Country'])) ? clean_variable($_POST['Country'],true) : '';								$required_string[] = "'Country','','R'";
$CType = (isset($_POST['Type_of_Card'])) ? clean_variable($_POST['Type_of_Card'],true) : '';				//$required_string[] = "'Type_of_Card','','RisSelect'";
$CCNum = (isset($_POST['Credit_Card_Number'])) ? clean_variable(ereg_replace("[^0-9]","",$_POST['Credit_Card_Number']),true) : '';
$CC4Num = substr($CCNum,-4,4);																																			$required_string[] = "'Credit_Card_Number','','R'";
if($CCNum != "" && strlen(trim($CCNum)) > 4){
	$CCNum = encrypt_data($CCNum);
	$updthis = ", `cc_num` = '$CCNum', `cc_short` = '$CC4Num' ";
} else {
	$updthis = "";
}
$CCV = (isset($_POST['CCV_Code'])) ? clean_variable($_POST['CCV_Code'],true) : '';									$required_string[] = "'CCV_Code','','R'";
$CCM = (isset($_POST['Experation_Month'])) ? clean_variable($_POST['Experation_Month'],true) : '';	//$required_string[] = "'Experation_Month','','RisSelect'";
$CCY = (isset($_POST['Experation_Year'])) ? clean_variable($_POST['Experation_Year'],true) : '';		//$required_string[] = "'Experation_Year','','RisSelect'";
$Amt = (isset($_POST['Charge_Amount'])) ? clean_variable(ereg_replace("[^0-9.]","",$_POST['Charge_Amount']),true) : '';				$required_string[] = "'Charge_Amount','','RisSelect'";
$PDate = (isset($_POST['Process_Month'])) ? clean_variable($_POST['Process_Year']."-".$_POST['Process_Month']."-".$_POST['Process_Day']." OO:OO:00",true) : date("Y-m-d H:i:s");
$Det = (isset($_POST['Transaction_Detail'])) ? clean_variable($_POST['Transaction_Detail'],true) : ''; $required_string[] = "'Transaction_Detail','','R'";
$Fail = '';
$FailMsg = '';
$Proc = 'n';
$Ref = 'n';
$Error = false;
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	$SDiscon = date("Y-m-d H:i:s",mktime(0,0,0,substr($CDiscon,0,2),substr($CDiscon,3,2),substr($CDiscon,6,4)));
	if($cont == "add"){
		$query_get_last = "SELECT `invoice_id` FROM `photo_invoices` ORDER BY `invoice_id` DESC LIMIT 0,1";
		$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
		$row_get_last = mysql_fetch_assoc($get_last);
		
		$invnum = intval($row_get_last['invoice_id'])+1001;
		$encnum = md5($invnum);
		
		$addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$addInfo->mysql("INSERT INTO `photo_invoices` (`cust_id`, `event_id`, `fname`, `lname`, `phone`, `email`, `add`, `add2`, `suite_apt`, `city`, `state`, `zip`, `country`, `cc_num`, `cc_short`, `cc_type`, `cc_exp_month`, `cc_exp_year`, `cc_ccv`, `amount`, `chrg_date`, `date`, `inv_num`, `inv_enc`, `detail`) VALUES ('$CustId', '$EID', '$FName', '$LName', '$Phone', '$Email', '$Add', '$Add2', '$SApt', '$City', '$State', '$Zip', '$Country', '$CCNum', '$CC4Num', '$CType', '$CCM', '$CCY', '$CCV', '$Amt', '$PDate', NOW(), '$invnum', '$encnum', '$Det');");
		
		$getLast = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getLast->mysql("SELECT `invoice_id` FROM `photo_invoices` WHERE `cust_id` = '$CustId' ORDER BY `invoice_id` DESC LIMIT 0,1;");
		$getLast = $getLast->Rows(); $MId = $getLast[0]['invoice_id'];
		array_push($path,$MId);
	} else {
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT * FROM `photo_invoices` WHERE `invoice_id` = '$MId';");
		$getInfo = $getInfo->Rows();
		// yyyy-mm-dd HH:ii:ss
		// 0123456789012345678
		// 1234567890123456789
		//if($getInfo[0]['fail'] == 'y' && date("U",mktime(date("H"),date("i"),date("s"),substr($getInfo[0]['chrg_date'],5,2),substr($getInfo[0]['chrg_date'],8,2), substr($getInfo[0]['chrg_date'],0,4))) >= date("U")){
		if($getInfo[0]['fail'] == 'y'){
			$updthis .= " , `fail_msg` = '' , `fail` = 'n', `process` = 'n' ";
		}
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql("UPDATE `photo_invoices` SET `event_id` = '$EID', `fname` = '$FName', `lname` = '$LName', `phone` = '$Phone', `email` = '$Email', `add` = '$Add', `add2` = '$Add2', `suite_apt` = '$SApt', `city` = '$City', `state` = '$State', `zip` = '$Zip', `country` = '$Country' ".$updthis.", `cc_type` = '$CType', `cc_exp_month` = '$CCM', `cc_exp_year` = '$CCY', `cc_ccv` = '$CCV', `amount` = '$Amt', `chrg_date` = '$PDate', `detail` = '$Det' WHERE `invoice_id` = '$MId';");
				
		$updInfo->mysql("SELECT `cc_num`,`inv_num` FROM `photo_invoices` WHERE `invoice_id` = '$MId';");
		$CCNum = $updInfo->Rows(); 
		$invnum = $CCNum[0]['inv_num'];
		$encnum = md5($invnum);
		$CCNum = $CCNum[0]['cc_num'];
	}
	
	//  ***************** Process the credit card ***************** //
	
	require_once ($r_path.'../scripts/cart/merchant_ini_2.php');
	$ini_approved = false;
	if($Amt > 0){
		if($is_process){
			$bibEC_ccp = new bibEC_processCard('authorize_net');
			$bibEC_ccp->set_user($CCUName,'',$CCKey,'');
			$bibEC_ccp->set_customer($FName, $LName, 
															 $Add, 	$City,  $State, $Zip, 
															 $Country, $Phone, '', $Email);
			$bibEC_ccp->set_ccard(  $FName.' '.$LName, 
															$CType, decrypt_data($CCNum), $CCM,
															$CCY,   $CCV); 
			$bibEC_ccp->set_valuta( 'USD','$');
			$bibEC_ccp->set_order($Amt,'','','charge','process',NULL,NULL,NULL,NULL);
			if(!$bibEC_ccp->process()){ // Process the credit card information
				$ini_error =  $bibEC_ccp->get_error(); 	// Get Error
				$ini_approved = false; 									// Set Approved to false
				$ini_error_msg = $ini_error['text']; 		// Get Error Message
				$ini_error = $ini_error['gnumber'];			// Get Error Number
			} else { 
				//echo $bibEC_ccp->get_authorization();
				$ini_tran_id = $bibEC_ccp->get_transactionnum(); // Get the Transaction Number from Gateway
				$ini_approved = true;										// Set Approved to true
			}
		} else {
			$ini_approved = true;
		}
	} else {
		$ini_approved = true;
		$ini_tran_id = '1111111111111111111111111111';
	}
	if($ini_approved == true){
		$getPhoto = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getPhoto->mysql("SELECT `cust_customers`.`cust_cname`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_handle`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_website`, `cust_customers`.`cust_email`, `photo_event`.`event_num`, `photo_event`.`event_name`, `photo_event_images`.`image_folder`, `photo_event_images`.`image_tiny` FROM `cust_customers` 
	LEFT JOIN `photo_event` ON ( `photo_event`.`event_id` = '$EID' OR `photo_event`.`event_id` IS NULL)
	LEFT JOIN `photo_event_images` ON (`photo_event_images`.`image_id` = `photo_event`.`event_image` OR `photo_event_images`.`image_id` IS NULL)
	WHERE `cust_customers`.`cust_id` = '$CustId'
	GROUP BY `cust_customers`.`cust_id`;");
		$getPhoto = $getPhoto->Rows();
		$Handle = $getPhoto[0]['cust_handle'];
		$address = (strlen(trim($getPhoto[0]['cust_city'])) > 0)?$getPhoto[0]['cust_city']." ".$getPhoto[0]['cust_state']." ".$getPhoto[0]['cust_zip']:'';
		$website = (strlen(trim($getPhoto[0]['cust_website'])) > 0)?$getPhoto[0]['cust_website']:'';
		$Photographer = ($getPhoto[0]['cust_cname'] == "") ? $getPhoto[0]['cust_fname']." ".$getPhoto[0]['cust_lname'] : $getPhoto[0]['cust_cname'];
		$PhotoEmail =  $getPhoto[0]['cust_email'];
		
		//$PhotoEmail = "development@proimagesoftware.com";
		
		$Event = $getPhoto[0]['event_num'];
		$EName = $getPhoto[0]['event_name'];
		
		$BioImage = "Logo.jpg";
		if(is_file($r_path."../photographers/".$Handle."/".$BioImage)){
			list($BioWidth, $BioHeight) = getimagesize($r_path."../photographers/".$Handle."/".$BioImage);
			$BioImage = $r_path."../photographers/".$Handle."/".$BioImage;
			if($BioWidth > 700){
				$Ration = 700/$BioWidth;
				$BioWidth = 700;
				$BioHeight = $BioHeight*$Ration;
			}
		} else {
			$BioImage = false;
		}
		$IName = $getPhoto[0]['image_tiny'];
		$Image = $r_path.substr($getPhoto[0]['image_folder'],0,-11)."Thumbnails/".$IName;
		
		if(!is_file($Image)){
			$Image = false;
			$IName = false;
		}
		
		ob_start(); include($r_path.'../Templates/Photographer/invoice.php');
		$msg = ob_get_contents(); ob_end_clean();
		
		$Time = time();
		$title = 'Invoice #'.$invnum;
		
		if($Image !== false) {
			list($width, $height) = getimagesize($Image);
			if($width > $height){ $Perc = 242/$width; $height = round($height*$Perc); $width = 242; }
			else { $Perc = 242/$height; $width = round($width*$Perc); $height = 242; }
			$msg = str_replace('[EmbedImg]','<img class="img" src="cid:'.$Time.'" width="'.$width.'" height="'.$height.'" alt="'.$Handle.'" vspace="0" hspace="0" alt="'.$title.'" style="border: 3px solid #c89441;"><br clear="all" />',$msg);
		} else { $msg = str_replace('[EmbedImg]','',$msg); }
		
		$msg = str_replace('[Title]',$title,$msg);
		$msg = str_replace('[Unsubscribe]','',$msg);
		$msg = str_replace('[Photographer]',$Photographer,$msg);
		$msg = str_replace('[Address]',((strlen(trim($address))>0)?'<br />'.$address:''),$msg);
		$msg = str_replace('[Website]',((strlen(trim($website))>0)?'<br /><a href="'.$website.'" title="'.$website.'">'.$website.'</a>':''),$msg);
		
		$Desc = "<p>Thank you for your order from ".$Photographer.". This is your receipt for your transaction. Please be advised that your credit card statement will reflect this charge as being from Pro Image Software, not ".$Photographer.". Should you have any questions regarding this transaction please contact your photographer or feel free to email us at ".$PhotoEmail.".</p><p>".$Det."</p><p><strong>Total: </strong>$".number_format($Amt,2,".",",")."</p><p>Thank you again and have a wonderful day.</p>";
		
		$msg = str_replace('[Text]','<p>'.ereg_replace(array("^<p>","</p>$"),'',sanatizeentry($Desc,true)).'</p>',$msg);
		$msg = str_replace('[Coupons]','',$msg);
		$msg = str_replace('[Name]',$FName.' '.$LName,$msg);
		if(strlen(trim($Event)) > 0){
			$msg = str_replace(array('[Link]','%5BLink%5D'),"http://www.proimagesoftware.com/photo_viewer.php?Photographer=".$Handle."&code=".$Event."&email=".$v."&full=true",$msg);
			$msg = str_replace(array('[Link2]','%5BLink2%5D'),wordwrap("http://www.proimagesoftware.com/photo_viewer.php?Photographer=".$Handle."&code=".$Event."&email=".$v."&full=true", 100, $eol),$msg);
		} else {
			$msg = str_replace(array('[Link]','%5BLink%5D'),"http://www.proimagesoftware.com/".$Handle,$msg);
			$msg = str_replace(array('[Link2]','%5BLink2%5D'),wordwrap("http://www.proimagesoftware.com/".$Handle, 100, $eol),$msg);
		}
		
		$Pattern = array();
		$Pattern[] = '/<link\s[^>]*href=(["\']??)([^"\'>]*?)\\1[^>]*rel="stylesheet"(.*)>/eiU';
		$Pattern[] = '@<style[^>]*?>.*?</style>@esiU';
		
		$CSS = "";
		$msg = preg_replace($Pattern[0],"FindCSS('$2')",$msg);
		$msg = preg_replace($Pattern[1],"FindCSS2('$0')",$msg);
		
		$InlineHTML = new Emogrifier();
		$InlineHTML -> setHTML($msg);
		$InlineHTML -> setCSS($CSS);
	
		$msg = removeSpecial($msg);
		$msg = $InlineHTML -> emogrify();
		
		while(strpos("\r",$msg) !== false || strpos("\n",$msg) !== false || strpos("\r\n",$msg) !== false || strpos("\n\r",$msg) !== false ){
			$msg = trim(str_replace(array("\r","\n","\r\n","\n\r"),"",$msg));
		}
		
		$msg = preg_replace("/ +/", " ", $msg);
		$msg = str_replace('href= "', 'href="', $msg);
		$msg = str_replace('src="/','src="http://'.$_SERVER['HTTP_HOST'].'/',$msg);
		$msg = str_replace('url(/','url(http://'.$_SERVER['HTTP_HOST'].'/',$msg);
		$msg = clean_html_code($msg);
		
		$mail = new PHPMailer();
		$mail -> IsSendMail();
		$mail -> Host = "smtp.proimagesoftware.com";
		$mail -> IsHTML(true);
		$mail -> Sender = "info@proimagesoftware.com";
		$mail -> Hostname = "proimagesoftware.com";
		$mail -> From = $PhotoEmail;
		$mail -> FromName = $Photographer;
		$mail -> ReplyTo = $PhotoEmail;
		$mail -> AddAddress($Email);
		$mail -> Subject = $title;
		if($Image !== false) $mail -> AddEmbeddedImage($Image, $Time);
		$mail -> Body = $msg;
		$mail -> Send();
		
		$path[0]='Busn';
		$path[1]='Past';
		
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql("UPDATE `photo_invoices` SET `process` = 'y', `trans_num` = '$ini_tran_id', `cc_num` = '".encrypt_data('')."' WHERE `invoice_id` = '$MId';");
		
		$Proc = 'y';
		$Fail = 'n';
		$cont = "view";
	} else {
		$Fail = 'y';
		$Error = $ini_error_msg;
		
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql("UPDATE `photo_invoices` SET `process` = 'y', `fail` = 'y', `fail_msg` = '$ini_error_msg' WHERE `invoice_id` = '$MId';");
		$cont = "edit";
	}
	//  ***************** Process the credit card ***************** //
} else {
	if($cont == 'refund'){
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT * FROM `photo_invoices` WHERE `invoice_id` = '$MId';");
		$getInfo = $getInfo->Rows();
		//$tranNum = explode(".",$getInfo[0]['trans_num']);
		require_once $r_path.'../scripts/cart/merchant_ini_2.php';		
		$bibEC_ccp = new bibEC_processCard('authorize_net'); 
		//$bibEC_ccp->save_log($file);
		$bibEC_ccp->set_user($CCUName,'',$CCKey,'');
		// Customer Information for Order
		$bibEC_ccp->set_valuta('USD','$');
		$CCNum = $getInfo[0]['cc_short']; while(strlen($CCNum) < 4) $CCNum = "0".$CCNum;
		$ExpMon = $getInfo[0]['cc_exp_month']; while(strlen($ExpMon) < 2) $ExpMon = "0".$ExpMon;
		$bibEC_ccp->set_ccard($getInfo[0]['fname'].' '.$getInfo[0]['lname'], $getInfo[0]['cc_type'], $CCNum, '', '', $getInfo[0]['cc_ccv']); 
		$bibEC_ccp->set_order($getInfo[0]['amount'],'','','credit',$getInfo[0]['trans_num'], NULL, NULL, NULL, NULL);
		
		if(!$bibEC_ccp->process()){ // Process the credit card information
			$ini_error =  $bibEC_ccp->get_error(); 	// Get Error
			$ini_approved = false; 									// Set Approved to false
			$ini_error_msg = $ini_error['text']; 		// Get Error Message
			$ini_error = $ini_error['gnumber'];			// Get Error Number
			$Error = $ini_error_msg;
		} else { 
			$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$updInfo->mysql("UPDATE `photo_invoices` SET `refund` = 'y' WHERE `invoice_id` = '$MId';");
		}
		$cont = 'view';
	}
	if($cont != "add"){
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT * FROM `photo_invoices` WHERE `invoice_id` = '$MId';");
		$getInfo = $getInfo->Rows();
		
		$EID = $getInfo[0]['event_id'];
		$FName = $getInfo[0]['fname'];
		$LName = $getInfo[0]['lname'];
		$Phone = $getInfo[0]['phone'];
		$P1 = substr($getInfo[0]['phone'],0,3);
		$P2 = substr($getInfo[0]['phone'],3,3);
		$P3 = substr($getInfo[0]['phone'],6,4);
		$Email = $getInfo[0]['email'];
		$Add = $getInfo[0]['add'];
		$SApt = $getInfo[0]['suite_apt'];
		$Add2 = $getInfo[0]['add2'];
		$City = $getInfo[0]['city'];
		$State = $getInfo[0]['state'];
		$Zip = $getInfo[0]['zip'];
		$Country = $getInfo[0]['country'];
		$CType = $getInfo[0]['cc_type'];
		$CCNum = '';
		$CC4Num = $getInfo[0]['cc_short'];
		$CCV = $getInfo[0]['cc_ccv'];
		$CCM = $getInfo[0]['cc_exp_month'];
		$CCY = $getInfo[0]['cc_exp_year'];
		$Amt = $getInfo[0]['amount'];
		$PDate = $getInfo[0]['chrg_date'];
		$Det = $getInfo[0]['detail'];
		$Fail = $getInfo[0]['fail'];
		$FailMsg = $getInfo[0]['fail_msg'];
		$Proc = $getInfo[0]['process'];
		$Ref = $getInfo[0]['refund'];
		if($Ref == 'y') $Error = "This invoice has been refunded";
	}
}
define('NoSave',true);
if(isset($required_string))
	$onclick = 'MM_validateForm('.implode(",",$required_string).'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();} else {send_Msg(\'\',false,null,undefined);}';
else 
	$onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
?>