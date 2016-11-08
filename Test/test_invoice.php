<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
$r_path = ""; $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);

require_once($r_path.'Connections/cp_connection.php');
mysql_select_db($database_cp_connection,$cp_connection);

require_once $r_path.'scripts/fnct_phpmailer.php';
require_once($r_path.'scripts/emogrifier.php');
require_once($r_path.'scripts/cart/encrypt.php');

$encnum = "a0ced38c8090e9d3706d20d370abbbe4";

function FindCSS($CSSLink){
	global $CSS, $Template, $r_path;
	$path_parts = pathinfo($CSSLink);
	$path = $path_parts['basename'];
	$path_parts = pathinfo($Template);
	$path = $path_parts['dirname']."/".$path;
	$handle = fopen($r_path.$path, "r") or die("Failed Opening ".$r_path.$path);
	while (!feof($handle)) $CSS .= fread($handle, 8192);
	fclose($handle);
	return "";
}
function FindCSS2($StyleSheet){
	global $CSS;
	$CSS .= $StyleSheet;
	return "";
}
function cleanUpHTML($text) {
	$text = ereg_replace(" style=[^>]*","", $text);
	return ($text);
}

$query_get_email = "SELECT * FROM `cust_customers` INNER JOIN `orders_invoice` ON `orders_invoice`.`cust_id` = `cust_customers`.`cust_id` WHERE `invoice_enc` = '$encnum'";
$get_email = mysql_query($query_get_email, $cp_connection) or die(mysql_error());
$row_get_email = mysql_fetch_assoc($get_email);

$reciever = $row_get_email['cust_email'];
//$reciever = "pat@proimagesoftware.com";
$reciever = "development@proimagesoftware.com";
//echo $reciever;

// Invoice

ob_start();
require_once($r_path.'checkout/invoice.php');
$page = ob_get_contents();
ob_end_clean();

$mail = new PHPMailer();
$mail -> Host = "smtp.proimagesoftware.com";
$mail -> IsHTML(true);
$mail -> Sender = "info@proimagesoftware.com";
$mail -> From = "orders@proimagesoftware.com";
$mail -> FromName = "orders@proimagesoftware.com";
$mail -> AddAddress($reciever);
$mail -> Subject = "Invoice";
$mail -> Body = $page;
//$mail -> Send();

// Digital Download

ob_start();
require_once($r_path.'checkout/digital_download_2.php');
$page2 = ob_get_contents();
ob_end_clean();

$mail = new PHPMailer();
$mail -> Host = "smtp.proimagesoftware.com";
$mail -> IsHTML(true);
$mail -> Sender = "info@proimagesoftware.com";
$mail -> Hostname = "proimagesoftware.com";
$mail -> From = "orders@proimagesoftware.com";
$mail -> FromName = "orders@proimagesoftware.com";
$mail -> AddAddress($reciever);
$mail -> Subject = "Your Digital Download";
$mail -> Body = $page2;
var_dump($page2);
//$mail -> Send();

// Welcome Letter

$email_text = '<p>You can use your Control Panel to administer your events and personal webpage.</p>
<p>Please Log Onto '.$_SERVER['HTTP_HOST'].'/PhotoCP to manage your profile information</p>
<h1>Your Username Is: '.$row_get_email['cust_username'].'</h1>
<h1>Your Password Is: '.trim(decrypt_data($row_get_email['cust_uncode'])).'</h1>
<p>Your personal website can be found at:<br />
<a href="http://'.$_SERVER['HTTP_HOST']."/".$row_get_email['cust_handle'].'" target="_blank">http://'.$_SERVER['HTTP_HOST']."/".$row_get_email['cust_handle'].'</a></p>';
	
ob_start();
include($r_path.'Templates/Signup/index.php');
$msg = ob_get_contents();
ob_end_clean();

$msg = str_replace('[Title]',"Thank you for joining us at proimagesoftware.com",$msg);
$msg = str_replace('[Text]',$email_text,$msg);

$Pattern = array();
$Pattern[] = '/<link\s[^>]*href=(["\']??)([^"\'>]*?)\\1[^>]*rel="stylesheet"(.*)>/eiU';
$Pattern[] = '@<style[^>]*?>.*?</style>@esiU';

$CSS = "";
$msg = preg_replace($Pattern[0],"FindCSS('$2')",$msg);
$msg = preg_replace($Pattern[1],"FindCSS2('$0')",$msg);

$InlineHTML = new Emogrifier();
$InlineHTML -> setHTML($msg);
$InlineHTML -> setCSS($CSS);

$msg = removeSpecial($msg);

$msg = $InlineHTML -> emogrify();

//$config = array('indent' => TRUE,'wrap' => 200);
//$tidy = tidy_parse_string($msg, $config, 'UTF8');
//$tidy->cleanRepair();
//$msg = $tidy;
$msg = clean_html_code($msg);

while(strpos("\r",$msg) !== false || strpos("\n",$msg) !== false || strpos("\r\n",$msg) !== false || strpos("\n\r",$msg) !== false ){
	$msg = trim(str_replace(array("\r","\n","\r\n","\n\r"),"",$msg));
}

$msg = preg_replace("/ +/", " ", $msg);
$msg = str_replace('href= "', 'href="', $msg);
$msg = str_replace('src="/','src="http://'.$_SERVER['HTTP_HOST'].'/',$msg);
$msg = str_replace('url(/','url(http://'.$_SERVER['HTTP_HOST'].'/',$msg);

$mail = new PHPMailer();
$mail -> IsSendMail();
$mail -> Host = "smtp.proimagesoftware.com";
$mail -> Sender = "info@proimagesoftware.com";
$mail -> IsHTML(true);
$mail -> From = "info@proimagesoftware.com";
$mail -> FromName = "ProImage Software";
$mail -> AddAddress($reciever);
$mail -> Subject = "Thank You for Signing Up";
$mail -> Body = $msg;
//var_dump($msg);
//$mail->Send();

?>
