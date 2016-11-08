<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
require_once($r_path.'includes/info_calendar.php');
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>
<script type="text/javascript">
function SetRoll(ACT){ var Inpts = document.getElementsByTagName('input');
	for(var n=0; n<Inpts.length; n++){ if(Inpts[n].id=="Rolling_Credit" && Inpts[n].value==ACT){
			Inpts[n].checked = true; Inpts[n].onchange(); break;
		} } }
function UpdRoll(VAL){ var Inpts = document.getElementsByTagName('input');
	for(var n=0; n<Inpts.length; n++){ if(Inpts[n].id=="Rolling_Credit" && Inpts[n].checked){
			if(Inpts[n].value=='y'){ document.getElementById('Percent').value='0';
		} } } }
</script>
<h1 id="HdrType2" class="BsnDiscCodes">
  <div>Discount Code Information</div>
</h1>
<div id="HdrLinks">
  <? if($is_enabled){ ?>
  <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status='Save Discount Information'; return true;" onmouseout="window.status=''; return true;" title="Save Discount Information" class="BtnSave2">Save</a><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','<? echo ($cont == "add")?'query':'view'; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Cancel'; return true;" onmouseout="window.status=''; return true;" title="Cancel" class="BtnCancel">Cancel</a>
  <? } else { ?>
  <a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Edit Discount Code <? echo $CName; ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit Discount Code <? echo $CName; ?>" class="BtnEdit2">Edit</a>
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
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records" class="Colmn2"> <span>
    <label for="Name" class="CstmFrmElmntLabel">Discount Name</label>
    <? if(!$is_enabled) echo $CName; else { ?>
    <input type="text" name="Name" id="Name" maxlength="75" class="CstmFrmElmntInput" title="Discount Name" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Discount Name'; return true;" onmouseout="window.status=''; return true;" value="<? echo $CName; ?>" />
    <? } ?>
    <br />
    <label for="Code" class="CstmFrmElmntLabel">Code</label>
    <? if(!$is_enabled) echo $CCode; else { ?>
    <input type="text" name="Code" id="Code" maxlength="50" class="CstmFrmElmntInput" title="Discount Code" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Discount Code'; return true;" onmouseout="window.status=''; return true;" value="<? echo $CCode; ?>" />
    <? } ?>
    <br />
    <label for="Percent" class="CstmFrmElmntLabel">Percent</label>
    <? if(!$is_enabled) echo $CPercent."%"; else { ?>
    <input type="text" name="Percent" id="Percent" maxlength="50" class="CstmFrmElmntInput" title="Discount Percent" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Discount Percent'; return true;" onmouseout="window.status=''; return true;" value="<? echo $CPercent; ?>" onkeyup="javascript: if(this.value>0) SetRoll('n');" />
    <? } ?>
    <br />
    <label for="Price" class="CstmFrmElmntLabel">Price</label>
    <? if(!$is_enabled) echo "$".number_format($CPrice,2,".",","); else { ?>
    <input type="text" name="Price" id="Price" maxlength="50" class="CstmFrmElmntInput" title="Discount Price" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Discount Price'; return true;" onmouseout="window.status=''; return true;" value="<? echo $CPrice; ?>" />
    <? } ?>
    </span> <span>
    <label for="Disc_Month" class="CstmFrmElmntLabel">Discontinue</label>
    <? if(!$is_enabled) echo ($CDiscon != "" && $CDiscon != "0000-00-00 00:00:00")?format_date($CDiscon,"Dash",false,true,false):''; else { ?>
    <div style="float:left; clear:none;">
      <select name="Disc Month" id="Disc_Month" class="CstmFrmElmnt88" onmouseover="window.status='Discount Month'; return true;" onmouseout="window.status=''; return true;" title="Discount Month">
        <? $TDate = date("m",mktime(0,0,1,substr($CDiscon,5,2),1,date("Y")));
				for($n = 0; $n < 12; $n++){ $TDate2 = date("m",mktime(0,0,1,($n+1),1,date("Y"))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo date("F",mktime(0,0,1,($n+1),1,date("Y"))); ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo date("F",mktime(0,0,1,($n+1),1,date("Y"))); ?></option>
        <? } ?>
      </select>
    </div>
    <div style="float:left; clear:none; margin-left:3px; margin-right:3px;">
      <select name="Disc Day" id="Disc_Day" class="CstmFrmElmnt53" onmouseover="window.status='Discount Day'; return true;" onmouseout="window.status=''; return true;" title="Discount Day">
        <? $TDate = date("d",mktime(0,0,1,1,substr($CDiscon,8,2),date("Y")));
				for($n = 0; $n < 31; $n++){ $TDate2 = date("d",mktime(0,0,1,1,($n+1),date("Y"))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
        <? } ?>
      </select>
    </div>
    <div style="float:left; clear:none;">
      <select name="Disc Year" id="Disc_Year" class="CstmFrmElmnt64" onmouseover="window.status='Discount Year'; return true;" onmouseout="window.status=''; return true;" title="Discount Year">
        <? $TDate = date("Y",mktime(0,0,1,1,1,substr($CDiscon,0,4)));
				for($n = -5; $n < 8; $n++){ $TDate2 = date("Y",mktime(0,0,1,1,1,(date("Y")+$n))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
        <? } ?>
      </select>
    </div>
    <div id="BtnCalendar"><a href="javascript:StartCalDate('DiscCalendar','Disc_Year','Disc_Month','Disc_Day',null);" onmouseover="window.status='Start Calendar'; return true;" onmouseout="window.status=''; return true;" title="Start Calendar" id="DiscCalendar">Calendar</a></div>
    <? } ?>
    <br />
    <label for="Number_of_Uses" class="CstmFrmElmntLabel">Number of Uses</label>
    <? if(!$is_enabled) echo $CUses; else { ?>
    <input type="text" name="Number of Uses" id="Number_of_Uses" class="CstmFrmElmntInput" maxlength="50" title="Number of Uses" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Number of Uses'; return true;" onmouseout="window.status=''; return true;" value="<? echo $CUses; ?>" />
    0 for infinite
    <? } ?>
    <br />
    <label for="Rolling_Credit" class="CstmFrmElmntLabel">Rolling Credit</label>
    <? if(!$is_enabled) echo ($CRoll=="y")?"Yes":"No"; else { ?>
    <p>
      <input type="radio" name="Rolling Credit" id="Rolling_Credit" value="y" class="CstmFrmElmnt" title="Roll Over the Credit" onmouseover="window.status='Roll Over the Credit'; return true;" onmouseout="window.status=''; return true;"<? if($CRoll=="y")echo' checked="checked"'; ?> onchange="javascript: UpdRoll();" />
      <font class="fontSpecial2">Yes</font><br clear="all" />
    </p>
    <p>
      <input type="radio" name="Rolling Credit" id="Rolling_Credit" value="n" class="CstmFrmElmnt" title="Roll Over the Credit" onmouseover="window.status='Roll Over the Credit'; return true;" onmouseout="window.status=''; return true;"<? if($CRoll=="n")echo' checked="checked"'; ?> onchange="javascript: UpdRoll();" />
      <font class="fontSpecial2">No</font> </p>
    <? } ?>
    </span> <br clear="all" /><br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
