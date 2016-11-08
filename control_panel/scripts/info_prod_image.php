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
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div><img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Image Information</p></th>
  </tr>
  <tr>
    <td width="20%"><strong>Name:</strong></td>
    <td width="80%"><?php if(!$is_enabled){
		echo $IName_1;
	} else { ?>
      <input type="text" name="Name_1" id="Name_1" maxlength="75" value="<?php echo $IName_1; ?>">
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Alt Tag:</strong></td>
    <td><?php if(!$is_enabled){
		echo $IAlt_1;
	} else { ?>
      <input type="text" name="Alt_1" id="Alt_1" maxlength="75" value="<?php echo $IAlt_1; ?>" />
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Image:</strong></td>
    <td><?php if($is_enabled){ ?>
      <input type="file" name="Image_1" id="Image_1">
      <input type="hidden" name="Image_val_1" id="Image_val_1" value="<?php echo $Imagev_1;?>">
      <?php 
	  }
	  if($Image_1 != ""){?>
      &nbsp;<a href="<?php echo $Prod_Review_Folder; ?>/<?php echo $Image_1;?>" target="_blank">View</a>
      <?php } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Image_1" id="Remove_Image_1" value="true" />
      Remove Image
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Thumbnail:</strong></td>
    <td><?php if($is_enabled){ ?>
      <input type="file" name="Icon_1" id="Icon_1">
      <input type="hidden" name="Icon_val_1" id="Icon_val_1" value="<?php echo $Iconv_1;?>">
      <?php 
	  }
	  if($Iconv_1 != ""){?>
      &nbsp;<a href="<?php echo $Prod_Folder; ?>/<?php echo $Iconv_1;?>" target="_blank">View</a>
      <?php } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Icon_1" id="Remove_Icon_1" value="true" />
      Remove Icon
      <?php } ?></td>
  </tr>
  <tr>
    <td colspan="2"><hr size="1" /></td>
  </tr>
  <tr>
    <td><strong>Name:</strong></td>
    <td><?php if(!$is_enabled){
		echo $IName_2;
	} else { ?>
      <input type="text" name="Name_2" id="Name_2" maxlength="75" value="<?php echo $IName_2; ?>">
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Alt Tag:</strong></td>
    <td><?php if(!$is_enabled){
		echo $IAlt_2;
	} else { ?>
      <input type="text" name="Alt_2" id="Alt_2" maxlength="75" value="<?php echo $IAlt_2; ?>" />
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Image:</strong></td>
    <td><?php if($is_enabled){ ?>
      <input type="file" name="Image_2" id="Image_2">
      <input type="hidden" name="Image_val_2" id="Image_val_2" value="<?php echo $Imagev_2;?>">
      <?php 
	  }
	  if($Image_2 != ""){?>
      &nbsp;<a href="<?php echo $Prod_Review_Folder; ?>/<?php echo $Image_2;?>" target="_blank">View</a>
      <?php } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Image_2" id="Remove_Image_2" value="true" />
      Remove Image
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Thumbnail:</strong></td>
    <td><?php if($is_enabled){ ?>
      <input type="file" name="Icon_2" id="Icon_2">
      <input type="hidden" name="Icon_val_2" id="Icon_val_2" value="<?php echo $Iconv_2;?>">
      <?php 
	  }
	  if($Iconv_2 != ""){?>
      &nbsp;<a href="<?php echo $Prod_Folder; ?>/<?php echo $Iconv_2;?>" target="_blank">View</a>
      <?php } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Icon_2" id="Remove_Icon_2" value="true" />
      Remove Icon
      <?php } ?></td>
  </tr>
  <tr>
    <td colspan="2"><hr size="1" /></td>
  </tr>
  <tr>
    <td><strong>Name:</strong></td>
    <td><?php if(!$is_enabled){
		echo $IName_3;
	} else { ?>
      <input type="text" name="Name_3" id="Name_3" maxlength="75" value="<?php echo $IName_3; ?>">
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Alt Tag:</strong></td>
    <td><?php if(!$is_enabled){
		echo $IAlt_3;
	} else { ?>
      <input type="text" name="Alt_3" id="Alt_3" maxlength="75" value="<?php echo $IAlt_3; ?>" />
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Image:</strong></td>
    <td><?php if($is_enabled){ ?>
      <input type="file" name="Image_3" id="Image_3">
      <input type="hidden" name="Image_val_3" id="Image_val_3" value="<?php echo $Imagev_1;?>">
      <?php 
	  }
	  if($Image_3 != ""){?>
      &nbsp;<a href="<?php echo $Prod_Review_Folder; ?>/<?php echo $Image_3;?>" target="_blank">View</a>
      <?php } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Image_3" id="Remove_Image_3" value="true" />
      Remove Image
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Thumbnail:</strong></td>
    <td><?php if($is_enabled){ ?>
      <input type="file" name="Icon_3" id="Icon_3">
      <input type="hidden" name="Icon_val_3" id="Icon_val_3" value="<?php echo $Iconv_3;?>">
      <?php 
	  }
	  if($Iconv_3 != ""){?>
      &nbsp;<a href="<?php echo $Prod_Folder; ?>/<?php echo $Iconv_3;?>" target="_blank">View</a>
      <?php } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Icon_3" id="Remove_Icon_3" value="true" />
      Remove Icon
      <?php } ?></td>
  </tr>
  <tr>
    <td colspan="2"><hr size="1" /></td>
  </tr>
  <tr>
    <td><strong>Name:</strong></td>
    <td><?php if(!$is_enabled){
		echo $IName_4;
	} else { ?>
      <input type="text" name="Name_4" id="Name_4" maxlength="75" value="<?php echo $IName_4; ?>">
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Alt Tag:</strong></td>
    <td><?php if(!$is_enabled){
		echo $IAlt_4;
	} else { ?>
      <input type="text" name="Alt_4" id="Alt_4" maxlength="75" value="<?php echo $IAlt_4; ?>" />
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Image:</strong></td>
    <td><?php if($is_enabled){ ?>
      <input type="file" name="Image_4" id="Image_4">
      <input type="hidden" name="Image_val_4" id="Image_val_4" value="<?php echo $Imagev_4;?>">
      <?php 
	  }
	  if($Image_4 != ""){?>
      &nbsp;<a href="<?php echo $Prod_Review_Folder; ?>/<?php echo $Image_4;?>" target="_blank">View</a>
      <?php } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Image_4" id="Remove_Image_4" value="true" />
      Remove Image
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Thumbnail:</strong></td>
    <td><?php if($is_enabled){ ?>
      <input type="file" name="Icon_4" id="Icon_4">
      <input type="hidden" name="Icon_val_4" id="Icon_val_4" value="<?php echo $Iconv_4;?>">
      <?php 
	  }
	  if($Iconv_4 != ""){?>
      &nbsp;<a href="<?php echo $Prod_Folder; ?>/<?php echo $Iconv_4;?>" target="_blank">View</a>
      <?php } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Icon_4" id="Remove_Icon_4" value="true" />
      Remove Icon
      <?php } ?></td>
  </tr>
  <tr>
    <td colspan="2"><hr size="1" /></td>
  </tr>
  <tr>
    <td><strong>Name:</strong></td>
    <td><?php if(!$is_enabled){
		echo $IName_5;
	} else { ?>
      <input type="text" name="Name_5" id="Name_5" maxlength="75" value="<?php echo $IName_5; ?>">
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Alt Tag:</strong></td>
    <td><?php if(!$is_enabled){
		echo $IAlt_5;
	} else { ?>
      <input type="text" name="Alt_5" id="Alt_5" maxlength="75" value="<?php echo $IAlt_5; ?>" />
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Image:</strong></td>
    <td><?php if($is_enabled){ ?>
      <input type="file" name="Image_5" id="Image_5">
      <input type="hidden" name="Image_val_5" id="Image_val_5" value="<?php echo $Imagev_5;?>">
      <?php 
	  }
	  if($Image_5 != ""){?>
      &nbsp;<a href="<?php echo $Prod_Review_Folder; ?>/<?php echo $Image_5;?>" target="_blank">View</a>
      <?php } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Image_5" id="Remove_Image_5" value="true" />
      Remove Image
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Thumbnail:</strong></td>
    <td><?php if($is_enabled){ ?>
      <input type="file" name="Icon_5" id="Icon_5">
      <input type="hidden" name="Icon_val_5" id="Icon_val_5" value="<?php echo $Iconv_5;?>">
      <?php 
	  }
	  if($Iconv_5 != ""){?>
      &nbsp;<a href="<?php echo $Prod_Folder; ?>/<?php echo $Iconv_5;?>" target="_blank">View</a>
      <?php } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Icon_5" id="Remove_Icon_5" value="true" />
      Remove Icon
      <?php } ?></td>
  </tr>
</table>
