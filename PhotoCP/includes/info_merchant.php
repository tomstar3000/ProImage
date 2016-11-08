<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
if($cont == "view") $is_enabled = false; else	$is_enabled = true;
require_once($r_path.'includes/info_calendar.php');
if($cont == 'add') $HotMenu = "Busn,Mrch:add"; $Key = array_search($HotMenu, $StrArray);
 ?>
<script type="text/javascript">
function UsrAggr(){
	HTML = "<p><input type=\"checkbox\" onclick=\"<? echo $onclick; ?>\" title=\"I agree to the User Agreement\" value=\"1\" />By check marking this box I agree to pay the agreed upon percentage for processing this transaction. I also understand that should I need to refund this transaction I will still be charged the processing fee for this transaction. I [photographer name] am responsible for this charge and have full rights from the client to charge their credit card for the amount I enter into this system. I do not hold Pro Image Software, Inc. responsible for fraudulent activity and take full responsibility for any charge backs that may occur due to this credit card transaction. Should I need to refund Pro Image Software, Inc. for any charge backs due to the client not accepting this charge I agree to allow Pro Image Software, Inc. the full amount of the charge plus any processing fees that may be occurred.</p>";
	send_Msg(HTML,true,null,null);
}
</script>

<h1 id="HdrType2" class="BsnMerchant">
  <div>Merchant Terminal</div>
</h1>
<div id="HdrLinks">
  <? if($is_enabled){ if($cont == 'edit'){ /* ?>
  <a href="#" onclick="javascript: UsrAggr(); return false;" onmouseover="window.status='Save Invoice'; return true;" onmouseout="window.status=''; return true;" title="Save Invoice" class="BtnSave2">Save</a><? */ ?>
  <a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,2)); ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Cancel'; return true;" onmouseout="window.status=''; return true;" title="Cancel" class="BtnCancel">Cancel</a>
  <? } ?>
  <a href="#" onclick="javascript: UsrAggr(); return false;" onmouseover="window.status='Process'; return true;" onmouseout="window.status=''; return true;" title="Process" class="BtnProcess">Process</a>
  <? } else if($Proc == 'n' || ($Proc == 'y' && $Fail == 'y')){ ?>
  <a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Edit Invoice'; return true;" onmouseout="window.status=''; return true;" title="Edit Invoice" class="BtnEdit2">Edit</a>
  <? } else if ($Ref == 'n'){ ?>
  <a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','refund','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Refund Invoice'; return true;" onmouseout="window.status=''; return true;" title="Refund Invoice" class="BtnRefund">Refund</a>
  <? } if(isset($HotMenu)){ ?>
  <a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key!==false)?'2':'1'; ?>);" title="Add to Hot Menu" onmouseover="window.status='Add to Hot Menu'; return true;" onmouseout="window.status=''; return true;" class="BtnHotMenu<? echo ($Key!==false)?'Added':''; ?>" id="BtnHotMenu">Add to Hot Menu</a>
  <? } ?>
</div>
<p style="color: #ff0000;padding-left:15px; padding-right:15px;">Merchant Terminal transactions are provided as a convenience to our members who do not take credit cards.  There is a credit card processing/handling fee of 6% of the amount of the transaction.  The amount collected minus this fee will be added to your end of month sales.</p>
<? if($Fail == 'y' || (isset($Error) && strlen(trim($Error)) > 0)){
	if($Fail == 'y' && strlen(trim($Error)) == 0) $Error = $FailMsg; ?>
<h1 id="HdrType2" class="EvntInfo2">
  <div>Notice</div>
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
<div id="RecordTabs"> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Mrch"; ?>','add','','');" id="BtnTransNew"<? if($cont=="add") echo ' class="NavSel"'; ?>>New Transaction</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Mrch"; ?>','query','','');" id="BtnTransPend">Pending Transactions</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Past"; ?>','query','','');" id="BtnTransPast">Past Transactions</a><br clear="all" />
</div>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records" class="Colmn3">
    <p>
      <label for="Event" class="CstmFrmElmntLabel">Select Event</label>
    </p>
    <span style="width:175px;">
    <? if(!$is_enabled){
				if($EID == 0 || $EID == "") echo "No Event";
				else {
				 $getEvent = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
				 $getEvent->mysql("SELECT `event_id`, `event_name` FROM `photo_event` WHERE `event_id` = '$EID';");
				 $getEvent = $getEvent->Rows(); echo $getEvent[0]['event_name'];
				}
		 } else { ?>
    <select name="Event" id="Event" class="CstmFrmElmnt" onmouseover="window.status='Select Event'; return true;" onmouseout="window.status=''; return true;" title="Select Event">
      <option value="0" title="Select Event">-- Select Event --</option>
      <option value="0" title="No Event">No Event</option>
      <? $getEvent = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
				 $getEvent->mysql("SELECT `event_id`, `event_name` FROM `photo_event` 
													WHERE `cust_id` = '$CustId' AND (`event_use` = 'y' 
															 OR (`event_use` = 'n' AND `event_del` = 'n' AND `event_updated` > '".date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d"),date("Y")-1))."'
																AND `event_updated` > '".date('Y-m-d H:i:s',mktime(0,0,0,1,1,2009))."')) ORDER BY `event_name` ASC;");
				 foreach($getEvent->Rows() as $r){ ?>
      <option value="<? echo $r['event_id']; ?>" title="<? echo $r['event_name']; ?>"<? if($r['event_id']==$EID) echo ' selected="selected"'; ?>><? echo $r['event_name']; ?></option>
      <? } ?>
    </select>
    <? } ?>
    </span>
    <? if($is_enabled){ ?>
    <span>
    <div id="BtnEdit2" style="float:left"><a href="javascript:set_form('','Evnt,Evnt','add','','');" onmouseover="window.status='Create Event'; return true;" onmouseout="window.status=''; return true;" title="Create Event">Create Event</a></div>
    </span>
    <? } ?>
    <br clear="all" />
    <hr />
    <span>
    <label for="First_Name" class="CstmFrmElmntLabel">First Name</label>
    <? if(!$is_enabled)echo $FName; else { ?>
    <input type="text" name="First Name" id="First_Name" value="<? echo $FName;?>" maxlength="75" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='First Name'; return true;" onmouseout="window.status=''; return true;" title="First Name" class="CstmFrmElmntInput" />
    <? } ?>
    </span><span>
    <label for="Last_Name" class="CstmFrmElmntLabel">Last Name</label>
    <? if(!$is_enabled)echo $LName; else { ?>
    <input type="text" name="Last Name" id="Last_Name" value="<? echo $LName;?>" maxlength="75" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Last Name'; return true;" onmouseout="window.status=''; return true;" title="Last Name" class="CstmFrmElmntInput" />
    <? } ?>
    </span> <br clear="all" />
    <hr />
    <span>
    <label for="P1" class="CstmFrmElmntLabel">Billing Number</label>
    <? if(!$is_enabled) echo ($Phone != "0")?"(".$P1.") ".$P2."-".$P3:''; else { ?>
    <input name="P1" type="text" id="P1" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Phone Number: Area Code'; return true;" onmouseout="window.status=''; return true;" title="Phone Number: Area Code" value="<? echo $P1;?>" maxlength="3" onkeyup="AEV_set_tel_number('Phone_Number','P');" />
    <input name="P2" type="text" id="P2" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Phone Number: Prefix'; return true;" onmouseout="window.status=''; return true;" title="Phone Number: Prefix" value="<? echo $P2;?>" maxlength="3" onkeyup="AEV_set_tel_number('Phone_Number','P');" />
    <input name="P3" type="text" id="P3" size="8" class="CstmFrmElmntInputi34" onfocus="javascript:this.className='CstmFrmElmntInputi34NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi34';" onmouseover="window.status='Phone Number: Suffix'; return true;" onmouseout="window.status=''; return true;" title="Phone Number: Suffix" value="<? echo $P3;?>" maxlength="4" onkeyup="AEV_set_tel_number('Phone_Number','P');" />
    <input type="hidden" name="Phone Number" id="Phone_Number" value="<? echo $Phone;?>" />
    <? } ?>
    </span> <span>
    <label for="Email" class="CstmFrmElmntLabel">E-mail</label>
    <? if(!$is_enabled) echo $Email; else { ?>
    <input type="text" name="Email" id="Email" value="<? echo $Email;?>" maxlength="125" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='E-mail'; return true;" onmouseout="window.status=''; return true;" title="E-mail" class="CstmFrmElmntInput" />
    <? } ?>
    </span> <br clear="all" />
    <hr />
    <p><strong>Billing Address</strong></p>
    <span>
    <label for="Address" class="CstmFrmElmntLabel">Address</label>
    <? if(!$is_enabled) echo $Add;  else { ?>
    <input name="Address" type="text" id="Address" value="<? echo $Add;?>" maxlength="125" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Address'; return true;" onmouseout="window.status=''; return true;" title="Address" class="CstmFrmElmntInput" />
    <? } ?>
    </span> <span style="width:185px; padding-right:5px;">
    <label for="Suite_Apt" class="CstmFrmElmntLabel">Suite / Apt</label>
    <? if(!$is_enabled) echo (($SApt != "") ? $SApt:'');  else { ?>
    <input type="text" name="Suite Apt" id="Suite_Apt" value="<? echo $SApt;?>" maxlength="25" size="10" onfocus="javascript:this.className='CstmFrmElmntInputi34NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi34';" onmouseover="window.status='Suite/Apt'; return true;" onmouseout="window.status=''; return true;" title="Suite/Apt" class="CstmFrmElmntInputi34" />
    <? } ?>
    </span> <br clear="all" />
    <span>
    <label for="Address_2" class="CstmFrmElmntLabel">Address Second Line</label>
    <? if(!$is_enabled) echo $Add2; else { ?>
    <input type="text" name="Address 2" id="Address_2" value="<? echo $Add2;?>" maxlength="125" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Address Second Line'; return true;" onmouseout="window.status=''; return true;" title="Address Second Line" class="CstmFrmElmntInput" />
    <? } ?>
    </span> <br clear="all" />
    <span>
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
    </span> <span style="width:115px">
    <label for="City" class="CstmFrmElmntLabel">City</label>
    <? if(!$is_enabled) echo $City; else { ?>
    <input name="City" id="City" type="text" value="<? echo $City; ?>" onfocus="javascript:this.className='CstmFrmElmntInputi117NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi117';" onmouseover="window.status='City'; return true;" onmouseout="window.status=''; return true;" title="City" class="CstmFrmElmntInputi117" />
    <? } ?>
    </span> <span style="width:80px">
    <label for="State" class="CstmFrmElmntLabel">State</label>
    <? if(!$is_enabled) echo $State; else { ?>
    <span style="float:left; clear:none;" id="State_Box">
    <script type="text/javascript">AEV_GetState('<? echo $Country; ?>','<? echo $State; ?>','','');</script>
    </span>
    <? } ?>
    </span> <span style="width:260px;">
    <label for="Zip" class="CstmFrmElmntLabel">Zip</label>
    <? if(!$is_enabled) echo $Zip; else { ?>
    <input name="Zip" id="Zip" type="text" value="<? echo $Zip; ?>" onfocus="javascript:this.className='CstmFrmElmntInputi117NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi117';" onmouseover="window.status='Zip'; return true;" onmouseout="window.status=''; return true;" title="Zip" class="CstmFrmElmntInputi117" />
    <? } ?>
    </span> <br clear="all" />
    <hr />
    <p><strong>Transaction Details</strong></p>
    <br clear="all" />
    <span>
    <label for="Type of Card" class="CstmFrmElmntLabel">Card Type</label>
    <? $getCards = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			 $getCards->mysql("SELECT * FROM `billship_cc_types` WHERE `cc_accept` = 'y' ORDER BY `cc_order` ASC;");
			 if(!$is_enabled){ foreach($getCards->Rows() as $r){if($CType == $r['cc_type_id']){ echo $r['cc_type_name']; break; } } } else { ?>
    <select name="Type of Card" id="Type_of_Card" class="CstmFrmElmnt" onmouseover="window.status='Credit Card Type'; return true;" onmouseout="window.status=''; return true;" title="Credit Card Type">
      <? foreach($getCards->Rows() as $r){ ?>
      <option value="<? echo $r['cc_type_id']; ?>"<? if($CType == $r['cc_type_id']) echo ' selected="selected"';?> title="<? echo $r['cc_type_name']; ?>"><? echo $r['cc_type_name']; ?></option>
      <? } ?>
    </select>
    <? } ?>
    </span> <span style="width:208px;">
    <label for="Credit_Card_Number" class="CstmFrmElmntLabel">Credit Card Number</label>
    <? if(strlen(trim($CC4Num)) > 0) { while(strlen(trim($CC4Num)) < 4) $CC4Num = "0".$CC4Num; } if($is_enabled) { ?>
    <input type="text" name="Credit Card Number" id="Credit_Card_Number" value="<? if(strlen(trim($CC4Num)) > 0) echo 'xxxx-xxxx-xxxx-'.$CC4Num; ?>" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Credit Card Number'; return true;" onmouseout="window.status=''; return true;" title="Credit Card Number" class="CstmFrmElmntInput" />
    <? } else echo "xxxx-xxxx-xxxx-".$CC4Num; ?>
    </span> <span>
    <label for="Experation_Month" class="CstmFrmElmntLabel">Expiration Date</label>
    <? if(!$is_enabled) echo $CCM." / ".$CCY; else { ?>
    <div style="float:left; clear:none;">
      <select name="Experation Month" id="Experation_Month" tabindex="<? echo $tab++; ?>" class="CstmFrmElmnt64"  onmouseover="window.status='Experation Month'; return true;" onmouseout="window.status=''; return true;" title="Experation Month">
        <? for($n=1;$n<=12;$n++){ ?>
        <option value="<? echo $n; ?>"<? if($CCM == $n){ echo ' selected="selected"'; }?> title="<? echo $n; ?>"><? echo $n; ?></option>
        <? } ?>
      </select>
    </div>
    <div style="float:left; clear:none; margin-left:5px;">
      <select name="Experation Year" id="Experation_Year" tabindex="<? echo $tab++; ?>" class="CstmFrmElmnt64"  onmouseover="window.status='Experation Year'; return true;" onmouseout="window.status=''; return true;" title="Experation Year">
        <? $date = date("Y"); for($n=0;$n<10;$n++){ ?>
        <option value="<? echo ($date+$n); ?>"<? if($CCY == ($date+$n)){print(' selected="selected"');}?> title="<? echo ($date+$n); ?>"><? echo ($date+$n); ?></option>
        <? } ?>
      </select>
    </div>
    <? } ?>
    </span><br clear="all" />
    <span>
    <label for="CCV_Code" class="CstmFrmElmntLabel">CCV Security Code</label>
    <? if(!$is_enabled) echo $CCV; else { ?>
    <input type="text" name="CCV Code" id="CCV_Code" value="<? echo $CCV; ?>" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='CCV Security Code'; return true;" onmouseout="window.status=''; return true;" title="CCV Security Code" class="CstmFrmElmntInput" />
    <? } ?>
    </span><span style="width:208px;">
    <label for="Charge Amount" class="CstmFrmElmntLabel">Charge Amount</label>
    <? if(!$is_enabled) echo $Amt; else { ?>
    <input type="text" name="Charge Amount" id="Charge_Amount" value="<? echo $Amt; ?>" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Charge Amount'; return true;" onmouseout="window.status=''; return true;" title="Charge Amount" class="CstmFrmElmntInput" />
    <? } ?>
    </span> <span style="width:260px;"><? /*
    <label for="Process_Month" class="CstmFrmElmntLabel">Process Date</label>
    <? if(!$is_enabled) echo format_date($PDate,"Short",false,true,false); else { ?>
    <div style="float:left; clear:none;">
      <select name="Process Month" id="Process_Month" class="CstmFrmElmnt88" onmouseover="window.status='Month of Card Processing'; return true;" onmouseout="window.status=''; return true;" title="Process Date of Card">
        <? $TDate = date("m",mktime(0,0,1,substr($PDate,5,2),1,date("Y")));
				for($n = 0; $n < 12; $n++){ $TDate2 = date("m",mktime(0,0,1,($n+1),1,date("Y"))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo date("F",mktime(0,0,1,($n+1),1,date("Y"))); ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo date("F",mktime(0,0,1,($n+1),1,date("Y"))); ?></option>
        <? } ?>
      </select>
    </div>
    <div style="float:left; clear:none; margin-left:3px; margin-right:3px;">
      <select name="Process Day" id="Process_Day" class="CstmFrmElmnt53" onmouseover="window.status='Day of Card Processing'; return true;" onmouseout="window.status=''; return true;" title="Process Date of Card">
        <? $TDate = date("d",mktime(0,0,1,1,substr($PDate,8,2),date("Y")));
				for($n = 0; $n < 31; $n++){ $TDate2 = date("d",mktime(0,0,1,1,($n+1),date("Y"))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
        <? } ?>
      </select>
    </div>
    <div style="float:left; clear:none;">
      <select name="Process Year" id="Process_Year" class="CstmFrmElmnt64" onmouseover="window.status='Year of Card Processing'; return true;" onmouseout="window.status=''; return true;" title="Process Date of Card">
        <? $TDate = date("Y",mktime(0,0,1,1,1,substr($PDate,0,4)));
				for($n = -2; $n < 5; $n++){ $TDate2 = date("Y",mktime(0,0,1,1,1,(date("Y")+$n))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
        <? } ?>
      </select>
    </div>
    <div id="BtnCalendar"><a href="javascript:StartCalDate('ProcessCalendar','Process_Year','Process_Month','Process_Day',null);" onmouseover="window.status='Start Calendar'; return true;" onmouseout="window.status=''; return true;" title="Start Calendar" id="ProcessCalendar">Calendar</a></div>
    <? } ?>*/ ?>
    </span> <br clear="all" />
    <br clear="all" />
    <p>
      <label for="Transaction Detail" class="CstmFrmElmntLabel">Transaction Detail</label>
    </p>
    <? if(!$is_enabled) echo '<p>'.$Det.'</p>'; else { ?>
    <div class="CstmFrmElmntTextField" style="margin-left:15px;">
      <textarea name="Transaction Detail" id="Transaction_Detail" onfocus="javascript:this.parentNode.className='CstmFrmElmntTextFieldNavSel';" onblur="javascript:this.parentNode.className='CstmFrmElmntTextField';" onmouseover="window.status='Message'; return true;" onmouseout="window.status=''; return true;" title="Message"><? echo $Det; ?></textarea>
    </div>
    <? } ?>
    <br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
