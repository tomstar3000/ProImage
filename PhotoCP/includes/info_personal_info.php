<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
if((count($path)<=3 && $cont == "view") || (count($path)>3)) $is_enabled = false; else	$is_enabled = true;
$HotMenu = "Pers,Info:view"; $Key = array_search($HotMenu, $StrArray); ?>

<h1 id="HdrType2" class="AccntPersonal">
  <div>Personal Information</div>
</h1>
<div id="HdrLinks">
  <? if($is_enabled){ ?>
  <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status='Save Personal Information'; return true;" onmouseout="window.status=''; return true;" title="Save Personal Information" class="BtnSave2">Save</a><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Cancel'; return true;" onmouseout="window.status=''; return true;" title="Cancel" class="BtnCancel">Cancel</a>
  <? } else { ?>
  <a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Edit Personal Information <? echo $Name; ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit Personal Information <? echo $Name; ?>" class="BtnEdit2">Edit</a><a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key!==false)?'2':'1'; ?>);" title="Add to Hot Menu" onmouseover="window.status='Add to Hot Menu'; return true;" onmouseout="window.status=''; return true;" class="BtnHotMenu<? echo ($Key!==false)?'Added':''; ?>" id="BtnHotMenu">Add to Hot Menu</a>
  <? } ?>
</div>
<? if(isset($Error) && strlen(trim($Error)) > 0){ ?>
<h1 id="HdrType2" class="EvntInfo2">
  <div>Error</div>
</h1>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p class="Error"><? echo $Error; ?></p>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<? } ?>
<div id="RecordTabs"> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Info"; ?>','view','','');" id="BtnTabPers"<? if($path[1]=="Info") echo ' class="NavSel"'; ?>>Personal Information</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Bill"; ?>','view','','');" id="BtnTabBill">Billing Information</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Renew"; ?>','view','','');" id="BtnTabRenew">Renew Account</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Cancel"; ?>','view','','');" id="BtnTabCancel">Cancel Account</a><br clear="all" />
</div>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records" class="Colmn">
    <p> Sign Up Date: <? echo format_date($SignUpDate,'Short',false,true,false); ?><br />
      Handle: <? echo $CHandle; ?><br />
      <br />
      <label for="First_Name" class="CstmFrmElmntLabel">Name</label>
      <? if(!$is_enabled)echo $FName." ".(($MInt != "")?$MInt.". ":'').$LName; else { ?>
      <input type="text" name="First Name" id="First_Name" value="<? echo $FName;?>" maxlength="75" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='First Name'; return true;" onmouseout="window.status=''; return true;" title="First Name" class="CstmFrmElmntInput" />
      <input type="text" name="Middle Initial" id="Middle_Initial" value="<? echo $MInt;?>" maxlength="1" size="5" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Middle Name'; return true;" onmouseout="window.status=''; return true;" title="Middle Name" class="CstmFrmElmntInputi29" />
      <input type="text" name="Last Name" id="Last_Name" value="<? echo $LName;?>" maxlength="75" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Last Name'; return true;" onmouseout="window.status=''; return true;" title="Last Name" class="CstmFrmElmntInput" />
      <? } ?>
      <br />
      <label for="Company_Name" class="CstmFrmElmntLabel">Company Name</label>
      <? if(!$is_enabled) echo $CName; else { ?>
      <input type="text" name="Company Name" id="Company_Name" value="<? echo $CName;?>" maxlength="100" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Company Name'; return true;" onmouseout="window.status=''; return true;" title="Company Name" class="CstmFrmElmntInput" />
      <? } ?>
      <br />
      <label for="Address" class="CstmFrmElmntLabel">Address</label>
      <? if(!$is_enabled) echo $Add.(($SApt != "") ? " Suite/Apt: ".$SApt:'');  else { ?>
      <input name="Address" type="text" id="Address" value="<? echo $Add;?>" maxlength="125" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Address'; return true;" onmouseout="window.status=''; return true;" title="Address" class="CstmFrmElmntInput" />
      <strong class="CstmFrmElmntStrong">Suite/Apt</strong>
      <input type="text" name="Suite Apt" id="Suite_Apt" value="<? echo $SApt;?>" maxlength="25" size="10" onfocus="javascript:this.className='CstmFrmElmntInputi34NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi34';" onmouseover="window.status='Suite/Apt'; return true;" onmouseout="window.status=''; return true;" title="Suite/Apt" class="CstmFrmElmntInputi34" />
      <? } ?>
      <br />
      <label for="Address_2" class="CstmFrmElmntLabel">Address Second Line</label>
      <? if(!$is_enabled) echo $Add2; else { ?>
      <input type="text" name="Address 2" id="Address_2" value="<? echo $Add2;?>" maxlength="125" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Address Second Line'; return true;" onmouseout="window.status=''; return true;" title="Address Second Line" class="CstmFrmElmntInput" />
      <? } ?>
      <br />
      <label for="City" class="CstmFrmElmntLabel">City, State, Zip</label>
      <? if(!$is_enabled) echo $City." ".$State." ".$Zip; else { ?>
      <input name="City" type="text" id="City" value="<? echo $City; ?>" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='City'; return true;" onmouseout="window.status=''; return true;" title="City" class="CstmFrmElmntInput" />
      <span style="float:left; clear:none; margin-right:5px;" id="State_Box">
      <script type="text/javascript">AEV_GetState('<? echo $Country; ?>','<? echo $State; ?>','','');</script>
      </span>
      <input name="Zip" type="text" id="Zip" value="<? echo $Zip; ?>" onfocus="javascript:this.className='CstmFrmElmntInputi117NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi117';" onmouseover="window.status='Zip'; return true;" onmouseout="window.status=''; return true;" title="Zip" class="CstmFrmElmntInputi117" />
      <? } ?>
      <br />
      <label for="Country" class="CstmFrmElmntLabel">Country</label>
      <? if(!$is_enabled) echo $Country; else {
					$getCnty = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
				 	$getCnty->mysql("SELECT `country_short_3`, `country_name` FROM `a_country` WHERE `country_use` = 'y' ORDER BY `country_name` ASC;"); ?>
      <select name="Country" id="Country" onchange="javascript:AEV_GetState(document.getElementById('Country').value,false,false,'');" class="CstmFrmElmnt" title="Country">
        <option value="0" title="Select Country"> -- Select Country -- </option>
        <? foreach($getCnty->Rows() as $r){ ?>
        <option value="<? echo $r['country_short_3']; ?>"<? if($Country == $r['country_short_3'])echo ' selected="selected"';?> title="<? echo $r['country_name']; ?>"><? echo $r['country_name']; ?></option>
        <? } ?>
      </select>
      <? } ?>
      <br />
      <label for="P1" class="CstmFrmElmntLabel">Phone Number</label>
      <? if(!$is_enabled) echo ($Phone != "0")?"(".$P1.") ".$P2."-".$P3:''; else { ?>
      <input name="P1" type="text" id="P1" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Phone Number: Area Code'; return true;" onmouseout="window.status=''; return true;" title="Phone Number: Area Code" value="<? echo $P1;?>" maxlength="3" onkeyup="AEV_set_tel_number('Phone_Number','P');" />
      <input name="P2" type="text" id="P2" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Phone Number: Prefix'; return true;" onmouseout="window.status=''; return true;" title="Phone Number: Prefix" value="<? echo $P2;?>" maxlength="3" onkeyup="AEV_set_tel_number('Phone_Number','P');" />
      <input name="P3" type="text" id="P3" size="8" class="CstmFrmElmntInputi34" onfocus="javascript:this.className='CstmFrmElmntInputi34NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi34';" onmouseover="window.status='Phone Number: Suffix'; return true;" onmouseout="window.status=''; return true;" title="Phone Number: Suffix" value="<? echo $P3;?>" maxlength="4" onkeyup="AEV_set_tel_number('Phone_Number','P');" />
      <input type="hidden" name="Phone Number" id="Phone_Number" value="<? echo $Phone;?>" />
      <? } ?>
      <br />
      <label for="C1" class="CstmFrmElmntLabel">Cell Phone Number</label>
      <? if(!$is_enabled) echo ($Cell != "0")?"(".$C1.") ".$C2."-".$C3:''; else { ?>
      <input name="C1" type="text" id="C1" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Cell Phone Number: Area Code'; return true;" onmouseout="window.status=''; return true;" title="Cell Phone Number: Area Code" value="<? echo $C1;?>" maxlength="3" onkeyup="AEV_set_tel_number('Cell_Number','C');" />
      <input name="C2" type="text" id="C2" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Cell Phone Number: Prefix'; return true;" onmouseout="window.status=''; return true;" title="Cell Phone Number: Prefix" value="<? echo $C2;?>" maxlength="3" onkeyup="AEV_set_tel_number('Cell_Number','C');" />
      <input name="C3" type="text" id="C3" size="8" class="CstmFrmElmntInputi34" onfocus="javascript:this.className='CstmFrmElmntInputi34NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi34';" onmouseover="window.status='Cell Phone Number: Suffix'; return true;" onmouseout="window.status=''; return true;" title="Cell Phone Number: Suffix" value="<? echo $C3;?>" maxlength="4" onkeyup="AEV_set_tel_number('Cell_Number','C');" />
      <input type="hidden" name="Cell Number" id="Cell_Number" value="<? echo $Cell;?>" />
      <? } ?>
      <br />
      <label for="F1" class="CstmFrmElmntLabel">Fax Number</label>
      <? if(!$is_enabled) echo ($Fax != "0")?"(".$F1.") ".$F2."-".$F3:''; else { ?>
      <input name="F1" type="text" id="F1" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Fax Number: Area Code'; return true;" onmouseout="window.status=''; return true;" title="Fax Number: Area Code" value="<? echo $F1;?>" maxlength="3" onkeyup="AEV_set_tel_number('Fax_Number','F');" />
      <input name="F2" type="text" id="F2" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Fax Number: Prefix'; return true;" onmouseout="window.status=''; return true;" title="Fax Number: Prefix" value="<? echo $F2;?>" maxlength="3" onkeyup="AEV_set_tel_number('Fax_Number','F');" />
      <input name="F3" type="text" id="F3" size="8" class="CstmFrmElmntInputi34" onfocus="javascript:this.className='CstmFrmElmntInputi34NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi34';" onmouseover="window.status='Fax Number: Suffix'; return true;" onmouseout="window.status=''; return true;" title="Fax Number: Suffix" value="<? echo $F3;?>" maxlength="4" onkeyup="AEV_set_tel_number('Fax_Number','F');" />
      <input type="hidden" name="Fax Number" id="Fax_Number" value="<? echo $Fax;?>" />
      <? } ?>
      <br />
      <label for="W1" class="CstmFrmElmntLabel">Work Phone Number</label>
      <? if(!$is_enabled) echo ($Work != "0")?"(".$W1.") ".$W2."-".$W3:''.(($WExt!=0)?' Ext. '.$WExt:''); else { ?>
      <input name="W1" type="text" id="W1" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Work Phone Number: Area Code'; return true;" onmouseout="window.status=''; return true;" title="Work Phone Number: Area Code" value="<? echo $W1;?>" maxlength="3" onkeyup="set_tel_number('Work_Number','W');" />
      <input name="W2" type="text" id="W2" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Work Phone Number: Prefix'; return true;" onmouseout="window.status=''; return true;" title="Work Phone Number: Prefix" value="<? echo $W2;?>" maxlength="3" onkeyup="set_tel_number('Work_Number','W');" />
      <input name="W3" type="text" id="W3" size="8" class="CstmFrmElmntInputi34" onfocus="javascript:this.className='CstmFrmElmntInputi34NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi34';" onmouseover="window.status='Work Phone Number: Suffix'; return true;" onmouseout="window.status=''; return true;" title="Work Phone Number: Suffix" value="<? echo $W3;?>" maxlength="4" onkeyup="set_tel_number('Work_Number','W');" />
      <strong class="CstmFrmElmntStrong">Ext.</strong>
      <input type="text" name="Work Extension" id="Work_Extension" class="CstmFrmElmntInputi34" onfocus="javascript:this.className='CstmFrmElmntInputi34NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi34';" onmouseover="window.status='Work Phone Number: Extension'; return true;" onmouseout="window.status=''; return true;" title="Work Phone Number: Extension" value="<? echo $WExt; ?>" size="6" maxlength="10" />
      <input type="hidden" name="Work Number" id="Work_Number" value="<? echo $Work;?>" />
      <? } ?>
      <br />
      <label for="Email" class="CstmFrmElmntLabel">E-mail</label>
      <? if(!$is_enabled) echo $Email; else { ?>
      <input type="text" name="Email" id="Email" value="<? echo $Email;?>" maxlength="125" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='E-mail'; return true;" onmouseout="window.status=''; return true;" title="E-mail" class="CstmFrmElmntInput" />
      <? } ?>
      <br />
      <label for="Website" class="CstmFrmElmntLabel">Website</label>
      <? if(!$is_enabled) echo $Website; else { ?>
      <input type="text" name="Website" id="Website" value="<? echo $Website;?>" maxlength="125" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Website Address'; return true;" onmouseout="window.status=''; return true;" title="Website Address" class="CstmFrmElmntInput" />
      <? } ?>
      <br clear="all" />
    </p>
    <hr />
    <p>
      <label for="Username" class="CstmFrmElmntLabel">Username</label>
      <? if(!$is_enabled) echo $UName; else { ?>
      <input type="text" name="Username" id="Username" value="<? echo $UName; ?>" maxlength="75" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Username'; return true;" onmouseout="window.status=''; return true;" title="Username" class="CstmFrmElmntInput" />
      <? } ?>
      <br />
      <label for="Password" class="CstmFrmElmntLabel">Password</label>
      <? if(!$is_enabled) echo "**********"; else { ?>
      <input type="password" name="Password" id="Password" maxlength="75" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Password'; return true;" onmouseout="window.status=''; return true;" title="Password" class="CstmFrmElmntInput" />
      <br />
      <label for="Confirm_Password" class="CstmFrmElmntLabel">Confirm Password</label>
      <input type="password" name="Confirm Password" id="Confirm_Password" maxlength="75" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Confirm Password'; return true;" onmouseout="window.status=''; return true;" title="Confirm Password" class="CstmFrmElmntInput" />
      <br />
      <label for="Security_Question" class="CstmFrmElmntLabel">Security Question</label>
      <?  $getSeqGues = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
				 	$getSeqGues->mysql("SELECT * FROM `securty_questions` ORDER BY `question` ASC;"); ?>
      <select name="Security Question" id="Security_Question"  class="CstmFrmElmnt" onmouseover="window.status='Security Question'; return true;" onmouseout="window.status=''; return true;" title="Security Question">
        <? foreach($getSeqGues->Rows() as $r){ ?>
        <option value="<? echo $r['question_id']; ?>"<? if($SQId ==$r['question_id']) echo ' selected="selected"'; ?> title="<? echo $r['question']; ?>"><? echo $r['question']; ?></option>
        <? } ?>
      </select>
      <br />
      <label for="Answer" class="CstmFrmElmntLabel">Answer</label>
      <input type="text" name="Answer" id="Answer" value="<? echo $SQAnw; ?>" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Security Answer'; return true;" onmouseout="window.status=''; return true;" title="Security Answer" class="CstmFrmElmntInput" />
      <? } ?>
      <br />
    </p>
    <br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
