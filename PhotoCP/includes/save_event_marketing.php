<?

if (isset($r_path) === false) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';
require_once $r_path . 'scripts/fnct_clean_entry.php';

$EId = $path[2];
$MktIds = (isset($_POST['Event_Marketing'])) ? $_POST['Event_Marketing'] : array();
foreach ($MktIds as $k => $r)
    $MktIds[$k] == clean_variable($r, true);
$MktIds = "}" . implode("[+]", $MktIds) . "{";
$MktCodes = (isset($_POST['Event_Marketing_Codes'])) ? $_POST['Event_Marketing_Codes'] : array();
$SMktCodes = array();
foreach ($MktCodes as $r)
    if (strlen(trim($r)) > 0)
        $SMktCodes[] = clean_variable($r, true);
$MktCodes = "}" . implode("[+]", $SMktCodes) . "{";

if (isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == "add" || $cont == "edit")) {
    if ($cont == "add") {
        if ($_POST['Time'] != $_SESSION['Time']) {
            $_SESSION['Time'] = $_POST['Time'];
            //$addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
            //$addInfo->mysql("INSERT INTO `photo_event` (`cust_id`,`event_num`,`event_price_id`,`event_public`,`event_name`,`event_desc`,`event_date`,`event_end`,`photo_to_lab`,`photo_at_lab`,`photo_at_photo`,`event_use`,`event_copyright`,`event_opacity`,`event_frequency`,`event_not`) VALUES ('$CustId','$Code','$Group','$Public','$Name','$text2','$Date','$EDate','$ToLab','$AtLab','$AtPhoto','y','$Copy','$Opac','$WFreq','$ENote');");
            //$addInfo->mysql("SELECT `event_id` FROM `photo_event` WHERE `cust_id` = '$CustId' ORDER BY `event_id` DESC LIMIT 0,1;");
            //$addInfo = $addInfo->Rows();
        }
    } else {
        $updInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $updInfo->mysql("UPDATE `photo_event` SET `event_mrk_ids` = '$MktIds', `event_mrk_codes` = '$MktCodes' WHERE `event_id` = '$EId';");
    }

    define('SAVEMESSAGE', 'Event Marketing has been applied to the selected events');
} else {
    if ($cont != "add") {
        $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $getInfo->mysql("SELECT * FROM `photo_event` WHERE `event_id` = '$EId';");
        $getInfo = $getInfo->Rows();

        $MktIds = $getInfo[0]['event_mrk_ids'];
        $MktCodes = $getInfo[0]['event_mrk_codes'];
    }
}
define('NoSave', true);
if (isset($required_string))
    $onclick = 'MM_validateForm(' . $required_string . '); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}';
else
    $onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
?>