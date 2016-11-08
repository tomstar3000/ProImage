<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_phpmailer.php';
require_once $r_path.'scripts/fnct_image_resize.php';
require_once $r_path.'scripts/fnct_format_file_name.php';
require_once $r_path.'scripts/emogrifier.php';
require_once $r_path.'scripts/encrypt.php';
require_once $r_path.'../scripts/fnct_ImgeProcessor.php';

$EId = $path[2];
$Desc = (isset($_POST['Email_Text'])) ? clean_variable($_POST['Email_Text']) : '';

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

if(isset($_POST['Controller']) && $_POST['Controller'] == "Send"){	
	
	$eol = (strtoupper(substr(PHP_OS,0,3))=='WIN')?"\r\n":((strtoupper(substr(PHP_OS,0,3))=='MAC')?"\r":"\n"); 
	
	$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getInfo->mysql("SELECT `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_handle`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_website`, `cust_customers`.`cust_thumb`, `photo_quest_book`.*, `photo_event`.`event_name`, `photo_event`.`event_num`, `cust_customers`.`cust_email`, 
`cover_image`.`image_folder` AS `cover_folder`, 				`cover_image`.`image_tiny` AS `cover_tiny`,
`cover_image`.`image_rotate` AS `cover_rotate`,					`cover_image`.`image_color_space` AS `cover_color_space`,
`cover_image`.`image_id` AS `cover_id`,
`photo_event_images`.`image_folder`,										`photo_event_images`.`image_tiny`,
`photo_event_images`.`image_rotate`,										`photo_event_images`.`image_color_space`,
`photo_event_images`.`image_id` 
	FROM `photo_quest_book` 
	LEFT JOIN `photo_event` 
		ON `photo_event`.`event_id` = `photo_quest_book`.`event_id` 
		OR `photo_event`.`event_id` IS NULL
	LEFT JOIN `photo_event_images` AS `cover_image`
		ON `cover_image`.`image_id` = `photo_event`.`event_image`
		OR `cover_image`.`image_id` IS NULL
	LEFT JOIN `photo_event_group`
		ON (`photo_event_group`.`event_id` = `photo_event`.`event_id` AND `photo_event_group`.`group_use` = 'y')
		OR `photo_event_group`.`event_id` IS NULL
	LEFT JOIN `photo_event_images` 
		ON (`photo_event_images`.`group_id` = `photo_event_group`.`group_id` AND `photo_event_images`.`image_active` = 'y')
		OR `photo_event_images`.`group_id` IS NULL 
	LEFT JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` 
		OR `cust_customers`.`cust_id` IS NULL 
	WHERE `photo_quest_book`.`event_id` = '$EId' 
		AND `promotion` = 'y' 
	GROUP BY `photo_quest_book`.`email` ASC");
	$bcc = array();
	$getImg = $getInfo->Rows(); $getImg = $getImg[0];
	/* $BioImage = "Logo.jpg";
	if(is_file($r_path."../photographers/".$handle."/".$BioImage)){
		list($BioWidth, $BioHeight) = getimagesize($r_path."../photographers/".$handle."/".$BioImage);
		$BioImage = "photographers/".$handle."/".$BioImage;
		if($BioWidth > 700){
			$Ration = 700/$BioWidth;
			$BioWidth = 700;
			$BioHeight = $BioHeight*$Ration;
		}
	} else {
		$BioImage = false;
	} */
	
	$BioImage = false;
	if(is_numeric($getImg['cover_id'])){
		$file = $r_path."../".substr($getImg['cover_folder'],0,-11)."Large/".$getImg['cover_tiny'];
		$rotate = $getImg['cover_rotate'];
		$color = $getImg['cover_color_space'];
	} else {
		$file = $r_path."../".substr($getImg['image_folder'],0,-11)."Large/".$getImg['image_tiny'];
		$rotate = $getImg['image_rotate'];
		$color = $getImg['image_color_space'];
	}
	
	$Imager = new ImageProcessor();
	$Imager->SetMaxSize(67108864);
	$Imager->File($file);
	if($Imager->ERROR == false){
		$Imager->Resize(145,145);
		if(intval($rotate) > 0) $Imager->Rotate((intval($rotate)*-1));
		if($color == 'b')				$Imager->Gray();
		else if($color == 's')	$Imager->Sepia();
		$fileType = $Imager->Ext;

		$Image = tempnam("/tmp", "FOO");
		
		$Hndl = fopen($Image, "w");
		fwrite($Hndl, $Imager->OutputContents());
		fclose($Hndl);
	
	} else {
		$file = false;
		$Image = false;
	}
		
	foreach($getInfo->Rows() as $r){
		$handle = $r['cust_handle'];
		$name = ($r['cust_cname'] == "") ? $r['cust_fname']." ".$r['cust_lname'] : $r['cust_cname'];
		$code = $r['event_num'];
		$address = (strlen(trim($r['cust_city'])) > 0)?$r['cust_city']." ".$r['cust_state']." ".$r['cust_zip']:'';
		$website = (strlen(trim($r['cust_website'])) > 0)?$r['cust_website']:'';
		$EName = $r['event_name'];
		$EEmail = $r['cust_email'];

		array_push($bcc,$r['email']);
	}
	if($EId == 1){
		$bcc = implode(",",$bcc);
		$bcc = array();
		array_push($bcc,"development@proimagesoftware.com");
	}
	$Desc = cleanUpHTML($Desc);
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		$MaxSize = 20971520; //Maximum Files Sizes that can be loaded
		$IWidth = 195;
		$IHeight = 195;
		
		$Fname = $_FILES['Image']['name'];
		$Iname = format_file_name($Fname,"i");
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		$Folder = realpath($r_path."../Temp");
		if($use_ftp == true){
			$conn_id = ftp_connect($ftp_server);
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
		}
		switch(strtolower($IType)){ // Switch to change file extention to Mime Type.  Populare extensions not all.
			case "image/jpeg": $fileType = "jpeg"; break;
			case "image/gif":  $fileType = "gif"; break;
		}
					
		ini_set("memory_limit","100M");
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Folder, $IWidth, $IHeight, false, true, $use_ftp, $conn_id);
		ini_restore ("memory_limit");
		
		if($Image[0] === false) $Image = false;
		else $Image = $Folder."/".$Iname;
	} else if(!is_file($Image) && $file!==false){
		$Image = false;
		$IName = false;
	}
	$title = mb_convert_encoding($EName, "UTF-8", 'HTML-ENTITIES');
		
	ob_start();
	include($r_path.'../Templates/Photographer/index.php');
	$msg = ob_get_contents();
	ob_end_clean();
	
	$Time = time();
	
	if($Image !== false && $file!==false) {
		list($width, $height) = getimagesize($Image);
		if($width > $height){ $Perc = 242/$width; $height = round($height*$Perc); $width = 242; }
		else { $Perc = 242/$height; $width = round($width*$Perc); $height = 242; }
		$msg = str_replace('[EmbedImg]','<img class="img" src="cid:'.$Time.'" width="'.$width.'" height="'.$height.'" alt="'.$handle.'" vspace="0" hspace="0" alt="'.$title.'" style="border: 3px solid #c89441;"><br clear="all" />',$msg);
	} else { $msg = str_replace('[EmbedImg]','',$msg); }
	
	$msg = str_replace('[Title]',$title,$msg);
	$msg = str_replace('[Unsubscribe]',"http://www.proimagesoftware.com/unsubscribe.php?token=".substr(time(),0,5).$EId.substr(time(),5),$msg);
	$msg = str_replace('[Text]','<p>'.ereg_replace(array("^<p>","</p>$"),'',sanatizeentry($Desc,true)).'</p>',$msg);
	$msg = str_replace('[Photographer]',$name,$msg);
	$msg = str_replace('[Address]',((strlen(trim($address))>0)?'<br />'.$address:''),$msg);
	$msg = str_replace('[Website]',((strlen(trim($website))>0)?'<br /><a href="'.$website.'" title="'.$website.'">'.$website.'</a>':''),$msg);
	$msg = str_replace('[Coupons]','',$msg);
	
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
	
	foreach($bcc as $v){
		$msg2 = str_replace(array('[Name]','%5BName%5D'),$v,$msg);
		$msg2 = str_replace(array('[Link]','%5BLink%5D'),"http://www.proimagesoftware.com/photo_viewer.php?Photographer=".$handle."&code=".$code."&email=".$v."&full=true",$msg2);
		$msg2 = str_replace(array('[Link2]','%5BLink2%5D'),wordwrap("http://www.proimagesoftware.com/photo_viewer.php?Photographer=".$handle."&code=".$code."&email=".$v."&full=true", 100, $eol),$msg2);
	
		$mail = new PHPMailer();
		$mail -> IsSendMail();
		$mail -> Host = "smtp.proimagesoftware.com";
		$mail -> IsHTML(true);
		$mail -> Sender = $EEmail;
		$mail -> Hostname = "proimagesoftware.com";
		$mail -> From = $EEmail;
		$mail -> FromName = $name;
		$mail -> AddReplyTo($EEmail, $name);
		$mail -> AddAddress($v);
		$mail -> Subject = $title;
		if($Image !== false) $mail -> AddEmbeddedImage($Image, $Time, "image.".$fileType);
		$mail -> Body = $msg2;
		$mail -> Send();
		
		unset($v);
		unset($msg2);
	}
	$Error = "Your E-mail has been sent";
	if($Image !== false && $file !== false && isset($conn_id)) @ftp_delete($conn_id, $Image); else if($file !== false) unlink($Image);
}
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
?>