<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../"; }
$RealPath = $r_path; $r_path = '../';
define ("PhotoExpress Pro", true);
require_once $r_path.'config.php';
require_once $r_path.'../Connections/cp_connection.php';
require_once $r_path.'scripts/encrypt.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$ID = (isset($_GET['id'])) ? clean_variable($_GET['id'],true) : '';
$DATA = isset($_GET['data']) ? unserialize(rawurldecode($_GET['data'])) : 0;
if(is_array($DATA)){
	foreach($DATA as $k => $v){
		if(is_array($v)){
			foreach($v as $k2 => $v2){
				$v[$k2] = trim(clean_variable($v2,true));
			}
			$DATA[$k] = $v;
		} else $DATA[$k] = trim(clean_variable($v,true));
	}
	
	$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$updInfo->mysql("SELECT `cust_id` FROM `photo_cust_defaults` WHERE `cust_id` = '".$ID."';");
	if($updInfo->TotalRows() == 0){
		$updInfo->mysql("INSERT INTO `photo_cust_defaults` (`cust_id`, `default_event`) VALUES ('".$ID."','".rawurlencode(serialize($DATA))."');");
	} else {
		$updInfo->mysql("UPDATE `photo_cust_defaults` SET `default_event` = '".rawurlencode(serialize($DATA))."' WHERE `cust_id` = '".$ID."';");
	}
}
		
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('Content-type: text/xml');

echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<saved>';
print_r($DATA);
echo '</saved>';
?>
