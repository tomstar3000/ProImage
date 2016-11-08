<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$FId = $path[2];
$FName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$FStore = (isset($_POST['Store'])) ? clean_variable($_POST['Store'],true) : 'y';
$FEmail = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';
$Message = (isset($_POST['Message'])) ? clean_variable($_POST['Message'],true) : '';
$SText = (isset($_POST['Submit_Text'])) ? clean_variable($_POST['Submit_Text'],true) : '';
$CText = (isset($_POST['Cancle_Text'])) ? clean_variable($_POST['Cancle_Text'],true) : '';
$RBtn = (isset($_POST['Reset_Button'])) ? clean_variable($_POST['Reset_Button'],true) : 'n';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		if($_POST['Time'] != $_SESSION['Time']){
			$_SESSION['Time'] = $_POST['Time'];	
			$add = "INSERT INTO `form_forms` (`form_name`,`form_store`,`form_email`,`form_return_mssg`,`form_proc_btn`,`form_cal_btn`,`form_reset_btn`) VALUES ('$FName','$FStore','$FEmail','$Message','$SText','$CText','$RBtn');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());	
																																																						
			$query_get_last = "SELECT `form_id` FROM `form_forms` ORDER BY `form_id` DESC LIMIT 0,1";
			$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
			$row_get_last = mysql_fetch_assoc($get_last);
				
			$FId = $row_get_last['form_id'];
			array_push($path,$FId);
		}
	} else {
		$upd = "UPDATE `form_forms` SET `form_name` = '$FName',`form_store` = '$FStore',`form_email` = '$FEmail',`form_return_mssg` = '$Message',`form_proc_btn` = '$SText',`form_cal_btn` = '$CText',`form_reset_btn` = '$RBtn' WHERE `form_id` = '$FId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){	
		$query_get_info = "SELECT * FROM `form_forms` WHERE `form_id` = '$FId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$FName = $row_get_info['form_name'];
		$FStore = $row_get_info['form_store'];
		$FEmail = $row_get_info['form_email'];
		$Message = $row_get_info['form_return_mssg'];
		$SText = $row_get_info['form_proc_btn'];
		$CText = $row_get_info['form_cal_btn'];
		$RBtn = $row_get_info['form_reset_btn'];
		
		mysql_free_result($get_info);
	}
}
?>