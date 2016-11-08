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

$where = "";

//1-3
switch( $path[1] ){
  case "All":
    $where = "WHERE `cust_photo` = 'y' AND `cust_active` = 'y' AND `cust_canceled` = 'n' AND `cust_del` = 'n' AND `cust_created` < DATE_SUB(NOW(), INTERVAL 45 DAY)";
  break;
  case "Trial":
    $where = "WHERE `cust_photo` = 'y' AND `cust_active` = 'y' AND `cust_canceled` = 'n' AND `cust_del` = 'n' AND `cust_created` BETWEEN DATE_SUB(NOW(), INTERVAL 45 DAY) AND NOW()";
  break;
  case "Hold":
    $where = "WHERE `cust_photo` = 'y' AND `cust_active` = 'y' AND `cust_canceled` = 'n' AND `cust_del` = 'n' AND `cust_hold` = 'y'";
  break;
  case "Inactive":
    $where = "WHERE `cust_photo` = 'y' AND `cust_active` = 'n' AND `cust_canceled` = 'n' AND `cust_del` = 'n' AND `cust_hold` = 'n' AND `cust_subscription_number` IS NOT NULL";
  break;
  case "Unsigned":
    $where = "WHERE `cust_photo` = 'y' AND `cust_active` = 'n' AND `cust_canceled` = 'n' AND `cust_del` = 'n' AND `cust_hold` = 'n' AND `cust_subscription_number` IS NULL";
  break;
  case "Deleted":
    $where = "WHERE `cust_photo` = 'y' AND (`cust_canceled` = 'y' OR `cust_del` = 'y')";
  break;
}

if (isset($_POST['Controller']) && ($_POST['Controller'] == "Accept" || $_POST['Controller'] == "Decline"))
    include $r_path . 'scripts/del_customers.php';
?>
<div id="Form_Header">
    <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('', 'Cust,All', 'add', '<?php echo $sort; ?>', '<?php echo $rcrd; ?>');" /> </div>
    <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
    <p>Internal Customer List </p>
</div>
<div id="Div_Records">
    <?php
    
    $sortheaders = array();

    if($sort != ""){
        $Sorting = explode(",",$sort,3);
        $sortheaders[0] = str_replace("_"," ",$Sorting[0]);
        $sortheaders[1] = $Order = str_replace("_"," ",$Sorting[1]);

        switch( $sortheaders[0] ){
          case "Company Name":
            $Sort = "`cust_customers`.`cust_cname`";
          break;
          case "Username":
            $Sort = "`cust_customers`.`cust_username`";
          break;
          case "Handle":
            $Sort = "`cust_customers`.`cust_handle`";
          break;
          case "Last":
            $Sort = "`cust_customers`.`cust_lname`";
          break;
          case "First":
            $Sort = "`cust_customers`.`cust_fname`";
          break;
          case "Disk Space":
            $Sort = "`total_size`";
          break;
          case "Joined":
            $Sort = "`cust_customers`.`cust_created`";
          break;
          case "Last Accessed":
            $Sort = "`cust_customers`.`cust_accessed`";
          break;
          case "Membership Type":
            $Sort = "`prod_products`.`prod_name`";
          break;
        }   
    }
    else{
        $sortheaders[0] = "Company Name";
        $sortheaders[1] = $Order = "ASC";
    }

    if( !$Sort ){
        $Sort = "`cust_customers`.`cust_cname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_fname`";
    }

    $query_get_cust = "SELECT DISTINCT (ROUND(SUM(`photo_event_images`.`image_size`)*100)/100) AS `total_size`, `cust_customers`.`cust_id`, `cust_customers`.`cust_handle`, `cust_customers`.`cust_username`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_created`, `prod_products`.`prod_name`, `cust_customers`.`cust_accessed`, `cust_customers`.`cust_quota`, `cust_customers`.`cust_add`, `cust_customers`.`cust_add_2`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_phone`, `cust_customers`.`cust_email`, `cust_customers`.`cust_website` FROM `cust_customers` LEFT JOIN `photo_event_images` ON (`photo_event_images`.`cust_id` = `cust_customers`.`cust_id` OR `photo_event_images`.`cust_id` IS NULL) AND `photo_event_images`.`image_active` = 'y' LEFT JOIN `prod_products` ON `prod_products`.`prod_id` = `cust_customers`.`cust_service` $where GROUP BY `cust_customers`.`cust_id` ORDER BY $Sort $Order";
    // echo "<br>$query_get_cust";

    $get_cust = mysql_query($query_get_cust, $cp_connection) or die(mysql_error());
    $row_get_cust = mysql_fetch_assoc($get_cust);

    $records = array();
    $headers = array();
    $div_data = array();
    $drop_downs = false;

    $headers[] = "Company Name";
    $headers[] = "Username";
    $headers[] = "Handle";
    $headers[] = "Last";
    $headers[] = "First";
    $headers[] = "Disk Space";
    $headers[] = "Joined";
    $headers[] = "Last Accessed";
    $headers[] = "Membership Type";

    do {

        $MBUsed = ($row_get_cust['total_size'] == NULL) ? 0 : $row_get_cust['total_size'];
        $count = count($records);

        $records[$count][0][0] = false;
        $records[$count][0][1] = false;
        $records[$count][0][2] = false;
        $records[$count][] = $row_get_cust['cust_id'];
        $records[$count][] = $row_get_cust['cust_cname'];
        $records[$count][] = $row_get_cust['cust_username'];
        $records[$count][] = $row_get_cust['cust_handle'];
        $records[$count][] = $row_get_cust['cust_lname'];
        $records[$count][] = $row_get_cust['cust_fname'];
        $records[$count][] = (round($MBUsed / 1024 * 100) / 100) . " GB / " . ($row_get_cust['cust_quota'] / 1024) . " GB";
        $records[$count][] = format_date( $row_get_cust['cust_created'], "DashShort", '', true, false );
        $records[$count][] = format_date( $row_get_cust['cust_accessed'], "DashShort", 'Standard', true, true );
        $records[$count][] = $row_get_cust['prod_name'];
        
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

    // echo "<br>$count($records)";
    mysql_free_result($get_cust);
    build_record_5_table('Customers', 'Customers', $headers, $sortheaders, $records, $div_data, $drop_downs, false, false, '100%', '0', '0', '0', false, false, $Rec_Style_1, $Rec_Style_2, $Rec_Style_3, $Rec_Style_4, $Rec_Style_5, false, false);
    if (is_array($rcrd))
        $rcrd = implode(",", $rcrd);
    ?>
</div>