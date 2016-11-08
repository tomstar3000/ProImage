<?php

if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++) {
        $r_path .= "../";
    }
}
include $r_path . 'security.php';
require_once $r_path . 'scripts/fnct_clean_entry.php';
require_once $r_path . 'scripts/fnct_image_resize.php';
require_once $r_path . 'scripts/fnct_date_format_for_db.php';
require_once $r_path . 'scripts/fnct_format_file_name.php';
$ENId = $path[2];
$Name = (isset($_POST['Name'])) ? clean_variable($_POST['Name'], true) : '';
$Desc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
$Date = (isset($_POST['On_Date'])) ? date("Y-m-d", strtotime($_POST['On_Date'])) : '';
$Days = (isset($_POST['Days'])) ? preg_replace('/[^0-9]/', '', $_POST['Days']) : '0';
$Before = (isset($_POST['BeforeDate'])) ? (in_array($_POST['BeforeDate'], array('b', 's', 'e')) ? $_POST['BeforeDate'] : '') : '';

$Image = "";
$Imagev = false;

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
        $text = preg_replace("/style=[^>]*/", "", $text);
        return ($text);
    }

    $Desc = cleanUpHTML($Desc);
    $text = clean_variable($Desc, "Store");
    if (isset($_POST['Remove_Image']) && $_POST['Remove_Image'] == "true") {
        $Contents = "";
        $Image = false;
    }
    if ($cont == "add") {

        $add = "INSERT INTO `photo_event_notes` (`cust_id`,`event_note_name`,`event_days`,`event_before`,`event_date`,`event_image`,`event_image_id`,`event_message`,`event_recps`) VALUES ('0','$Name','$Days','$Before','$Date','$Contents','','$text','');";
        $addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
    } else {
        if ($Image === true || ($Image === false && $_POST['Remove_Image'] == "true")) {
            $upd = "UPDATE `photo_event_notes` SET event_id = '0', `event_note_name` = '$Name',`event_days` = '$Days',`event_before` = '$Before',`event_date` = '$Date',`event_image` = '$Contents',`event_image_id` = '',`event_message` = '$text',`event_recps` = '' WHERE `event_note_id` = '$ENId';";
        } else {
            $upd = "UPDATE `photo_event_notes` SET event_id = '0', `event_note_name` = '$Name',`event_days` = '$Days',`event_before` = '$Before',`event_date` = '$Date',`event_image_id` = '',`event_message` = '$text',`event_recps` = '' WHERE `event_note_id` = '$ENId';";
        }
        $updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
    }
    $cont = "view";
} else {
    if ($cont != "add") {
        $query_get_info = "SELECT * FROM `photo_event_notes` WHERE `event_note_id` = '$ENId';";
        $get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
        $row_get_info = mysql_fetch_assoc($get_info);
        $totalRows_get_info = mysql_num_rows($get_info);

        $Name = $row_get_info['event_note_name'];
        $Days = $row_get_info['event_days'];
        $Before = $row_get_info['event_before'];
        $Date = $row_get_info['event_date'];
        $Desc = $row_get_info['event_message'];
        $Imagev = ($row_get_info['event_image'] == "") ? false : true;

        mysql_free_result($get_info);
    }
}
?>