<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
$required_string = "'Name','','R','Code','','R','Date','','R','End_Date','','R'";
if((count($path)<=3 && $cont == "view") || (count($path)>3)) $is_enabled = false; else $is_enabled = true;
$HotMenu = "Pers,Bill:view"; $Key = array_search($HotMenu, $StrArray); ?>

<h1 id="HdrType2" class="AccntBill">
  <div>Billing Information</div>
</h1>
<div id="HdrLinks">
  <? if($is_enabled){ ?>
  <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status='Save Billing Information'; return true;" onmouseout="window.status=''; return true;" title="Save Billing Information" class="BtnSave2">Save</a><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Cancel'; return true;" onmouseout="window.status=''; return true;" title="Cancel" class="BtnCancel">Cancel</a>
  <? } else { ?>
  <a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Edit Billing Information <? echo $Name; ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit Billing Information <? echo $Name; ?>" class="BtnEdit2">Edit</a><a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key!==false)?'2':'1'; ?>);" title="Add to Hot Menu" onmouseover="window.status='Add to Hot Menu'; return true;" onmouseout="window.status=''; return true;" class="BtnHotMenu<? echo ($Key!==false)?'Added':''; ?>" id="BtnHotMenu">Add to Hot Menu</a>
  <? } ?>
</div>
<? if(isset($Error) && strlen(trim($Error)) > 0){ ?>
<h1 id="HdrType2-5" class="EvntInfo2">
  <div>Error</div>
</h1>
<div id="RecordTable-5" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p class="Error"><? echo $Error; ?></p>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<? } ?>
<div id="RecordTabs"> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Info"; ?>','view','','');" id="BtnTabPers">Personal Information</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Bill"; ?>','view','','');" id="BtnTabBill"<? if($path[1]=="Bill") echo ' class="NavSel"'; ?>>Billing Information</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Renew"; ?>','view','','');" id="BtnTabRenew">Renew Account</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Cancel"; ?>','view','','');" id="BtnTabCancel">Cancel Account</a><br clear="all" />
</div>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records" class="Colmn">
    <p>
      <label for="First_Name" class="CstmFrmElmntLabel">Name</label>
      <? if(!$is_enabled){ echo $FName.' '.$MInt.' '.$LName;
		switch($Suffix){
			case 0: echo "&nbsp;"; break;
			case 1: echo "Sr."; break;
			case 2: echo "Jr."; break;
			case 3: echo "II"; break;
			case 4: echo "III"; break;
			case 5: echo "IV"; break;
			case 6: echo "V"; break;		
		} } else { ?>
      <input type="text" name="First Name" id="First_Name" value="<? echo $FName; ?>" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='First Name'; return true;" onmouseout="window.status=''; return true;" title="First Name" class="CstmFrmElmntInput" />
      <input type="text" name="Middle Initial" id="Middle_Initial" size="3" value="<? echo $MInt; ?>" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Middle Name'; return true;" onmouseout="window.status=''; return true;" title="Middle Name" class="CstmFrmElmntInputi29" />
      <input type="text" name="Last Name" id="Last_Name" value="<? echo $LName; ?>" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Last Name'; return true;" onmouseout="window.status=''; return true;" title="Last Name" class="CstmFrmElmntInput" />
      <span style="float:left; clear:none; margin-right:5px;">
      <select name="Suffix" id="Suffix" class="CstmFrmElmnt64" onmouseover="window.status='Suffix'; return true;" onmouseout="window.status=''; return true;" title="Suffix">
        <option value="0"<? if($Suffix == "0"){print(' selected="selected"');}?>>&nbsp;</option>
        <option value="1"<? if($Suffix == "1"){print(' selected="selected"');}?> title="Sr.">Sr.</option>
        <option value="2"<? if($Suffix == "2"){print(' selected="selected"');}?> title="Jr.">Jr.</option>
        <option value="3"<? if($Suffix == "3"){print(' selected="selected"');}?> title="II">II</option>
        <option value="4"<? if($Suffix == "4"){print(' selected="selected"');}?> title="III">III</option>
        <option value="5"<? if($Suffix == "5"){print(' selected="selected"');}?> title="IV">IV</option>
        <option value="6"<? if($Suffix == "6"){print(' selected="selected"');}?> title="V">V</option>
      </select>
      </span>
      <? } ?>
      <br />
      <label for="Company_Name" class="CstmFrmElmntLabel">Name</label>
      <? if(!$is_enabled) echo $CName; else { ?>
      <input type="text" name="Company Name" id="Company_Name" value="<? echo $CName; ?>" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Company Name'; return true;" onmouseout="window.status=''; return true;" title="Company Name" class="CstmFrmElmntInput" />
      <? } ?>
      <br />
      <label for="Address" class="CstmFrmElmntLabel">Address</label>
      <? if(!$is_enabled) echo $Add.(($SuiteApt != "") ? " Suite/Apt: ".$SuiteApt:'');  else { ?>
      <input name="Address" type="text" id="Address" value="<? echo $Add;?>" maxlength="125" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Address'; return true;" onmouseout="window.status=''; return true;" title="Address" class="CstmFrmElmntInput" />
      <strong class="CstmFrmElmntStrong">Suite/Apt</strong>
      <input type="text" name="Suite Apt" id="Suite_Apt" value="<? echo $SuiteApt;?>" maxlength="25" size="10" onfocus="javascript:this.className='CstmFrmElmntInputi34NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi34';" onmouseover="window.status='Suite/Apt'; return true;" onmouseout="window.status=''; return true;" title="Suite/Apt" class="CstmFrmElmntInputi34" />
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
      <select name="Country" id="Country" onchange="javascript:AEV_GetState(document.getElementById('Country').value,false,false,'');" class="CstmFrmElmnt" onmouseover="window.status='Country'; return true;" onmouseout="window.status=''; return true;" title="Country">
        <option value="0" title="Select Country"> -- Select Country -- </option>
        <? foreach($getCnty->Rows() as $r){ ?>
        <option value="<? echo $r['country_short_3']; ?>"<? if($Country == $r['country_short_3'])echo ' selected="selected"';?> title="<? echo $r['country_name']; ?>"><? echo $r['country_name']; ?></option>
        <? } ?>
      </select>
      <? } ?>
    <br />
    </p>
    <br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
