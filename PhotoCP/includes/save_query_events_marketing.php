<?php

$MktIds = (isset($_POST['Event_Marketing'])) ? $_POST['Event_Marketing'] : array();
foreach ($MktIds as $k => $r) {
    $MktIds[$k] == clean_variable($r, true);
}
$MktIds = "}" . implode("[+]", $MktIds) . "{";
$MktCodes = (isset($_POST['Event_Marketing_Codes'])) ? $_POST['Event_Marketing_Codes'] : array();
foreach ($MktCodes as $k => $r) {
    $MktCodes[$k] == clean_variable($r, true);
}
$MktCodes = "}" . implode("[+]", $MktCodes) . "{";

if (isset($_POST['Controller']) && $_POST['Controller'] == "Save") {
    $items = $_POST['Event_items'];
    foreach ($items as $r) {
        $updInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $updInfo->mysql("UPDATE `photo_event` SET `event_mrk_ids` = '$MktIds', `event_mrk_codes` = '$MktCodes' WHERE `event_id` = '" . $r . "';");
    }

    define('SAVEMESSAGE', 'Event Marketing has been applied to selected events');
}
$onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';