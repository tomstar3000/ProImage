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
      <p>Shipping Information</p></th>
  </tr>
  <?php } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Shipping Information</p></th>
  </tr>
  <?php } ?>
  <tr>
    <td width="20%"><strong>Care Of: </strong></td>
    <td width="80%"><?php if(!$is_enabled){ echo $SCareOf; } else { ?>
      <input type="text" name="Care Of" id="Care_Of" value="<?php echo $SCareOf; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Address:</strong></td>
    <td><?php if(!$is_enabled){ echo $BAdd;
		if($BSuite != "") echo " Suite/Apt. ".$BSuite;
	} else { ?>
      <input type="text" name="Address" id="Address" value="<?php echo $BAdd; ?>" />
      Suite/Apt
      <input type="text" name="Suite/Apt" id="Suite/Apt" value="<?php echo $BSuite; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?php if(!$is_enabled){ echo $BAdd2; } else { ?>
      <input type="text" name="Address 2" id="Address_2" value="<?php echo $BAdd2; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?php if(!$is_enabled){ echo $BCity.", ".$BState.". ".$BZip; } else { ?>
      <input type="text" name="City" id="City" maxlength="125" value="<?php echo $BCity; ?>" />
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
      <input type="text" name="Zip" id="Zip" maxlength="10" value="<?php echo $BZip; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Default:</strong></td>
    <td><?php if(!$is_enabled){ echo ($SDefault == "y") ? "Yes" : "No";	} else { ?>
      <input type="radio" name="Default" id="Default" value="y"<?php if($SDefault == "y"){ print(" checked=\"checked\""); } ?> />
      Yes
      <input type="radio" name="Default" id="Default" value="n"<?php if($SDefault == "n"){ print(" checked=\"checked\""); } ?> />
      No
      <?php } ?>
      &nbsp;</td>
  </tr>
</table>
