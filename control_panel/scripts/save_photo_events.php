<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++) $r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$EId = $path[2];
$Name = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$Group = (isset($_POST['Pricing_Group'])) ? clean_variable($_POST['Pricing_Group'],true) : '';
$Public = (isset($_POST['Public_Event'])) ? clean_variable($_POST['Public_Event'],true) : 'n';
$Code = (isset($_POST['Code'])) ? clean_variable($_POST['Code']) : '';
$Copy = (isset($_POST['Watermark'])) ? clean_variable($_POST['Watermark']) : ('(C)'.date("Y"));
$Opac = (isset($_POST['Watermark_Opacity'])) ? clean_variable($_POST['Watermark_Opacity']) : '20';
$WFreq = (isset($_POST['Watermark_Frequency'])) ? clean_variable($_POST['Watermark_Frequency']) : '1';
$ENote = (isset($_POST['Notify'])) ? clean_variable($_POST['Notify']) : '10';
$Image = (isset($_POST['Image_Val'])) ? clean_variable($_POST['Image_Val'],true) : '';
$ToLab = (isset($_POST['To_Lab'])) ? clean_variable($_POST['To_Lab'],true) : 'n';
$AtLab = (isset($_POST['At_Lab'])) ? clean_variable($_POST['At_Lab'],true) : 'n';
$AtPhoto = (isset($_POST['At_Studio'])) ? clean_variable($_POST['At_Studio'],true) : 'n';
$Date = (isset($_POST['Date'])) ? clean_variable($_POST['Date'],true) : '';
if(isset($_POST['Date'])){
	$TempDate = explode("/",$Date);
	foreach($TempDate as $k => $v){
		while(strlen($TempDate[$k])<2){
			$TempDate[$k] = "0".$TempDate[$k];
		}
	}
	$TempDate = implode("/",$TempDate);
	$Date = $TempDate;
}
$Date = substr($Date,6,4)."-".substr($Date,0,2)."-".substr($Date,3,2);
$EDate = (isset($_POST['End_Date'])) ? clean_variable($_POST['End_Date'],true) : '';
if(isset($_POST['End_Date'])){
	$TempDate = explode("/",$EDate);
	foreach($TempDate as $k => $v){
		while(strlen($TempDate[$k])<2){
			$TempDate[$k] = "0".$TempDate[$k];
		}
	}
	$TempDate = implode("/",$TempDate);
	$EDate = $TempDate;
}
$EDate = substr($EDate,6,4)."-".substr($EDate,0,2)."-".substr($EDate,3,2);
$SDesc = (isset($_POST['Short_Description'])) ? clean_variable($_POST['Short_Description']) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == "add" || $cont == "edit")){
	$Code = trim($Code);
	$folder_replace = array("\\","/",":","*","?","<",">","|","'","&");
	$folder_with = array("","","","","","","","","","and");
	$Code = str_replace($folder_replace,$folder_with,$Code);
	$text2 = clean_variable($SDesc,'Store');
	
	$query_get_id = "SELECT `cust_id` FROM `cust_customers` WHERE `cust_session` = '$loginsession[0]'";
	$get_id = mysql_query($query_get_id, $cp_connection) or die(mysql_error());
	$row_get_id = mysql_fetch_assoc($get_id);
	
	$cust_id = $row_get_id['cust_id'];
	
	mysql_free_result($get_id);
	if($cont == "add"){
		$query_check_number = "SELECT COUNT(`event_num`) AS `event_count` FROM `photo_event` WHERE `cust_id` = '$cust_id' AND LOWER(`event_num`) = LOWER('$Code') AND `event_use` = 'y'";
		$check_number = mysql_query($query_check_number, $cp_connection) or die(mysql_error());
		$row_check_number = mysql_fetch_assoc($check_number);
		
		if($row_check_number['event_count'] == 0){
			if($_POST['Time'] != $_SESSION['Time']){
				$_SESSION['Time'] = $_POST['Time'];
				$add = "INSERT INTO `photo_event` (`cust_id`,`event_num`,`event_price_id`,`event_public`,`event_name`,`event_short`,`event_date`,`event_end`,`photo_to_lab`,`photo_at_lab`,`photo_at_photo`,`event_use`,`event_copyright`,`event_opacity`,`event_frequency`,`event_not`) VALUES ('$CustId','$Code','$Group','$Public','$Name','$text2','$Date','$EDate','$ToLab','$AtLab','$AtPhoto','y','$Copy','$Opac','$WFreq','$ENote');";
				$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
			}
			$query_get_info = "SELECT `event_id` FROM `photo_event` WHERE `cust_id` = '$CustId' ORDER BY `event_id` DESC LIMIT 0,1";
			$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
			$row_get_info = mysql_fetch_assoc($get_info);
			
			array_push($path,$row_get_info['event_id']);
			$cont = "upload";
		} else {
			$Error = "That event code is already in use.";
		}
	} else {
		$upd = "UPDATE `photo_event` SET `event_num` ='$Code',`event_price_id` = '$Group',`event_public` = '$Public',`event_name` = '$Name',`event_short` = '$text2',`event_date` = '$Date',`event_end` = '$EDate',`photo_to_lab` = '$ToLab',`photo_at_lab` = '$AtLab' ,`photo_at_photo` = '$AtPhoto' ,`event_use` = 'y',`event_copyright` = '$Copy',`event_opacity` = '$Opac', `event_frequency` = '$WFreq', `event_not` = '$ENote' WHERE `event_id` = '$EId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());		
		$cont = "view";
	}
} else {
	if($cont != "add"){		
		$query_get_info = "SELECT * FROM `photo_event` WHERE `event_id` = '$EId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$Name = $row_get_info['event_name'];
		$Group = $row_get_info['event_price_id'];
		$Public = $row_get_info['event_public'];
		$Code = $row_get_info['event_num'];
		$Copy = $row_get_info['event_copyright'];
		$Opac = $row_get_info['event_opacity'];
		$WFreq  = $row_get_info['event_frequency'];
		$Image = $row_get_info['event_image'];
		$Date = $row_get_info['event_date'];
		$EDate = $row_get_info['event_end'];
		$ENote = $row_get_info['event_not'];
		$SDesc = $row_get_info['event_short'];
		$Desc = $row_get_info['event_desc'];
		$ToLab = $row_get_info['photo_to_lab'];
		$AtLab = $row_get_info['photo_at_lab'];
		$AtPhoto = $row_get_info['photo_at_photo'];
		
		mysql_free_result($get_info);
	}
}
?>