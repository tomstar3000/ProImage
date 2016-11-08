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
require_once $r_path . 'scripts/fnct_date_format_for_db.php';
$NId = $path[2];
$NName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'], true) : '';
$NDiscon = (isset($_POST['Expiration'])) ? (!empty($_POST['Expiration']) ? date('Y-m-d', strtotime(clean_variable($_POST['Expiration'], true))) : '') : '';
$NDesc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
$NAct = (isset($_POST['Active'])) ? (clean_variable($_POST['Active'], true) == 'n' ? 0 : 1) : 1;

if (isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')) {

    $text = clean_variable($NDesc, 'Store');
    if ($cont == "add") {
        $add = "INSERT INTO `notifications_notifications` (`notification_name`,notification_body,`notification_date`,`notification_expire`, `notification_active`) 
            VALUES ('$NName','$text',NOW(),'$NDiscon','$NAct');";
        $addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
    } else {
        $upd = "UPDATE `notifications_notifications` SET `notification_name` = '$NName',`notification_body` = '$text',`notification_expire` = '$NDiscon',`notification_active` = '$NAct' WHERE `notification_id` = '$NId'";
        $updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
    }
    $cont = "view";
} else {
    if ($cont != "add") {
        $query_get_info = "SELECT * FROM `notifications_notifications` WHERE `notification_id` = '$NId'";
        $get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
        $row_get_info = mysql_fetch_assoc($get_info);

        $NName = $row_get_info['notification_name'];
        $NDiscon = $row_get_info['notification_expire'];
        if ($NDiscon == "0000-00-00") {
            $NDiscon = "";
        }
        $NDesc = $row_get_info['notification_body'];
        $NAct = $row_get_info['notification_active'];

        mysql_free_result($get_info);
    }
}
if (empty($NAct)) {
    $NAct = 'y';
}
?>