<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";

define ("Allow Scripts", true);
//$data = file_get_contents("php://input");
$data = $GLOBALS['HTTP_RAW_POST_DATA'];

require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');

$parsed = json_decode( $data );

$code = clean_variable($parsed->event->{'-number'}, true);
$photo = clean_variable($parsed->event->{'-photographer'}, true);
$Favs = clean_variable($parsed->event->{'-favs'}, true);
$Others = clean_variable($parsed->event->{'-others'}, true);
$Email = clean_variable($parsed->event->{'-email'}, true);
$LName = clean_variable($parsed->event->{'-lname'}, true);
$FName = clean_variable($parsed->event->{'-fname'}, true);
$FComm = clean_variable($parsed->event->{'-cmnts'}, true);

switch($Others){
	case "Yes": $YesNo = 'y'; break;
	default: $YesNo = 'n'; break;
}
$query_get_info = "SELECT `fav_id` FROM `photo_cust_favories` WHERE `fav_email` = '$Email' AND `fav_code` = '".$code.$photo."' AND `fav_occurance` = '2' LIMIT 0,1";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$total_get_info = mysql_num_rows($get_info);

// echo $query_get_info;

if($total_get_info > 0) {
	$row_get_info = mysql_fetch_assoc($get_info);
	
	$Id = $row_get_info['fav_id'];
	$upd = "UPDATE `photo_cust_favories` SET `fav_fname` = '$FName', `fav_lname` = '$LName', `fav_email` = '$Email', `fav_xml` = '$Favs', `fav_others` = '$YesNo' WHERE `fav_id` = '$Id'";
	$upd_info = mysql_query($upd, $cp_connection) or die(mysql_error());
} else {
	$add = " INSERT INTO `photo_cust_favories` (`fav_fname`,`fav_lname`,`fav_email`,`fav_code`,`fav_xml`,`fav_others`,`fav_occurance`) VALUES ('$FName','$LName','$Email','".$code.$photo."','$Favs','$YesNo','2')";
	$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
	
	$query_get_info = "SELECT `fav_id` FROM `photo_cust_favories` WHERE `fav_email` = '$Email' AND `fav_code` = '".$code.$photo."' AND `fav_occurance` = '2' LIMIT 0,1";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$row_get_info = mysql_fetch_assoc($get_info);
	
	$Id = $row_get_info['fav_id'];
}

$query_get_info = "SELECT `fav_message_id` FROM `photo_cust_favories_message` WHERE `fav_id` = '$Id' ORDER BY `fav_message_id` DESC LIMIT 0,1";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$total_get_info = mysql_num_rows($get_info);
$row_get_info = mysql_fetch_assoc($get_info);
if(strlen(trim($FComm)) > 0){
	if($total_get_info == 0 || md5($row_get_info['$row_get_info']) != md5($FComm)){
		$add = " INSERT INTO `photo_cust_favories_message` (`fav_id`,`fav_message`,`fav_date`) VALUES ('$Id','$FComm',NOW())";
		$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
		
		$query_get_info = "SELECT `cust_email` FROM `cust_customers` WHERE `cust_handle` = '$photo' LIMIT 0,1";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$total_get_info = mysql_num_rows($get_info);
		if($total_get_info > 0){
			require_once $r_path.'scripts/fnct_phpmailer.php';
			if (strtoupper(substr(PHP_OS,0,3))=='WIN') $eol="\r\n"; 
			elseif (strtoupper(substr(PHP_OS,0,3))=='MAC') $eol="\r"; 
			else $eol="\n";
		
			$row_get_info = mysql_fetch_assoc($get_info);
			$PhotoEmail = $row_get_info['cust_email'];
			//$PhotoEmail = "development@proimagesoftware.com";
			$text = "<p>".$Email." has written on your wall for event code ".$code."</p>";
			$text .= "<p>Message:<br/>".rawurldecode($FComm)."</p>";
			$text .= "<p>Sincerely Pro Image Software.</p>";
				
			ob_start();
			include($r_path.'template_email_2.php');
			$page = ob_get_contents();
			ob_end_clean();
			
			$mail = new PHPMailer();
			//$mail -> IsSMTP();
			$mail -> Host = "smtp.proimagesoftware.com";
			$mail -> IsHTML(true);
			$mail -> IsSendMail();
			$mail -> Sender = "info@proimagesoftware.com";
			$mail -> Hostname = "proimagesoftware.com";
			$mail -> From = $Email;
			$mail -> AddAddress($PhotoEmail);
			$mail -> FromName = $Email;
			$mail -> Subject = $code." Message Board";
			$mail -> Body = $page;
			$mail -> Send();
			$Emailsent = true;
		}
	}
}

require_once($r_path.'json/get_favorites_cs3.php');
?>