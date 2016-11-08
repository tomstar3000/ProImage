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
      <p>Attribute Information</p></th>
  </tr><?php } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Attribute Information</p></th>
  </tr>
  <?php } if(count($PSel_Attr>0) && is_array($PSel_Attr)){
		foreach($PSel_Attr as $key => $value){
			if($value != $PAttrs[$key]){
				if($PAttrs[$key] == 0){
					if($key == 0){
						$PAttr = 0;
					} else {
						$PAttr = $PAttrs[$key-1];
					}
				} else {
					$PAttr = $PAttrs[$key];
				}
				break;
			}
		}
	}
	
	$n = 0;
	$parents = array();
	find_parents($PAttr,$n,'prod_attributes','att_part_id','att_id');
	
	for($n=count($parents)-1;$n>=0;$n--){ 
		$Temp_id = $parents[$n][0];
		$Cur_id = $parents[$n][1];

		$query_get_attributes = "SELECT `att_id`,`att_name` FROM `prod_attributes` WHERE `att_part_id` = '$Temp_id' ORDER BY `att_name` ASC";
		$get_attributes = mysql_query($query_get_attributes, $cp_connection) or die(mysql_error());
		$row_get_attributes = mysql_fetch_assoc($get_attributes);
		$totalRows_get_attributes = mysql_num_rows($get_attributes);
	?>
  <tr>
    <td width="20%"><strong>Attribute:</strong></td>
    <td width="80%"><?php if(!$is_enabled){
		do {
			if($row_get_attributes['att_id'] == $Cur_id){
				echo $row_get_attributes['att_name'];
			}
		} while ($row_get_attributes = mysql_fetch_assoc($get_attributes));
	} else { ?>
      <select name="Attribute[]" id="Attribute[]" onchange="document.getElementById('controller').value='false'; document.getElementById('Attribute_Information_Form').submit();">
        <option value="0"<?php if($Cur_id == 0){print(" selected=\"selected\"");} ?>>-- Select Attribute --</option>
        <?php do { ?>
        <option value="<?php echo $row_get_attributes['att_id']; ?>"<?php if($row_get_attributes['att_id'] == $Cur_id){print(" selected=\"selected\"");} ?>><?php echo $row_get_attributes['att_name']; ?></option>
        <?php } while ($row_get_attributes = mysql_fetch_assoc($get_attributes)); ?>
      </select>
      <input type="hidden" name="Sel_Attr[]" id="Sel_Attr[]" value="<?php echo $Cur_id; ?>" />
      <?php } ?>
    </td>
  </tr>
  <?php mysql_free_result($get_attributes);  } 
  if($_GET['cont'] == "add" || $_GET['cont'] == "edit"){
	$query_get_attributes = "SELECT `att_id`,`att_name` FROM `prod_attributes` WHERE `att_part_id` = '$PAttr'";
	$get_attributes = mysql_query($query_get_attributes, $cp_connection) or die(mysql_error());
	$row_get_attributes = mysql_fetch_assoc($get_attributes);
	$totalRows_get_attributes = mysql_num_rows($get_attributes);
	
	if($totalRows_get_attributes != 0 && $Cur_id != 0){ ?>
  <tr>
    <td>&nbsp;</td>
    <td><select name="Attribute[]" id="Attribute[]" onchange="document.getElementById('controller').value='false'; document.getElementById('Attribute_Information_Form').submit();">
        <option value="0">-- Select Attribute --</option>
        <?php do { ?>
        <option value="<?php echo $row_get_attributes['att_id']; ?>"><?php echo $row_get_attributes['att_name']; ?></option>
        <?php } while ($row_get_attributes = mysql_fetch_assoc($get_attributes)); ?>
      </select>
      <input type="hidden" name="Sel_Attr[]" id="Sel_Attr[]" value="-1" />
    </td>
  </tr>
  <?php } mysql_free_result($get_attributes); } ?>
  <tr>
    <td><strong>Name:</strong></td>
    <td><?php if(!$is_enabled){
		echo $AName;
	} else { ?>
      <input type="text" name="Name" id="Name" maxlength="75" value="<?php echo $AName; ?>">
      <?php } ?>
      <input type="hidden" name="Attribute_Id" id="Attribute_Id" value="<?php echo $AId;?>" /></td>
  </tr>
  <tr>
    <td><strong>Quantity:</strong></td>
    <td><?php if(!$is_enabled){ 
	echo $PQty;
	} else { ?>
      <input type="text" name="Quantity" id="Quantity" value="<?php echo $PQty; ?>" size="3" />
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Price:</strong></td>
    <td colspan="3"><?php if(!$is_enabled){
		echo "$ ".number_format($APrice,2,".",",");
	} else { ?>
      <input type="text" name="Price" id="Price" maxlength="75" size="12" value="<?php echo $APrice; ?>" />
      <?php } ?>
      &nbsp;&nbsp;&nbsp;Wholesale:&nbsp;
      <?php if(!$is_enabled){
		echo "$ ".number_format($AWhole,2,".",",");
	} else { ?>
      <input type="text" name="Whole Price" id="Whole_Price" maxlength="75" size="12" value="<?php echo $AWhole; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Sale Price:</strong></td>
    <td colspan="3"><?php if(!$is_enabled){
		if($ASaleExp != ""){
			echo "$ ".number_format($ASale,2,".",",")." Exp. ";
			echo format_date($ASaleExp,"DayShort","Standard",true,true);
		}
	} else { ?>
      <input type="text" name="Sale Price" id="Sale_Price" maxlength="75" size="12" value="<?php echo $ASale; ?>" />
      Exp.
      <input type="text" name="Sale Experation" id="Sale_Experation" maxlength="75" value="<?php echo $ASaleExp; ?>" />
      <a href="#" onclick="newwindow=window.open('scripts/calendar.php?future=true&time=true&field=Sale_Experation','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;"> Select
      Date</a> (yyyy-mm-dd hh:mm:ss)
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Image:</strong></td>
    <td><?php if($_GET['cont'] == "add" || $_GET['cont'] == "edit"){ ?>
      <input type="file" name="Image" id="Image">
      <input type="hidden" name="Image_val" id="Image_val" value="<?php echo $Imagev;?>">
      <?php 
	  }
	  if($Image != ""){?>
      &nbsp;<a href="<?php echo $Prod_Attrib_Folder; ?>/<?php echo $Image;?>" target="_blank">View</a>
      <?php } 
	  if($_GET['cont'] == "add" || $_GET['cont'] == "edit"){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" />
      Remove Image
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Thumb:</strong></td>
    <td><?php if($_GET['cont'] == "add" || $_GET['cont'] == "edit"){ ?>
      <input type="file" name="Thumb" id="Thumb">
      <input type="hidden" name="Thumb_val" id="Thumb_val" value="<?php echo $Thumbv;?>">
      <?php 
	  }
	  if($Thumb != ""){?>
      &nbsp;<a href="<?php echo $Prod_Attrib_Folder; ?>/<?php echo $Thumb;?>" target="_blank">View</a>
      <?php } 
	  if($_GET['cont'] == "add" || $_GET['cont'] == "edit"){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Thumb" id="Remove_Thumb" value="true" />
      Remove Thumb
      <?php } ?></td>
  </tr>
</table>
