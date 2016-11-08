<?php
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
include $r_path.'scripts/save_state_taxes.php'; 
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <?php if($is_enabled){ ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>State Tax Information</p></th>
  </tr>
  <?php } else { ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,3)); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,2)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>State Tax Information</p></th>
  </tr>
  <?php } ?>
  <tr>
    <td width="20%"><strong>State: </strong></td>
    <td width="80%"><?php if(!$is_enabled){	echo $TState;	} else { 
		$query_get_states = "SELECT * FROM `a_states` ORDER BY `state_short` ASC";
		$get_states = mysql_query($query_get_states, $cp_connection) or die(mysql_error());	?>
      <select name="State" id="State">
        <?php while ($row_get_states = mysql_fetch_assoc($get_states)) { ?>
        <option value="<?php echo $row_get_states['state_short']; ?>"<?php if($row_get_states['state_short'] == $TState){print(" selected=\"selected\"");} ?>><?php echo $row_get_states['state_short']; ?></option>
        <?php } ?>
      </select>
      <?php mysql_free_result($get_states);  } ?>
      <input type="hidden" name="State Tax Id" id="State_Tax_Id" value="<?php echo $STId; ?>" />
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Percent: </strong></td>
    <td><?php if(!$is_enabled){	echo $TPercent; } else { ?>
      <input type="text" name="Percent" id="Percent" maxlength="50" value="<?php echo $TPercent; ?>" />
      <?php } ?>
      %&nbsp;</td>
  </tr>
</table>
