<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";

define ("Allow Scripts", true);
$data = $GLOBALS['HTTP_RAW_POST_DATA'];

require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_xml_parser.php');

$parsed = json_decode( $data );

$code = clean_variable($parsed->event->{'-number'}, true);
$photo = clean_variable($parsed->event->{'-photographer'}, true);

mysql_select_db($database_cp_connection, $cp_connection);
$query_info = "SELECT `photo_cust_favories`.*, `photo_cust_favories_message`.`fav_message`
  FROM `photo_cust_favories`
  LEFT JOIN `photo_cust_favories_message`
    ON (`photo_cust_favories_message`.`fav_id` = `photo_cust_favories`.`fav_id`
      OR `photo_cust_favories_message`.`fav_id` IS NULL)
  WHERE `fav_code` = '".$code.$photo."'
    AND `fav_occurance` = '2'
  ORDER BY `photo_cust_favories_message`.`fav_date` DESC";
$info = mysql_query($query_info, $cp_connection) or die(mysql_error());

$fav_array = array();
while($row_info = mysql_fetch_assoc($info)){
  $object = new stdClass();
  $object->{'-id'} = $row_info['fav_id'];
  $object->{'-email'} = $row_info['fav_email'];
  $object->{'-date'} = $row_info['fav_date'];
  $object->{'-others'} = $row_info['fav_others'];
  $object->{'-favs'} = $row_info['fav_xml'];
  $object->{'#text'} = (isset($row_info['fav_message']) ? $row_info['fav_message'] : '');
  $fav_array[] = $object;
}

$query_info = "SELECT `photo_cust_favories`.*, `photo_cust_favories_message`.`fav_message`
  FROM `photo_cust_favories`
  LEFT JOIN `photo_cust_favories_message`
    ON (`photo_cust_favories_message`.`fav_id` = `photo_cust_favories`.`fav_id`
      OR `photo_cust_favories_message`.`fav_id` IS NULL)
  WHERE `fav_code` = '".$code.$photo."'
    AND `fav_occurance` = '1'
  ORDER BY `photo_cust_favories_message`.`fav_date` DESC";
$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
while($row_info = mysql_fetch_assoc($info)){
  $TMPdata = '<?xml version="1.0" encoding="iso-8859-1"?><temp id="run">'.$row_info['fav_xml'].'</temp>';
  $TMPparser = new XMLParser($TMPdata, 'raw', 1);
  $TMPtree = $TMPparser->getTree();
  $TMPXML = array();
  if(isset($TMPtree['TEMP']['IMAGE'][0])){
    foreach($TMPtree['TEMP']['IMAGE'] as $r){
      $TMPXML[] = str_replace("F","",$r['ATTRIBUTES']['ID']);
    }
  } else {
    $TMPXML[] = str_replace("F","",$TMPtree['TEMP']['IMAGE']['ATTRIBUTES']['ID']);
  }
  
  $object = new stdClass();
  $object->{'-id'} = $row_info['fav_id'];
  $object->{'-email'} = $row_info['fav_email'];
  $object->{'-date'} = $row_info['fav_date'];
  $object->{'-others'} = $row_info['fav_others'];
  $object->{'-favs'} = implode(".",$TMPXML);
  $object->{'#text'} = (isset($row_info['fav_message']) ? $row_info['fav_message'] : '');
  $fav_array[] = $object;
}

ob_start();

$wrapper = array( "event"=> array( "favs"=> array( "fav"=> $fav_array )));
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
