<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?php if($is_enabled){  ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Warehouse Information</p></th>
  </tr>
  <?php } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Warehouse Information</p></th>
  </tr>
  <?php } ?>
  <tr>
    <td width="20%"><strong>Address:</strong></td>
    <td width="80%"><?php if(!$is_enabled){ echo $WAdd;
		if($WSuite != "") echo " Suite/Apt. ".$WSuite;
	} else { ?>
      <input type="text" name="Address" id="Address" value="<?php echo $WAdd; ?>" />
      Suite/Apt
      <input type="text" name="Suite/Apt" id="Suite/Apt" value="<?php echo $WSuite; ?>" />
      <?php } ?>
      <input type="hidden" name="Whare_id" id="Whare_id" value="<?php echo $WId; ?>" />
      <input type="hidden" name="Man_Id" id="Man_Id" value="<?php echo $ManId; ?>" />
      &nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?php if(!$is_enabled){ echo $WAdd2; } else { ?>
      <input type="text" name="Address 2" id="Address_2" value="<?php echo $WAdd2; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?php if(!$is_enabled){ echo $WCity.", ".$WState.". ".$WZip; } else { ?>
      <input type="text" name="City" id="City" maxlength="125" value="<?php echo $WCity; ?>" />
      <?php
		$query_get_states = "SELECT * FROM `a_states` ORDER BY `state_short` ASC";
		$get_states = mysql_query($query_get_states, $cp_connection) or die(mysql_error());
		$row_get_states = mysql_fetch_assoc($get_states);
		$totalRows_get_states = mysql_num_rows($get_states);
		?>
      <select name="State" id="State">
        <?php do { ?>
        <option value="<?php echo $row_get_states['state_short']; ?>"<?php if($row_get_states['state_short'] == $BState){print(" selected=\"selected\"");} ?>><?php echo $row_get_states['state_short']; ?></option>
        <?php } while ($row_get_states = mysql_fetch_assoc($get_states)); ?>
      </select>
      <?php mysql_free_result($get_states); ?>
      <input type="text" name="Zip" id="Zip" maxlength="10" value="<?php echo $WZip; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
</table>
