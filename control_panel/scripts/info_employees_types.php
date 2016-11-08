<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <? if($is_enabled){ ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /></div>
      <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>EmployeeType Information</p></th>
  </tr>
  <? } else { ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,2)); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
      <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Employee Type  Information</p></th>
  </tr>
  <? } 
	if($Image === false || $Thumb === false){
  	echo "<tr><td colspan=\"4\" style=\"background-color:#FFFFFF; color:#CC0000;\">".$Image."<br />".$Thumb."<br />".$Icon."</td></tr>";
  }?>
  <tr>
    <td width="20%"><strong>Name: </strong></td>
    <td width="80%"><? if(!$is_enabled){	echo $Name;} else { ?>
      <input type="text" name="Name" id="Name" value="<? echo $Name;?>" maxlength="75" />
      &nbsp;<? } ?>
      &nbsp;</td>
  </tr>
  <? if(count($path) < 4){ ?>
  <? } ?>
</table>
