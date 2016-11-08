<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";

require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/cart/encrypt.php');

function getEventInfoViaHandleAndCode( $handle, $code ){
  global $database_cp_connection, $cp_connection;

  $query = "SELECT `photo_event`.`event_id`, `cust_customers`.`cust_email`
            FROM `photo_event`, `cust_customers` 
            WHERE `photo_event`.`cust_id` = `cust_customers`.`cust_id` 
            AND `cust_customers`.`cust_handle` = '$handle' 
            AND `photo_event`.`event_num` = '$code' 
            AND `photo_event`.`event_use` = 'y';";
  
  mysql_select_db($database_cp_connection, $cp_connection);
  $row = mysql_query($query, $cp_connection) or die(mysql_error());

  return mysql_fetch_assoc( $row );
}

function logUserAccess( $obj ){
  global $cp_connection;

  $login_query = "UPDATE `photo_quest_book` SET `last_login` = NOW() WHERE `email` = '$obj->email' AND `event_id` = '$obj->eventID';";
  mysql_query($login_query, $cp_connection) or die(mysql_error());
}

function logUserVisit( $obj ){
  global $cp_connection;

  $email_query = "SELECT `email` FROM `photo_quest_book` WHERE `email` = '$obj->email' AND `event_id` = '$obj->eventID' LIMIT 0,1;";
  mysql_select_db($database_cp_connection, $cp_connection);
  $result = mysql_query($email_query, $cp_connection) or die(mysql_error());
  $numResults = mysql_num_rows( $result );

  if( $numResults > 0 ){
    // echo "yay";
    $login_query = "UPDATE `photo_quest_book` SET `visits` = `visits` + 1, `last_login` = NOW(), `promotion` = '$obj->promo' WHERE `email` = '$obj->email' AND `event_id` = '$obj->eventID';";
  }
  else{
    // echo "nay";
    $login_query = "INSERT INTO `photo_quest_book` (`event_id`,`email`,`visits`, `first_login`, `last_login`, `promotion`) VALUES ('$obj->eventID','$obj->email','1', NOW(), NOW(), '$obj->promo' );";
  }

  mysql_query($login_query, $cp_connection) or die(mysql_error());
}

function getEventSlideshowImages( $obj ){
  global $database_cp_connection, $cp_connection, $gateways_cp_connection;

  $image = array();

  $code = $obj->eventNumber.$obj->photographer;
  $code = preg_replace("/[^a-zA-Z0-9]/", "", $code);

  $getCvr = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
  $getCvr->mysql("SELECT `image_id`
                  FROM `photo_event`
                  INNER JOIN `photo_event_images`
                    ON `photo_event_images`.`image_id` = `photo_event`.`event_image`
                  WHERE `photo_event`.`event_id` = '$obj->eventID';");

  $Cover = false;
  
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
      AND `fav_email` = '".$obj->photographerEmail."'
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
          $object->{"-file"} = "images/image.php?data=" .base64_encode(encrypt_data(serialize($data))). "&t=" .time();
          $image['image'][] = $object;
          unset($data,$object);
        }
      }
    }
  }

  return $image;
}
  
?>