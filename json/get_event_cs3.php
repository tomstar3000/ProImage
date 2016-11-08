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
$code = $event_number.$photographer;
$code = preg_replace("/[^a-zA-Z0-9]/", "", $code);
$cookieName = 'PhotoExpress_'.$code;
$group_id = 0;
$perpage = 100;

mysql_select_db($database_cp_connection, $cp_connection);

$query_check = "SELECT `event_id` FROM `photo_event` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` WHERE `event_num` = '$event_number' AND `cust_handle` = '$photographer' AND `event_use` = 'y' ORDER BY `event_id`";
$check = mysql_query($query_check, $cp_connection) or die(mysql_error());
$total_check = mysql_num_rows($check);

$query_favs = "SELECT `fav_xml` FROM `photo_cust_favories` WHERE `fav_code` = '".$event_number.$photographer."' AND `fav_email` = '$Email' AND `fav_occurance` = '2' ORDER BY `fav_id` DESC LIMIT 0,1";
$favs = mysql_query($query_favs, $cp_connection) or die(mysql_error());
$favsArry = mysql_fetch_assoc($favs);
$favsArry = explode(".",$favsArry['fav_xml']);

if($total_check==0){
  $object = new stdClass();
  $object->{'-photographer'} = $photographer;
  $object->{'-number'} = $event_number;
  $wrapper = array( "success" => array( "action" => "failed", "info" => $object ));
  die(json_encode($wrapper));
}

$event_number = array();
$event_number2 = array();
while($row_check = mysql_fetch_assoc($check)){ 
  array_push($event_number, "`event_id` ='".$row_check['event_id']."'");
  array_push($event_number2, "`photo_event`.`event_id` ='".$row_check['event_id']."'"); 
}

$event_number = implode(" OR ",$event_number);
$event_number2 = implode(" OR ",$event_number2);
$query_get_info = "SELECT `event_name`,`event_copyright`,`event_opacity`,`event_frequency`,`event_price_id`,`event_short`
  FROM `photo_event` 
  WHERE $event_number
  ORDER BY `event_name`;";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

function buildGroup($prntId, $n){
  global $group_array;

  if($n >= 4) return;
  global $cp_connection, $event_number;
  $query_get_info = "SELECT * FROM `photo_event_group`
  WHERE ($event_number)
    AND `parnt_group_id` = '$prntId'
    AND `group_use` = 'y'
  ORDER BY `group_name` ASC;";
  $get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
  $total_get_info = mysql_num_rows($get_info);
  if($total_get_info == 0) return;
  while($row_get_info = mysql_fetch_assoc($get_info)){
    $object = new stdClass();
    $object->{'-id'} = rawurlencode($row_get_info['group_id']);
    $object->{'-name'} = rawurlencode($row_get_info['group_name']);

    $group_array[] = $object;

    buildGroup($row_get_info['group_id'], $n+1);
  }
}
function buildImages(){
  global $cp_connection, $event_number2, $cookieName, $Email, $code, $perpage, $favsArry, $image_array;
  $query_get_info = "SELECT `photo_event_group`.`group_id`, `photo_event_images`.* 
    FROM `photo_event` 
    INNER JOIN `photo_event_group` 
      ON `photo_event_group`.`event_id` = `photo_event`.`event_id` 
    INNER JOIN `photo_event_images` 
      ON `photo_event_images`.`group_id` = `photo_event_group`.`group_id` 
    WHERE ($event_number2) 
      AND `group_use` = 'y' 
      AND `image_active` = 'y' 
    ORDER BY `group_name` ASC, `photo_event`.`event_id` ASC, `image_name` ASC, `image_large` ASC";
  $get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
  $total_get_info = mysql_num_rows($get_info);
  if($total_get_info == 0) return;
  
  $LGroup = NULL;
  while($row_get_info = mysql_fetch_assoc($get_info)){
    $name = ($row_get_info['image_name'] == "") ? $row_get_info['image_large'] : $row_get_info['image_name'];
    
    $data1 = array("id" => $row_get_info['image_id'], "width" => 195, "height" => 195);
    $data2 = array("id" => $row_get_info['image_id'], "width" => 630, "height" => 630);
    $data3 = array("id" => $row_get_info['image_id'], "width" => 960, "height" => 640);
      
    $folder = substr($row_get_info['image_folder'],0,-11);
    if($LGroup == NULL || $LGroup != $row_get_info['group_id']){ 
      $c = 1; $p = 1; $LGroup = $row_get_info['group_id'];
    }

    $object = new stdClass();
    $object->{'-id'} = $row_get_info['image_id'];
    $object->{'-group_id'} = $row_get_info['group_id'];
    $object->{'-tiny'} = "?data=".base64_encode(encrypt_data(serialize($data1)));
    $object->{'-small'} = "?data=".base64_encode(encrypt_data(serialize($data2)));
    $object->{'-zoom'} = "?data=".base64_encode(encrypt_data(serialize($data3)));
    $object->{'-folder'} = "images/image.php";
    $object->{'-page'} = $p;
    $object->{'-fav'} = ((in_array($row_get_info['image_id'],$favsArry))?'y':'n');
    $object->{"#text"} = rawurlencode($name);
    
    $image_array[] = $object;

    $c++;
    if($c > $perpage){ $p++; $c = 1; }
  }
}

$group_array = array();
buildGroup(0, 1);
$object = new stdClass();
$object->{'-id'} = '-1';
$object->{'-name'} = 'Favorites';

$group_array[] = $object;

$image_array = array();
buildImages();

$group_wrapper = array( "event"=> array( "-name"=> $row_get_info['event_name'], "-price_id"=> $row_get_info['event_price_id'], "-copy"=> $row_get_info['event_copyright'], "-opacity"=> $row_get_info['event_opacity'], "-freq"=> $row_get_info['event_frequency'], "groups"=> array( "group"=> $group_array), "images"=> array( "image"=> $image_array )));

ob_start();

echo str_replace('\\/', '/', json_encode( $group_wrapper));

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

mysql_free_result($get_info);
exit(0);
?>
