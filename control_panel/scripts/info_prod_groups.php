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
      <p>Group Information</p></th>
  </tr><?php } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Group Information</p></th>
  </tr>
  <?php }
	if(count($PSel_Group>0) && is_array($PSel_Group)){
		foreach($PSel_Group as $key => $value){
			if($value != $PGroups[$key]){
				if($PGroups[$key] == 0){
					if($key == 0){
						$PGroup = 0;
					} else {
						$PGroup = $PGroups[$key-1];
					}
				} else {
					$PGroup = $PGroups[$key];
				}
				break;
			}
		}
	}
	
	$n = 0;
	$parents = array();
	find_parents($PGroup,$n,'prod_groups','group_part_id','group_id');
	
	for($n=count($parents)-1;$n>=0;$n--){ 
		$Temp_id = $parents[$n][0];
		$Cur_id = $parents[$n][1];
		
		$query_get_groups = "SELECT `group_id`,`group_name` FROM `prod_groups` WHERE `group_part_id` = '$Temp_id' ORDER BY `group_name` ASC";
		$get_groups = mysql_query($query_get_groups, $cp_connection) or die(mysql_error());
		$row_get_groups = mysql_fetch_assoc($get_groups);
		$totalRows_get_groups = mysql_num_rows($get_groups);
	?>
  <tr>
    <td width="20%"><strong>Group:</strong></td>
    <td width="80%"><?php if(!$is_enabled){
		do {
			if($row_get_groups['group_id'] == $Cur_id){
				echo $row_get_groups['group_name'];
			}
		} while ($row_get_groups = mysql_fetch_assoc($get_groups));
	} else { ?>
      <select name="Group[]" id="Group[]" onchange="document.getElementById('Controller').value='false'; document.getElementById('form_action_form').submit();">
        <option value="0"<?php if($Cur_id == 0){print(" selected=\"selected\"");} ?>>-- Select Group --</option>
        <?php do { ?>
        <option value="<?php echo $row_get_groups['group_id']; ?>"<?php if($row_get_groups['group_id'] == $Cur_id){print(" selected=\"selected\"");} ?>><?php echo $row_get_groups['group_name']; ?></option>
        <?php } while ($row_get_groups = mysql_fetch_assoc($get_groups)); ?>
      </select>
      <input type="hidden" name="Sel_Group[]" id="Sel_Group[]" value="<?php echo $Cur_id; ?>" />
      <?php } ?>    </td>
  </tr>
  <?php 
  	mysql_free_result($get_groups);
  } 
  if($is_enabled){
	$query_get_groups = "SELECT `group_id`,`group_name` FROM `prod_groups` WHERE `group_part_id` = '$PGroup'";
	$get_groups = mysql_query($query_get_groups, $cp_connection) or die(mysql_error());
	$row_get_groups = mysql_fetch_assoc($get_groups);
	$totalRows_get_groups = mysql_num_rows($get_groups);
	
	if($totalRows_get_groups != 0 && $Cur_id != 0){ ?>
  <tr>
    <td>&nbsp;</td>
    <td><select name="Group[]" id="Group[]" onchange="document.getElementById('Controller').value='false'; document.getElementById('form_action_form').submit();">
        <option value="0">-- Select Group --</option>
        <?php do { ?>
        <option value="<?php echo $row_get_groups['group_id']; ?>"><?php echo $row_get_groups['group_name']; ?></option>
        <?php } while ($row_get_groups = mysql_fetch_assoc($get_groups)); ?>
      </select>
      <input type="hidden" name="Sel_Group[]" id="Sel_Group[]" value="-1" /></td>
  </tr>
  <?php } mysql_free_result($get_groups); } ?>
</table>
