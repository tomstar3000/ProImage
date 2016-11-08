<?  if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++) $r_path .= "../";
}
include $r_path.'security.php';
$items = array();
$items = $_POST['Message_Board_items'];
function delete_vals($value){
	global $cp_connection;
		
	$FavId = explode(".",$value);
	if(is_array($FavId)){
		$MsgId = $FavId[1];
		$FavId = $FavId[0];
	} else {
		$MsgId = "";
		$FavId = $FavId;
	}
	
	$del= "DELETE FROM `photo_cust_favories_message` WHERE `fav_message_id` = '$MsgId'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	$optimize = "OPTIMIZE TABLE `photo_cust_favories_message`";
	$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
		
	$query_get_info = "SELECT `fav_id` 
	FROM `photo_cust_favories_message`
	WHERE `fav_id` = '$FavId'";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$total_get_info = mysql_num_rows($get_info);
	if($total_get_info == 0 && $FavId != 0){
		$del= "DELETE FROM `photo_cust_favories` WHERE `fav_id` = '$FavId'";
		$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
		
		$optimize = "OPTIMIZE TABLE `photo_cust_favories`";
		$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	}
}
if(count($items)>0){
	foreach ($items as $key => $value) delete_vals($value);
}
?>