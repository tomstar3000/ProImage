<?

require_once $r_path.'scripts/emogrifier.php';
require_once $r_path.'scripts/fnct_phpmailer.php';

$code = (isset($_POST['Event_Code'])) ? clean_variable($_POST['Event_Code'],true) : ((isset($_GET['code'])) ? clean_variable($_GET['code'],true) : "");
$code = preg_replace("/[^a-zA-Z0-9&\._\-]/", "",str_replace("&amp;","&",$code));

if(isset($_POST['Event_Code']) || isset($_GET['code'])){
	$getCode = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getCode->mysql("SELECT `event_id`, `event_num`, `event_name`, `event_date`, `event_name`, `event_desc`, `image_tiny`, `image_id`, `image_folder`, `image_rotate`, `event_short`, `cust_cname`, `cust_fname`, `cust_lname`, `cust_email`
									FROM `photo_event`
									LEFT OUTER JOIN `photo_event_images`
										ON `photo_event_images`.`image_id` = `photo_event`.`event_image`
									INNER JOIN `cust_customers` 
										ON `cust_customers`.`cust_id` = `photo_event`.`cust_id`
									WHERE `event_num` = '$code' 
										AND `cust_handle` = '$handle' 
										AND `event_use` = 'y' LIMIT 0,1;");
	
	if($getCode->TotalRows() != 0){ $getCode = $getCode->Rows();
		$Event_id = $getCode[0]['event_id'];
		$code = $getCode[0]['event_num'];
		session_start();
		if(isset($_SESSION['cart'])){
			if(isset($_SESSION['cart']))unset($_SESSION['cart']);
			if(isset($cart))unset($cart); 
		}
		$codehandle = $code.$handle;
		$codehandle = preg_replace("/[^a-zA-Z0-9]/", "",$codehandle);
		
		if(!isset($_COOKIE['PhotoExpress_Guestbook_'.$codehandle]) && !isset($_POST['Email'])){
			$Email = (strlen($getInfo[0]['cust_email'])==0)?$getInfo[0]['cust_femail']:$getInfo[0]['cust_email'];
			$getFavs = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$getFavs->mysql("SELECT `fav_xml` FROM `photo_cust_favories` WHERE `fav_code` = '".$codehandle."' AND `fav_email` = '".$Email."' AND `fav_occurance` = '2' ORDER BY `fav_date` DESC;");
			
			$getFavs = $getFavs->Rows();
			$HasFavs = false;
			if(strlen(trim($getFavs[0]['fav_xml'])) > 0){
				$getFavs = explode(".",$getFavs[0]['fav_xml']);
				if(count($getFavs)>0){
					$HasFavs = true;
				}
			}
			require_once('guestbook.php');
			
			die();
		} else if(isset($_COOKIE['PhotoExpress_Guestbook_'.$codehandle])){
        /*$GoTo = "/photo_viewer.php?Photographer=".$handle."&code=".rawurlencode($code)."&email=".$_COOKIE['PhotoExpress_Guestbook_'.$codehandle];
        header(sprintf("Location: %s", $GoTo));*/

      if ( isset( $_GET['email'] ) && $_COOKIE['PhotoExpress_Guestbook_'.$codehandle] == $_GET['email'] ){
  			$GoTo = "/photo_viewer.php?Photographer=".$handle."&code=".rawurlencode($code)."&email=".$_COOKIE['PhotoExpress_Guestbook_'.$codehandle];
  			header(sprintf("Location: %s", $GoTo));
      }
      else{
        if( isset( $_POST['Email'] )){
          $Email = clean_variable($_POST['Email'],true);
          setcookie('PhotoExpress_Guestbook_'.$codehandle,$Email,(time()+60*60*24*30),'/');

          $_SESSION['promo'] = (isset($_POST['Promotion'])) ? clean_variable($_POST['Promotion'],true) : 'n';
          $GoTo = "/photo_viewer.php?Photographer=".$handle."&code=".rawurlencode($code)."&email=".$Email;
          header(sprintf("Location: %s", $GoTo));
        }
        else{
          $Email = (isset($_GET['email'])) ? clean_variable($_GET['email'],true) : $_COOKIE['PhotoExpress_Guestbook_'.$codehandle];
          $getFavs = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
          $getFavs->mysql("SELECT `fav_xml` FROM `photo_cust_favories` WHERE `fav_code` = '".$codehandle."' AND `fav_email` = '".$Email."' AND `fav_occurance` = '2' ORDER BY `fav_date` DESC;");
          
          $getFavs = $getFavs->Rows();
          $HasFavs = false;
          if(strlen(trim($getFavs[0]['fav_xml'])) > 0){
            $getFavs = explode(".",$getFavs[0]['fav_xml']);
            if(count($getFavs)>0){
              $HasFavs = true;
            }
          }
          require_once('guestbook.php');
          die();
        }
      }
		} else {
			$Email = clean_variable($_POST['Email'],true);
			$Name = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
			$Promo = (isset($_POST['Promotion'])) ? clean_variable($_POST['Promotion'],true) : 'n';
			
			$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$getInfo->mysql("SELECT `email` FROM `photo_quest_book` WHERE `email` = '$Email' AND `event_id` = '$Event_id' LIMIT 0,1;");
			if($getInfo -> TotalRows() == 0){
				include $r_path.'includes/_EventMarketingGuestbook.php';
			}
			
			setcookie('PhotoExpress_Guestbook_'.$codehandle,$Email,(time()+60*60*24*30),'/');
			
      $_SESSION['promo'] = (isset($_POST['Promotion'])) ? clean_variable($_POST['Promotion'],true) : 'n';
			$GoTo = "/photo_viewer.php?Photographer=".$handle."&code=".rawurlencode($code)."&email=".$Email;
			header(sprintf("Location: %s", $GoTo));
		}
	}
} else {
	$total_check_code = 0;
}
?>