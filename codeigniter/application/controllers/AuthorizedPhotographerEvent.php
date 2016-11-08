<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AuthorizedPhotographerEvent extends AuthorizedController{
  function __construct(){
    parent::__construct();
  }

  public function images( $handle, $code ){
    /*$this->load->model('ImagesModel');

    $groupsInputObj = new stdClass();
    $groupsInputObj->handle = $handle;
    $groupsInputObj->code = $code;
    
    $groups = $this->ImagesModel->getGroups( $groupsInputObj );
    $data['handle'] = $handle;
    $data['code'] = $code;

    $this->load->view('event/images', $data);*/

    $this->data[ 'handle' ] = $handle;
    $this->data[ 'code' ] = $code;
    $this->data[ 'page' ] = 'photographer images';
    // $this->data[ 'the_view_title' ] = $handle.":str_replace("&amp;", "&", $code);";
    $this->data[ 'the_view_title' ] = $handle.":".str_replace("&amp;", "&", $code).":images";

    $this->setFooter( $handle );

    $this->render( 'photographer-event/images', 'cart' );
    
  }

  public function checkoutComplete( $handle, $code ){
    /*$data['handle'] = $handle;
    $data['code'] = $code;

    $this->load->view('event/checkout-complete', $data); */
    $this->data[ 'handle' ] = $handle;
    $this->data[ 'code' ] = $code;
    $this->data[ 'page' ] = 'photographer images';
    // $this->data[ 'the_view_title' ] = $handle.":str_replace("&amp;", "&", $code);";
    $this->data[ 'the_view_title' ] = $handle.":".str_replace("&amp;", "&", $code).":images";

    $this->setFooter( $handle );

    $this->render( 'photographer-event/checkout-complete' );

  }

  public function logout( $handle, $code ){
    $this->session->sess_destroy();

    // redirect user to the welcome page
    $url = site_url().'/'.$handle.'/'.$code;
    redirect( $url );
  }
}
?>