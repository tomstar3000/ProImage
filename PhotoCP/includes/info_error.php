<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
define('Allow Scripts',true);
require_once($r_path.'scripts/fnct_clean_entry.php');
$sent = false;
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save"){
	if (strtoupper(substr(PHP_OS,0,3))=='WIN') $eol="\r\n"; 
	else if (strtoupper(substr(PHP_OS,0,3))=='MAC') $eol="\r"; 
	else $eol="\n"; 
	require_once($r_path.'scripts/fnct_send_email.php');
	$Name = sanatizeentry($_POST['Name'],false);
	$Event = sanatizeentry($_POST['Event'],false);
	$Code = sanatizeentry($_POST['Code'],false);
	$Phone = sanatizeentry($_POST['Phone_Number'],false);
	$Email = sanatizeentry($_POST['Email'],false);
	$Comm = sanatizeentry($_POST['Comments'],false);
	
	$reciever = "support@proimagesoftware.com, development@proimagesoftware.com";
	//$reciever = "development@proimagesoftware.com";
	if($path[1] == "Request"){
		$subject = "Photographer Request";
	} else {
		$subject = "Photographer Technical Issue";
	}
	
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
$required_string = "'Name','','R','Email','','RisEmail','Event','','R','Comments','','R'";
if(isset($required_string)){
	$onclick = '"MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; this.disabled=true; this.form.submit();}"';
} else {
	$onclick = '"document.getElementById(\'Controller\').value = \'Save\'; this.disabled=true; this.form.submit();"';
}
?>

<h1 id="HdrType2" class="CreateEvnt">
  <div><? if($path[1] == "Request") echo "Request a Feature"; else echo "Technical Issues or Problems"; ?></div>
</h1>
<? if($sent === false){ ?>
<div id="RecordTable" class="Green">
  <div id="Top"></div>
  <div id="Records">
    <p>You can contact us at <a href="mailto:support@proimagesoftware.com">support@proimagesoftware.com</a> or use the form below to send
      us an e-mail. </p>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<div id="RecordTable" class="Green">
  <div id="Top"></div>
  <div id="Records">
    <p>
      <label for="Name" class="CstmFrmElmntLabel">Name *</label>
      <input type="text" name="Name" id="Name" value="<? echo $Name; ?>" tabindex="<? $tab=1; echo $tab++;?>" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Your Name'; return true;" onmouseout="window.status=''; return true;" title="Your Name" class="CstmFrmElmntInput" />
      <br />
      <label for="Event" class="CstmFrmElmntLabel">Event Name *</label>
      <input type="text" name="Event" id="Event" value="<? echo $Event; ?>" tabindex="<? echo $tab++;?>" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Event Name'; return true;" onmouseout="window.status=''; return true;" title="Event Name" class="CstmFrmElmntInput" />
      <br />
      <label for="Code" class="CstmFrmElmntLabel">Event Code</label>
      <input type="text" name="Code" id="Code" value="<? echo $Code; ?>" tabindex="<? echo $tab++;?>" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Event Code'; return true;" onmouseout="window.status=''; return true;" title="Event Code" class="CstmFrmElmntInput" />
      <br />
      <label for="Phone_Number" class="CstmFrmElmntLabel">Phone Number</label>
      <input type="text" name="Phone Number" id="Phone_Number" value="<? echo $Phone; ?>" tabindex="<? echo $tab++;?>" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Phone Number'; return true;" onmouseout="window.status=''; return true;" title="Phone Number" class="CstmFrmElmntInput" />
      <br />
      <label for="Email" class="CstmFrmElmntLabel">E-mail Address *</label>
      <input type="text" name="Email" id="Email" value="<? echo $Email; ?>" tabindex="<? echo $tab++;?>" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='E-mail Address'; return true;" onmouseout="window.status=''; return true;" title="E-mail Address" class="CstmFrmElmntInput" />
      <br />
      <? if($path[1] == "Request"){ ?>
      <label for="Comments" class="CstmFrmElmntLabel">Please Describe in detail what you would like to see or added to the Pro Image Software's Control Panel *</label>
      <? } else { ?>
      <label for="Comments" class="CstmFrmElmntLabel">Please Describe in detail the issue you are having, if you received an error message please paste the message
      in the box below *</label>
      <? } ?>
    <div class="CstmFrmElmntTextField" style="margin-left:15px;">
      <textarea name="Comments" id="Comments" onfocus="javascript:this.parentNode.className='CstmFrmElmntTextFieldNavSel';" onblur="javascript:this.parentNode.className='CstmFrmElmntTextField';" onmouseover="window.status='Description'; return true;" onmouseout="window.status=''; return true;" title="Description"><? echo $Comm; ?></textarea>
    </div>
    <br />
    <input type="button" name="btnSave" id="btnSave" value="Send E-mail" alt="Send E-mail" onmouseup=<? echo $onclick; ?> />
    </p>
  </div>
  <div id="Bottom"></div>
</div>
<? } else { ?>
<div id="RecordTable" class="Green">
  <div id="Top"></div>
  <div id="Records">
    <p>Your E-mail has been sent. </p>
    <p>Thank you for contacting us. Quality is a prime issue and every little error that you have means a lot to us.</p>
    <p>You can contact us at <a href="mailto:support@proimagesoftware.com">support@proimagesoftware.com</a> or use the form below to send
      us an e-mail. </p>
    <br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<? } ?>
