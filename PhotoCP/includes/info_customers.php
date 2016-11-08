<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
if($cont == "view") $is_enabled = false; else	$is_enabled = true;
if($cont == "add") $back = "query"; else $back = "view"; ?>
<script type="text/javascript">
function Open_Sec(ID,ELMNT){
	if(ELMNT.className == "NavSel"){ ELMNT.className = ''; document.getElementById(ID).parentNode.style.display='block';
	} else { ELMNT.className = 'NavSel'; document.getElementById(ID).parentNode.style.display='none';
	} }
</script>

<h1 id="HdrType2" class="ClntInfoClnt">
  <div>Customer Information</div>
</h1>
<div id="HdrLinks">
  <? if($cont=="view"){ ?>
  <a href="javascript:set_form('','<? echo implode(",",$path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" title="Edit Customer <? echo $FName.' '.(($MInt != '')?$MInt.'. ':'').$LName; ?>" onmouseover="window.status='Edit Customer <? echo $FName.' '.(($MInt != '')?$MInt.'. ':'').$LName; ?>'; return true;" onmouseout="window.status=''; return true;" class="BtnEdit2">Edit</a>
  <? } else { ?>
  <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status='Save Customer Information'; return true;" onmouseout="window.status=''; return true;" title="Save Customer Information" class="BtnSave2">Save</a><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','<? echo $back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Cancel'; return true;" onmouseout="window.status=''; return true;" title="Cancel" class="BtnCancel">Cancel</a>
  <? } ?>
</div>
<div id="RecordTabs"> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Clnt"; ?>','query','','');" id="BtnClntAll"<? if($path[1]=="Clnt" && $cont!="add") echo ' class="NavSel"'; ?> title="View All Clients">View All Clients</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Clnt"; ?>','add','','');" id="BtnClntNew"<? if($path[1]=="Clnt" && $cont=="add") echo ' class="NavSel"'; ?> title="Create New Client">Create New Client</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Search"; ?>','query','','');" id="BtnClntSrch"<? if($path[1]=="Search") echo ' class="NavSel"'; ?> title="Search Clients">Search Clients</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",SrchSvd"; ?>','query','','');" id="BtnClntSrchSvd"<? if($path[1]=="SrchSvd") echo ' class="NavSel"'; ?> title="Saved Searches">Saved Searches</a><br clear="all" />
</div>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p>
      <? if(!$is_enabled)echo $FName." ".(($MInt != "")?$MInt.". ":'').$LName; else { ?>
      <label for="First_Name" class="CstmFrmElmntLabel">Name</label>
      <input type="text" name="First Name" id="First_Name" value="<? echo $FName; ?>" maxlength="75" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='First Name'; return true;" onmouseout="window.status=''; return true;" title="First Name" class="CstmFrmElmntInput" />
      <input type="text" name="Middle Initial" id="Middle_Initial" value="<? echo $MInt; ?>" maxlength="1" size="5" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Middle Name'; return true;" onmouseout="window.status=''; return true;" title="Middle Name" class="CstmFrmElmntInputi29" />
      <input type="text" name="Last Name" id="Last_Name" value="<? echo $LName; ?>" maxlength="75" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Last Name'; return true;" onmouseout="window.status=''; return true;" title="Last Name" class="CstmFrmElmntInput" />
      <? } ?>
      <br />
      <? if(!$is_enabled) echo $Add.(($SApt != "") ? " Suite/Apt: ".$SApt:'');  else { ?>
      <label for="Address" class="CstmFrmElmntLabel">Address</label>
      <input name="Address" type="text" id="Address" value="<? echo $Add; ?>" maxlength="125" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Address'; return true;" onmouseout="window.status=''; return true;" title="Address" class="CstmFrmElmntInput" />
      <strong class="CstmFrmElmntStrong">Suite/Apt</strong>
      <input type="text" name="Suite Apt" id="Suite_Apt" value="<? echo $SApt; ?>" maxlength="25" size="10" onfocus="javascript:this.className='CstmFrmElmntInputi34NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi34';" onmouseover="window.status='Suite/Apt'; return true;" onmouseout="window.status=''; return true;" title="Suite/Apt" class="CstmFrmElmntInputi34" />
      <? } ?>
      <br />
      <? if(!$is_enabled) echo ($Add2!='')?$Add2.'<br />':''; else { ?>
      <label for="Address_2" class="CstmFrmElmntLabel">Address Second Line</label>
      <input type="text" name="Address 2" id="Address_2" value="<? echo $Add2; ?>" maxlength="125" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Address Second Line'; return true;" onmouseout="window.status=''; return true;" title="Address Second Line" class="CstmFrmElmntInput" />
      <br />
      <? } ?>
      <? if(!$is_enabled) echo $City." ".$State." ".$Zip; else { ?>
      <label for="City" class="CstmFrmElmntLabel">City, State, Zip</label>
      <input name="City" type="text" id="City" value="<? echo $City; ?>" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='City'; return true;" onmouseout="window.status=''; return true;" title="City" class="CstmFrmElmntInput" />
      <span style="float:left; clear:none; margin-right:5px;" id="State_Box">
      <script type="text/javascript">AEV_GetState('<? echo $Country; ?>','<? echo $State; ?>','','');</script>
      </span>
      <input name="Zip" type="text" id="Zip" value="<? echo $Zip; ?>" onfocus="javascript:this.className='CstmFrmElmntInputi117NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi117';" onmouseover="window.status='Zip'; return true;" onmouseout="window.status=''; return true;" title="Zip" class="CstmFrmElmntInputi117" />
      <? } ?>
      <br />
      <? if(!$is_enabled) echo $Country; else {
					$getCnty = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
				 	$getCnty->mysql("SELECT `country_short_3`, `country_name` FROM `a_country` WHERE `country_use` = 'y' ORDER BY `country_name` ASC;"); ?>
      <label for="Country" class="CstmFrmElmntLabel">Country</label>
      <select name="Country" id="Country" onchange="javascript:AEV_GetState(document.getElementById('Country').value,false,false,'');" class="CstmFrmElmnt" title="Country">
        <option value="0" title="Select Country"> -- Select Country -- </option>
        <? foreach($getCnty->Rows() as $r){ ?>
        <option value="<? echo $r['country_short_3']; ?>"<? if($Country == $r['country_short_3'])echo ' selected="selected"';?> title="<? echo $r['country_name']; ?>"><? echo $r['country_name']; ?></option>
        <? } ?>
      </select>
      <? } ?>
      <br />
      <? if(!$is_enabled) echo ($Phone != "0")?"(".$P1.") ".$P2."-".$P3:''; else { ?>
      <label for="P1" class="CstmFrmElmntLabel">Phone Number</label>
      <input name="P1" type="text" id="P1" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Phone Number: Area Code'; return true;" onmouseout="window.status=''; return true;" title="Phone Number: Area Code" value="<? echo $P1;?>" maxlength="3" onkeyup="AEV_set_tel_number('Phone_Number','P');" />
      <input name="P2" type="text" id="P2" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Phone Number: Prefix'; return true;" onmouseout="window.status=''; return true;" title="Phone Number: Prefix" value="<? echo $P2;?>" maxlength="3" onkeyup="AEV_set_tel_number('Phone_Number','P');" />
      <input name="P3" type="text" id="P3" size="8" class="CstmFrmElmntInputi34" onfocus="javascript:this.className='CstmFrmElmntInputi34NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi34';" onmouseover="window.status='Phone Number: Suffix'; return true;" onmouseout="window.status=''; return true;" title="Phone Number: Suffix" value="<? echo $P3;?>" maxlength="4" onkeyup="AEV_set_tel_number('Phone_Number','P');" />
      <input type="hidden" name="Phone Number" id="Phone_Number" value="<? echo $Phone;?>" />
      <? } ?>
      <br />
      <? if(!$is_enabled){ ?>
      <a href="mailto:<? echo $Email; ?>"><? echo $Email; ?></a>
      <? } else { ?>
      <label for="Email" class="CstmFrmElmntLabel">E-mail</label>
      <input type="text" name="Email" id="Email" value="<? echo $Email;?>" maxlength="125" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='E-mail'; return true;" onmouseout="window.status=''; return true;" title="E-mail" class="CstmFrmElmntInput" />
      <? } ?>
      <br />
    </p>
    <br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
