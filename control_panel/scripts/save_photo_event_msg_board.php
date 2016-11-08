<?  if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++) $r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$EId = $path[2];

$query_get_info = "SELECT `event_num` 
	FROM `photo_event`
	WHERE `event_id` = '$EId'
	LIMIT 0,1";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

$FavCode = $row_get_info['event_num'].$CHandle;

$FavId = explode(".",$path[4]);
if(is_array($FavId)){
	$MsgId = $FavId[1];
	$FavId = $FavId[0];
} else {
	$MsgId = 0;
	$FavId = $FavId;
}
$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : $CHandle;
$Message = (isset($_POST['Message'])) ? clean_variable($_POST['Message'],true) : '';

if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){

		$query_get_info = "SELECT `fav_id` FROM `photo_cust_favories` WHERE `fav_email` = '$CHandle'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$total_get_info = mysql_num_rows($get_info);
		
		if($total_get_info == 0){
			$add = "INSERT INTO `photo_cust_favories` (`fav_email`,`fav_others`,`fav_date`,`fav_code`) VALUES ('$CHandle','n',NOW(),'$FavCode');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
			
		
			$query_get_info = "SELECT `fav_id` FROM `photo_cust_favories` WHERE `fav_email` = '$CHandle' AND `fav_code` = '$FavCode'";
			$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
			$row_get_info = mysql_fetch_assoc($get_info);
			
			$FavId = $row_get_info['fav_id'];
		} else {		
			$FavId = $row_get_info['fav_id'];
			
			$upd = "UPDATE `photo_cust_favories` SET `fav_email` = '$CHandle' WHERE `fav_id` = '$FavId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		}
		
		$add = "INSERT INTO `photo_cust_favories_message` (`fav_id`,`fav_message`,`fav_date`) VALUES ('$FavId','$Message',NOW());";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		
		$cont = "query";
	} else {
		$upd = "UPDATE `photo_cust_favories` SET `fav_email` = '$Email' WHERE `fav_id` = '$FavId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		if($MsgId == 0 || $MsgId == ""){
			$add = "INSERT INTO `photo_cust_favories_message` (`fav_id`,`fav_message`,`fav_date`) VALUES ('$FavId','$Message',NOW());";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		} else {
			$upd = "UPDATE `photo_cust_favories_message` SET `fav_message` = '$Message' WHERE `fav_message_id` = '$MsgId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		}
		$cont = "view";
	}
} else {
	if($cont != "add"){
		if($MsgId == 0 || $MsgId == ""){ 
			$query_get_info = "SELECT `photo_cust_favories`.`fav_email`
			FROM `photo_cust_favories`
			WHERE `fav_id` = '$FavId'";
		} else {
			$query_get_info = "SELECT `photo_cust_favories`.`fav_email`, `photo_cust_favories_message`.`fav_message`
			FROM `photo_cust_favories`
			LEFT JOIN `photo_cust_favories_message`
				ON `photo_cust_favories_message`.`fav_id` = `photo_cust_favories`.`fav_id`
			WHERE `fav_message_id` = '$MsgId'";
		}
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$Email = $row_get_info['fav_email'];
		$Message = $row_get_info['fav_message'];
		
		mysql_free_result($get_info);
	}
}
?>