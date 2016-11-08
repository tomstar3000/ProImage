<? if(isset($r_path)===false){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";} 
include $r_path.'security.php';
function ShowRecord($val, $key, $n,$HEAD,$VAR_NAME){
	if(!isset($val[1]) || $val[1] == false)
		return $val[0];
	else if(isset($val[1]) && !is_bool($val[1]) && $val[1] == "Order")
		return '<input type="text" id="'.$VAR_NAME.'_Order_'.$n.'" name="'.$VAR_NAME.'_Order[]" value="'.$val[0].'" size="3" onkeyup="compute(\'\',this.value,this,event);" />';
	else if(isset($val[1]) && $val[1] == true)
		return '<input type="text" id="'.$VAR_NAME."_".preg_replace("/[^a-zA-Z0-9]/","",$HEAD[($key)]).'_'.$n.'" name="'.$VAR_NAME."_".preg_replace("/[^a-zA-Z0-9]/","",$HEAD[($key)]).'[]" value="'.$val[0].'" />';
}
function build_record_6_table($VAR_NAME, $TBL_HEAD, $HEAD, $SORTHEAD, $RCDS, $DIVDATA = false, $drop_downs = false,$BTNS = array(array("Delete","Delete","Delete",false)), $height = false, $width = "100%", $border = "0", $padd = "0", $spac = "0", $class = false, $align = false, $COL1 = "FFFFFF", $COL2 = "EEEEEE", $COL3 = "DDDDDD", $COL4 = "EFEFEF", $COL5 = "CDCDCD", $CONTSHOW = true, $check_all_img = false, $uncheck_all_img = false, $BTNTEXT = "Open", $BTNIMG = false, $BTNTARG = false, $BTNURL = false){ 
	global $path, $sort, $rcrd, $cont, $AevNet_Path;
	$CELLNUM = count($HEAD); ?>
<script language="javascript">
function changeselect(checkvalue,fieldid,fieldcount){
	for(n=1;n<=document.getElementById(fieldcount).value;n++) document.getElementById(fieldid+"_"+n).checked = checkvalue;
}
function clickthis(field,fieldid){
	if(document.getElementById(fieldid+"_"+field).checked == true) document.getElementById(fieldid+"_"+field).checked = false;
	else document.getElementById(fieldid+"_"+field).checked = true;
}
function confirmdelete(formid, message, action, controller){
	if(confirm("Are You Sure You Want to "+message)){	document.getElementById(controller).value = action;
		document.getElementById(formid).submit(); }
}
function showdiv(divid){
	if(document.getElementById(divid).style.display == "none") document.getElementById(divid).style.display = "";
	else document.getElementById(divid).style.display = "none";
}
function compute(addthis,orderval,fieldvar,e){
	if(window.event) { // for IE, e.keyCode or window.event.keyCode can be used
		keycodeval = e.keyCode; 
	} else if(e.which) { // Netscape 
		keycodeval = e.which; }
	if(keycodeval < 32 || (keycodeval >= 33 && keycodeval <= 46) || (keycodeval >= 112 && keycodeval <= 123)){
		changeorder(addthis,orderval,fieldvar);
	} else {
		if(orderval.match(/^\d+$/)){ changeorder(addthis,orderval,fieldvar);
		} else { alert ("Value Must Be a Number!"); }
	} delete keycodeval;
}
function changeorder(addthis,orderval,fieldvar){
	Catcount = "<? echo $VAR_NAME; ?>_count"+addthis; valarray = new Array(); idarray = new Array(); a = 0;
	for(n=1;n<=document.getElementById(Catcount).value;n++){
		Catid = "<? echo $VAR_NAME; ?>_Order"+addthis+"_"+n;
		thisvalue = parseInt(document.getElementById(Catid).value); 
		if(thisvalue==orderval && Catid != fieldvar.id){
			if(orderval == 1){ thisvalue++;
			} else if (orderval == document.getElementById(Catcount).value){ thisvalue--;
			} else { thisvalue++; }
		} else if (thisvalue>orderval){	thisvalue++; 
		} else if (thisvalue<orderval){ thisvalue--; }
		idarray[a] = n; valarray[a] = thisvalue; a++;	}
	sortarray = new Array(idarray,valarray); sortedarray = sortthearray(sortarray);
	for(n=0;n<sortedarray[0].length;n++){
		Catid = "<? echo $VAR_NAME; ?>_Order"+addthis+"_"+sortedarray[0][n];
		document.getElementById(Catid).value = (n+1); }
}
function sortthearray(thearray){
	var holder = new Array();
	for (var i = 0; i < thearray[0].length; i++) {
		if(thearray[1][i]>thearray[1][i+1]){
			holder[0] = thearray[0][i]; holder[1] = thearray[1][i];
			thearray[0][i] = thearray[0][i+1]; thearray[1][i] = thearray[1][i+1];
			thearray[0][i+1] = holder[0];	thearray[1][i+1] = holder[1];
			sortthearray(thearray); }	}
	return thearray;
}
</script>
<div id="AevRecords" <? if($class) echo 'class="'.$class.'"'; if($height || $width) echo ' style="'.(($height)?'heigth:'.$height.'; ':'').(($width)?'width:'.$width.'; ':'').'"'; ?>>
 <? if(count($drop_downs) > 0 && $drop_downs !== false){ ?>
 <div id="AevDrops">
  <? $drop_vals = ""; $drop_vals = array(); foreach($drop_downs as $k => $v){ ?>
  <select name="Dropdowns[]" id="Dropdowns[]" style="float:left" onchange="document.getElementById('Controller').value = 'false'; document.getElementById('form_rcrd').value = ''; document.getElementById('form_action_form').submit();" >
   <? $selected_var = $drop_downs[$k][0][0]; $counter = count($drop_downs[$k]);
				for($n=1;$n<$counter;$n++){ ?>
   <option value="<? echo $drop_downs[$k][$n][0]; ?>"<? if($drop_downs[$k][$n][0] == $selected_var) echo' selected="selected"';  ?>><? echo $drop_downs[$k][$n][1]; ?></option>
   <? } array_push($drop_vals,$selected_var); ?>
  </select>
  <input type="hidden" name="Sel_Dropdowns[]" id="Sel_Dropdowns[]" value="<? echo $selected_var; ?>" />
  <? } $drop_vals = ','.implode(',',$drop_vals); ?>
  <br clear="all" />
 </div>
 <? } if(is_numeric($RCDS[0][1]) === false) { echo '<p>There are no '.$TBL_HEAD.'</p>'; } else { if($CONTSHOW){ ?>
 <div id="AevBtns">
  <div style="float:left" align="left">
   <? if($check_all_img){ echo '<img src="/'.$AevNet_Path.'/images/'.$check_all_img.'" name="Check All" onclick="changeselect(true,\''.$VAR_NAME.'_items\',\''.$VAR_NAME.'_count\');" alt="Check All" />'; } else { ?>
   <input type="button" name="Check All" id="Check All" value="Check All" onclick="changeselect(true,'<? echo $VAR_NAME; ?>_items','<? echo $VAR_NAME; ?>_count');">
   <? } if($uncheck_all_img){ echo '<img src="/'.$AevNet_Path.'/images/'.$uncheck_all_img.'" name="Un-Check All" onclick="changeselect(false,\''.$VAR_NAME.'_items\',\''.$VAR_NAME.'_count\');" alt="Un-Check All" />'; } else { ?>
   <input type="button" name="Un-Check All" id="Un-Check All" value="Un-Check_All" onclick="changeselect(false,'<? echo $VAR_NAME; ?>_items','<? echo $VAR_NAME; ?>_count');">
   <? } ?>
  </div>
  <div style="float:right" align="right">
   <? foreach($BTNS as $v){ if($v[3] !== false){ echo '<img src="/'.$AevNet_Path.'/images/'.$v[3].'" name="'.$v[1].'" onclick="confirmdelete(\'form_action_form\',\''.$v[0].'\',\''.$v[2].'\', \'Controller\');" alt="'.$v[1].'" />'; } else { ?>
   <input type="button" name="<? echo $v[1]; ?>" id="<? echo $v[1]; ?>" value="<? echo $v[1]; ?>" onclick="confirmdelete('form_action_form', '<? echo $v[0]; ?>', '<? echo $v[2]; ?>', 'Controller');">
   &nbsp;
   <? } } ?>
  </div>
  <br clear="all" />
 </div>
 <? } ?>
 <table border="<? echo $border; ?>" cellpadding="<? echo $padd; ?>" cellspacing="<? echo $spac; ?>"<? if($align)echo ' align="'.$align.'"'; ?> width="100%">
  <tr>
   <? foreach ($HEAD as $k => $v){ if($SORTHEAD != false && $v != "&nbsp;"){
				if($path[(count($path)-1)] == "Groups") $SORTPATH = array_slice($path,0,(count($path)-1)); else $SORTPATH = $path;
				if($v == $SORTHEAD[0]){
					if($SORTHEAD[1] == "ASC") $PLCEORD = "DESC";
					else $PLCEORD = "ASC";
				} else $PLCEORD = "ASC"; } ?>
   <td align="left" bgcolor="#<? echo ($v == $SORTHEAD[0])?$COL5:$COL4; ?>" onclick="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo (is_array($SORTPATH)?implode(",",$SORTPATH):$SORTPATH); ?>','<? echo $cont; ?>','<? echo $v.','.$PLCEORD.$drop_vals; ?>','<? echo (is_array($rcrd))?implode(",",$rcrd):$rcrd; ?>');" style="cursor:pointer;" <? if($v != $SORTHEAD[0]){ ?> onmouseover="this.bgColor='#<? echo $COL5; ?>'" onmouseout="this.bgColor='#<? echo $COL4; ?>'" <? } ?>><strong>
    <? if($SORTHEAD != false && $v != "&nbsp;"){ ?>
    <a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo (is_array($SORTPATH)?implode(",",$SORTPATH):$SORTPATH); ?>','<? echo $cont; ?>','<? echo $v.','.$PLCEORD.$drop_vals; ?>','<? echo (is_array($rcrd))?implode(",",$rcrd):$rcrd; ?>');"><? echo $v; if($PLCEORD == "ASC") echo '<img src="/'.$AevNet_Path.'/images/arrow_blue_down.gif" width="10" height="10" border="0" hspace="5" />'; else echo '<img src="/'.$AevNet_Path.'/images/arrow_blue_up.gif" width="10" height="10" border="0" hspace="5" />'; ?> </a>
    <? } else { echo $v; } ?>
    </strong></td>
   <? } ?>
   <td bgcolor="#<? echo $COL4; ?>">&nbsp;</td>
  </tr>
  <? $BREAKVAL = false; $n = 0;
	  foreach ($RCDS as $k => $v){ $n++;
		if(isset($RCDS[$k][0][0]) && $RCDS[$k][0][0] != false && $BREAKVAL != $RCDS[$k][0][0]){ $BREAKVAL = $RCDS[$k][0][0]; ?>
  <tr>
   <td colspan="<? echo $CELLNUM+1; ?>" valign="bottom"><strong><? echo $BREAKVAL; ?></strong></td>
  </tr>
  <? } ?>
  <tr<? if($COL1){ ?> bgcolor="#<? echo ((is_int($n/2))?$COL1:$COL2); ?>" onmouseover="this.bgColor='#<? echo $COL3; ?>'" onmouseout="this.bgColor='#<? echo ((is_int($n/2))?$COL1:$COL2); ?>'"<? } ?>>
   <? foreach(array_slice($RCDS[$k],2) as $k2 => $v2){ ?>
   <td valign="top" <? if($k2>0)echo 'onClick="clickthis('.$n.',\''.$VAR_NAME.'_items\');"'; ?>><? if($k2 == 0){ if($CONTSHOW){ ?>
    <input name="<? echo $VAR_NAME; ?>_items[]" type="checkbox" class="no_border" id="<? echo $VAR_NAME."_items_".$n; ?>" value="<? echo $RCDS[$k][1]; ?>"<? if(isset($RCDS[$k][0][1]) && $RCDS[$k][0][1] == true)echo ' checked="checked"';?>>
    <input name="<? echo $VAR_NAME; ?>_stored_items[]" type="hidden" id="<? echo $VAR_NAME."_stored_items_".$n; ?>" value="<? echo $RCDS[$k][1]; ?>"<? if(isset($RCDS[$k][0][1]) && $RCDS[$k][0][1] == true)echo ' checked="checked"';?>>
    <? } if($DIVDATA){ ?>
    <a href="javascript:showdiv('div_<? echo $VAR_NAME."_".$n; ?>');">\/</a>
    <? } if($BTNTEXT) { if($BTNURL == false && $v2[1] == false){ ?>
    <a href="#"<? if($BTNTARG){ echo 'target="'.$BTNTARG.'"';}?> onclick="javascript:set_form('form_','<? echo implode(",",$path).','.$RCDS[$k][1]; ?>','view','<? echo $sort; ?>','<? echo (is_array($rcrd))?implode(",",$rcrd):$rcrd; ?>');">
    <? } else if($v2[1] == false){ echo '<a href="'.$BTNURL.'"'.(($BTNTARG)?' target="'.$BTNTARG.'"':'').'>'; } echo ShowRecord($v2, $k2, $n,$HEAD,$VAR_NAME);
		if($v2[1] == false){ ?>
    </a>
    <? } } else { echo $v2; } if($DIVDATA){?>
    <div<? if(!isset($RCDS[$k][0][2]) || $RCDS[$k][0][2] === false)echo' style="display:none;"'; ?> id="div_<? echo $VAR_NAME."_".$n; ?>"><? echo $DIVDATA[$k]; ?></div>
    <? } } else { echo ShowRecord($v2, $k2, $n,$HEAD,$VAR_NAME); } ?></td>
   <? } ?>
   <td align="right" valign="top"><? if($BTNTEXT) { if($BTNURL == false){ ?>
    <a href="#"<? if($BTNTARG){ echo 'target="'.$BTNTARG.'"';}?> onclick="javascript:set_form('form_','<? echo implode(",",$path).','.$RCDS[$k][1]; ?>','view','<? echo $sort; ?>','<? echo (is_array($rcrd))?implode(",",$rcrd):$rcrd; ?>');">
    <? } else { echo '<a href="'.$BTNURL.'"'.(($BTNTARG)?' target="'.$BTNTARG.'"':'').'>'; } echo $BTNTEXT; ?>
    </a>
    <? } else { echo '&nbsp;'; }?></td>
  </tr>
  <? } ?>
 </table>
 <? if($CONTSHOW){ ?>
 <div id="AevBtns">
  <div style="float:left" align="left">
   <? if($check_all_img){ echo '<img src="/'.$AevNet_Path.'/images/'.$check_all_img.'" name="Check All" onclick="changeselect(true,\''.$VAR_NAME.'_items\',\''.$VAR_NAME.'_count\');" alt="Check All" />'; } else { ?>
   <input type="button" name="Check All" id="Check All" value="Check All" onclick="changeselect(true,'<? echo $VAR_NAME; ?>_items','<? echo $VAR_NAME; ?>_count');">
   <? } if($uncheck_all_img){ echo '<img src="/'.$AevNet_Path.'/images/'.$uncheck_all_img.'" name="Un-Check All" onclick="changeselect(false,\''.$VAR_NAME.'_items\',\''.$VAR_NAME.'_count\');" alt="Un-Check All" />'; } else { ?>
   <input type="button" name="Un-Check All" id="Un-Check All" value="Un-Check_All" onclick="changeselect(false,'<? echo $VAR_NAME; ?>_items','<? echo $VAR_NAME; ?>_count');">
   <? } ?>
  </div>
  <div style="float:right" align="right">
   <? foreach($BTNS as $v){ if($v[3] !== false){ echo '<img src="/'.$AevNet_Path.'/images/'.$v[3].'" name="'.$v[1].'" onclick="confirmdelete(\'form_action_form\',\''.$v[0].'\',\''.$v[2].'\', \'Controller\');" alt="'.$v[1].'" />'; } else { ?>
   <input type="button" name="<? echo $v[1]; ?>" id="<? echo $v[1]; ?>" value="<? echo $v[1]; ?>" onclick="confirmdelete('form_action_form', '<? echo $v[0]; ?>', '<? echo $v[2]; ?>', 'Controller');">
   &nbsp;
   <? } } ?>
  </div>
  <br clear="all" />
 </div>
 <? } ?>
 <input type="hidden" name="<? echo $VAR_NAME; ?>_count" id="<? echo $VAR_NAME; ?>_count" value="<? echo $n; ?>" />
 <? } ?>
</div>
<? } ?>
