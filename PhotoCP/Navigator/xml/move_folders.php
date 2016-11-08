<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
$r_path = '../';
require_once $r_path.'includes/cp_connection.php';
require_once $r_path.'scripts/encrypt.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$data = trim(clean_variable(decrypt_data(base64_decode($_GET['data'])),true));
$FID = clean_variable($_GET['fid'],true);
$NID = clean_variable($_GET['nid'],true);

$getRcrds = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getRcrds->mysql("UPDATE `photo_event_group` SET `parnt_group_id` = '$NID' WHERE `group_id` = '$FID' AND `event_id` = '$data';");

require_once $r_path.'xml/load_folders.php';
?>
