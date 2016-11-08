<?php
if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';
include $r_path . 'scripts/fnct_record_5_table.php';
if (isset($_POST['Controller']) && $_POST['Controller'] == "Delete")
    ;
?>

<div id="Form_Header">
  <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Pricing Groups</p>
</div>
<div id="Div_Records">
    <?
    $records = array();
    $headers = array();
    $sortheaders = false;
    $div_data = false;
    $drop_downs = false;
    $headers[0] = "Pricing Group Name";

    //$query_get_info = "SELECT `photo_event_price_id`, `price_name` FROM `photo_event_price` WHERE cust_id = '0' ORDER BY `price_name` ASC";
    $query_get_info = "SELECT `photo_event_price_id`, `cust_id`, `price_name`, `photo_price`, `photo_color`, `photo_blk_wht`, `photo_sepia`, `photo_price_use` FROM `photo_event_price` WHERE cust_id = '0' ORDER BY `price_name` ASC";

    $get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
    $row_get_info = mysql_fetch_assoc($get_info);
    do {
        $count = count($records);
        $records[$count][0][0] = false;
        $records[$count][0][1] = false;
        $records[$count][0][2] = false;
        $records[$count][1] = $row_get_info['photo_event_price_id'];
        $records[$count][2] = $row_get_info['price_name'];
        
    } while ($row_get_info = mysql_fetch_assoc($get_info));

    mysql_free_result($get_info);
    build_record_5_table('Pricing Group', 'Event Pricing Groups', $headers, $sortheaders, $records, $div_data, $drop_downs, false, false, '100%', '0', '0', '0', false, false, $Rec_Style_1, $Rec_Style_2, $Rec_Style_3);
    ?>
</div>