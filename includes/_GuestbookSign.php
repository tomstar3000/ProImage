<?
if(!function_exists("FindCSS")){
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
}

ob_start();
include($r_path.'Templates/Photographer/Guestbook.php');
$msg = ob_get_contents();
ob_end_clean();

$Photographer = ((strlen($getCode[0]['cust_cname'])>0)?$getCode[0]['cust_cname']:$getCode[0]['cust_fname'].' '.substr($getCode[0]['cust_lname'],0,1).".");
$PhotoEmail = $getCode[0]['cust_email'];
$link = "http://".$_SERVER['HTTP_HOST']."/portal.php?".base64_encode(urlencode(serialize(array($Event_id,$handle,$code,$Email,$Promo))));

$Time = time();
$IName = $getCode[0]['image_tiny'];
$Image = $r_path.substr($getCode[0]['image_folder'],0,-11)."Thumbnails/".$IName;

if($getCode[0]['event_image'] != ""){
	$IName = "Notification";
	$TempName = tempnam("/tmp", $IName);
	$THandle = fopen($TempName, "w");
	fwrite($THandle, base64_decode($getCode[0]['event_image']));
	fclose($THandle);

	$Image = $TempName;
} else if(!is_file($Image)){
	$Image = false;
	$IName = false;
}
if($Image !== false) {
	list($width, $height) = getimagesize($Image);
	if($width > $height){ $Perc = 242/$width; $height = round($height*$Perc); $width = 242; }
	else { $Perc = 242/$height; $width = round($width*$Perc); $height = 242; }
	$msg = str_replace('[EmbedImg]','<img class="img" src="cid:'.$Time.'" width="'.$width.'" height="'.$height.'" alt="'.$handle.'" vspace="0" hspace="0" alt="'.$title.'" style="border: 3px solid #c89441;"><br clear="all" />
	',$msg);
} else { $msg = str_replace('[EmbedImg]','',$msg); }

$msg = str_replace('[Title]',mb_convert_encoding($getCode[0]['event_name'], "UTF-8", 'HTML-ENTITIES'),$msg);
$msg = str_replace('[Text]',((strlen($Name)>0)?$Name.', ':'').'Thank you for coming to check out my event.  To view this event please follow this <a href="'.$link.'" title="'.$getCode[0]['event_name'].'">link</a>.',$msg);
$msg = str_replace('[Photographer]',$Photographer,$msg);
$msg = str_replace(array('[Link]','%5BLink%5D'),$link,$msg);

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

while(strpos("\r",$msg) !== false || strpos("\n",$msg) !== false || strpos("\r\n",$msg) !== false || strpos("\n\r",$msg) !== false ){
	$msg = trim(str_replace(array("\r","\n","\r\n","\n\r"),"",$msg));
}

$msg = preg_replace("/ +/", " ", $msg);
$msg = str_replace('href= "', 'href="', $msg);
$msg = str_replace('src="/','src="http://'.$_SERVER['HTTP_HOST'].'/',$msg);
$msg = str_replace('url(/','url(http://'.$_SERVER['HTTP_HOST'].'/',$msg);
$msg = clean_html_code($msg);

$mail = new PHPMailer();
$mail -> IsSendMail();
$mail -> Host = "smtp.proimagesoftware.com";
$mail -> IsHTML(true);
$mail -> Sender = "info@proimagesoftware.com";
$mail -> Hostname = "proimagesoftware.com";
$mail -> From = $PhotoEmail;
$mail -> FromName = $Photographer;
$mail -> ReplyTo = $PhotoEmail;
$mail -> AddAddress($Email,$Name);
$mail -> Subject = "View our event ".str_replace("&amp;","&",$getCode[0]['event_name']);
if($Image !== false) $mail -> AddEmbeddedImage($Image, $Time);
$mail -> Body = $msg;
$mail -> AltBody = trim(str_replace("Â","",trim(mb_convert_encoding(trim(strip_tags($msg)), 'UTF-8', 'HTML-ENTITIES'))));
$mail -> Send();

include $r_path.'guestbook_signed.php';
die();
?>