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
$SId = $path[4];
$ProdId = $path[2];
$SName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
if(isset($_POST['Spec'])){
	$PSpecs = $_POST['Spec'];
	$Count = count($PSpecs);
	$PSpec = $PSpecs[$Count-1];
} else {
	$PSpec = '0';
}
$PSel_Spec = (isset($_POST['Sel_Spec'])) ? $_POST['Sel_Spec'] : '0';
$SQty = (isset($_POST['Quantity'])) ? clean_variable($_POST['Quantity'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$add = "INSERT INTO `prod_link_prod_spec` (`prod_id`,`spec_id`,`link_prod_spec_qty`,`link_prod_spec_name`) VALUES ('$ProdId','$PSpec','$SQty','$SName');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	} else {
		$upd = "UPDATE `prod_link_prod_spec` SET `prod_id` = '$ProdId',`spec_id` = '$PSpec',`link_prod_spec_qty` ='$SQty', `link_prod_spec_name` = '$SName' WHERE `link_prod_spec_id` = '$SId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `prod_link_prod_spec` WHERE `link_prod_spec_id` = '$SId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$PSpec = $row_get_info['spec_id'];
		$SName = $row_get_info['link_prod_spec_name'];
		$SQty = $row_get_info['link_prod_spec_qty'];
		
		mysql_free_result($get_info);
	}
}

?>