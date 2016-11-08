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
  <p>Link Information</p></th>
 </tr>
 <? } else { ?>
 <tr>
  <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,count($path)-1)); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
   <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Link Information</p></th>
 </tr>
 <? } ?>
 <tr>
  <td width="20%"><strong>Name :</strong></td>
  <td width="80%"><? if(!$is_enabled){ echo $Name; } else { ?>
   <input type="text" name="Name" id="Name" maxlength="125" value="<? echo $Name; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>URL:</strong></td>
  <td><? if(!$is_enabled){ echo $URL; } else { ?>
   <input type="text" name="URL" id="URL" maxlength="125" value="<? echo $URL; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Target:</strong></td>
  <td><? if(!$is_enabled){ 
			switch ($Target){
				case "":
					echo "Itself";
					break;
				case "_parent":
					echo "Parent Window";
					break;
				case "_blank":
					echo "New Window";
					break;
				case "_top":
					echo "Top Window";
					break;
			}
	 } else { ?>
   <select name="Target" id="Target">
   <option value=""<? if($Target == "") echo ' selected="selected"'; ?>>Itself</option>
   <option value="_parent"<? if($Target == "_parent") echo ' selected="selected"'; ?>>Parent Window</option>
   <option value="_blank"<? if($Target == "_blank") echo ' selected="selected"'; ?>>New Window</option>
   <option value="_top"<? if($Target == "_top") echo ' selected="selected"'; ?>>Top Window</option>
   </select>
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Header 1 :</strong></td>
  <td><? if(!$is_enabled){ echo $Head1; } else { ?>
   <input type="text" name="Header 1" id="Header_1" maxlength="25" value="<? echo $Head1; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Header 2 : </strong></td>
  <td><? if(!$is_enabled){ echo $Head2; } else { ?>
   <input type="text" name="Header 2" id="Header_2" maxlength="25" value="<? echo $Head2; ?>" />
   <? } ?>
&nbsp;</td>
 </tr>
 <tr>
  <td><strong>Image:</strong></td>
  <td><? if($cont == "add" || $cont == "edit"){ ?>
   <input type="file" name="Image" id="Image" />
   <input type="hidden" name="Image_val" id="Image_val" value="<? echo $Imagev;?>" />
   <? }
	  if($Imagev != ""){?>
   &nbsp;<a href="<? echo $Link_Folder; ?>/<? echo $Imagev;?>" target="_blank">View</a>
   <? } 
	  if($cont == "add" || $cont == "edit"){ ?>
   &nbsp;
   <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" />
   Remove Image
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Image Alt Tag:</strong></td>
  <td><? if(!$is_enabled){ echo $Alt;	} else { ?>
   <input type="text" name="Alt" id="Alt" maxlength="150" value="<? echo $Alt; ?>" />
   <? } ?></td>
 </tr>
 <tr>
  <td colspan="2" style="padding:10px;<? if(!$is_enabled){ ?> height:300px;<? } ?>" valign="top"><? if(!$is_enabled){	echo $Desc;	} else {
		$oFCKeditor = new FCKeditor('Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $Desc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	}	?></td>
 </tr>
</table>
