<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <th colspan="2" id="Form_Header"><p>Relationship Information</p></th>
 </tr>
 <?
$query_get_selection = "SELECT * FROM `prod_selections` ORDER BY `sel_name` ASC";
$get_selection = mysql_query($query_get_selection, $cp_connection) or die(mysql_error());
$row_get_selection = mysql_fetch_assoc($get_selection);
$totalRows_get_selection = mysql_num_rows($get_selection);
?>
 <? mysql_free_result($get_selection); ?>
 <tr>
  <td width="20%"><strong>Product List :</strong></td>
  <td width="80%"><?
	foreach($drop_downs as $key => $value){
	
		print('<select name="Prod_Dropdowns[]" id="Prod_Dropdowns[]" onchange="document.getElementById(\'Controller\').value=\'change\'; document.getElementById(\'form_action_form\').submit();">');
			foreach($value as $k => $v){
				if($k == 0){
					$sel_val = $v[0];
				} else {
					echo '<option value="'.$v[0].'"'.(($sel_val == $v[0]) ? ' selected="selected"' : '').'>'.$v[1].'</option>';
				}
			}
		print('</select>');
		print('<input type="hidden" name="Prod_Sel_Dropdowns[]" id="Prod_Sel_Dropdowns[]" value="'.$sel_val.'" />');
	}
	?></td>
 </tr>
 <tr>
  <td colspan="2"><div class="pane">
    <table border="0" cellpadding="0" cellspacing="0" width="97%">
     <tr>
      <td width="2%">&nbsp;</td>
      <td width="98%">Product</td>
     </tr>
     <? if($records[1][1]!= "" && $records[1][1] != " " && $records[1][1]!= "0"){
		  foreach($records as $key => $value){ ?>
     <tr>
      <td><input type="checkbox" name="Product_Id[]" id="Product_Id[]" value="<? echo $value[1]; ?>" /></td>
      <td><? echo $value[2].' - '.$value[3]; ?></td>
     </tr>
     <? } } else { ?>
     <tr>
      <td colspan="5">No records found meeting your criteria.</td>
     </tr>
     <? }?>
    </table>
   </div></td>
 </tr>
 <tr>
  <td colspan="2" style="text-align:center"><input type="button" name="Transfer" id="Transfer" value=" /\  \/ " style="width:250px;" onclick="document.getElementById('Controller').value='change'; this.form.submit();"/></td>
 </tr>
 <tr>
  <td colspan="2"><div class="pane">
    <table border="0" cellpadding="0" cellspacing="0" width="97%">
     <tr>
      <td width="2%">&nbsp;</td>
      <td width="98%">Product</td>
     </tr>
     <? 
		  foreach($sel_prod_ids as $key => $value){ ?>
     <tr>
      <td><input type="checkbox" name="Selected Product Id[]" id="Selected_Product_Id[]" value="<? echo $key; ?>" />
       <input type="hidden" name="Sel Product Id[]" id="Sel_Product_Id[]" value="<? echo $key; ?>" />
       <input type="hidden" name="Sel Product Name[]" id="Sel_Product_Name[]" value="<? echo $value[2]; ?>" />
       <input type="hidden" name="Sel Product Num[]" id="Sel_Product_Num[]" value="<? echo $value[3]; ?>" /></td>
      <td><? echo $value[2].' - '.$value[3]; ?></td>
     </tr>
     <? } ?>
    </table>
   </div></td>
 </tr>
</table>
