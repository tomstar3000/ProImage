<?  if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$EId = $path[2];
$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `event_num` FROM `photo_event` WHERE `event_id` = '$EId' LIMIT 0,1;");
$getInfo = $getInfo->Rows();

$FavCode = $getInfo[0]['event_num'].$CHandle;

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
		$getLast = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getLast->mysql("SELECT `fav_id` FROM `photo_cust_favories` WHERE `fav_email` = '$CHandle';");
		
		if($getLast->TotalRows() == 0){
			$addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$addInfo->mysql("INSERT INTO `photo_cust_favories` (`fav_email`,`fav_others`,`fav_date`,`fav_code`) VALUES ('$CHandle','n',NOW(),'$FavCode');");			
		
			$getLast = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$getLast->mysql("SELECT `fav_id` FROM `photo_cust_favories` WHERE `fav_email` = '$CHandle' AND `fav_code` = '$FavCode';");
			$getLast = $getLast->Rows();
			
			$FavId = $getLast[0]['fav_id'];
		} else {		
			$getLast = $getLast->Rows();
			$FavId = $getLast[0]['fav_id'];
			
			$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$updInfo->mysql("UPDATE `photo_cust_favories` SET `fav_email` = '$CHandle' WHERE `fav_id` = '$FavId';");
		}
		
		$addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$addInfo->mysql("INSERT INTO `photo_cust_favories_message` (`fav_id`,`fav_message`,`fav_date`) VALUES ('$FavId','$Message',NOW());");	
				
		$cont = "query";
	} else {
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql("UPDATE `photo_cust_favories` SET `fav_email` = '$Email' WHERE `fav_id` = '$FavId';");
		if($MsgId == 0 || $MsgId == ""){
			$updInfo->mysql("INSERT INTO `photo_cust_favories_message` (`fav_id`,`fav_message`,`fav_date`) VALUES ('$FavId','$Message',NOW());");
		} else {
			$updInfo->mysql("UPDATE `photo_cust_favories_message` SET `fav_message` = '$Message' WHERE `fav_message_id` = '$MsgId';");
		}
		$cont = "view";
	}
} else {
	if($cont != "add"){
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		if($MsgId == 0 || $MsgId == ""){ 
			$getInfo->mysql("SELECT `photo_cust_favories`.`fav_email` FROM `photo_cust_favories` WHERE `fav_id` = '$FavId';");
		} else {
			$getInfo->mysql("SELECT `photo_cust_favories`.`fav_email`, `photo_cust_favories_message`.`fav_message`
			FROM `photo_cust_favories`
			LEFT JOIN `photo_cust_favories_message`
				ON `photo_cust_favories_message`.`fav_id` = `photo_cust_favories`.`fav_id`
			WHERE `fav_message_id` = '$MsgId';");
		}
		$getInfo = $getInfo->Rows();		
		$Email = $getInfo[0]['fav_email'];
		$Message = $getInfo[0]['fav_message'];
	}
}
define('NoSave',true);
if(isset($required_string))
	$onclick = 'MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}';
else 
	$onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
?>