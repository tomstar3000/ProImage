<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
if((count($path)<=3 && $cont == "view") || (count($path)>3)){
	$is_enabled = false;
} else {
	$is_enabled = true;
} ?>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
  <h2>Guestbook E-mailer </h2>
  <p id="Back"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');">Back</a></p>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<? if($Emailsent == true)echo "<tr><td style=\"background-color:#FFFFFF; color:#CC0000; padding:5px;\">Your E-mail has been sent.</td></tr>"; ?>
	<tr>
	  <td>Image: <input type="file" name="Image" id="Image" /> Image will display 150 pixels wide in the top left corner</td>
	</tr>
  <tr>
    <td>E-mail Text: </td>
  </tr>
  <tr>
    <td style="padding:10px; height:500px;" valign="top">
	<? 	$oFCKeditor = new FCKeditor('Email_Text');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $Desc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '500';
		$oFCKeditor->ToolbarSet = 'Very_Basic';
		$oFCKeditor->Create();
		$output = $oFCKeditor->CreateHtml(); ?></td>
  </tr>
</table>

          <input type="submit" name="btnSend" id="btnSend" value="Send E-mail" alt="Save Information" onmouseup="document.getElementById('Controller').value = 'Send'; this.disabled=true; this.form.submit();" />