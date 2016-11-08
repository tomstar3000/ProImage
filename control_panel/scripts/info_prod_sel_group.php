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
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Selection Group  Information</p></th>
  </tr>
  <?php
$query_get_selection = "SELECT * FROM `prod_selections` ORDER BY `sel_name` ASC";
$get_selection = mysql_query($query_get_selection, $cp_connection) or die(mysql_error());
$row_get_selection = mysql_fetch_assoc($get_selection);
$totalRows_get_selection = mysql_num_rows($get_selection);
?>
  <tr>
    <td><strong>Selection Group:</strong> </td>
    <td><select name="Selection Group" id="Selection_Group">
	  <option value="0"<?php if($SGroup == 0){print(' selected="selected"');}?>>-- Select Selection Group --</option>
	  <?php do{ ?>
	  <option value="<?php echo $row_get_selection['sel_id']; ?>"<?php if($SGroup == $row_get_selection['sel_id']){print(' selected="selected"');}?>><?php echo $row_get_selection['sel_name']; ?></option>
	  <?php } while($row_get_selection = mysql_fetch_assoc($get_selection)); ?>
	</select></td>
  </tr>
  
  <?php mysql_free_result($get_selection); ?>
  <tr>
    <td><strong>Page:</strong></td>
    <td><input name="Page" type="text" id="Page" value="<?php echo $SPage; ?>" size="3" /></td>
  </tr>
  <tr>
    <td><strong>Multiple Selections:</strong> </td>
    <td><input type="radio" name="Multiple Selections" id="Multiple_Selections" value="y"<?php if($SMSelect == 'y'){print(' checked="checked"');}?> /> Yes <input type="radio" name="Multiple Selections" id="Multiple_Selections" value="n"<?php if($SMSelect == 'n'){print(' checked="checked"');}?> /> No</td>
  </tr>
  <tr>
    <td><strong>Select None:</strong> </td>
    <td><input type="radio" name="Select None" id="Select_None" value="y"<?php if($SNone == 'y'){print(' checked="checked"');}?> />
Yes
  <input type="radio" name="Select None" id="Select_None" value="n"<?php if($SNone == 'n'){print(' checked="checked"');}?> />
No</td>
  </tr>
  <tr>
    <td width="20%"><strong>Product List :</strong></td>
    <td width="80%"><?php
	foreach($drop_downs as $key => $value){
		print('<select name="Prod_Dropdowns[]" id="Prod_Dropdowns[]" onchange="document.getElementById(\'Controller\').value=\'change\'; document.getElementById(\'form_action_form\').submit();">');
			foreach($value as $k => $v){
				if($k == 0){
					$sel_val = $v[0];
				} else {
					print('<option value="'.$v[0].'"');
					if($sel_val == $v[0]){
						print(' selected="selected"');
					}
					print('>'.$v[1].'</option>');
				}
			}
		print('</select>');
		print('<input type="hidden" name="Prod_Sel_Dropdowns[]" id="Prod_Sel_Dropdowns[]" value="'.$sel_val.'" />');
	}
	?></td>
  </tr>
  <tr>
    <td colspan="2" style="padding:10px;" valign="top"><p>Key:</p>
      <blockquote>
        <p>To enter multiple quantities separate them by a comma; i.e. 1,2,3</p>
    </blockquote></td>
  </tr>
  <tr>
    <td colspan="2"><div class="pane">
        <table border="0" cellpadding="0" cellspacing="0" width="97%">
		  <tr>
		    <td>&nbsp;</td>
		    <td>Product</td>
		    <td>Product Price</td>
		    <td>New Price</td>
		    <td>Quantity</td>
		  </tr>
          <?php 
		  if($records[1][1]!= "" && $records[1][1] != " " && $records[1][1]!= "0"){
		  foreach($records as $key => $value){ ?>
          <tr>
            <td><input type="checkbox" name="Product_Id[]" id="Product_Id[]" value="<?php echo $value[1]; ?>" /></td>
            <td><?php echo $value[2].' - '.$value[3]; ?></td>
            <td><?php echo $value[4]; ?></td>
            <td><input type="text" name="Product Price[]" id="Product_Price[]" style="width:50px;" /></td>
            <td><input type="text" name="Product Quantity[]" id="Product_Quantity[]" style="width:25px;" /></td>
          </tr>
          <? } 
		  } else { ?>
		  <tr>
            <td colspan="5">No records found meeting your criteria.</td>
          </tr>
		  <?php }?>
        </table>
      </div></td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center"><input type="button" name="Transfer" id="Transfer" value=" /\  \/ " style="width:250px;" onclick="document.getElementById('Controller').value='change'; document.getElementById('form_action_form').submit();"/></td>
  </tr>
  <tr>
    <td colspan="2"><div class="pane">
        <table border="0" cellpadding="0" cellspacing="0" width="97%">
		<tr>
		    <td>&nbsp;</td>
		    <td>Default</td>
		    <td>Product</td>
		    <td>Product Price</td>
		    <td>New Price</td>
		    <td>Quantity</td>
		  </tr>
          <?php 
		  foreach($sel_prod_ids as $key => $value){ ?>
          <tr>
            <td><input type="checkbox" name="Selected Product Id[]" id="Selected_Product_Id[]" value="<?php echo $key; ?>" />
              <input type="hidden" name="Sel Product Id[]" id="Sel_Product_Id[]" value="<?php echo $key; ?>" />
              <input type="hidden" name="Sel Product Name[]" id="Sel_Product_Name[]" value="<?php echo $value[2]; ?>" />
              <input type="hidden" name="Sel Product Num[]" id="Sel_Product_Num[]" value="<?php echo $value[3]; ?>" />
              <input type="hidden" name="Sel Product O Price[]" id="Sel_Product_O_Price[]" value="<?php echo $value[4]; ?>" /></td>
            <td><input type="radio" name="Selected Default" id="Selected_Default" value="<?php echo $key; ?>"<?php if($value[0]){print(' checked="checked"');}?> /></td>
            <td><?php echo $value[2].' - '.$value[3]; ?></td>
            <td><?php echo preg_replace('/&lt;br \/&gt;/','<br />',$value[4]); ?></td>
            <td><input type="text" name="Selected Product Price[]" id="Selected_Product_Price[]" style="width:50px;" value="<?php echo $value[5]; ?>" /></td>
            <td><input type="text" name="Selected Product Quantity[]" id="Selected_Product_Quantity[]" style="width:25px;" value="<?php echo $value[6]; ?>" /></td>
          </tr>
          <? } ?>
        </table>
      </div></td>
  </tr>
</table>
