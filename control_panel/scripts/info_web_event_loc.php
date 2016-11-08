<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$temppathing = $path[0].",".$path[1];
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <? if($is_enabled){ ?>
 <tr>
  <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /></div>
   <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>Event Location Information</p></th>
 </tr>
 <? } else { ?>
 <tr>
  <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,2)); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
   <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>Event Location Information</p></th>
 </tr>
 <? } ?>
 <tr>
  <td width="20%"><strong>Name: </strong></td>
  <td width="80%"><? if(!$is_enabled){ echo $Name; } else { ?>
   <input type="text" name="Name" id="Name" value="<? echo $Name;?>" maxlength="75" />
   &nbsp;
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
  <td><? if(!$is_enabled){	echo $City.", ".$State.". ".$Zip; } else { ?>
   <input type="text" name="City" id="City" value="<? echo $City;?>" maxlength="125" />
   ,&nbsp;
   <select name="State" id="State">
    <? 
			$query_get_states = "SELECT `state_short` FROM `a_states` WHERE `country_id` = '225' ORDER BY `state_short` ASC";
			$get_states = mysql_query($query_get_states, $cp_connection) or die(mysql_error());			
			while ($row_get_states = mysql_fetch_assoc($get_states)) { ?>
    <option value="<? echo $row_get_states['state_short']; ?>"<? if($State == $row_get_states['state_short']){print(" selected=\"selected\"");} ?>><? echo $row_get_states['state_short']; ?></option>
    <? } 
		  mysql_free_result($get_states); ?>
   </select>
   .
   <input name="Zip" type="text" id="Zip" tabindex="8" value="<? echo $Zip; ?>" size="10" maxlength="10" />
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
</table>
