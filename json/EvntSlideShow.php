<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);

$data = $GLOBALS['HTTP_RAW_POST_DATA'];

include $r_path.'scripts/security.php';
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/cart/encrypt.php');

$parsed = json_decode( $data );

$event_number = clean_variable($parsed->event->{'-number'}, true);
$event_number = str_replace("&amp;", "&", $event_number);
$photographer = clean_variable($parsed->event->{'-photographer'}, true);
$Email = clean_variable($parsed->event->{'-email'}, true);
$EvntID = clean_variable($parsed->event->{'-id'}, true);
$code = $event_number.$photographer;
$code = preg_replace("/[^a-zA-Z0-9]/", "", $code);

$getCvr = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getCvr->mysql("SELECT `image_id`
                FROM `photo_event`
                INNER JOIN `photo_event_images`
                  ON `photo_event_images`.`image_id` = `photo_event`.`event_image`
                WHERE `photo_event`.`event_id` = '$EvntID';");

$Cover = false;
$image = array();
if($getCvr->TotalRows() > 0){
  $Cover = true;
  $getCvr = $getCvr->Rows();
  $data = array("id" =>  $getCvr[0]['image_id'], "width" => 500, "height" => 330,"Master"=>true);
  $object = new stdClass();
  $object->{"-file"} = "images/image.php?data=" .base64_encode(encrypt_data(serialize($data))). "&t=" .time(). "&id=" .$data['id'];
  $image['image'][] = $object;
  unset($data,$object);
}

$getFavs = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getFavs->mysql("SELECT `fav_xml`
  FROM `photo_cust_favories`
  WHERE `fav_code` = '".$code."'
    AND `fav_email` = '".$Email."'
    AND `fav_occurance` = '2'
  ORDER BY `fav_date` DESC;");
$getFavs = $getFavs->Rows();
if(strlen(trim($getFavs[0]['fav_xml'])) > 0){
  $getFavs = explode(".",$getFavs[0]['fav_xml']);
  foreach ($getFavs as $r){
    if($r != ''){
      $getFav = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
      $getFav->mysql("SELECT `image_id` FROM `photo_event_images` WHERE `image_id` = '".$r."';");
      $getFav = $getFav->Rows();
      if(($Cover==true && $getFav[0]['image_id'] != $getCvr[0]['image_id']) || $Cover===false){
        $data = array("id" => $getFav[0]['image_id'], "width" => 500, "height" => 330, "Master"=>true);
        $object = new stdClass();
        // $object->{"-file"} = "images/image.php?data=" .base64_encode(encrypt_data(serialize($data))). "&t=" .time(). "&id=" .$r;
        $object->{"-file"} = "images/image.php?data=" .base64_encode(encrypt_data(serialize($data))). "&t=" .time();
        $image['image'][] = $object;
        unset($data,$object);
      }
    }
  }
}
ob_start();

$wrapper = array( "data"=> array( "images"=> $image ));
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