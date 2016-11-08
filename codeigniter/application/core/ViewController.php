<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ViewController extends CI_Controller{
  function __construct(){
    parent::__construct();
  }

  protected function render( $the_view = NULL, $template = 'default' ){
    /*$this->data[ 'the_view_content' ] = ( is_null( $the_view )) ? '' : $this->load->view( $the_view, $this->data, TRUE );
    $this->load->view( 'templates/'.$template, $this->data );*/
    if( is_null( $the_view ) ){
      $this->data[ 'the_view_content' ] = '';
    }
    else{
      $this->data[ 'the_view_content' ] = $this->load->view( $the_view, $this->data, TRUE );
    }
    
    $this->load->view( 'templates/'.$template, $this->data );
  }

  protected function setFooter( $handle ){
    $this->load->model( 'PhotographerModel' );
    $contactInfo = $this->PhotographerModel->getPhotographerContactInfo( $handle );

    //content values
    // $this->data[ 'description' ] = $contactInfo->cust_desc;

    //footer values
    $this->data[ 'company' ] = $contactInfo->cust_cname;
    $this->data[ 'company_email' ] = $contactInfo->cust_email;
    $this->data[ 'company_phone' ] = $contactInfo->cust_phone;
  }
}
?>