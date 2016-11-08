<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);

$data = $GLOBALS['HTTP_RAW_POST_DATA'];

include $r_path.'scripts/security.php';
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');

$parsed = json_decode( $data );

$event_number = clean_variable($parsed->event->{'-number'}, true);
$event_number = str_replace("&amp;", "&", $event_number);
$photographer = clean_variable($parsed->event->{'-photographer'}, true);
$Email = clean_variable($parsed->event->{'-email'}, true);

mysql_select_db($database_cp_connection, $cp_connection);

$query_cart = "SELECT `fav_cart` FROM `photo_cust_favories` WHERE `fav_code` = '".$event_number.$photographer."' AND `fav_email` = '$Email' AND `fav_occurance` = '2' ORDER BY `fav_id` DESC LIMIT 0,1";
$cart = mysql_query($query_cart, $cp_connection) or die(mysql_error());
$cartString = mysql_fetch_assoc($cart);

$wrapper = array( "cartelems"=> array( "items"=> urlencode($cartString['fav_cart'])));

ob_start();

echo str_replace('\\/', '/', json_encode( $wrapper));

$rawData = ob_get_contents();
ob_end_clean();

$TMP = tempnam("/tmp", "TEMP"); // Create Temp File
$HND = fopen($TMP, "w"); // Open Temp File for writing
fwrite($HND, $rawData); // Write RawFile Data to Temp File
fclose($HND); // Close File Handle

header("Pragma: no-cache");
header("Expires: 0");
header('Content-Type: application/json');
header('Content-Length: '. filesize($TMP));

$HND = fopen($TMP, "r");
while (!feof($HND)) echo fread($HND, 8192);
fclose($HND);

unlink($TMP);

exit(0);
?>
