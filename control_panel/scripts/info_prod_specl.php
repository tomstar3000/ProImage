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
      <p>Special Delivery Information</p></th>
  </tr><?php } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Special Delivery Information</p></th>
  </tr>
  <?php }
$query_get_special = "SELECT * FROM `prod_special_delivery` ORDER BY `spec_del_name` ASC";
$get_special = mysql_query($query_get_special, $cp_connection) or die(mysql_error());
$row_get_special = mysql_fetch_assoc($get_special);
$totalRows_get_special = mysql_num_rows($get_special);
?>
  <tr>
    <td width="20%"><strong>Special Delivery:</strong></td>
    <td width="80%"><?php if(!$is_enabled){
		do {
			if($row_get_special['spec_del_id'] == $SDelId){
				echo $row_get_special['spec_del_name']." $".number_format($row_get_special['spec_del_price'],2,".",",");
			}
		} while ($row_get_special = mysql_fetch_assoc($get_special));
	} else { ?>
      <select name="Special Delivery" id="Special_Delivery">
        <option value="0"<?php if($SDelId == 0){print(" selected=\"selected\"");} ?>>-- Select Special Delivery --</option>
        <?php do { ?>
        <option value="<?php echo $row_get_special['spec_del_id']; ?>"<?php if($row_get_special['spec_del_id'] == $SDelId){print(" selected=\"selected\"");} ?>><?php echo $row_get_special['spec_del_name']." $".number_format($row_get_special['spec_del_price'],2,".",","); ?></option>
        <?php } while ($row_get_special = mysql_fetch_assoc($get_special)); ?>
      </select>
      <input type="hidden" name="Prod_Id" id="Prod_Id" value="<?php echo $ProdId; ?>" />
      <?php } ?>    </td>
  </tr>
  <?php mysql_free_result($get_special); ?>
  <tr>
    <td><strong>Required:</strong></td>
    <td><?php if(!$is_enabled){
		echo ($SReq == "y") ? "Yes" : "No";
	} else { ?>
      <input type="radio" name="Required" id="Required" value="y"<?php if($SReq == "y"){print(" checked=\"checked\""); } ?>> Yes
      <input type="radio" name="Required" id="Required" value="n"<?php if($SReq == "n"){print(" checked=\"checked\""); } ?>> No
      <?php } ?></td>
  </tr>
</table>
