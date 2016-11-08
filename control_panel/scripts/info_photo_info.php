<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
//$required_string = "'Name','','R','Code','','R','Date','','R','End_Date','','R'";
if((count($path)<=3 && $cont == "view") || (count($path)>3)){
	$is_enabled = false;
} else {
	$is_enabled = true;
} 
if($is_enabled){ ?>
<script type="text/javascript">
	function change_state(prefix,value){
		if(value == "USA"){
			document.getElementById(prefix+'State_Text').style.display='none';
			document.getElementById(prefix+'State_Select').style.display='block';
			document.getElementById(prefix+'State_Text').getElementsByTagName('input')[0].disabled=true;
			document.getElementById(prefix+'State_Select').getElementsByTagName('select')[0].disabled=false;
		} else {
			document.getElementById(prefix+'State_Text').style.display='block';
			document.getElementById(prefix+'State_Select').style.display='none';
			document.getElementById(prefix+'State_Text').getElementsByTagName('input')[0].disabled=false;
			document.getElementById(prefix+'State_Select').getElementsByTagName('select')[0].disabled=true;
		}
	}
	</script>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Personal Information </h2>
 <p id="Back"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');">Back</a></p>
</div>
<? } else { ?>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Personal Information</h2>
 <p id="Edit"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');">Edit</a></p>
</div>
<? } ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <? if(isset($Error)){ ?>
 <tr>
  <td colspan="2" style=" padding:5px; border:#CC0000 1px solid; background:#FFFFFF; color:#CC0000;"><img src="/images/warning.jpg" width="30" height="29" border="0" align="absmiddle" style="float:left" />
   <div style="float:left; margin:8px;"><? echo $Error; ?></div>
   <img src="/images/warning.jpg" width="30" height="29" border="0" align="absmiddle"  style="float:right" /></td>
  </td>
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
  <td><? if(!$is_enabled){echo $City." ".$State." ".$Zip;} else { ?>
   <div style="float:left;">
    <input name="City" type="text" id="City" value="<? echo $City; ?>" />
   </div>
   <div style="float:left; <? if($Country!="USA")print(" display:none;"); ?>" id="Billing_State_Select">
    <? $query_get_states = "SELECT `state_short` FROM `a_states` WHERE `country_id` = '225' ORDER BY `state_short` ASC";
					$get_states = mysql_query($query_get_states, $cp_connection) or die(mysql_error());?>
    <select name="State" id="State"<? if($Country!="USA")print(" disabled=\"disabled\""); ?>>
     <? while($row_get_states = mysql_fetch_assoc($get_states)){ ?>
     <option value="<? echo $row_get_states['state_short'] ?>"<? if($State == $row_get_states['state_short'])print(' selected="selected"');?>><? echo $row_get_states['state_short'] ?></option>
     <? } ?>
    </select>
    <? mysql_free_result($get_states); ?>
   </div>
   <div style="float:left;<? if($Country=="USA")print(" display:none;"); ?>" id="Billing_State_Text">
    <input name="State" type="text" id="State" value="<? echo $State;?>" size="3" maxlength="3"<? if($Country=="USA")print(" disabled=\"disabled\""); ?> />
   </div>
   <div style="float:left;">
    <input name="Zip" type="text" id="Zip" value="<? echo $Zip; ?>" />
   </div>
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td>Country:</td>
  <td><? $query_get_country = "SELECT `country_short_3`, `country_name` FROM `a_country` WHERE `country_use` = 'y' ORDER BY `country_name` ASC";
	$get_country = mysql_query($query_get_country, $cp_connection) or die(mysql_error()); ?>
   <? if(!$is_enabled){echo $Country;} else { ?>
   <select name="Country" id="Country" onchange="javascript:change_state('Billing_',this.value);">
    <option value="0"> -- Select Country -- </option>
    <? while($row_get_country = mysql_fetch_assoc($get_country)){ ?>
    <option value="<? echo $row_get_country['country_short_3']; ?>"<? if($Country == $row_get_country['country_short_3'])print(' selected="selected"');?>><? echo $row_get_country['country_name']; ?></option>
    <? } ?>
   </select>
   <? } ?></td>
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
  <td><strong>Website:</strong></td>
  <td><? if(!$is_enabled){	echo $Website; } else { ?>
   <input type="text" name="Website" id="Website" value="<? echo $Website;?>" maxlength="125" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Username:</strong></td>
  <td><? if(!$is_enabled){ echo $UName; } else { ?>
   <input type="text" name="Username" id="Username" maxlength="75" value="<? echo $UName; ?>" />
   <? } ?></td>
 </tr>
 <tr>
  <td><strong>Password:</strong></td>
  <td><? if(!$is_enabled){ echo "**********"; } else { ?>
   <input type="password" name="Password" id="Password" maxlength="75" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <? if($is_enabled){ ?>
 <tr>
  <td><strong>Confirm Password:</strong></td>
  <td><input type="password" name="Confirm Password" id="Confirm_Password" maxlength="75" />
   &nbsp; </td>
 </tr>
 <? include ($r_path.'../scripts/security_questions.php'); ?>
 <tr>
  <td><strong>Security Question: </strong></td>
  <td><select name="Security Question" id="Security_Question">
    <? while ($row_get_sec_quest = mysql_fetch_assoc($get_sec_quest)){ ?>
    <option value="<? echo $row_get_sec_quest['question_id']; ?>"<? if($SQId ==$row_get_sec_quest['question_id']) echo ' selected="selected"'; ?> ><? echo $row_get_sec_quest['question']; ?></option>
    <? } ?>
   </select></td>
 </tr>
 <tr>
  <td><strong>Answer:</strong></td>
  <td><input type="text" name="Answer" id="Answer" value="<? echo $SQAnw; ?>"></td>
 </tr>
 <? } ?>
</table>
