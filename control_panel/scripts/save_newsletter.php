<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$TEmail = (isset($_POST['Testing_Email'])) ? clean_variable($_POST['Testing_Email']) : '';
$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email']) : '';
$Title = (isset($_POST['Title'])) ? clean_variable($_POST['Title'], true) : '';	
$Desc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
if(isset($_POST['Controller']) && ($_POST['Controller'] == "test" || $_POST['Controller'] == "send")){
	if (strtoupper(substr(PHP_OS,0,3)=='WIN')) { 
	  $eol="\r\n"; 
	} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) { 
	  $eol="\r"; 
	} else { 
	  $eol="\n"; 
	} 
	$Desc = sanatizeentry($Desc,true);
	$Title = sanatizeentry($Title,true);
	$Email = sanatizeentry($Email,true);
	$TEmail = sanatizeentry($TEmail,true);	
	$subject = $Title;
	ob_start();
	include $r_path.'../newsletter_template.php';
	$message = ob_get_contents();
	ob_end_clean();
	
	include ($r_path.'scripts/fnct_send_email.php');
	
	if($_POST['Controller'] == "test"){
		$msg = str_replace('src="/images','src="http://'.$_SERVER['HTTP_HOST'].'/images',$message);
		$mail = new PHPMailer();
		//$mail -> IsSMTP();
		$mail -> Host = "smtp.".str_replace("www.","",strtolower($_SERVER['HTTP_HOST']));
		$mail -> IsHTML(true);
		$mail -> IsSendMail();
		$mail -> From = $Email;
		$mail -> AddAddress($TEmail);
		$mail -> FromName = $Email;
		$mail -> Subject = $Title;
		$mail -> Body = $msg;
		$mail->Send();
	} else if($_POST['Controller'] == "send") {
		$mail = new PHPMailer();
		//$mail -> IsSMTP();
		$mail -> Host = "smtp.".str_replace("www.","",strtolower($_SERVER['HTTP_HOST']));
		$mail -> IsHTML(true);
		$mail -> IsSendMail();
		$mail -> From = $Email;
		$mail -> AddAddress($Email);
		$mail -> FromName = $Email;
		$mail -> Subject = $Title;
		if (is_uploaded_file($_FILES['Mailing_List']['tmp_name'])){
			$name = array();
			$name = explode(".",$_FILES['Mailing_List']['tmp_name']);
			$name[1] = strtolower($name[1]);
			if($name[1] = "csv"){
				$get_list = false;
			} else {
				$get_list = true;
			}
		} else {
			$get_list = true;
		}
		if(!$get_list){
			$handle = fopen($_FILES['Mailing_List']['tmp_name'], 'r');
			$csv = fread($handle, filesize($_FILES['Mailing_List']['tmp_name']));
			$temp_records = explode("\n",$csv);
			$temp_records[0] = substr($temp_records[0],0,-1);
			$titles = explode(",",$temp_records[0]);
			$records = array();
			foreach($temp_records as $key => $value){
				if($key != 0){
					foreach($titles as $k => $v){
						$temp_val = array();
						$temp_val = explode(",",$value);
						$records[($key-1)][substr(substr($v,0,-1),1)] = substr(substr($temp_val[$k],0,-1),1);
					}
				}
			}
			foreach($records as $k => $v){
				if($v["E-mail Address"] != "")	$mail -> AddBCC($v["E-mail Address"]);
			}
			fclose($handle);
		} else {
			$query_get_information = "SELECT `user_email` FROM `phpbb_users` WHERE `user_email` != ''";
			$get_information = mysql_query($query_get_information, $cp_connection) or die(mysql_error());
			$row_get_information = mysql_fetch_assoc($get_information);
			
			do{
				$mail -> AddBCC($row_get_information['user_email']);
			} while($row_get_information = mysql_fetch_assoc($get_information));
		}	
		$msg = str_replace('src="/images','src="http://'.$_SERVER['HTTP_HOST'].'/images',$message);
		$mail -> Body = $msg;
		$mail->Send();
	}
	
}
if(isset($_POST['Controller']) && ($_POST['Controller'] == "test" || $_POST['Controller'] == "send" || $_POST['Controller'] == "save")){
	$text = clean_variable($Desc,"Store");
	
	$upd = "UPDATE `web_newsletter` SET `web_news_desc` = '$text', `web_news_title` = '$Title',`web_news_email` = '$Email' WHERE `web_news_id` = '1'";
	$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
} else {
	$query_get_information = "SELECT * FROM `web_newsletter` WHERE `web_news_id` = '1'";
	$get_information = mysql_query($query_get_information, $cp_connection) or die(mysql_error());
	$row_get_information = mysql_fetch_assoc($get_information);
	$totalRows_get_information = mysql_num_rows($get_information);
	
	$Email = $row_get_information['web_news_email'];
	$Title = $row_get_information['web_news_title'];
	$Desc = $row_get_information['web_news_desc'];
	
	mysql_free_result($get_information);
}

?>