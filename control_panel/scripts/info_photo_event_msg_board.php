<?  if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";
if($is_enabled){  ?>

<div id="Sub_Header"> <img src="/<?  echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
 <h2>Message Board</h2>
 <p id="Back"><a href="#" onclick="javascript:set_form('form_','<?  echo implode(",",array_slice($path,0,5)); ?>','<?  echo $is_back; ?>','<?  echo $sort; ?>','<?  echo $rcrd; ?>');">Back</a></p>
</div>
<?  } else { ?>
<div id="Sub_Header"> <img src="/<?  echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
 <h2>Message Board</h2>
 <p id="Edit"><a href="#" onclick="javascript:set_form('form_','<?  echo implode(",",$path); ?>','edit','<?  echo $sort; ?>','<?  echo $rcrd; ?>');">Edit</a></p>
 <p id="Back"><a href="#" onclick="javascript:set_form('form_','<?  echo implode(",",array_slice($path,0,4)); ?>','<?  echo $is_back; ?>','<?  echo $sort; ?>','<?  echo $rcrd; ?>');">Back</a></p>
</div>
<? }
		$query_get_info = "SELECT * FROM `prod_discount_codes` WHERE `cust_id` = '0' ORDER BY `disc_name` ASC";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$totalRows_get_info = mysql_num_rows($get_info); ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td width="20%"><strong>E-mail:</strong></td>
  <td width="80%"><? if(!$is_enabled){ echo $Email; } else { ?>
   <input type="text" name="Email" id="Email" value="<? echo $Email; ?>">
   <? } ?>  </td>
 </tr>
 <tr>
  <td width="20%"><strong>Message:</strong></td>
  <td width="80%">&nbsp;</td>
 </tr>
 <tr>
  <td colspan="2"><?  if(!$is_enabled){ echo $Message; } else { ?>
   <textarea name="Message" id="Message" style="width:400px; height:250px;"><? echo $Message; ?></textarea>
   <? } ?>  </td>
 </tr>
</table>
