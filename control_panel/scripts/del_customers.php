<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
$items = array();
$items = $_POST['Pending_Customers_items'];
$accpt_decline = ($_POST['Controller'] == "Accept") ? 'y' : 'n';
function delete_vals($value, $accpt_decline){
	global $cp_connection;
	
	$upd = "UPDATE `cust_customers` SET `cust_active` = '$accpt_decline' WHERE `cust_id` = '$value'";
	$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());	
}
if(count($items)>0){
	foreach ($items as $key => $value){
		delete_vals($value, $accpt_decline);
	}
}
?>