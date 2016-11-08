<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("PhotoExpress Pro", true);
define('Allow Scripts',true);
include $r_path.'security.php';
require_once($r_path.'../Connections/cp_connection.php');
require_once($r_path.'includes/get_user_information.php'); ?>

<h1>Add / Edit Pricing Information</h1>
<form method="post" name="PricingForm" id="PricingForm" enctype="multipart/form-data">
  <label for="Name" class="CstmFrmElmntLabel">Price List Name</label>
  <input type="text" name="Name" id="Name" class="CstmFrmElmntInput" title="Price List Name" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Price List Name'; return true;" onmouseout="window.status=''; return true;" value="">
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
  <div style="height:250px; overflow:auto;">
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
	ksort($sort_array); foreach($sort_array as $k => $v){
		echo '<div style="background-color: #CCCCCC; padding:5px; margin-bottom:5px;">'.$k.'</div>';
		foreach($v as $k2 => $v2){ foreach($v2 as $k3 => $v3){ $cur_cat_id = $v3['Cat_Id']; ?>
    <span>
    <p>
      <input type="checkbox" name="id[]" id="id[]" title="Select <? echo $k2; ?>" onmouseover="window.status='Select <? echo $k2; ?>'; return true;" onmouseout="window.status=''; return true;" value="<? echo $k3; ?>" />
      <? /*if($v3['Desc'] != ""){ ?>
    <a style="cursor:pointer" onmouseover="popBubble('none','<h4>Product Description</h4><p><? echo $v3['Desc']; ?></p>'); getMousePos();" onmouseout="hideBubble();" ><? echo $k2; ?></a>
    <? } else */ echo $k2; ?>
      <br clear="all" />
    </p>
    </span> <span>
    <input type="text" name="price[]" id="price[]" class="CstmFrmElmntInputi117" title="My Price for <? echo $k2; ?>" onfocus="javascript:this.className='CstmFrmElmntInputi117NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi117';" onmouseover="window.status='My Price for <? echo $k2; ?>'; return true;" onmouseout="window.status=''; return true;"  value="" />
    </span> <span>$<? echo number_format($v3['Price'],2,".",","); ?></span> <span>
    <input name="order[]" type="text" id="order[]" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Price Order'; return true;" onmouseout="window.status=''; return true;" title="Price Order" value="" maxlength="2" />
    <input type="hidden" name="ids[]" id="ids[]" value="<? echo $k3; ?>" />
    </span><br clear="all" />
    <? } } } ?>
  </div>
  <? /* <div id="BtnImgSbmt" onclick="javascript:document.getElementById('InvoiceImageUpdaterForm').submit();"><input type="submit" name="Submit" id="Submit" value="Submit" /></div> */ ?>
  <input type="submit" name="BtnSubmit" id="BtnSubmit" value="Submit" onclick="javascript: Save_Price_Info(); return false;" />
  <input type="hidden" name="Controller" id="Controller" value="true" />
</form>
