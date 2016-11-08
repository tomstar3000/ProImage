<?php
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++) $r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';  ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <th colspan="2" id="Form_Header"><img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Re-activate Event </p></th>
  </tr>
	<? if($Error) { ?>
	<tr>
	  <td colspan="2"><? echo $Error; ?></td>
	</tr>
	<? } ?>
  <tr>
    <td width="20%"><strong>Photographer Handle :</strong></td>
    <td width="80%"><input type="text" name="Handle" id="Handle" maxlength="75" value="<?php echo $Handle; ?>">
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Event Number :</strong></td>
    <td><input type="text" name="Event Number" id="Event_Number" maxlength="75" value="<?php echo $Event; ?>">
      &nbsp;</td>
  </tr>
</table>
