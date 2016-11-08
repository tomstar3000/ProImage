<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$NId = $path[count($path)-1];
$Cur_id = (isset($_POST['Parnt_Page'])) ? clean_variable($_POST['Parnt_Page'],true) : 0;
$NDisplay = (isset($_POST['Display_Info'])) ? clean_variable($_POST['Display_Info'],true) : 'n';
$NHeader = (isset($_POST['News_Header'])) ? clean_variable($_POST['News_Header'],true) : '';
$NHeader2 = (isset($_POST['News_Header_2'])) ? clean_variable($_POST['News_Header_2'],true) : '';
$NLink = (isset($_POST['Link'])) ? clean_variable($_POST['Link'],true) : '';
$NDesc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
$NDate = (isset($_POST['News_Date'])) ? clean_variable($_POST['News_Date'])." 00:00:00" : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	$text = clean_variable($NDesc,"Store");	
	if($cont == "add"){
		if($_POST['Time'] != $_SESSION['Time']){
			$_SESSION['Time'] = $_POST['Time'];	
			$add = "INSERT INTO `web_news_info` (`news_info_header_1`,`news_info_header_2`,`news_info_link`,`news_info_text`,`news_info_page`,`news_info_date`,`news_show`) VALUES ('$NHeader','$NHeader2','$NLink','$text','$Cur_id','$NDate','$NDisplay');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());

			$query_get_last = "SELECT `news_info_id` FROM `web_news_info` ORDER BY `news_info_id` DESC LIMIT 0,1";
			$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
			$row_get_last = mysql_fetch_assoc($get_last);
			
			$NId = $row_get_last['news_info_id'];
			array_push($path,$NId);
		}
	} else {
		$upd = "UPDATE `web_news_info` SET `news_info_header_1` = '$NHeader',`news_info_header_2` = '$NHeader2',`news_info_link` = '$NLink',`news_info_text` = '$text',`news_info_page` = '$Cur_id', `news_info_date` = '$NDate',`news_show` = '$NDisplay' WHERE `news_info_id` = '$NId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){		
		$query_info = "SELECT * FROM `web_news_info` WHERE `news_info_id` = '$NId'";
		$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
		$row_info = mysql_fetch_assoc($info);
		$totalRows_info = mysql_num_rows($info);
		
		$NId = $row_info['news_info_id'];
		$Cur_id = $row_info['news_info_page'];
		$NHeader = $row_info['news_info_header_1'];
		$NHeader2 = $row_info['news_info_header_2'];
		$NLink = $row_info['news_info_link'];
		$NDisplay = $row_info['news_show'];
		$NDesc = $row_info['news_info_text'];
		$NDate = $row_info['news_info_date'];
	}
}
?>