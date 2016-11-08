<?
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$Desc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';

if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	$text = clean_variable($Desc,'Store');
	if($cont == "add"){
		if($_POST['Time'] != $_SESSION['Time']){
			$_SESSION['Time'] = $_POST['Time'];	
			$add = "INSERT INTO `web_hot_of_press` (`hot_press`) VALUES ('$text');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
			
			$query_get_last = "SELECT `hot_press_id` FROM `web_hot_of_press` ORDER BY `hot_press_id` DESC LIMIT 0,1";
			$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
			$row_get_last = mysql_fetch_assoc($get_last);
			
			$Id = $row_get_last['hot_press_id'];
			array_push($path,$Id);
		}
	} else {
		$upd = "UPDATE `web_hot_of_press` SET `hot_press` = '$text' WHERE `hot_press_id` = '1'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());		
	}
} else {
	if($cont != "add"){		
		$query_get_info = "SELECT * FROM `web_hot_of_press` WHERE `hot_press_id` = '1'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$Desc = $row_get_info['hot_press'];
		
		mysql_free_result($get_info);
	}
}

?>