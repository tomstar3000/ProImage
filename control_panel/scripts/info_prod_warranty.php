<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++) $r_path .= "../";
}
include $r_path.'security.php';
require_once($r_path.'FCKeditor/fckeditor.php'); ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <th colspan="4" id="Form_Header"><img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>Warranty Information</p></th>
 </tr>
 <tr>
  <td><strong>Warranty Length:</strong></td>
  <td><input type="text" name="Warranty Length" id="Warranty_Length" value="<? echo $LWarnt; ?>" /> 
  (years)</td>
 </tr>
 <tr>
  <td colspan="2"><strong>Warranty:</strong></td>
 </tr>
 <tr>
  <td colspan="2" style="padding:10px; height:300px;" valign="top"><? 
		$oFCKeditor = new FCKeditor('Warranty');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $Warnt;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create();
		$output = $oFCKeditor->CreateHtml(); ?></td>
 </tr>
 
 <tr>
  <td><strong>Warranty Length:</strong></td>
  <td><input type="text" name="Warranty Length" id="Warranty_Length" value="<? echo $LMWarnt; ?>" /> 
  (years)</td>
 </tr>
 <tr>
  <td colspan="2"><strong>Manufacture's Warranty:</strong></td>
 </tr>
 <tr>
  <td colspan="2" style="padding:10px; height:300px;" valign="top"><?
		$oFCKeditor = new FCKeditor('Manufacture_Warranty');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $MWarnt;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml(); ?>
   &nbsp;</td>
 </tr>
</table>
