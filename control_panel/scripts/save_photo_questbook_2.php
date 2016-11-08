<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
define ("PhotoExpress Pro", true);
define('Allow Scripts',true);
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_phpmailer.php';
require_once($r_path.'../Connections/cp_connection.php');
mysql_select_db($database_cp_connection, $cp_connection);

$EId = 1;
$Desc = "This IS a Test E-mail with CapITALIZED letters";
$Emailsent = false;

$query_get_records = "SELECT `cust_customers`.`cust_handle`, `photo_quest_book`.*, `photo_event`.`event_name`, `photo_event`.`event_num`, `cust_customers`.`cust_email`, `photo_event_images`.`image_tiny`, `photo_event_images`.`image_folder` FROM `photo_quest_book` INNER JOIN `photo_event` ON `photo_event`.`event_id` = `photo_quest_book`.`event_id` INNER JOIN `photo_event_images` ON `photo_event_images`.`image_id` = `photo_event`.`event_image` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` WHERE `photo_quest_book`.`event_id` = '$EId' AND `promotion` = 'y' GROUP BY `photo_quest_book`.`email` ASC";
$get_records = mysql_query($query_get_records, $cp_connection) or die(mysql_error());
$bcc = array();
while($row_get_records = mysql_fetch_assoc($get_records)){
	$handle = $row_get_records['cust_handle'];
	$code = $row_get_records['event_num'];
	$EName = $row_get_records['event_name'];
	$EEmail = $row_get_records['cust_email'];
	$IName = $row_get_records['image_tiny'];
	$Image = $r_path."../".substr($row_get_records['image_folder'],0,-11)."Thumbnails/".$IName;
	array_push($bcc,$row_get_records['email']);
}
//$bcc = implode(",",$bcc);
$bcc = array();
$bcc[0] = "development@proimagesoftware.com";
$bcc[1] = "joshuapoplocks@hotmail.com";
if (is_uploaded_file($_FILES['Image']['tmp_name'])){
	$Image = $_FILES['Image']['tmp_name'];
	$IName = $_FILES['Image']['name'];
} else {
	if(!is_file($Image)){
		$Image = false;
		$IName = false;
	}
}
$title = $EName;
$text = '<h1>Hello from '.$handle.'</h1><p><a href="http://www.proimagesoftware.com/photo_viewer.php?Photographer='.$handle.'&code='.$code.'&full=false">Click here to view the event'.$EName.'</a></p>';
if($Image !== false)$text .= '<img class="img" src="cid:'.$IName.'" width="150" alt="'.$IName.'" align="left" vspace="5" hspace="5">';
$text .= "<p>".sanatizeentry($Desc,true)."</p>";
ob_start();
include($r_path.'../template_email_2.php');
$page = ob_get_contents();
ob_end_clean();
//if($Image === false)$page = str_replace('=','=3D',$page);
//$sender = $EEmail;
//$subject = $EName;
echo $_SERVER['HTTP_HOST']; 
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
$mail -> Subject = $EName;
foreach($bcc as $v){
	$mail -> AddBCC($v);
}
$mail -> AddEmbeddedImage($Image, $IName);
$mail -> Body = $page;
$mail->Send();

//send_email($sender, $bcc, $subject, $page, true, false, false, true, $Image, $IName);
$Emailsent = true;
?>