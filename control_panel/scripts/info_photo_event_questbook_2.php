<? if(isset($r_path)===false){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";
if($is_enabled){  ?>

<div id="Sub_Header"> <img src="/<?  echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
 <h2>Event Notification Information</h2>
 <p id="Back"><a href="#" onclick="javascript:set_form('form_','<?  echo implode(",",array_slice($path,0,3)); ?>','<?  echo $is_back; ?>','<?  echo $sort; ?>','<?  echo $rcrd; ?>');">Back</a></p>
</div>
<?  } else { ?>
<div id="Sub_Header"> <img src="/<?  echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
 <h2>Event Notification Information</h2>
 <p id="Edit"><a href="#" onclick="javascript:set_form('form_','<?  echo implode(",",$path); ?>','edit','<?  echo $sort; ?>','<?  echo $rcrd; ?>');">Edit</a></p>
 <p id="Back"><a href="#" onclick="javascript:set_form('form_','<?  echo implode(",",array_slice($path,0,2)); ?>','<?  echo $is_back; ?>','<?  echo $sort; ?>','<?  echo $rcrd; ?>');">Back</a></p>
</div>
<? } ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td><strong>Event:</strong></td>
  <td><? $query_get_info = "SELECT `event_name`, `event_id`
	FROM `photo_event`
	WHERE `cust_id` = '$CustId'
		AND `event_use` = 'y'
	ORDER BY `event_name` ASC";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
if(!$is_enabled){ while($row_get_info = mysql_fetch_assoc($get_info)){if($EID == $row_get_info['event_id'])echo $row_get_info['event_name']; } } else { ?>
   <select name="Event_Id" id="Event_Id">
    <option value="0"<? if($EID == 0)echo' selected="selected"'; ?>>Select Event</option>
    <? while($row_get_info = mysql_fetch_assoc($get_info)){ ?>
    <option value="<? echo $row_get_info['event_id']; ?>"<? if($EID == $row_get_info['event_id'])echo' selected="selected"'; ?>><? echo $row_get_info['event_name']; ?></option>
    <? } ?>
   </select>
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Name</strong></td>
  <td><? if(!$is_enabled){ echo $Name; } else { ?>
   <input type="text" name="Name" id="Name" value="<? echo $Name; ?>">
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td valign="top"><strong>Add Recipients</strong></td>
  <td><? if($is_enabled){ ?>
   <input type="text" name="Emails" id="Emails" value="" />
   <br />
   (You may add email address for this event notification , please separate by commas. Email notifications will go out to everyone
   in guestbook as well as email addresses you enter.)
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Number of Days</strong></td>
  <td><? if(!$is_enabled){ echo $Days."&nbsp;";
		switch($Before){
			case "b":
				echo "Before Event";
				break;
			case "s":
				echo "After Event Starts";
				break;
			case "e":
				echo "Before Event Ends";
				break;
		} } else { ?>
   <input type="text" name="Days" id="Days" value="<? echo $Days; ?>">
   <input type="radio" name="BeforeDate" id="BeforeDate" value="b"<? if($Before=="b")echo ' checked="checked"'; ?>>
   Before Event
   <input type="radio" name="BeforeDate" id="BeforeDate" value="s"<? if($Before=="s")echo ' checked="checked"'; ?>>
   After Event Starts
   <input type="radio" name="BeforeDate" id="BeforeDate" value="e"<? if($Before=="e")echo ' checked="checked"'; ?>>
   Before Event Ends
   <? } ?></td>
 </tr>
 <tr>
  <td><strong>On Date</strong></td>
  <td><? if($Date != "" && $Date != " " && $Date != "--" && $Date != "0000-00-00 00:00:00") $TDate = substr($Date,5,2)."/".substr($Date,8,2)."/".substr($Date,0,4); else $TDate = "";
	if(!$is_enabled){ echo $TDate; } else { ?>
   <input type="text" name="Date" id="Date" value="<? echo $TDate; ?>" readonly="readonly" onclick="newwindow=window.open('scripts/calendar.php?future=true&time=false&field=Date','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;" />
   &nbsp;<img src="/control_panel/images/ico_cal.jpg" width="19" height="22" hspace="0" vspace="0" border="0" onclick="newwindow=window.open('scripts/calendar.php?future=true&time=false&field=Date','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;" style="cursor:pointer"> (mm/dd/yyyy)
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Image</strong></td>
  <td><?  if($is_enabled){ ?>
   <input type="file" name="Image" id="Image" />
   Image will display 150 pixels wide in the top left corner
   <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" />
   Remove Image
   <? } if($Imagev === true){?>
   &nbsp;<a href="/control_panel/event_image.php?id=<? echo urlencode(base64_encode($QId)); ?>" target="_blank">View</a>
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td colspan="2"><strong>Notification Text</strong></td>
 </tr>
 <tr>
  <td colspan="2" valign="top" style="padding:10px; height:500px;"><?  if(!$is_enabled){ echo $Desc;	} else {
		$oFCKeditor = new FCKeditor('Text');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $Desc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '500';
		$oFCKeditor->ToolbarSet = 'Very_Basic';
		$oFCKeditor->Create();
		$output = $oFCKeditor->CreateHtml(); } ?></td>
 </tr>
</table>
