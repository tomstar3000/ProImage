<? 
if(strlen(trim($_SERVER['QUERY_STRING']))==0){
	$GoTo = "/index.php";
	header(sprintf("Location: %s", $GoTo));
	exit();
}

$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../";
define('Allow Scripts',true);
require_once($r_path.'scripts/cart/ssl_paths.php'); 
require_once($r_path.'scripts/fnct_format_phone.php');
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_sql_processor.php');
require_once $r_path.'scripts/emogrifier.php';
require_once $r_path.'scripts/fnct_phpmailer.php';

$Data = unserialize(urldecode(base64_decode(trim($_SERVER['QUERY_STRING']))));
foreach($Data as $k => $v) $Data[$k] = clean_variable($v,true);
$Event_id = $Data[0];
$handle = $Data[1];
$code = $Data[2];
$Email = $Data[3];
$Promo = $Data[4];
$codehandle = $code.$handle;
$codehandle = preg_replace("/[^a-zA-Z0-9]/", "",$codehandle);
		
$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `email` FROM `photo_quest_book` WHERE `email` = '$Email' AND `event_id` = '$Event_id' LIMIT 0,1;");
if($getInfo -> TotalRows() == 0){
	$addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$addInfo->mysql("INSERT INTO `photo_quest_book` (`event_id`,`email`,`promotion`) VALUES ('$Event_id','$Email','$Promo');");

  include $r_path.'includes/_EventMarketingGuestbook.php';
}

setcookie('PhotoExpress_Guestbook_'.$codehandle,$Email,(time()+60*60*24*30),'/');

$GoTo = "/photo_viewer.php?Photographer=".$handle."&code=".rawurlencode($code)."&email=".$Email;
header(sprintf("Location: %s", $GoTo));
?>