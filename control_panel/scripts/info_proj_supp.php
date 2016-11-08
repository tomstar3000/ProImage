<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
if($path[3] == "Proj"){
	$crumb_count = 7;
} else {
	$crumb_count = 4;
}
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<?php if($is_enabled){  ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,$crumb_count)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
    <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Supply Information</p></th>
  </tr>
	<?php } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,$crumb_count-1)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Supply Information</p></th>
  </tr>
  <?php } ?>
  <tr>
    <td width="20%"><strong>Name:</strong></td>
    <td width="80%"><input type="text" name="Name" id="Name" value="<?php echo $SName; ?>" /></td>
  </tr>
  <tr>
    <td><strong>Price:</strong></td>
    <td><input type="text" name="Price" id="Price" value="<?php echo $SPrice; ?>" /></td>
  </tr>
</table>
