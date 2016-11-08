<?
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
include $r_path.'scripts/save_category.php'; 
$is_enabled = ($cont == "view" || $cont == "query") ? false : true;
$is_back = ($cont== "edit" || $cont == "add" || (count($path)>3 && $cont == "view")) ? "view" : "query";?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <? if($is_enabled){  ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
      <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Category Information</p></th>
  </tr>
  <? } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,count($path)-1)); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
      <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Category Information</p></th>
  </tr>
  <? 
  }
	$query_get_parent = "SELECT `cat_parent_id` FROM `prod_categories` WHERE `cat_id` = '$CPId'";
	$get_parent = mysql_query($query_get_parent, $cp_connection) or die(mysql_error());
	$row_get_parent = mysql_fetch_assoc($get_parent);
	$totalRows_get_parent = mysql_num_rows($get_parent);
	
	if($totalRows_get_parent != 0){
		$TempId = $row_get_parent['cat_parent_id'];		
		
		$query_get_categories = "SELECT `cat_id`, `cat_name` FROM `prod_categories` WHERE `cat_parent_id` = '$TempId'";
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
    <td width="80%"><? if(!$is_enabled){
		do {
			if($row_get_categories['cat_id'] == $CPId) echo $row_get_categories['cat_name'];
		} while ($row_get_categories = mysql_fetch_assoc($get_categories));
	 } else { ?>
      <select name="Parent Category" id="Parent_Category">
        <? do { ?>
        <option value="<? echo $row_get_categories['cat_id']; ?>"<? if($row_get_categories['cat_id'] == $CPId){print(" selected=\"selected\"");}?>><? echo $row_get_categories['cat_name']; ?></option>
        <? } while ($row_get_categories = mysql_fetch_assoc($get_categories)); ?>
      </select>
      <? } ?>
      &nbsp;</td>
  </tr>
  <?	}
	if($totalRows_get_prarent_id != 0){
		mysql_free_result($get_categories);
	} ?>
  <tr>
    <td><strong>Name:</strong></td>
    <td><? if(!$is_enabled){	echo $CName; } else { ?>
      <input type="text" name="Name" id="Name" maxlength="75" value="<? echo $CName; ?>">
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="padding:10px;<? if($is_enabled){ ?> height:300px;<? } ?>"  valign="top"><? if(!$is_enabled){
		echo $CDesc;
	} else {
		$oFCKeditor = new FCKeditor('Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $CDesc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	} ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Tag-Line:</strong></td>
    <td><? if(!$is_enabled){
		echo $TagLine;
	} else { ?>
      <input type="text" name="Tag_Line" id="Tag_Line" maxlength="75" value="<? echo $TagLine; ?>">
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Image:</strong></td>
    <td><? if($is_enabled){ ?>
      <input type="file" name="Image" id="Image">
      <input type="hidden" name="Image_val" id="Image_val" value="<? echo $Imagev;?>">
      <? 
	  }
	  if($Image != ""){?>
      &nbsp;<a href="<? echo $Cat_Folder; ?>/<? echo $Image;?>" target="_blank">View</a>
      <? } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" />
      Remove Image
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Show in Shopping Cart:</strong></td>
    <td><? if(!$is_enabled){
		if($Show=="y"){print("Yes");} else {print("No");}
	} else { ?>
      <input type="radio" name="Show" id="Show" value="y"<? if($Show=="y"){print(" checked=\"checked\"");}?>>
      Yes&nbsp;
      <input type="radio" name="Show" id="Show" value="n"<? if($Show=="n"){print(" checked=\"checked\"");}?>>
      No
      <? } ?>
      &nbsp;</td>
  </tr>
</table>
