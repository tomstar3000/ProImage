<?
if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
$EId = $path[2];
include $r_path . 'security.php';
include $r_path . 'scripts/fnct_record_5_table.php';
//if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete") include $r_path.'scripts/del_photo_event_questbook.php'; 
?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
    <h2>Event Notification List</h2>
    <p id="Add"><a href="#" onclick="javascript:set_form('', '<? echo implode(',', $path); ?>', 'add', '<? echo $sort; ?>', '<? echo $rcrd; ?>');">Add</a></p>
</div>
<div id="Div_Records">
    <?
    $query_get_info = "SELECT *
	FROM `photo_event_notes`
	WHERE `event_id` = '$EId'
	ORDER BY `event_note_name` ASC";
    $get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
    $row_get_info = mysql_fetch_assoc($get_info);
    $totalRows_get_info = mysql_num_rows($get_info);

    $records = array();
    $headers = array();
    $sortheaders = false;
    $div_data = array();
    $drop_downs = false;
    $headers[0] = "Note Name";
    $headers[1] = "Number of Days";
    $headers[2] = "&nbsp;";
    $headers[3] = "On Date";
    do {
        $count = count($records);
        $records[$count][0][0] = false;
        $records[$count][0][1] = false;
        $records[$count][0][2] = false;
        $records[$count][1] = $row_get_info['event_note_id'];
        $records[$count][2] = $row_get_info['event_note_name'];
        $records[$count][3] = $row_get_info['event_days'];
        $records[$count][4] = ($row_get_info['event_before'] == "b") ? "Before Event" : ($row_get_info['event_before'] == "s") ? "After Start of Event" : "Before End of Event";
        $records[$count][5] = format_date($row_get_info['event_date'], "Short", false, true, false);
        $div_data[$count] = $row_get_info['event_message'];
    } while ($row_get_info = mysql_fetch_assoc($get_info));

    mysql_free_result($get_info);
    build_record_5_table('Event_Notifications', 'Event Notifications', $headers, $sortheaders, $records, $div_data, $drop_downs, array(array('Delete Notification(s)', 'Delete', 'Delete', false)), false, '100%', '0', '0', '0', false, false, $Rec_Style_1, $Rec_Style_2, $Rec_Style_3, $Rec_Style_4, $Rec_Style_5);
    if (is_array($rcrd))
        $rcrd = implode(",", $rcrd);
    ?>
</div>
