<? 
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'scripts/security.php'; 
$required_string = "'Username','','R','Password','','R','Confirm_Password','','R','Answer','','R','First_Name','','R','Last_Name','','R','Url_Handle','','R','Address','','R','City','','R','State','','R','Zip','','R','Phone_Number','','RisNum','Email','','RisEmail','Billing_First_Name','','R','Billing_Last_Name','','R','Billing_Address','','R','Billing_City','','R','Billing_State','','R','Billing_Zip','','R','Credit_Card_Number','','RisNum','CCV_Code','','RisNum','Email','','RisEmail'";
?>

<script type="text/javascript" src="/javascript/swatches.js"></script>
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
<div id="Bubble">
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td id="Bubble_topleft"><img src="/images/spacer.gif" width="23" height="3"></td>
      <td id="Bubble_top"><img src="/images/spacer.gif" width="1" height="3"></td>
      <td id="Bubble_topright"><img src="/images/spacer.gif" width="5" height="3"></td>
    </tr>
    <tr>
      <td id="Bubble_left"><img src="/images/spacer.gif" width="23" height="1"></td>
      <td rowspan="3"><table border="0" cellpadding="10" cellspacing="0">
          <tr>
            <td id="Bubble_text">&nbsp;</td>
          </tr>
        </table></td>
      <td id="Bubble_right" rowspan="3"><img src="/images/spacer.gif" width="5" height="1"></td>
    </tr>
    <tr>
      <td id="Bubble_tap"><img src="/images/spacer.gif" width="23" height="28"></td>
    </tr>
    <tr>
      <td id="Bubble_left_2"><img src="/images/spacer.gif" width="23" height="1"></td>
    </tr>
    <tr>
      <td id="Bubble_bottomleft"><img src="/images/spacer.gif" width="23" height="6"></td>
      <td id="Bubble_bottom"><img src="/images/spacer.gif" width="1" height="6"></td>
      <td id="Bubble_bottomright"><img src="/images/spacer.gif" width="5" height="6"></td>
    </tr>
  </table>
</div>
<div style="clear:both">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <? if ($Error){ ?>
    <tr>
      <td colspan="8" style=" padding:5px; border:#CC0000 1px solid; background:#FFFFFF; color:#CC0000;"><img src="/images/warning.jpg" width="30" height="29" border="0" align="absmiddle" style="float:left" />
        <div style="float:left; margin:8px;"><? echo $Error; ?></div>
        <img src="/images/warning.jpg" width="30" height="29" border="0" align="absmiddle"  style="float:right" /></td>
    </tr>
    <tr>
      <td colspan="8">&nbsp;</td>
    </tr>
    <? } ?>
    <tr>
      <td colspan="3">Service Level: </td>
      <? 
			$tab = 1;
			$tabadd = 6;
	$query_get_service = "SELECT `prod_id`, `prod_name`, `prod_price` FROM `prod_products` WHERE `prod_service` = 'y' AND `prod_use` = 'y' AND `prod_recur` = 'y' ORDER BY `prod_name` ASC";
	$get_service = mysql_query($query_get_service, $cp_connection) or die(mysql_error());
	$row_get_service = mysql_fetch_assoc($get_service);
	$totalRows_get_service = mysql_num_rows($get_service);
	  
	  $SelVal = $SvLvl; ?>
      <td colspan="5"><select name="Service Level" id="Service_Level" tabindex="<? echo $tab++; ?>">
          <? do{ ?>
          <option value="<? echo $row_get_service['prod_id']; ?>"<? if($SelVal == $row_get_service['prod_id']){print(' selected="selected"');}?>><? echo $row_get_service['prod_name'];?></option>
          <? } while($row_get_service = mysql_fetch_assoc($get_service)); ?>
        </select>
        &nbsp;</td>
    </tr>
    <? mysql_free_result($get_service); ?>
    <tr>
      <td colspan="8"><hr size="1" /></td>
    </tr>
    <tr>
      <td colspan="3"><h1>Account Information </h1></td>
      <td colspan="5"><h1>Billing Information </h1></td>
    </tr>
    <tr>
      <td colspan="3">Referred By: </td>
      <td colspan="4">* Name on Card: </td>
    </tr>
    <tr>
      <td colspan="3"><input type="text" name="Referred By" id="Referred_By" value="<? echo $RName;?>" tabindex="<? echo $tab++; ?>" /></td>
      <td colspan="4"><input type="text" name="Billing First Name" id="Billing_First_Name" value="<? echo $BFName;?>" tabindex="<? echo $tab+($tabadd++); ?>" />
        &nbsp;
        <input type="text" name="Billing Middle Name" id="Billing_Middle_Name" size="3" value="<? echo $BMName;?>" tabindex="<? echo $tab+($tabadd++); ?>" />
        .
        &nbsp;
        <input type="text" name="Billing Last Name" id="Billing_Last_Name" value="<? echo $BLName;?>" tabindex="<? echo $tab+($tabadd++); ?>" />
        &nbsp;
        <? $SelVal = $BSuffix; ?>
        <select name="Billing Suffix" id="Billing_Suffix" tabindex="<? echo $tab+($tabadd++); ?>">
          <option value="0"<? if($SelVal == "0"){print(' selected="selected"');}?>>&nbsp;</option>
          <option value="1"<? if($SelVal == "1"){print(' selected="selected"');}?>>Sr.</option>
          <option value="2"<? if($SelVal == "2"){print(' selected="selected"');}?>>Jr.</option>
          <option value="3"<? if($SelVal == "3"){print(' selected="selected"');}?>>II</option>
          <option value="4"<? if($SelVal == "4"){print(' selected="selected"');}?>>III</option>
          <option value="5"<? if($SelVal == "5"){print(' selected="selected"');}?>>IV</option>
          <option value="6"<? if($SelVal == "6"){print(' selected="selected"');}?>>V</option>
        </select>
        &nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">* Username:</td>
      <td colspan="4">Company Name:</td>
    </tr>
    <tr>
      <td colspan="3"><input type="text" name="Username" id="Username" value="<? echo $UName;?>" tabindex="<? echo $tab++; ?>" />
        &nbsp;</td>
      <td colspan="4"><input type="text" name="Billing Company Name" id="Billing_Company_Name" value="<? echo $BCName;?>" tabindex="<? echo $tab+($tabadd++); ?>" style="width:415px" /></td>
    </tr>
    <tr>
      <td colspan="3">* Create unique Web Handle: <a href="#" onmouseover="popBubble('none','<h1>Your URL Handle</h1><p>Enter your business name without spaces. This will determine what the URL is going to be for the link on your site. This will also be what your client enters in the photographer code area if they go to www.proimagesoftware.com. Once you have your URL handle figured out your link on your site will be : http://www.proimagesoftware.com/yourbusinessname. </p>'); getMousePos();" onmouseout="hideBubble();" >what
          is this? </a></td>
      <td>* Address:</td>
      <td colspan="3">Suite/Apt.</td>
    </tr>
    <tr>
      <td colspan="3"><input type="text" name="Url Handle" id="Url_Handle" value="<? echo $CString;?>" tabindex="<? echo $tab++; ?>" /></td>
      <td><input type="text" name="Billing Address" id="Billing_Address" value="<? echo $BAdd;?>" tabindex="<? echo $tab+($tabadd++); ?>" />
        &nbsp;</td>
      <td colspan="3"><input name="Billing Suite Apt" type="text" id="Billing_Suite_Apt" value="<? echo $BSuiteApt;?>" size="5" maxlength="5" tabindex="<? echo $tab+($tabadd++); ?>" /></td>
    </tr>
    <? $query_get_country = "SELECT `country_short_3`, `country_name` FROM `a_country` WHERE `country_use` = 'y' ORDER BY `country_name` ASC";
	$get_country = mysql_query($query_get_country, $cp_connection) or die(mysql_error());
	$row_get_country = mysql_fetch_assoc($get_country);
	$totalRows_get_country = mysql_num_rows($get_country);
	$SelVal = $BCount;?>
    <tr>
      <td colspan="3">* Password: </td>
      <td colspan="4">Address 2 </td>
    </tr>
    <tr>
      <td colspan="3"><input type="password" name="Password" id="Password" tabindex="<? echo $tab++; ?>" />
        &nbsp;</td>
      <td colspan="4"><input type="text" name="Billing Address 2" id="Billing_Address_2" value="<? echo $BAdd2;?>" tabindex="<? echo $tab+($tabadd++); ?>" /></td>
    </tr>
    <? mysql_free_result($get_country); ?>
    <tr>
      <td colspan="3">* Confirm Password: </td>
      <td>* City: </td>
      <td>* State/Province:</td>
      <td colspan="2">* Zip:</td>
    </tr>
    <tr>
      <td colspan="3"><input type="password" name="Confirm Password" id="Confirm_Password" tabindex="<? echo $tab++; ?>" />
        &nbsp;
      <td><input type="text" name="Billing_City" id="Billing_City" value="<? echo $BCity;?>" tabindex="<? echo $tab+($tabadd++); ?>" /></td>
      <td><div style="float:left; <? if($BCount!="USA")print(" display:none;"); ?>" id="Billing_State_Select">
          <? $query_get_states = "SELECT `state_short` FROM `a_states` WHERE `country_id` = '225' ORDER BY `state_short` ASC";
		$get_states = mysql_query($query_get_states, $cp_connection) or die(mysql_error());
		$SelVal = $BState;?>
          <select name="Billing State" id="Billing_State"<? if($BCount!="USA")print(" disabled=\"disabled\""); ?> tabindex="<? echo $tab+($tabadd++); ?>">
            <? while($row_get_states = mysql_fetch_assoc($get_states)){ ?>
            <option value="<? echo $row_get_states['state_short'] ?>"<? if($SelVal == $row_get_states['state_short']){print(' selected="selected"');}?>><? echo $row_get_states['state_short'] ?></option>
            <? } ?>
          </select>
          <? mysql_free_result($get_states); ?>
        </div>
        <div style="float:left;<? if($BCount=="USA")print(" display:none;"); ?>" id="Billing_State_Text">
          <input name="Billing State" type="text" id="Billing State" value="<? echo $SelVal;?>" size="3" maxlength="3"<? if($BCount=="USA"){print(" disabled=\"disabled\"");} ?> tabindex="<? echo $tab+($tabadd++); ?>" />
        </div></td>
      <td colspan="2"><input name="Billing Zip" type="text" id="Billing_Zip" value="<? echo $BZip;?>" size="10" maxlength="15" tabindex="<? echo $tab+($tabadd++); ?>" /></td>
    </tr>
    <tr>
      <td colspan="3">Security Question: </td>
      <td colspan="4">Country</td>
    </tr>
    <tr>
      <td colspan="3"><? $query_get_questions = "SELECT * FROM `securty_questions` ORDER BY `question` ASC";
			$get_questions = mysql_query($query_get_questions, $cp_connection) or die(mysql_error());	
			$SelVal = $SQ; ?>
        <select name="Security Question" id="Security_Question" tabindex="<? echo $tab++; ?>">
          <? while($row_get_questions = mysql_fetch_assoc($get_questions)){ ?>
          <option value="<? echo $row_get_questions['question_id']; ?>"<? if($SelVal == $row_get_questions['question_id']){print(' selected="selected"');}?>><? echo $row_get_questions['question']; ?></option>
          <? } ?>
        </select>
        <? mysql_free_result($get_questions); ?></td>
      <td colspan="4"><? $query_get_country = "SELECT `country_short_3`, `country_name` FROM `a_country` WHERE `country_use` = 'y' ORDER BY `country_name` ASC";
	$get_country = mysql_query($query_get_country, $cp_connection) or die(mysql_error());
	$SelVal = $BCount; ?>
        <select name="Billing Country" id="Billing_Country" tabindex="<? echo $tab+($tabadd++); ?>" onchange="javascript:change_state('Billing_',this.value);">
          <option value="0"> -- Select Country -- </option>
          <? while($row_get_country = mysql_fetch_assoc($get_country)){ ?>
          <option value="<? echo $row_get_country['country_short_3']; ?>"<? if($SelVal == $row_get_country['country_short_3']){print(' selected="selected"');}?>><? echo $row_get_country['country_name']; ?></option>
          <? } ?>
        </select></td>
    </tr>
    <tr>
      <td colspan="3">* Answer:</td>
      <td colspan="4">Type of Card:</td>
      <td rowspan="5"><div id="Billing_State_Text" style="float:left;<? if($BCount=="USA")print(" display:none;"); ?>"></div>
        <div id="Billing_State_Select" style="float:left;<? if($BCount!="USA")print(" display:none;"); ?>"></div></td>
    </tr>
    <? $query_get_cards = "SELECT * FROM `billship_cc_types` WHERE `cc_accept` = 'y' ORDER BY `cc_order` ASC";
	$get_cards = mysql_query($query_get_cards, $cp_connection) or die(mysql_error());
	$row_get_cards = mysql_fetch_assoc($get_cards);
	$totalRows_get_cards = mysql_num_rows($get_cards);
	$SelVal = $CType;?>
    <tr>
      <td colspan="3"><input type="text" name="Answer" id="Answer" value="<? echo $Answer;?>" tabindex="<? echo $tab++; ?>" />
        &nbsp;</td>
      <td colspan="4"><select name="Type of Card" id="Type_of_Card" tabindex="<? echo $tab+($tabadd++); ?>">
          <? do{ ?>
          <option value="<? echo $row_get_cards['cc_type_id']; ?>"<? if($CType == $row_get_cards['cc_type_id']){print(' selected="selected"');}?>><? echo $row_get_cards['cc_type_name']; ?></option>
          <? } while($row_get_cards = mysql_fetch_assoc($get_cards)); ?>
        </select></td>
    </tr>
    <? mysql_free_result($get_cards); $SelVal = $CCM; ?>
    <tr>
      <td colspan="3" rowspan="4">&nbsp;</td>
      <td>* Credit Card Number:</td>
      <td>* CCV Code:</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td><input name="Credit Card Number" type="text" id="Credit_Card_Number" maxlength="16" tabindex="<? echo $tab+($tabadd++); ?>" /></td>
      <td><input name="CCV Code" type="text" id="CCV_Code" value="<? echo $CCV;?>" maxlength="5" tabindex="<? echo $tab+($tabadd++); ?>" /></td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4">Expiration: </td>
    </tr>
    <tr>
      <td colspan="4">Month:
			
        <? $SelVal = $CCM; ?>
        <select name="Experation Month" id="Experation_Month" tabindex="<? echo $tab+($tabadd++); ?>">
          <? for($n=1;$n<=12;$n++){ ?>
          <option value="<? echo $n; ?>"<? if($SelVal == $n){print(' selected="selected"');}?>><? echo $n; ?></option>
          <? } ?>
        </select>
        <? $SelVal = $CCY; 
		$date = date("Y"); ?>
        &nbsp; Year:
        <select name="Experation Year" id="Experation_Year" tabindex="<? echo $tab+($tabadd++); ?>">
          <? for($n=0;$n<5;$n++){ ?>
          <option value="<? echo ($date+$n); ?>"<? if($SelVal == ($date+$n)){print(' selected="selected"');}?>><? echo ($date+$n); ?></option>
          <? } ?>
        </select></td>
    </tr>
    <tr>
      <td colspan="8"><hr size="1" /></td>
    </tr>
    <tr>
      <td colspan="8"><h1>Personal Information</h1></td>
    </tr>
    <? $tab+=$tabadd; ?>
    <tr>
      <td colspan="3">* Name:</td>
      <td colspan="5" rowspan="22">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3"><input type="text" name="First Name" id="First_Name" value="<? echo $FName;?>" tabindex="<? echo $tab++; ?>" />
        &nbsp;
        <input type="text" name="Middle Name" id="Middle_Name" size="3" value="<? echo $MName;?>" tabindex="<? echo $tab++; ?>" />
        .
        &nbsp;
        <input type="text" name="Last Name" id="Last_Name" value="<? echo $LName;?>" tabindex="<? echo $tab++; ?>" />
        &nbsp;
        <? $SelVal = $Suffix; ?>
        <select name="Suffix" id="Suffix" tabindex="<? echo $tab++; ?>">
          <option value="0"<? if($SelVal == "0"){print(' selected="selected"');}?>>&nbsp;</option>
          <option value="1"<? if($SelVal == "1"){print(' selected="selected"');}?>>Sr.</option>
          <option value="2"<? if($SelVal == "2"){print(' selected="selected"');}?>>Jr.</option>
          <option value="3"<? if($SelVal == "3"){print(' selected="selected"');}?>>II</option>
          <option value="4"<? if($SelVal == "4"){print(' selected="selected"');}?>>III</option>
          <option value="5"<? if($SelVal == "5"){print(' selected="selected"');}?>>IV</option>
          <option value="6"<? if($SelVal == "6"){print(' selected="selected"');}?>>V</option>
        </select>
        &nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">Company Name: </td>
    </tr>
    <tr>
      <td colspan="3"><input type="text" name="Company Name" id="Company_Name" value="<? echo $CName;?>" tabindex="<? echo $tab++; ?>"  style="width:415px" /></td>
    </tr>
    <tr>
      <td colspan="3">* Address:</td>
    </tr>
    <? mysql_free_result($get_country); ?>
    <tr>
      <td colspan="3"><input type="text" name="Address" id="Address" value="<? echo $Add;?>" tabindex="<? echo $tab++; ?>" />
        Suite/Apt.
        <input name="Suite Apt" type="text" id="Suite_Apt" value="<? echo $SuiteApt;?>" size="5" maxlength="5" tabindex="<? echo $tab++; ?>" />
        &nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">Address 2: </td>
    </tr>
    <tr>
      <td colspan="3"><input type="text" name="Address 2" id="Address_2" value="<? echo $Add2;?>" tabindex="<? echo $tab++; ?>" /></td>
    </tr>
    <tr>
      <td>* City:</td>
      <td>* State/Province: </td>
      <td>* Zip:</td>
    </tr>
    <tr>
      <td><input type="text" name="City" id="City" value="<? echo $City;?>" tabindex="<? echo $tab++; ?>" /></td>
      <td><div id="State_Select" style="float:left;<? if($Count!="USA")print(" display:none;"); ?>">
          <?
		$query_get_states = "SELECT `state_short` FROM `a_states` WHERE `country_id` = '225' ORDER BY `state_short` ASC";
		$get_states = mysql_query($query_get_states, $cp_connection) or die(mysql_error());
		$row_get_states = mysql_fetch_assoc($get_states);
		$totalRows_get_states = mysql_num_rows($get_states);
		
		$SelVal = $State;?>
          <select name="State" id="State"<? if($Count!="USA")print(" disabled=\"disabled\""); ?> tabindex="<? echo $tab++; ?>">
            <? do { ?>
            <option value="<? echo $row_get_states['state_short'] ?>"<? if($SelVal == $row_get_states['state_short']){print(' selected="selected"');}?>><? echo $row_get_states['state_short'] ?></option>
            <? } while($row_get_states = mysql_fetch_assoc($get_states)); ?>
          </select>
          <? mysql_free_result($get_states); ?>
        </div>
        <div id="State_Text" style="float:left;<? if($Count=="USA")print(" display:none;"); ?>">
          <input name="State" type="text" id="State" value="<? echo $State;?>" size="3" maxlength="3"<? if($Count=="USA")print(" disabled=\"disabled\""); ?> tabindex="<? echo $tab++; ?>" />
        </div></td>
      <td><input name="Zip" type="text" id="Zip" value="<? echo $Zip;?>" size="10" maxlength="15" tabindex="<? echo $tab++; ?>" />
      </td>
    </tr>
    <tr>
      <td colspan="3">Country:</td>
    </tr>
    <? $query_get_country = "SELECT `country_short_3`, `country_name` FROM `a_country` WHERE `country_use` = 'y' ORDER BY `country_name` ASC";
	$get_country = mysql_query($query_get_country, $cp_connection) or die(mysql_error());
	$row_get_country = mysql_fetch_assoc($get_country);
	$totalRows_get_country = mysql_num_rows($get_country);
	$SelVal = $Count; ?>
    <tr>
      <td colspan="3"><select name="Country" id="Country" onchange="javascript:change_state('',this.value);" tabindex="<? echo $tab++; ?>">
          <option value="0"> -- Select Country -- </option>
          <? do{ ?>
          <option value="<? echo $row_get_country['country_short_3']; ?>"<? if($SelVal == $row_get_country['country_short_3']){print(' selected="selected"');}?>><? echo $row_get_country['country_name']; ?></option>
          <? } while($row_get_country = mysql_fetch_assoc($get_country)); ?>
        </select></td>
    </tr>
    <tr>
      <td colspan="3">* Phone Number: </td>
    </tr>
    <tr>
      <td colspan="3">(
        <input name="P1" type="text" id="P1" value="<? echo $P1;?>" maxlength="3" onkeyup="set_tel_number('Phone_Number','P'); move_tel_number('P','1');" style="width:30px;" tabindex="<? echo $tab++; ?>" />
        )
        <input name="P2" type="text" id="P2" value="<? echo $P2;?>" maxlength="3" onkeyup="set_tel_number('Phone_Number','P'); move_tel_number('P','2');" style="width:30px;" tabindex="<? echo $tab++; ?>" />
        -
        <input name="P3" type="text" id="P3" value="<? echo $P3;?>" maxlength="4" onkeyup="set_tel_number('Phone_Number','P');" style="width:40px;" tabindex="<? echo $tab++; ?>" />
        <input type="hidden" name="Phone Number" id="Phone_Number" value="<? echo $Phone;?>" />
        &nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">Work Number: </td>
    </tr>
    <tr>
      <td colspan="3">(
        <input name="W1" type="text" id="W1" value="<? echo $W1;?>" maxlength="3" onkeyup="set_tel_number('Work_Number','W'); move_tel_number('W','1');" style="width:30px;" tabindex="<? echo $tab++; ?>" />
        )
        <input name="W2" type="text" id="W2" value="<? echo $W2;?>" maxlength="3" onkeyup="set_tel_number('Work_Number','W'); move_tel_number('W','2');" style="width:30px;" tabindex="<? echo $tab++; ?>" />
        -
        <input name="W3" type="text" id="W3" value="<? echo $W3;?>" maxlength="4" onkeyup="set_tel_number('Work_Number','W');" style="width:40px;" tabindex="<? echo $tab++; ?>" />
        <input type="hidden" name="Work Number" id="Work_Number" value="<? echo $Work;?>" tabindex="<? echo $tab++; ?>" />
        &nbsp;Ext.
        <input name="Work Ext" type="text" id="Work_Ext" value="<? echo $WorkExt;?>" style="width:50px;" tabindex="<? echo $tab++; ?>" />
        &nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">Mobile Number:</td>
    </tr>
    <tr>
      <td colspan="3">(
        <input name="M1" type="text" id="M1" value="<? echo $M1;?>" maxlength="3" onkeyup="set_tel_number('Mobile_Number','M'); move_tel_number('M','1');" style="width:30px;" tabindex="<? echo $tab++; ?>" />
        )
        <input name="M2" type="text" id="M2" value="<? echo $M2;?>" maxlength="3" onkeyup="set_tel_number('Mobile_Number','M'); move_tel_number('M','2');" style="width:30px;" tabindex="<? echo $tab++; ?>" />
        -
        <input name="M3" type="text" id="M3" value="<? echo $M3;?>" maxlength="4" onkeyup="set_tel_number('Mobile_Number','M');" style="width:40px;" tabindex="<? echo $tab++; ?>" />
        <input type="hidden" name="Mobile Number" id="Mobile_Number" value="<? echo $Mobile;?>" />
        &nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">Fax Number:</td>
    </tr>
    <tr>
      <td colspan="3">(
        <input name="F1" type="text" id="F1" value="<? echo $F1;?>" maxlength="3" onkeyup="set_tel_number('Fax_Number','F'); move_tel_number('F','1');" style="width:30px;" tabindex="<? echo $tab++; ?>" />
        )
        <input name="F2" type="text" id="F2" value="<? echo $F2;?>" maxlength="3" onkeyup="set_tel_number('Fax_Number','F'); move_tel_number('F','2');" style="width:30px;" tabindex="<? echo $tab++; ?>" />
        -
        <input name="F3" type="text" id="F3" value="<? echo $F3;?>" maxlength="4" onkeyup="set_tel_number('Fax_Number','F');" style="width:40px;" tabindex="<? echo $tab++; ?>" />
        <input type="hidden" name="Fax Number" id="Fax_Number" value="<? echo $Fax;?>" />
        &nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">* E-mail:</td>
    </tr>
    <tr>
      <td colspan="3"><input type="text" name="Email" id="Email" value="<? echo $Email;?>" tabindex="<? echo $tab++; ?>" /></td>
    </tr>
  </table>
  <br clear="all" />
</div>
