<?
if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';
require_once $r_path . 'scripts/fnct_format_phone.php';
require_once $r_path . 'scripts/fnct_format_date.php';
?>
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
<?php
if (isset($path[2]) && is_int(intval($path[2]))) {
    $query_get_oldest = "(SELECT `invoice_paid_date` 
		FROM `orders_invoice` 
		INNER JOIN `orders_invoice_photo` 
			ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id` 
		INNER JOIN `photo_event_images` 
			ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` 
		INNER JOIN `cust_customers` 
			ON `cust_customers`.`cust_id` = `photo_event_images`.`cust_id` " . $addthis . "
		LIMIT 0,1)
	UNION
		(SELECT `invoice_paid_date` 
		FROM `orders_invoice` 
		INNER JOIN `orders_invoice_border` 
			ON `orders_invoice_border`.`invoice_id` = `orders_invoice`.`invoice_id` 
		INNER JOIN `photo_event_images` 
			ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id` 
		INNER JOIN `cust_customers` 
			ON `cust_customers`.`cust_id` = `photo_event_images`.`cust_id` " . $addthis . "
		LIMIT 0,1)
		ORDER BY `invoice_paid_date` ASC ";
} else {
    $query_get_oldest = "SELECT `invoice_paid_date` FROM `orders_invoice` WHERE invoice_paid_date > '0000-00-00' ORDER BY `invoice_paid_date` ASC LIMIT 0,1";
}
$get_oldest = mysql_query($query_get_oldest, $cp_connection) or die(mysql_error());
$row_get_oldest = mysql_fetch_assoc($get_oldest);
$year = substr($row_get_oldest['invoice_paid_date'], 0, 4);
if ($year == 0)
    $year = date("Y");
$month = substr($row_get_oldest['invoice_paid_date'], 5, 2);
?>

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
                if ($n == date("Y"))
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
        <?
        if (isset($path[2]) && is_int(intval($path[2]))) {
            $query_get_events = "SELECT `event_id`, `event_name`, `event_num` FROM `photo_event` WHERE `cust_id` = '$CustId' AND `event_use` = 'y' ORDER BY `event_name` ASC";
            $get_events = mysql_query($query_get_events, $cp_connection) or die(mysql_error());
            ?>
            <br />
            Event Name:
            <select name="Events" id="Events" onchange="document.getElementById('form_action_form').submit();">
                <option value="0"> -- Active Events -- </option>
                <? while ($row_get_events = mysql_fetch_assoc($get_events)) { ?>
                    <option value="<? echo $row_get_events['event_id']; ?>"<? if ($row_get_events['event_id'] == $Events) echo '  selected="selected"'; ?>><? echo $row_get_events['event_name'] . " - " . $row_get_events['event_num']; ?></option>
                    <?
                } $query_get_events = "SELECT `event_id`, `event_name`, `event_num` FROM `photo_event` WHERE `cust_id` = '$CustId' AND `event_use` = 'n' ORDER BY `event_name` ASC";
                $get_events = mysql_query($query_get_events, $cp_connection) or die(mysql_error());
                ?>
                <option value="-1"> -- Archived Events -- </option>
                <? while ($row_get_events = mysql_fetch_assoc($get_events)) { ?>
                    <option value="<? echo $row_get_events['event_id']; ?>"<? if ($row_get_events['event_id'] == $Events) echo '  selected="selected"'; ?>><? echo $row_get_events['event_name'] . " - " . $row_get_events['event_num']; ?></option>
                <? } ?>
            </select>
            <?
        } else {
            $query_get_photos = "SELECT `cust_fname`, `cust_lname`, `cust_id`, `cust_cname` FROM `cust_customers` WHERE `cust_photo` = 'y' AND `cust_del` = 'n' ORDER BY `cust_cname`, `cust_lname`, `cust_fname` ASC";
            $get_photos = mysql_query($query_get_photos, $cp_connection) or die(mysql_error());
            ?>
            <br />
            Photographers:
            <select name="Photographers" id="Photographer" onchange="document.getElementById('form_action_form').submit();">
                <option value="0"> -- Show All --</option>
                <? while ($row_photos = mysql_fetch_assoc($get_photos)) { ?>
                    <option value="<? echo $row_photos['cust_id']; ?>"<? if ($row_photos['cust_id'] == $Photog) echo '  selected="selected"'; ?>><? echo $row_photos['cust_cname'] . " - " . $row_photos['cust_lname'] . ", " . $row_photos['cust_fname']; ?></option>
                <? } ?>
            </select>
            <br />
            <br />
            <input type="button" name="Save Changes" id="Save_Changes" value="Save Changes" onclick="document.getElementById('Controller').value = 'Save_Comm';
            document.getElementById('form_action_form').submit();" />
               <? } ?>
    </p>
</div>
<?
ob_start();
$grand_sales = 0;
$grand_cost = 0;
$grand_discounts = 0;
$grand_print_discounts = 0;
$grand_profit = 0;
$grand_credit_card = 0;
$grand_due = 0;
$grand_ship = 0;
$grand_tax = 0;

if (count($sorted_array) > 0) {
    foreach ($sorted_array as $k => $v) {
        $temp_comm = array();
        echo '<div style="border:solid 1px #666666; margin-top:10px; page-break-before:always"><p style="margin:0px; padding-top:5px; padding-bottom:5px; background-color:#CCCCCC"><strong>' . $v['Info']['Name'] . '</strong></p><p style="margin:0px;">';
        echo ($v['Info']['CName'] != "") ? $v['Info']['CName'] . "<br />" : '';
        echo ($v['Info']['Add'] != "") ? $v['Info']['Add'] . (($v['Info']['Suite'] != "") ? " Suite/Apt: " . $v['Info']['Suite'] : "") . "<br />" : '';
        echo ($v['Info']['Add2'] != "") ? $v['Info']['Add2'] . "<br />" : '';
        echo ($v['Info']['City'] != "") ? $v['Info']['City'] . ' ' . $v['Info']['State'] . ' ' . $v['Info']['Zip'] . "<br />" : '';
        echo ($v['Info']['Phone'] != 0) ? 'p:' . phone_number($v['Info']['Phone']) . "<br />" : '';
        echo ($v['Info']['Cell'] != 0) ? 'c:' . phone_number($v['Info']['Cell']) . "<br />" : '';
        echo ($v['Info']['Fax'] != 0) ? 'f:' . phone_number($v['Info']['Fax']) . "<br />" : '';
        echo ($v['Info']['Work'] != 0) ? 'w:' . phone_number($v['Info']['Work']) . (($v['Info']['Ext'] != "") ? " Ext: " . $v['Info']['Ext'] : 0) . "<br />" : '';
        echo ($v['Info']['800'] != 0) ? '8:' . phone_number($v['Info']['800']) . "<br />" : '';
        echo ($v['Info']['Email'] != "") ? '<a href="mailto:' . $v['Info']['Email'] . '">' . $v['Info']['Email'] . "</a><br />" : '';
        echo ($v['Info']['Email2'] != "") ? '<a href="mailto:' . $v['Info']['Email2'] . '">' . $v['Info']['Email2'] . "</a><br />" : '';
        echo ($v['Info']['Website'] != "") ? '<a href="' . $v['Info']['Website'] . '" target="_blank">' . $v['Info']['Website'] . "</a><br />" : '';
        echo '</p>';
        echo '<p>Revenue Share: <input type="hidden" name="RevIds[]" id="RevIds[]" value="' . $k . '"><input type="text" name="Rev[]" id="Rev[]" value="' . ((intval($v['Totals']['Rev'], 10) === 0) ? 10 : $v['Totals']['Rev']) . '" size="3"> %</p>';
        if (count($v['Dates']) > 0) {
            foreach ($v['Dates'] as $key => $value) {
                echo '<blockquote>';
                //YYYY-MM-DD HH:II:SS
                //0123456789012345678
                $temp_date = explode(".", $key);
                $temp_date = date("F Y", mktime(substr($temp_date[0], 11, 2), substr($temp_date[0], 14, 2), substr($temp_date[0], 17, 2), substr($temp_date[0], 5, 2), substr($temp_date[0], 8, 2), substr($temp_date[0], 0, 4))) . " - " . date("F Y", mktime(substr($temp_date[1], 11, 2), substr($temp_date[1], 14, 2), substr($temp_date[1], 17, 2), substr($temp_date[1], 5, 2), substr($temp_date[1], 8, 2), substr($temp_date[1], 0, 4)));
                echo '<p style="margin:0px; padding-top:5px; padding-bottom:5px; background-color:#DDDDDD"><strong>' . $temp_date . '</strong></p>';
                echo '<div style="background-color:#CCCCCC; height:22px;">
				<div style="width:205px; text-align:left" class="TotalCell"><p><strong>Product</strong></p></div>
				<div style="width:45px;" class="TotalCell"><p><strong>Sold</strong></p></div>
				<div style="width:45px;" class="TotalCell"><p><strong>Disc</strong></p></div>
				<div style="width:45px;" class="TotalCell"><p><strong>Unit</strong></p></div>
				<div style="width:50px;" class="TotalCell"><p><strong>Refund</strong></p></div>
				<div style="width:70px;" class="TotalCell"><p><strong>Total</strong></p></div>
				<div style="width:60px;" class="TotalCell"><p><strong>Per Print</strong></p></div>
				<div style="width:60px;" class="TotalCell"><p><strong>Refund</strong></p></div>
				<div style="width:125px;" class="TotalCellnoBD"><p><strong>Total Charge</strong></p></div>
				</div>';
                $temp_cost = 0;
                $temp_refund = 0;
                $temp_disccost = 0;
                $temp_discprice = 0;
                $temp_cost_refund = 0;
                foreach ($value['Items'] as $key2 => $value2) {

                    $temp_cost += ($value2['COST'] * ($value2['Total'] - $value2['Disc']['Total']));
                    $temp_refund += ($value2['COST'] * ($value2['Refund']));
                    $temp_disccost +=($value2['COST'] * $value2['Disc']['Total']);
                    $temp_discprice +=($value2['PRICE'] * $value2['Disc']['Total']);
                    $temp_cost_refund += ($value2['PRICE'] * ($value2['Refund']));


                    echo '<div style="height:21px; overflow:hidden;">
                            <div style="width:205px; text-align:left" class="TotalCell"><p>' . $value2['NAME'] . '</p></div>
                            <div style="width:40px; text-align:right; padding-right:5px;" class="TotalCell"><p>' . $value2['Total'] . '</p></div>
                            <div style="width:40px; text-align:right; padding-right:5px;" class="TotalCell"><p>' . $value2['Disc']['Total'] . '</p></div>
                            <div style="width:40px; text-align:right; padding-right:5px;" class="TotalCell"><p>$' . number_format($value2['PRICE'], 2, ".", ",") . '</p></div>
                            <div style="width:45px; text-align:right; padding-right:5px;" class="TotalCell"><p>$' . number_format(($value2['PRICE'] * ($value2['Refund'])), 2, ".", ",") . '</p></div>
                            <div style="width:65px; text-align:right; padding-right:5px;" class="TotalCell"><p>$' . number_format(($value2['PRICE'] * ($value2['Total'] - $value2['Disc']['Total'] - $value2['Refund'])), 2, ".", ",") . '</p></div>
                            <div style="width:55px; text-align:right; padding-right:5px;" class="TotalCell"><p>$' . number_format($value2['COST'], 2, ".", ",") . '</p></div>
                            <div style="width:55px; text-align:right; padding-right:5px;" class="TotalCell"><p>$' . number_format(($value2['COST'] * ($value2['Refund'])), 2, ".", ",") . '</p></div>
                            <div style="width:120px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>$' . number_format(($value2['COST'] * ($value2['Total'] - $value2['Disc']['Total'] - $value2['Refund'])), 2, ".", ",") . '</p></div>
                            </div>';
                }
            }
            echo '<div style="height:3px; background-color:#999999; padding:0px; margin:0px;"></div>';
            echo '<div style="height:21px; overflow:hidden;">
		<div style="width:433px; text-align:right" class="TotalCell"><p>Total Sales&nbsp;</p></div>
		<div style="width:75px; text-align:right; padding-right:5px;" class="TotalCell"><p>$' . number_format(($value['Totals']['Total'] - $temp_discprice), 2, ".", ",") . '</p></div>';

            $grand_sales += ($value['Totals']['Total'] - $temp_discprice - $temp_cost_refund);
            echo '<div style="width:65px; text-align:right; padding-right:5px;" class="TotalCell"><p>Total Cost</p></div>
		<div style="width:120px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>($' . number_format(($temp_cost), 2, ".", ",") . ')</p></div>
		</div>';

            echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
            echo '<div style="height:21px; overflow:hidden;">
                    <div style="width:428px; text-align:right; padding-right:5px" class="TotalCell"><p>Refunds</p></div>
                    <div style="width:75px; text-align:right; padding-right:5px;" class="TotalCell"><p>($' . number_format($temp_cost_refund, 2, ".", ",") . ')</p></div>
                    <div style="width:65px; text-align:right; padding-right:5px" class="TotalCell"><p>Refunds</p></div>
                    <div style="width:120px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>($' . number_format($temp_refund, 2, ".", ",") . ')</p></div>
                 </div>';
            echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
            $profit = 0;
            foreach ($value['Totals']['Rev'] as $RevVal) {
                $RevVals = each($RevVal);
                echo '<div style="height:21px; overflow:hidden;">
                    <div style="width:509px; text-align:right; padding-right:5px" class="TotalCell"><p>Revenue Share as of ' . format_date($RevVal['Date'], "Dash", false, true, false) . ':</p></div>
                                    <div style="width:65px; text-align:right; padding-right:5px;" class="TotalCell"><p>' . $RevVals[0] . '%</p></div>
                                    <div style="width:120px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>($' . number_format($RevVals[1], 2, ".", ",") . ')</p></div>
                    </div>';
                echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
                $profit += $RevVals[1];
            }
            $grand_profit += $profit;


            $grand_cost += ($temp_cost);
            echo '<div style="height:21px; overflow:hidden;">
				<div style="width:514px; text-align:right" class="TotalCell"><p>Discounts:&nbsp;</p></div>
				<div style="width:65px; text-align:right; padding-right:5px;" class="TotalCell"><p>&nbsp;</p></div>
				<div style="width:120px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>($' . number_format($value['Totals']['Disc'] + $value['Totals']['Disc2'], 2, ".", ",") . ')</p></div>
				</div>';

            $grand_discounts += ($value['Totals']['Disc'] + $value['Totals']['Disc2']);
            echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
            echo '<div style="height:21px; overflow:hidden;">
				<div style="width:514px; text-align:right" class="TotalCell"><p>Printer Discounts:&nbsp;</p></div>
				<div style="width:65px; text-align:right; padding-right:5px;" class="TotalCell"><p>&nbsp;</p></div>
				<div style="width:120px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>($' . number_format($temp_disccost, 2, ".", ",") . ')</p></div>
				</div>';
            echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
            $grand_print_discounts += ($temp_disccost);
            $credit_fee = ($value['Totals']['Total'] - $temp_discprice - $value['Totals']['Disc'] - $value['Totals']['Disc2'] - $temp_cost_refund) * (3 / 100);
            echo '<div style="height:21px; overflow:hidden;">
				<div style="width:514px; text-align:right" class="TotalCell"><p>Processing:&nbsp;</p></div>
				<div style="width:65px; text-align:right; padding-right:5px;" class="TotalCell"><p>3%</p></div>
				<div style="width:120px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>($' . number_format($credit_fee, 2, ".", ",") . ')</p></div>
				</div>';
            $grand_credit_card += ($credit_fee);

            echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
            echo '<div style="height:21px; overflow:hidden;">
				<div style="width:509px; text-align:right; padding-right:5px" class="TotalCell"><p>Credit Card Terminal:</p></div>
				<div style="width:65px; text-align:right; padding-right:5px;" class="TotalCell"><p>&nbsp;</p></div>
				<div style="width:120px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>$' . number_format($value['Merchant']['Total'], 2, ".", ",") . '</p></div>
				</div>';
            echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
            echo '<div style="height:21px; overflow:hidden;">
				<div style="width:509px; text-align:right; padding-right:5px;" class="TotalCell"><p>Credit Card Refunds:</p></div>
				<div style="width:65px; text-align:right; padding-right:5px;" class="TotalCell"><p>&nbsp;</p></div>
				<div style="width:120px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>($' . number_format($value['Merchant']['Refund'], 2, ".", ",") . ')</p></div>
				</div>';
            echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
            $merchant_terminal = ($value['Merchant']['Total'] - $value['Merchant']['Refund']);
            $Cntr = 0;
            if (count($value['Merchant']['Date']) > 0) {
                foreach ($value['Merchant']['Date'] as $MrchKey => $MrchVal) {
                    if ($Cntr > 0)
                        echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
                    echo '<div style="height:21px; overflow:hidden;">
				<div style="width:514px; text-align:right" class="TotalCell"><p>Terminal Fee as of ' . format_date($MrchKey, "Dash", false, true, false) . ':</p></div>
				<div style="width:65px; text-align:right; padding-right:5px;" class="TotalCell"><p>' . ($MrchVal['ID'] * 100) . '%</p></div>
				<div style="width:120px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>($' . number_format($MrchVal['Fee'], 2, ".", ",") . ')</p></div>
				</div>';
                    $Cntr++;
                    $merchant_terminal -= $MrchVal['Fee'];
                }
            }
            unset($Cntr);

            $due = $value['Totals']['Total'] - $temp_discprice - $temp_cost - $value['Totals']['Disc'] - $value['Totals']['Disc2'] - $profit - $credit_fee + $merchant_terminal + $temp_refund - $temp_cost_refund;
            $value['Totals']['Due'] = $due;
            $temp_comm[$key] = $due;

            echo '<div style="background-color:#CCCCCC; height:22px;">
				<div style="width:509px; text-align:right; padding-right:5px;" class="TotalCell"><p><strong>Due to Photographer&nbsp;</strong></p></div>
				<div style="width:65px; text-align:right; padding-right:5px;" class="TotalCell"><p>&nbsp;</p></div>
				<div style="width:120px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p><strong>$' . number_format($due, 2, ".", ",") . '</strong></p></div>
				</div>';
            $grand_due += ($due);
            echo '<div style="height:21px; overflow:hidden;">
				<div style="width:514px; text-align:right" class="TotalCell"><p>Shipping:&nbsp;</p></div>
				<div style="width:65px; text-align:right; padding-right:5px;" class="TotalCell"><p>&nbsp;</p></div>
				<div style="width:120px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>$' . number_format($value['Totals']['Ship'], 2, ".", ",") . '</p></div>
				</div>';
            $grand_ship += ($value['Totals']['Ship']);
            echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
            echo '<div style="height:21px; overflow:hidden;">
				<div style="width:514px; text-align:right" class="TotalCell"><p>Taxes:&nbsp;</p></div>
				<div style="width:65px; text-align:right; padding-right:5px;" class="TotalCell"><p>&nbsp;</p></div>
				<div style="width:120px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>$' . number_format($value['Totals']['Tax'], 2, ".", ",") . '</p></div>
				</div>';
            echo '</blockquote>';
            $grand_tax += ($value['Totals']['Tax']);
        }
        echo '<blockquote>';
        /*
          foreach($temp_comm as $key => $value){
          echo '<div style="width:150px; height:100px; float:left; margin-left:5px; margin-bottom:5px;"><p style="margin:0px; padding-top:5px; padding-bottom:5px; background-color:#DDDDDD"><strong>'.date("F",mktime(0,0,0,substr($key,5,2),1,substr($key,0,4))).'</strong></p><p style="margin:0px;">';
          echo 'Commision: $'.number_format($value,2,".",",").'<br />';
          echo '</div>';
          } */
        echo '<br clear="all" />';
        echo '</blockquote>';
        echo '<br clear="all" />';
        echo "</div>";
    }
} else {
    echo '<div style="background-color:#CCCCCC; height:22px; padding-top:5px;"><p><strong>There are no reports for this date range.</strong></p></div>';
}
$records = ob_get_contents();
ob_end_clean();
if (isset($path[2]) && is_int(intval($path[2]))) {
    
} else {
    echo '<div><p><strong>Totals</strong><br />';
    echo 'Sales: $' . number_format($grand_sales, 2, ".", ",") . '<br />';
    echo 'Costs: $' . number_format($grand_cost, 2, ".", ",") . '<br />';
    echo 'Number of Photographers: ' . count($Totals['Photogs']) . '<br />';
    echo 'Monthly Dues: $' . number_format($Totals['Monthly'], 2, ".", ",") . '<br />';
    echo 'Discounts: $' . number_format($grand_discounts, 2, ".", ",") . '<br />';
    echo 'Printing Discounts: $' . number_format($grand_print_discounts, 2, ".", ",") . '<br />';
    echo 'Revenue Share: $' . number_format($grand_profit, 2, ".", ",") . '<br />';
    echo 'Credit Card Fees: $' . number_format($grand_credit_card, 2, ".", ",") . '<br />';
    echo 'Photographer Dues: $' . number_format($grand_due, 2, ".", ",") . '<br />';
    echo 'Shipping: $' . number_format($grand_ship, 2, ".", ",") . '<br />';
    echo 'Taxes: $' . number_format($grand_tax, 2, ".", ",");
    echo '</p></div>';
}
echo $records;
?>
