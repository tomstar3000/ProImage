<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <? if($is_enabled){  ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
      <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Career Information</p></th>
  </tr>
  <? } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,2)); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
      <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Career Information</p></th>
  </tr>
  <? } 
	if($Image === false || $White === false){
  	echo "<tr><td colspan=\"4\" style=\"background-color:#FFFFFF; color:#CC0000;\">".$Image."<br />".$White."</td></tr>";
  }
	?>
  <tr>
    <td width="20%"><strong>Career Name: </strong></td>
    <td><? if(!$is_enabled){echo $Name; } else { ?>
      <input type="text" name="Name" id="Name" maxlength="75" value="<? echo $Name; ?>" />
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Career Number: </strong></td>
    <td><? if(!$is_enabled){ echo $Number; } else { ?>
      <input type="text" name="Number" id="Number" maxlength="50" value="<? echo $Number; ?>" />
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Start Date:</strong> </td>
    <td><? if(!$is_enabled){echo format_date($Date,"DayShort",false,true,true);} else { ?>
      <input type="text" name="Start Date" id="Start_Date" maxlength="75" value="<? echo $Date; ?>" />
      <a href="#" onclick="newwindow=window.open('scripts/calendar.php?future=true&amp;time=true&amp;field=Start_Date','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;"> Select
      Date</a> (yyyy-mm-dd hh:mm:ss)
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><strong>Description:</strong></td>
  </tr>
  <tr>
    <td colspan="2" style="padding:10px;<? if($cont == "add" || $cont == "edit"){ ?> height:300px;<? } ?>" valign="top"><? if(!$is_enabled){ echo $Desc; } else {
		$oFCKeditor = new FCKeditor('Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $Desc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	} ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Career Image:</strong></td>
    <td><? if($cont == "add" || $cont == "edit"){ ?>
      <input type="file" name="Image" id="Image">
      <input type="hidden" name="Image_val" id="Image_val" value="<? echo $Imagev;?>">
      <? }
	  if($Imagev != ""){?>
      &nbsp;<a href="<? echo $Car_Folder; ?>/<? echo $Imagev;?>" target="_blank">View</a>
      <? } 
	  if($cont == "add" || $cont == "edit"){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" />
      Remove Image
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Career Whitepaper:</strong></td>
    <td><? if($cont == "add" || $cont == "edit"){ ?>
      <input type="file" name="Whitepaper" id="Whitepaper" />
      <input type="hidden" name="Whitepaper_val" id="Whitepaper_val" value="<? echo $Whitev;?>" />
      <input type="hidden" name="Whitepaper_name" id="Whitepaper_name" value="<? echo $Whiten;?>" />
      <? } if($Whitev != ""){?>
      &nbsp;<a href="<? echo $Prod_White_Folder; ?>/<? echo $Whitev;?>" target="_blank">View</a>
      <? } if($cont == "add" || $cont == "edit"){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Whitepaper" id="Remove_Whitepaper" value="true" />
      Remove Whitepaper
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Career Active: </strong></td>
    <td><? if(!$is_enabled){	echo ($Active=="y") ? "Yes" : "No"; } else { ?>
      <input type="radio" name="Active" id="Active" value="y"<? if($Active=="y"){print(" checked=\"checked\"");} ?> />
      Yes
      <input type="radio" name="Active" id="Active" value="n"<? if($Active=="n"){print(" checked=\"checked\"");} ?> />
      No
      <? } ?>
      &nbsp;</td>
  </tr>
  <? 
	$query_get_type = "SELECT * FROM `web_career_req_type` ORDER BY `career_req_type_name` ASC";
	$get_type = mysql_query($query_get_type, $cp_connection) or die(mysql_error());
	$n=0;
	while($row_get_type = mysql_fetch_assoc($get_type)){ ?>
  <tr>
    <td colspan="2"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="2"><strong><? echo $row_get_type['career_req_type_name']; ?></strong></td>
  </tr>
  <tr>
    <td colspan="2"><hr size="1" /></td>
  </tr>
  <?
  foreach($CRId as $k => $v){
  	if($CRType[$k] == $row_get_type['career_req_type_id']){ $n++; ?>
  <tr>
    <td><strong>Requirement:</strong> </td>
    <td><? if(!$is_enabled){ 
		switch($CRReq[$k]){
			case "y":
				echo "Required<br />";
				break;
			default:
				echo "Preferred<br />";
				break;
		}
		echo $CRDesc[$k]; } else { ?>
      <div style="float:left;">
        <input type="radio" name="Requirment Required_<? echo $n; ?>" id="Requirment_Required_<? echo $n; ?>" value="y"<? if($CRReq[$k]=="y" || $CRReq[$k]=="")echo ' checked="checked"'; ?> /> Required
        <input type="radio" name="Requirment Required_<? echo $n; ?>" id="Requirment_Required_<? echo $n; ?>" value="n"<? if($CRReq[$k]=="n")echo ' checked="checked"'; ?> /> Preferred
        <br />
        <input type="text" name="Requirment Description[]" id="Requirment_Description[]" style="width:300px;" value="<? echo $CRDesc[$k]; ?>" />
        <input type="hidden" name="Requirment Ids[]" id="Requirment_Ids[]" value="<? echo $v; ?>" />
        <input type="hidden" name="Requirment Type[]" id="Requirment_Type[]" value="<? echo $CRType[$k]; ?>" />
      </div>
      <? if (count($CRId)>1){ ?>
      <div style="float:right">
        <input type="button" name="Remove_Requirement" id="Remove_Requirement" value="Remove Requirement" onclick="document.getElementById('Controller').value='remove_block'; document.getElementById('Rmv_Id').value='<? echo ($n-1); ?>'; document.getElementById('form_action_form').submit();" />
      </div>
      <? } } ?>
      &nbsp; </td>
  </tr>
  <? } } if($is_enabled){ ?>
  <tr>
    <td colspan="2" align="right"><input type="button" name="Add_Requirement" id="Add_Requirement" value="Add Requirement" onclick="document.getElementById('new_type').value='<? echo $row_get_type['career_req_type_id']; ?>'; document.getElementById('Controller').value='new_block'; document.getElementById('form_action_form').submit();" /></td>
  </tr>
  <? } } ?>
  <input type="hidden" name="new_type" id="new_type" value="" />
  <input type="hidden" name="Rmv_Id" id="Rmv_Id" value="" />
</table>
