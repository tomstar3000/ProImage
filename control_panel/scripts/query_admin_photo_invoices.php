<?
if (isset($r_path) === false) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';
require_once($r_path . 'scripts/fnct_record_5_table.php');
require_once($r_path . 'scripts/fnct_format_date.php');
$CustId = $path[2];
if (isset($_POST['Controller']) && ($_POST['Controller'] == "Print" || $_POST['Controller'] == "Ship"))
    require_once($r_path . 'scripts/del_admin_photo_invoices.php');
if ($path[1] == "All") {
    $Date = date("Y-m-d H:i:s");
    $StartDate = date("m/d/Y", mktime(0, 0, 0, date("m"), 1, date("Y")));
    $EndDate = date("m/d/Y", mktime(0, 0, 0, (date("m") + 1), 0, date("Y")));
    $Start = get_variable($_POST['From'], $StartDate, true); //
    $End = get_variable($_POST['To'], $EndDate, true);
    $Start = date("Y-m-d H:i:s", mktime(0, 0, 0, substr($Start, 0, 2), substr($Start, 3, 2), substr($Start, 6, 4)));
    $End = date("Y-m-d H:i:s", mktime(0, 0, 0, substr($End, 0, 2), (substr($End, 3, 2) + 1), substr($End, 6, 4)));

    $query_get_oldest = "SELECT `invoice_paid_date` FROM `orders_invoice` 
        WHERE invoice_paid_date > '0000-00-00'
        ORDER BY `invoice_paid_date` ASC LIMIT 0,1";
    $get_oldest = mysql_query($query_get_oldest, $cp_connection) or die(mysql_error());
    $row_get_oldest = mysql_fetch_assoc($get_oldest);

    $year = substr($row_get_oldest['invoice_paid_date'], 0, 4);
    if ($year == 0)
        $year = date("Y");
    $month = substr($row_get_oldest['invoice_paid_date'], 5, 2);
}
if ($path[1] == "Open") {
//  $andthis = "`orders_invoice`.`invoice_accepted` = 'y' AND `orders_invoice`.`invoice_printed` = 'n' AND `orders_invoice`.`invoice_comp` = 'n'";
    $andthis = "";
} else if ($path[1] == "Ship") {
    $andthis = "`orders_invoice`.`invoice_accepted` = 'y' AND `orders_invoice`.`invoice_printed` = 'y' AND `orders_invoice`.`invoice_comp` = 'n'";
} else if ($path[1] == "All") {
//  $andthis = "`orders_invoice`.`invoice_accepted` = 'y' AND `orders_invoice`.`invoice_comp` = 'y' AND `orders_invoice`.`invoice_paid_date` >= '$Start' AND `orders_invoice`.`invoice_paid_date` <= '$End'";
    $andthis = "`orders_invoice`.`invoice_paid_date` >= '$Start' AND `orders_invoice`.`invoice_paid_date` <= '$End'";
}
?>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
    <h2>Invoices</h2>
    <p id="Search"><a href="#" onclick="javascript:set_form('', '<? echo implode(",", $path); ?>', 'search', '<? echo $sort; ?>', '<? echo $rcrd; ?>');">Invoice
            Search</a></p>
</div>
<? if ($path[1] == "All") { ?>
    <script type="text/javascript">
            function set_time(date_val) {
                date_val = date_val.split("[-]");
                // yyyy-mm-dd
                // 0123456789
                date_val[0] = date_val[0].substr(5, 2) + "/" + date_val[0].substr(8, 2) + "/" + date_val[0].substr(0, 4);
                date_val[1] = date_val[1].substr(5, 2) + "/" + date_val[1].substr(8, 2) + "/" + date_val[1].substr(0, 4);
                document.getElementById('From').value = date_val[0];
                document.getElementById('To').value = date_val[1];
                document.getElementById('form_action_form').submit();
            }
    </script>
    <div id="Report_Date">
        <p>Yearly Report
            <select name="Report_Year" id="Report_Year" onchange="set_time(this.value);">
                <option value="0">-- Select Year --</option>
                <? for ($n = date('Y'); $n >= intval($year); $n--) { ?>
                    <option value="<? echo date("Y-m-d", mktime(0, 0, 0, 1, 1, $n)) . "[-]" . date("Y-m-d", mktime(0, 0, 0, 1, 0, ($n + 1))); ?>"><? echo $n; ?></option>
                <? } ?>
            </select>
            Monthly Report
            <select name="Report_Month" id="Report_Month" onchange="set_time(this.value);">
                <option value="0">-- Select Month --</option>
                <?
                for ($n = date('Y'); $n >= intval($year); $n--) {
                    if ($n == $year)
                        $start = $month;
                    else
                        $start = 1;
                    if ($n == date('Y'))
                        $end = date("m");
                    else
                        $end = 12;
                    for ($z = $end; $z >= $start; $z--) {
                        ?>
                        <option value="<? echo date("Y-m-d", mktime(0, 0, 0, $z, 1, $n)) . "[-]" . date("Y-m-d", mktime(0, 0, 0, ($z + 1), 0, $n)); ?>"><? echo date("M", mktime(0, 0, 0, $z, 1, date("Y"))) . " (" . $n . ")"; ?></option>
                        <?
                    }
                }
                ?>
            </select>
            <br />
            Select Report From:
            <input type="text" name="From" id="From" value="<? echo format_date($Start, "Dash", false, true, false); ?>" />
            To:
            <input type="text" name="To" id="To" value="<? echo format_date($End, "Dash", false, true, false); ?>" />
            <input type="submit" name="Submit" id="Submit" value="Generate Report" />
        </p>
    </div>
<? } ?>
<div>
    <?
    $query_get_info = "SELECT *, SUM(`count_image_ids`) AS `count_image_ids`
	FROM (
		SELECT * FROM 
			(SELECT `orders_invoice`.*, `photo_event`.`event_name`, `photo_event`.`event_num`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, COUNT(`orders_invoice_photo`.`image_id`) AS `count_image_ids` 
			FROM `orders_invoice_photo` 
			INNER JOIN `photo_event_images` 
				ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id`
			INNER JOIN `photo_event_group` 
				ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id` 
			INNER JOIN `photo_event` 
				ON `photo_event`.`event_id` = `photo_event_group`.`event_id` 
			INNER JOIN `orders_invoice` 
				ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` 
			INNER JOIN `cust_customers` 
				ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` 
			WHERE `orders_invoice`.`invoice_online` = 'n' 
				" . (!empty($andthis) ? "AND $andthis" : "") . "
			GROUP BY `orders_invoice`.`invoice_id`
                        ORDER BY `invoice_id` DESC
                        " . (($path[1] == "Open") ? "LIMIT 0, 1000" : "") . "
                ) AS `DerivedTable1`
	UNION 
		SELECT * FROM 
			(SELECT `orders_invoice`.*, `photo_event`.`event_name`, `photo_event`.`event_num`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, COUNT(`orders_invoice_border`.`image_id`) AS `count_image_ids` 
			FROM `orders_invoice_border`  
			INNER JOIN `photo_event_images`
				ON `orders_invoice_border`.`image_id` = `photo_event_images`.`image_id`
			INNER JOIN `photo_event_group` 
				ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id` 
			INNER JOIN `photo_event` 
				ON `photo_event`.`event_id` = `photo_event_group`.`event_id`  
			INNER JOIN `orders_invoice` 
				ON `orders_invoice`.`invoice_id` = `orders_invoice_border`.`invoice_id` 
			INNER JOIN `cust_customers` 
				ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` 
			WHERE `orders_invoice`.`invoice_online` = 'n' 
				" . (!empty($andthis) ? "AND $andthis" : "") . "
			GROUP BY `orders_invoice`.`invoice_id`
                        ORDER BY `invoice_id` DESC
                        " . (($path[1] == "Open") ? "LIMIT 0, 1000" : "") . "
                   ) AS `DerivedTable2`
) AS `MainTable`
GROUP BY `invoice_id`
ORDER BY `invoice_id` DESC
" . (($path[1] == "Open") ? "LIMIT 0, 1000" : "") . "
; ";
    $get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
    $row_get_info = mysql_fetch_assoc($get_info);

    $records = array();
    $headers = array();
    $sortheaders = false;
    $div_data = false;
    $drop_downs = false;
    $headers[] = "Invoice";
    $headers[] = "Date";
    $headers[] = "Accepted";
    $headers[] = "Total";
    $headers[] = "Name";
    $headers[] = "Event";
    $headers[] = "Number";
    $headers[] = "I";
    $headers[] = "P";
    $headers[] = 'R';
    if ($path[1] == "Open") {
        // $headers[] = "Printer";
        $headers[] = "&nbsp;";
        $headers[] = "&nbsp;";
    } else if ($path[1] == "Ship") {
        // $headers[] = "Shipper";
        $headers[] = "&nbsp;";
        $headers[] = "&nbsp;";
    } else if ($path[1] == "All") {
        //$headers[] = "Printer";
//        $headers[] = "Shipper";
        $headers[] = "&nbsp;";
        $headers[] = "&nbsp;";
    }
    do {
        $count = count($records);
        $records[$count][0][0] = false;
        $records[$count][0][1] = false;
        $records[$count][0][2] = false;
        $records[$count][] = $row_get_info['invoice_id'];
        $records[$count][] = $row_get_info['invoice_num'];
        $records[$count][] = format_date($row_get_info['invoice_date'], "DashShort", false, true, false) . '&nbsp;';
        $records[$count][] = format_date($row_get_info['invoice_accepted_date'], "DashShort", false, true, false);
        $records[$count][] = "$" . number_format($row_get_info['invoice_total'], 2, ".", ",");
        $records[$count][] = $row_get_info['cust_fname'] . " " . $row_get_info['cust_lname'];
        $records[$count][] = $row_get_info['event_name'];
        $records[$count][] = $row_get_info['event_num'];
        $records[$count][] = $row_get_info['count_image_ids'];
        $records[$count][] = (($row_get_info['invoice_accepted'] == 'y') ? '<strong>X</strong>' : '') . '&nbsp;';
        $records[$count][] = (($row_get_info['invoice_refund'] == 'y') ? '<strong>X</strong>' : '') . '&nbsp;';
        if ($path[1] == "Open") {
//            $records[$count][] = '<input type="hidden" name="Ids[]" id="Ids[]" value="' . $row_get_info['invoice_id'] . '">
//		<input type="text" name="Quality[]" id="Quality[]" value="' . $row_get_info['invoice_pers_quality'] . '" style="width:50px;">';
//            $records[$count][] = '<input type="text" name="Printer[]" id="Printer[]" value="' . $row_get_info['invoice_pers_print'] . '" style="width:50px;">';
            $records[$count][] = '<a href="/checkout/invoice.php?invoice=' . $row_get_info['invoice_enc'] . '" target="_blank">View</a>';
            $records[$count][] = "&nbsp;";
        } else if ($path[1] == "Ship") {
//            $records[$count][] = '<input type="hidden" name="Ids[]" id="Ids[]" value="' . $row_get_info['invoice_id'] . '">
//		<input type="text" name="Quality[]" id="Quality[]" value="' . $row_get_info['invoice_pers_quality'] . '" style="width:50px;">';
//            $records[$count][] = '<input type="text" name="Shipper[]" id="Shipper[]" value="' . $row_get_info['invoice_pers_ship'] . '" style="width:50px;">';
            $records[$count][] = '<a href="/checkout/invoice.php?invoice=' . $row_get_info['invoice_enc'] . '" target="_blank">View</a>';
            $records[$count][] = "&nbsp;";
        } else if ($path[1] == "All") {
//            $records[$count][] = $row_get_info['invoice_pers_quality'];
//            $records[$count][] = $row_get_info['invoice_pers_print'];
//            $records[$count][] = $row_get_info['invoice_pers_ship'];
            $records[$count][] = '<a href="/checkout/invoice.php?invoice=' . $row_get_info['invoice_enc'] . '" target="_blank">View</a>';
        }
        $tempnum = $row_get_info['invoice_num'];
        while (strlen($tempnum) < 5)
            $tempnum = "0" . $tempnum;
        //if(is_dir($r_path."../toPhatFoto/".substr($row_get_info['invoice_date'],0,10)."/".$tempnum)){
        /* 	$records[$count][11] = '<div style="width:110px; height20px;" align="center"><script type="text/javascript">
          function FileDownload_'.$count.'(Step){
          if (Step == 2){
          getFileDownloader("FileDownloader_'.$count.'").setFileList("GetFileList.php?id='.substr($row_get_info['invoice_accepted_date'],0,10)."/".$tempnum.'");
          }
          }
          var fd = new FileDownloaderWriter("FileDownloader_'.$count.'", 101, 19);
          fd.activeXControlCodeBase = "/Aurigma/v_1/FileDownloader.cab";
          fd.activeXControlVersion = "1,0,100,0";
          fd.addParam("ButtonDownloadText", "Download files");
          fd.addParam("ProcessSubfolders", "true");
          fd.addParam("ButtonDownloadImageFormat", "Width=101;Height=19;UrlNormal=/control_panel/images/btn_download.jpg;BackgroundColor='.$Rec_Style_1.'");
          fd.addEventListener("DownloadStep", "FileDownload_'.$count.'");
          fd.writeHtml();
          </script></div>';
          //} else {
          //	$records[$count][11] = "&nbsp;";
          //} */
    } while ($row_get_info = mysql_fetch_assoc($get_info));

    mysql_free_result($get_info);
    if ($path[1] == "Open") {
        build_record_5_table('Invoices', 'Invoices', $headers, $sortheaders, $records, $div_data, $drop_downs, array(array('Mark as Printed Invoice(s)', 'Mark as Printed', 'Print', false)), false, '100%', '0', '0', '0', false, false, $Rec_Style_1, $Rec_Style_2, $Rec_Style_3, $Rec_Style_4, $Rec_Style_5);
    } else if ($path[1] == "Ship") {
        build_record_5_table('Invoices', 'Invoices', $headers, $sortheaders, $records, $div_data, $drop_downs, array(array('Mark as Shipped and Completed ', 'Shipped and Completed', 'Ship', false)), false, '100%', '0', '0', '0', false, false, $Rec_Style_1, $Rec_Style_2, $Rec_Style_3, $Rec_Style_4, $Rec_Style_5);
    } else if ($path[1] == "All") {
        build_record_5_table('Invoices', 'Invoices', $headers, $sortheaders, $records, $div_data, $drop_downs, false, false, '100%', '0', '0', '0', false, false, $Rec_Style_1, $Rec_Style_2, $Rec_Style_3, $Rec_Style_4, $Rec_Style_5, false);
    }
    if (is_array($rcrd))
        $rcrd = implode(",", $rcrd);
    ?>
</div>
