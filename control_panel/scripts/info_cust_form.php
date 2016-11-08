<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
include $r_path.'scripts/save_cust_form.php';
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <? if($is_enabled){ ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /></div>
      <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Custom Form Information</p></th>
  </tr>
  <? } else { ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,2)); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
      <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Custom Form  Information</p></th>
  </tr>
  <? } ?>
  <tr>
    <td width="20%">Name: </td>
    <td width="80%"><? if(!$is_enabled){ echo $FName; } else { ?>
      <input type="text" name="Name" id="Name" maxlength="75" value="<? echo $FName; ?>" />
      <? } ?>
    &nbsp;</td>
  </tr>
  <tr>
    <td>Store Information : </td>
    <td><? if(!$is_enabled){ if($FStore=="y")	print("Yes"); } else { ?>
      <input type="radio" name="Store" id="Store" value="y"<? if($FStore=="y"){print(" checked=\"checked\"");} ?> />
      Yes
      <input type="radio" name="Store" id="Store" value="n"<? if($FStore=="n"){print(" checked=\"checked\"");} ?> />
      No
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>E-mail Results to : </td>
    <td><? if(!$is_enabled){ echo $FEmail; } else { ?>
      <input type="text" name="Email" id="Email" maxlength="75" value="<? echo $FEmail; ?>" />
      <? } ?>
&nbsp;</td>
  </tr>
  <tr>
    <td>Display Message: </td>
    <td><? if(!$is_enabled){ echo $Message; } else { ?>
      <input type="text" name="Message" id="Message" maxlength="75" value="<? echo $Message; ?>" />
      <? } ?>
&nbsp;</td>
  </tr>
  <tr>
    <td>Submit Button Text: </td>
    <td><? if(!$is_enabled){ echo $SText; } else { ?>
      <input type="text" name="Submit Text" id="Submit_Text" maxlength="75" value="<? echo $SText; ?>" />
      <? } ?>
&nbsp;</td>
  </tr>
  <tr>
    <td>Cancle Button Text: </td>
    <td><? if(!$is_enabled){ echo $CText; } else { ?>
      <input type="text" name="Cancle Text" id="Cancle_Text" maxlength="75" value="<? echo $CText; ?>" />
      <? } ?>
&nbsp;</td>
  </tr>
  <tr>
    <td>Reset Form Button </td>
    <td><? if(!$is_enabled){ if($RBtn=="y")	print("Yes"); } else { ?>
      <input type="radio" name="Reset Button" id="Reset_Button" value="y"<? if($RBtn=="y"){print(" checked=\"checked\"");} ?> />
Yes
<input type="radio" name="Reset Button" id="Reset_Button" value="n"<? if($RBtn=="n"){print(" checked=\"checked\"");} ?> />
No
<? } ?></td>
  </tr>
</table>
