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
      <p>Feature Information</p></th>
  </tr>
  <?php } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Feature Information</p></th>
  </tr>
  <?php }
	if(count($PSel_Feat>0) && is_array($PSel_Feat)){
		foreach($PSel_Feat as $key => $value){
			if($value != $PFeats[$key]){
				if($PFeats[$key] == 0){
					if($key == 0){
						$PFeat = 0;
					} else {
						$PFeat = $PFeats[$key-1];
					}
				} else {
					$PFeat = $PFeats[$key];
				}
				break;
			}
		}
	}
	
	$n = 0;
	$parents = array();
	find_parents($PFeat,$n,'prod_features','feat_part_id','feat_id');
	
	for($n=count($parents)-1;$n>=0;$n--){ 
		$Temp_id = $parents[$n][0];
		$Cur_id = $parents[$n][1];
		
		$query_get_features = "SELECT `feat_id`,`feat_name` FROM `prod_features` WHERE `feat_part_id` = '$Temp_id' ORDER BY `feat_name` ASC";
		$get_features = mysql_query($query_get_features, $cp_connection) or die(mysql_error());
		$row_get_features = mysql_fetch_assoc($get_features);
		$totalRows_get_features = mysql_num_rows($get_features);
	?>
  <tr>
    <td width="20%"><strong>Feature:</strong></td>
    <td width="80%"><?php if(!$is_enabled){
		do {
			if($row_get_features['feat_id'] == $Cur_id){
				echo $row_get_features['feat_name'];
			}
		} while ($row_get_features = mysql_fetch_assoc($get_features));
	} else { ?>
      <select name="Feature[]" id="Feature[]" onchange="document.getElementById('controller').value='false'; document.getElementById('Feature_Information_Form').submit();">
        <option value="0"<?php if($Cur_id == 0){print(" selected=\"selected\"");} ?>>-- Select Feature --</option>
        <?php do { ?>
        <option value="<?php echo $row_get_features['feat_id']; ?>"<?php if($row_get_features['feat_id'] == $Cur_id){print(" selected=\"selected\"");} ?>><?php echo $row_get_features['feat_name']; ?></option>
        <?php } while ($row_get_features = mysql_fetch_assoc($get_features)); ?>
      </select>
      <input type="hidden" name="Sel_Feat[]" id="Sel_Feat[]" value="<?php echo $Cur_id; ?>" />
      <?php } ?>
    </td>
  </tr>
  <?php 
  	mysql_free_result($get_features);
  } 
  if($is_enabled){
	$query_get_features = "SELECT `feat_id`,`feat_name` FROM `prod_features` WHERE `feat_part_id` = '$PFeat'";
	$get_features = mysql_query($query_get_features, $cp_connection) or die(mysql_error());
	$row_get_features = mysql_fetch_assoc($get_features);
	$totalRows_get_features = mysql_num_rows($get_features);
	
	if($totalRows_get_features != 0 && $Cur_id != 0){ ?>
  <tr>
    <td>&nbsp;</td>
    <td><select name="Feature[]" id="Feature[]" onchange="document.getElementById('controller').value='false'; document.getElementById('Feature_Information_Form').submit();">
        <option value="0">-- Select Feature --</option>
        <?php do { ?>
        <option value="<?php echo $row_get_features['feat_id']; ?>"><?php echo $row_get_features['feat_name']; ?></option>
        <?php } while ($row_get_features = mysql_fetch_assoc($get_features)); ?>
      </select>
      <input type="hidden" name="Sel_Feat[]" id="Sel_Feat[]" value="-1" />
    </td>
  </tr>
  <?php } mysql_free_result($get_features);	}  ?>
  <tr>
    <td><strong>Quantity:</strong></td>
    <td><?php if(!$is_enabled){ 
	echo $FQty;
	} else { ?>
      <input type="text" name="Quantity" id="Quantity" value="<?php echo $FQty; ?>" size="3" />
      <?php } ?></td>
  </tr>
  <tr>
    <td colspan="2" style="padding:10px;<?php if($_GET['cont'] == "add" || $_GET['cont'] == "edit"){ ?> height:300px;<?php } ?>"  valign="top"><?php if(!$is_enabled){
		echo $FDesc;
	} else {
		$oFCKeditor = new FCKeditor('Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $FDesc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	} ?>
      &nbsp;</td>
  </tr>
</table>
