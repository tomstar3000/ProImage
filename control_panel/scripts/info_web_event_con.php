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
   <p>Event Contact Information</p></th>
 </tr>
 <? } else { ?>
 <tr>
  <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,2)); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
   <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>Event Contact Information</p></th>
 </tr>
 <? } ?>
 <tr>
  <td width="20%"><strong>Name: </strong></td>
  <td width="80%"><? if(!$is_enabled){ echo $FName." ".$LName; } else { ?>
   <input type="text" name="First Name" id="First_Name" value="<? echo $FName;?>" maxlength="75" />
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
   <input type="hidden" name="Fax_Number" id="Fax_Number" value="<? echo $Fax;?>" />
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>E-mail:</strong></td>
  <td><? if(!$is_enabled){	echo $Email; } else { ?>
   <input type="text" name="Email" id="Email" value="<? echo $Email;?>" maxlength="125" />
   <? } ?>
   &nbsp;</td>
 </tr>
</table>
