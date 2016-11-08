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
   <p>Press Release Information</p></th>
 </tr>
 <? } else { ?>
 <tr>
  <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,count($path)-1)); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
   <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>Press Release Information</p></th>
 </tr>
 <? }
  	if($is_enabled){
			$query_name = "SELECT `nav_id`, `nav_name` FROM `web_review_navigation` WHERE `nav_name` LIKE '%press%' AND `nav_is_nav` = 'y'";
			$name = mysql_query($query_name, $cp_connection) or die(mysql_error());
			//$row_name = mysql_fetch_assoc($page);
		} else {
			$query_name = "SELECT `nav_name` FROM `web_review_navigation` WHERE `nav_id` = '$Cur_id'";
			$name = mysql_query($query_name, $cp_connection) or die(mysql_error());
			$row_name = mysql_fetch_assoc($name);
		}
  ?>
 <tr>
  <td width="20%"><strong>Parent Page :</strong></td>
  <td width="80%"><? if(!$is_enabled){ echo $row_name['nav_name']; } else { ?>
   <select name="Parnt_Page" id="Parnt_Page">
    <option value="0"<? if($Cur_id == 0){print(" selected=\"selected\"");} ?>>-- Main Press Page --</option>
    <? while($row_name = mysql_fetch_assoc($name)){	?>
    <option value="<? echo $row_name['nav_id']; ?>"<? if($row_name['nav_id'] == $Cur_id){print(" selected=\"selected\"");} ?>> <? echo substr($row_name['nav_name'],0,25); if(strlen($row_name['nav_name'])>25) echo "..."; ?> </option>
    <? } ?>
   </select>
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Date:</strong></td>
  <td><? if(!$is_enabled){ echo format_date($NDate,"DayShort","Standard",true,false); } else { ?>
   <input type="text" name="Press Date" id="Press_Date" maxlength="150" value="<? echo $NDate; ?>" />
   <a href="#" onclick="newwindow=window.open('scripts/calendar.php?future=true&time=false&field=Press_Date','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;"> Select
   Date</a> (yyyy-mm-dd hh:mm:ss)
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Display in Info Section: </strong></td>
  <td><? if(!$is_enabled){ echo ($NDisplay == "y") ? "Yes" : "No"; } else { ?>
   <input type="radio" name="Display Info" id="Display_Info" value="y"<? if($NDisplay == "y")echo ' checked="checked"'; ?> />
   Yes
   <input type="radio" name="Display Info" id="Display Info" value="n"<? if($NDisplay == "n")echo ' checked="checked"'; ?> />
   No
   <? } ?></td>
 </tr>
 <tr>
  <td><strong>Press Header :</strong></td>
  <td><? if(!$is_enabled){ echo $NHeader; } else { ?>
   <input type="text" name="Press Header" id="Press_Header" maxlength="150" value="<? echo $NHeader; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>2nd Press Header : </strong></td>
  <td><? if(!$is_enabled){ echo $NHeader2; } else { ?>
   <input type="text" name="Press Header 2" id="Press_Header_2" maxlength="150" value="<? echo $NHeader2; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Link:</strong></td>
  <td><? if(!$is_enabled){ echo $NLink;	} else { ?>
   <input type="text" name="Link" id="Link" maxlength="150" value="<? echo $NLink; ?>" />
   <? } ?>
   If you want press to just link to a website: Place link here </td>
 </tr>
 <tr>
  <td><strong>Document:</strong></td>
  <td><? if($is_enabled){ ?>
   <input type="file" name="File" id="File" />
   <input type="hidden" name="File_val" id="File_val" value="<? echo $Filev;?>" />
   <input type="hidden" name="File_name" id="File_name" value="<? echo $Fname;?>" />
   <? } if($File != ""){?>
   &nbsp;<a href="<? echo $Prod_White_Folder; ?>/<? echo $File;?>" target="_blank">View</a>
   <? } if($is_enabled){ ?>
   &nbsp;
   <input type="checkbox" name="Remove File" id="Remove_File" value="true" />
   Remove File
   <? } ?></td>
 </tr>
 <tr>
  <td colspan="2" style="padding:10px;<? if(!$is_enabled){ ?> height:300px;<? } ?>" valign="top"><? if(!$is_enabled){	echo $NDesc;	} else {
		$oFCKeditor = new FCKeditor('Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $NDesc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	}	?></td>
 </tr>
</table>
