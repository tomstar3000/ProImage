<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("Contact", true);
define('Allow Scripts',true);
require_once($r_path.'scripts/cart/ssl_paths.php'); 
require_once($r_path.'scripts/fnct_clean_entry.php');

$TstFldVal=strtolower(mb_convert_encoding($_POST['Comments'],'UTF-8','HTML-ENTITIES'));
if(preg_match('/<a\s[^>]([^"\'>]*?)\\1[^>]*/eiU',$TstFldVal)==1) $TagInject=true;
if(preg_match('/(Ã.+){10,}/',$_POST['Comments'])==1) $TagInject=true;
if(preg_match('/(Â.+){10,}/',$_POST['Comments'])==1) $TagInject=true;
if(preg_match('/(¥.+){10,}/',$_POST['Comments'])==1) $TagInject=true;

$FID = 586432;
$EncodeTime = isset($_POST['Field']) ? clean_variable($_POST['Field'],true) : '';
$Referer = parse_url($_SERVER['HTTP_REFERER']);
session_start();
$aHeaders = array('HTTP_FORWARDED','HTTP_FORWARDED_FOR','HTTP_X_FORWARDED','HTTP_X_FORWARDED_FOR','HTTP_CLIENT_IP','X-FORWARDED-FOR','X-Forwarded-For','REMOTE_ADDR');
foreach($aHeaders as $v) if(isset($_SERVER[$v])) {$_SESSION['IP_Address'] = $_SERVER[$v]; break; }

if(isset($_POST['Controller']) && $_POST['Controller'] == "true" && isset($_POST['FID']) && $_POST['FID'] == $FID && $_POST['IP_Address'] == $_SESSION['IP_Address'] && strlen(trim($_POST['Form_Id'])) == 0 && (!isset($_SESSION['Time']) || $_POST['Time'] != $_SESSION['Time']) && $EncodeTime == base64_encode(md5($_POST['Time'])) && $Referer['path'] == $_SERVER['PHP_SELF']){
//if(isset($_POST['Controller']) && $_POST['Controller'] == "true"){
	$_SESSION['Time'] = $_POST['Time'];
	if (strtoupper(substr(PHP_OS,0,3))=='WIN') $eol="\r\n"; 
	else if (strtoupper(substr(PHP_OS,0,3))=='MAC') $eol="\r"; 
	else $eol="\n"; 
	
	require_once($r_path.'includes/CheckBrowser.php');
	$browser = new Browser();
	if($TagInject == false && $browser->getBrowser() != 'unknown' && $browser->isRobot() == false){
			
		require_once $r_path.'scripts/fnct_phpmailer.php';
		$Name = sanatizeentry($_POST['Name'],false);
		$Phone = sanatizeentry($_POST['Phone_Number'],false);
		$Email = sanatizeentry($_POST['Email'],false);
		$Comm = sanatizeentry($_POST['Comments'],false);
		
		$reciever = "support@proimagesoftware.com";
		//$reciever = "development@proimagesoftware.com";
		$subject = "On-line Contact Form";
		$rsubject = "Thank you for contacting proimagesoftware.com";
		
		$msg = "Name: ".$Name.$eol;
		$msg .= "Phone Number: ".$Phone.$eol;
		$msg .= "E-mail: ".$Email.$eol.$eol;
		$msg .= "Comments".$eol;
		$msg .= "------------------------".$eol;
		$msg .= $Comm;
		$rmsg = "Thank you for contacting Pro Image Software. A representative will get back to you within 24 hours either by phone or email. Should you need to talk with someone right away please feel free to call us at 303.440.6008. We greatly appreciate your comments, feedback and questions as it only makes our system better for everybody. We look forward to the opportunity to serve you.";
		
		$mail = new PHPMailer();
		//$mail -> IsSMTP();
		$mail -> IsSendMail();
		$mail -> Host = "smtp.proimagesoftware.com";
		$mail -> IsHTML(false);
		$mail -> Sender = "info@proimagesoftware.com";
		$mail -> Hostname = "proimagesoftware.com";
		$mail -> From = $Email;
		$mail -> AddAddress($reciever);
		$mail -> Subject = $subject;
		$mail -> Body = $msg;
		$mail -> Send();
		
		$mail = new PHPMailer();
		//$mail -> IsSMTP();
		$mail -> IsSendMail();
		$mail -> Host = "smtp.proimagesoftware.com";
		$mail -> IsHTML(false);
		$mail -> Sender = "info@proimagesoftware.com";
		$mail -> Hostname = "proimagesoftware.com";
		$mail -> From = $reciever;
		$mail -> AddAddress($Email);
		$mail -> Subject = $rsubject;
		$mail -> Body = $rmsg;
		$mail -> Send();
	}
	
	$GoTo = "/thankyou.php".$token;
	header(sprintf("Location: %s", $GoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include $r_path.'includes/_metadata.php'; ?>
<link href="/css/ProImageSoftware_09.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="Container">
  <? include $r_path.'includes/_navigation.php'; ?>
  <div id="Content2" class="Grey">
    <div id="Text">
      <h1 class="HdrContactUs"><span>Contact Us</span></h1>
      <br clear="all" />
      <div class="BgText">
        <div class="BgTextBottom"><br clear="all" />
          <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>" name="Contact Form" id="Contact_Form" enctype="multipart/form-data">
            <div class="Column">
              <p><strong>Pro Image Software</strong></p>
              <p>303.440.6008<br />
                <a href="mailto:info@proimagesoftware.com">info@proimagesoftware.com</a><br />
                <a href="mailto:support@proimagesoftware.com">support@proimagesoftware.com</a> </p>
            </div>
            <div class="Column">
              <label for="Name" style="width:180px;">Name *</label>
              <label for="Phone_Number">Phone Number:</label>
              <br clear="all" />
              <div class="CstmInput2">
                <input type="text" name="Name" id="Name" title="Name" value="<? echo $Name; ?>" tabindex="<? $tab=1; $ReqStr=array(); echo $tab++; $ReqStr[] = "'Name','','R'"; ?>" />
              </div>
              <div class="CstmInput2">
                <input type="text" name="Phone Number" id="Phone_Number" title="Phone_Number" value="<? echo $Phone; ?>" tabindex="<? echo $tab++;?>" />
              </div>
              <br clear="all" />
              <label for="Email">E-mail Address *</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Email" id="Email" title="Email Address" value="<? echo $Email; ?>" tabindex="<? echo $tab++; $ReqStr[] = "'Email','Email Address','R'"; ?>" />
              </div>
              <br clear="all" />
              <label>Comments / Questions: * </label>
              <br clear="all" />
              <div class="CstmTextArea">
                <textarea name="Comments" id="Comments" value="<? echo $Comm; ?>" tabindex="<? echo $tab++; $ReqStr[] = "'Comments','','R'"; ?>"><? echo $Comm; ?></textarea>
              </div>
              <br clear="all" />
              <div class="BtnContinue">
                <input type="submit" name="btnSubmit" id="btnSumbit" value="Submit" title="Submit" onclick="javascript: MM_validateForm(<? echo implode(",",$ReqStr); ?>);return document.MM_returnValue;" />
              </div>
              <input type="hidden" name="Controller" id="Controller" value="true" />
              <input type="text" name="Field" id="Field" value="<? echo base64_encode(md5(time())); ?>" style="display:none;" />
              <input type="hidden" value="<? echo $FID; ?>" name="FID" id="FID" />
              <input type="hidden" name="IP_Address" id="IP_Address" value="<? echo $_SESSION['IP_Address']; ?>" />
              <input type="text" name="Form Id" id="Form_Id" value="" class="FormCheckField" />
              <input type="hidden" name="Time" id="Time" value="<? echo time(); ?>" />
              <input type="hidden" name="Refer" id="Refer" value="<? echo $_SERVER['HTTP_REFERER']; ?>" />
            </div>
            <br clear="all" />
          </form>
          <br clear="all" />
        </div>
      </div>
      <br clear="all" />
    </div>
  </div>
  <? include $r_path.'includes/_footer.php'; ?>
</div>
</body>
</html>
