<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
$r_path = '../';
require_once $r_path.'includes/cp_connection.php';
require_once $r_path.'scripts/encrypt.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$data = unserialize($_GET['data']);
foreach($data as $k => $v) $k = trim(clean_variable($v,true));
$action = trim(clean_variable($_GET['action'],true));
$master = trim(clean_variable(decrypt_data(base64_decode($_GET['master'])),true));
$handle = trim(clean_variable(urldecode(base64_decode($_GET['handle'])),true));
$email = trim(clean_variable(urldecode(base64_decode($_GET['email'])),true));

$getFavs = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getFavs->mysql("SELECT `fav_xml` FROM `photo_cust_favories` WHERE `fav_code` = '".$handle."' AND `fav_email` = '".$email."' AND `fav_occurance` = '2' ORDER BY `fav_date` DESC;");
if($getFavs -> TotalRows() > 0){ $getFavs = $getFavs->Rows();
	$getFavs = explode(".",$getFavs[0]['fav_xml']); $ADD = false;
} else { $getFavs = array(); $ADD = true; }

foreach($data as $v){
	if($action == "true"){ if(!in_array($v,$getFavs)) $getFavs[] = $v;
	} else { if(in_array($v,$getFavs)) { $k = array_search($v,$getFavs);  unset($getFavs[$k]); } }
}
if(count($getFavs)>0){
	$Favs = implode(".",$getFavs);
	$getFavs = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	if($ADD == false){
		$getFavs->mysql("UPDATE `photo_cust_favories` SET `fav_xml` = '".$Favs."', `fav_occurance` = '2' WHERE `fav_code` = '".$handle."' AND `fav_email` = '".$email."';");
	} else {
		$getFavs->mysql("INSERT INTO `photo_cust_favories` (`fav_email`, `fav_code`, `fav_xml`, `fav_occurance`) VALUES ('$email', '$handle', '".$Favs."', '2');");
	}
} else {
	$getFavs = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	if($ADD == false){
		$getFavs->mysql("UPDATE `photo_cust_favories` SET `fav_xml` = '', `fav_occurance` = '2' WHERE `fav_code` = '".$handle."' AND `fav_email` = '".$email."';");
	} else {
		$getFavs->mysql("INSERT INTO `photo_cust_favories` (`fav_email`, `fav_code`, `fav_xml`, `fav_occurance`) VALUES ('$email', '$handle', '', '2');");
	}
}

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<finished>true</finished>';
?>
