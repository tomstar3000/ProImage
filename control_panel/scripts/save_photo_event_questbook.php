<?

if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';
require_once $r_path . 'scripts/fnct_clean_entry.php';
require_once $r_path . 'scripts/fnct_phpmailer.php';
require_once $r_path . 'scripts/fnct_image_resize.php';
require_once $r_path . 'scripts/fnct_format_file_name.php';

$EId = $path[2];
$QId = $path[4];
$Name = (isset($_POST['Name'])) ? clean_variable($_POST['Name'], true) : '';
$Emails = (isset($_POST['Emails'])) ? clean_variable($_POST['Emails'], true) : '';
$Emails = explode(",", $Emails);
$Days = (isset($_POST['Days'])) ? clean_variable($_POST['Days'], true) : '';
$Before = (isset($_POST['BeforeDate'])) ? clean_variable($_POST['BeforeDate'], true) : 's';
$Date = (isset($_POST['Date'])) ? clean_variable($_POST['Date'], true) : '';
if (isset($_POST['Date'])) {
    $TempDate = explode("/", $Date);
    foreach ($TempDate as $k => $v) {
        while (strlen($TempDate[$k]) < 2) {
            $TempDate[$k] = "0" . $TempDate[$k];
        }
    }
    $TempDate = implode("/", $TempDate);
    $Date = $TempDate;
} else {
    $Date = "00/00/0000";
}
$Date = substr($Date, 6, 4) . "-" . substr($Date, 0, 2) . "-" . substr($Date, 3, 2) . " 00:00:00";
$Desc = (isset($_POST['Text'])) ? clean_variable($_POST['Text']) : '';
$Image = array();
$Imagev = '';

if (isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')) {
    if (is_uploaded_file($_FILES['Image']['tmp_name'])) {
        $MaxSize = 20971520; //Maximum Files Sizes that can be loaded
        $IWidth = 195;
        $IHeight = 195;

        $Fname = $_FILES['Image']['name'];
        $Iname = format_file_name($Fname, "i");
        $ISize = $_FILES['Image']['size'];
        $ITemp = $_FILES['Image']['tmp_name'];
        $IType = $_FILES['Image']['type'];
        $Folder = realpath($r_path . "../Temp");
        if ($use_ftp == true) {
            $conn_id = ftp_connect($ftp_server);
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
        }
        ini_set("memory_limit", "100M");
        $Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Folder, $IWidth, $IHeight, false, true, $use_ftp, $conn_id);
        ini_restore("memory_limit");

        if ($Image[0] === false) {
            $Image = false;
            $Contents = "";
        } else {
            $Image = true;
            $FName = $Folder . "/" . $Iname;
            $Handle = fopen($FName, "r");
            $Contents = fread($Handle, filesize($FName));
            $Contents = base64_encode($Contents);
            fclose($Handle);
        }
    } else {
        $Image = false;
        $Contents = "";
    }

    function cleanUpHTML($text) {
        $text = ereg_replace(" style=[^>]*", "", $text);
        return ($text);
    }

    $Desc = cleanUpHTML($Desc);
    $text = clean_variable($Desc, "Store");
    if (isset($_POST['Remove_Image']) && $_POST['Remove_Image'] == "true") {
        $Contents = "";
        $Image = false;
    }

    foreach ($Emails as $k => $v) {
        $query_get_info = "SELECT *
			FROM `photo_quest_book`
			WHERE `email` = '$v'";
        $get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
        $total_get_info = mysql_num_rows($get_info);

        if ($total_get_info == 0) {
            $add = "INSERT INTO `photo_quest_book` (`event_id`,`email`,`promotion`) VALUES ('$EId','$v','y');";
            $addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
        }
    }
    /** Removed on 2013-11-18 */
//	if($cont == "add"){
//		$add = "INSERT INTO `photo_event_notes` (`event_id`,`event_note_name`,`event_days`,`event_before`,`event_date`,`event_image`,`event_image_id`,`event_message`,`event_recps`) VALUES ('$EId','$Name','$Days','$Before','$Date','$Contents','','$text','');";
//		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
//		
//		$cont = "query";
//	} else {
//		if($Image === true || ($Image === false && $_POST['Remove_Image'] == "true")){
//			$upd = "UPDATE `photo_event_notes` SET `event_note_name` = '$Name',`event_days` = '$Days',`event_before` = '$Before',`event_date` = '$Date',`event_image` = '$Contents',`event_image_id` = '',`event_message` = '$text',`event_recps` = '' WHERE `event_note_id` = '$QId'";
//		} else {
//			$upd = "UPDATE `photo_event_notes` SET `event_note_name` = '$Name',`event_days` = '$Days',`event_before` = '$Before',`event_date` = '$Date',`event_image_id` = '',`event_message` = '$text',`event_recps` = '' WHERE `event_note_id` = '$QId'";
//		}
//		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
//		
//		$cont = "view";
//	}
    if ($Image !== false)
        @ftp_delete($conn_id, $Image);
} else {
    if ($cont != "add") {
        $query_get_info = "SELECT *
			FROM `photo_event_notes`
			WHERE `event_note_id` = '$QId'";
        $get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
        $row_get_info = mysql_fetch_assoc($get_info);

        $Name = $row_get_info['event_note_name'];
        $Emails = $row_get_info['event_recps'];
        $Days = $row_get_info['event_days'];
        $Before = $row_get_info['event_before'];
        $Date = $row_get_info['event_date'];
        $Desc = $row_get_info['event_message'];
        $Imagev = ($row_get_info['event_image'] == "") ? false : true;

        mysql_free_result($get_info);
    }
}
?>