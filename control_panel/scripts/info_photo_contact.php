<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
	$r_path = "";
	for($n=0;$n<$count;$n++) $r_path .= "../";
}
define('Allow Scripts',true);
require_once($r_path.'scripts/fnct_clean_entry.php');
$sent = false;
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save"){
	if (strtoupper(substr(PHP_OS,0,3)=='WIN')) { 
		$eol="\r\n"; 
	} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) { 
		$eol="\r"; 
	} else { 
		$eol="\n"; 
	} 
	require_once($r_path.'scripts/fnct_send_email.php');
	$Name = sanatizeentry($_POST['Name'],false);
	$Event = sanatizeentry($_POST['Event'],false);
	$Code = sanatizeentry($_POST['Code'],false);
	$Phone = sanatizeentry($_POST['Phone_Number'],false);
	$Email = sanatizeentry($_POST['Email'],false);
	$Comm = sanatizeentry($_POST['Comments'],false);
	
	$reciever = "info@photoexpresspro.com";
	//$reciever = "development@proimagesoftware.com";
	$subject = "Photographer On-line Contact Form";
	
	$msg = "Name: ".$Name.$eol;
	$msg .= "Event: ".$Event." ".$Code.$eol;
	$msg .= "Phone Number: ".$Phone.$eol;
	$msg .= "E-mail: ".$Email.$eol.$eol;
	$msg .= "Comments".$eol;
	$msg .= "------------------------".$eol;
	$msg .= $Comm;
	
	send_email($Email, $reciever, $subject, $msg, false, false, false, false);
	$sent = true;
}
$tab=1; 
$required_string = "'Name','','R','Email','','RisEmail','Event','','R','Comments','','R'";
if(isset($required_string)){
	$onclick = '"MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; this.disabled=true; this.form.submit();}"';
} else {
	$onclick = '"document.getElementById(\'Controller\').value = \'Save\'; this.disabled=true; this.form.submit();"';
}
?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Please Send us your Comments or Suggestions </h2>
</div>
<? if($sent === false){ ?>
<div>
 <p>You can contact us at <a href="mailto:info@photoexpresspro.com">info@photoexpresspro.com</a> or use the form below to send
  us an e-mail. </p>
 <p class="no_indent">Name: *<br />
  <input type="text" name="Name" id="Name" value="<? echo $Name; ?>" tabindex="<? echo $tab++;?>" />
 </p>
 <p class="no_indent">Event Name: *<br />
  <input type="text" name="Event" id="Event" value="<? echo $Event; ?>" tabindex="<? echo $tab++;?>" />
 </p>
 <p class="no_indent">Event Code:<br />
  <input type="text" name="Code" id="Code" value="<? echo $Code; ?>" tabindex="<? echo $tab++;?>" />
 </p>
 <p class="no_indent">Phone Number:<br />
  <input type="text" name="Phone Number" id="Phone_Number" value="<? echo $Phone; ?>" tabindex="<? echo $tab++;?>" />
 </p>
 <p class="no_indent">E-mail Address: *<br />
  <input type="text" name="Email" id="Email" value="<? echo $Email; ?>" tabindex="<? echo $tab++;?>" />
 </p>
 <p class="no_indent">Comments / Suggestions: * <br />
  <textarea name="Comments" id="Comments" value="<? echo $Comm; ?>" tabindex="<? echo $tab++;?>" style="width:500px; height:75px;"><? echo $Comm; ?></textarea>
 </p>
 <p class="no_indent">
  <input type="button" name="btnSave" id="btnSave" value="Send E-mail" alt="Send E-mail" onmouseup=<? echo $onclick; ?> />
  <br clear="all" />
 </p>
</div>
<? } else { ?>
<div>
 <p>Your E-mail has been sent. </p>
 <p>Thank you for your comments and suggestions. Any questions that you have will be answered as soon as possible. </p>
 <p>You can contact us at <a href="mailto:info@photoexpresspro.com">info@photoexpresspro.com</a> or use the form below to send
  us an e-mail. </p>
</div>
<? } ?>
