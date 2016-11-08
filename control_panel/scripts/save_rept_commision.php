<?

if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security_2.php';
set_time_limit(0);
ini_set('max_execution_time', (60 * 60));
$Date = date("Y-m-d H:i:s");
$StartDate = date("m/d/Y", mktime(0, 0, 0, date("m"), 1, date("Y")));
$EndDate = date("m/d/Y", mktime(0, 0, 0, (date("m") + 1), 0, date("Y")));
$Start = get_variable($_POST['From'], $StartDate, true); //
$End = get_variable($_POST['To'], $EndDate, true);
$Start = date("Y-m-d H:i:s", mktime(0, 0, 0, substr($Start, 0, 2), substr($Start, 3, 2), substr($Start, 6, 4)));
$End = date("Y-m-d H:i:s", mktime(0, 0, -1, substr($End, 0, 2), (substr($End, 3, 2) + 1), substr($End, 6, 4)));
$addthis = "";
$addthis2 = "";
$innerthis = "";
$selectthis = "";
$merchaddthis = "";
$merchaddthis2 = "";
if (isset($_POST['Controller']) && $_POST['Controller'] == "Save_Comm") {
    $CommIds = $_POST['RevIds'];
    $Comm = $_POST['Rev'];
    foreach ($CommIds as $key => $value) {
        $upd = "UPDATE `cust_customers` SET `cust_rev` = '" . clean_variable($Comm[$key], true) . "' WHERE `cust_id` = '$value'";
        $updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
    }
}
if (isset($path[2]) && is_int(intval($path[2]))) {
    $addthis = " AND `cust_customers`.`cust_id` = '" . $path[2] . "'";
    $merchaddthis = " AND `cust_id` = '" . $path[2] . "'";
    $Events = get_variable($_POST['Events'], 0, true); //
    if ($Events > 0) {
        $addthis2 = " AND `photo_event_group`.`event_id` = '" . $Events . "'";
        $innerthis = ' INNER JOIN `photo_event_group` ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id` ';
        $selectthis = ', `photo_event_group`.`event_id`';
        $merchaddthis2 = " AND `event_id` = '" . $Events . "' ";
    }
} else if (isset($_POST['Photographers']) && $_POST['Photographers'] != 0) {
    $Photog = $_POST['Photographers'];
    $addthis = " AND `cust_customers`.`cust_id` = '" . $Photog . "'";
    $merchaddthis = " AND `cust_id` = '" . $Photog . "'";
    $Events = get_variable($_POST['Events'], 0, true); //
    if ($Events > 0) {
        $addthis2 = " AND `photo_event_group`.`event_id` = '" . $Events . "'";
        $innerthis = ' INNER JOIN `photo_event_group` ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id` ';
        $selectthis = ', `photo_event_group`.`event_id`';
        $merchaddthis2 = " AND `event_id` = '" . $Events . "' ";
    }
}
$query_get_info = "(SELECT `cust_customers`.`cust_id`, `cust_customers`.`cust_fname`, 
            `cust_customers`.`cust_lname`, `cust_customers`.`cust_cname`, 
            `cust_customers`.`cust_add`, `cust_customers`.`cust_suite_apt`, 
            `cust_customers`.`cust_add_2`, `cust_customers`.`cust_city`, 
            `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, 
            `cust_customers`.`cust_phone`, `cust_customers`.`cust_cell`, 
            `cust_customers`.`cust_fax`, `cust_customers`.`cust_work`, 
            `cust_customers`.`cust_ext`, `cust_customers`.`cust_800`, 
            `cust_customers`.`cust_email`, `cust_customers`.`cust_email_2`, 
            `cust_customers`.`cust_website`, `cust_customers`.`cust_rev`, 
										
            `orders_invoice`.`invoice_id`, `orders_invoice`.`invoice_date`, 
            `orders_invoice`.`invoice_num`, `orders_invoice`.`invoice_paid_date`, 
            `orders_invoice`.`invoice_total`, `orders_invoice`.`invoice_disc`, 
            `orders_invoice`.`invoice_tax`, `orders_invoice`.`invoice_ship`, 
            `orders_invoice`.`invoice_refund`,
            `orders_invoice`.`invoice_grand`, `profit_sharing`.`invoice_rev`, 
            `orders_invoice_codes`.`cust_code`, `orders_invoice_codes`.`prod_id`, 
            `orders_invoice_codes`.`item_count`, `orders_invoice_codes`.`disc_total` 
            " . $selectthis . " 
	FROM `orders_invoice`
	LEFT JOIN `orders_invoice_codes`
			ON (`orders_invoice_codes`.`invoice_id` = `orders_invoice`.`invoice_id`
				OR `orders_invoice_codes`.`invoice_id` IS NULL) 
	INNER JOIN `orders_invoice_photo` 
		ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id` 
	INNER JOIN `photo_event_images` 
		ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` 
		" . $innerthis . " 
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event_images`.`cust_id`
			AND `cust_customers`.`cust_photo` = 'y'
			AND `cust_customers`.`cust_del` = 'n'
	LEFT JOIN `orders_invoice` AS `profit_sharing` 
		ON (`profit_sharing`.`cust_id` = `cust_customers`.`cust_id` OR `profit_sharing`.`cust_id` IS NULL) 
	WHERE `orders_invoice`.`cust_id` > '0' 
		AND `orders_invoice`.`invoice_paid_date` >= '$Start' 
		AND `orders_invoice`.`invoice_paid_date` <= '$End' " . $addthis . $addthis2 . " 
	GROUP BY `orders_invoice`.`invoice_id` )
UNION
	(SELECT `cust_customers`.`cust_id`, `cust_customers`.`cust_fname`, 
        `cust_customers`.`cust_lname`, `cust_customers`.`cust_cname`, 
        `cust_customers`.`cust_add`, `cust_customers`.`cust_suite_apt`, 
        `cust_customers`.`cust_add_2`, `cust_customers`.`cust_city`, 
        `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, 
        `cust_customers`.`cust_phone`, `cust_customers`.`cust_cell`, 
        `cust_customers`.`cust_fax`, `cust_customers`.`cust_work`, 
        `cust_customers`.`cust_ext`, `cust_customers`.`cust_800`, 
        `cust_customers`.`cust_email`, `cust_customers`.`cust_email_2`, 
        `cust_customers`.`cust_website`, `cust_customers`.`cust_rev`, 
	 
	 `orders_invoice`.`invoice_id`, `orders_invoice`.`invoice_date`, 
         `orders_invoice`.`invoice_num`, `orders_invoice`.`invoice_paid_date`, 
         `orders_invoice`.`invoice_total`, `orders_invoice`.`invoice_disc`, 
         `orders_invoice`.`invoice_tax`, `orders_invoice`.`invoice_ship`, 
         `orders_invoice`.`invoice_refund`,
         `orders_invoice`.`invoice_grand`, `profit_sharing`.`invoice_rev`,
         `orders_invoice_codes`.`cust_code`, `orders_invoice_codes`.`prod_id`, 
         `orders_invoice_codes`.`item_count`, `orders_invoice_codes`.`disc_total` 
         " . $selectthis . " 
	FROM `orders_invoice` 
	LEFT JOIN `orders_invoice_codes`
			ON (`orders_invoice_codes`.`invoice_id` = `orders_invoice`.`invoice_id`
				OR `orders_invoice_codes`.`invoice_id` IS NULL) 
	INNER JOIN `orders_invoice_border` 
		ON `orders_invoice_border`.`invoice_id` = `orders_invoice`.`invoice_id` 
	INNER JOIN `photo_event_images` 
		ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id` 
		" . $innerthis . " 
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event_images`.`cust_id` 
			AND `cust_customers`.`cust_photo` = 'y'
			AND `cust_customers`.`cust_del` = 'n'
	LEFT JOIN `orders_invoice` AS `profit_sharing` 
		ON (`profit_sharing`.`cust_id` = `cust_customers`.`cust_id` OR `profit_sharing`.`cust_id` IS NULL) 
	WHERE `orders_invoice`.`cust_id` > '0' 
		AND `orders_invoice`.`invoice_paid_date` >= '$Start' 
		AND `orders_invoice`.`invoice_paid_date` <= '$End' " . $addthis . $addthis2 . " 
	GROUP BY `orders_invoice`.`invoice_id` )
	
	ORDER BY `cust_fname`, `cust_lname`, `invoice_paid_date` ASC;";

$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$total_info = mysql_num_rows($get_info);

$sorted_array = array();
$Totals = array('Monthly' => 0);
while ($row_get_info = mysql_fetch_assoc($get_info)) {
    $sorted_array[$row_get_info['cust_id']]['Info']['Name'] = $row_get_info['cust_fname'] . ' ' . $row_get_info['cust_lname'];
    $sorted_array[$row_get_info['cust_id']]['Info']['CName'] = $row_get_info['cust_cname'];
    $sorted_array[$row_get_info['cust_id']]['Info']['Add'] = $row_get_info['cust_add'];
    $sorted_array[$row_get_info['cust_id']]['Info']['Suite'] = $row_get_info['cust_suite_apt'];
    $sorted_array[$row_get_info['cust_id']]['Info']['Add2'] = $row_get_info['cust_add_2'];
    $sorted_array[$row_get_info['cust_id']]['Info']['City'] = $row_get_info['cust_city'];
    $sorted_array[$row_get_info['cust_id']]['Info']['State'] = $row_get_info['cust_state'];
    $sorted_array[$row_get_info['cust_id']]['Info']['Zip'] = $row_get_info['cust_zip'];
    $sorted_array[$row_get_info['cust_id']]['Info']['Phone'] = $row_get_info['cust_phone'];
    $sorted_array[$row_get_info['cust_id']]['Info']['Cell'] = $row_get_info['cust_cell'];
    $sorted_array[$row_get_info['cust_id']]['Info']['Fax'] = $row_get_info['cust_fax'];
    $sorted_array[$row_get_info['cust_id']]['Info']['Work'] = $row_get_info['cust_work'];
    $sorted_array[$row_get_info['cust_id']]['Info']['Ext'] = $row_get_info['cust_ext'];
    $sorted_array[$row_get_info['cust_id']]['Info']['800'] = $row_get_info['cust_800'];
    $sorted_array[$row_get_info['cust_id']]['Info']['Email'] = $row_get_info['cust_email'];
    $sorted_array[$row_get_info['cust_id']]['Info']['Email2'] = $row_get_info['cust_email_2'];
    $sorted_array[$row_get_info['cust_id']]['Info']['Website'] = $row_get_info['website'];
    $sorted_array[$row_get_info['cust_id']]['Totals']['Rev'] = (intval($row_get_info['cust_rev'], 10) === 0) ? 10 : $row_get_info['cust_rev'];
    $sorted_array[$row_get_info['cust_id']]['Inv'] = $row_get_info['invoice_id'];
    $sorted_array[$row_get_info['cust_id']]['Paid'] = $row_get_info['invoice_paid_date'];

    $temp_id = $row_get_info['invoice_id'];
    //$date = date("Y-m",mktime(0,0,0,substr($row_get_info['invoice_paid_date'],5,2),1,substr($row_get_info['invoice_paid_date'],0,4)));
    $date = $Start . "." . $End;

    $query_get_items = "(SELECT `photo_event_images`.`image_name`, `photo_event_images`.`image_tiny`, `photo_event_images`.`image_id`, `orders_invoice_photo`.`invoice_image_size_id`, `orders_invoice_photo`.`invoice_image_asis`, `orders_invoice_photo`.`invoice_image_sepia`, `orders_invoice_photo`.`invoice_image_bw`, `orders_invoice_photo`.`invoice_image_cost`, `orders_invoice_photo`.`invoice_image_price`, `prod_products`.`prod_name`, `prod_products`.`prod_id`, `prod_products`.`prod_price`
		FROM `orders_invoice_photo` 
		INNER JOIN `photo_event_images` 
			ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` 
		INNER JOIN `prod_products` 
			ON `prod_products`.`prod_id` = `orders_invoice_photo`.`invoice_image_size_id`
		WHERE `orders_invoice_photo`.`invoice_id` = '$temp_id' )
	UNION
		(SELECT `photo_event_images`.`image_name`, `photo_event_images`.`image_tiny`, `photo_event_images`.`image_id`, `orders_invoice_border`.`invoice_image_size_id`, `orders_invoice_border`.`invoice_image_asis`, `orders_invoice_border`.`invoice_image_sepia`, `orders_invoice_border`.`invoice_image_bw`, `orders_invoice_border`.`invoice_image_cost`, `orders_invoice_border`.`invoice_image_price`, `prod_products`.`prod_name`, `prod_products`.`prod_id`, `prod_products`.`prod_price` 
		FROM `orders_invoice_border` 
		INNER JOIN `photo_event_images` 
			ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id` 
		INNER JOIN `prod_products` 
			ON `prod_products`.`prod_id` = `orders_invoice_border`.`invoice_image_size_id` 
		WHERE `orders_invoice_border`.`invoice_id` = '$temp_id')
		ORDER BY `image_name` ASC; ";
    $get_items = mysql_query($query_get_items, $cp_connection) or die(mysql_error());
    $DiscStored = false;
    while ($row_get_items = mysql_fetch_assoc($get_items)) {
        $key = $row_get_items['prod_id'] . str_replace(".", "", $row_get_items['invoice_image_price']);
        $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Items'][$key]['NAME'] = $row_get_items['prod_name'];
        $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Items'][$key]['PRICE'] = $row_get_items['invoice_image_price'];
        $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Items'][$key]['COST'] = $row_get_items['invoice_image_cost'];
        if ($row_get_info['invoice_refund'] == 'y') {
            $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Items'][$key]['Refund']+=$row_get_items['invoice_image_asis'] + $row_get_items['invoice_image_bw'] + $row_get_items['invoice_image_sepia'];
        } else {
            $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Items'][$key]['Refund'] += 0;
        }
        $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Items'][$key]['Total'] += $row_get_items['invoice_image_asis'] + $row_get_items['invoice_image_bw'] + $row_get_items['invoice_image_sepia'];
        if ($row_get_items['prod_id'] == $row_get_info['prod_id'] && $row_get_info['prod_id'] != 0 && $DiscStored == false) {
            $DiscStored = true;
            $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Items'][$key]['Disc']['Total'] += $row_get_info['item_count'];
            $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Items'][$key]['Disc']['Price'] += $row_get_info['disc_total'];
        } else {
            $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Items'][$key]['Disc']['Total'] += 0;
            $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Items'][$key]['Disc']['Price'] += 0;
        }
    }
    unset($DiscStored);
    $ItemCost += $row_get_items['invoice_image_cost'];
    $ItemDisc += $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Items'][$key]['Disc']['Total'];

    $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Totals']['Total'] += $row_get_info['invoice_total'];
    if ($row_get_info['cust_code'] == 'y') {
        $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Totals']['Disc'] += $row_get_info['invoice_disc'];
    } else {
        if ($row_get_info['prod_id'] == 0) {
            $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Totals']['Disc2'] += $row_get_info['invoice_disc'];
        }
    }
    if ($row_get_info['invoice_refund'] == 'y') {
        $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Totals']['Refund'] += $row_get_info['invoice_total'];
    } else {
        $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Totals']['Refund'] += 0;
    }
    $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Totals']['Tax'] += $row_get_info['invoice_tax'];
    $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Totals']['Ship'] += $row_get_info['invoice_ship'];
    $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Totals']['Grand'] += $row_get_info['invoice_grand'];
    $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Totals']['Due'] = 0;

    $query_getRev = "SELECT `invoice_rev`, `invoice_date`, `prod_id` FROM `orders_invoice`
            INNER JOIN `orders_invoice_prod` ON (`orders_invoice_prod`.`invoice_id` = `orders_invoice`.`invoice_id`)
            WHERE `cust_id` = '" . $row_get_info['cust_id'] . "' AND `invoice_rev` > 0 ORDER BY `invoice_date` ASC;";
    $getRev = mysql_query($query_getRev, $cp_connection) or die(mysql_error());

    if ($LastCustId != $row_get_info['cust_id']) {
        $lastRev = 0;
        $RevKey = -1;
        $newRev = false;
        $LastCustId = $row_get_info['cust_id'];
    }

    while ($row_getRev = mysql_fetch_assoc($getRev)) {
        $RevDate = date("U", mktime(substr($row_getRev['invoice_date'], 11, 2), substr($row_getRev['invoice_date'], 14, 2), substr($row_getRev['invoice_date'], 17, 2), substr($row_getRev['invoice_date'], 5, 2), substr($row_getRev['invoice_date'], 8, 2), substr($row_getRev['invoice_date'], 0, 4)));

        $InvDate = date("U", mktime(substr($row_get_info['invoice_date'], 11, 2), substr($row_get_info['invoice_date'], 14, 2), substr($row_get_info['invoice_date'], 17, 2), substr($row_get_info['invoice_date'], 5, 2), substr($row_get_info['invoice_date'], 8, 2), substr($row_get_info['invoice_date'], 0, 4)));

        if (intval($InvDate) >= intval($RevDate)) {
            $updRev = intval($row_getRev['invoice_rev']);
            $revDate = $row_getRev['invoice_date'];
            $revId = $row_getRev['prod_id'];
        }
    }
    if ($updRev != $lastRev) {
        $newRev = true;
        $RevKey++;
        $lastRev = $updRev;
    }

    $ProfitShare = $row_get_info['invoice_total'];
    //$ProfitShare -= ($ItemCost*$row_get_info['item_count']*$ItemDisc);
    //$ProfitShare -= $row_get_info['invoice_disc'];
    if ($row_get_info['invoice_refund'] == 'y') {
        $ProfitShare = 0;
    }
    $ProfitShare *= ($lastRev / 100);

    if ($newRev == true) {
        $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Totals']['Rev'][$RevKey][$lastRev] = 0;
        $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Totals']['Rev'][$RevKey]['ID'] = $revId;
        $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Totals']['Rev'][$RevKey]['Date'] = $revDate;
        $newRev = false;
    }
    $sorted_array[$row_get_info['cust_id']]['Dates'][$date]['Totals']['Rev'][$RevKey][$lastRev] += $ProfitShare;
    //$sorted_array[$row_get_info['cust_id']]['Totals']['Rev'] = $lastRev;
}

$query_testMerch = "SELECT `cust_id` FROM `photo_invoices` WHERE `chrg_date` >= '$Start' AND `chrg_date` <= '$End' AND `process` = 'y' AND `fail` = 'n'" . $merchaddthis . $merchaddthis2 . " GROUP BY `cust_id`;";
$testMerch = mysql_query($query_testMerch, $cp_connection) or die(mysql_error());
$LastCustId = 0;
$LastCustId2 = 0;
while ($record = mysql_fetch_assoc($testMerch)) {
    $date = $Start . "." . $End;
    if (count($sorted_array[$record['cust_id']]) == 0) {

        $query_Info = "SELECT `cust_customers`.*, `profit_sharing`.`invoice_id`, `profit_sharing`.`invoice_paid_date` FROM `cust_customers` 					
		LEFT JOIN `orders_invoice` AS `profit_sharing` 
			ON (`profit_sharing`.`cust_id` = `cust_customers`.`cust_id` OR `profit_sharing`.`cust_id` IS NULL)
		WHERE `cust_customers`.`cust_id` = '" . $record['cust_id'] . "'; ";
        $getInfo = mysql_query($query_Info, $cp_connection) or die(mysql_error());
        $row_getInfo = mysql_fetch_assoc($getInfo);

        $sorted_array[$record['cust_id']]['Info']['Name'] = $row_getInfo['cust_fname'] . ' ' . $row_getInfo['cust_lname'];
        $sorted_array[$record['cust_id']]['Info']['CName'] = $row_getInfo['cust_cname'];
        $sorted_array[$record['cust_id']]['Info']['Add'] = $row_getInfo['cust_add'];
        $sorted_array[$record['cust_id']]['Info']['Suite'] = $row_getInfo['cust_suite_apt'];
        $sorted_array[$record['cust_id']]['Info']['Add2'] = $row_getInfo['cust_add_2'];
        $sorted_array[$record['cust_id']]['Info']['City'] = $row_getInfo['cust_city'];
        $sorted_array[$record['cust_id']]['Info']['State'] = $row_getInfo['cust_state'];
        $sorted_array[$record['cust_id']]['Info']['Zip'] = $row_getInfo['cust_zip'];
        $sorted_array[$record['cust_id']]['Info']['Phone'] = $row_getInfo['cust_phone'];
        $sorted_array[$record['cust_id']]['Info']['Cell'] = $row_getInfo['cust_cell'];
        $sorted_array[$record['cust_id']]['Info']['Fax'] = $row_getInfo['cust_fax'];
        $sorted_array[$record['cust_id']]['Info']['Work'] = $row_getInfo['cust_work'];
        $sorted_array[$record['cust_id']]['Info']['Ext'] = $row_getInfo['cust_ext'];
        $sorted_array[$record['cust_id']]['Info']['800'] = $row_getInfo['cust_800'];
        $sorted_array[$record['cust_id']]['Info']['Email'] = $row_getInfo['cust_email'];
        $sorted_array[$record['cust_id']]['Info']['Email2'] = $row_getInfo['cust_email_2'];
        $sorted_array[$record['cust_id']]['Info']['Website'] = $row_getInfo['website'];
        $sorted_array[$record['cust_id']]['Totals']['Rev'] = ($row_getInfo['cust_rev'] == 0) ? 10 : $row_getInfo['cust_rev'];
        $sorted_array[$record['cust_id']]['Inv'] = $row_getInfo['invoice_id'];
        $sorted_array[$record['cust_id']]['Paid'] = $row_getInfo['invoice_paid_date'];

        $sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Total'] = 0;
        $sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Disc'] = 0;
        $sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Disc2'] = 0;
        $sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Tax'] = 0;
        $sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Ship'] = 0;
        $sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Grand'] = 0;
        $sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Due'] = 0;


        $query_getRev = "SELECT `invoice_rev`, `invoice_date`, `prod_id`, `invoice_prod_price` FROM `orders_invoice`
            INNER JOIN `orders_invoice_prod` ON (`orders_invoice_prod`.`invoice_id` = `orders_invoice`.`invoice_id`)
            WHERE `cust_id` = '" . $row_get_info['cust_id'] . "' AND `invoice_rev` > 0 ORDER BY `invoice_date` ASC;";
        $getRev = mysql_query($query_getRev, $cp_connection) or die(mysql_error());

        if ($LastCustId != $record['cust_id']) {
            $lastRev = 0;
            $RevKey = -1;
            $newRev = false;
            $LastCustId = $record['cust_id'];
        }

        while ($row_getRev = mysql_fetch_assoc($getRev)) {
            $RevDate = date("U", mktime(substr($row_getRev['invoice_date'], 11, 2), substr($row_getRev['invoice_date'], 14, 2), substr($row_getRev['invoice_date'], 17, 2), substr($row_getRev['invoice_date'], 5, 2), substr($row_getRev['invoice_date'], 8, 2), substr($row_getRev['invoice_date'], 0, 4)));
            $InvDate = date("U");
            if (intval($InvDate) >= intval($RevDate)) {
                $updRev = intval($row_getRev['invoice_rev']);
                $revDate = date("Y-m-d H:i:s");
                $revId = $row_getRev['prod_id'];
            }
        }
        if ($updRev != $lastRev) {
            $newRev = true;
            $RevKey++;
            $lastRev = $updRev;
        }

        if ($newRev == true) {
            $sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Rev'][$RevKey][$lastRev] = 0;

            $sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Rev'][$RevKey]['ID'] = $revId;
            $sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Rev'][$RevKey]['Date'] = $revDate;
            $newRev = false;
        }
        $sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Rev'][$RevKey][$lastRev] += 0;
        $sorted_array[$record['cust_id']]['Totals']['Rev'] = $lastRev;
    }
    $sorted_array[$record['cust_id']]['Dates'][$date]['Merchant']['Total'] = 0;
    $sorted_array[$record['cust_id']]['Dates'][$date]['Merchant']['Refund'] = 0;

    $query_getMerch = "SELECT * FROM `photo_invoices` WHERE `chrg_date` >= '$Start' AND `chrg_date` <= '$End' AND `process` = 'y' AND `fail` = 'n' AND `cust_id` = '" . $record['cust_id'] . "' " . $merchaddthis2 . " ORDER BY `chrg_date` ASC;";
    $getMerch = mysql_query($query_getMerch, $cp_connection) or die(mysql_error());

    while ($row_getMerch = mysql_fetch_assoc($getMerch)) {
        $sorted_array[$record['cust_id']]['Dates'][$date]['Merchant']['Total'] += $row_getMerch['amount'];
        if ($row_getMerch['refund'] == 'y')
            $sorted_array[$record['cust_id']]['Dates'][$date]['Merchant']['Refund'] += $row_getMerch['amount'];

        if ($LastCustId2 != $record['cust_id']) {
            $lastMrch = 0;
            $LastCustId2 = $row_getMerch['cust_id'];
        }
        foreach ($sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Rev'] as $v) {
            $RevDate = date("U", mktime(substr($v['Date'], 11, 2), substr($v['Date'], 14, 2), substr($v['Date'], 17, 2), substr($v['Date'], 5, 2), substr($v['Date'], 8, 2), substr($v['Date'], 0, 4)));
            $MrchDate = date("U", mktime(substr($row_getMerch['chrg_date'], 11, 2), substr($row_getMerch['chrg_date'], 14, 2), substr($row_getMerch['chrg_date'], 17, 2), substr($row_getMerch['chrg_date'], 5, 2), substr($row_getMerch['chrg_date'], 8, 2), substr($row_getMerch['chrg_date'], 0, 4)));
            if (intval($MrchDate) >= intval($RevDate)) {
                $updMrch = intval($v['ID']);
                $FeeDate = $v['Date'];
                $SvLvl = $v['ID'];
            }
        }
        if ($updMrch != $lastMrch)
            $lastMrch = $updMrch;
        switch ($SvLvl) {
            case 9: case 328: case 10: $Fee = .06;
                break;
            case 329: case 11: $Fee = .05;
                break;
            default: $Fee = .06;
                break;
        }
        $sorted_array[$record['cust_id']]['Dates'][$date]['Merchant']['Date'][$FeeDate]['ID'] = $Fee;
        $sorted_array[$record['cust_id']]['Dates'][$date]['Merchant']['Date'][$FeeDate]['Fee'] += $row_getMerch['amount'] * $Fee;
    }
}
$query_getDues = "SELECT `invoice_prod_price`,`cust_id` FROM `orders_invoice`
    INNER JOIN `orders_invoice_prod` ON (`orders_invoice_prod`.`invoice_id` = `orders_invoice`.`invoice_id`)
    WHERE `orders_invoice`.`cust_id` > '0' 
           AND `orders_invoice`.`invoice_paid_date` >= '$Start' 
           AND `orders_invoice`.`invoice_paid_date` <= '$End'
           AND `orders_invoice`.`invoice_online` = 'y'
   GROUP BY `orders_invoice`.`invoice_id`;";
$getDues = mysql_query($query_getDues, $cp_connection) or die(mysql_error());
while ($row_getDues = mysql_fetch_assoc($getDues)) {
    $Totals['Monthly']+=intval($row_getDues['invoice_prod_price']);
    $Totals['Photogs'][$row_getDues['cust_id']] += 1;
}
?>