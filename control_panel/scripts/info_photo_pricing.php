<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++) $r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
if((count($path)<=3 && $cont == "view") || (count($path)>3)){
	$is_enabled = false;
} else {
	$is_enabled = true;
}
if($is_enabled){  ?>
<script type="text/javascript" src="/javascript/swatches.js"></script>

<div id="Bubble">
 <table border="0" cellpadding="0" cellspacing="0">
  <tr>
   <td id="Bubble_topleft"><img src="/images/spacer.gif" width="23" height="3"></td>
   <td id="Bubble_top"><img src="/images/spacer.gif" width="1" height="3"></td>
   <td id="Bubble_topright"><img src="/images/spacer.gif" width="5" height="3"></td>
  </tr>
  <tr>
   <td id="Bubble_left"><img src="/images/spacer.gif" width="23" height="1"></td>
   <td rowspan="3"><table border="0" cellpadding="10" cellspacing="0">
     <tr>
      <td id="Bubble_text">&nbsp;</td>
     </tr>
    </table></td>
   <td id="Bubble_right" rowspan="3"><img src="/images/spacer.gif" width="5" height="1"></td>
  </tr>	
  <tr>
   <td id="Bubble_tap"><img src="/images/spacer.gif" width="23" height="28"></td>
  </tr>
  <tr>
   <td id="Bubble_left_2"><img src="/images/spacer.gif" width="23" height="1"></td>
  </tr>
  <tr>
   <td id="Bubble_bottomleft"><img src="/images/spacer.gif" width="23" height="6"></td>
   <td id="Bubble_bottom"><img src="/images/spacer.gif" width="1" height="6"></td>
   <td id="Bubble_bottomright"><img src="/images/spacer.gif" width="5" height="6"></td>
  </tr>
 </table>
</div>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Pricing Information </h2>
 <p id="Back"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');">Back</a></p>
</div>
<? } else { ?>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Pricing Information</h2>
 <p id="Back"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,2)); ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');">Back</a></p>
 <p id="Edit"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');">Edit</a></p>
</div>
<? } ?>
<tr>
 <td colspan="3"><p style="margin:5px;"><font color="#FF0000">To Store Price Information you must check the box next to the price.
    The price next to the box is the lab price. Packages will have a description soon so you will know what is included in each
    package.</font></p></td>
</tr>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td colspan="2">Price List Name:</td>
  <td colspan="2"><? if(!$is_enabled){echo $Name;} else { ?>
   <input type="text" name="Name" id="Name" value="<? echo $Name; ?>">
   <? } ?>
   &nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td colspan="5">&nbsp;</td>
 </tr>
 <tr>
  <td><strong>Product Name:</strong> </td>
  <td><strong>Recommended Resolution: </strong></td>
  <td><strong>Your Price: </strong></td>
  <td><strong>Lab Price: </strong></td>
  <td><strong>Display Order:</strong></td>
 </tr>
 <?
	$cur_cat_id = 0;
	$query_get_info = "SELECT `prod_products`.*, `prod_categories`.`cat_name`, `prod_cat_2`.`cat_name` AS `cat_name_2` FROM `prod_products` INNER JOIN `prod_categories` ON `prod_categories`.`cat_id` = `prod_products`.`cat_id` LEFT JOIN `prod_categories` AS `prod_cat_2` ON ((`prod_cat_2`.`cat_id` = `prod_categories`.`cat_parent_id` OR `prod_cat_2`.`cat_parent_id` = NULL)) WHERE `prod_products`.`prod_service` = 'n' AND `prod_categories`.`cat_show` = 'y' ORDER BY `prod_categories`.`cat_name`, `cat_name_2`, `prod_products`.`prod_name` ASC";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$sort_array = array();
	while($row_get_info = mysql_fetch_assoc($get_info)){
		$tempname = ($row_get_info['cat_name_2'] != NULL)? $row_get_info['cat_name_2']." - ".$row_get_info['cat_name'] : $row_get_info['cat_name'];
		$sort_array[$tempname][$row_get_info['prod_name']][$row_get_info['prod_id']]['Cat_Id'] = $row_get_info['prod_rec_width'];
		$sort_array[$tempname][$row_get_info['prod_name']][$row_get_info['prod_id']]['Width'] = $row_get_info['prod_rec_width'];
		$sort_array[$tempname][$row_get_info['prod_name']][$row_get_info['prod_id']]['Height'] = $row_get_info['prod_rec_height'];
		$sort_array[$tempname][$row_get_info['prod_name']][$row_get_info['prod_id']]['Res'] = $row_get_info['prod_rec_res'];
		$sort_array[$tempname][$row_get_info['prod_name']][$row_get_info['prod_id']]['Price'] = $row_get_info['prod_price'];
		$sort_array[$tempname][$row_get_info['prod_name']][$row_get_info['prod_id']]['Desc'] = $row_get_info['prod_desc'];
	}
	ksort($sort_array);
	foreach($sort_array as $k => $v){
		echo '<tr><td colspan="5" style="background-color: #CCCCCC">'.$k.'</td></tr>';
		foreach($v as $k2 => $v2){
			foreach($v2 as $k3 => $v3){
				$cur_cat_id = $v3['Cat_Id'];
				$key = array_search($k3, $Id);
				//$Price = ($key === false) ? "" : $prices[$key]-$row_get_info['prod_price'];
				$Price = ($key === false) ? "" : number_format($prices[$key],2,".",",");
				if(!$is_enabled){ 
				if($key !== false){?>
 <tr>
  <td>&nbsp;&nbsp;&nbsp;&nbsp;<? echo $k2; ?></td>
  <td><? if($v3['Width'] != 0)echo $v3['Width']." x ".$v3['Height']." ".$v3['Res']." dpi"; ?></td>
  <td>$<? echo $Price; ?></td>
  <td>$<? echo number_format($v3['Price'],2,".",","); ?></td>
  <td><? echo $key+1; ?></td>
 </tr>
 <? } } else { ?>
 <tr>
  <td><input type="checkbox" name="id[]" id="id[]" value="<? echo $k3; ?>"<? if($key !== false)echo ' checked="checked"';?> onblur="javascript:set_warn();" />
   <input type="hidden" name="ids[]" id="ids[]" value="<? echo $k3; ?>" />
   <? if($v3['Desc'] != ""){ ?>
   <a style="cursor:pointer" onmouseover="popBubble('none','<h4>Product Description</h4><p><? echo $v3['Desc']; ?></p>'); getMousePos();" onmouseout="hideBubble();" ><? echo $k2; ?></a>
   <? } else {echo $k2; }?></td>
  <td><? if($v3['Width'] != 0)echo $v3['Width']." x ".$v3['Height']." ".$v3['Res']." dpi"; ?></td>
  <td> $
   <input type="text" name="price[]" id="price[]" value="<? echo $Price; ?>" /></td>
  <td>$<? echo number_format($v3['Price'],2,".",","); ?></td>
  <td><input name="order[]" type="text" id="order[]" value="<? echo ($key !== false) ? $key+1 : ""; ?>" size="3" maxlength="2" /></td>
 </tr>
 <? } } } } ?>
</table>
