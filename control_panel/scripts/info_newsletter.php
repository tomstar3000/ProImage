<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <th colspan="4" id="Form_Header"><div id="Add"></div>
   <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>Newsletter</p></th>
 </tr>
 <tr>
  <td>
     <table width="100%">
      <tr>
       <td>Testing E-mail: </td>
       <td><input type="text" name="Testing Email" id="Testing_Email" /></td>
      </tr>
      <tr>
       <td>Load Mailing List: </td>
       <td><input type="file" name="Mailing_List" id="Mailing_List" />
        (CSV file) </td>
      </tr>
      <tr>
       <td>Sending E-mail </td>
       <td><input type="text" name="Email" id="Email" value="<? echo $Email; ?>" /></td>
      </tr>
      <tr>
       <td>Title:</td>
       <td><input type="text" name="Title" id="Title" value="<? echo $Title; ?>" /></td>
      </tr>
      <tr>
       <td colspan="2" valign="top" style="padding:10px; height:500px;"><? 
		$oFCKeditor = new FCKeditor('Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $Desc;
    $oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '500'; 
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
		?>
        &nbsp;</td>
      </tr>
     </table>
     <input type="submit" id="Test_E-mail" name="Test E-mail" value="Test E-mail" onclick="document.getElementById('Controller').value = 'test'" />
     <input type="submit" id="Send_E-mail" name="Send E-mail" value="Send E-mail" onclick="document.getElementById('Controller').value = 'send'" />
     <input type="submit" id="Save" name="Save" value="Save w/o Sending" onclick="document.getElementById('Controller').value = 'save'" /></td>
 </tr>
</table>
