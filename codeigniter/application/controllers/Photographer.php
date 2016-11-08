<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Photographer extends ViewController{

  private function renderNotFound( $handle ){
    $this->data[ 'handle' ] = $handle;
    // $this->data[ 'page' ] = 'photographer not found';
    $this->data[ 'the_view_title' ] = "Photographer not found";
    $this->data[ 'company' ] = "Pro Image Software";
    $this->data[ 'company_email' ] = "info@proimagesoftware.com";
    $this->data[ 'company_phone' ] = "3034406008";
    $this->render( 'photographer/not-found' ); 
  }

  public function home( $handle ){
    $this->load->model( 'PhotographerModel' );
    $result = $this->PhotographerModel->isValidPhotographer( $handle );

    if( !$result ){
      $this->renderNotFound($handle);
    }
    else{
      $this->data[ 'handle' ] = $handle;
      // $this->data[ 'page' ] = 'home';
      $this->data[ 'the_view_title' ] = $handle.":home";

      $this->setFooter( $handle );

      if( $_SERVER['REQUEST_METHOD'] == 'GET' ){
        $this->render( 'photographer/home' );
      }
      else if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
        $code = $this->security->xss_clean($this->input->post('event-code'));
        $this->data[ 'code' ] = $code;
        redirect($handle.'/'.$code);
      }
    }
  }

  public function gallery( $handle ){
    $this->load->model( 'PhotographerModel' );
    $result = $this->PhotographerModel->isValidPhotographer( $handle );
    
    if( !$result ){
      $this->renderNotFound($handle);
    }
    else{
      $this->data[ 'handle' ] = $handle;
      // $this->data[ 'page' ] = 'gallery';
      $this->data[ 'the_view_title' ] = $handle.":gallery";

      $this->setFooter( $handle );

      $this->render( 'photographer/gallery' );
    }
  }

  public function contactUs( $handle ){
    $this->load->model( 'PhotographerModel' );
    $result = $this->PhotographerModel->isValidPhotographer( $handle );
    
    if( !$result ){
      $this->renderNotFound($handle);
    }
    else{
      $this->data[ 'handle' ] = $handle;
      // $this->data[ 'page' ] = 'contact us';
      $this->data[ 'the_view_title' ] = $handle.":contact us";
      
      $this->setFooter( $handle );
      
      $this->render( 'photographer/contact-us' );
    }
  }
}
?>