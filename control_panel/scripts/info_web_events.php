<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)	$r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <? if($is_enabled){  ?>
 <tr>
  <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
   <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>Event Information</p></th>
 </tr>
 <? } else { ?>
 <tr>
  <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
   <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>Event Information</p></th>
 </tr>
 <? } ?>
 <tr>
  <td width="20%"><strong>Event Name: </strong></td>
  <td><? if(!$is_enabled){ echo $Name; } else { ?>
   <input type="text" name="Name" id="Name" maxlength="75" value="<? echo $Name; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Code:</strong></td>
  <td><? if(!$is_enabled){ echo $Code; } else { ?>
   <input type="text" name="Code" id="Code" maxlength="75" value="<? echo $Code; ?>" />
   <? } ?>
&nbsp;</td>
 </tr>
 <tr>
  <td><strong>Contact:</strong></td>
  <td><? 
	$query_get_option = "SELECT `event_con_id`,`event_con_lname`, `event_con_fname` FROM `web_event_contacts` ORDER BY `event_con_lname`, `event_con_fname` ASC";
	$get_option = mysql_query($query_get_option, $cp_connection) or die(mysql_error());
	//$row_get_option = mysql_fetch_assoc($get_option);
 	if(!$is_enabled){
		while ($row_get_option = mysql_fetch_assoc($get_option)) if($row_get_option['event_con_id'] == $Cont) echo $row_get_option['event_con_lname'].", ".$row_get_option['event_con_fname'];
	} else { ?>
   <select name="Contact" id="Contact" >
    <option value="0"<? if($Cont == 0){print(" selected=\"selected\"");} ?>>-- Select Contact --</option>
    <? while ($row_get_option = mysql_fetch_assoc($get_option)){ ?>
    <option value="<? echo $row_get_option['event_con_id']; ?>"<? if($row_get_option['event_con_id'] == $Cont){print(" selected=\"selected\"");} ?>><? echo $row_get_option['event_con_lname'].", ".$row_get_option['event_con_fname']; ?></option>
    <? } ?>
   </select>
   <? } ?>
   &nbsp;</td>
 </tr>
 
 <tr>
  <td><strong>Location:</strong></td>
  <td><?
	$query_get_option = "SELECT `event_loc_id`,`event_loc_name` FROM `web_event_locations` ORDER BY `event_loc_name` ASC";
	$get_option = mysql_query($query_get_option, $cp_connection) or die(mysql_error());
	//$row_get_option = mysql_fetch_assoc($get_option);
   if(!$is_enabled){
		while ($row_get_option = mysql_fetch_assoc($get_option)) if($row_get_option['event_loc_id'] == $Loc) echo $row_get_option['event_loc_name'];
	} else { ?>
   <select name="Location" id="Location" >
    <option value="0"<? if($Loc == 0){print(" selected=\"selected\"");} ?>>-- Select Location --</option>
    <? while ($row_get_option = mysql_fetch_assoc($get_option)){ ?>
    <option value="<? echo $row_get_option['event_loc_id']; ?>"<? if($row_get_option['event_loc_id'] == $Loc){print(" selected=\"selected\"");} ?>><? echo $row_get_option['event_loc_name']; ?></option>
    <? } ?>
   </select>
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Price:</strong></td>
  <td><? if(!$is_enabled){echo "$ ".number_format($Price,2,".",",");} else { ?>
   <input type="text" name="Price" id="Price" maxlength="75" size="12" value="<? echo $Price; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Link:</strong></td>
  <td><? if(!$is_enabled){ echo $Link; } else { ?>
   <input type="text" name="Link" id="Link" maxlength="75" value="<? echo $Link; ?>" />
   <? } ?>
&nbsp;</td>
 </tr>
 <tr>
  <td><strong>Start Date:</strong></td>
  <td><? if(!$is_enabled){ echo format_date($SDate,"DayShort","Standard",true,true); } else { 
	//yyyy-mm-dd hh:mm:ss
	//0123456789012345678?>
   <input type="text" name="Start Date" id="Start_Date" maxlength="75" value="<? echo substr($SDate,0,10); ?>" />
   <a href="#" onclick="newwindow=window.open('scripts/calendar.php?future=true&time=false&field=Start_Date','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;"> Select
   Date</a> (yyyy-mm-dd)
   <? } ?>
   &nbsp;</td>
 </tr>
 <? if($is_enabled){ ?>
 <tr>
  <td><strong>Start Time:</strong></td>
  <td><input type="text" name="Start Hour" id="Start_Hour" maxlength="2" value="<? echo date('g',mktime(substr($SDate,11,2),1,1,1,1,1));  ?>" size="4" />
   :
   <input type="text" name="Start Minute" id="Start_Minute" maxlength="2" value="<? echo date('i',mktime(1,substr($SDate,14,2),1,1,1,1)); ?>" size="4" />
   <select name="Start AM PM" id="Start_AM_PM">
    <option value="0"<? if(date('A',mktime(substr($SDate,11,2),1,1,1,1,1)) == "AM") echo ' selected="selected"'; ?>>AM</option>
    <option value="1"<? if(date('A',mktime(substr($SDate,11,2),1,1,1,1,1)) == "PM") echo ' selected="selected"'; ?>>PM</option>
   </select>
   &nbsp;</td>
 </tr>
 <? } ?>
 <tr>
  <td><strong>End Date:</strong></td>
  <td><? if(!$is_enabled){ echo format_date($EDate,"DayShort","Standard",true,true); } else { ?>
   <input type="text" name="End Date" id="End_Date" maxlength="75" value="<? echo substr($EDate,0,10); ?>" />
   <a href="#" onclick="newwindow=window.open('scripts/calendar.php?future=true&time=false&field=End_Date','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;"> Select
   Date</a> (yyyy-mm-dd)
   <? } ?>
   &nbsp;</td>
 </tr>
 <? if($is_enabled){ ?>
 <tr>
  <td><strong>End Time:</strong></td>
  <td><input type="text" name="End Hour" id="End_Hour" maxlength="2" value="<? echo date('g',mktime(substr($EDate,11,2),0,1,1,1,1));  ?>" size="4" />
   :
   <input type="text" name="End Minute" id="End_Minute" maxlength="2" value="<? echo date('i',mktime(1,substr($EDate,14,2),0,1,1,1)); ?>" size="4" />
   <select name="End AM PM" id="End_AM_PM">
    <option value="0"<? if(date('A',mktime(substr($EDate,11,2),1,1,1,1,1)) == "AM") echo ' selected="selected"'; ?>>AM</option>
    <option value="1"<? if(date('A',mktime(substr($EDate,11,2),1,1,1,1,1)) == "PM") echo ' selected="selected"'; ?>>PM</option>
   </select>
   &nbsp;</td>
 </tr>
 <? } ?>
 <tr>
  <td colspan="2"><strong>Description:</strong></td>
 </tr>
 <tr>
  <td colspan="2" style="padding:10px;<? if($cont == "add" || $cont == "edit"){ ?> height:300px;<? } ?>" valign="top"><? if(!$is_enabled){ echo $EDesc; } else {
		$oFCKeditor = new FCKeditor('Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $EDesc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	} ?>
   &nbsp;</td>
 </tr>
 <!-- 
 <tr>
  <td><strong>Image Description:</strong></td>
  <td><? if(!$is_enabled){echo $Alt; } else { ?>
   <input type="text" name="Alt" id="Alt" maxlength="75" value="<? echo $Alt; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 -->
 <tr>
  <td><strong> Image:</strong></td>
  <td><? if($cont == "add" || $cont == "edit"){ ?>
   <input type="file" name="Image" id="Image">
   <input type="hidden" name="Image_val" id="Image_val" value="<? echo $Imagev;?>">
   &nbsp;
   <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" />
   Remove Image
   <? } if($Imagev != ""){?>
   &nbsp;<a href="<? echo $Evnt_Folder; ?>/<? echo $Imagev;?>" target="_blank">View</a>
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Log-in Required:</strong></td>
  <td><? if(!$is_enabled){ echo ($Private=="y") ? "Yes" : "No"; } else { ?>
   <input type="radio" name="Private" id="Private" value="y"<? if($Private=="y"){print(" checked=\"checked\"");} ?> />
   Yes
   <input type="radio" name="Private" id="Private" value="n"<? if($Private=="n"){print(" checked=\"checked\"");} ?> />
   No
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Send Reminder:</strong></td>
  <td><? if(!$is_enabled){ echo ($Remind=="y") ? "Yes" : "No"; } else { ?>
   <input type="radio" name="Remind" id="Remind" value="y"<? if($Remind=="y"){print(" checked=\"checked\"");} ?> />
Yes
<input type="radio" name="Remind" id="Remind" value="n"<? if($Remind=="n"){print(" checked=\"checked\"");} ?> />
No
<? } ?></td>
 </tr>
 <tr>
  <td><strong>Days Remaining:</strong></td>
  <td><? if(!$is_enabled){echo $DRemind;} else { ?>
   <input type="text" name="Days Remaining" id="Days_Remaining" maxlength="75" size="12" value="<? echo $DRemind; ?>" />
   <? } ?>
&nbsp;</td>
 </tr>
 
 <tr>
  <td colspan="2"><strong>Message:</strong></td>
 </tr>
 <tr>
  <td colspan="2" style="padding:10px;<? if($cont == "add" || $cont == "edit"){ ?> height:300px;<? } ?>" valign="top"><? if(!$is_enabled){ echo $MRemind; } else {
		$oFCKeditor = new FCKeditor('Message');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $MRemind;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	} ?>
   &nbsp;</td>
 </tr>
</table>
