<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$SId = $path[4];
$ProdId = $path[2];
$SDelId = (isset($_POST['Special_Delivery'])) ? clean_variable($_POST['Special_Delivery'],true) : '';
$SReq = (isset($_POST['Required'])) ? clean_variable($_POST['Required'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$add = "INSERT INTO `prod_link_prod_spec_del` (`prod_id`,`spec_del_id`,`required`) VALUES ('$ProdId','$SDelId','$SReq');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	} else {
		$upd = "UPDATE `prod_link_prod_spec_del` SET `prod_id` = '$ProdId',`spec_del_id` = '$SDelId',`required` = '$SReq' WHERE `link_prod_spec_del_id` = '$SId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `prod_link_prod_spec_del` WHERE `link_prod_spec_del_id` = '$SId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$SDelId = $row_get_info['spec_del_id'];
		$SReq = $row_get_info['required'];
		
		mysql_free_result($get_info);
	}
}

?>