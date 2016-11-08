<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AuthorizedController extends ViewController{
  function __construct(){
    parent::__construct();
    $this->checkIfAuthorized();
  }

  protected function checkIfAuthorized(){
    /*$data = explode('/', uri_string());

    // check if the user has logged in for specific event
    if( !$this->session->userdata('verified') || $this->session->userdata('handle') != $data[0] || $this->session->userdata('code') != $data[1]){

      // redirect user to the welcome page
      $url = site_url().'/'.$data[0].'/'.$data[1];
      redirect($url);
    }*/
  }
}
?>