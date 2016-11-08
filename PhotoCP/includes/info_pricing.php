<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
if((count($path)<=3 && $cont == "view") || (count($path)>3)){
	$is_enabled = false;
} else {
	$is_enabled = true;
} if($is_enabled){ ?>
<script type="text/javascript" src="/javascript/swatches.js"></script>

<div id="Bubble">
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td id="Bubble_topleft"><img src="/images/spacer.gif" width="1" height="1"></td>
      <td id="Bubble_top"><img src="/images/spacer.gif" width="1" height="1"></td>
      <td id="Bubble_topright"><img src="/images/spacer.gif" width="1" height="1"></td>
    </tr>
    <tr>
      <td id="Bubble_left"><img src="/images/spacer.gif" width="1" height="1"></td>
      <td rowspan="3" id="Bubble_text"></td>
      <td id="Bubble_right" rowspan="3"><img src="/images/spacer.gif" width="1" height="1"></td>
    </tr>
    <tr>
      <td id="Bubble_tap"><img src="/images/spacer.gif" width="1" height="1"></td>
    </tr>
    <tr>
      <td id="Bubble_left_2"><img src="/images/spacer.gif" width="1" height="1"></td>
    </tr>
    <tr>
      <td id="Bubble_bottomleft"><img src="/images/spacer.gif" width="1" height="1"></td>
      <td id="Bubble_bottom"><img src="/images/spacer.gif" width="1" height="1"></td>
      <td id="Bubble_bottomright"><img src="/images/spacer.gif" width="1" height="1"></td>
    </tr>
  </table>
</div>
<? } ?>
<h1 id="HdrType2" class="BsnPrcPrds">
  <div>Product Pricing Information</div>
</h1>
<div id="HdrLinks">
  <? if($is_enabled){ ?>
  <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status='Save Price List'; return true;" onmouseout="window.status=''; return true;" title="Save Price List" class="BtnSave2">Save</a><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','<? echo ($cont == "add")?'query':'view'; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Cancel'; return true;" onmouseout="window.status=''; return true;" title="Cancel" class="BtnCancel">Cancel</a>
  <? } else { ?>
  <? 
  if( $Owner != 0 ){
  ?>
    <a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Edit Price List <? echo $Name; ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit Price List <? echo $Name; ?>" class="BtnEdit2">Edit</a>
  <? }} ?>
</div>
<? if($is_enabled){ ?>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p class="Error">To Store Price Information you must check the box next to the price.
      The price next to the box is the lab price. Packages will have a description soon so you will know what is included in each
      package.</p>
  </div>
  <div id="Bottom"></div>
</div>
<? } ?>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records" class="Colmn4"> <span>
    <label for="Name" class="CstmFrmElmntLabel">Price List Name</label>
    <? if(!$is_enabled)echo $Name; else { ?>
    <input type="text" name="Name" id="Name" class="CstmFrmElmntInput" title="Price List Name" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Price List Name'; return true;" onmouseout="window.status=''; return true;" value="<? echo $Name; ?>">
    <? } ?>
    </span><br clear="all" />
    <span>
    <label class="CstmFrmElmntLabel">Product Name</label>
    </span> <span>
    <label class="CstmFrmElmntLabel">Your Price</label>
    </span> <span>
    <label class="CstmFrmElmntLabel">Lab Price</label>
    </span> <span>
    <label class="CstmFrmElmntLabel">Display Order</label>
    </span> <br clear="all" />
    <? $cur_cat_id = 0;
	 $getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	 $getInfo->mysql("SELECT `prod_products`.*, `prod_categories`.`cat_name`, `prod_cat_2`.`cat_name` AS `cat_name_2` FROM `prod_products` INNER JOIN `prod_categories` ON `prod_categories`.`cat_id` = `prod_products`.`cat_id` LEFT JOIN `prod_categories` AS `prod_cat_2` ON ((`prod_cat_2`.`cat_id` = `prod_categories`.`cat_parent_id` OR `prod_cat_2`.`cat_parent_id` = NULL)) WHERE `prod_products`.`prod_service` = 'n' AND `prod_categories`.`cat_show` = 'y' AND `prod_products`.`prod_use` = 'y' ORDER BY `prod_categories`.`cat_name`, `cat_name_2`, `prod_products`.`prod_name` ASC;");
	foreach($getInfo->Rows() as $r){
		$tempname = ($r['cat_name_2'] != NULL)? $r['cat_name_2']." - ".$r['cat_name'] : $r['cat_name'];
		$sort_array[$tempname][$r['prod_name']][$r['prod_id']]['Cat_Id'] = $r['prod_rec_width'];
		$sort_array[$tempname][$r['prod_name']][$r['prod_id']]['Width'] = $r['prod_rec_width'];
		$sort_array[$tempname][$r['prod_name']][$r['prod_id']]['Height'] = $r['prod_rec_height'];
		$sort_array[$tempname][$r['prod_name']][$r['prod_id']]['Res'] = $r['prod_rec_res'];
		$sort_array[$tempname][$r['prod_name']][$r['prod_id']]['Price'] = $r['prod_price'];
		$sort_array[$tempname][$r['prod_name']][$r['prod_id']]['Desc'] = $r['prod_desc'];
	}
	ksort($sort_array);
	foreach($sort_array as $k => $v){
		echo '<div style="background-color: #CCCCCC; padding:5px; margin-bottom:5px;">'.$k.'</div>';
		foreach($v as $k2 => $v2){
			foreach($v2 as $k3 => $v3){
				$cur_cat_id = $v3['Cat_Id'];
				$key = array_search($k3, $Id);
        // var_dump($Id);
				$Price = ($key === false) ? "" : number_format($prices[$key],2,".",",");
				if(!$is_enabled){ 
				if($key !== false){ ?>
    <span>&nbsp;&nbsp;&nbsp;&nbsp;<? echo $k2; ?></span> <span>$<? echo $Price; ?></span> <span>$<? echo number_format($v3['Price'],2,".",","); ?></span> <span><? echo $key+1; ?></span><br clear="all" />
    <? } } else { ?>
    <span>
    <p>
      <input type="checkbox" name="id[]" id="id[]" class="CstmFrmElmnt" title="Select <? echo $k2; ?>" onmouseover="window.status='Select <? echo $k2; ?>'; return true;" onmouseout="window.status=''; return true;" value="<? echo $k3; ?>"<? if($key !== false)echo ' checked="checked"';?> />
      <? if($v3['Desc'] != ""){ ?>
      <a style="cursor:pointer" onmouseover="popBubble('none','<h2><? echo $k2; ?></h2><p><? echo $v3['Desc']; ?></p>'); getMousePos();" onmouseout="hideBubble();" ><? echo $k2; ?></a>
      <? } else echo $k2; ?>
      <br clear="all" />
    </p>
    </span> <span>
    <input type="text" name="price[]" id="price[]" class="CstmFrmElmntInputi117" title="My Price for <? echo $k2; ?>" onfocus="javascript:this.className='CstmFrmElmntInputi117NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi117';" onmouseover="window.status='My Price for <? echo $k2; ?>'; return true;" onmouseout="window.status=''; return true;"  value="<? echo $Price; ?>" />
    </span> <span>$<? echo number_format($v3['Price'],2,".",","); ?></span> <span>
    <input name="order[]" type="text" id="order[]" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Price Order'; return true;" onmouseout="window.status=''; return true;" title="Price Order" value="<? echo ($key !== false) ? $key+1 : ""; ?>" maxlength="2" />
    <input type="hidden" name="ids[]" id="ids[]" value="<? echo $k3; ?>" />
    </span><br clear="all" />
    <? } } } } ?>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
