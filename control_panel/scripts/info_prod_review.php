<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <?php if($is_enabled){  ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div><img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Review Information</p></th>
  </tr><?php } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Review Information</p></th>
  </tr>
  <?php } ?>
  <tr>
    <td width="20%"><strong>Name:</strong></td>
    <td width="80%"><?php if(!$is_enabled){
		echo $RName;
	} else { ?>
      <input type="text" name="Name" id="Name" maxlength="75" value="<?php echo $RName; ?>">
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Rating:</strong></td>
    <td><?php if(!$is_enabled){ 
	echo $Rating;
	} else { ?>
      <input type="text" name="Rating" id="Rating" value="<?php echo $Rating; ?>" size="6" />
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Url:</strong></td>
    <td><?php if(!$is_enabled){
		echo $RURL;
	} else { ?>
      <input type="text" name="URL" id="URL" maxlength="75" value="<?php echo $RURL; ?>" />
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Image:</strong></td>
    <td><?php if($is_enabled){ ?>
      <input type="file" name="Image" id="Image">
      <input type="hidden" name="Image_val" id="Image_val" value="<?php echo $Imagev;?>">
      <?php 
	  }
	  if($Image != ""){?>
      &nbsp;<a href="<?php echo $Prod_Review_Folder; ?>/<?php echo $Image;?>" target="_blank">View</a>
      <?php } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" />
      Remove Image
      <?php } ?></td>
  </tr><tr>
    <td colspan="2" style="padding:10px;<?php if(!$is_enabled){ ?> height:300px;<?php } ?>" valign="top"><?php if(!$is_enabled){
		echo $RDesc;
	} else {
		$oFCKeditor = new FCKeditor('Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $RDesc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	}
		?></td>
  </tr>
</table>
