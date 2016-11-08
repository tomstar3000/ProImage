<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php'; ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <th colspan="2" id="Form_Header"> <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>Home Page Information </p></th>
 </tr>
 <tr>
  <td width="20%"><strong>Header: </strong></td>
  <td width="80%"><input type="text" name="Header 1" id="Header_1" maxlength="25" value="<? echo $PHead; ?>" />
   &nbsp;Use an image name if the header is an image. </td>
 </tr>
 <tr>
  <td><strong>2nd Header: </strong></td>
  <td><input type="text" name="Header 2" id="Header_2" maxlength="25" value="<? echo $PHead_2; ?>" />
   &nbsp;</td>
 </tr>
 <tr>
  <td width="20%"><strong>Tag Line : </strong></td>
  <td width="80%"><input type="text" name="Tag Line 1" id="Tag_Line_1" maxlength="25" value="<? echo $PTag; ?>" />
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>2nd Tag Line: </strong></td>
  <td><input type="text" name="Tag Line 2" id="Tag_Line_2" maxlength="25" value="<? echo $PTag_2; ?>" />
   &nbsp;</td>
 </tr>
 <tr>
  <td colspan="2" style="padding:10px;" valign="top"><p>Key: </p>
   <ul>
    <li>[links] - Creates a bulleted list of links that are set as sub-pages to this page.</li>
    <li>[links (page name)] - Creates a link to a specific page; use &quot;,&quot; to name Main Page Name then the Page Name (i.e.
     [links Home,About_Us])</li>
    <li>[links (page name)-(title)] - Use the &quot;-&quot; to add a title to the link. (i.e. [links Home,About_Us-About Us])</li>
   </ul></td>
 </tr>
 <tr>
  <td colspan="2" style="padding:10px; height:300px;" valign="top"><?
		$oFCKeditor = new FCKeditor('Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $PDesc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
		?>
   &nbsp;</td>
 </tr>
 <tr>
  <td colspan="2" align="center"><input type="button" name="Reset" id="Reset" value="Reset Information"  onmouseup="document.getElementById('Controller').value = 'Reset'; this.disabled=true; this.form.submit();" /></td>
 </tr>
</table>
