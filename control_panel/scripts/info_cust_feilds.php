<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>
<script type="text/javascript">
function additem(){
	count = document.getElementById('Option_Count').value+1;
	newinput = document.createElement('div');
	newinput.setAttribute('id','Option_'+count);
	newinput.innerHTML = '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><img src="/<? echo $AevNet_Path; ?>/images/remove.jpg" width="72" height="33" alt="remove" onclick="removeitem(\'Option_'+count+'\');" /></td><td><strong>Value:</strong></td><td><input type="text" name="Option Value[]" id="Option_Value[]" maxlength="75" value=""><input type="hidden" name="Option Id[]" id="Option_Id[]" value="-1"></td><td><strong>Text:</strong></td><td><input type="text" name="Option Text[]" id="Option_Text[]" value="" /></td></tr></table>';
	document.getElementById('Options').appendChild(newinput);
	document.getElementById('Option_Count').value = count;
}
function removeitem(id){
	document.getElementById(id).style.display = "none";
	document.getElementById(id).getElementsByTagName('input')[0].disabled = true;
	document.getElementById(id).getElementsByTagName('input')[1].disabled = true;
}
</script>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <? if($is_enabled){ ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');" /></div>
      <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Custom Form Field </p></th>
  </tr>
  <? } else { ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
      <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Custom Form Field </p></th>
  </tr>
  <? } ?>
  <tr>
    <td width="20%">Field Name: </td>
    <td width="80%"><? if(!$is_enabled){ echo $FName; } else { ?>
      <input type="text" name="Name" id="Name" maxlength="100" value="<? echo $FName; ?>" />
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>Field Tag: </td>
    <td><? if(!$is_enabled){ echo $Tag; } else { ?>
      <input type="text" name="Tag" id="Tag" maxlength="75" value="<? echo $Tag; ?>" />
      <? } ?>
&nbsp;</td>
  </tr>
  <tr>
    <td>Field Type: </td>
    <td><? if(!$is_enabled){
		switch($FField){
			case "1":
				echo "Text Feild";
				break;
			case "2":
				echo "Hidden Field";
				break;
			case "3":
				echo "Text Area";
				break;
			case "4":
				echo "Selection Group";
				break;
			case "5":
				echo "Checkbox Group";
				break;
			case "6":
				echo "Radio Group";
				break;
		}	} else { ?>
      <select name="Field Type" id="Field_Type">
        <option value="1"<? if($FField=="1")echo ' selected="selected"'; ?>>Text
        Field</option>
        <option value="2"<? if($FField=="2")echo ' selected="selected"'; ?>>Hidden Field</option>
        <option value="3"<? if($FField=="3")echo ' selected="selected"'; ?>>Text Area</option>
        <option value="4"<? if($FField=="4")echo ' selected="selected"'; ?>>Selection Group</option>
        <option value="5"<? if($FField=="5")echo ' selected="selected"'; ?>>Checkbox Group</option>
        <option value="6"<? if($FField=="6")echo ' selected="selected"'; ?>>Radio Group</option>
      </select>
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>Field Max Characters: </td>
    <td><? if(!$is_enabled){ echo $FMChar; } else { ?>
      <input type="text" name="Max Characters" id="Max_Characters" value="<? echo $FMChar; ?>" />
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>Rows:</td>
    <td><? if(!$is_enabled){ echo $FRows; } else { ?>
      <input type="text" name="Rows" id="Rows" value="<? echo $FRows; ?>" />
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>Columns:</td>
    <td><? if(!$is_enabled){ echo $FCol; } else { ?>
      <input type="text" name="Columns" id="Columns" value="<? echo $FCol; ?>" />
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>Required Field:</td>
    <td><? if(!$is_enabled){ echo ($FReq=="n")?"No":"Yes";} else { ?>
      <input type="radio" name="Required" id="Required" value="n"<? if($FReq=="n")echo ' checked="checked"';?> />
      No
      <input type="radio" name="Required" id="Required" value="y"<? if($FReq=="y")echo ' checked="checked"';?> />
      Yes
      <? } ?></td>
  </tr>
  <tr>
    <td>Field Restrictions: </td>
    <td><? if(!$is_enabled){ 
		switch($FRest){
			case "n":
				echo "None";
				break;
			case "y":
				echo "Number";
				break;
			case "e":
				echo "E-mail";
				break;
		} } else { ?>
      <input type="radio" name="Restrictions" id="Restrictions" value="n"<? if($FRest=="n")echo ' checked="checked"';?> />
      None
      <input type="radio" name="Restrictions" id="Restrictions" value="y"<? if($FRest=="y")echo ' checked="checked"';?> />
      Number
      <input type="radio" name="Restrictions" id="Restrictions" value="e"<? if($FRest=="e")echo ' checked="checked"';?> />
      E-mail
      <? } ?></td>
  </tr>
  <tr>
    <td>Minimum Number Value: </td>
    <td><? if(!$is_enabled){ echo $FMin; } else { ?>
      <input type="text" name="Minimum Value" id="Minimum_Value" value="<? echo $FMin; ?>" />
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>Maximum Number Value: </td>
    <td><? if(!$is_enabled){ echo $FMax; } else { ?>
      <input type="text" name="Maximum Number" id="Maximum_Number" value="<? echo $FMax; ?>" />
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>State List:</td>
    <td><? if(!$is_enabled){ echo ($State=="n")?"No":"Yes";} else { ?>
      <input type="radio" name="State" id="State" value="n"<? if($State=="n")echo ' checked="checked"';?> />
No
<input type="radio" name="State" id="State" value="y"<? if($State=="y")echo ' checked="checked"';?> />
Yes
<? } ?></td>
  </tr>
  <tr>
    <td>Country List:</td>
    <td><? if(!$is_enabled){ echo ($Country=="n")?"No":"Yes";} else { ?>
      <input type="radio" name="Country" id="Country" value="n"<? if($Country=="n")echo ' checked="checked"';?> />
No
<input type="radio" name="Country" id="Country" value="y"<? if($Country=="y")echo ' checked="checked"';?> />
Yes
<? } ?></td>
  </tr>
</table>
<div id="Form_Header">
  <? if($is_enabled){ ?>
  <div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:additem();" /> </div>
  <? } ?>
  <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Feild Answers </p>
</div>
<div id="Options">
  <? foreach($OId as $k => $v){ ?>
  <div id="Option_<? echo $k+1; ?>">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <? if($is_enabled){ ?>
        <td width="10%"><img src="/<? echo $AevNet_Path; ?>/images/remove.jpg" width="72" height="33" alt="remove" style="cursor:pointer" onclick="removeitem('Option_<? echo $k+1; ?>');" /></td>
        <? } ?>
        <td width="10%"><strong>Value:</strong></td>
        <td width="35%"><? if(!$is_enabled){ echo $OValue[$k]; } else { ?>
          <input type="text" name="Option Value[]" id="Option_Value[]" maxlength="75" value="<? echo $OValue[$k]; ?>">
          <input type="hidden" name="Option Id[]" id="Option_Id[]" value="<? echo $v; ?>">
        <? } ?></td>
        <td width="10%"><strong>Text:</strong></td>
        <td width="35%"><? if(!$is_enabled){ echo $OText[$k]; } else { ?>
          <input type="text" name="Option Text[]" id="Option_Text[]" value="<? echo $OText[$k]; ?>" />
        <? } ?></td>
      </tr>
    </table>
  </div>
  <? } ?>
</div>
<input type="hidden" name="Option_Count" id="Option_Count" value="<? echo $k+1; ?>" />
<br clear="all" />
