<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_phpmailer.php';
require_once $r_path.'scripts/fnct_image_resize.php';
require_once $r_path.'scripts/fnct_format_file_name.php';

$EId = $path[2];
$Desc = (isset($_POST['Email_Text'])) ? clean_variable($_POST['Email_Text']) : '';
$Emailsent = false;

if(isset($_POST['Controller']) && $_POST['Controller'] == "Send"){	
	$query_get_records = "SELECT `cust_customers`.`cust_fname` , `cust_customers`.`cust_lname` , `cust_customers`.`cust_cname` , `cust_customers`.`cust_handle` , `cust_customers`.`cust_thumb`, `photo_quest_book`. * , `photo_event`.`event_name` , `photo_event`.`event_num`, `cust_customers`.`cust_email`, `cover_image`.`image_tiny` AS `cover_tiny`, `cover_image`.`image_folder` AS `cover_folder`, `photo_event_images`.`image_tiny` , `photo_event_images`.`image_folder` 
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
	GROUP BY `photo_quest_book`.`email` ASC";
	$get_records = mysql_query($query_get_records, $cp_connection) or die(mysql_error());
	$bcc = array();
	while($row_get_records = mysql_fetch_assoc($get_records)){
		$handle = $row_get_records['cust_handle'];
		$name = ($row_get_records['cust_cname'] == "") ? $row_get_records['cust_fname']." ".$row_get_records['cust_lname'] : $row_get_records['cust_cname'];
		$code = $row_get_records['event_num'];
		$EName = $row_get_records['event_name'];
		$EEmail = $row_get_records['cust_email'];
		$BioImage = "Logo.jpg";
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
		}
		$IName = $row_get_records['cover_tiny'];
		$Image = $r_path."../".substr($row_get_records['cover_folder'],0,-11)."Thumbnails/".$IName;
		if($IName == '' || !is_file($Image)){
			$IName = $row_get_records['image_tiny'];
			$Image = $r_path."../".substr($row_get_records['image_folder'],0,-11)."Thumbnails/".$IName;
		}
		array_push($bcc,$row_get_records['email']);
	}
	
	if($EId == 1){
		$bcc = implode(",",$bcc);
		$bcc = array();
		array_push($bcc,"development@proimagesoftware.com");
	}
	function cleanUpHTML($text) {
		$text = ereg_replace(" style=[^>]*","", $text);
		return ($text);
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
		ini_set("memory_limit","100M");
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Folder, $IWidth, $IHeight, false, true, $use_ftp, $conn_id);
		ini_restore ("memory_limit");
		
		if($Image[0] === false){
			$Image = false;
		} else {
			$Image = $Folder."/".$Iname;
		}
	} else {
		if(!is_file($Image)){
			$Image = false;
			$IName = false;
		}
	}
	$title = mb_convert_encoding($EName, "UTF-8", 'HTML-ENTITIES');
	$text = '<h1>Hello from '.$name.'</h1><p><a href="http://www.proimagesoftware.com/'.$handle.'/index.php?code='.$code.'">Click here to view the event '.$EName.'</a></p>';
	if($Image !== false)$text .= '<img class="img" src="cid:'.time().'" width="150" alt="'.$handle.'" align="left" vspace="5" hspace="5">';
	$text .= "<p>".sanatizeentry($Desc,true)."</p>";
	$text .= "<br clear=\"all\" /><br clear=\"all\" /><p>Unsubscribe from this newsletter by <a href=\"http://www.proimagesoftware.com/unsubscribe.php?token=".substr(time(),0,5).$EId.substr(time(),5)."\">clicking here</a></p>";
	ob_start();
	include($r_path.'../template_email_2.php');
	$page = ob_get_contents();
	ob_end_clean();
	//if($Image === false)$page = str_replace('=','=3D',$page);
	$mail = new PHPMailer();
	//$mail -> IsSMTP();
	$mail -> Host = "smtp.proimagesoftware.com";
	$mail -> IsHTML(true);
	$mail -> IsSendMail();
	$mail -> Sender = "info@proimagesoftware.com";
	$mail -> Hostname = "proimagesoftware.com";
	$mail -> From = $EEmail;
	$mail -> AddAddress($EEmail);
	$mail -> FromName = $EEmail;
	$mail -> Subject = $title;
	foreach($bcc as $v){
		$mail -> AddBCC($v);
	}	
	if($Image !== false){
		$mail -> AddEmbeddedImage($Image, time(), $Fname);
	}
	$mail -> Body = $page;
	$mail->Send();
	$Emailsent = true;
	if($Image !== false) @ftp_delete($conn_id, $Image);
}
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
?>