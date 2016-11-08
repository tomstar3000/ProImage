<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class PhotographerModel extends CI_Model{    

    public function isValidPhotographer( $handle ){
      $isTrue = false;

      $query = $this->db->query("SELECT `cust_id` 
                                 FROM `cust_customers` 
                                 WHERE `cust_photo` = 'y' 
                                 AND `cust_active` = 'y' 
                                 AND `cust_canceled` = 'n' 
                                 AND `cust_del` = 'n' 
                                 AND `cust_handle` = '$handle';");

      if( $query->num_rows == 1 ){
        $isTrue = true;
      }

      return $isTrue; 
    }

    public function getPhotographerContactInfo( $handle ){
      $query = $this->db->query("SELECT `cust_id`, `cust_fname`, `cust_mint`, `cust_lname`, `cust_suffix`, 
                                 `cust_cname`, `cust_desc`, `cust_desc_2`, `cust_add`, `cust_add_2`, 
                                 `cust_suite_apt`, `cust_city`, `cust_state`, `cust_zip`, `cust_country`, 
                                 `cust_phone`, `cust_cell`, `cust_fax`, `cust_work`, `cust_ext`, `cust_email`, 
                                 `cust_website`, `cust_thumb`, `cust_image`, `cust_fcname`, `cust_fwork`, 
                                 `cust_fext`, `cust_femail`, `cust_icon`,`cust_active`,`cust_paid` 
                                 FROM `cust_customers` 
                                 WHERE `cust_handle` = '$handle' 
                                 LIMIT 0,1;");
      return $query->row();      
    }
  }
?>