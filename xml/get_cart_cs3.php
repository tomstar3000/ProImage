<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);
//require_once($r_path.'control_panel/scripts/test_login.php');

//$data = file_get_contents("php://input");
$data = $GLOBALS['HTTP_RAW_POST_DATA'];

include $r_path.'scripts/security.php';
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_xml_parser.php');

//$data = '<event number="pisano" photographer="RyanSerpan" email="development@proimagesoftware.com"></event>';
//$data = '<event number="dcw1" photographer="flyinghorse"></event>';
$data = '<?xml version="1.0" encoding="iso-8859-1"?>'.$data;
$parser = new XMLParser($data, 'raw', 1);
$tree = $parser->getTree();
$event_number = clean_variable($tree['EVENT']['ATTRIBUTES']['NUMBER'],true);
$event_number = str_replace("&amp;","&",$event_number);
$photographer = clean_variable($tree['EVENT']['ATTRIBUTES']['PHOTOGRAPHER'],true);
$Email = clean_variable($tree['EVENT']['ATTRIBUTES']['EMAIL'],true);

mysql_select_db($database_cp_connection, $cp_connection);

$query_cart = "SELECT `fav_cart` FROM `photo_cust_favories` WHERE `fav_code` = '".$event_number.$photographer."' AND `fav_email` = '$Email' AND `fav_occurance` = '2' ORDER BY `fav_id` DESC LIMIT 0,1";
$cart = mysql_query($query_cart, $cp_connection) or die(mysql_error());
$cartString = mysql_fetch_assoc($cart);

echo '<?xml version="1.0" encoding="iso-8859-1"?>';
echo '<cartelems><items>';
echo urlencode($cartString['fav_cart']);
echo '</items></cartelems>';
?>
