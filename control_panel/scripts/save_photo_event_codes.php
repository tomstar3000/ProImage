<?  if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++) $r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$EId = $path[2];
$DID = $path[4];
$Discount = (isset($_POST['Discount'])) ? clean_variable($_POST['Discount'],true) : '0';

if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$add = "INSERT INTO `photo_event_disc` (`event_id`,`disc_id`) VALUES ('$EId','$Discount');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		
		$cont = "query";
	} else {
		$upd = "UPDATE `photo_event_disc` SET `disc_id` = '$Discount' WHERE `event_disc_id` = '$DID'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		
		$cont = "view";
	}
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `photo_event_disc` WHERE `event_disc_id` = '$DID'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$Discount = $row_get_info['disc_id'];
		
		mysql_free_result($get_info);
	}
}
?>