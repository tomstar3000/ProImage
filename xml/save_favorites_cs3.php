<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";

define ("Allow Scripts", true);
//$data = file_get_contents("php://input");
$data = $GLOBALS['HTTP_RAW_POST_DATA'];

require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_xml_parser.php');

$data = file_get_contents("php://input");
$data = '<?xml version="1.0" encoding="iso-8859-1"?>'.$data;
$parser = new XMLParser($data, 'raw', 1);
$tree = $parser->getTree();
$code = $tree['FAVS']['ATTRIBUTES']['NUMBER'];
$photo = $tree['FAVS']['ATTRIBUTES']['PHOTOGRAPHER'];
$email = $tree['FAVS']['ATTRIBUTES']['EMAIL'];
$favs = $tree['FAVS']['ATTRIBUTES']['FAVS'];

mysql_select_db($database_cp_connection, $cp_connection);

$query_check = "SELECT `fav_id` FROM `photo_cust_favories` WHERE `fav_code` = '".$code.$photo."' AND `fav_email` = '$email' AND `fav_occurance` = '2' ORDER BY `fav_id` DESC LIMIT 0,1";
$check = mysql_query($query_check, $cp_connection) or die(mysql_error());
$total_check = mysql_num_rows($check);
if($total_check == 0){
	$query_check = "INSERT INTO `photo_cust_favories` (`fav_code`,`fav_email`,`fav_occurance`,`fav_date`,`fav_others`,`fav_xml`) VALUES ('".$code.$photo."','$email','2',NOW(),'y','$favs');";
} else {
	$row_check = mysql_fetch_assoc($check);
	$query_check = "UPDATE `photo_cust_favories` SET `fav_xml` = '$favs' WHERE `fav_id` = '".$row_check['fav_id']."';";
}
$check = mysql_query($query_check, $cp_connection) or die(mysql_error());
echo '<?xml version="1.0" encoding="iso-8859-1"?>';
?>
<favorates>
  <done>true</done>
</favorates>
