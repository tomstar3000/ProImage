<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)	$r_path .= "../";
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
  <h2>Billing Information </h2>
  <p id="Back"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');">Back</a></p>
</div>
<? } else { ?>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
  <h2>Billing Information</h2>
  <p id="Edit"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');">Edit</a></p>
</div>
<? } ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <? if(isset($Error)) echo '<tr><td colspan="2" style="background-color:#FFFFFF; color:#CC0000;">'.$Error.'</td></tr>'; ?>
  <tr>
    <td>Name:</td>
    <td><? if(!$is_enabled){ echo $FName." ".$MInt." ".$LName;
		switch($Suffix){
			case 0:
				echo "&nbsp;";
				break;
			case 1:
				echo "Sr.";
				break;
			case 2:
				echo "Jr.";
				break;
			case 3:
				echo "II";
				break;
			case 4:
				echo "III";
				break;
			case 5:
				echo "IV";
				break;
			case 6:
				echo "V";
				break;		
		} } else { ?>
      <input type="text" name="First Name" id="First_Name" value="<? echo $FName; ?>">
      <input type="text" name="Middle Initial" id="Middle_Initial" size="3" value="<? echo $MInt; ?>" />
      <input type="text" name="Last Name" id="Last_Name" value="<? echo $LName; ?>" />
      <select name="Suffix" id="Suffix">
        <option value="0"<? if($Suffix == "0"){print(' selected="selected"');}?>>&nbsp;</option>
        <option value="1"<? if($Suffix == "1"){print(' selected="selected"');}?>>Sr.</option>
        <option value="2"<? if($Suffix == "2"){print(' selected="selected"');}?>>Jr.</option>
        <option value="3"<? if($Suffix == "3"){print(' selected="selected"');}?>>II</option>
        <option value="4"<? if($Suffix == "4"){print(' selected="selected"');}?>>III</option>
        <option value="5"<? if($Suffix == "5"){print(' selected="selected"');}?>>IV</option>
        <option value="6"<? if($Suffix == "6"){print(' selected="selected"');}?>>V</option>
      </select>
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>Company Name: </td>
    <td><? if(!$is_enabled){ echo $CName; } else { ?>
      <input type="text" name="Company Name" id="Company_Name" value="<? echo $CName; ?>" />
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>Address:</td>
    <td><? if(!$is_enabled){echo $Add.(($SuiteApt!="")?" Apt/Suite: ".$SApt : '');} else { ?>
      <input name="Address" type="text" id="Address" value="<? echo $Add; ?>" />
      Apt/Suite
      <input type="text" name="Suite Apt" id="Suite_Apt" value="<? echo $SApt; ?>" />
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><? if(!$is_enabled){echo $Add2;} else { ?>
      <input name="Address 2" type="text" id="Address_2" value="<? echo $Add2; ?>" />
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
    <td>Card Type: </td>
    <td><? $query_get_cards = "SELECT * FROM `billship_cc_types` WHERE `cc_accept` = 'y' ORDER BY `cc_order` ASC";
	$get_cards = mysql_query($query_get_cards, $cp_connection) or die(mysql_error());
	$row_get_cards = mysql_fetch_assoc($get_cards);
	$totalRows_get_cards = mysql_num_rows($get_cards);
	if(!$is_enabled){do{if($CType == $row_get_cards['cc_type_id']){echo $row_get_cards['cc_type_name']; break; }} while($row_get_cards = mysql_fetch_assoc($get_cards));} else { ?>
      <select name="Type of Card" id="Type_of_Card">
        <? do{ ?>
        <option value="<? echo $row_get_cards['cc_type_id']; ?>"<? if($CType == $row_get_cards['cc_type_id'])print(' selected="selected"');?>><? echo $row_get_cards['cc_type_name']; ?></option>
        <? } while($row_get_cards = mysql_fetch_assoc($get_cards)); ?>
      </select>
      <? } ?></td>
  </tr>
  <tr>
    <td>Credit Card Number:</td>
    <td><? if(!$is_enabled){	echo "xxxx-xxxx-xxxx-".$CC4Num; } else { ?>
      <input type="text" name="Credit Card Number" id="Credit_Card_Number" maxlength="16" value="">
      <? echo "xxxx-xxxx-xxxx-".$CC4Num; } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>CCV Code: </td>
    <td><? if(!$is_enabled){echo $CCV;} else { ?>
      <input type="text" name="CCV Code" id="CCV_Code" value="<? echo $CCV; ?>">
      <? } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>Experation: </td>
    <td><? if(!$is_enabled){echo $CCM." / ".$CCY;} else { ?>
      Month:
      <select name="Experation Month" id="Experation_Month" tabindex="<? echo $tab+($tabadd++); ?>">
        <? for($n=1;$n<=12;$n++){ ?>
        <option value="<? echo $n; ?>"<? if($CCM == $n){print(' selected="selected"');}?>><? echo $n; ?></option>
        <? } ?>
      </select>
      <? $date = date("Y"); ?>
      &nbsp; Year:
      <select name="Experation Year" id="Experation_Year" tabindex="<? echo $tab+($tabadd++); ?>">
        <? for($n=0;$n<10;$n++){ ?>
        <option value="<? echo ($date+$n); ?>"<? if($CCY == ($date+$n)){print(' selected="selected"');}?>><? echo ($date+$n); ?></option>
        <? } ?>
      </select>
      <? } ?>
      &nbsp;</td>
  </tr>
</table>
