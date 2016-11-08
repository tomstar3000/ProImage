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
      <p>Sales Representative Information</p></th>
  </tr>
  <?php } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Sales Representative Information</p></th>
  </tr>
  <?php } ?>
  <tr>
    <td width="20%"><strong>Name:</strong></td>
    <td width="80%"><?php if(!$is_enabled){ echo $SFName." ".$SMInt." ".$SLName; } else { ?>
      <input type="text" name="First Name" id="First_Name" value="<?php echo $SFName; ?>" />
      <input type="text" name="Middle Int" id="Middle_Int" value="<?php echo $SMInt; ?>" size="3" maxlength="1" />
      <input type="text" name="Last Name" id="Last_Name" value="<?php echo $SLName; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Phone:</strong></td>
    <td><?php if(!$is_enabled){
		if($Phone != "0") echo "(".$P1.") ".$P2."-".$P3;
	 } else { ?>
      (
      <input name="P1" type="text" id="P1" size="6" value="<?php echo $P1;?>" maxlength="3" onKeyUp="set_tel_number('Phone_Number','P');" />
      )
      <input name="P2" type="text" id="P2" size="6" value="<?php echo $P2;?>" maxlength="3" onKeyUp="set_tel_number('Phone_Number','P');" />
      -
      <input name="P3" type="text" id="P3" size="8" value="<?php echo $P3;?>" maxlength="4" onKeyUp="set_tel_number('Phone_Number','P');" />
      <?php } ?>
      <input type="hidden" name="Phone Number" id="Phone_Number" value="<?php echo $Phone;?>" />
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Cell:</strong></td>
    <td><?php if(!$is_enabled){
		if($Cell != "0") echo "(".$C1.") ".$C2."-".$C3;
	 } else { ?>
      (
      <input name="C1" type="text" id="C1" size="6" value="<?php echo $C1;?>" maxlength="3" onKeyUp="set_tel_number('Cell_Number','C');" />
      )
      <input name="C2" type="text" id="C2" size="6" value="<?php echo $C2;?>" maxlength="3" onKeyUp="set_tel_number('Cell_Number','C');" />
      -
      <input name="C3" type="text" id="C3" size="8" value="<?php echo $C3;?>" maxlength="4" onKeyUp="set_tel_number('Cell_Number','C');" />
      <?php } ?>
      <input type="hidden" name="Cell Number" id="Cell_Number" value="<?php echo $Cell;?>" />
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Fax:</strong></td>
    <td><?php if(!$is_enabled){
		if($Fax != "0")	echo "(".$F1.") ".$F2."-".$F3;
	 } else { ?>
      (
      <input name="F1" type="text" id="F1" size="6" value="<?php echo $F1;?>" maxlength="3" onKeyUp="set_tel_number('Fax_Number','F');" />
      )
      <input name="F2" type="text" id="F2" size="6" value="<?php echo $F2;?>" maxlength="3" onKeyUp="set_tel_number('Fax_Number','F');" />
      -
      <input name="F3" type="text" id="F3" size="8" value="<?php echo $F3;?>" maxlength="4" onKeyUp="set_tel_number('Fax_Number','F');" />
      <?php } ?>
      <input type="hidden" name="Fax Number" id="Fax_Number" value="<?php echo $Fax;?>" />
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>E-Mail:</strong></td>
    <td><?php if(!$is_enabled){	echo $SEmail; } else { ?>
      <input type="text" name="Email" id="Email" value="<?php echo $SEmail; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
</table>
