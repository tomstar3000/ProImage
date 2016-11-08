<?php
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
    $del = "DELETE FROM `notifications_notifications` WHERE `notification_id` IN ( " . implode(',', $items) . " )";
    $delinfo = mysql_query($del, $cp_connection) or die(mysql_error());

    $optimize = "OPTIMIZE TABLE `notifications_notifications`";
    $optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
}
?>

<div id="Form_Header">
    <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('', 'Ntfc,Ntfc', 'add', '<?php echo $sort; ?>', '<?php echo $rcrd; ?>');" /> </div>
    <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
    <p>Admin Messages List </p>
</div>
<div id="Div_Records">
    <?php
    $records = array();
    $headers = array();
    $sortheaders = false;
    $div_data = array();
    $drop_downs = false;
    $headers[0] = "Title";
    $headers[1] = "Expires";
    $headers[2] = "Active";

    $query_get_info = "SELECT * FROM `notifications_notifications` ORDER BY `notification_name` ASC";
    $get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
    $row_get_info = mysql_fetch_assoc($get_info);
    $totalRows_get_info = mysql_num_rows($get_info);
    do {
        $count = count($records);
        $records[$count][0][0] = false;
        $records[$count][0][1] = false;
        $records[$count][0][2] = false;
        $records[$count][1] = $row_get_info['notification_id'];
        $records[$count][2] = $row_get_info['notification_name'];
        $records[$count][3] = ($row_get_info['notification_expire'] == "" || $row_get_info['notification_expire'] == "0000-00-00") ? 'Never' : format_date($row_get_info['notification_expire'], "Short", false, true, true);
        $records[$count][4] = ($row_get_info['notification_active'] == 0) ? 'No' : 'Yes';
    } while ($row_get_info = mysql_fetch_assoc($get_info));

    mysql_free_result($get_info);
    build_record_5_table('Notifications', 'Messages', $headers, $sortheaders, $records, $div_data, $drop_downs, array(array('Delete Admin Messages(s)', 'Delete', 'Delete', false)), false, '100%', '0', '0', '0', false, false, $Rec_Style_1, $Rec_Style_2, $Rec_Style_3, $Rec_Style_4, $Rec_Style_5);
    if (is_array($rcrd))
        $rcrd = implode(",", $rcrd);
    ?>
</div>
