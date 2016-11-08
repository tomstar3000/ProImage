<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$GId = $path[4];
$ProdId = $path[2];
if(isset($_POST['Group'])){
	$PGroups = $_POST['Group'];
	$Count = count($PGroups);
	$PGroup = $PGroups[$Count-1];
} else {
	$PGroup = '0';
}
$PSel_Group = (isset($_POST['Sel_Group'])) ? $_POST['Sel_Group'] : '0';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){	
		$add = "INSERT INTO `prod_link_prod_group` (`prod_id`,`group_id`) VALUES ('$ProdId', '$PGroup');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	} else {
		
		$upd = "UPDATE `prod_link_prod_group` SET `prod_id` = '$ProdId',`group_id` = '$PGroup' WHERE `link_prod_group_id` = '$GId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `prod_link_prod_group` WHERE `link_prod_group_id` = '$GId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$PGroup = $row_get_info['group_id'];
		
		mysql_free_result($get_info);
	}
}

?>