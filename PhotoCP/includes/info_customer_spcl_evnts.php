<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
if($cont == "view") $is_enabled = false; else	$is_enabled = true;
if($cont == "add") $back = implode(",",array_slice($path,0,-1)); else $back = implode(",",$path);
if($cont == "edit") require_once($r_path.'includes/info_calendar.php'); ?>
<script type="text/javascript">
function prevEdtEml(ID, VALS, TYPE, EDT){ 
	var URL = '/PhotoCP/EditEmail.php?id='+ID+'&vals='+VALS+'&type='+TYPE+'&edit='+EDT;
	AEV_new_window(URL,'EventMarketing');
}
</script>

<h1 id="HdrType2" class="ClntSpclDate">
  <div>Special Dates</div>
</h1>
<div id="HdrLinks">
  <? if($cont=="view"){ ?>
  <a href="javascript:set_form('','<? echo implode(",",$path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" title="Edit Customer <? echo $FName.' '.(($MInt != '')?$MInt.'. ':'').$LName; ?>" onmouseover="window.status='Edit Customer <? echo $FName.' '.(($MInt != '')?$MInt.'. ':'').$LName; ?>'; return true;" onmouseout="window.status=''; return true;" class="BtnEdit2">Edit</a> <a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,-2)); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Cancel'; return true;" onmouseout="window.status=''; return true;" title="Cancel" class="BtnCancel">Cancel</a>
  <? } else { ?>
  <a href="#" onclick="javascript: document.getElementById('Email_Notes').value = window.frames[0].tinyMCE.activeEditor.getContent()
; <? echo $onclick; ?> return false;" onmouseover="window.status='Save Customer Information'; return true;" onmouseout="window.status=''; return true;" title="Save Customer Information" class="BtnSave2">Save</a><a href="#" onclick="javascript:set_form('form_','<? echo $back; ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Cancel'; return true;" onmouseout="window.status=''; return true;" title="Cancel" class="BtnCancel">Cancel</a>
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
  <div id="Records" class="Colmn3"> <span style="width:195px;">
    <label for="Name" class="CstmFrmElmntLabel">Name</label>
    <? if(!$is_enabled)echo $Name; else { ?>
    <input type="text" name="Name" id="Name" value="<? echo $Name;?>" maxlength="75" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Event Name'; return true;" onmouseout="window.status=''; return true;" title="Event Name" class="CstmFrmElmntInput" />
    <? } ?>
    </span> <span style="width:275px;">
    <label for="Event_Month" class="CstmFrmElmntLabel">Date</label>
    <? if(!$is_enabled)echo format_date($Date,"Long",false,true,false); else { ?>
    <div style="float:left; clear:none;">
      <select name="Event Month" id="Event_Month" class="CstmFrmElmnt88" onmouseover="window.status='Month'; return true;" onmouseout="window.status=''; return true;" onchange="javascript:SetDates();" title="Month">
        <? $TDate = date("m",mktime(0,0,1,substr($Date,5,2),1,date("Y")));
				for($n = 0; $n < 12; $n++){ $TDate2 = date("m",mktime(0,0,1,($n+1),1,date("Y"))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo date("F",mktime(0,0,1,($n+1),1,date("Y"))); ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo date("F",mktime(0,0,1,($n+1),1,date("Y"))); ?></option>
        <? } ?>
      </select>
    </div>
    <div style="float:left; clear:none; margin-left:3px; margin-right:3px;">
      <select name="Event Day" id="Event_Day" class="CstmFrmElmnt53" onmouseover="window.status='Day'; return true;" onmouseout="window.status=''; return true;" onchange="javascript:SetDates();" title="Day">
        <? $TDate = date("d",mktime(0,0,1,1,substr($Date,8,2),date("Y")));
				for($n = 0; $n < 31; $n++){ $TDate2 = date("d",mktime(0,0,1,1,($n+1),date("Y"))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
        <? } ?>
      </select>
    </div>
    <div style="float:left; clear:none;">
      <select name="Event Year" id="Event_Year" class="CstmFrmElmnt64" onmouseover="window.status='Year'; return true;" onmouseout="window.status=''; return true;" onchange="javascript:SetDates();" title="Year">
        <? $TDate = date("Y",mktime(0,0,1,1,1,substr($Date,0,4)));
				for($n = -107; $n < 5; $n++){ $TDate2 = date("Y",mktime(0,0,1,1,1,(date("Y")+$n))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
        <? } ?>
      </select>
    </div>
    <div id="BtnCalendar"><a href="javascript:StartCalDate('EventCalendar','Event_Year','Event_Month','Event_Day',null);" onmouseover="window.status='Start Calendar'; return true;" onmouseout="window.status=''; return true;" title="Start Calendar" id="EventCalendar">Calendar</a></div>
    <? } ?>
    </span> <span>
    <?
			$RcrArr = array(
													"Non Recurring" 											=> 0,
												 	"One Time" 														=> 1,
													"Monthly" 														=> 2,
													"Yearly" 															=> 3
											);
			/*$ID = 4;
			$Cnters = array("First","Second","Third","Forth","Last");
			$Days = array("Day","Weekday","Weekend Day","Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
			foreach($Cnters as $Cntr){
				$RcrArr["------- Monthly ".$Cntr." ------"] = '';
				foreach($Days as $Day){
					$RcrArr["Every Month ".$Cntr." ".$Day] = $ID++;
				}
			}
			for($n=0; $n<12; $n++){ $Month = date("M",mktime(0,0,0,($n+1),1,date("Y")));
				foreach($Cnters as $Cntr){
					$RcrArr["------- Yearly ".$Month.": ".$Cntr." ------"] = '';
					foreach($Days as $Day){
						$RcrArr["Every ".$Month." on the ".$Cntr." ".$Day] = $ID++;
					}
				}
			} */
		?>
    <label for="Frequency" class="CstmFrmElmntLabel">Recurring Frequency</label>
    <? if(!$is_enabled){ foreach($RcrArr as $k => $v){ if($v == $Freg){ echo $k; break; } } } else { ?>
    <div style="float:left; clear:none;">
      <select name="Frequency" id="Frequency" class="CstmFrmElmnt" onmouseover="window.status='Recurring Frequency'; return true;" onmouseout="window.status=''; return true;" onchange="javascript:SetDates();" title="Recurring Frequency">
        <? foreach($RcrArr as $k => $v){ ?>
        <option value="<? echo $v; ?>" title="<? echo $k; ?>"<? if($Freg == $v) echo ' selected="selected"'; ?>><? echo $k; ?></option>
        <? } ?>
      </select>
    </div>
    <? } ?>
    </span><br clear="all" />
    <hr />
    <br clear="all" />
  </div>
  <div id="Records" class="Colmn">
    <div class="fontSpecial5-3">
      <? if(!$is_enabled){ 
				if($EvntNot=='y') echo '<p style="width:470px;">Send out email reminded '.$EvntTme.' days prior to event</p>';
				else echo '<p style="width:470px;">Don\'t send out email reminded</p>';
			} else { ?>
      <p style="width:195px; padding-top:3px;">
        <input type="checkbox" name="Email Notification" id="Email_Notification" value="y" class="CstmFrmElmnt" title="Send Email Notification" onmouseover="window.status='Send Email Notification'; return true;" onmouseout="window.status=''; return true;" <? if($EvntNot == 'y') echo ' checked="checked"'; ?> />
        Email Reminder</p>
      <p style="width:275px;">
        <select name="Notification Time" id="Notification_Time" class="CstmFrmElmnt" onmouseover="window.status='Notification Time'; return true;" onmouseout="window.status=''; return true;" onchange="javascript:SetDates();" title="Notification Time">
          <option value="30" title="30 Days"<? if($EvntTme == "30") echo ' selected="selected"'; ?>>30 Days</option>
          <option value="15" title="15 Days"<? if($EvntTme == "15") echo ' selected="selected"'; ?>>15 Days</option>
          <option value="10" title="10 Days"<? if($EvntTme == "10") echo ' selected="selected"'; ?>>10 Days</option>
          <option value="5" title="5 Days"<? if($EvntTme == "5") echo ' selected="selected"'; ?>>5 Days</option>
        </select>
        &nbsp;Prior to Event </p>
      <? } /* ?>
      <p style="width:210px;"><span class="BtnPrev"><a href="javascript: prevEdtEml('<? echo $CId; ?>','<? echo $EId; ?>','2','true');" title="Preview Email">Preview Email</a></span></p> */ ?>
    </div>
    <div style="padding-left:5px;">
      <iframe id="EditEmail" src="/PhotoCP/EditEmail.php?id=<? echo $CId; ?>&vals=<? echo $EId; ?>&type=2&edit=<? echo ($is_enabled)?"true":"false"; ?>&Ajax=true" width="715" height="700" style=" border:solid 1px #333333;" frameborder="0" scrolling="auto"></iframe>
    </div>
    <input type="hidden" name="Email Notes" id="Email_Notes" value="" />
    <br clear="all"/>
    <br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
