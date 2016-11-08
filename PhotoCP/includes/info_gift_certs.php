<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
require_once($r_path.'includes/info_calendar.php');
if($path[0] == "Evnt"){
	$is_enabled = ((count($path)<=5 && $cont == "view") || (count($path)>5)) ? false : true;
	$is_back = ($cont == "edit") ? "view" : "query";
} else {
	$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
	$is_back = ($cont == "edit") ? "view" : "query";
} ?>

<h1 id="HdrType2" class="<? switch($path[0]){
	case "Evnt": echo "EvntMarket"; break;
	case "Busn": echo "BsnDiscCodes"; break;
} ?>">
  <div>Gift Certificate Information</div>
</h1>
<div id="HdrLinks">
  <? if($is_enabled){ ?>
  <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status='Save Gift Certificate'; return true;" onmouseout="window.status=''; return true;" title="Save Gift Certificate" class="BtnSave2">Save</a><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','<? echo ($cont == "add")?'query':'view'; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Cancel'; return true;" onmouseout="window.status=''; return true;" title="Cancel" class="BtnCancel">Cancel</a>
  <? } else { ?>
  <a href="#" onclick="javascript:set_form('form_','<? echo ($path[0]=='Evnt') ? implode(",",array_slice($path,0,5)) : implode(",",array_slice($path,0,3)); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Edit Discount Code <? echo $CName; ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit Discount Code <? echo $CName; ?>" class="BtnEdit2">Edit</a>
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
  	<label for="Event" class="CstmFrmElmntLabel">Event</label>
		<? if(!$is_enabled){
			if(intval($SEID) != 0){
				$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
				$getInfo->mysql("SELECT `event_id`, `event_name`, `event_num`
				FROM `photo_event` WHERE `event_id` = '$SEID';");
				$getInfo = $getInfo->Rows(); echo $getInfo[0]['event_name']." - ".$getInfo[0]['event_num'];
			} else echo 'No Event Selected';
		} else { ?>
    <select name="Event" id="Event" class="CstmFrmElmnt" onmouseover="window.status='Event'; return true;" onmouseout="window.status=''; return true;" title="Event">
    	<option value="0" title="Select Event">-- Select Event --</option>
      <option value="0" title="Select Event">-- Active Events --</option>
      <? $getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
				 $getInfo->mysql("SELECT `event_id`, `event_name`, `event_num`
				FROM `photo_event` WHERE `cust_id` = '$CustId'
					AND `event_use` = 'y' ORDER BY `event_name` ASC, `event_num` ASC;");
			foreach($getInfo->Rows() as $r){ ?>
				<option value="<? echo $r['event_id']; ?>" title="<? echo $r['event_name']." - ".$r['event_num']; ?>"<? if($SEID==$r['event_id']) echo ' selected="selected"'; ?>><? echo $r['event_name']." - ".$r['event_num']; ?></option>
			<? } ?>
      
      <option value="0" title="Select Event">&nbsp;</option>
      <option value="0" title="Select Event">-- Expired Events --</option>
      <? $getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
				 $getInfo->mysql("SELECT `event_id`, `event_name`, `event_num`
				FROM `photo_event` WHERE `cust_id` = '$CustId'
					AND `event_use` = 'n' ORDER BY `event_name` ASC, `event_num` ASC;");
			foreach($getInfo->Rows() as $r){ ?>
				<option value="<? echo $r['event_id']; ?>" title="<? echo $r['event_name']." - ".$r['event_num']; ?>"<? if($SEID==$r['event_id']) echo ' selected="selected"'; ?>><? echo $r['event_name']." - ".$r['event_num']; ?></option>
			<? } ?>
    </select>
    <? } ?>
    <br />
    <label for="Name" class="CstmFrmElmntLabel">Gift Certificate Name</label>
    <? if(!$is_enabled) echo $CName; else { ?>
    <input type="text" name="Name" id="Name" maxlength="75" class="CstmFrmElmntInput" title="Discount Name" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Discount Name'; return true;" onmouseout="window.status=''; return true;" value="<? echo $CName; ?>" />
    <? } ?>
    <br />
    <label for="Code" class="CstmFrmElmntLabel">Code</label>
    <? if(!$is_enabled) echo $CCode; else { ?>
    <input type="text" name="Code" id="Code" maxlength="50" class="CstmFrmElmntInput" title="Discount Code" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Discount Code'; return true;" onmouseout="window.status=''; return true;" value="<? echo $CCode; ?>" />
    <? } ?>
    <br clear="all" />
    </span> <span>
    <label for="Email" class="CstmFrmElmntLabel">E-mail</label>
    <? if(!$is_enabled) echo $CEmail; else { ?>
    <input type="text" name="Email" id="Email" maxlength="50" class="CstmFrmElmntInput" title="Customer's E-mail" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Customer\'s E-mail'; return true;" onmouseout="window.status=''; return true;" value="<? echo $CEmail; ?>" />
    <? } ?>
    <br />
    <label for="Price" class="CstmFrmElmntLabel">Price</label>
    <? if(!$is_enabled) echo "$".number_format($CPrice,2,".",","); else { ?>
    <input type="text" name="Price" id="Price" maxlength="50" class="CstmFrmElmntInput" title="Discount Price" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Discount Price'; return true;" onmouseout="window.status=''; return true;" value="<? echo $CPrice; ?>" />
    <? } ?>
    <br clear="all" />
    </span><br clear="all" />
    <br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
