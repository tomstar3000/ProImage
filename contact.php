<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../";
define('Allow Scripts',true);
define('PAGE',"Contact");
require_once($r_path.'scripts/cart/ssl_paths.php'); 
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_format_phone.php');
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_sql_processor.php');

require_once($r_path.'includes/_PhotoInfo.php');
require_once($r_path.'includes/_Guestbook.php');

$email_sent = false;
$FID = 7896541;
$EncodeTime = isset($_POST['Field']) ? clean_variable($_POST['Field'],true) : '';
$Referer = parse_url($_SERVER['HTTP_REFERER']);
session_start();
$aHeaders = array('HTTP_FORWARDED','HTTP_FORWARDED_FOR','HTTP_X_FORWARDED','HTTP_X_FORWARDED_FOR','HTTP_CLIENT_IP','X-FORWARDED-FOR','X-Forwarded-For','REMOTE_ADDR');
foreach($aHeaders as $v) if(isset($_SERVER[$v])) {$_SESSION['IP_Address'] = $_SERVER[$v]; break; }

if(isset($_POST['Controller']) && $_POST['Controller'] == "true" && isset($_POST['FID']) && $_POST['FID'] == $FID && $_POST['IP_Address'] == $_SESSION['IP_Address'] && strlen(trim($_POST['Form_Id'])) == 0 && (!isset($_SESSION['Time']) || $_POST['Time'] != $_SESSION['Time']) && $EncodeTime == base64_encode(md5($_POST['Time'])) && $Referer['path'] == $_SERVER['PHP_SELF']){
//if(isset($_POST['Controller']) && $_POST['Controller'] == "true"){
	$_SESSION['Time'] = $_POST['Time'];
	if (strtoupper(substr(PHP_OS,0,3))=='WIN') $eol="\r\n"; 
	elseif (strtoupper(substr(PHP_OS,0,3))=='MAC') $eol="\r"; 
	else $eol="\n"; 
	require_once($r_path.'includes/CheckBrowser.php');
	$browser = new Browser();
	if($TagInject == false && $browser->getBrowser() != 'unknown' && $browser->isRobot() == false){	
		require_once $r_path.'scripts/fnct_phpmailer.php';
		$Name = sanatizeentry($_POST['Name'],false);
		$Phone = sanatizeentry($_POST['Phone_Number'],false);
		$Email = sanatizeentry($_POST['Email'],false);
		$Comm = sanatizeentry($_POST['Comments'],false);
		
		$reciever = $getInfo[0]['cust_email'];
		//$reciever = "development@proimagesoftware.com";
		$subject = "On-line Contact Form";
		$rsubject = "Thank you for contacting us.";
		
		$msg = "Name: ".$Name.$eol;
		$msg .= "Phone Number: ".$Phone.$eol;
		$msg .= "E-mail: ".$Email.$eol.$eol;
		$msg .= "Comments".$eol;
		$msg .= "------------------------".$eol;
		$msg .= $Comm;
		$rmsg = "Thank you for contacting us at ".$getInfo[0]['cust_cname'].".  If you had any question we will answer as soon as we can.";
		
		$mail = new PHPMailer();
		//$mail -> IsSMTP();
		$mail -> Host = "smtp.proimagesoftware.com";
		$mail -> IsHTML(false);
		$mail -> IsSendMail();
		$mail -> Sender = "info@proimagesoftware.com";
		$mail -> Hostname = "proimagesoftware.com";
		$mail -> From = $Email;
		$mail -> AddAddress($reciever);
		$mail -> Subject = $subject;
		$mail -> Body = $msg;
		$mail -> Send();
		
		$mail = new PHPMailer();
		//$mail -> IsSMTP();
		$mail -> Host = "smtp.proimagesoftware.com";
		$mail -> IsHTML(false);
		$mail -> IsSendMail();
		$mail -> Sender = "info@proimagesoftware.com";
		$mail -> Hostname = "proimagesoftware.com";
		$mail -> From = $reciever;
		$mail -> AddAddress($Email);
		$mail -> Subject = $rsubject;
		$mail -> Body = $rmsg;
		$mail -> Send();
	}
	$email_sent = true;
} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include $r_path.'includes/_metadata.php'; ?>
<link href="/css/Photographer.css" rel="stylesheet" type="text/css" />
<? if($launch_full === true){ ?>
<script type="text/JavaScript">
AEV_new_window("<? echo $GoTo; ?>","ProImageSoftware",null,null,null,null,null,null,null,null,true);
</script>
<? } ?>
</head>
<body>
<div id="Container">
  <div id="Logo">
    <? if($getInfo[0]['cust_image'] !="" ){
		$data = array(
			'imagetype' => 'logo',
			'image' => $handle.'/'.$getInfo[0]['cust_image'],
			'width' => 998,
			'height' => 387,
			'salt' => time(),
		);
		require_once $r_path.'scripts/cart/encrypt.php';
		$data = base64_encode(encrypt_data(serialize($data)));
		echo '<img src="/images/image.php?data='.$data.'"  />'; } else { ?>
    <img src="/images/spacer.gif" width="998" height="387" />
    <? } ?>
  </div>
  <div id="Content">
    <? include $r_path.'includes/_PhotoNavigation.php'; ?>
    <div id="TextLong">
      <? if($email_sent === false){ ?>
      <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>" name="Contact Form" id="Contact_Form" enctype="multipart/form-data">
        <h1>Contact Us </h1>
        <p>You can contact us at <a href="mailto:<? echo $getInfo[0]['cust_email'];?>"><? echo $getInfo[0]['cust_email'];?></a> or use the form below to send us an e-mail. </p>
        <label for="Name">Name: *</label>
        <div class="CstmInput">
          <input type="text" name="Name" id="Name" value="<? echo $Name; ?>" tabindex="<? $tab=1; echo $tab++;?>" />
        </div>
        <label for="Phone_Number">Phone Number:</label>
        <div class="CstmInput">
          <input type="text" name="Phone Number" id="Phone_Number" value="<? echo $Phone; ?>" tabindex="<? echo $tab++;?>" />
        </div>
        <label for="Email">E-mail Address: *</label>
        <div class="CstmInput">
          <input type="text" name="Email" id="Email" value="<? echo $Email; ?>" tabindex="<? echo $tab++;?>" />
        </div>
        <label for="Comments">Comments / Questions: *</label>
        <div class="CstmTextArea">
          <textarea name="Comments" id="Comments" value="<? echo $Comm; ?>" tabindex="<? echo $tab++;?>"><? echo $Comm; ?></textarea>
        </div>
        <input type="hidden" name="Controller" id="Controller" value="true" />
        <input type="text" name="Field" id="Field" value="<? echo base64_encode(md5(time())); ?>" style="display:none;" />
        <input type="hidden" value="<? echo $FID; ?>" name="FID" id="FID" />
        <input type="hidden" name="IP_Address" id="IP_Address" value="<? echo $_SESSION['IP_Address']; ?>" />
        <input type="text" name="Form Id" id="Form_Id" value="" class="FormCheckField" />
        <input type="hidden" name="Time" id="Time" value="<? echo time(); ?>" />
        <input type="hidden" name="Refer" id="Refer" value="<? echo $_SERVER['HTTP_REFERER']; ?>" />
        <br clear="all" />
        <div id="CstmSend" style="float:right; margin-right:160px;">
          <input type="submit" name="Submit" id="Submit" value="Submit" onclick="MM_validateForm('Name','','R','Email','','RisEmail','Comments','','R'); return document.MM_returnValue;" />
        </div>
        <br clear="all" />
      </form>
      <? } else { ?>
      <h1>Thank you</h1>
      <p>Thank you for contacting us at <? echo $getInfo[0]['cust_cname']; ?> if you had any questions, a representative will answer them as soon as they can. </p>
      <p>You can contact us at <a href="mailto:<? echo $getInfo[0]['cust_email'];?>"><? echo $getInfo[0]['cust_email'];?></a> or use the form below to send us an e-mail. </p>
      <? } ?>
    </div>
    <br clear="all" />
  </div>
</div>
<? include $r_path.'includes/_PhotoFooter.php'; ?>
</body>
</html>
