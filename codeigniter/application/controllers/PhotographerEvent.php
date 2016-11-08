<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class PhotographerEvent extends ViewController{

  private function renderNotFound( $handle ){
    $this->load->model( 'PhotographerModel' );
    $result = $this->PhotographerModel->isValidPhotographer( $handle );

    if( !$result ){
      $this->data[ 'handle' ] = $handle;
      // $this->data[ 'page' ] = 'photographer not found';
      $this->data[ 'the_view_title' ] = "Photographer not found";
      $this->data[ 'company' ] = "Pro Image Software";
      $this->data[ 'company_email' ] = "info@proimagesoftware.com";
      $this->data[ 'company_phone' ] = "3034406008";
      $this->render( 'photographer-event/not-found' ); 
    }
    else{
      $this->data[ 'handle' ] = $handle;
      $this->data[ 'the_view_title' ] = "Photographer not found";
      $this->setFooter( $handle );
      $this->render( 'photographer-event/not-found' ); 
    }
  }

  public function welcome( $handle, $code, $msg = null ){

    /*var_dump( $handle );
    var_dump( $code );
    var_dump( $msg );*/
    // load login model
    $this->load->model('PhotographerEventModel');

    // validate that we have a valid event via handle and code
    $result = $this->PhotographerEventModel->isValidPhotographerEvent( $handle, $code );

    if( !$result ){
      $this->renderNotFound( $handle );
    }
    else{
      if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {

        /*
        // add the message to the view, if there is one present
        if( $msg ){
          $data['msg'] = $msg;  
        }
        */

        $this->data[ 'handle' ] = $handle;
        $this->data[ 'code' ] = $code;
        // $this->data[ 'page' ] = 'photographer event welcome';
        // $this->data[ 'the_view_title' ] = $handle.":str_replace("&amp;", "&", $code);";
        $this->data[ 'the_view_title' ] = $handle.":".str_replace("&amp;", "&", $code).":welcome";

        $this->setFooter( $handle );

        $this->render( 'photographer-event/welcome' );
      }
      else if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

        // retrieve the email address from the post variables
        $email = $this->security->xss_clean($this->input->post('email'));

        // check if the email address submitted is valid
        if( filter_var( $email, FILTER_VALIDATE_EMAIL )){

          // retrieve the event info via handle and code
          $eventInfo = $this->PhotographerEventModel->getEventInfoViaHandleAndCode( $handle, $code );

          // retrieve the opt-in from the post variables
          $optIn = $this->security->xss_clean($this->input->post('opt-in'));

          // db is expecting optIn to be y or n
          $optIn = ( $optIn === 'y' ? $optIn : 'n');
          
          // create object to pass on to logUserVisit
          $object = new stdClass();
          $object->email = $email;
          $object->eventID = $eventInfo->row()->event_id;
          $object->optIn = $optIn;

          // log the user visit to the db
          // $this->PhotographerEventModel->logUserVisit( $object );
          
          // create array hashtable used by the session
          $data = array('handle' => $handle,
                        'code' => $code,
                        // 'eventID' => $eventInfo->row()->event_id,
                        // 'photographerEmail' => $eventInfo->row()->cust_email,
                        'email' => $email,
                        'verified' => true);

          // set session vars
          $this->session->set_userdata($data);

          // redirect user to the images page
          $url = site_url().'/'.$handle.'/'.$code.'/images';
          redirect($url);
        }
        else{
          // create array hashtable used by the view, including error
          $this->data['msg'] = "Enter your email address.";
          $this->data['handle'] = $handle;
          $this->data['code'] = $code;
          $this->data[ 'the_view_title' ] = $handle.":".str_replace("&amp;", "&", $code).":welcome";

          $this->setFooter( $handle );
          $this->render( 'photographer-event/welcome' );

        }
      }
    }
  }
}
?>