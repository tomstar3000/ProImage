<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
$r_path = '../';
require_once $r_path.'includes/cp_connection.php';
require_once $r_path.'scripts/encrypt.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$data = unserialize($_GET['data']);
foreach($data as $k => $v) $k = trim(clean_variable($v,true));
$master = trim(clean_variable(decrypt_data(base64_decode($_GET['master'])),true));

$FID = clean_variable($_GET['fid'],true);

foreach($data as $v){

	$getRcrds = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getRcrds->mysql("UPDATE `photo_event_images` SET `group_id` = '$FID' WHERE `image_id` = '$v' AND `cust_id` = '$master';");
}
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('Content-type: text/xml');

echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<finished>true</finished>';
?>
