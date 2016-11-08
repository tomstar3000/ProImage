<?php

include $r_path.'security_2.php';
function build_record_2_table($var_names, $table_header, $message, $control_val, $control_act, $control_img, $message_2, $control_val_2, $control_act_2, $control_img_2, $control_show, $check_all_img, $uncheck_all_img, $headers, $sortheaders, $records, $div_data, $drop_downs, $button_url, $button_text, $button_img, $button_target, $border, $padd, $spac, $height, $width, $class, $align, $cell_col_1, $cell_col_2, $cell_col_3, $add_button = false, $hdr_image = false, $drop_style = false){ 
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
	if(confirm("You Sure You Want to "+message)){
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

<table border="<?php echo $border; ?>" cellpadding="<?php echo $padd; ?>" cellspacing="<?php echo $spac; ?>" <?php if($class){ echo "class=\"".$class."\""; }if($height){ echo " height=\"".$height."\""; } if($width){ echo " width=\"".$width."\""; } if($align){ echo " align=\"".$align."\""; }?>>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" name="<?php echo $var_names; ?>_form" id="<?php echo $var_names; ?>_form">
    <?php if(count($drop_downs) != 0 && $drop_downs !== false){ ?>
    <tr>
      <td colspan="<?php echo $cell_num+2; ?>"><table border="0" cellpadding="0" cellspacing="0" width="100%"<?php if($drop_style !== false){ echo " class=\"".$drop_style."\""; } ?>>
          <tr>
            <?php 
			foreach($drop_downs as $key => $value){
				print("<td align=\"center\"><p><select name=\"Dropdowns[]\" id=\"Dropdowns[]\" onchange=\"document.getElementById('".$var_names."_controller').value = 'false'; document.getElementById('".$var_names."_form').submit();\" >\n");
				$selected_var = $drop_downs[$key][0][0];
				$counter = count($drop_downs[$key]);
				for($n=1;$n<$counter;$n++){
					print("<option value=\"".$drop_downs[$key][$n][0]."\"");
					if($drop_downs[$key][$n][0] == $selected_var){
						print(" selected=\"selected\"");
					}
					print(">".$drop_downs[$key][$n][1]."</option>\n");
					
				}
				print("</select><input type=\"hidden\" name=\"Sel_Dropdowns[]\" id=\"Sel_Dropdowns[]\" value=\"".$selected_var."\" /></p></td>\n");
			}?>
          </tr>
        </table></td>
    </tr>
    <?php } ?>
    <tr>
      <th colspan="<?php echo $cell_num+2; ?>" id="Form_Header"><?php echo ($add_button!==false) ? $add_button : ""; ?><?php echo ($hdr_image!==false) ? $hdr_image : ""; ?>
        <p><?php echo $table_header;?></p></th>
    </tr>
    <?php
	if(!$records[0][1]){ ?>
    <tr>
      <td colspan="<?php echo $cell_num+2; ?>">There are no <?php echo $table_header;?></td>
    </tr>
    <?php
	} else {
	if($control_show){ ?>
    <tr>
      <td colspan="<?php echo $cell_num+2; ?>"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%" align="left"><?php if($check_all_img){
			echo "<img src=\"/".$AevNet_Path."/images/".$check_all_img."\" name=\"Check All\" onclick=\"changeselect(true,'".$var_names."_items','".$var_names."_count');\" alt=\"Check All\" />";
			} else { ?>
              <input type="button" name="Check All" id="Check All" value="Check All" onClick="changeselect(true,'<?php echo $var_names; ?>_items','<?php echo $var_names; ?>_count');">
              <?php } 
			if($uncheck_all_img){
			echo "<img src=\"/".$AevNet_Path."/images/".$uncheck_all_img."\" name=\"Un-Check All\" onclick=\"changeselect(false,'".$var_names."_items','".$var_names."_count');\" alt=\"Un-Check All\" />";
			} else { ?>
              <input type="button" name="Un-Check All" id="Un-Check All" value="Un-Check_All" onClick="changeselect(false,'<?php echo $var_names; ?>_items','<?php echo $var_names; ?>_count');">
              <?php } ?></td>
            <td align="right" style="text-align:right"><?php if($control_img){
				echo "<img src=\"/".$AevNet_Path."/images/".$control_img."\" name=\"".$control_val."\" onclick=\"".$var_names."_form', '".$message."', 'true', '".$var_names."_controller');\" alt=\"".$control_val."\" />";
			} else { ?>
              <input type="button" name="<?php echo $control_val; ?>" id="<?php echo $control_val; ?>" value="<?php echo $control_val; ?>" onClick="confirmdelete('<?php echo $var_names; ?>_form', '<?php echo $message; ?>', '<?php echo $control_act; ?>', '<?php echo $var_names; ?>_controller');">
              <?php 
			  	if($control_val_2 !== false){ ?>
              <input type="button" name="<?php echo $control_val_2; ?>" id="<?php echo $control_val_2; ?>" value="<?php echo $control_val_2; ?>" onClick="confirmdelete('<?php echo $var_names; ?>_form', '<?php echo $message_2; ?>', '<?php echo $control_act_2; ?>', '<?php echo $var_names; ?>_controller');">
			  <?php	}
			  } ?></td>
          </tr>
        </table></td>
    </tr>
    <?php } ?>
    <tr>
      <td align="center">&nbsp;</td>
      <?php foreach ($headers as $key => $value){ ?>
      <td align="center"><strong>
        <?php 
	  if($sortheaders != false){ 
	  	if($value == $sortheaders[0]){
			if($sortheaders[1] == "ASC"){
				$Place_Order = "DESC";
			} else {
				$Place_Order = "ASC";
			}
		} else {
			$Place_Order = "ASC";
		}
	  ?>
        <a href="javascript:document.getElementById('Sort_By').value='<?php echo $value; ?>'; document.getElementById('Order_By').value='<?php echo $Place_Order;?>'; document.getElementById('<?php echo $var_names; ?>_controller').value = 'false'; document.getElementById('<?php echo $var_names; ?>_form').submit();">
        <?php 
	  	echo $value; 
		if($Place_Order == "ASC"){
			print(" \\/");
		} else {
			print(" /\\");
		}
		?>
        </a>
        <?php } else {
	  	echo $value;
	  }
	   ?>
        </strong></td>
      <?php } ?>
      <td>&nbsp;</td>
    </tr>
    <?php 
	  $breaker_val = false;
	  $n = 0;
	  foreach ($records as $key => $value){
		$n++;
		if($breaker_val != $records[$key][0] && $records[$key][0] != false){ ?>
    <tr>
      <td colspan="<?php echo $cell_num+2; ?>" valign="bottom"><strong><?php echo $records[$key][0]; ?></strong></td>
    </tr>
    <?php $breaker_val = $records[$key][0];
		} ?>
    <tr<?php if($cell_col_1){ ?> bgcolor="#<?php if(is_int($n/2)){ echo $cell_col_1; } else { echo $cell_col_2; } ?>" onMouseOver="this.bgColor='#<?php echo $cell_col_3; ?>'" onMouseOut="this.bgColor='#<?php if(is_int($n/2)){ echo $cell_col_1; } else { echo $cell_col_2;} ?>'" <?php } ?>>
      <td width="20" valign="top"><?php if($control_show){ ?>
        <input name="<?php echo $var_names; ?>_items[]" type="checkbox" class="no_border" id="<?php echo $var_names."_items_".$n; ?>" value="<?php echo $records[$key][1]; ?>">
        <?php } else { print("&nbsp;"); } ?></td>
      <?php foreach(array_slice($records[$key],2) as $k => $v){ ?>
      <?php
	  if(($k == 0 && $div_data)){ 
	   if(strlen($v) > 30){
	   		$v = substr($v,0,30)."...";
	   }
	  ?>
      <td valign="top"><a href="javascript:showdiv('div_<?php echo $var_names."_".$n; ?>');"><?php echo $v; ?></a>
        <div style="display:none;" id="div_<?php echo $var_names."_".$n; ?>"><?php echo $div_data[$key]; ?></div></td>
      <?php } else {
	  	if(!$cell_col_1){
			echo "<td valign=\"top\">".$v."</td>";
		} else {
			echo "<td onClick=\"clickthis(".$n.",'".$var_names."_items');\" valign=\"top\">".$v."</td>";
		}
	  }
	  ?>
      <?php } ?>
      <td align="right" valign="top"><?php if($button_url) { ?>
        <a href="<?php echo $button_url; ?>
		<?php if(strpos($button_url, '?') === false){
			print("?");
		} else {
			print("&");
		} ?>		
		id=<?php echo $records[$key][1]; ?>"<?php if($button_target){ echo "target=\"".$button_target."\""; }?>>
        <?php if ($button_img) { echo "<img src=\"/".$AevNet_Path."/images/".$button_img."\" alt=\"".$button_text."\">"; } else { echo $button_text; }?>
        </a>
        <?php } else { print("&nbsp;"); }?></td>
    </tr>
    <?php } 
	if($control_show){ ?>
    <tr>
      <td colspan="<?php echo $cell_num+2; ?>"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%" align="left"><?php if($check_all_img){
			echo "<img src=\"/".$AevNet_Path."/images/".$check_all_img."\" name=\"Check All\" onclick=\"changeselect(true,'".$var_names."_items','".$var_names."_count');\" alt=\"Check All\" />";
			} else { ?>
              <input type="button" name="Check All" id="Check All" value="Check All" onClick="changeselect(true,'<?php echo $var_names; ?>_items','<?php echo $var_names; ?>_count');">
              <?php } 
			if($uncheck_all_img){
			echo "<img src=\"/".$AevNet_Path."/images/".$uncheck_all_img."\" name=\"Un-Check All\" onclick=\"changeselect(false,'".$var_names."_items','".$var_names."_count');\" alt=\"Un-Check All\" />";
			} else { ?>
              <input type="button" name="Un-Check All" id="Un-Check All" value="Un-Check_All" onClick="changeselect(false,'<?php echo $var_names; ?>_items','<?php echo $var_names; ?>_count');">
              <?php } ?></td>
            <td align="right" style="text-align:right"><?php if($control_img){
				echo "<img src=\"/".$AevNet_Path."/images/".$control_img."\" name=\"".$control_val."\" onclick=\"".$var_names."_form', '".$message."', 'true', '".$var_names."_controller');\" alt=\"".$control_val."\" />";
			} else { ?>
              <input type="button" name="<?php echo $control_val; ?>" id="<?php echo $control_val; ?>" value="<?php echo $control_val; ?>" onClick="confirmdelete('<?php echo $var_names; ?>_form', '<?php echo $message; ?>', '<?php echo $control_act; ?>', '<?php echo $var_names; ?>_controller');">
              <?php 
			  	if($control_val_2 !== false){ ?>
              <input type="button" name="<?php echo $control_val_2; ?>" id="<?php echo $control_val_2; ?>" value="<?php echo $control_val_2; ?>" onClick="confirmdelete('<?php echo $var_names; ?>_form', '<?php echo $message_2; ?>', '<?php echo $control_act_2; ?>', '<?php echo $var_names; ?>_controller');">
			  <?php	}
			  } ?></td>
          </tr>
        </table></td>
    </tr>
    <?php } }?>
    <tr>
      <td width="20"><img src="/images/spacer.gif" width="20" height="1" /></td>
      <td colspan="<?php echo $cell_num+1; ?>" width="100%"><img src="/images/spacer.gif" width="1" height="1" /></td>
    </tr>
    <input type="hidden" name="<?php echo $var_names; ?>_controller" id="<?php echo $var_names; ?>_controller" value="false" />
    <input type="hidden" name="<?php echo $var_names; ?>_count" id="<?php echo $var_names; ?>_count" value="<?php echo $n; ?>" />
    <input type="hidden" name="Sort_By" id="Sort_By" value="<?php echo $sortheaders[0]; ?>" />
    <input type="hidden" name="Order_By" id="Order_By" value="<?php echo $sortheaders[1]; ?>" />
  </form>
</table>
<?php } ?>
