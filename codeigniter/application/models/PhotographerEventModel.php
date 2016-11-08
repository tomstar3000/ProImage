<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class PhotographerEventModel extends CI_Model{    

    public function isValidPhotographerEvent( $handle, $code ){
      $isTrue = false;

      $code = str_replace("&amp;", "&", $code);

      /*$query = $this->db->query("SELECT `photo_event`.`event_id`
                                 FROM `photo_event`, `cust_customers` 
                                 WHERE `photo_event`.`cust_id` = `cust_customers`.`cust_id` 
                                 AND `cust_customers`.`cust_handle` = '$handle' 
                                 AND `photo_event`.`event_num` = '$code' 
                                 AND `photo_event`.`event_use` = 'y';");*/
      $eventInfo = $this->getEventInfoViaHandleAndCode( $handle, $code );                                 

      if( $eventInfo->num_rows == 1 ){
        $isTrue = true;
      }

      return $isTrue;
    }

    public function getEventInfoViaHandleAndCode( $handle, $code ){
      /*$query = $this->db->query("SELECT `photo_event`.`event_id`, `cust_customers`.`cust_email`
                                 FROM `photo_event`, `cust_customers` 
                                 WHERE `photo_event`.`cust_id` = `cust_customers`.`cust_id` 
                                 AND `cust_customers`.`cust_handle` = '$handle' 
                                 AND `photo_event`.`event_num` = '$code' 
                                 AND `photo_event`.`event_use` = 'y';");*/
      $query = $this->db->query("SELECT `photo_event`.`event_id`
                                 FROM `photo_event`, `cust_customers` 
                                 WHERE `photo_event`.`cust_id` = `cust_customers`.`cust_id` 
                                 AND `cust_customers`.`cust_handle` = '$handle' 
                                 AND `photo_event`.`event_num` = '$code' 
                                 AND `photo_event`.`event_use` = 'y';");
      
      return $query;
    }
  }
?>