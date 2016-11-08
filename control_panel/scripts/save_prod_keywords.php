<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once($r_path.'scripts/crumbs_pathing.php');
$ProdId = $path[2];
$keywords = isset($_POST['Keywords']) ? clean_variable($_POST['Keywords'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save"){
	$query_getInfo = "SELECT * FROM `prod_keywords` WHERE `prod_id` = '$ProdId'";
	$getInfo = mysql_query($query_getInfo, $cp_connection) or die(mysql_error());
	$row_getInfo = mysql_fetch_assoc($getInfo);
	$totalRows_getInfo = mysql_num_rows($getInfo);
	
	$KId = $row_getInfo['prod_keyword_id'];
	if($totalRows_getInfo == 0){
		if($_POST['Time'] != $_SESSION['Time']){
			$_SESSION['Time'] = $_POST['Time'];	
			
			$add = "INSERT INTO `prod_keywords` (`prod_id`,`keywords`) VALUES ('$ProdId','$keywords');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		}
	} else {
		$upd = "UPDATE `prod_keywords` SET `keywords` = '$keywords' WHERE `prod_keyword_id` = '$KId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
} else {
	if($cont != "add"){		
		$query_get_info = "SELECT * FROM `prod_keywords` WHERE `prod_id` = '$ProdId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$keywords = $row_get_info['keywords'];
		
		mysql_free_result($get_info);
	}
}
?>