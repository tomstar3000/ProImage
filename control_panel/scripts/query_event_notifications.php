<?
if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';
include $r_path . 'scripts/fnct_record_5_table.php';
if (isset($_POST['Controller']) && $_POST['Controller'] == "Delete") {
    $items = $_POST['Notifications_items'];

    foreach ($items as &$item) {
        $item = preg_replace('/[^0-9]/', '', $item);
        unset($item);
    }

    $del = "DELETE FROM `photo_event_notes` WHERE `event_note_id` IN ( " . implode(',', $items) . " );";
    $delinfo = mysql_query($del, $cp_connection) or die(mysql_error());

    $optimize = "OPTIMIZE TABLE `photo_event_notes`";
    $optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
}
?>

<div id="Form_Header">
    <div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('', 'Prod,Notes', 'add', '<? echo $sort; ?>', '<? echo $rcrd; ?>');" /> </div>
    <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
    <p>Event Notification List </p>
</div>
<div id="Div_Records">
    <?
    $records = array();
    $headers = array();
    $sortheaders = false;
    $div_data = false;
    $drop_downs = false;
    $headers[0] = "Note Name";
    $headers[1] = "Number of Days";
    $headers[2] = "Time";
    $headers[3] = "On Date";
    $headers[4] = "Num of Events";

    $query_get_info = "SELECT * FROM `photo_event_notes` WHERE cust_id = '0' AND event_id = 0 ORDER BY `event_note_name` ASC";
    $get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
    $row_get_info = mysql_fetch_assoc($get_info);
    do {
        $count = count($records);
        $records[$count][0][0] = false;
        $records[$count][0][1] = false;
        $records[$count][0][2] = false;
        $records[$count][1] = $row_get_info['event_note_id'];
        $records[$count][2] = $row_get_info['event_note_name'];
        $records[$count][3] = $row_get_info['event_days'];
        $records[$count][4] = ($row_get_info['event_before'] == "b") ?"Before Event Release Date" : (($row_get_info['event_before'] == "s") ? "After Event Release Date" : (($row_get_info['event_before'] == "e") ? "Before Event Expiration Date" : "On Specified Date at 12:01am"));
        $records[$count][5] = (in_array($row_get_info['event_before'], array('b', 's', 'e')) ? '' : format_date($row_get_info['event_date'], "Short", false, true, false));
        $records[$count][6] = $row_get_info['event_name'];
    } while ($row_get_info = mysql_fetch_assoc($get_info));

    mysql_free_result($get_info);
    build_record_5_table('Notifications', 'Event Notifications', $headers, $sortheaders, $records, $div_data, $drop_downs, array(array('Delete Event Notification(s)', 'Delete', 'Delete', false)), false, '100%', '0', '0', '0', false, false, $Rec_Style_1, $Rec_Style_2, $Rec_Style_3);
    ?>
</div>
