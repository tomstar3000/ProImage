<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
require_once($r_path.'json/custom/util/headers.php');

define ("Allow Scripts", true);

$data = $GLOBALS['HTTP_RAW_POST_DATA'];

include $r_path.'scripts/security.php';
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'json/vendor/jwt_helper.php');

$parsed = json_decode( $data );

$first = (isset($parsed->data->{'-first'})) ? clean_variable($parsed->data->{'-first'}, true) : '';
$promo = (isset($parsed->data->{'-promo'})) ? clean_variable($parsed->data->{'-promo'}, true) : 'n';

$email;
$eventID;

if(isset($parsed->data->{'-email'})){
  $email = clean_variable($parsed->data->{'-email'}, true);
}

if(isset($parsed->data->{'-event-id'})){
  $eventID = clean_variable($parsed->data->{'-event-id'}, true);
}

// echo 'hi';
if( $email && $eventID ){
  
  $stuff = JWT::encode($token, 'secret_server_key');
  /*$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
  $getInfo-> mysql("SELECT `email` FROM `photo_quest_book` WHERE `email` = '$email' AND `event_id` = '$eventID' LIMIT 0,1;");

  $addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
  if( $getInfo-> TotalRows() > 0 ){
    // echo "yay";
    $addInfo->mysql("UPDATE `photo_quest_book` SET `visits` = `visits` + 1, `last_login` = NOW(), `promotion` = '$promo' WHERE `email` = '$email' AND `event_id` = '$eventID';");
  }
  else{
    // echo "nay";
    $addInfo->mysql("INSERT INTO `photo_quest_book` (`event_id`,`email`,`visits`, `first_login`, `last_login`, `promotion`) VALUES ('$eventID','$email','1', NOW(), NOW(), '$promo' );");
  }*/

  // echo "yup";
  // $wrapper = array( "data"=> array( "success"=> "true" ));
  $wrapper = array( "data"=> array( "success"=> $stuff ));
}
else{
  // echo "nope";
  $wrapper = array( "data"=> array( "success"=> "false" ));
}

ob_start();
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


/*
$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
setcookie('Pro-Image-Software-handle-code', 'data', time()+60*60*24*365, '/', $domain, false);
*/


?>