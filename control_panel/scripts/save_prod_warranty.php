<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$PId = $path[2];
$LWarnt = (isset($_POST['Warranty_Length'])) ? clean_variable($_POST['Warranty_Length'],true) : '';
$Warnt = (isset($_POST['Warranty'])) ? clean_variable($_POST['Warranty']) : '';
$LMWarnt = (isset($_POST['Warranty_Length'])) ? clean_variable($_POST['Warranty_Length'],true) : '';
$MWarnt = (isset($_POST['Manufacture_Warranty'])) ? clean_variable($_POST['Manufacture_Warranty']) : '';

$query_get_info = "SELECT * FROM `prod_warranty` WHERE `prod_id` = '$PId'";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
$totalRows_get_info = mysql_num_rows($get_info);
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save"){
	if($totalRows_get_info == 0){	
	
		if($_POST['Time'] != $_SESSION['Time']){
			$_SESSION['Time'] = $_POST['Time'];	
			$add = "INSERT INTO `prod_warranty` (`prod_id`, `warnt_length`,`warnt_desc`,`warnt_man_length`,`warnt_man_desc`) VALUES ('$PId','$LWarnt','$Warnt','$LMWarnt','$MWarnt');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		}
	} else {
		$upd = "UPDATE `prod_warranty` SET `warnt_length` = '$LWarnt',`warnt_desc` = '$Warnt', `warnt_man_length` = '$LMWarnt',`warnt_man_desc` = '$MWarnt' WHERE `prod_id` = '$PId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
} else {
	$LWarnt = $row_get_info['warnt_length'];
	$Warnt = $row_get_info['warnt_desc'];
	$LMWarnt = $row_get_info['warnt_man_length'];
	$MWarnt = $row_get_info['warnt_man_desc'];
}
mysql_free_result($get_info);
?>