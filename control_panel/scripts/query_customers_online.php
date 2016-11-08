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
  
if (isset($_POST['Controller']) && ($_POST['Controller'] == "Accept" || $_POST['Controller'] == "Decline"))
    include $r_path . 'scripts/del_customers.php';

if (!$is_enabled) {
    ?>
    <div id="Form_Header">
        <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
        <p>Pending Customer List </p>
    </div>
    <?php } ?>
<div id="Div_Records">
    <?php
    $query_get_cust = "SELECT * FROM `cust_customers` WHERE `cust_online` = 'y' AND `cust_active` = 'p' ORDER BY `cust_cname` ASC";
    $get_cust = mysql_query($query_get_cust, $cp_connection) or die(mysql_error());
    $row_get_cust = mysql_fetch_assoc($get_cust);

    $records = array();
    $headers = array();
    $sortheaders = false;
    $div_data = array();
    $drop_downs = false;
    $headers[0] = "Personal Name";
    $headers[1] = "Company Name";
    do {
        $count = count($records);
        $records[$count][0][0] = false;
        $records[$count][0][1] = false;
        $records[$count][0][2] = false;
        $records[$count][1] = $row_get_cust['cust_id'];
        if ($row_get_cust['cust_lname']) {
            $records[$count][2] = $row_get_cust['cust_lname'] . ", " . $row_get_cust['cust_fname'];
        } else {
            $records[$count][2] = $row_get_cust['cust_fname'];
        }
        $records[$count][3] = $row_get_cust['cust_cname'];

        $div_data[$count] = "";
        if ($row_get_cust['cust_add']) {
            $div_data[$count] .= $row_get_cust['cust_add'] . " " . $row_get_cust['cust_suite_apt'] . "<br />";
            if ($row_get_cust['cust_add_2']) {
                $div_data[$count] .= $row_get_cust['cust_add_2'] . "<br />";
            }
            $div_data[$count] .= $row_get_cust['cust_city'] . ", " . $row_get_cust['cust_state'] . ". " . $row_get_cust['cust_zip'] . "<br />";
            if ($row_get_cust['cust_country']) {
                $div_data[$count] .= $row_get_cust['cust_country'] . "<br />";
            }
        }
        if ($row_get_cust['cust_phone']) {
            $div_data[$count] .= "Phone Number: " . phone_number($row_get_cust['cust_phone']) . "<br />";
        }
        if ($row_get_cust['cust_email']) {
            $div_data[$count] .= "E-mail: <a href=\"mailto:" . $row_get_cust['cust_email'] . "\">" . $row_get_cust['cust_email'] . "</a><br />";
        }
        if ($row_get_cust['cust_website']) {
            $div_data[$count] .= "Website: <a href=\"http://" . $row_get_cust['cust_website'] . "\" target=\"_blank\">" . $row_get_cust['cust_website'] . "</a><br />";
        }
    } while ($row_get_cust = mysql_fetch_assoc($get_cust));

    mysql_free_result($get_cust);
    build_record_5_table('Pending_Customers', 'Pending Customers', $headers, $sortheaders, $records, $div_data, $drop_downs, array(array('Accept Customer(s)', 'Accept', 'Accept', false), array('Decline Customer(s)', 'Decline', 'Decline', false)), false, '100%', '0', '0', '0', false, false, $Rec_Style_1, $Rec_Style_2, $Rec_Style_3, $Rec_Style_4, $Rec_Style_5, true);
    if (is_array($rcrd))
        $rcrd = implode(",", $rcrd);
    ?>
</div>
<div id="Form_Header">
    <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('', 'Cust,All', 'add', '<?php echo $sort; ?>', '<?php echo $rcrd; ?>');" /> </div>
    <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
    <p>Internal Customer List </p>
</div>
<div id="Div_Records">
    <?php
    // $query_get_cust = "SELECT DISTINCT (ROUND(SUM(`photo_event_images`.`image_size`)*100)/100) AS `total_size`, `cust_customers`.`cust_id`, cust_customers.cust_handle, `cust_customers`.`cust_username`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_quota`, `cust_customers`.`cust_add`, `cust_customers`.`cust_add_2`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_phone`, `cust_customers`.`cust_email`, `cust_customers`.`cust_website` FROM `cust_customers` LEFT JOIN `photo_event_images` ON (`photo_event_images`.`cust_id` = `cust_customers`.`cust_id` OR `photo_event_images`.`cust_id` IS NULL) AND `photo_event_images`.`image_active` = 'y' WHERE `cust_photo` = 'n' AND `cust_del` = 'n' GROUP BY `cust_customers`.`cust_id` ORDER BY `cust_customers`.`cust_cname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_fname` ASC";
    $query_get_cust = "SELECT `cust_customers`.`cust_id`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname` FROM `cust_customers` WHERE `cust_photo` = 'n' AND `cust_del` = 'n' GROUP BY `cust_customers`.`cust_id` ORDER BY `cust_customers`.`cust_cname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_fname` ASC";
    
    $get_cust = mysql_query($query_get_cust, $cp_connection) or die(mysql_error());
    $row_get_cust = mysql_fetch_assoc($get_cust);

    $records = array();
    $headers = array();
    $sortheaders = false;
    $div_data = array();
    $drop_downs = false;
  
    $headers[] = "Personal Name";
    $headers[] = "Company Name";
    do {
        $count = count($records);

        $records[$count][0] = false;
        $records[$count][] = $row_get_cust['cust_id'];
        if ($row_get_cust['cust_lname']) {
            $records[$count][] = $row_get_cust['cust_lname'] . ", " . $row_get_cust['cust_fname'];
        } else {
            $records[$count][] = $row_get_cust['cust_fname'];
        }
        $records[$count][] = $row_get_cust['cust_cname'];
       
    } while ($row_get_cust = mysql_fetch_assoc($get_cust));

    mysql_free_result($get_cust);
    build_record_5_table('Customers', 'Customers', $headers, $sortheaders, $records, $div_data, $drop_downs, false, false, '100%', '0', '0', '0', false, false, $Rec_Style_1, $Rec_Style_2, $Rec_Style_3, $Rec_Style_4, $Rec_Style_5, false, false);
    if (is_array($rcrd))
        $rcrd = implode(",", $rcrd);
    ?>
</div>