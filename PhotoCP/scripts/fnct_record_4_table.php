<? if(isset($r_path)===false){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
function build_record_4_table($var_names, $table_header, $headers, $sortheaders, $records, $div_data = false, $drop_downs = false, $message, $control_val = "Delete", $control_act = "Delete", $control_img = false, $height = false, $width = "100%", $border = "0", $padd = "0", $spac = "0", $class = false, $align = false, $cell_col_1 = "FFFFFF", $cell_col_2 = "EEEEEE", $cell_col_3 = "DDDDDD", $message_2 = false, $control_val_2 = false, $control_act_2 = false, $control_img_2 = false, $control_show = true, $check_all_img = false, $uncheck_all_img = false, $button_text = "Open", $button_img = false, $button_target = false,$button_url = false){ 
	global $path;
	global $sort;
	global $rcrd;
	global $cont;
	global $AevNet_Path;
	$cell_num = count($headers);
?>
<script language="javascript">
function changeselect(checkvalue,fieldid,fieldcount){
	for(n=1;n<=document.getElementById(fieldcount).value;n++){
		document.getElementById(fieldid+"_"+n).checked = checkvalue;
	}
}
function clickthis(field,fieldid){
	if(document.getElementById(fieldid+"_"+field).checked == true){
		document.getElementById(fieldid+"_"+field).checked = false;
	} else {
		document.getElementById(fieldid+"_"+field).checked = true;
	}
}
function confirmdelete(formid, message, action, controller){
	if(confirm("Are You Sure You Want to "+message)){
		document.getElementById(controller).value = action;
		document.getElementById(formid).submit();
	}
}
function showdiv(divid){
	if(document.getElementById(divid).style.display == "none"){
		document.getElementById(divid).style.display = "";
	} else {
		document.getElementById(divid).style.display = "none";
	}
}
</script>

<table border="<? echo $border; ?>" cellpadding="<? echo $padd; ?>" cellspacing="<? echo $spac; ?>" <? if($class){ echo 'class="'.$class.'"';}if($height){ echo ' height="'.$height.'"';} if($width){ echo ' width="'.$width.'"';} if($align){ echo ' align="'.$align.'"';}?>>
 <? if(count($drop_downs) != 0 && $drop_downs !== false){ ?>
 <tr>
  <td colspan="<? echo $cell_num+2; ?>"><table border="0" cellpadding="0" cellspacing="0" width="100%"<? if($drop_style !== false)echo ' class="'.$drop_style.'"';?>>
    <tr>
     <? 
			$drop_vals = "";
			if($drop_downs){
			$drop_vals = array();
			foreach($drop_downs as $key => $value){
				print('<td align="center"><p><select name="Dropdowns[]" id="Dropdowns[]" onchange="document.getElementById(\'Controller\').value = \'false\'; document.getElementById(\'form_rcrd\').value = \'\'; document.getElementById(\'form_action_form\').submit();" >');
				$selected_var = $drop_downs[$key][0][0];
				$counter = count($drop_downs[$key]);
				for($n=1;$n<$counter;$n++){
					print('<option value="'.$drop_downs[$key][$n][0].'"');
					if($drop_downs[$key][$n][0] == $selected_var){
						print(' selected="selected"');
					}
					print('>'.$drop_downs[$key][$n][1].'</option>');
					
				}
				array_push($drop_vals,$selected_var);
				print('</select><input type="hidden" name="Sel_Dropdowns[]" id="Sel_Dropdowns[]" value="'.$selected_var.'" /></p></td>');
			} 
			$drop_vals = ','.implode(',',$drop_vals);
			}?>
    </tr>
   </table></td>
 </tr>
 <? } if(!$records[0][2]){ ?>
 <tr>
  <td colspan="<? echo $cell_num+2; ?>">There are no <? echo $table_header;?></td>
 </tr>
 <? } else { if($control_show){ ?>
 <tr>
  <td colspan="<? echo $cell_num+2; ?>"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td width="25%" align="left"><? if($check_all_img){
			echo '<img src="/'.$AevNet_Path.'/images/'.$check_all_img.'" name="Check All" onclick="changeselect(true,\''.$var_names.'_items\',\''.$var_names.'_count\');" alt="Check All" />';
			} else { ?>
      <input type="button" name="Check All" id="Check All" value="Check All" onclick="changeselect(true,'<? echo $var_names; ?>_items','<? echo $var_names; ?>_count');">
      <? } 
			if($uncheck_all_img){
			echo '<img src="/'.$AevNet_Path.'/images/'.$uncheck_all_img.'" name="Un-Check All" onclick="changeselect(false,\''.$var_names.'_items\',\''.$var_names.'_count\');" alt="Un-Check All" />';
			} else { ?>
      <input type="button" name="Un-Check All" id="Un-Check All" value="Un-Check_All" onclick="changeselect(false,'<? echo $var_names; ?>_items','<? echo $var_names; ?>_count');">
      <? } ?></td>
     <td align="right" style="text-align:right"><? if($control_img){
				echo '<img src="/'.$AevNet_Path.'/images/'.$control_img.'" name="'.$control_val.'" onclick="confirmdelete(\'form_action_form\',\''.$message.'\',\''.$control_act.'\', \'Controller\');" alt="'.$control_val.'" />';
			} else { ?>
      <input type="button" name="<? echo $control_val; ?>" id="<? echo $control_val; ?>" value="<? echo $control_val; ?>" onclick="confirmdelete('form_action_form', '<? echo $message; ?>', '<? echo $control_act; ?>', 'Controller');">
      <? } if($control_val_2){ if($control_img_2){
				echo '<img src="/'.$AevNet_Path.'/images/'.$control_img_2.'" name="'.$control_val_2.'" onclick="confirmdelete(\'form_action_form\',\''.$message_2.'\',\''.$control_act_2.'\', \'Controller\');" alt="'.$control_val_2.'" />';
			} else { ?>
      <input type="button" name="<? echo $control_val_2; ?>" id="<? echo $control_val_2; ?>" value="<? echo $control_val_2; ?>" onclick="confirmdelete('form_action_form', '<? echo $message_2; ?>', '<? echo $control_act_2; ?>', 'Controller');">
      <? } } ?></td>
    </tr>
   </table></td>
 </tr>
 <? } ?>
 <tr>
  <? foreach ($headers as $key => $value){ ?>
  <td align="left"<? if($value == $sortheaders[0]) echo ' bgcolor="#CDCDCD"'; ?>><strong>
   <? 
	  if($sortheaders != false && $value != "&nbsp;"){
			if($path[(count($path)-1)] == "Groups") $sort_path = array_slice($path,0,(count($path)-1)); else $sort_path = $path;
	  	if($value == $sortheaders[0]){
			if($sortheaders[1] == "ASC"){
				$Place_Order = "DESC";
			} else {
				$Place_Order = "ASC";
			}
		} else {
			$Place_Order = "ASC";
		} ?>
   <a href="#" onclick="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$sort_path); ?>','<? echo $cont; ?>','<? echo $value.','.$Place_Order.$drop_vals; ?>','<? echo (is_array($rcrd)) ? implode(",",$rcrd) : $rcrd; ?>');"> <? echo $value; if($Place_Order == "ASC") echo '<img src="/'.$AevNet_Path.'/images/arrow_blue_down.gif" width="10" height="10" border="0" hspace="5" />'; else echo '<img src="/'.$AevNet_Path.'/images/arrow_blue_up.gif" width="10" height="10" border="0" hspace="5" />'; ?> </a>
   <? } else {
	  	echo $value;
	  }
	   ?>
   </strong></td>
  <? } ?>
  <td>&nbsp;</td>
 </tr>
 <? $breaker_val = false;
	  $n = 0;
	  foreach ($records as $key => $value){
		$n++;
		if($breaker_val != $records[$key][0] && $records[$key][0] != false){ ?>
 <tr>
  <td colspan="<? echo $cell_num+2; ?>" valign="bottom"><strong><? echo $records[$key][0]; ?></strong></td>
 </tr>
 <? $breaker_val = $records[$key][0]; } ?>
 <tr<? if($cell_col_1){ ?> bgcolor="#<? if(is_int($n/2)){ echo $cell_col_1; } else { echo $cell_col_2; } ?>" onmouseover="this.bgColor='#<? echo $cell_col_3; ?>'" onmouseout="this.bgColor='#<? if(is_int($n/2)){ echo $cell_col_1; } else { echo $cell_col_2;} ?>'" <? } ?>>
  <td width="20" valign="top"><? if($control_show){ ?>
   <input name="<? echo $var_names; ?>_items[]" type="checkbox" class="no_border" id="<? echo $var_names."_items_".$n; ?>" value="<? echo $records[$key][2]; ?>"<? if($records[$key][1] == true)echo ' checked="checked"';?>>
   <? } else { print("&nbsp;"); } ?></td>
  <? foreach(array_slice($records[$key],3) as $k => $v){ 
	//if(strlen($v) > 30 && $k == 2)$v = substr($v,0,30)."..."; ?>
  <td valign="top" <? if($k != 0){echo 'onClick="clickthis('.$n.',\''.$var_names.'_items\');"';} ?>><? if(($k == 0 && $div_data)){ ?>
   <a href="javascript:showdiv('div_<? echo $var_names."_".$n; ?>');">\/</a>
   <? } if($button_text && $k == 0) { if($button_url == false){?>
   <a href="#"<? if($button_target){ echo 'target="'.$button_target.'"';}?> onclick="javascript:set_form('form_','<? echo implode(",",$path).','.$records[$key][2]; ?>','view','<? echo $sort; ?>','<? echo (is_array($rcrd)) ? implode(",",$rcrd) : $rcrd; ?>');">
   <? } else { echo '<a href="'.$button_url.'"'; if($button_target)echo' target="'.$button_target.'"';		echo '>';	} echo $v; ?>
   </a>
   <? } else { echo $v; }
	  if(($k == 0 && $div_data)){?>
   <div style="display:none;" id="div_<? echo $var_names."_".$n; ?>"><? echo $div_data[$key]; ?></div>
   <? } ?></td>
  <? } ?>
  <td align="right" valign="top"><? if($button_text) { ?>
   <a href="#"<? if($button_target){ echo 'target="'.$button_target.'"';}?> onclick="javascript:set_form('form_','<? echo implode(",",$path).','.$records[$key][2]; ?>','view','<? echo $sort; ?>','<? echo (is_array($rcrd)) ? implode(",",$rcrd) : $rcrd; ?>');">
   <? if ($button_img) { echo '<img src="'.$AevNet_Path.'/images/'.$button_img.'" alt="'.$button_text.'">';} else { echo $button_text; }?>
   </a>
   <? } else { print("&nbsp;"); }?></td>
 </tr>
 <? } if($control_show){ ?>
 <tr>
  <td colspan="<? echo $cell_num+2; ?>"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td width="25%" align="left"><? if($check_all_img){
			echo '<img src="/'.$AevNet_Path.'/images/'.$check_all_img.'" name="Check All" onclick="changeselect(true,\''.$var_names.'_items\',\''.$var_names.'_count\');" alt="Check All" />';
			} else { ?>
      <input type="button" name="Check All" id="Check All" value="Check All" onclick="changeselect(true,'<? echo $var_names; ?>_items','<? echo $var_names; ?>_count');">
      <? } if($uncheck_all_img){
			echo '<img src="/'.$AevNet_Path.'/images/'.$uncheck_all_img.'" name="Un-Check All" onclick="changeselect(false,\''.$var_names.'_items\',\''.$var_names.'_count\');" alt="Un-Check All" />';
			} else { ?>
      <input type="button" name="Un-Check All" id="Un-Check All" value="Un-Check_All" onclick="changeselect(false,'<? echo $var_names; ?>_items','<? echo $var_names; ?>_count');">
      <? } ?></td>
     <td align="right" style="text-align:right"><? if($control_img){
				echo '<img src="/'.$AevNet_Path.'/images/'.$control_img.'" name="'.$control_val.'" onclick="confirmdelete(\'form_action_form\', \''.$message.'\', \''.$control_act.'\', \'Controller\');" alt="'.$control_val.'" />';
			} else { ?>
      <input type="button" name="<? echo $control_val; ?>" id="<? echo $control_val; ?>" value="<? echo $control_val; ?>" onclick="confirmdelete('form_action_form', '<? echo $message; ?>', '<? echo $control_act; ?>', 'Controller');">
      <? } if($control_val_2){ if($control_img_2){
				echo '<img src="/'.$AevNet_Path.'/images/'.$control_img_2.'" name="'.$control_val_2.'" onclick="confirmdelete(\'form_action_form\',\''.$message_2.'\',\''.$control_act_2.'\', \'Controller\');" alt="'.$control_val_2.'" />';
			} else { ?>
      <input type="button" name="<? echo $control_val_2; ?>" id="<? echo $control_val_2; ?>" value="<? echo $control_val_2; ?>" onclick="confirmdelete('form_action_form', '<? echo $message_2; ?>', '<? echo $control_act_2; ?>', 'Controller');">
      <?	} } ?></td>
    </tr>
   </table></td>
 </tr>
 <? } } ?>
 <tr>
  <td width="20"><img src="/images/spacer.gif" width="20" height="1" /></td>
  <td colspan="<? echo $cell_num+1; ?>" width="100%"><img src="/images/spacer.gif" width="1" height="1" /></td>
 </tr>
 <input type="hidden" name="<? echo $var_names; ?>_count" id="<? echo $var_names; ?>_count" value="<? echo $n; ?>" />
</table>
<? } ?>
