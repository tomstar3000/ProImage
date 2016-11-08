<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
require_once($r_path.'json/custom/include/headers.php');

define ("Allow Scripts", true);

$data = $GLOBALS['HTTP_RAW_POST_DATA'];

include $r_path.'scripts/security.php';
require_once($r_path.'json/vendor/jwt_helper.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'json/custom/event/util/queries.php');

function isAuthenticated(){
  $isTrue = false;
  $auth = $_SERVER["HTTP_X_AUTH_TOKEN"];

  if( $auth ){

    $token = JWT::decode($auth, 'secret_server_key');

    $email = $token->email;
    $eventID = $token->eventID;
    $expires = $token->expires;

    // echo $email;
    // echo $eventID;
    // echo $expires;

    // if payload contains proper key value pairs
    if( !empty($email) && !empty($eventID) && !empty($expires)){
      
      // if the token has not expired
      if( time() < $expires ){
        // echo "token valid";
        $isTrue = true;
      }
    }
  }

  return $isTrue;
}

function isValidEvent( $obj ){
  return is_object($obj) && (count(get_object_vars($obj)) > 0);
}

function getEventData(){
  $data = $GLOBALS['HTTP_RAW_POST_DATA'];
  $parsed = json_decode( $data );

  $obj = new stdClass();
  $eventNumber;
  $photographer;

  if(isset($parsed->event->{'-number'})){
    $eventNumber = clean_variable($parsed->event->{'-number'}, true);
    $eventNumber = str_replace("&amp;", "&", $eventNumber);
  }

  if(isset($parsed->event->{'-photographer'})){
    $photographer = clean_variable($parsed->event->{'-photographer'}, true);
  }

  if( !empty( $eventNumber ) && !empty( $photographer )){
    $event_info = getEventInfoViaHandleAndCode( $photographer, $eventNumber );

    if( !empty( $event_info )){
      $obj->eventNumber = $eventNumber;
      $obj->photographer = $photographer;
      $obj->eventID = $event_info['event_id'];
      $obj->photographerEmail = $event_info['cust_email'];
    }
  }

  return $obj;
}

function write_json( $json ){
  ob_start();
  echo str_replace('\\/', '/', json_encode( $json ));

  $rawData = ob_get_contents();
  ob_end_clean();

  $TMP = tempnam("/tmp", "TEMP"); // Create Temp File
  $HND = fopen($TMP, "w"); // Open Temp File for writing
  fwrite($HND, $rawData); // Write RawFile Data to Temp File
  fclose($HND); // Close File Handle

  header("Pragma: no-cache");
  header('Content-Type: application/json');
  header('Content-Length: '. filesize($TMP));

  $HND = fopen($TMP, "r");
  while (!feof($HND)) echo fread($HND, 8192);
  fclose($HND);

  unlink($TMP);
  exit(0);
}

?>