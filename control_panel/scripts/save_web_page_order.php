<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$PIds = (isset($_POST['Page_Id'])) ? $_POST['Page_Id'] : array();
$POrders = (isset($_POST['Page_Order'])) ? $_POST['Page_Order'] : array();
if(isset($_POST['Parnt_Page'])){
	$PPages = $_POST['Parnt_Page'];
	$Count = count($PPages);
	$PPage = $PPages[$Count-1];
} else {
	$PPages = array();
	$PPages[0] = "-1";
	$PPage = '0';
}
$PSel_Page = (isset($_POST['Sel_Parnt_Page'])) ? $_POST['Sel_Parnt_Page'] : array('-1');
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save"){	
	foreach($PIds as $k => $v){
		$upd = "UPDATE `web_review_navigation` SET `nav_order` = '$POrders[$k]' WHERE `nav_id` = '$v'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
}
?>