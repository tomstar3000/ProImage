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
      <p>Discount Code Information</p></th>
  </tr><?php } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,4)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Discount Code Information</p></th>
  </tr>
  <?php } if($Error) echo "<tr><td colspan=\"2\" style=\"background-color:#FFFFFF; color:#CC0000;\">".$Error."</td></tr>"; ?>
  <tr>
    <td width="20%"><strong>Discount Name: </strong></td>
    <td><?php if(!$is_enabled){ echo $CName; } else { ?>
      <input type="text" name="Name" id="Name" maxlength="75" value="<?php echo $CName; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Discount Code: </strong></td>
    <td><?php if(!$is_enabled){	echo $CCode; } else { ?>
      <input type="text" name="Code" id="Code" maxlength="50" value="<?php echo $CCode; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Percent:</strong></td>
    <td><?php if(!$is_enabled){	echo $CPercent; } else { ?>
      <input type="text" name="Percent" id="Percent" maxlength="50" value="<?php echo $CPercent; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Price:</strong></td>
    <td><?php if(!$is_enabled){	echo $CPrice; } else { ?>
      <input type="text" name="Price" id="Price" maxlength="50" value="<?php echo $CPrice; ?>" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Discontinue:</strong></td>
    <td><?php if(!$is_enabled){
		if($CDiscon != "")echo format_date($PDiscon,"DayShort","Standard",true,true);
		} else { ?>
      <input type="text" name="Discontinue" id="Discontinue" maxlength="75" value="<?php echo $CDiscon; ?>" />
      <img src="/<?php echo $AevNet_Path; ?>/images/ico_cal.jpg" width="19" height="22" border="0" align="absbottom" onclick="newwindow=window.open('scripts/calendar.php?future=true&time=true&field=Discontinue','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;" style="cursor:pointer" /> (yyyy-mm-dd hh:mm:ss)
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Number of Uses:</strong> </td>
    <td><?php if(!$is_enabled){ echo $CUses; } else { ?>
      <input type="text" name="Number of Uses" id="Number_of_Uses" maxlength="50" value="<?php echo $CUses; ?>" />
      <?php } ?>
      &nbsp;0 for Infanite </td>
  </tr>
</table>
