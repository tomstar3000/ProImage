<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++) $r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Home Page Information</h2>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td><strong>Top Welcome Image: </strong></td>
  <td><input type="file" name="Image" id="Image">
   <input type="hidden" name="Image_val" id="Image_val" value="<? echo $Imagev;?>">
   <? if($Imagev != ""){?>
   &nbsp;<a href="<? echo "/photographers/".$row_get_id['cust_handle']; ?>/<? echo $Imagev;?>" target="_blank">View</a>
   <? } ?>
   998 x 387 Pixel Dimensions</td>
 </tr>
 <tr>
  <td colspan="2"><strong>Page Informaiton: </strong></td>
 </tr>
 <tr>
  <td colspan="2" style="padding:10px; height:300px;" valign="top"><? $oFCKeditor = new FCKeditor('Page_Text');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $Desc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->ToolbarSet = 'Simple';
		$oFCKeditor->Create();
		$output = $oFCKeditor->CreateHtml(); ?></td>
 </tr>
</table>
