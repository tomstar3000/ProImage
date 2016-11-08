<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
if($cont == "view") $is_enabled = false; else	$is_enabled = true;
if($cont == "add") $back = implode(",",array_slice($path,0,-1)); else $back = implode(",",$path); ?>

<script type="text/javascript" src="/PhotoCP/javascript/cust_rel_info.php"></script>
<h1 id="HdrType2" class="ClntRelation">
  <div>Client Relationships</div>
</h1>
<div id="HdrLinks">
  <? if($cont=="view"){ ?>
  <a href="javascript:set_form('','<? echo implode(",",$path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" title="Edit Relationship" onmouseover="window.status='Edit Relationship'; return true;" onmouseout="window.status=''; return true;" class="BtnEdit2">Edit</a> <a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,-2)); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Cancel'; return true;" onmouseout="window.status=''; return true;" title="Cancel" class="BtnCancel">Cancel</a>
  <? } else { ?>
  <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status='Save Relationship'; return true;" onmouseout="window.status=''; return true;" title="Save Relationship" class="BtnSave2">Save</a><a href="#" onclick="javascript:set_form('form_','<? echo $back; ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Cancel'; return true;" onmouseout="window.status=''; return true;" title="Cancel" class="BtnCancel">Cancel</a>
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
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records" class="Colmn3"> <span>
    <? if($is_enabled){ ?>
    <label class="CstmFrmElmntLabel">Select From Client List</label>
    <div class="btnClntLst"><a href="javascript:ClntList();" onmouseover="window.status='Open Client List'; return true;" onmouseout="window.status=''; return true;" title="Open Client List">Open Client List</a></div>
    <? } ?>
    </span> <span>
    <label for="Relationship_1" class="CstmFrmElmntLabel">How is this person related to client</label>
    <? if(!$is_enabled){ $getList = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
					 $getList->mysql("SELECT * FROM `cust_relationships` WHERE `cust_rel_use` = 'y' ORDER BY `cust_rel_name` ASC;");
					 foreach($getList->Rows() as $r){ if($Rel1 == $r['cust_rel_id']) echo $r['cust_rel_name']; } } else { ?>
    <div style="float:left; clear:none;">
      <select name="Relationship_1" id="Relationship_1" class="CstmFrmElmnt" onmouseover="window.status='Relationship'; return true;" onmouseout="window.status=''; return true;" title="Relationship">
        <option value="" title="-- Select --">-- Select --</option>
        <? $getList = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
					 $getList->mysql("SELECT * FROM `cust_relationships` WHERE `cust_rel_use` = 'y' ORDER BY `cust_rel_name` ASC;");
				foreach($getList->Rows() as $r){ ?>
        <option value="<? echo $r['cust_rel_id']; ?>" title="<? echo $r['cust_rel_name']; ?>"<? if($Rel1 == $r['cust_rel_id']) echo ' selected="selected"'; ?>><? echo $r['cust_rel_name']; ?></option>
        <? } ?>
      </select>
    </div>
    <? } ?>
    </span> <span>
    <label for="Relationship_2" class="CstmFrmElmntLabel">How is client related to this person</label>
    <? if(!$is_enabled){ $getList = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
					 $getList->mysql("SELECT * FROM `cust_relationships` WHERE `cust_rel_use` = 'y' ORDER BY `cust_rel_name` ASC;");
					 foreach($getList->Rows() as $r){ if($Rel2 == $r['cust_rel_id']) echo $r['cust_rel_name']; } }  else { ?>
    <div style="float:left; clear:none;">
      <select name="Relationship_2" id="Relationship_2" class="CstmFrmElmnt" onmouseover="window.status='Relationship'; return true;" onmouseout="window.status=''; return true;" title="Relationship">
        <option value="" title="-- Select --">-- Select --</option>
        <? $getList = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
						$getList->mysql("SELECT * FROM `cust_relationships` WHERE `cust_rel_use` = 'y' ORDER BY `cust_rel_name` ASC;");
				foreach($getList->Rows() as $r){ ?>
        <option value="<? echo $r['cust_rel_id']; ?>" title="<? echo $r['cust_rel_name']; ?>"<? if($Rel2 == $r['cust_rel_id']) echo ' selected="selected"'; ?>><? echo $r['cust_rel_name']; ?></option>
        <? } ?>
      </select>
    </div>
    <? } ?>
    </span><br clear="all" />
  </div>
  <div id="Records" class="Colmn"> <br clear="all" />
    <hr />
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
    </p>
    <br clear="all" />
    <br clear="all" />
    <hr />
    <p>
      <label for="Notes" class="CstmFrmElmntLabel">Notes</label>
    </p>
    <? if(!$is_enabled) echo '<p>'.$Notes.'</p>'; else { ?>
    <div class="CstmFrmElmntTextField" style="margin-left:15px;">
      <textarea name="Notes" id="Notes" onfocus="javascript:this.parentNode.className='CstmFrmElmntTextFieldNavSel';" onblur="javascript:this.parentNode.className='CstmFrmElmntTextField';" onmouseover="window.status='Notes'; return true;" onmouseout="window.status=''; return true;" title="Notes"><? echo $Notes; ?></textarea>
    </div>
    <? } ?>
    <input type="hidden" name="ClientID" id="ClientID" value="<? echo $ClntID; ?>" />
    <br clear="all" />
    <br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
