<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++) $r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$required_string = "'Name','','R','Code','','R','Date','','R','End_Date','','R'";
if((count($path)<=3 && $cont == "view") || (count($path)>3)){
	$is_enabled = false;
} else {
	$is_enabled = true;
} 
if($is_enabled){  ?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Events Information </h2>
 <p id="Back"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');">Back</a></p>
</div>
<? } else { ?>
<div id="Tri_Nav">
 <ul class="Tabs">
  <li onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');">
   <p>Overview</p>
  </li>
  <li onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)).',Disc'; ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');">
   <p>Preset Discount Codes</p>
  </li>
  <li onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)).',Msg'; ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');">
   <p>Message Board</p>
  </li>
  <li onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)).',Note'; ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');">
   <p>Event Notifications</p>
  </li>
 </ul>
</div>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Events Information</h2>
 <? if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),"msie")!==false){ ?>
 <p id="Mass_Upload"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','upload','<? echo $sort; ?>','<? echo $rcrd; ?>');">Mass
   Upload</a></p>
 <? } ?>
 <p id="Back"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,2)); ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');">Back</a></p>
 <p id="Edit"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');">Edit</a></p>
</div>
<? } ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <? if(isset($Error)) echo '<tr><td colspan="2" style="background-color:#FFFFFF; color:#CC0000;">'.$Error.'</td></tr>'; ?>
 <tr>
  <td>Event Name:</td>
  <td><? if(!$is_enabled){ echo $Name; } else { ?>
   <input type="text" name="Name" id="Name" value="<? echo $Name; ?>">
   <? } ?>
   &nbsp;</td>
 </tr>
 <?
	$query_get_price = "SELECT * FROM `photo_event_price` WHERE `cust_id` = '$CustId' AND `photo_price_use` = 'y' ORDER BY `price_name` ASC";
	$get_price = mysql_query($query_get_price, $cp_connection) or die(mysql_error());	
	?>
 <tr>
  <td>Pricing Group: </td>
  <td><? if(!$is_enabled){ while($row_get_price = mysql_fetch_assoc($get_price)){
			if($Group==$row_get_price['photo_event_price_id']){echo $row_get_price['price_name']; break; }
		} } else { ?>
   <select name="Pricing Group" id="Pricing_Group">
    <? while($row_get_price = mysql_fetch_assoc($get_price)){ ?>
    <option value="<? echo $row_get_price['photo_event_price_id']; ?>"<? if($Group==$row_get_price['photo_event_price_id'])echo' selected="selected"';?>> <? echo $row_get_price['price_name']; ?> </option>
    <? } ?>
   </select>
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td>Public Event: </td>
  <td><? if(!$is_enabled){echo($Public=="n")?'No':'Yes';} else { ?>
   <input type="radio" name="Public Event" id="Public_Event" value="n"<? if($Public=="n")echo' checked="checked"';?> />
   No
   <input type="radio" name="Public Event" id="Public_Event" value="y"<? if($Public=="y")echo' checked="checked"';?> />
   Yes
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td>Event Code:</td>
  <td><? if(!$is_enabled){echo $Code;} else { ?>
   <input name="Code" type="text" id="Code" value="<? echo $Code; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <? if(count($path) < 4){ 
	$query_get_image = "SELECT `image_tiny` FROM `photo_event_images` WHERE `image_id` = '$Image'";
	$get_image = mysql_query($query_get_image, $cp_connection) or die(mysql_error());
	$row_get_image = mysql_fetch_assoc($get_image);
	$ImageName = $row_get_image['image_tiny'];
	?>
 <tr>
  <td>Watermark:</td>
  <td><? if(!$is_enabled){echo $Copy;} else { ?>
   <input name="Watermark" type="text" id="Watermark" value="<? echo $Copy; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td>Watermark Opacity: </td>
  <td><? if(!$is_enabled){echo $Opac;} else { ?>
   <input name="Watermark Opacity" type="text" id="Watermark_Opacity" value="<? echo $Opac; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td>Watermark Frequency: </td>
  <td><? if(!$is_enabled){echo $WFreq;} else { ?>
   <input name="Watermark Frequency" type="text" id="Watermark_Frequency" value="<? echo $WFreq; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td>Event Cover: </td>
  <td><? if(!$is_enabled){
		echo $ImageName;
	} else { ?>
   <input type="text" name="Image" id="Image" value="<? echo $ImageName; ?>" readonly="readonly" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','choose','<? echo $sort; ?>','<? echo $rcrd; ?>');" />
   <input type="hidden" name="Image_Val" id="Image_Val" value="<? echo $Image; ?>" />
   - <a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','choose','<? echo $sort; ?>','<? echo $rcrd; ?>');">Change</a> Use
   this to change the events album cover.
   <? } ?></td>
 </tr>
 <tr>
  <td>Event Date: </td>
  <td><? if($Date != "" && $Date != " " && $Date != "--" && $Date != "0000-00-00 00:00:00") $TDate = substr($Date,5,2)."/".substr($Date,8,2)."/".substr($Date,0,4); else $TDate = "";
		if(!$is_enabled){
		echo $TDate;
	} else { ?>
   <input type="text" name="Date" id="Date" value="<? echo $TDate; ?>" readonly="readonly" onclick="newwindow=window.open('scripts/calendar.php?future=both&time=false&field=Date','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;" />
   &nbsp;<img src="/control_panel/images/ico_cal.jpg" width="19" height="22" hspace="0" vspace="0" border="0" onclick="newwindow=window.open('scripts/calendar.php?future=both&time=false&field=Date','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;" style="cursor:pointer"> (mm/dd/yyyy)
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td>Listing End Date: </td>
  <td><? 
		if($EDate != "" && $EDate != " " && $EDate != "--" && $EDate != "0000-00-00 00:00:00") $TEDate = substr($EDate,5,2)."/".substr($EDate,8,2)."/".substr($EDate,0,4); else $TEDate = "";
		if(!$is_enabled){
		echo $TEDate;
	} else { ?>
   <input type="text" name="End Date" id="End_Date" value="<? echo $TEDate; ?>" readonly="readonly" onclick="newwindow=window.open('scripts/calendar.php?future=both&time=false&field=End_Date','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;" />
   &nbsp;<img src="/control_panel/images/ico_cal.jpg" width="19" height="22" hspace="0" vspace="0" border="0" onclick="newwindow=window.open('scripts/calendar.php?future=both&time=false&field=End_Date','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;" style="cursor:pointer"> (mm/dd/yyyy)
   <? } ?>
   &nbsp;This is when you want your listing to end. </td>
 </tr>
 <tr>
  <td>Notify Guestbook # days before end of event: </td>
  <td><? if(!$is_enabled){echo $ENote;} else { ?>
   <input name="Notify" type="text" id="Notify" value="<? echo $ENote; ?>" />
   <? } ?>
   Days </td>
 </tr>
 <tr>
  <td>Send Directly to Lab: </td>
  <td><? if(!$is_enabled){echo($ToLab=="n")?'No':'Yes';} else { ?>
   <input type="radio" name="To Lab" id="To_Lab" value="n"<? if($ToLab=="n")echo' checked="checked"';?> />
   No
   <input type="radio" name="To Lab" id="To_Lab" value="y"<? if($ToLab=="y")echo' checked="checked"';?> />
   Yes
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td>Allow Customers to Pick Up At: </td>
  <td><? if(!$is_enabled){if($AtLab=="y")echo'&nbsp;Lab';if($AtPhoto=="y")echo'&nbsp;Studio';} else { ?>
   <input type="checkbox" name="At Lab" id="At_Lab" value="y"<? if($AtLab=="y")echo' checked="checked"';?> />
   Lab
   <input type="checkbox" name="At Studio" id="At_Studio" value="y"<? if($AtPhoto=="y")echo' checked="checked"';?> />
   Studio
   <? } ?>
   &nbsp;</td>
 </tr>
 <? } ?>
</table>
