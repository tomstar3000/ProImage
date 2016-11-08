<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../"; }
$RealPath = $r_path; $r_path = '../';
define ("PhotoExpress Pro", true);
require_once $r_path.'config.php';
require_once $r_path.'../Connections/cp_connection.php';
require_once $r_path.'scripts/encrypt.php';
require_once $r_path.'scripts/fnct_clean_entry.php';


$DATA = isset($_GET['data']) ? unserialize(rawurldecode($_GET['data'])) : 0;
if(is_array($DATA)){
	foreach($DATA as $k => $v) $DATA[$k] = trim(clean_variable($v,true));
	$DATA['ID'] = trim(decrypt_data(base64_decode($DATA['ID'])));
	
	$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$updInfo->mysql("UPDATE `photo_event` SET `photo_to_lab` = '".$DATA['toLab']."',`photo_at_lab` = '".$DATA['pickUp']."' ,`photo_at_photo` = '".$DATA['shipTo']."' WHERE `event_id` = '".$DATA['ID']."';");
		
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header('Content-type: text/xml');
	
	echo '<?xml version="1.0" encoding="utf-8"?>';
	echo '<saved>';
	print_r($DATA);
	echo '</saved>';
}
?>
