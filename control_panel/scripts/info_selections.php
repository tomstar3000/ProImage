<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <?php if($is_enabled){  ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Selection Information</p></th>
  </tr>
  <?php } else { ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,3)); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,2)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Selection Information</p></th>
  </tr>
  <?php } ?>
  <tr>
    <td width="20%"><strong>Name:</strong></td>
    <td width="80%"><?php if(!$is_enabled){	echo $SName; } else { ?>
      <input type="text" name="Name" id="Name" maxlength="75" value="<?php echo $SName; ?>">
      <?php } ?>
      <input type="hidden" name="Selection_Id" id="Selection_Id" value="<?php echo $SId;?>" />
      &nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="padding:10px;<?php if($is_enabled){ ?> height:300px;<?php } ?>" valign="top">
	<?php if(!$is_enabled){	echo $SDesc; } else {
		$oFCKeditor = new FCKeditor('Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $SDesc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	} ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Image:</strong></td>
    <td><?php if($is_enabled){ ?>
      <input type="file" name="Image" id="Image">
      <input type="hidden" name="Image_val" id="Image_val" value="<?php echo $Imagev;?>">
      <?php }
	  if($Image != ""){?>
      &nbsp;<a href="<?php echo $Select_Folder; ?>/<?php echo $Image;?>" target="_blank">View</a>
      <?php } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" />
      Remove Image
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Selection Page: </strong></td>
    <td><?php if($_GET['cont'] != "add" || $_GET['cont'] != "edit"){ 
	  	echo $SPage;
	 } else { ?>
      <input type="text" name="Selection Page" id="Selection_Page" value="<?php echo $SPage; ?>" size="3" />
	  <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><?php if($_GET['cont'] != "add" || $_GET['cont'] != "edit"){ 
	  	echo  "Select Multiple Items: ".(($SMult == "y") ? "Yes" : "No");
	 } else { ?>
      <input type="checkbox" name="Select Multiple Items" id="Select_Multiple_Items" value="y"<?php if($SMult == "y"){ print(" checked=\"checked\"");}?> />
      Select Multiple Items
      <?php } ?>
      &nbsp;</td>
  </tr>
</table>
