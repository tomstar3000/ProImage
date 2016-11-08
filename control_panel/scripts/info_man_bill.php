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
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div><img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Billing Information</p></th>
  </tr><?php } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Billing Information</p></th>
  </tr>
  <?php } ?>
  <tr>
    <td width="20%"><strong>Address:</strong></td>
    <td width="80%"><?php if(!$is_enabled){ echo $BAdd;
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
		$row_get_states = mysql_fetch_assoc($get_states); ?>
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
    <td><strong>Credit Card Number:</strong></td>
    <td><?php if(!$is_enabled){ echo $BCC; } else { ?>
      <input type="text" name="Credit Card Number" id="Credit_Card_Number" maxlength="16" value="<?php echo $BCC; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>CCV:</strong></td>
    <td><?php if(!$is_enabled){ echo $BCCV; } else { ?>
      <input type="text" name="CCV" id="CCV" value="<?php echo $BCCV; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Card Type:</strong></td>
    <td><?php if(!$is_enabled){ 
		do{
			if($BCCT == $row_get_card_types['cc_type_id'])echo " selected=\"selected\"";
		} while($row_get_card_types = mysql_fetch_assoc($get_card_types));
	} else {
		$query_get_card_types = "SELECT * FROM `billship_cc_types` ORDER BY `cc_type_name` ASC";
		$get_card_types = mysql_query($query_get_card_types, $cp_connection) or die(mysql_error());
		$row_get_card_types = mysql_fetch_assoc($get_card_types); ?>
      <select name="Credit Card Type" id="Credit_Card_Type">
        <?php do { ?>
        <option value="<?php echo $row_get_card_types['cc_type_id']; ?>"<?php if($BCCT == $row_get_card_types['cc_type_id'])echo " selected=\"selected\"";?>><?php echo $row_get_card_types['cc_type_name']; ?></option>
        <?php } while($row_get_card_types = mysql_fetch_assoc($get_card_types)); ?>
      </select>
      <?php mysql_free_result($get_card_types); }?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Experation: </strong></td>
    <td><?php if(!$is_enabled){echo $BCCM." ".$BCCY;} else {?>	
	<select name="Expiration Month" id="Expiration_Month">
        <option value="1"<?php if($BCCM==1)print(" selected=\"selected\"");?>>01</option>
        <option value="2"<?php if($BCCM==2)print(" selected=\"selected\"");?>>02</option>
        <option value="3"<?php if($BCCM==3)print(" selected=\"selected\"");?>>03</option>
        <option value="4"<?php if($BCCM==4)print(" selected=\"selected\"");?>>04</option>
        <option value="5"<?php if($BCCM==5)print(" selected=\"selected\"");?>>05</option>
        <option value="6"<?php if($BCCM==6)print(" selected=\"selected\"");?>>06</option>
        <option value="7"<?php if($BCCM==7)print(" selected=\"selected\"");?>>07</option>
        <option value="8"<?php if($BCCM==8)print(" selected=\"selected\"");?>>08</option>
        <option value="9"<?php if($BCCM==9)print(" selected=\"selected\"");?>>09</option>
        <option value="10"<?php if($BCCM==10)print(" selected=\"selected\"");?>>10</option>
        <option value="11"<?php if($BCCM==11)print(" selected=\"selected\"");?>>11</option>
        <option value="12"<?php if($BCCM==12)print(" selected=\"selected\"");?>>12</option>
      </select>
      <?php $year = date("Y")-1; ?>
      <select name="Expiration Year" id="Expiration_Year">
        <?php for($n=0;$n<10;$n++){
			$year++; ?>
        <option value="<?php echo $year; ?>"<?php if($BCCY==$year)print(" selected=\"selected\"");?>><?php echo $year; ?></option>
        <?php }	?>
      </select>
	  <?php } ?>
      &nbsp;</td>
  </tr>
</table>
