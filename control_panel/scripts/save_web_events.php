<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_image_resize.php';
require_once $r_path.'scripts/fnct_format_file_name.php';
$Date = $path[2];
$EId = $path[3];
$Name = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$Code = (isset($_POST['Code'])) ? clean_variable($_POST['Code'],true) : '';
$EDesc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
$Cont = (isset($_POST['Contact'])) ? clean_variable($_POST['Contact'],true) : '0';
$Loc = (isset($_POST['Location'])) ? clean_variable($_POST['Location'],true) : '0';
$Price = (isset($_POST['Price'])) ? clean_variable($_POST['Price'],true) : '';
$Link = (isset($_POST['Link'])) ? clean_variable($_POST['Link'],true) : '';
$SDate = (isset($_POST['Start_Date'])) ? clean_variable($_POST['Start_Date'],true) : $Date;
$SHour = (isset($_POST['Start_Hour'])) ? clean_variable($_POST['Start_Hour'],true) : '00';
$SMint = (isset($_POST['Start_Minute'])) ? clean_variable($_POST['Start_Minute'],true) : '00';
$SAMPM = (isset($_POST['Start_AM_PM'])) ? clean_variable($_POST['Start_AM_PM'],true) : 'AM';
if($SAMPM == 1 && $SHour != "00") $SHour += 12;
if(strlen($SHour)<2)$SHour = "0".$SHour;
if(strlen($SMint)<2)$SMint = "0".$SMint;
$SDate = $SDate." ".$SHour.":".$SMint.":00";
$EDate = (isset($_POST['End_Date'])) ? clean_variable($_POST['End_Date'],true) : $Date;
$EHour = (isset($_POST['End_Hour'])) ? clean_variable($_POST['End_Hour'],true) : '00';
$EMint = (isset($_POST['End_Minute'])) ? clean_variable($_POST['End_Minute'],true) : '00';
$EAMPM = (isset($_POST['End_AM_PM'])) ? clean_variable($_POST['End_AM_PM'],true) : 'AM';
if($EAMPM == 1 && $EHour != "00") $EHour += 12;
if(strlen($EHour)<2)$EHour = "0".$EHour;
if(strlen($EMint)<2)$EMint = "0".$EMint;
$EDate = $EDate." ".$EHour.":".$EMint.":00";
$Image = "";
$Imagev = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'],true) : '';
$Private = (isset($_POST['Private'])) ? clean_variable($_POST['Private'],true) : 'n';
$Remind = (isset($_POST['Remind'])) ? clean_variable($_POST['Remind'],true) : 'n';
$DRemind = (isset($_POST['Days_Remaining'])) ? clean_variable($_POST['Days_Remaining'],true) : '';
$MRemind = (isset($_POST['Message'])) ? clean_variable($_POST['Message'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		$Fname = $_FILES['Image']['name'];
		$Iname = format_file_name($Fname,"i");
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Evnt_Folder, $Evnt_IWidth, $Evnt_IHeight, $Evnt_ICrop, $Evnt_IResize);
	} else {
		$Image = array();
		if($Evnt_IReq){
			$Image[0] = false;
			$Image[1] = "Image is Required";
		} else {
			$Image[0] = true;
			$Image[1] = $Imagev;
		}
	}
	if($Image[0]){
		$Imagev = $Image[1];
		if($_POST['Remove_Image'] == "true") $Imagev = "";
		$text = clean_variable($EDesc,'Store');
		if($cont == "add"){
			if($_POST['Time'] != $_SESSION['Time']){
				$_SESSION['Time'] = $_POST['Time'];	
				$add = "INSERT INTO `web_events` (`event_name`,`event_code`,`event_desc`,`event_start`,`event_end`,`event_price`,`event_link`,`event_loc_id`,`event_con_id`,`event_private`,`event_image`,`event_remind`,`event_r_days`,`event_r_message`) VALUES ('$Name','$Code','$text','$SDate','$EDate','$Price','$Link','$Loc','$Cont','$Private', '$Imagev','$Remind','$DRemind','$MRemind');";
				$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
				
				$query_get_last = "SELECT `event_id` FROM `web_events` ORDER BY `event_id` DESC LIMIT 0,1";
				$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
				$row_get_last = mysql_fetch_assoc($get_last);
				
				$EId = $row_get_last['event_id'];
				array_push($path,$EId);
			}
		} else if($cont == "edit"){
			$upd = "UPDATE `web_events` SET `event_name` = '$Name',`event_code` = '$Code',`event_desc` = '$text',`event_start` = '$SDate',`event_end` = '$EDate',`event_price` = '$Price',`event_link` = '$Link', `event_loc_id` = '$Loc',`event_con_id` = '$Cont',`event_private` = '$Private',`event_image` = '$Imagev',`event_remind` = '$Remind',`event_r_days` = '$DRemind',`event_r_message` = '$MRemind' WHERE `event_id` = '$EId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());		
		}
		$cont = "view";
	} else {
		if($Image[0])	$message = $Image[1];
		$Image = $Image[1];
	}
} else {
	if($cont != "add"){		
		$query_get_info = "SELECT * FROM `web_events` WHERE `event_id` = '$EId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$Name = $row_get_info['event_name'];
		$Code = $row_get_info['event_code'];
		$EDesc = $row_get_info['event_desc'];
		$Cont = $row_get_info['event_con_id'];
		$Loc = $row_get_info['event_loc_id'];
		$Price = $row_get_info['event_price'];
		$Link = $row_get_info['event_link'];
		$SDate = $row_get_info['event_start'];
		$EDate = $row_get_info['event_end'];
		$Image = "";
		$Imagev = $row_get_info['event_image'];
		$Private = $row_get_info['event_private'];
		$Remind = $row_get_info['event_remind'];
		$DRemind = $row_get_info['event_r_days'];
		$MRemind = $row_get_info['event_r_message'];
		
		mysql_free_result($get_info);
	}
}
?>
