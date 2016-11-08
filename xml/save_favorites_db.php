<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
mysql_select_db($database_cp_connection, $cp_connection);

$code = clean_variable($_POST['Data_0'],true);
$handle = clean_variable($_POST['Data_1'],true);
$codehandle = $code.$handle;
$codehandle = preg_replace("/[^a-zA-Z0-9]/", "",$codehandle);
$data = $_POST['Data_2'];
$SOthers = $_POST['Data_3'];
$Others = clean_variable($_POST['LikeOthers'],true);
$Email = clean_variable($_POST['txtEmail'],true);
$LName = clean_variable($_POST['txtLastName'],true);
$FName = clean_variable($_POST['txtFirstName'],true);
$FComm = clean_variable($_POST['txtComments'],true);
/*
Data%5F2=%3Cimage%20folder%3D%22photographers%2Fflyinghorse%2Fdcw1%2F6%20yr%20olds%2F%22%20zoom%3D%2281T%5F2891%2EJPG%22%20small%3D%2281T%5F2891%2EJPG%22%20tiny%3D%2281T%5F2891%2EJPG%22%20id%3D%22F634968%22%20groupid%3D%220%22%3E81T%5F2891%2EJPG%3C%2Fimage%3E%3Cimage%20folder%3D%22photographers%2Fflyinghorse%2Fdcw1%2F6%20yr%20olds%2F%22%20zoom%3D%2281T%5F2890%2EJPG%22%20small%3D%2281T%5F2890%2EJPG%22%20tiny%3D%2281T%5F2890%2EJPG%22%20id%3D%22F634967%22%20groupid%3D%220%22%3E81T%5F2890%2EJPG%3C%2Fimage%3E%3Cimage%20folder%3D%22photographers%2Fflyinghorse%2Fdcw1%2F6%20yr%20olds%2F%22%20zoom%3D%2281T%5F2889%2EJPG%22%20small%3D%2281T%5F2889%2EJPG%22%20tiny%3D%2281T%5F2889%2EJPG%22%20id%3D%22F634966%22%20groupid%3D%220%22%3E81T%5F2889%2EJPG%3C%2Fimage%3E%3Cimage%20folder%3D%22photographers%2Fflyinghorse%2Fdcw1%2F6%20yr%20olds%2F%22%20zoom%3D%2281T%5F2888%2EJPG%22%20small%3D%2281T%5F2888%2EJPG%22%20tiny%3D%2281T%5F2888%2EJPG%22%20id%3D%22F634964%22%20groupid%3D%220%22%3E81T%5F2888%2EJPG%3C%2Fimage%3E&Data%5F1=flyinghorse&Data%5F0=dcw1&LikeOthers=Yes&txtEmail=chad%2Eserpan%40aevium%2Ecom&txtLastName=Serpan&txtFirstName=Chad
*/
$XML = clean_variable($data,"Store");
switch($Others){
	case "Yes":
		$YesNo = 'y';
		break;
	default:
		$YesNo = 'n';
		break;
}
$query_get_info = "SELECT `fav_id` FROM `photo_cust_favories` WHERE `fav_email` = '$Email' AND `fav_code` = '$codehandle' LIMIT 0,1";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$total_get_info = mysql_num_rows($get_info);
if($total_get_info > 0) {
	$row_get_info = mysql_fetch_assoc($get_info);
	
	$Id = $row_get_info['fav_id'];
	if($SOthers == "true"){
		$upd = "UPDATE `photo_cust_favories` SET `fav_fname` = '$FName', `fav_lname` = '$LName', `fav_email` = '$Email', `fav_code` = '$codehandle', `fav_xml` = '$XML', `fav_others` = '$YesNo' WHERE `fav_id` = '$Id'";
	} else {
		$upd = "UPDATE `photo_cust_favories` SET `fav_fname` = '$FName', `fav_lname` = '$LName', `fav_email` = '$Email', `fav_code` = '$codehandle', `fav_xml` = '$XML' WHERE `fav_id` = '$Id'";
	}
	$upd_info = mysql_query($upd, $cp_connection) or die(mysql_error());
} else {
	$add = " INSERT INTO `photo_cust_favories` (`fav_fname`,`fav_lname`,`fav_email`,`fav_code`,`fav_xml`,`fav_others`) VALUES ('$FName','$LName','$Email','$codehandle','$XML','$YesNo')";
	$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
	
	$query_get_info = "SELECT `fav_id` FROM `photo_cust_favories` WHERE `fav_email` = '$Email' AND `fav_code` = '$codehandle' LIMIT 0,1";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$row_get_info = mysql_fetch_assoc($get_info);
	
	$Id = $row_get_info['fav_id'];
}
if(strlen($FComm) > 0){
	$add = " INSERT INTO `photo_cust_favories_message` (`fav_id`,`fav_message`,`fav_date`) VALUES ('$Id','$FComm',NOW())";
	$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
}

$query_get_info = "SELECT `cust_email` FROM `cust_customers` WHERE `cust_handle` = '$handle' LIMIT 0,1";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$total_get_info = mysql_num_rows($get_info);
if($total_get_info > 0){
	require_once $r_path.'scripts/fnct_phpmailer.php';
	if (strtoupper(substr(PHP_OS,0,3)=='WIN')) { 
		$eol="\r\n"; 
	} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) { 
		$eol="\r"; 
	} else { 
		$eol="\n"; 
	}

	$row_get_info = mysql_fetch_assoc($get_info);
	$PhotoEmail = $row_get_info['cust_email'];
	//$PhotoEmail = "development@proimagesoftware.com";
		if(strlen(trim($FComm)) > 0){
		$text = "<p>".$Email." has written on your wall for event code ".$code."</p>";
		$text .= "<p>Message:<br/>".$FComm."</p>";
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
		$mail->Send();
		$Emailsent = true;
	}
}

setcookie('PhotoExpress_Guestbook_'.$codehandle,$Email,(time()+60*60*24*30),'/');
/*
$cookieName = 'PhotoExpress_'.$codehandle;
$data = base64_encode(urlencode($data));
setcookie($cookieName,$data,(time()+60*60*24*30));
*/

echo '&Msg=Your%20Favorites%20have%20been%20saved&CookieSaved='.$cookieName;
?>