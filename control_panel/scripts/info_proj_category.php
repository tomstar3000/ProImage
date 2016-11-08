<?php
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
include $r_path.'scripts/save_proj_category.php'; 
$is_enabled = ($cont == "view" || $cont == "query") ? false : true;
$is_back = ($cont== "edit" || $cont == "add" || (count($path)>3 && $cont == "view")) ? "view" : "query";?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <?php if($is_enabled){  ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Category Information</p></th>
  </tr>
  <?php } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,count($path)-1)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Category Information</p></th>
  </tr>
  <?php }
	$query_get_parent = "SELECT `cat_parent_id` FROM `proj_categories` WHERE `cat_id` = '$CPId'";
	$get_parent = mysql_query($query_get_parent, $cp_connection) or die(mysql_error());
	$row_get_parent = mysql_fetch_assoc($get_parent);
	$totalRows_get_parent = mysql_num_rows($get_parent);
	
	if($totalRows_get_parent != 0){
		$TempId = $row_get_parent['cat_parent_id'];		
		
		$query_get_categories = "SELECT `cat_id`, `cat_name` FROM `proj_categories` WHERE `cat_parent_id` = '$TempId'";
		$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
		$row_get_categories = mysql_fetch_assoc($get_categories);
		$totalRows_get_categories = mysql_num_rows($get_categories);
	} else {
		$totalRows_get_categories = 0;
	}
	mysql_free_result($get_parent);
	if($totalRows_get_categories != 0) { ?>
  <tr>
    <td width="20%"><strong>Parent Category: </strong></td>
    <td width="80%"><?php if(!$is_enabled){	echo $row_get_categories['cat_name']; } else { ?>
      <select name="Parent Category" id="Parent_Category">
        <?php do { ?>
        <option value="<?php echo $row_get_categories['cat_id']; ?>"<?php if($row_get_categories['cat_id'] == $CPId){print(" selected=\"selected\"");}?>><?php echo $row_get_categories['cat_name']; ?></option>
        <?php } while ($row_get_categories = mysql_fetch_assoc($get_categories)); ?>
      </select>
      <?php } ?>
    </td>
  </tr>
  <?php	}	if($totalRows_get_prarent_id != 0) mysql_free_result($get_categories); ?>
  <tr>
    <td><strong>Name:</strong></td>
    <td><?php if(!$is_enabled){	echo $CName; } else { ?>
      <input type="text" name="Name" id="Name" maxlength="75" value="<?php echo $CName; ?>">
      <?php } ?>
      <input type="hidden" name="Category_Id" id="Category_Id" value="<?php echo $CId;?>" /></td>
  </tr>
  <tr>
    <td colspan="2" style="padding:10px;<?php if($is_enabled){ ?> height:300px;<?php } ?>" valign="top">
		<?php if(!$is_enabled){ echo $CDesc; } else {
		$oFCKeditor = new FCKeditor('Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $CDesc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	}	?></td>
  </tr>
  <tr>
    <td><strong>Tag-Line:</strong></td>
    <td><?php if(!$is_enabled){	echo $TagLine; } else { ?>
      <input type="text" name="Tag_Line" id="Tag_Line" maxlength="75" value="<?php echo $TagLine; ?>">
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Image:</strong></td>
    <td><?php if($is_enabled){ ?>
      <input type="file" name="Image" id="Image">
      <input type="hidden" name="Image_val" id="Image_val" value="<?php echo $Imagev;?>">
      <?php } if($Image != ""){?>
      &nbsp;<a href="<?php echo $Cat_Folder; ?>/<?php echo $Image;?>" target="_blank">View</a>
      <?php } if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" />
      Remove Image
      <?php } ?></td>
  </tr>
</table>
