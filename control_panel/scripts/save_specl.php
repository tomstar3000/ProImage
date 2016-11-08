<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
$SId = $path[2];
$SName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$SPrice = (isset($_POST['Price'])) ? clean_variable($_POST['Price'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$add = "INSERT INTO `prod_special_delivery` (`spec_del_name`,`spec_del_price`) VALUES ('$SName','$SPrice');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	} else {
		$upd = "UPDATE `prod_special_delivery` SET `spec_del_name` = '$SName',`spec_del_price` = '$SPrice' WHERE `spec_del_id` = '$SId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){		
		$query_get_info = "SELECT * FROM `prod_special_delivery` WHERE `spec_del_id` = '$SId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$SName = $row_get_info['spec_del_name'];
		$SPrice = $row_get_info['spec_del_price'];
		
		mysql_free_result($get_info);
	}
}
?>