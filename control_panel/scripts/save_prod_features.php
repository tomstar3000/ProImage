<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_image_resize.php';
$FId = $path[4];
$ProdId = $path[2];
if(isset($_POST['Feature'])){
	$PFeats = $_POST['Feature'];
	$Count = count($PFeats);
	$PFeat = $PFeats[$Count-1];
} else {
	$PFeat = '0';
}
$PSel_Feat = (isset($_POST['Sel_Feat'])) ? $_POST['Sel_Feat'] : '0';
$FQty = (isset($_POST['Quantity'])) ? clean_variable($_POST['Quantity'],true) : '';
$FDesc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	$text = clean_variable($FDesc,'Store');
	if($cont == "add"){
		$add = "INSERT INTO `prod_link_prod_feat` (`prod_id`,`feat_id`,`link_prod_feat_qty`,`link_prod_feat_desc`) VALUES ('$ProdId','$PFeat','$FQty','$text');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	} else {
		$upd = "UPDATE `prod_link_prod_feat` SET `prod_id` = '$ProdId',`feat_id` = '$PFeat',`link_prod_feat_qty` ='$FQty', `link_prod_feat_desc` = '$text' WHERE `link_prod_feat_id` = '$FId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `prod_link_prod_feat` WHERE `link_prod_feat_id` = '$FId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$PFeat = $row_get_info['feat_id'];
		$FQty = $row_get_info['link_prod_feat_qty'];
		$FDesc = $row_get_info['link_prod_feat_desc'];
		
		mysql_free_result($get_info);
	}
}

?>