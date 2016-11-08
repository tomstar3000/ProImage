<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$PId = $path[count($path)-1];
$PId = 1;
$PHead = (isset($_POST['Header_1'])) ? clean_variable($_POST['Header_1'],true) : '';
$PHead_2 = (isset($_POST['Header_2'])) ? clean_variable($_POST['Header_2'],true) : '';
$PTag = (isset($_POST['Tag_Line_1'])) ? clean_variable($_POST['Tag_Line_1'],true) : '';
$PTag_2 = (isset($_POST['Tag_Line_2'])) ? clean_variable($_POST['Tag_Line_2'],true) : '';
$PDesc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	$text = clean_variable($PDesc,'Store');
	if($cont == "add"){
		if($_POST['Time'] != $_SESSION['Time']){
			$_SESSION['Time'] = $_POST['Time'];	
			$add = "INSERT INTO `web_review_home_info` (`home_header`,`home_header_2`,`home_tag`,`home_tag_2`,`home_text`) VALUES ('$PHead','$PHead_2','$PTag','$PTag_2','$text);";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
			
			$query_get_last = "SELECT `home_id` FROM `web_review_home_info` ORDER BY `home_id` DESC LIMIT 0,1";
			$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
			$row_get_last = mysql_fetch_assoc($get_last);
			
			$PId = $row_get_last['home_id'];
			array_push($path,$PId);
		}
	} else {
		$upd = "UPDATE `web_review_home_info` SET `home_header` = '$PHead',`home_header_2` = '$PHead_2',`home_tag` = '$PTag',`home_tag_2` = '$PTag_2',`home_text` = '$text' WHERE `home_id` = '$PId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());		
	}
	$cont = "edit";
} else {
	if($cont != "add"){
		if(isset($_POST['Controller']) && $_POST['Controller'] == "Reset"){
			$query_info = "SELECT * FROM `web_home_info` WHERE `home_id` = '$PId'";
			$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
			$row_info = mysql_fetch_assoc($info);
		} else {
			$query_info = "SELECT * FROM `web_review_home_info` WHERE `home_id` = '$PId'";
			$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
			$row_info = mysql_fetch_assoc($info);
		}
		$PHead = $row_info['home_header'];
		$PHead_2 = $row_info['home_header_2'];
		$PTag = $row_info['home_tag'];
		$PTag_2 = $row_info['home_tag_2'];
		$PDesc = $row_info['home_text'];
		
		mysql_free_result($info);
	}
}

?>