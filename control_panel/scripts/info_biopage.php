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
 <h2>Bio Page Information</h2>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td><strong>Bio Image: </strong></td>
  <td><input type="file" name="Icon" id="Icon">
   <input type="hidden" name="Icon_val" id="Icon_val" value="<? echo $Iconv;?>">
   <? if($Iconv != ""){?>
   &nbsp;<a href="<? echo "/photographers/".$row_get_id['cust_handle']; ?>/<? echo $Iconv;?>" target="_blank">View</a>
   <? } ?>
   135 x 135 Pixel Dimensions</td>
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
