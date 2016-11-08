<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';

$temppathing = $path[0].",".$path[1];
$pattern = "/Path=";
  for($n=0;$n<count($pathing);$n++){
  	$pattern .= ($n == 0) ? "[\\d\\w]*" : ",[\\d\\w]*" ;
  }
  $pattern .= "/";
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; 
if(!$is_enabled){ ?>

<div id="Tri_Nav">
 <table border="0" cellpadding="0" cellspacing="0">
  <tr>
   <td align="center" class="Tabs" onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');"><p>Overview</p></td>
   <!-- 
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)).',Proj'; ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');"><p>Projects</p></td>
      -->
   <td align="center" class="Tabs" onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)).',Inv'; ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');"><p>Invoices</p></td>
   <td align="center" class="Tabs" onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)).',Rept'; ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');"><p>Reports</p></td>
   <!-- 
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)).',Order'; ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');"><p>Service Orders</p></td>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)).',Contr'; ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');"><p>Contracts</p></td>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)).',Cont'; ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');"><p>Contacts</p></td>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)).',Bill'; ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');"><p>Billing</p></td>
    </tr>
    <tr>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)).',Ship'; ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');"><p>Shipping</p></td>
			-->
   <td align="center" class="Tabs" onclick="javascript:window.open('<? echo "/PhotoCP/cp.php?token=".session_id()."&admin=true&adminid=".$path[2]; ?>','','');
"><p>Launch Admin </p></td>
  </tr>
 </table>
</div>
<? } ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <? if($is_enabled){ ?>
 <tr>
  <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /></div>
   <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>Customer Information</p></th>
 </tr>
 <? } else { ?>
 <tr>
  <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,2)); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
   <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>Customer Information</p></th>
 </tr>
 <? } ?>
 <tr>
  <td width="20%"><strong>Name: </strong></td>
  <td width="80%"><? if(!$is_enabled){	echo $FName." ";
		if($MInt != "")echo $MInt.". ";
		echo $LName; } else { ?>
   <input type="text" name="First Name" id="First_Name" value="<? echo $FName;?>" maxlength="75" />
   &nbsp;
   <input type="text" name="Middle Initial" id="Middle_Initial" value="<? echo $MInt;?>" maxlength="1" size="5" />
   &nbsp;
   <input type="text" name="Last Name" id="Last_Name" value="<? echo $LName;?>" maxlength="75" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Company Name: </strong></td>
  <td><? if(!$is_enabled){ echo $CName; } else { ?>
   <input type="text" name="Company Name" id="Company_Name" value="<? echo $CName;?>" maxlength="100" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <? if(count($path) < 4){ /* ?>
  <tr>
    <td><strong>Description:</strong></td>
    <td><input type="hidden" name="Customer_Id" id="Customer_Id" value="<? echo $CId;?>" />
      &nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="padding:10px;<? if($is_enabled){ ?> height:300px;<? } ?>"  valign="top"><?
	 if(!$is_enabled){ echo $CDesc; } else {
		$oFCKeditor = new FCKeditor('Client_Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $CDesc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	} ?>
      &nbsp;</td>
  </tr>
	<? */ ?>
 <tr>
  <td><strong>Address:</strong></td>
  <td><? if(!$is_enabled){ echo $Add;
	 	if($SApt != "")	echo " Suite/Apt: ".$SApt;
		 } else { ?>
   <input name="Address" type="text" id="Address" value="<? echo $Add;?>" maxlength="125" />
   Suite/Apt
   <input type="text" name="Suite Apt" id="Suite_Apt" value="<? echo $SApt;?>" maxlength="25" size="10" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td><? if(!$is_enabled){ echo $Add2; } else { ?>
   <input type="text" name="Address 2" id="Address_2" value="<? echo $Add2;?>" maxlength="125" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td><? if(!$is_enabled){	echo $City.", ".$State.". ".$Zip; } else { ?>
   <input type="text" name="City" id="City" value="<? echo $City;?>" maxlength="125" />
   ,&nbsp;
   <select name="State" id="State">
    <? 
			$query_get_states = "SELECT `state_short` FROM `a_states` WHERE `country_id` = '225' GROUP BY `state_short` ORDER BY `state_short` ASC";
			$get_states = mysql_query($query_get_states, $cp_connection) or die(mysql_error());			
			while ($row_get_states = mysql_fetch_assoc($get_states)) { ?>
    <option value="<? echo $row_get_states['state_short']; ?>"<? if($State == $row_get_states['state_short']){print(" selected=\"selected\"");} ?>><? echo $row_get_states['state_short']; ?></option>
    <? } 
		  mysql_free_result($get_states); ?>
   </select>
   .
   <input name="Zip" type="text" id="Zip" tabindex="8" value="<? echo $Zip; ?>" size="10" maxlength="10">
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Country:</strong></td>
  <td><? if(!$is_enabled){	echo $Country; } else { ?>
   <input type="text" name="Country" id="Country" value="<? echo $Country;?>" maxlength="75" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Phone:</strong></td>
  <td><? if(!$is_enabled){ if($Phone != "0")echo "(".$P1.") ".$P2."-".$P3; } else { ?>
   (
   <input name="P1" type="text" id="P1" size="6" value="<? echo $P1;?>" maxlength="3" onkeyup="set_tel_number('Phone_Number','P');" />
   )
   <input name="P2" type="text" id="P2" size="6" value="<? echo $P2;?>" maxlength="3" onkeyup="set_tel_number('Phone_Number','P');" />
   -
   <input name="P3" type="text" id="P3" size="8" value="<? echo $P3;?>" maxlength="4" onkeyup="set_tel_number('Phone_Number','P');" />
   <? } ?>
   <input type="hidden" name="Phone Number" id="Phone_Number" value="<? echo $Phone;?>" />
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Cell:</strong></td>
  <td><? if(!$is_enabled){	if($Cell != "0") echo "(".$C1.") ".$C2."-".$C3; } else { ?>
   (
   <input name="C1" type="text" id="C1" size="6" value="<? echo $C1;?>" maxlength="3" onkeyup="set_tel_number('Cell_Number','C');" />
   )
   <input name="C2" type="text" id="C2" size="6" value="<? echo $C2;?>" maxlength="3" onkeyup="set_tel_number('Cell_Number','C');" />
   -
   <input name="C3" type="text" id="C3" size="8" value="<? echo $C3;?>" maxlength="4" onkeyup="set_tel_number('Cell_Number','C');" />
   <? } ?>
   <input type="hidden" name="Cell Number" id="Cell_Number" value="<? echo $Cell;?>" />
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Fax:</strong></td>
  <td><? if(!$is_enabled){	if($Fax != "0") echo "(".$F1.") ".$F2."-".$F3; } else { ?>
   (
   <input name="F1" type="text" id="F1" size="6" value="<? echo $F1;?>" maxlength="3" onkeyup="set_tel_number('Fax_Number','F');" />
   )
   <input name="F2" type="text" id="F2" size="6" value="<? echo $F2;?>" maxlength="3" onkeyup="set_tel_number('Fax_Number','F');" />
   -
   <input name="F3" type="text" id="F3" size="8" value="<? echo $F3;?>" maxlength="4" onkeyup="set_tel_number('Fax_Number','F');" />
   <? } ?>
   <input type="hidden" name="Fax Number" id="Fax_Number" value="<? echo $Fax;?>" />
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Work:</strong></td>
  <td><? if(!$is_enabled){	if($Work != "0") echo "(".$W1.") ".$W2."-".$W3; if($WExt != "0") echo "&nbsp;Ext. ".$WExt; } else { ?>
   (
   <input name="W1" type="text" id="W1" size="6" value="<? echo $W1;?>" maxlength="3" onkeyup="set_tel_number('Work_Number','W');" />
   )
   <input name="W2" type="text" id="W2" size="6" value="<? echo $W2;?>" maxlength="3" onkeyup="set_tel_number('Work_Number','W');" />
   -
   <input name="W3" type="text" id="W3" size="8" value="<? echo $W3;?>" maxlength="4" onkeyup="set_tel_number('Work_Number','W');" />
   &nbsp;Ext.
   <input name="Work Extension" type="text" id="Work_Extension" size="8" value="<? echo $WExt;?>" maxlength="10" />
   <? } ?>
   <input type="hidden" name="Work Number" id="Work_Number" value="<? echo $Work;?>" />
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>E-mail:</strong></td>
  <td><? if(!$is_enabled){	echo $Email; } else { ?>
   <input type="text" name="Email" id="Email" value="<? echo $Email;?>" maxlength="125" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Second E-mail: </strong></td>
  <td><? if(!$is_enabled){	echo $Email2; } else { ?>
   <input type="text" name="Email 2" id="Email_2" value="<? echo $Email2;?>" maxlength="125" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Website:</strong></td>
  <td><? if(!$is_enabled){	echo $Website; } else { ?>
   <input type="text" name="Website" id="Website" value="<? echo $Website;?>" maxlength="125" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Service Level:</strong></td>
  <td><? if(!$is_enabled){
			$query_get_service = "SELECT `prod_id`, `prod_name` FROM `prod_products` WHERE `prod_service` = 'y' AND `prod_recur` = 'y' AND `prod_use` = 'y' ORDER BY `prod_name` ASC";
			$get_service = mysql_query($query_get_service, $cp_connection) or die(mysql_error());
			while ($row_get_service = mysql_fetch_assoc($get_service)) { 
				if($ServLvl == $row_get_service['prod_id']){echo $row_get_service['prod_name']; break;}
			} } else { ?>
   <select name="Service Level" id="Service_Level" onchange="document.getElementById('Controller').value = 'Change'; this.form.submit();">
    <? 
			$query_get_service = "SELECT `prod_id`, `prod_name` FROM `prod_products` WHERE `prod_service` = 'y' AND `prod_recur` = 'y' AND `prod_use` = 'y' ORDER BY `prod_name` ASC";
			$get_service = mysql_query($query_get_service, $cp_connection) or die(mysql_error());
			while ($row_get_service = mysql_fetch_assoc($get_service)) { ?>
    <option value="<? echo $row_get_service['prod_id']; ?>"<? if($ServLvl == $row_get_service['prod_id']){print(" selected=\"selected\"");} ?>><? echo $row_get_service['prod_name']; ?></option>
    <? } 
		  mysql_free_result($get_service); ?>
   </select>
   <input type="hidden" name="Current Level" id="Current_Level" value="<? echo $ServLvl; ?>" />
   <? } ?></td>
 </tr>
 <tr>
  <td><strong>Revenue Share Percent:</strong> </td>
  <td><? if(!$is_enabled){	echo $Commission; } else { ?>
   <input type="text" name="Commission" id="Commission" value="<? echo $Commission;?>" />
   <? } ?>
   &nbsp;%</td>
 </tr>
 <tr>
  <td><strong>Disk Quota:</strong></td>
  <td><? if(!$is_enabled){	echo $Quota/1024; } else { ?>
   <input type="text" name="Disk Quota" id="Disk_Quota" value="<? echo ($Quota/1024);?>" />
   <? } ?>
   &nbsp;Gb</td>
 </tr>
 <tr>
  <td><strong>Monthly Fee: </strong></td>
  <td><? if(!$is_enabled){	echo $MFee; } else { ?>
   <input type="text" name="Monthly Fee" id="Monthly_Fee" value="<? echo $MFee;?>" />
   0 for default
   <? } ?></td>
 </tr>
 <tr>
  <td><strong>Thumbnail Image: </strong></td>
  <td><? if(!$is_enabled){ if($Thumb != ""){ ?>
   &nbsp;<a href="<? echo $Comp_Folder; ?>/<? echo $Thumb;?>" target="_blank">View</a>
   <? } } else { ?>
   <input type="file" name="Thumbnail" id="Thumbnail" />
   <input type="hidden" name="Thumbnail_val" id="Thumbnail_val" value="<? echo $Thumbv;?>">
   <? if($Thumb != ""){?>
   &nbsp;<a href="<? echo $Comp_Folder; ?>/<? echo $Thumb;?>" target="_blank">View</a>
   <? } ?>
   &nbsp;
   <input type="checkbox" name="Remove Thumbnail" id="Remove_Thumbnail" value="true" />
   Remove Thumbnail
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Client Image: </strong></td>
  <td><? if(!$is_enabled){ if($Image != ""){ ?>
   &nbsp;<a href="<? echo $Comp_Folder; ?>/<? echo $Image;?>" target="_blank">View</a>
   <? } } else { ?>
   <input type="file" name="Image" id="Image" />
   <input type="hidden" name="Image_val" id="Image_val" value="<? echo $Imagev;?>">
   <? if($Image != ""){?>
   &nbsp;<a href="<? echo $Comp_Folder; ?>/<? echo $Image;?>" target="_blank">View</a>
   <? } ?>
   &nbsp;
   <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" />
   Remove Image
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>IP Address: </strong></td>
  <td><? if(!$is_enabled){	echo $IP; } else { ?>
   <input type="text" name="IP Address" id="IP_Address" value="<? echo $IP;?>" maxlength="13" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Security Level: </strong></td>
  <td><? if(!$is_enabled){	echo($SecLevel == 0) ? "Administrator" : "User"; } else { ?>
   <input name="Security Level" type="radio" id="Security_Level" value="1"<? if($SecLevel == 1)print(' checked="checked"');?> />
   User
   <input type="radio" name="Security Level" id="Security_Level" value="0"<? if($SecLevel == 0)print(' checked="checked"');?> />
   Administrator
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Online Customer: </strong></td>
  <td><? if(!$is_enabled){	echo ($OCust == "y") ? "Yes" : "No"; } else { ?>
   <input type="radio" name="Online Customer" id="Online_Customer" value="y"<? if($OCust == "y")print(' checked="checked"');?> />
   Yes
   <input type="radio" name="Online Customer" id="Online_Customer" value="n"<? if($OCust == "n")print(' checked="checked"');?> />
   No
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Portfolio:</strong></td>
  <td><? if(!$is_enabled){	echo ($Port == "y") ? "Yes" : "No";	 } else { ?>
   <input type="radio" name="Portfolio" id="Portfolio" value="y"<? if($Port == "y")print(' checked="checked"');?> />
   Yes
   <input type="radio" name="Portfolio" id="Portfolio" value="n"<? if($Port == "n")print(' checked="checked"');?> />
   No
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Paid:</strong></td>
  <td><? if(!$is_enabled){	echo ($Paid == "y") ? "Yes" : "No";	 } else { ?>
   <input type="radio" name="Paid" id="Paid" value="y"<? if($Paid == "y")print(' checked="checked"');?> />
   Yes
   <input type="radio" name="Paid" id="Paid" value="n"<? if($Paid == "n")print(' checked="checked"');?> />
   No
   <? } ?>
   &nbsp;</td>
 </tr>
 <? } ?>
</table>
