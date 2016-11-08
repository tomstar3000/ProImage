<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";
if($is_enabled){  ?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Discount Information </h2>
 <p id="Back"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');">Back</a></p>
</div>
<? } else { ?>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Discount Information</h2>
 <p id="Back"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,2)); ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');">Back</a></p>
 <p id="Edit"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');">Edit</a></p>
</div>
<? } ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <?php	if($Error) echo "<tr><td colspan=\"2\" style=\"background-color:#FFFFFF; color:#CC0000;\">".$Error."</td></tr>"; ?>
 <tr>
  <td width="20%"><strong>Discount Name:</strong></td>
  <td><?php if(!$is_enabled){ echo $CName; } else { ?>
   <input type="text" name="Name" id="Name" maxlength="75" value="<?php echo $CName; ?>" />
   <?php } ?></td>
 </tr>
 <tr>
  <td><strong>Discount Code:</strong></td>
  <td><?php if(!$is_enabled){	echo $CCode; } else { ?>
   <input type="text" name="Code" id="Code" maxlength="50" value="<?php echo $CCode; ?>" />
   <?php } ?></td>
 </tr>
 <tr>
  <td><strong>Percent:</strong></td>
  <td><?php if(!$is_enabled){	echo $CPercent; } else { ?>
   <input type="text" name="Percent" id="Percent" maxlength="50" value="<?php echo $CPercent; ?>" />
   <?php } ?></td>
 </tr>
 <tr>
  <td><strong>Price:</strong></td>
  <td><?php if(!$is_enabled){	echo $CPrice; } else { ?>
   <input type="text" name="Price" id="Price" maxlength="50" value="<?php echo $CPrice; ?>" />
   <?php } ?></td>
 </tr>
 <tr>
  <td><strong>Discontinue:</strong></td>
  <td><?php if(!$is_enabled){
		if($CDiscon != "")echo $CDiscon;
		} else { ?>
   <input type="text" name="Discontinue" id="Discontinue" maxlength="75" value="<?php echo $CDiscon; ?>" />
   <img src="/<?php echo $AevNet_Path; ?>/images/ico_cal.jpg" width="19" height="22" border="0" align="absbottom" onclick="newwindow=window.open('scripts/calendar.php?future=true&time=false&field=Discontinue','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;" style="cursor:pointer" /> (mm/dd/yyyy)
   <?php } ?></td>
 </tr>
 <tr>
  <td><strong>Number of Uses:</strong></td>
  <td><?php if(!$is_enabled){ echo $CUses; } else { ?>
   <input type="text" name="Number of Uses" id="Number_of_Uses" maxlength="50" value="<?php echo $CUses; ?>" />
   &nbsp;0 for infinite
   <?php } ?></td>
 </tr>
 <tr>
  <td><strong>Rolling Credit:</strong> </td>
  <td><?php if(!$is_enabled){	echo ($CRoll=="y")?"Yes":"No"; } else { ?>
   <input type="radio" name="Rolling Credit" id="Rolling_Credit" value="y"<?php if($CRoll=="y")echo' checked="checked"'; ?> />
   Yes
   <input type="radio" name="Rolling Credit" id="Rolling_Credit" value="n"<?php if($CRoll=="n")echo' checked="checked"'; ?> />
   No
   <?php } ?></td>
 </tr>
</table>
