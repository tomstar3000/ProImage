<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
require_once($r_path.'json/custom/include/headers.php');

define ("Allow Scripts", true);

include $r_path.'scripts/security.php';
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'json/vendor/jwt_helper.php');
require_once($r_path.'json/custom/event/util/functions.php');

$dataObj = getEventData();
$encoded = false;

if( isValidEvent( $dataObj )){
  $data = $GLOBALS['HTTP_RAW_POST_DATA'];
  $parsed = json_decode( $data );

  if(isset($parsed->event->{'-email'})){
    $email = clean_variable($parsed->event->{'-email'}, true);

    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      // there isn't a database column for first name in the photo_quest_book table
      /*$first = (isset($parsed->event->{'-first'})) ? clean_variable($parsed->event->{'-first'}, true) : '';*/
      $promo = (isset($parsed->event->{'-promo'})) ? clean_variable($parsed->event->{'-promo'}, true) : 'y';
      $dataObj->promo = $promo;
      $dataObj->email = $email;

      $token = array();
      $token['email'] = $email;
      $token['eventID'] = $dataObj->eventID;
      $token['expires'] = strtotime( "+2 hours" );

      $encoded = JWT::encode($token, 'secret_server_key');
      logUserVisit( $dataObj );
    }
  }
}

$json = array( "event"=> array( "access"=> $encoded ));
write_json( $json );
?>