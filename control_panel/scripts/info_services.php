<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
include $r_path.'scripts/save_services.php';
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <?php if($is_enabled){  ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Service Information</p></th>
  </tr>
  <?php } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,3)); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,2)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Service Information</p></th>
  </tr>
  <?php 
  }
  if($Image != "" || $Thumb != "" || $Icon != ""){
  	echo "<tr><td colspan=\"4\" style=\"background-color:#FFFFFF; color:#CC0000;\">".$Image."<br />".$Thumb."<br />".$Icon."</td></tr>";
  }
	$query_get_parent = "SELECT `spec_part_id` FROM `prod_specs` WHERE `spec_id` = '$SPId'";
	$get_parent = mysql_query($query_get_parent, $cp_connection) or die(mysql_error());
	$row_get_parent = mysql_fetch_assoc($get_parent);
	$totalRows_get_parent = mysql_num_rows($get_parent);
	
	if($totalRows_get_parent != 0){
		$TempId = $row_get_parent['spec_part_id'];		
		
		$query_get_specs = "SELECT `spec_id`, `spec_name` FROM `prod_specs` WHERE `spec_part_id` = '$TempId'";
		$get_specs = mysql_query($query_get_specs, $cp_connection) or die(mysql_error());
		$row_get_specs = mysql_fetch_assoc($get_specs);
		$totalRows_get_specs = mysql_num_rows($get_specs);
	} else {
		$totalRows_get_specs = 0;
	}
	mysql_free_result($get_parent);

	if($totalRows_get_prarent_id != 0){
		mysql_free_result($get_specs);
	}
?>
  <tr>
    <td width="20%"><strong>Product Name: </strong></td>
    <td><?php if(!$is_enabled){	echo $PName; } else { ?>
      <input type="text" name="Name" id="Name" maxlength="75" value="<?php echo $PName; ?>" />
      <?php } ?>
      &nbsp;</td>
    <td colspan="2"><?php if($cont != "add" && $PDate != "0000-00-00 00:00:00" && $PDate != ""){ ?>
      Inserted/Updated: <?php echo format_date($PDate,"DayShort","Standard",true,true); }?>&nbsp;</td>
  </tr>
  <?php if(count($path) < 4){ ?>
  <tr>
    <td><strong>Product Numbers: </strong></td>
    <td><?php if(!$is_enabled){	echo $PNumber; } else { ?>
      <input type="text" name="Product Number" id="Product_Number" maxlength="50" value="<?php echo $PNumber; ?>" />
      <?php } ?>
      &nbsp;</td>
    <td><strong>Serial Number:</strong></td>
    <td><?php if(!$is_enabled){	echo $PSerial; } else { ?>
      <input type="text" name="Serial Number" id="Serial_Number" maxlength="75" value="<?php echo $PSerial; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <?php
	$query_get_man = "SELECT `sell_id`,`sell_cname` FROM `sellers` ORDER BY `sell_cname` ASC";
	$get_man = mysql_query($query_get_man, $cp_connection) or die(mysql_error());
	$row_get_man = mysql_fetch_assoc($get_man);
  ?>
  <tr>
    <td><strong>Manufacture / Seller: </strong></td>
    <td colspan="3"><?php if(!$is_enabled){
		do {
			if($row_get_man['sell_id'] == $PSel)echo $row_get_man['sell_cname'];
		} while ($row_get_man = mysql_fetch_assoc($get_man));
	} else { ?>
      <select name="Seller" id="Seller" >
        <option value="0"<?php if($PSel == 0){print(" selected=\"selected\"");} ?>>--
        Select Manufacture --</option>
        <?php do { ?>
        <option value="<?php echo $row_get_man['sell_id']; ?>"<?php if($row_get_man['sell_id'] == $PSel){print(" selected=\"selected\"");} ?>><?php echo $row_get_man['sell_cname']; ?></option>
        <?php } while ($row_get_man = mysql_fetch_assoc($get_man)); ?>
      </select>
      <?php } ?>
      &nbsp;</td>
  </tr>
  <?php
	if(count($PSel_Cal>0) && is_array($PSel_Cal)){
		foreach($PSel_Cal as $key => $value){
			if($value != $PCats[$key]){
				if($PCats[$key] == 0){
					if($key == 0){
						$PCat = 0;
					} else {
						$PCat = $PCats[$key-1];
					}
				} else {
					$PCat = $PCats[$key];
				}
				break;
			}
		}
	}
	
	$n = 0;
	$parents = array();
	find_parents($PCat,$n,'prod_categories','cat_parent_id','cat_id'); ?>
  <tr>
    <td valign="top"><strong>Category:</strong></td>
    <td valign="top" style="padding:0px; margin:0px;"><table border="0" cellpadding="0" cellspacing="0" style="padding:0px; margin:0px;">
        <?php
      for($n=count($parents)-1;$n>=0;$n--){ 
      $Temp_id = $parents[$n][0];
      $Cur_id = $parents[$n][1];
      
      $query_get_categories = "SELECT `cat_id`,`cat_name` FROM `prod_categories` WHERE `cat_parent_id` = '$Temp_id' ORDER BY `cat_name` ASC";
      $get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
      $row_get_categories = mysql_fetch_assoc($get_categories);
      $totalRows_get_categories = mysql_num_rows($get_categories);
      ?>
        <tr>
          <td><?php if(!$is_enabled){
		do {
			if($row_get_categories['cat_id'] == $Cur_id) echo $row_get_categories['cat_name'];
		} while ($row_get_categories = mysql_fetch_assoc($get_categories));
		echo "&nbsp;";
	} else { ?>
            <select name="Category[]" id="Category[]" onchange="document.getElementById('Controller').value='false'; document.getElementById('form_action_form').submit();">
              <option value="0"<?php if($Cur_id == 0){print(" selected=\"selected\"");} ?>>--
              Select Category --</option>
              <?php do { ?>
              <option value="<?php echo $row_get_categories['cat_id']; ?>"<?php if($row_get_categories['cat_id'] == $Cur_id){print(" selected=\"selected\"");} ?>><?php echo $row_get_categories['cat_name']; ?></option>
              <?php } while ($row_get_categories = mysql_fetch_assoc($get_categories)); ?>
            </select>
            <input type="hidden" name="Sel_Cat[]" id="Sel_Cat[]" value="<?php echo $Cur_id; ?>" />
            <?php } ?></td>
        </tr>
        <?php mysql_free_result($get_categories); } 
  if($cont == "add" || $cont == "edit"){
	$query_get_categories = "SELECT `cat_id`,`cat_name` FROM `prod_categories` WHERE `cat_parent_id` = '$PCat'";
	$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
	$row_get_categories = mysql_fetch_assoc($get_categories);
	$totalRows_get_categories = mysql_num_rows($get_categories);
	
	if($totalRows_get_categories != 0 && $Cur_id != 0){ ?>
        <tr>
          <td><select name="Category[]" id="Category[]" onchange="document.getElementById('Controller').value='false'; document.getElementById('form_action_form').submit();">
              <option value="0">-- Select Category --</option>
              <?php do { ?>
              <option value="<?php echo $row_get_categories['cat_id']; ?>"><?php echo $row_get_categories['cat_name']; ?></option>
              <?php } while ($row_get_categories = mysql_fetch_assoc($get_categories)); ?>
            </select>
            <input type="hidden" name="Sel_Cat[]" id="Sel_Cat[]" value="-1" /></td>
        </tr>
        <?php }	mysql_free_result($get_categories);	} ?>
      </table></td>
    <td valign="top" style="padding:0px; margin:0px;"><table border="0" cellpadding="0" cellspacing="0" style="padding:0px; margin:0px;">
        <?php
 	if(count($PSel_Cal_2>0) && is_array($PSel_Cal_2)){
		foreach($PSel_Cal_2 as $key => $value){
			if($value != $PCats_2[$key]){
				if($PCats_2[$key] == 0){
					if($key == 0){
						$PCat_2 = 0;
					} else {
						$PCat_2 = $PCats_2[$key-1];
					}
				} else {
					$PCat_2 = $PCats_2[$key];
				}
				break;
			}
		}
	}
	$n = 0;
	$parents = array();
	find_parents($PCat_2,$n,'prod_categories','cat_parent_id','cat_id');
	
	for($n=count($parents)-1;$n>=0;$n--){ 
		$Temp_id = $parents[$n][0];
		$Cur_id = $parents[$n][1];
		
		$query_get_categories = "SELECT `cat_id`,`cat_name` FROM `prod_categories` WHERE `cat_parent_id` = '$Temp_id' ORDER BY `cat_name` ASC";
		$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
		$row_get_categories = mysql_fetch_assoc($get_categories);
		$totalRows_get_categories = mysql_num_rows($get_categories);
	?>
        <tr>
          <td><?php if(!$is_enabled){
		do {
			if($row_get_categories['cat_id'] == $Cur_id) echo $row_get_categories['cat_name'];
		} while ($row_get_categories = mysql_fetch_assoc($get_categories));
		echo "&nbsp;";
	} else { ?>
            <select name="Category_2[]" id="Category_2[]" onchange="document.getElementById('Controller').value='false'; document.getElementById('form_action_form').submit();">
              <option value="0"<?php if($Cur_id == 0){print(" selected=\"selected\"");} ?>>--
              Select Category --</option>
              <?php do { ?>
              <option value="<?php echo $row_get_categories['cat_id']; ?>"<?php if($row_get_categories['cat_id'] == $Cur_id){print(" selected=\"selected\"");} ?>><?php echo $row_get_categories['cat_name']; ?></option>
              <?php } while ($row_get_categories = mysql_fetch_assoc($get_categories)); ?>
            </select>
            <input type="hidden" name="Sel_Cat_2[]" id="Sel_Cat_2[]" value="<?php echo $Cur_id; ?>" />
            <?php } ?></td>
        </tr>
        <?php 
  	mysql_free_result($get_categories);
  } 
  if($cont == "add" || $cont == "edit"){
	$query_get_categories = "SELECT `cat_id`,`cat_name` FROM `prod_categories` WHERE `cat_parent_id` = '$PCat_2'";
	$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
	$row_get_categories = mysql_fetch_assoc($get_categories);
	$totalRows_get_categories = mysql_num_rows($get_categories);
	
	if($totalRows_get_categories != 0 && $Cur_id != 0){ ?>
        <tr>
          <td><select name="Category_2[]" id="Category_2[]" onchange="document.getElementById('Controller').value='false'; document.getElementById('form_action_form').submit();">
              <option value="0">-- Select Category --</option>
              <?php do { ?>
              <option value="<?php echo $row_get_categories['cat_id']; ?>"><?php echo $row_get_categories['cat_name']; ?></option>
              <?php } while ($row_get_categories = mysql_fetch_assoc($get_categories)); ?>
            </select>
            <input type="hidden" name="Sel_Cat_2[]" id="Sel_Cat_2[]" value="-1" /></td>
        </tr>
        <?php }	mysql_free_result($get_categories);	}?>
      </table></td>
    <td valign="top" style="padding:0px; margin:0px;"><table border="0" cellpadding="0" cellspacing="0" style="padding:0px; margin:0px;">
        <?php
 	if(count($PSel_Cal_3>0) && is_array($PSel_Cal_3)){
		foreach($PSel_Cal_3 as $key => $value){
			if($value != $PCats_3[$key]){
				if($PCats_3[$key] == 0){
					if($key == 0){
						$PCat_3 = 0;
					} else {
						$PCat_3 = $PCats_3[$key-1];
					}
				} else {
					$PCat_3 = $PCats_3[$key];
				}
				break;
			}
		}
	}
	
	$n = 0;
	$parents = array();
	find_parents($PCat_3,$n,'prod_categories','cat_parent_id','cat_id');
	
	for($n=count($parents)-1;$n>=0;$n--){ 
		$Temp_id = $parents[$n][0];
		$Cur_id = $parents[$n][1];
		
		$query_get_categories = "SELECT `cat_id`,`cat_name` FROM `prod_categories` WHERE `cat_parent_id` = '$Temp_id' ORDER BY `cat_name` ASC";
		$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
		$row_get_categories = mysql_fetch_assoc($get_categories);
		$totalRows_get_categories = mysql_num_rows($get_categories);
	?>
        <tr>
          <td><?php if(!$is_enabled){
		do {
			if($row_get_categories['cat_id'] == $Cur_id) echo $row_get_categories['cat_name'];
		} while ($row_get_categories = mysql_fetch_assoc($get_categories));
		echo "&nbsp;";
	} else { ?>
            <select name="Category_3[]" id="Category_3[]" onchange="document.getElementById('Controller').value='false'; document.getElementById('form_action_form').submit();">
              <option value="0"<?php if($Cur_id == 0){print(" selected=\"selected\"");} ?>>--
              Select Category --</option>
              <?php do { ?>
              <option value="<?php echo $row_get_categories['cat_id']; ?>"<?php if($row_get_categories['cat_id'] == $Cur_id){print(" selected=\"selected\"");} ?>><?php echo $row_get_categories['cat_name']; ?></option>
              <?php } while ($row_get_categories = mysql_fetch_assoc($get_categories)); ?>
            </select>
            <input type="hidden" name="Sel_Cat_3[]" id="Sel_Cat_3[]" value="<?php echo $Cur_id; ?>" />
            <?php } ?></td>
        </tr>
        <?php mysql_free_result($get_categories); } 
  if($cont == "add" || $cont == "edit"){
	$query_get_categories = "SELECT `cat_id`,`cat_name` FROM `prod_categories` WHERE `cat_parent_id` = '$PCat_3'";
	$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
	$row_get_categories = mysql_fetch_assoc($get_categories);
	$totalRows_get_categories = mysql_num_rows($get_categories);
	
	if($totalRows_get_categories != 0 && $Cur_id != 0){ ?>
        <tr>
          <td><select name="Category_3[]" id="Category_3[]" onchange="document.getElementById('Controller').value='false'; document.getElementById('form_action_form').submit();">
              <option value="0">-- Select Category --</option>
              <?php do { ?>
              <option value="<?php echo $row_get_categories['cat_id']; ?>"><?php echo $row_get_categories['cat_name']; ?></option>
              <?php } while ($row_get_categories = mysql_fetch_assoc($get_categories)); ?>
            </select>
            <input type="hidden" name="Sel_Cat_3[]" id="Sel_Cat_3[]" value="-1" /></td>
        </tr>
        <?php } mysql_free_result($get_categories); } ?>
      </table></td>
  </tr>
  <tr>
    <td><strong>Availability:</strong></td>
    <td><?php 
	$query_get_availability = "SELECT * FROM `prod_availability` ORDER BY `avail_name` ASC";
	$get_availability = mysql_query($query_get_availability, $cp_connection) or die(mysql_error());
	$row_get_availability = mysql_fetch_assoc($get_availability);
	$totalRows_get_availability = mysql_num_rows($get_availability);
	
	if(!$is_enabled){
		do {
			if($row_get_availability['availability_id'] == $PAvail)	echo $row_get_availability['avail_name'];
		} while ($row_get_availability = mysql_fetch_assoc($get_availability));
	} else { ?>
      <select name="Availability" id="Availability">
        <?php do { ?>
        <option value="<?php echo $row_get_availability['availability_id']; ?>"<?php if ($row_get_availability['availability_id'] == $PAvail){print(" selected=\"selected\""); } ?>><?php echo $row_get_availability['avail_name']; ?></option>
        <?php } while ($row_get_availability = mysql_fetch_assoc($get_availability)); ?>
      </select>
      <?php mysql_free_result($get_availability); } ?></td>
    <td><strong>Ships-in:</strong></td>
    <td><?php 
	$query_get_shipin = "SELECT * FROM `prod_ships_in` ORDER BY `ships_in_name` ASC";
	$get_shipin = mysql_query($query_get_shipin, $cp_connection) or die(mysql_error());
	$row_get_shipin = mysql_fetch_assoc($get_shipin);
	if(!$is_enabled){
		do {
			if($row_get_shipin['ships_in_id'] == $PShips) echo $row_get_shipin['ships_in_name'];
		} while ($row_get_shipin = mysql_fetch_assoc($get_shipin));
	} else { ?>
      <select name="Ships In" id="Ships_In">
        <?php do { ?>
        <option value="<?php echo $row_get_shipin['ships_in_id']; ?>"<?php if ($row_get_shipin['ships_in_id'] == $PShips){print(" selected=\"selected\""); } ?>><?php echo $row_get_shipin['ships_in_name']; ?></option>
        <?php } while ($row_get_shipin = mysql_fetch_assoc($get_shipin)); ?>
      </select>
      <?php mysql_free_result($get_shipin);	}?></td>
  </tr>
  <?php 	
	//mysql_select_db($database_cp_connection, $cp_connection);
	$query_get_location = "SELECT `prod_loc_id`, `prod_loc_name`, `prod_loc_city`, `prod_loc_state` FROM `prod_locations` ORDER BY `prod_loc_name` ASC";
	$get_location = mysql_query($query_get_location, $cp_connection) or die(mysql_error());
	$row_get_location = mysql_fetch_assoc($get_location);
	$totalRows_get_location = mysql_num_rows($get_location);
	
	if($totalRows_get_location != 0){
		if(!$is_enabled){
			do {
				if($row_get_location['prod_loc_id'] == $PLoc){
					echo "<tr>\n<td align=\"right\">Location:</td>\n<td>".$row_get_location['prod_loc_name']." - ".$row_get_location['prod_loc_city'].", ".$row_get_location['prod_loc_state']."</td>\n</tr>";
				}
			} while ($row_get_location = mysql_fetch_assoc($get_location));
		} else {
	?>
    <tr>
      <td><strong>Location:</strong></td>
      <td colspan="3"><select name="Location" id="Location">
          <?php do { ?>
          <option value="<?php echo $row_get_location['prod_loc_id']; ?>"<?php if ($row_get_location['prod_loc_id'] == $PLoc){print(" selected=\"selected\""); } ?>><?php echo $row_get_location['prod_loc_name']; ?> - <?php echo $row_get_location['prod_loc_city']; ?>, <?php echo $row_get_location['prod_loc_state']; ?></option>
          <?php } while ($row_get_location = mysql_fetch_assoc($get_location)); ?>
        </select>
        &nbsp; </td>
    </tr>
    <?php }	mysql_free_result($get_location); }	
	
	$query_get_banks = "SELECT `bank_id`, `bank_name`, `bank_city`, `bank_state` FROM `finance_bank` ORDER BY `bank_name` ASC";
	$get_banks = mysql_query($query_get_banks, $cp_connection) or die(mysql_error());
	$row_get_banks = mysql_fetch_assoc($get_banks);
	$totalRows_get_banks = mysql_num_rows($get_banks);
	
	if($totalRows_get_banks != 0){
		if(!$is_enabled){
			do {
				if($row_get_banks['bank_id'] == $PBank) echo "<tr>\n<td align=\"right\">Financing Bank:</td>\n<td>".$row_get_banks['bank_name']." - ".$row_get_banks['bank_city'].", ".$row_get_banks['bank_state']."</td>\n</tr>";
			} while ($row_get_banks = mysql_fetch_assoc($get_banks)); 
		} else {
	?>
  <tr>
    <td><strong>Financing Bank:</strong></td>
    <td colspan="3"><select name="Bank" id="Bank">
        <?php do { ?>
        <option value="<?php echo $row_get_banks['bank_id']; ?>"<?php if ($row_get_banks['bank_id'] == $PBank){print(" selected=\"selected\""); } ?>><?php echo $row_get_banks['bank_name']; ?> - <?php echo $row_get_banks['bank_city']; ?>, <?php echo $row_get_banks['bank_state']; ?></option>
        <?php } while ($row_get_banks = mysql_fetch_assoc($get_banks)); ?>
      </select>
      &nbsp; </td>
  </tr>
  <?php }
	mysql_free_result($get_banks);	
	}
	?>
  <tr>
    <td><strong>Price:</strong></td>
    <td colspan="3"><?php if(!$is_enabled){
		echo "$ ".number_format($PPrice,2,".",",");
	} else { ?>
      <input type="text" name="Price" id="Price" maxlength="75" size="12" value="<?php echo $PPrice; ?>" />
      <?php } ?>
      &nbsp;&nbsp;&nbsp;Wholesale:&nbsp;
      <?php if(!$is_enabled){
		echo "$ ".number_format($PWhole,2,".",",");
	} else { ?>
      <input type="text" name="Whole Price" id="Whole_Price" maxlength="75" size="12" value="<?php echo $PWhole; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Sale Price:</strong></td>
    <td colspan="3"><?php if(!$is_enabled){
		if($PSaleExp != ""){
			echo "$ ".number_format($PSale,2,".",",")." Exp. ";
			echo format_date($PSaleExp,"DayShort","Standard",true,true);
		}
	} else { ?>
      <input type="text" name="Sale Price" id="Sale_Price" maxlength="75" size="12" value="<?php echo $PSale; ?>" />
      Exp.
      <input type="text" name="Sale Experation" id="Sale_Experation" maxlength="75" value="<?php echo $PSaleExp; ?>" />
      <a href="#" onclick="newwindow=window.open('scripts/calendar.php?future=true&time=true&field=Sale_Experation','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;"> Select
      Date</a> (yyyy-mm-dd hh:mm:ss)
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Special Fee:</strong> </td>
    <td colspan="3"><?php if(!$is_enabled){
		echo "$ ".number_format($PFee,2,".",",");
	} else { ?>
      <input type="text" name="Fee" id="Fee" maxlength="75" size="12" value="<?php echo $PFee; ?>" />
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Freight Cost: </strong></td>
    <td colspan="3"><?php if(!$is_enabled){
		echo ($PUFreight == "y") ? "Yes" : "No";
		echo "&nbsp;$".number_format($PFreight,2,".",",");
	} else { ?>
      <input type="radio" name="Use Freight" id="Use_Freight" value="y"<?php if($PUFreight == "y"){print(' checked="checked"');}?> />
      Yes
      <input type="radio" name="Use Freight" id="Use_Freight" value="n"<?php if($PUFreight == "n"){print(' checked="checked"');}?> />
      No
      &nbsp;
      <input type="text" name="Freight" id="Freight" maxlength="75" value="<?php echo $PFreight; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <?php	
	$query_get_spec_del = "SELECT spec_del_id, spec_del_name FROM prod_special_delivery ORDER BY spec_del_name ASC";
	$get_spec_del = mysql_query($query_get_spec_del, $cp_connection) or die(mysql_error());
	$row_get_spec_del = mysql_fetch_assoc($get_spec_del);
	$totalRows_get_spec_del = mysql_num_rows($get_spec_del);
	
	if($totalRows_get_spec_del != 0){
		if(!$is_enabled){
			do {
				if($row_get_spec_del['spec_del_id'] == $PSpecDel) echo "<tr>\n<td align=\"right\">Special Delivery Requirments:</td>\n<td>".$row_get_spec_del['spec_del_name']."</td>\n</tr>";
			} while ($row_get_spec_del = mysql_fetch_assoc($get_spec_del));
		} else {
	?>
  <tr>
    <td><strong>Special Delivery:</strong></td>
    <td colspan="3"><select name="Special Delivery" id="Special_Delivery">
        <option value="0"<?php if ($PSpecDel = 0){print(" selected=\"selected\""); } ?>>None</option>
        <?php do { ?>
        <option value="<?php echo $row_get_spec_del['spec_del_id']; ?>"<?php if ($row_get_spec_del['spec_del_id'] == $PSpecDel){print(" selected=\"selected\""); } ?>><?php echo $row_get_spec_del['spec_del_name']; ?></option>
        <?php } while ($row_get_spec_del = mysql_fetch_assoc($get_spec_del)); ?>
      </select>
      &nbsp; </td>
  </tr>
  <?php } mysql_free_result($get_spec_del);	} ?>
  <tr>
    <td><strong>Size and Weight:</strong></td>
    <td colspan="3"><?php if(!$is_enabled){
		echo "Size:&nbsp;".$PHeight." x ".$PWidth." x ".$PLength." Weight:&nbsp;".$PWeight." lbs.";
	} else { ?>
      Size:&nbsp;
      <input type="text" name="Height" id="Height" maxlength="75" size="3" value="<?php echo $PHeight; ?>" />
      x
      <input type="text" name="Width" id="Width" maxlength="75" size="3" value="<?php echo $PWidth; ?>" />
      x
      <input type="text" name="Length" id="Length" maxlength="75" size="3" value="<?php echo $PLength; ?>" />
      &nbsp;Weight:&nbsp;
      <input type="text" name="Weight" id="Weight" maxlength="75" size="3" value="<?php echo $PWeight; ?>" />
      <?php } ?>
      inch.&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Quantity:</strong></td>
    <td colspan="3"><?php if(!$is_enabled){
		echo $PQty."&nbsp;Use Quantity:&nbsp;";
		if($PUseQty=="y"){print("Yes");} else {print("No");}
	} else { ?>
      <input type="text" name="Quantity" id="Quantity" maxlength="75" size="3" value="<?php echo $PQty; ?>" />
      &nbsp;Use Quantity:
      <input type="radio" name="Use Quantity" id="Use_Quantity" value="y"<?php if($PUseQty=="y"){print(" checked=\"checked\"");} ?> />
      Yes
      <input type="radio" name="Use Quantity" id="Use_Quantity" value="n"<?php if($PUseQty=="n"){print(" checked=\"checked\"");} ?> />
      No
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Discontinue:</strong></td>
    <td colspan="3"><?php if(!$is_enabled){
		if($PDiscon != "") echo format_date($PDiscon,"DayShort","Standard",true,true);
	} else { ?>
      <input type="text" name="Discontinue" id="Discontinue" maxlength="75" value="<?php echo $PDiscon; ?>" />
      <a href="#" onclick="newwindow=window.open('scripts/calendar.php?future=true&time=true&field=Discontinue','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;"> Select
      Date</a> (yyyy-mm-dd hh:mm:ss)
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><strong>Short Description:</strong></td>
  </tr>
  <tr>
    <td colspan="4" style="padding:10px;<?php if($cont == "add" || $cont == "edit"){ ?> height:300px;<?php } ?>" valign="top">
	<?php if(!$is_enabled){	echo $PSDesc; } else {
		$oFCKeditor = new FCKeditor('Short_Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $PSDesc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create();
		$output = $oFCKeditor->CreateHtml();
	}?></td>
  </tr>
  <tr>
    <td colspan="4"><strong>Description:</strong></td>
  </tr>
  <tr>
    <td colspan="4" style="padding:10px;<?php if($cont == "add" || $cont == "edit"){ ?> height:300px;<?php } ?>" valign="top">
	<?php if(!$is_enabled){	echo $PDesc; } else {
		$oFCKeditor = new FCKeditor('Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $PDesc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	} ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Zoom Image:</strong></td>
    <td colspan="3"><?php if($cont == "add" || $cont == "edit"){ ?>
      <input type="file" name="Image" id="Image">
      <input type="hidden" name="Image_val" id="Image_val" value="<?php echo $Imagev;?>">
      <?php }
	  if($Imagev != ""){?>
      &nbsp;<a href="<?php echo $Prod_Folder; ?>/<?php echo $Imagev;?>" target="_blank">View</a>
      <?php } 
	  if($cont == "add" || $cont == "edit"){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" />
      Remove Image
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Large Image:</strong></td>
    <td colspan="3"><?php if($cont == "add" || $cont == "edit"){ ?>
      <input type="file" name="Thumb" id="Thumb">
      <input type="hidden" name="Thumb_val" id="Thumb_val" value="<?php echo $Thumbv;?>">
      <?php }
	  if($Thumbv != ""){?>
      &nbsp;<a href="<?php echo $Prod_Folder; ?>/<?php echo $Thumbv;?>" target="_blank">View</a>
      <?php } 
	  if($cont == "add" || $cont == "edit"){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Thumb" id="Remove_Thumb" value="true" />
      Remove Thumbnail
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Thumbnail:</strong></td>
    <td colspan="3"><?php if($cont == "add" || $cont == "edit"){ ?>
      <input type="file" name="Icon" id="Icon">
      <input type="hidden" name="Icon_val" id="Icon_val" value="<?php echo $Iconv;?>">
      <?php }
	  if($Iconv != ""){?>
      &nbsp;<a href="<?php echo $Prod_Folder; ?>/<?php echo $Iconv;?>" target="_blank">View</a>
      <?php } 
	  if($cont == "add" || $cont == "edit"){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Icon" id="Remove_Icon" value="true" />
      Remove Icon
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Reoccurring Billing: </strong></td>
    <td colspan="3"><?php if(!$is_enabled){	echo ($PRecYear=="y") ? "Yearly" : "Monthly"; } else { ?>
      <input type="radio" name="Reoccurring Billing" id="Reoccurring_Billing" value="y"<?php if($PRecure=="y"){print(" checked=\"checked\"");} ?> />
      Yes
      <input type="radio" name="Reoccurring Billing" id="Reoccurring_Billing" value="n"<?php if($PRecure=="n"){print(" checked=\"checked\"");} ?> />
      No
      <input type="radio" name="Reoccurring Year" id="Reoccurring_Year" value="y"<?php if($PRecYear=="y"){print(" checked=\"checked\"");} ?> />
      Year
      <input type="radio" name="Reoccurring Year" id="Reoccurring_Year" value="n"<?php if($PRecYear=="n"){print(" checked=\"checked\"");} ?> />
      Month (No for Upgrade Options)
      <?php } ?>
      &nbsp; </td>
  </tr>
  <?php } ?>
</table>
