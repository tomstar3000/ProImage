<?php
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
  <h2>Image Review </h2>
</div>
<br clear="all" />
<?php 
$a = 0;
foreach($IId as $k => $v){ 
	//photographers/Chad_Serpan/di0yL/Group_1/11537f9143/
	//01234567890123456789012345678901234567890123456789
	$index = strrpos($IFolder[$k], "/");
	$Folder = substr($IFolder[$k],0,$index);	
	$index = strrpos($Folder, "/");
	$Folder = substr($Folder,0,$index);
	?>
<div style="float:left; margin:5px;">
  <div style="float:left"> <img src="/<?php echo $Folder."/Icon/".$IFile[$k];?>" width="150" hspace="2" vspace="2"> </div>
  <div style="float:left">
    <div >
      <input type="text" name="Name[]" id="Name[]" value="<?php echo $IName[$k]; ?>" style="width:195px;">
			<input type="hidden" name="Id[]" id="Id[]" value="<?php echo $IId[$k]; ?>">
			<input type="hidden" name="File[]" id="File[]" value="<?php echo $IFile[$k]; ?>">
			<input type="hidden" name="Folder[]" id="Folder[]" value="<?php echo $IFolder[$k]; ?>">
    </div>
    <div>
      <?php 
		$oFCKeditor = new FCKeditor('Description_'.$k);
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $IDesc[$k];
		$oFCKeditor->Width = '200';
		$oFCKeditor->Height = '100';
		$oFCKeditor->ToolbarSet = 'Very_Basic';
		$oFCKeditor->Create();
		$output = $oFCKeditor->CreateHtml(); ?>
    </div>
  </div>
</div>
<?php 
$a++;
	if($a == 2){
		echo '<br clear="all" />';
		$a = 0;
	}
} ?>
<br clear="all" />
<div id="Save_Btn">
  <input type="submit" name="btnSave" id="btnSave" value="Save & Complete" alt="Save & Complete" onmouseup="document.getElementById('Controller').value = 'SaveComplete'; this.disabled=true; this.form.submit();" />
	  <input type="submit" name="btnSave" id="btnSave" value="Save & Load More" alt="Save & Load More" onmouseup="document.getElementById('Controller').value = 'SaveLoad'; this.disabled=true; this.form.submit();" />
</div>
