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
$is_back = ($cont == "edit") ? "view" : "query"; ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <?php if($is_enabled){  ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div><img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Spec Information</p></th>
  </tr><?php } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Spec Information</p></th>
  </tr>
  <?php }
	if(count($PSel_Spec>0) && is_array($PSel_Spec)){
		foreach($PSel_Spec as $key => $value){
			if($value != $PSpecs[$key]){
				if($PSpecs[$key] == 0){
					if($key == 0){
						$PSpec = 0;
					} else {
						$PSpec = $PSpecs[$key-1];
					}
				} else {
					$PSpec = $PSpecs[$key];
				}
				break;
			}
		}
	}
	
	$n = 0;
	$parents = array();
	find_parents($PSpec,$n,'prod_specs','spec_part_id','spec_id');
	
	for($n=count($parents)-1;$n>=0;$n--){ 
		$Temp_id = $parents[$n][0];
		$Cur_id = $parents[$n][1];
		
		$query_get_specs = "SELECT `spec_id`,`spec_name` FROM `prod_specs` WHERE `spec_part_id` = '$Temp_id' ORDER BY `spec_name` ASC";
		$get_specs = mysql_query($query_get_specs, $cp_connection) or die(mysql_error());
		$row_get_specs = mysql_fetch_assoc($get_specs);
		$totalRows_get_specs = mysql_num_rows($get_specs);
	?>
  <tr>
    <td width="20%"><strong>Spec:</strong></td>
    <td width="80%"><?php if(!$is_enabled){
		do {
			if($row_get_specs['spec_id'] == $Cur_id){
				echo $row_get_specs['spec_name'];
			}
		} while ($row_get_specs = mysql_fetch_assoc($get_specs));
	} else { ?>
      <select name="Spec[]" id="Spec[]" onchange="document.getElementById('controller').value='false'; document.getElementById('Spec_Information_Form').submit();">
        <option value="0"<?php if($Cur_id == 0){print(" selected=\"selected\"");} ?>>-- Select Spec --</option>
        <?php do { ?>
        <option value="<?php echo $row_get_specs['spec_id']; ?>"<?php if($row_get_specs['spec_id'] == $Cur_id){print(" selected=\"selected\"");} ?>><?php echo $row_get_specs['spec_name']; ?></option>
        <?php } while ($row_get_specs = mysql_fetch_assoc($get_specs)); ?>
      </select>
      <input type="hidden" name="Sel_Spec[]" id="Sel_Spec[]" value="<?php echo $Cur_id; ?>" />
      <?php } ?>    </td>
  </tr>
  <?php 
  	mysql_free_result($get_specs);
  } 
  if($_GET['cont'] == "add" || $_GET['cont'] == "edit"){
	$query_get_specs = "SELECT `spec_id`,`spec_name` FROM `prod_specs` WHERE `spec_part_id` = '$PSpec'";
	$get_specs = mysql_query($query_get_specs, $cp_connection) or die(mysql_error());
	$row_get_specs = mysql_fetch_assoc($get_specs);
	$totalRows_get_specs = mysql_num_rows($get_specs);
	
	if($totalRows_get_specs != 0 && $Cur_id != 0){ ?>
  <tr>
    <td>&nbsp;</td>
    <td><select name="Spec[]" id="Spec[]" onchange="document.getElementById('controller').value='false'; document.getElementById('Spec_Information_Form').submit();">
        <option value="0">-- Select Spec --</option>
        <?php do { ?>
        <option value="<?php echo $row_get_specs['spec_id']; ?>"><?php echo $row_get_specs['spec_name']; ?></option>
        <?php } while ($row_get_specs = mysql_fetch_assoc($get_specs)); ?>
      </select>
      <input type="hidden" name="Sel_Spec[]" id="Sel_Spec[]" value="-1" />    </td>
  </tr>
  <?php 
	}
	mysql_free_result($get_specs);	
	}
  ?>
  <tr>
    <td><strong>Name:</strong></td>
    <td><?php if(!$is_enabled){ 
	echo $SName;
	} else { ?>
      <input type="text" name="Name" id="Name" value="<?php echo $SName; ?>" />
      <?php } ?></td>
  </tr>
  <tr>
    <td><strong>Quantity:</strong></td>
    <td><?php if(!$is_enabled){ 
	echo $SQty;
	} else { ?>
      <input type="text" name="Quantity" id="Quantity" value="<?php echo $SQty; ?>" size="3" />
      <?php } ?></td>
  </tr>
</table>
