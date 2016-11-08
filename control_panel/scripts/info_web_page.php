<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <? if($is_enabled){ ?>
 <tr>
  <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
   <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>Website Page Information</p></th>
 </tr>
 <? } else { ?>
 <tr>
  <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,count($path)-1)); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
   <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>Website Page Information</p></th>
 </tr>
 <? }
  	foreach ($PPages as $k => $v){
		if($k==0) $Prnt_Id = -1; else $Prnt_Id = $PPages[$k-1];
		$Cur_id = $v;
		$query_get_pages = "SELECT `nav_id`,`nav_name` FROM `web_review_navigation` WHERE `nav_part_id` = '$Prnt_Id' ORDER BY `nav_name` ASC";
		$get_pages = mysql_query($query_get_pages, $cp_connection) or die(mysql_error());
		$row_get_pages = mysql_fetch_assoc($get_pages);
		$totalRows_get_pages = mysql_num_rows($get_pages);
  ?>
 <tr>
  <td width="20%"><strong>Parent Page :</strong></td>
  <td width="80%"><? if(!$is_enabled){
		if($Cur_id == -1 && $k==0){
			echo 'Main Navigation';
		} else if($Cur_id == 0 && $k==0){
			echo 'Home Page';
		} else {
			do {
				if($row_get_pages['nav_id'] == $Cur_id){
					echo substr($row_get_pages['nav_name'],0,25);
					if(strlen($row_get_pages['nav_name'])>25)	echo "...";
				}
			} while ($row_get_pages = mysql_fetch_assoc($get_pages));
		}
	} else { ?>
   <select name="Parnt_Page[]" id="Parnt_Page[]" onchange="document.getElementById('Controller').value = 'false'; this.form.submit();">
    <? if($k==0){ ?>
    <option value="-1"<? if($Cur_id == -1){print(" selected=\"selected\"");} ?>>-- Main Navigation --</option>
    <option value="0"<? if($Cur_id == 0){print(" selected=\"selected\"");} ?>>Home Page</option>
    <? 
		} else { ?>
    <option value="-1"<? if($Cur_id == -1){print(" selected=\"selected\"");} ?>>-- Select Parent --</option>
    <? }
		  if($totalRows_get_pages  > 0){
			  do { ?>
    <option value="<? echo $row_get_pages['nav_id']; ?>"<? if($row_get_pages['nav_id'] == $Cur_id){print(" selected=\"selected\"");} ?>> <? echo substr($row_get_pages['nav_name'],0,25);
					if(strlen($row_get_pages['nav_name'])>25)	echo "..."; ?> </option>
    <? } while ($row_get_pages = mysql_fetch_assoc($get_pages)); } ?>
   </select>
   <input type="hidden" name="Sel_Parnt_Page[]" id="Sel_Parnt_Page[]" value="<? echo $Cur_id; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <? 
  	mysql_free_result($get_pages);
		if($PSel_Page[$k]!=$v) break;
	}
  if($is_enabled){
	$query_get_pages = "SELECT `nav_id`,`nav_name` FROM `web_review_navigation` WHERE `nav_part_id` = '$Cur_id'";
	$get_pages = mysql_query($query_get_pages, $cp_connection) or die(mysql_error());
	$row_get_pages = mysql_fetch_assoc($get_pages);
	$totalRows_get_pages = mysql_num_rows($get_pages);
	
	if($totalRows_get_pages != 0 && $Cur_id != -1){ ?>
 <tr>
  <td><strong>Parent Page :</strong></td>
  <td><select name="Parnt_Page[]" id="Parnt_Page[]" onchange="document.getElementById('Controller').value = 'false'; this.form.submit();">
    <option value="-1">-- Select Parent Page --</option>
    <? do { ?>
    <option value="<? echo $row_get_pages['nav_id']; ?>"> <? echo substr($row_get_pages['nav_name'],0,25);
					if(strlen($row_get_pages['nav_name'])>25)	echo "..."; ?> </option>
    <? } while ($row_get_pages = mysql_fetch_assoc($get_pages)); ?>
   </select>
   <input type="hidden" name="Sel_Parnt_Page[]" id="Sel_Parnt_Page[]" value="-1" />
   &nbsp;</td>
 </tr>
 <? } mysql_free_result($get_pages);	} ?>
 <tr>
  <td><strong>Is in Navigation : </strong></td>
  <td><? if(!$is_enabled){ echo ($PNav == "y") ? "Yes" : "No";	} else { ?>
   <input type="radio" name="Is in Navigation" id="Is_in_Navigation" value="y"<? if($PNav == "y"){print(' checked="checked"');} ?> />
   Yes
   <input type="radio" name="Is in Navigation" id="Is_in_Navigation" value="n"<? if($PNav == "n"){print(' checked="checked"');} ?> />
   No
   <? } ?>
   &nbsp;
   <input type="hidden" name="Page_Id" id="Page_Id" value="<? echo $PId ?>" /></td>
 </tr>
 <tr>
  <td><strong>Use Parent Link:</strong> </td>
  <td><? if(!$is_enabled){ echo ($PUse == "y") ? "Yes" : "No"; } else { ?>
   <input type="radio" name="Use Parent Navigation" id="Use_Parent_Navigation" value="y"<? if($PUse == "y"){print(' checked="checked"');} ?> />
   Yes
   <input type="radio" name="Use Parent Navigation" id="Use_Parent_Navigation" value="n"<? if($PUse == "n"){print(' checked="checked"');} ?> />
   No
   <? } ?></td>
 </tr>
 <tr>
  <td><strong>Require Login:</strong></td>
  <td><? if(!$is_enabled){ echo ($PLog == "y") ? "Yes" : "No";	} else { ?>
   <input type="radio" name="Require Login" id="Require_Login" value="y"<? if($PLog == "y"){print(' checked="checked"');} ?> />
   Yes
   <input type="radio" name="Require Login" id="Require_Login" value="n"<? if($PLog == "n"){print(' checked="checked"');} ?> />
   No
   <? } ?></td>
 </tr>
 <tr>
  <td><strong>Navigation Name :</strong></td>
  <td><? if(!$is_enabled){ echo $PNName; } else { ?>
   <input type="text" name="Navigation Name" id="Navigation_Name" maxlength="150" value="<? echo $PNName; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Page Name: </strong></td>
  <td><? if(!$is_enabled){ echo $PName; } else { ?>
   <input type="text" name="Page Name" id="Page_Name" maxlength="150" value="<? echo $PName; ?>" />
   <input type="hidden" name="Page Info Id" id="Page_Info_Id" value="<? echo $PageId; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Page Header:</strong></td>
  <td><? if(!$is_enabled){ echo $PHead1; } else { ?>
   <input type="text" name="Header 1" id="Header_1" maxlength="50" value="<? echo $PHead1; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>2nd Page Header: </strong></td>
  <td><? if(!$is_enabled){ echo $PHead2; } else { ?>
   <input type="text" name="Header 2" id="Header_2" maxlength="75" value="<? echo $PHead2; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Tag Ling: </strong></td>
  <td><? if(!$is_enabled){ echo $PTag1; } else { ?>
   <input type="text" name="Tag Line 1" id="Tag_Line_1" maxlength="75" value="<? echo $PTag1; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>2nd Tag Line :</strong></td>
  <td><? if(!$is_enabled){ echo $PTag2; } else { ?>
   <input type="text" name="Tag Line 2" id="Tag_Line_2" maxlength="75" value="<? echo $PTag2; ?>" />
   <? } ?>
   &nbsp;
   <input type="hidden" name="Rmv_Id" id="Rmv_Id" value="" /></td>
 </tr>
 <tr>
  <td><strong>Page Style:</strong></td>
  <td><? if (!$is_enabled){
		switch($PStyle){
			case 0:
				echo '<img src="/'.$AevNet_Path.'/images/page_style_0.gif" />';
				break;
			case 1:
				echo '<img src="/'.$AevNet_Path.'/images/page_style_1.gif" />';
				break;
			case -1:
				echo 'New Window';
				break;
		}
	 } else { ?>
   <input type="radio" name="Page Style" id="Page_Style" value="0"<? if($PStyle == 0){print(' checked="checked"');} ?> />
   <img src="/<? echo $AevNet_Path; ?>/images/page_style_0.gif" />
   <input type="radio" name="Page Style" id="Page_Style" value="1"<? if($PStyle == 1){print(' checked="checked"');} ?> />
   <img src="/<? echo $AevNet_Path; ?>/images/page_style_1.gif" />
   <input type="radio" name="Page Style" id="Page_Style" value="-1"<? if($PStyle == -1){print(' checked="checked"');} ?> />
   New Window
   <? } ?></td>
 </tr>
 <? $n=0; foreach($PBHead as $k => $v){ $n++; ?>
 <tr>
  <td colspan="2"><hr size="1" /></td>
 </tr>
 <tr>
  <td><strong>Text Block Header:</strong> </td>
  <td><? if($is_enabled){ ?>
   <div style="float:left;">
    <input type="text" name="Text Header[]" id="Text_Header[]" maxlength="75" value="<? echo $v; ?>" />
    <input type="hidden" name="Text Ids[]" id="Text_Ids[]" value="<? echo $PBId[$k]; ?>" />
    &nbsp; </div>
   <? if (count($PBHead)>1){?>
   <div style="float:right">
    <input type="button" name="Remove_Text_Block" id="Remove_Text_Block" value="Remove Text Block" onclick="document.getElementById('Controller').value='remove_block'; document.getElementById('Rmv_Id').value='<? echo ($n-1); ?>';  this.form.submit();" />
   </div>
   <? } } else { echo $v; } ?></td>
 </tr>
 <tr>
  <td colspan="2" style="padding:10px;<? if($is_enabled){ ?> height:300px;<? } ?>" valign="top"><?
	 if(!$is_enabled){ echo $PBDesc[$k]; } else {
		$oFCKeditor = new FCKeditor('Description_'.$n);
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $PBDesc[$k];
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	} ?>
   &nbsp;</td>
 </tr>
 <? } if($is_enabled){ ?>
 <tr>
  <td colspan="2" align="right"><input type="button" name="Add_Text_Block" id="Add_Text_Block" value="Add Text Block" onclick="document.getElementById('Controller').value='new_block'; this.form.submit();" /></td>
 </tr>
 <tr>
  <td colspan="2" align="center"><input type="button" name="Reset" id="Reset" value="Reset Information"  onmouseup="document.getElementById('Controller').value = 'Reset'; this.disabled=true; this.form.submit();" /></td>
 </tr>
 <? } ?>
</table>
