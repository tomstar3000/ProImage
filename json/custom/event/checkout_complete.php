<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
require_once($r_path.'json/custom/include/headers.php');

define ("Allow Scripts", true);

$data = $GLOBALS['HTTP_RAW_POST_DATA'];

include $r_path.'scripts/security.php';

require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'json/custom/event/util/functions.php');

$json = array();

/*if( isAuthenticated() ){
  $dataObj = getEventData();

  if (is_object($dataObj) && (count(get_object_vars($dataObj)) > 0)){
    $json['search-matched'] = "true";
  }
  else{
    $json['search-matched'] = "false";
  }
}
else{
  $json['search-matched'] = "false";
}*/
$json = isAuthenticated();

write_json( $json );

?>