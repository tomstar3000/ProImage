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
  <? if($is_enabled){ ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /></div>
      <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Employee Information</p></th>
  </tr>
  <? } else { ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,2)); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
      <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Employee Information</p></th>
  </tr>
  <? } 
	if($Image === false || $Thumb === false){
  	echo "<tr><td colspan=\"4\" style=\"background-color:#FFFFFF; color:#CC0000;\">".$Image."<br />".$Thumb."<br />".$Icon."</td></tr>";
  }?>
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
    <td><strong>Title:</strong></td>
    <td><? if(!$is_enabled){ echo $Title; } else { ?>
      <input type="text" name="Title" id="Title" value="<? echo $Title;?>" maxlength="100" />
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Project Worktype :</strong></td>
    <td><? $query_get_cats = "SELECT * FROM `emp_types` ORDER BY `emp_type_name` ASC";
			$get_cats = mysql_query($query_get_cats, $cp_connection) or die(mysql_error());	
			if(!$is_enabled){ 
				while ($row_get_cats = mysql_fetch_assoc($get_cats)) { 
					if($row_get_cats['emp_type_id'] == $WorkType) {echo $row_get_cats['emp_type_name']; break;}
				}
			} else { ?>
      <select name="Project Worktype" id="Project_Worktype">
        <? while ($row_get_cats = mysql_fetch_assoc($get_cats)) {  ?>
        <option value="<? echo $row_get_cats['emp_type_id']; ?>"<? if($row_get_cats['emp_type_id'] == $WorkType) echo ' selected="selected"'; ?>><? echo $row_get_cats['emp_type_name']; ?></option>
        <? } ?>
      </select>
      <? } ?>
      &nbsp;</td>
  </tr>
  <? if(count($path) < 4){ ?>
  <tr>
    <td><strong>Bio:</strong></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="padding:10px;<? if($is_enabled){ ?> height:300px;<? } ?>"  valign="top"><?
	 if(!$is_enabled){ echo $EDesc; } else {
		$oFCKeditor = new FCKeditor('Employee_Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $EDesc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	} ?>
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
			$query_get_states = "SELECT `state_short` FROM `a_states` ORDER BY `state_short` ASC";
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
    <td><strong>E-mail:</strong></td>
    <td><? if(!$is_enabled){	echo $Email; } else { ?>
      <input type="text" name="Email" id="Email" value="<? echo $Email;?>" maxlength="125" />
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Thumbnail Image: </strong></td>
    <td><? if(!$is_enabled){ if($Thumb != ""){ ?>
      &nbsp;<a href="<? echo $Emp_Folder; ?>/<? echo $Thumb;?>" target="_blank">View</a>
      <? } } else { ?>
      <input type="file" name="Thumbnail" id="Thumbnail" />
      <input type="hidden" name="Thumbnail_val" id="Thumbnail_val" value="<? echo $Thumbv;?>">
      <? if($Thumb != ""){?>
      &nbsp;<a href="<? echo $Emp_Folder; ?>/<? echo $Thumb;?>" target="_blank">View</a>
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
      &nbsp;<a href="<? echo $Emp_Folder; ?>/<? echo $Image;?>" target="_blank">View</a>
      <? } } else { ?>
      <input type="file" name="Image" id="Image" />
      <input type="hidden" name="Image_val" id="Image_val" value="<? echo $Imagev;?>">
      <? if($Image != ""){?>
      &nbsp;<a href="<? echo $Emp_Folder; ?>/<? echo $Image;?>" target="_blank">View</a>
      <? } ?>
      &nbsp;
      <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" />
      Remove Image
      <? } ?>
      &nbsp;</td>
  </tr>
  <? } ?>
</table>
