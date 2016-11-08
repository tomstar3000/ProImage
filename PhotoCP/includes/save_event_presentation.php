<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$EId = $path[2];
$Copy = (isset($_POST['Watermark'])) ? clean_variable($_POST['Watermark']) : ('(C) '.date("Y"));
$Opac = (isset($_POST['Watermark_Opacity'])) ? clean_variable(round($_POST['Watermark_Opacity'])) : '20';
$WFreq = (isset($_POST['Watermark_Frequency'])) ? clean_variable($_POST['Watermark_Frequency']) : '1';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == "add" || $cont == "edit")){
	if($cont == "add"){
		if($_POST['Time'] != $_SESSION['Time']){
			$_SESSION['Time'] = $_POST['Time'];
			//$addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			//$addInfo->mysql("INSERT INTO `photo_event` (`cust_id`,`event_num`,`event_price_id`,`event_public`,`event_name`,`event_desc`,`event_date`,`event_end`,`photo_to_lab`,`photo_at_lab`,`photo_at_photo`,`event_use`,`event_copyright`,`event_opacity`,`event_frequency`,`event_not`) VALUES ('$CustId','$Code','$Group','$Public','$Name','$text2','$Date','$EDate','$ToLab','$AtLab','$AtPhoto','y','$Copy','$Opac','$WFreq','$ENote');");
		
			//$addInfo->mysql("SELECT `event_id` FROM `photo_event` WHERE `cust_id` = '$CustId' ORDER BY `event_id` DESC LIMIT 0,1;");
			//$addInfo = $addInfo->Rows();
		}
	} else {
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql("UPDATE `photo_event` SET `event_copyright` = '$Copy',`event_opacity` = '$Opac', `event_frequency` = '$WFreq' WHERE `event_id` = '$EId';");
	}
} else {
	if($cont != "add"){		
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT * FROM `photo_event` WHERE `event_id` = '$EId';");
		$getInfo = $getInfo->Rows();
		
		$Copy = $getInfo[0]['event_copyright'];
		$Opac = $getInfo[0]['event_opacity'];
		$WFreq  = $getInfo[0]['event_frequency'];
	}
}
$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `event_image` FROM `photo_event` WHERE `event_id` = '$EId';");
$getInfo = $getInfo->Rows(); $Image = $getInfo[0]['event_image'];
	
define('NoSave',true);
if(isset($required_string))
	$onclick = 'MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}';
else 
	$onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
?>