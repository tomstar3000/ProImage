<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
require_once($r_path.'json/custom/include/headers.php');

define ("Allow Scripts", true);

include $r_path.'scripts/security.php';
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'json/custom/event/util/functions.php');
require_once($r_path.'json/custom/event/util/queries.php');

$json = array();

$dataObj = getEventData();

if( isValidEvent( $dataObj )){

  $json['search-matched'] = "true";

  $images = getEventSlideshowImages( $dataObj );

  if( !empty( $images['image'] )){
    $json['images'] = $images['image'];
  }
  else{
    $json['images'] = array();
  }
}
else{
  $json['search-matched'] = "false";
  $json['images'] = array();
}

write_json( $json );

?>