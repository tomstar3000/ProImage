<?  if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++) $r_path .= "../";}
include $r_path.'security.php';
$items = array();
$items = $_POST['Discount_items'];
$EId = $path[2];
function delete_vals($value){
	global $cp_connection, $EId;
	
	$add = "INSERT INTO `photo_event_disc` (`event_id`,`disc_id`) VALUES ('$EId','$value');";
	$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
}
$del= "DELETE FROM `photo_event_disc` WHERE `event_id` = '$EId'";
$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());

$optimize = "OPTIMIZE TABLE `photo_event_disc`";
$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
if(count($items)>0){
	foreach ($items as $key => $value) delete_vals($value);
}
?>