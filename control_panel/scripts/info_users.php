<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <? if($is_enabled){  ?>
 <tr>
  <th colspan="5" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
   <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>User Information</p></th>
 </tr>
 <? } else { ?>
 <tr>
  <th colspan="5" id="Form_Header"><div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,2)); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
   <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>User Information</p></th>
 </tr>
 <? } 
  if($Error){
  	echo "<tr><td colspan=\"4\" style=\"background-color:#FFFFFF; color:#CC0000;\">".$Error."</td></tr>";
  }
  ?>
 <tr>
  <td colspan="2"><strong>Username: </strong></td>
  <td colspan="3"><? if(!$is_enabled){	echo $UName; } else { ?>
   <input type="text" name="Username" id="Username" maxlength="75" value="<? echo $UName; ?>" />
   <? } ?></td>
 </tr>
 <tr>
  <td colspan="2"><strong>Password:</strong></td>
  <td colspan="3"><? if(!$is_enabled){	echo "**********";	} else { ?>
   <input type="password" name="Password" id="Password" maxlength="75" />
   <? } ?>
   <input type="hidden" name="User_Id" id="User_Id" value="<? echo $UId;?>" />
   &nbsp;</td>
 </tr>
 <? if($is_enabled){ ?>
 <tr>
  <td colspan="2"><strong>Confirm Password: </strong></td>
  <td colspan="3"><input type="password" name="Confirm Password" id="Confirm_Password" maxlength="75" />
   &nbsp; </td>
 </tr>
 <? } if($loginsession[1] <= 1){ ?>
 <tr>
  <td colspan="2"><strong>User Type: </strong></td>
  <td colspan="3"><? if(!$is_enabled){
		switch($UserLvl){
			case 1;
				echo "Administrator";
				break;
			case 2;
				echo "User";
				break;
		}
	} else { 
	  $sel_val = $UserLvl; ?>
   <select name="User Type" id="User_Type">
    <option value="1"<? if($sel_val == "1")echo ' selected="selected"'; ?>>Administrator</option>
    <option value="2"<? if($sel_val == "2")echo ' selected="selected"'; ?>>User</option>
   </select>
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td colspan="2"><strong>Access:</strong></td>
  <td colspan="3">&nbsp;</td>
 </tr>
 <? } $ControlCodes = $loginsession[2];
 	foreach($navcodes as $k => $v){
 	if(isset($ControlCodes[$k]) && $ControlCodes[$k]['Active'] == 1){?>
 <tr>
  <td colspan="5"><hr size="1" color="#000000" /></td>
 </tr>
 <tr>
  <td valign="top" colspan="5"><? if(!$is_enabled) { echo ($Usercodes[$k]['Active'] == 1) ? "X&nbsp;" : "&nbsp;&nbsp;"; } else { ?>
   <input type="checkbox" name="Selection[]" id="Selection[]" value="<? echo $k; ?>"<? if($Usercodes[$k]['Active'] == 1) echo ' checked="checked"'; ?> />
   <? } ?>
   <strong><? echo $v['Name']; ?></strong></td>
 </tr>
 <? if(count($v['Sub']) > 0){
 		$n = 0;
		echo '<tr>';
		foreach($v['Sub'] as $k2 => $v2){ 
			if(isset($ControlCodes[$k]['Sub'][$k2]) && $ControlCodes[$k]['Sub'][$k2]['Active'] == 1) { ?>
    <td valign="top">
   <? if(!$is_enabled) { echo ($Usercodes[$k]['Sub'][$k2]['Active'] == 1) ? "X&nbsp;" : "&nbsp;&nbsp;"; } else { ?>
   <input type="checkbox" name="Selection_<? echo $k; ?>[]" id="Selection_<? echo $k; ?>[]" value="<? echo $k2; ?>"<? if($Usercodes[$k]['Sub'][$k2]['Active'] == 1) echo ' checked="checked"'; ?> />
   <? } echo $v2['Name']; ?></td>
			<? $n++; }
			if($n == 4) { echo "</tr><tr>"; $n = 0; }
		}
		if($n > 0){ while($n < 4){ echo "<td>&nbsp;</td>"; $n++; }	echo '</tr>';	}
 } } } ?>
</table>
