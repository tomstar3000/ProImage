<?

if (isset($r_path) === false) {
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

//$EId = (isset($_POST['Event'])) ? intval($_POST['Event'], 10) : $path[2];
$ENId = 0;
if ($path[3] == "Note") {
    $ENId = $path[4];
} else {
    $ENId = $path[2];
}
$Name = (isset($_POST['Name'])) ? clean_variable($_POST['Name']) : '';
$Emails = (isset($_POST['Emails'])) ? clean_variable($_POST['Emails']) : '';
$Emails = explode(",", $Emails);
$Days = (isset($_POST['Days'])) ? clean_variable($_POST['Days']) : '';
$Before = (isset($_POST['BeforeDate'])) ? clean_variable($_POST['BeforeDate']) : 's';
$Date = (isset($_POST['Month'])) ? clean_variable($_POST['Year'] . "-" . $_POST['Month'] . "-" . $_POST['Day'] . " 00:00:00", true) : date("Y-m-d H:i:s");
$Desc = (isset($_POST['Text'])) ? clean_variable($_POST['Text']) : '';
$Image = array();
$Imagev = '';

if ($ENId > 0) {
    $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
    $getInfo->mysql("SELECT cust_id FROM `photo_event_notes` WHERE `event_note_id` = '$ENId';");
    $getInfo = $getInfo->Rows();
    $DEFAULT = ($getInfo[0]['cust_id'] == 0) ? true : false;
}
$SaveEvents = array();

if (isset($_POST['Events'])) {
    $SaveEvents = $_POST['Events'];
    if (is_array($SaveEvents)) {
        foreach ($SaveEvents as &$values) {
            $values = intval($values, 10);
            unset($values);
        }
    }
}

if (isset($_POST['Controller']) && $_POST['Controller'] == "Clone") {
    $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
    $getInfo->mysql("SELECT * FROM `photo_event_notes` WHERE `event_note_id` = '$ENId';");
    $getEvtInfo = $getInfo->Rows();

    $Name = preg_replace('/ \(Clone\)/', '', $getEvtInfo[0]['event_note_name']) . ' (Clone)';
    $Days = $getEvtInfo[0]['event_days'];
    $Before = $getEvtInfo[0]['event_before'];
    $Date = $getEvtInfo[0]['event_date'];
    $Desc = $getEvtInfo[0]['event_message'];
    $Imagev = ($getEvtInfo[0]['event_image'] == "") ? false : true;

    $getInfo->mysql("INSERT INTO `photo_event_notes` (`cust_id`,`event_note_name`,`event_days`,`event_before`,`event_date`,`event_image`,`event_image_id`,`event_message`,`event_recps`) VALUES ('$CustId','$Name','$Days','$Before','$Date','$Imagev','','$text','');");

    $getLast = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
    $getLast->mysql("SELECT `event_note_id` FROM `photo_event_notes` WHERE `cust_id` = '$CustId' ORDER BY `event_note_id` DESC LIMIT 0,1;");
    $getLast = $getLast->Rows();

    $getNoteEvents = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
    $getNoteEvents->mysql("SELECT * FROM `photo_event_note_events` WHERE `event_note_id` = '$ENId';");
    $NoteEvents = array();
    foreach ($getNoteEvents->Rows() as $k => $r) {
        $NoteEvents[] = intval($r['event_id'], 10);
    }

    $ENId = $getLast[0]['event_note_id'];

    if (count($NoteEvents) > 0) {
        $baseSQL = "INSERT INTO photo_event_note_events VALUES ";
        foreach ($NoteEvents as &$value) {
            $value = "(" . $value . "," . $ENId . ")";
            unset($value);
        }
        $getInfo->mysql($baseSQL . implode(',', $NoteEvents) . ";");
    }

    if ($path[3] == "Note") {
        $path[4] = $ENId;
    } else {
        $path[2] = $ENId;
    }
} else if (isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')) {

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
        $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);

        $getInfo->mysql("INSERT INTO `photo_event_notes` (`cust_id`,`event_note_name`,`event_days`,`event_before`,`event_date`,`event_image`,`event_image_id`,`event_message`,`event_recps`) VALUES ('$CustId','$Name','$Days','$Before','$Date','$Contents','','$text','');");

        $getLast = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $getLast->mysql("SELECT `event_note_id` FROM `photo_event_notes` WHERE `cust_id` = '$CustId' ORDER BY `event_note_id` DESC LIMIT 0,1;");
        $getLast = $getLast->Rows();
        $ENId = $getLast[0]['event_note_id'];
        array_push($path, $ENId);

        if (count($SaveEvents) > 0) {
            $baseSQL = "INSERT INTO photo_event_note_events VALUES ";
            foreach ($SaveEvents as &$value) {
                $value = "(" . $value . "," . $ENId . ")";
                unset($value);
            }
            $getInfo->mysql($baseSQL . implode(',', $SaveEvents) . ";");
        }

        if ($path[3] == "Note") {
            $path = array_slice($path, 0, 4);
        } else {
            $path = array_slice($path, 0, 2);
        }
        $cont = "query";
    } else {
        $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        if (!$DEFAULT) {
            if ($Image === true || ($Image === false && $_POST['Remove_Image'] == "true")) {
                $getInfo->mysql("UPDATE `photo_event_notes` SET event_id = '0', `event_note_name` = '$Name',`event_days` = '$Days',`event_before` = '$Before',`event_date` = '$Date',`event_image` = '$Contents',`event_image_id` = '',`event_message` = '$text',`event_recps` = '' WHERE `event_note_id` = '$ENId';");
            } else {
                $getInfo->mysql("UPDATE `photo_event_notes` SET event_id = '0', `event_note_name` = '$Name',`event_days` = '$Days',`event_before` = '$Before',`event_date` = '$Date',`event_image_id` = '',`event_message` = '$text',`event_recps` = '' WHERE `event_note_id` = '$ENId';");
            }
        } else {
            if ($Image === false && $_POST['Remove_Image'] != "true") {
                $getInfo->mysql("SELECT event_image FROM `photo_event_notes` WHERE `event_note_id` = '$ENId';");
                $getInfo = $getInfo->Rows();
                $Contents = ($getInfo[0]['event_image'] == "") ? '' : $getEvtInfo[0]['event_image'];
            }

            $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
            $getInfo->mysql("INSERT INTO `photo_event_notes` (`cust_id`,`event_note_name`,`event_days`,`event_before`,`event_date`,`event_image`,`event_image_id`,`event_message`,`event_recps`, `event_clone_id`) VALUES ('$CustId','$Name','$Days','$Before','$Date','$Contents','','$text','',$ENId);");
            $getInfo->mysql("INSERT IGNORE INTO `photo_event_notes_cust_del` (event_note_id, `cust_id`) VALUES ($ENId, '$CustId');");
            $getInfo->mysql("DELETE FROM photo_event_note_events WHERE event_note_id = '$ENId';");

            $getLast = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
            $getLast->mysql("SELECT `event_note_id` FROM `photo_event_notes` WHERE `cust_id` = '$CustId' ORDER BY `event_note_id` DESC LIMIT 0,1;");
            $getLast = $getLast->Rows();

            $ENId = $getLast[0]['event_note_id'];
            if ($path[3] == "Note") {
                $path[4] = $ENId;
            } else {
                $path[2] = $ENId;
            }
        }

        $getNoteEvents = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $getNoteEvents->mysql("SELECT * FROM `photo_event_note_events` WHERE `event_note_id` = '$ENId';");
        $NoteEvents = array();
        foreach ($getNoteEvents->Rows() as $k => $r) {
            $NoteEvents[] = intval($r['event_id'], 10);
        }

        $add = array_diff($SaveEvents, $NoteEvents);
        $delete = array_diff($NoteEvents, $SaveEvents);
        if (count($add) > 0) {
            $baseSQL = "INSERT INTO photo_event_note_events VALUES ";
            foreach ($add as &$value) {
                $value = "(" . $value . "," . $ENId . ")";
                unset($value);
            }
            $getInfo->mysql($baseSQL . implode(',', $add) . ";");
        }
        if (count($delete) > 0) {
            $getInfo->mysql("DELETE FROM photo_event_note_events WHERE event_id IN (" . implode(',', $delete) . ") AND `event_note_id` = '$ENId';");
        }

        if ($path[3] == "Note") {
            $path = array_slice($path, 0, 4);
        } else {
            $path = array_slice($path, 0, 2);
        }
        $cont = "query";
    }
    if ($Image !== false) {
        @ftp_delete($conn_id, $Image);
    }
} else {
    if ($cont != "add" && $ENId != 0) {
        $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $getInfo->mysql("SELECT * FROM `photo_event_notes` WHERE `event_note_id` = '$ENId';");
        $getInfo = $getInfo->Rows();

        $Name = $getInfo[0]['event_note_name'];
        $Emails = $getInfo[0]['event_recps'];
        $Days = $getInfo[0]['event_days'];
        $Before = $getInfo[0]['event_before'];
        $Date = $getInfo[0]['event_date'];
        $Desc = $getInfo[0]['event_message'];
        $Imagev = ($getInfo[0]['event_image'] == "") ? false : true;
    }
}
define('NoSave', true);
if (isset($required_string))
    $onclick = 'MM_validateForm(' . $required_string . '); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}';
else
    $onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';

$getNoteEvents = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
$getNoteEvents->mysql("SELECT * FROM `photo_event_note_events` WHERE `event_note_id` = '$ENId';");
$NoteEvents = array();
foreach ($getNoteEvents->Rows() as $k => $r) {
    $NoteEvents[] = $r['event_id'];
}