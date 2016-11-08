<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";

define ("Allow Scripts", true);
$data = $GLOBALS['HTTP_RAW_POST_DATA'];

require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');

$parsed = json_decode( $data );
$code = clean_variable($parsed->favs->{'-number'}, true);
$photo = clean_variable($parsed->favs->{'-photographer'}, true);
$email = clean_variable($parsed->favs->{'-email'}, true);
$favs = clean_variable($parsed->favs->{'-favs'}, true);

echo $favs;

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

ob_start();

$wrapper = array( "favorates"=> array( "done"=> "true" ));
echo str_replace('\\/', '/', json_encode( $wrapper ));

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
