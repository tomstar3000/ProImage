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
include $r_path.'scripts/save_group.php';
$is_enabled = ($cont == "view" || $cont == "query") ? false : true;
$is_back = ($cont== "edit" || $cont == "add" || (count($path)>3 && $cont == "view")) ? "view" : "query";?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <?php if($is_enabled){  ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Group Information</p></th>
  </tr>
  <?php } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,count($path)-1)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Group Information</p></th>
  </tr>
  <?php 
  }
	$query_get_parent = "SELECT `group_part_id` FROM `prod_groups` WHERE `group_id` = '$GPId'";
	$get_parent = mysql_query($query_get_parent, $cp_connection) or die(mysql_error());
	$row_get_parent = mysql_fetch_assoc($get_parent);
	$totalRows_get_parent = mysql_num_rows($get_parent);
	
	if($totalRows_get_parent != 0){
		$TempId = $row_get_parent['group_part_id'];		
		
		$query_get_groups = "SELECT `group_id`, `group_name` FROM `prod_groups` WHERE `group_part_id` = '$TempId'";
		$get_groups = mysql_query($query_get_groups, $cp_connection) or die(mysql_error());
		$row_get_groups = mysql_fetch_assoc($get_groups);
		$totalRows_get_groups = mysql_num_rows($get_groups);
	} else {
		$totalRows_get_groups = 0;
	}
	mysql_free_result($get_parent);
	if($totalRows_get_groups != 0) { ?>
  <tr>
    <td width="20%"><strong>Parent Group: </strong></td>
    <td width="80%"><?php if(!$is_enabled){ echo $row_get_groups['group_name']; } else { ?>
      <select name="Parent Group" id="Parent_Group">
        <?php do { ?>
        <option value="<?php echo $row_get_groups['group_id']; ?>"<?php if($row_get_groups['group_id'] == $GPId){print(" selected=\"selected\"");}?>><?php echo $row_get_groups['group_name']; ?></option>
        <?php } while ($row_get_groups = mysql_fetch_assoc($get_groups)); ?>
      </select>
      <?php } ?>
      &nbsp;</td>
  </tr>
  <?php	} if($totalRows_get_prarent_id != 0) mysql_free_result($get_groups); ?>
  <tr>
    <td><strong>Name:</strong></td>
    <td><?php if(!$is_enabled){	echo $GName; } else { ?>
      <input type="text" name="Name" id="Name" maxlength="75" value="<?php echo $GName; ?>">
      <?php } ?>
      <input type="hidden" name="Group_Id" id="Group_Id" value="<?php echo $GId;?>" />
      &nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="padding:10px;<?php if($is_enabled){ ?> height:300px;<?php } ?>"  valign="top">
	<?php if(!$is_enabled){	echo $GDesc; } else {
		$oFCKeditor = new FCKeditor('Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $GDesc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	}?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Image:</strong></td>
    <td><?php if($is_enabled){ ?>
      <input type="file" name="Image" id="Image">
      <input type="hidden" name="Image_val" id="Image_val" value="<?php echo $Imagev;?>">
      <?php }
	  if($Image != ""){?>
      &nbsp;<a href="<?php echo $Group_Folder; ?>/<?php echo $Image;?>" target="_blank">View</a>
      <?php } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" />
      Remove Image
      <?php } ?>
      &nbsp;</td>
  </tr>
</table>
