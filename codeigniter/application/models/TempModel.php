<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class TempModel extends CI_Model{
    function __construct(){
      // Call the Model constructor
      parent::__construct();
    }

    public function tempFunction(){
      // $query = $this->db->query("SELECT `cust_handle` FROM `cust_customers` WHERE `cust_id` = '26' || `cust_id` = '6930';");
      $query = $this->db->query("SELECT `cust_handle` FROM `cust_customers` WHERE `cust_id` = '26' || `cust_id` = '6930';");
      // return $query;
      return $query->result();
      // return $query->num_rows;
    }
  }
?>