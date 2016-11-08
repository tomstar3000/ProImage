<?
function send_email($senderaddress, $emailaddress, $emailsubject, $emailtext, $html = false, $body_url = false, $foot_url = false, $BCC = false, $Files = false){
	
	# Is the OS Windows or Mac or Linux 
	if (strtoupper(substr(PHP_OS,0,3)=='WIN')) { 
	  $eol="\r\n"; 
	} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) { 
	  $eol="\r"; 
	} else { 
	  $eol="\n"; 
	} 
	# Today's Date
	$today = date("m-d-Y");
	$headers = "";
	# Server Address
	$serveraddress = $_SERVER['SERVER_NAME'];
	# Common Headers 
	$headers .= 'From: '.$senderaddress.$eol; 
	$headers .= 'Reply-To: '.$senderaddress.$eol; 
	
	if($BCC  === true){
		//$headers .= 'To: '.$senderaddress.$eol; 
		$headers .= 'Bcc: '.$emailaddress.$eol;
		$emailaddress = $senderaddress;
	} else {
		//$headers .= 'To: '.$emailaddress.$eol;
	}
	$msg = ""; 
	if($html == false){
		$switch_to = array(">","<","©","™","®");
		$switch_from = array("&gt;","&lt;","&copy;","&trade;","&reg;");
		$emailtext = str_replace($switch_from,$switch_to,$emailtext);
		$emailtext = html_entity_decode($emailtext, ENT_QUOTES);
		$msg .= $emailtext.$eol.$eol;  
	} else {
		if($body_url != false){
			ob_start(); 
			require($body_url);											// Header of E-mail for HTML
			$body = ob_get_contents();
			require($foot_url);											// Footer of E-mail for HTML
			$footer = ob_get_contents();
			ob_end_clean();
			$msg .= $body.$eol.$eol;
			$msg .= $emailtext.$eol.$eol; 
			$msg .= $footer.$eol.$eol;
		} else {
			$msg .= $emailtext.$eol.$eol; 
		}
	}
	
	# Attachment
	# File for Attachment
	if($Files != false){
		$file = $Files; // use relative path OR ELSE big headaches. $letter is my file for attaching.
		$fname = basename($file);
		$ftype = filetype($file);
		$handle = fopen($file, 'rb'); 
		$content = fread($handle, filesize($file)); 
		$content = chunk_split(base64_encode($content));		// Encode The Data For Transition using base64_encode(); 
		fclose($handle);
		
		$boundary = strtoupper(md5(uniqid(time()))); 
		$headers .= 'Date: '.date("D, d M Y H:m:s Z").$eol;
		$headers .= 'Content-Transfer-Encoding: quoted-printable'.$eol;
		$headers .= 'X-Sender: '.$senderaddress.$eol; 
		$headers .= 'X-Mailer: PHP v'.phpversion().$eol;				// These two to help avoid spam-filters 
		$headers .= 'X-Priority: 3'.$eol;												// 1 = Urgent, 3 = Normal
		$headers .= 'Return-Path: '.$senderaddress.$eol;				// these two to set reply address 
		$headers .= 'MIME-Version: 1.0'.$eol;
		$headers .= 'Content-Type: multipart/mixed;	boundary="----=A'.$boundary.'"';
		$msg2 = 'This is a multi-part message in MIME format.'.$eol.$eol; 
		$msg2 .= '------=A'.$boundary.$eol;
		$msg2 .= 'Content-Type: multipart/alternative; boundary="----=B'.$boundary.'"'.$eol.$eol;
		$msg2 .= '------=B'.$boundary.$eol;
		if($html == false){
			$msg2 .= 'Content-Type: text/plain; charset="iso-8859-1"'.$eol;
		} else {
			$msg2 .= 'Content-Type: text/html; charset="iso-8859-1"'.$eol;
		}
		$msg2 .= 'Content-Transfer-Encoding: 7bit'.$eol.$eol;
		$msg = $msg2.$msg.$eol.$eol;
		$msg .= '------=B'.$boundary.'--'.$eol;
		$msg .= '------=A'.$boundary.$eol;
		$msg .= 'Content-Type: application/'.$ftype.'; name="'.$fname .'"'.$eol;
		$msg .= 'Content-Disposition: attachment; filename="'.$fname.'"'.$eol;
		$msg .= 'Content-Transfer-Encoding: base64'.$eol.$eol;
		$msg .= $content.$eol.$eol;
		$msg .= '------=A'.$boundary.'--'.$eol;
	} else {
		if($html == false){
			$headers .= 'Content-Type: text/plain; charset="iso-8859-1"'.$eol;
		} else {
			$headers .= 'Content-Type: text/html; charset="iso-8859-1"'.$eol;
			$msg = str_replace('="','=3D"',$msg);
		}
		$headers .= 'Content-Transfer-Encoding: quoted-printable'.$eol;
		$headers .= 'X-Sender: '.$senderaddress.$eol; 
		$headers .= 'X-Mailer: PHP v'.phpversion().$eol;				// These two to help avoid spam-filters 
		$headers .= 'X-Priority: 3'.$eol;												// 1 = Urgent, 3 = Normal
		$headers .= 'Return-Path: '.$senderaddress.$eol;				// these two to set reply address 
	}
	
	# SEND THE EMAIL 
	ini_set(sendmail_from,$senderaddress);						// the INI lines are to force the From Address to be used ! 
	mail($emailaddress, $emailsubject, $msg, $headers); 
	ini_restore(sendmail_from); 
}
?>