<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
include $r_path.'scripts/save_manufactures.php';
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";
if(!$is_enabled){ ?>

<div id="Tri_Nav">
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<?php echo implode(',',array_slice($path,0,3)); ?>','view','<?php echo $sort; ?>','<?php echo $rcrd; ?>');"><p>Overview</p></td>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<?php echo implode(',',array_slice($path,0,3)).',Bill'; ?>','query','<?php echo $sort; ?>','<?php echo $rcrd; ?>');"><p>Billing</p></td>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<?php echo implode(',',array_slice($path,0,3)).',Ship'; ?>','query','<?php echo $sort; ?>','<?php echo $rcrd; ?>');"><p>Shipping</p></td>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<?php echo implode(',',array_slice($path,0,3)).',Whare'; ?>','query','<?php echo $sort; ?>','<?php echo $rcrd; ?>');"><p>Wharehouse</p></td>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<?php echo implode(',',array_slice($path,0,3)).',Cont'; ?>','query','<?php echo $sort; ?>','<?php echo $rcrd; ?>');"><p>Contact</p></td>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<?php echo implode(',',array_slice($path,0,3)).',Sale'; ?>','query','<?php echo $sort; ?>','<?php echo $rcrd; ?>');"><p>Sales
          Rep</p></td>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<?php echo implode(',',array_slice($path,0,3)).',Acct'; ?>','query','<?php echo $sort; ?>','<?php echo $rcrd; ?>');"><p>Account
          Rep</p></td>
    </tr>
  </table>
</div>
<?php } ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <?php if($is_enabled){ ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Manufacture Information</p></th>
  </tr>
  <?php } else { ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,3)); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,2)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Manufacture Information</p></th>
  </tr>
  <?php } ?>
  <tr>
    <td align="right" width="50%">Name: </td>
    <td><?php if(!$is_enabled){ echo $MName; } else { ?>
      <input type="text" name="Name" id="Name" maxlength="75" value="<?php echo $MName; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <?php if(count($path) < 4){ ?>
  <tr>
    <td align="right" width="50%">Address</td>
    <td><?php if(!$is_enabled){	echo $MAdd;
		if($MSuite != "") echo " Suite/Apt. ".$MSuite;
	} else { ?>
      <input type="text" name="Address" id="Address" maxlength="50" value="<?php echo $MAdd; ?>" />
      Suite/Apt.
      <input name="Suite/Apt" type="text" id="Suite/Apt" value="<?php echo $MSuite; ?>" size="10" maxlength="75" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td><?php if(!$is_enabled){	echo $MAdd2; } else { ?>
      <input type="text" name="Address 2" id="Address_2" maxlength="75" value="<?php echo $MAdd2; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td><?php if(!$is_enabled){	echo $MCity.", ".$MSate.". ",$MZip; } else { ?>
      <input type="text" name="City" id="City" maxlength="125" value="<?php echo $MCity; ?>" />
      <?php
		$query_get_states = "SELECT * FROM `a_states` ORDER BY `state_short` ASC";
		$get_states = mysql_query($query_get_states, $cp_connection) or die(mysql_error());
		$row_get_states = mysql_fetch_assoc($get_states);
		$totalRows_get_states = mysql_num_rows($get_states);
		?>
      <select name="State" id="State">
        <?php do { ?>
        <option value="<?php echo $row_get_states['state_short']; ?>"<?php if($row_get_states['state_short'] == $MState){print(" selected=\"selected\"");} ?>><?php echo $row_get_states['state_short']; ?></option>
        <?php } while ($row_get_states = mysql_fetch_assoc($get_states)); ?>
      </select>
      <?php mysql_free_result($get_states); ?>
      <input type="text" name="Zip" id="Zip" maxlength="10" value="<?php echo $MZip; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td align="right">Country:</td>
    <td><?php if(!$is_enabled){	echo $Count; } else { ?>
      <input type="text" name="Country" id="Country" maxlength="75" value="<?php echo $MCount; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td align="right">Phone:</td>
    <td><?php if(!$is_enabled){	if($MPhone != "0") echo "(".$P1.") ".$P2."-".$P3; } else { ?>
      (
      <input name="P1" type="text" id="P1" size="6" value="<?php echo $P1;?>" maxlength="3" onKeyUp="set_tel_number('Phone_Number','P');" />
      )
      <input name="P2" type="text" id="P2" size="6" value="<?php echo $P2;?>" maxlength="3" onKeyUp="set_tel_number('Phone_Number','P');" />
      -
      <input name="P3" type="text" id="P3" size="8" value="<?php echo $P3;?>" maxlength="4" onKeyUp="set_tel_number('Phone_Number','P');" />
      <?php } ?>
      <input type="hidden" name="Phone Number" id="Phone_Number" value="<?php echo $MPhone;?>" />
      &nbsp;</td>
  </tr>
  <tr>
    <td align="right">Mobile:</td>
    <td><?php if(!$is_enabled){	if($MCell != "0") echo "(".$C1.") ".$C2."-".$C3; } else { ?>
      (
      <input name="C1" type="text" id="C1" size="6" value="<?php echo $C1;?>" maxlength="3" onKeyUp="set_tel_number('Cell_Number','C');" />
      )
      <input name="C2" type="text" id="C2" size="6" value="<?php echo $C2;?>" maxlength="3" onKeyUp="set_tel_number('Cell_Number','C');" />
      -
      <input name="C3" type="text" id="C3" size="8" value="<?php echo $C3;?>" maxlength="4" onKeyUp="set_tel_number('Cell_Number','C');" />
      <?php } ?>
      <input type="hidden" name="Cell Number" id="Cell_Number" value="<?php echo $MCell;?>" />
      &nbsp;</td>
  </tr>
  <tr>
    <td align="right">Fax:</td>
    <td><?php if(!$is_enabled){	if($MFax != "0") echo "(".$F1.") ".$F2."-".$F3; } else { ?>
      (
      <input name="F1" type="text" id="F1" size="6" value="<?php echo $F1;?>" maxlength="3" onkeyup="set_tel_number('Fax_Number','F');" />
      )
      <input name="F2" type="text" id="F2" size="6" value="<?php echo $F2;?>" maxlength="3" onkeyup="set_tel_number('Fax_Number','F');" />
      -
      <input name="F3" type="text" id="F3" size="8" value="<?php echo $F3;?>" maxlength="4" onkeyup="set_tel_number('Fax_Number','F');" />
      <?php } ?>
      <input type="hidden" name="Fax Number" id="Fax_Number" value="<?php echo $MFax;?>" />
      &nbsp;</td>
  </tr>
  <tr>
    <td align="right"> E-mail: </td>
    <td><?php if(!$is_enabled){	echo $MEMail; } else { ?>
      <input type="text" name="Email" id="Email" maxlength="75" value="<?php echo $MEMail; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td align="right">Website: </td>
    <td><?php if(!$is_enabled){	echo $MWeb; } else { ?>
      <input type="text" name="Website" id="Website" maxlength="150" value="<?php echo $MWeb; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td align="right">Display Information: </td>
    <td><?php if(!$is_enabled){ if($MDisp=="y")	print("Yes"); } else { ?>
      <input type="radio" name="Display" id="Display" value="y"<?php if($MDisp=="y"){print(" checked=\"checked\"");} ?> />
      Yes
      <input type="radio" name="Display" id="Display" value="n"<?php if($MDisp=="n"){print(" checked=\"checked\"");} ?> />
      No
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td align="right">Active Manufacture: </td>
    <td><?php if(!$is_enabled){ if($MAct=="y") print("Yes"); } else { ?>
      <input type="radio" name="Active" id="Active" value="y"<?php if($MAct=="y"){print(" checked=\"checked\"");} ?> />
      Yes
      <input type="radio" name="Active" id="Active" value="n"<?php if($MAct=="n"){print(" checked=\"checked\"");} ?> />
      No
      <?php } ?>
      &nbsp;</td>
  </tr>
  <?php } ?>
</table>
