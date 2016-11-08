<style type="text/css">
    <!--
    #Information table th, #Information table h1, #Information h1 {
        font-size:14px;
        font-weight:bold;
        font-family: Arial, Helvetica, sans-serif;
        color:#000000;
        height:auto;
        width:auto;
        background:none;
        background-color:#999999;
    }
    -->
</style>
<?
$is_enabled = ($cont == "view" || $cont == "query") ? false : true;
$is_back = "query";
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <th colspan="4" id="Form_Header">
    <div id="Add">
        <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_', '<?php echo implode(",", array_slice($path, 0, count($path) - 1)); ?>', '<?php echo $is_back; ?>', '<?php echo $sort; ?>', '<?php echo $rcrd; ?>');" />
    </div>
    <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
    <p>Order Information</p>
</th>
</tr>
<tr>
    <td width="20%"><strong>Quality Control Person:</strong></td>
    <td width="30%"><?
        if (!$is_enabled) {
            echo $QName;
        } else {
            ?>
            <input type="text" name="Quality Control" id="Quality_Control" maxlength="75" value="<? echo $QName; ?>">
        <? } ?>
        &nbsp;</td>
    <td width="20%"><strong>Refund:</stron></td>
    <td width="30%"><input type="checkbox" value="true" name="refund" id="refund" <?php if ($Refund == true) echo ' checked="checked"'; ?> /> Refund invoice</td>
</tr>
<tr>
    <td><strong>Person Printing the Order: </strong></td>
    <td><?
        if (!$is_enabled) {
            echo $PName;
        } else {
            ?>
            <input type="text" name="Person Printing" id="Person_Printing" maxlength="75" value="<? echo $PName; ?>">
        <? } ?>
        &nbsp;</td>
    <td width="20%"><?php if ($Refund == true) { ?><strong>Refund Date:</strong><?php } ?></td>
    <td width="30%"><?php
        if ($Refund == true) {
            echo format_date($RefundDate, "Dash", "Standard", true, true) . " MST";
        }
        ?>&nbsp;</td>
</tr>
<tr>
    <td><strong>Person Shipping the Order: </strong></td>
    <td colspan="3"><?
        if (!$is_enabled) {
            echo $SName;
        } else {
            ?>
            <input type="text" name="Person Shipping" id="Person_Shipping" maxlength="75" value="<? echo $SName; ?>">
        <? } ?>
        &nbsp;</td>
</tr>
<tr>
    <td><strong>Order Printed On:</strong></td>
    <td colspan="3"><?
        if ($row_get_photo['invoice_printed_date'] == "0000-00-00 00:00:00") {
            echo "&nbsp;";
        } else {
            echo format_date($row_get_photo['invoice_printed_date'], "Dash", "Standard", true, true) . " MST";
        }
        ?></td>
</tr>
<tr>
    <td><strong>Order Shipped On:</strong></td>
    <td colspan="3"><?
        if ($row_get_photo['invoice_comp_date'] == "0000-00-00 00:00:00") {
            echo "&nbsp;";
        } else {
            echo format_date($row_get_photo['invoice_comp_date'], "Dash", "Standard", true, true) . " MST";
        }
        ?></td>
</tr>
</table>
<br clear="all">
<div id="Admin_Invoice">
    <div>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="left"><p><? echo $row_get_photo['cust_cname']; ?><br />
                        <? echo $row_get_photo['cust_add']; ?><br />
                        <? echo $row_get_photo['cust_city'] . " " . $row_get_photo['cust_state'] . " " . $row_get_photo['cust_zip']; ?></p></td>
                <td align="right"><p>Invoice: <? echo $row_get_photo['invoice_num']; ?><br />
                        <? echo format_date($row_get_photo['invoice_date'], "Dash", "Standard", true, true); ?> MST
                        <? if ($row_get_photo['invoice_accepted_date'] != "0000-00-00 00:00:00") echo "<br /> Accepted On: " . format_date($row_get_photo['invoice_accepted_date'], "Dash", "Standard", true, true) . " MST"; ?>
                    </p></td>
            </tr>
        </table>
    </div>
    <br clear="all" />
    <p>&nbsp;</p>
    <?
    $query_get_bill = "SELECT `cust_billing`.`cust_bill_fname`, `cust_billing`.`cust_bill_lname`, `cust_billing`.`cust_bill_add`, `cust_billing`.`cust_bill_suite_apt`, `cust_billing`.`cust_bill_city`, `cust_billing`.`cust_bill_state`, `cust_billing`.`cust_bill_zip`, `cust_customers`.`cust_phone`, `cust_customers`.`cust_email`, `cust_billing`.`cust_bill_ccshort`, `cust_billing`.`cust_bill_exp_month`, `cust_billing`.`cust_bill_year`, `billship_cc_types`.`cc_type_name` FROM `cust_billing` INNER JOIN `orders_invoice` ON `orders_invoice`.`cust_bill_id` = `cust_billing`.`cust_bill_id` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `cust_billing`.`cust_id` INNER JOIN `billship_cc_types` ON `billship_cc_types`.`cc_type_id` = `cust_billing`.`cust_bill_cc_type_id` WHERE `orders_invoice`.`invoice_id` = '$invnum' LIMIT 0,1";
    $get_bill = mysql_query($query_get_bill, $cp_connection) or die(mysql_error());
    $row_get_bill = mysql_fetch_assoc($get_bill);

    $query_get_ship = "SELECT `cust_shipping`.`cust_ship_fname`, `cust_shipping`.`cust_ship_lname`, `cust_shipping`.`cust_ship_add`, `cust_shipping`.`cust_ship_suite_apt`, `cust_shipping`.`cust_ship_city`, `cust_shipping`.`cust_ship_state`, `cust_shipping`.`cust_ship_zip` FROM `cust_shipping` INNER JOIN `orders_invoice` ON `orders_invoice`.`cust_ship_id` = `cust_shipping`.`cust_ship_id` WHERE `orders_invoice`.`invoice_id` = '$invnum' LIMIT 0,1";
    $get_ship = mysql_query($query_get_ship, $cp_connection) or die(mysql_error());
    $row_get_ship = mysql_fetch_assoc($get_ship);

    if ($speed < 0) {
        $row_ship_speeds = array();
        $row_ship_speeds['ship_comp_name'] = ($speed == -1) ? "Client to pick up" : "Photographer to pick up";
        $row_ship_speeds['ship_speed_name'] = "";
    } else {
        $query_ship_speeds = "SELECT `billship_shipping_companies`.`ship_comp_name`, `billship_shipping_speeds`.`ship_speed_name` FROM `billship_shipping_speeds` INNER JOIN `billship_shipping_companies` ON `billship_shipping_companies`.`ship_comp_id` = `billship_shipping_speeds`.`ship_comp_id` WHERE `ship_speed_id` = '$speed'";
        $ship_speeds = mysql_query($query_ship_speeds, $cp_connection) or die(mysql_error());
        $row_ship_speeds = mysql_fetch_assoc($ship_speeds);
    }
    ?>
    <div id="Information">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th width="50%"><h1>Billing Information:</h1></th>
            <th width="50%"><h1>Shipping Information:</h1></th>
            </tr>
            <tr>
                <td><p><? echo $row_get_bill['cust_bill_fname'] . " " . $row_get_bill['cust_bill_lname']; ?><br />
                        <?
                        echo $row_get_bill['cust_bill_add'];
                        if ($row_get_bill['cust_bill_suite_apt'] != "")
                            echo " " . $row_get_bill['cust_bill_suite_apt'];
                        ?><br />
                        <? echo $row_get_bill['cust_bill_city'] . " " . $row_get_bill['cust_bill_state'] . " " . $row_get_bill['cust_bill_zip']; ?><br />
                        <? echo phone_number($row_get_bill['cust_phone']); ?><br />
                        <? echo $row_get_bill['cust_email']; ?></p></td>
                <td><p><? echo $row_get_ship['cust_ship_fname'] . " " . $row_get_ship['cust_ship_lname']; ?><br />
                        <?
                        echo $row_get_ship['cust_ship_add'];
                        if ($row_get_ship['cust_ship_suite_apt'] != "")
                            echo " " . $row_get_ship['cust_ship_suite_apt'];
                        ?><br />
                        <? echo $row_get_ship['cust_ship_city'] . " " . $row_get_ship['cust_ship_state'] . " " . $row_get_ship['cust_ship_zip']; ?><br />
                        Ship via:
                        <? echo $row_ship_speeds['ship_comp_name'] . " " . $row_ship_speeds['ship_speed_name']; ?>
                    </p></td>
            </tr>
        </table>
        <br clear="all" />
        <hr size="1" />
        <p>Payment Type: Prepaid - CC <? echo $row_get_bill['cc_type_name']; ?> **** **** **** <? echo $row_get_bill['cust_bill_ccshort']; ?><br />
            Expiration: <? echo $row_get_bill['cust_bill_exp_month'] . "/" . $row_get_bill['cust_bill_year']; ?></p>
        <hr size="1" />
        <?
        $query_ship_count = "SELECT SUM(`orders_invoice_photo`.`invoice_image_asis`) AS `Total_asis`,  SUM(`orders_invoice_photo`.`invoice_image_bw`) AS `Total_bw`, SUM(`orders_invoice_photo`.`invoice_image_sepia`) AS `Total_sepia`,`orders_invoice_photo`.`invoice_image_price`, `prod_products`.`prod_name` FROM `orders_invoice_photo` INNER JOIN `orders_invoice` ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` INNER JOIN `prod_products` ON `prod_products`.`prod_id` = `orders_invoice_photo`.`invoice_image_size_id` WHERE `orders_invoice`.`invoice_id` = '$invnum' GROUP BY `orders_invoice_photo`.`invoice_image_size_id` ORDER BY `prod_products`.`prod_name` ASC";
        $ship_count = mysql_query($query_ship_count, $cp_connection) or die(mysql_error());
        $ship_name = array();
        $ship_qty = array();
        $ship_price = array();
        ?>
        <div id="Information">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th><h1>Order Summary</h1></th>
                <th> <h1>Sku</h1></th>
                <th> <h1>Total</h1></th>
                </tr>
                <? while ($row_ship_count = mysql_fetch_assoc($ship_count)) { ?>
                    <tr>
                        <td><p>Number of <? echo $row_ship_count['prod_name']; ?>Professional Print's:</p></td>
                        <td align="center"><p><? echo ($row_ship_count['Total_asis'] + $row_ship_count['Total_bw'] + $row_ship_count['Total_sepia']) . " " . $row_ship_count['prod_name']; ?></p></td>
                        <td align="center"><? echo ($row_ship_count['Total_asis'] + $row_ship_count['Total_bw'] + $row_ship_count['Total_sepia']); ?></td>
                    </tr>
                <? } ?>
            </table>
            <br clear="all" />
        </div>
        <?
        $query_items = "SELECT `orders_invoice_photo`.`invoice_image_price`, `orders_invoice_photo`.`invoice_image_asis`, `orders_invoice_photo`.`invoice_image_bw`, `orders_invoice_photo`.`invoice_image_sepia`, `prod_products`.`prod_name`, `photo_event_images`.`image_tiny` FROM `orders_invoice_photo` INNER JOIN `orders_invoice` ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` INNER JOIN `prod_products` ON `prod_products`.`prod_id` = `orders_invoice_photo`.`invoice_image_size_id` INNER JOIN `photo_event_images` ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` WHERE `orders_invoice`.`invoice_id` = '$invnum' ORDER BY `photo_event_images`.`image_tiny`, `prod_products`.`prod_name` ASC";
        $items = mysql_query($query_items, $cp_connection) or die(mysql_error());
        $item_desc = array();
        $item_asis = array();
        $item_bw = array();
        $item_sep = array();
        $item_price = array();
        ?>
        <div id="Information">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th><h1>File</h1></th>
                <th><h1>Image Description </h1></th>
                <th><h1>Sku</h1></th>
                <th><h1>As Is </h1></th>
                <th><h1>B&amp;W </h1></th>
                <th><h1>Sepia</h1></th>
                <th><h1>Price</h1></th>
                <th><h1>Total</h1></th>
                </tr>
                <?
                $k = 0;
                while ($row_items = mysql_fetch_assoc($items)) {
                    $total = 0;
                    ?>
                    <tr>
                        <td><p><? echo $row_items['image_tiny']; ?></p></td>
                        <td> [<? echo $k + 1; ?>] <? echo $row_items['prod_name']; ?></td>
                        <td align="center"><? echo $row_items['prod_name']; ?> m</td>
                        <td align="center"><? echo ($row_items['invoice_image_asis'] == 0) ? "&nbsp;" : $row_items['invoice_image_asis']; ?></td>
                        <td align="center"><? echo ($row_items['invoice_image_bw'] == 0) ? "&nbsp;" : $row_items['invoice_image_bw']; ?></td>
                        <td align="center"><? echo ($row_items['invoice_image_sepia'] == 0) ? "&nbsp;" : $row_items['invoice_image_sepia']; ?></td>
                        <td align="center"><? echo number_format($row_items['invoice_image_price'], 2, ".", ","); ?></td>
                        <td align="center"><?
                            $total = ($row_items['invoice_image_asis'] * $row_items['invoice_image_price']) + ($row_items['invoice_image_bw'] * $row_items['invoice_image_price']) + ($row_items['invoice_image_sepia'] * $row_items['invoice_image_price']);
                            echo number_format($total, 2, ".", ",");
                            ?></td>
                    </tr>
                    <?
                    $k++;
                } $z = 1;
                $query_items = "SELECT `orders_invoice_border`.*, `prod_borders`.`prod_name` AS `border_name`, `prod_products`.`cat_id`, `prod_products`.`prod_name`, `prod_products`.`prod_id`, `photo_event_images`.`image_tiny` 
	FROM `orders_invoice_border` 
	INNER JOIN `orders_invoice` 
		ON `orders_invoice`.`invoice_id` = `orders_invoice_border`.`invoice_id` 
	INNER JOIN `prod_products` 
		ON `prod_products`.`prod_id` = `orders_invoice_border`.`invoice_image_size_id` 
	INNER JOIN `photo_event_images` 
		ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id`
	INNER JOIN `prod_products`  AS `prod_borders`
		ON `prod_borders`.`prod_id` = `orders_invoice_border`.`border_id`
	WHERE `orders_invoice`.`invoice_id` = '$invnum'
	ORDER BY `photo_event_images`.`image_tiny`, `prod_products`.`prod_name` ASC";
                $items = mysql_query($query_items, $cp_connection) or die(mysql_error());
                $total_items = mysql_num_rows($items);

                while ($row_items = mysql_fetch_assoc($items)) {
                    $total = 0;
                    ?>
                    <tr>
                        <td><p><? echo $z . "_" . $row_items['border_name'] . "_" . $row_items['image_tiny']; ?></p></td>
                        <td> [<? echo $k + 1; ?>] <? echo $row_items['prod_name']; ?></td>
                        <td align="center"><? echo $row_items['prod_name']; ?> m</td>
                        <td align="center"><? echo ($row_items['invoice_image_asis'] == 0) ? "&nbsp;" : $row_items['invoice_image_asis']; ?></td>
                        <td align="center"><? echo ($row_items['invoice_image_bw'] == 0) ? "&nbsp;" : $row_items['invoice_image_bw']; ?></td>
                        <td align="center"><? echo ($row_items['invoice_image_sepia'] == 0) ? "&nbsp;" : $row_items['invoice_image_sepia']; ?></td>
                        <td align="center"><? echo number_format($row_items['invoice_image_price'], 2, ".", ","); ?></td>
                        <td align="center"><?
                            $total = ($row_items['invoice_image_asis'] * $row_items['invoice_image_price']) + ($row_items['invoice_image_bw'] * $row_items['invoice_image_price']) + ($row_items['invoice_image_sepia'] * $row_items['invoice_image_price']);
                            echo number_format($total, 2, ".", ",");
                            ?></td>
                    </tr>
                    <?
                    $k++;
                    $z++;
                }
                ?>
            </table>
            <br clear="all" />
        </div>
        <div id="Information">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th><h1>Additional Items</h1></th>
                <th><h1>Sku</h1></th>
                <th><h1>Qty</h1></th>
                <th><h1>Price</h1></th>
                <th><h1>Total</h1></th>
                </tr>
            </table>
            <br clear="all" />
        </div>
        <div id="Information">
            <h1>Total<br clear="all" />
            </h1>
        </div>
        <div style="float:right; width:300px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="left"><p>Subtotal:<br />
                            Discount:<br />
                            Shipping:<br />
                            Tax:</p>
                        <p><strong>Grand Total: </strong><br />
                            Amount Charged: </p>
                        <p><strong>Balance Due:</strong><br />
                        </p></td>
                    <td align="right"><p>$<? echo number_format($row_get_photo['invoice_total'], 2, ".", ","); ?><br />
                            $<? echo number_format($row_get_photo['invoice_disc'], 2, ".", ","); ?><br />
                            $<? echo number_format($row_get_photo['invoice_ship'], 2, ".", ","); ?><br />
                            $<? echo number_format($row_get_photo['invoice_tax'], 2, ".", ","); ?></p>
                        <p><strong>$<? echo number_format($row_get_photo['invoice_grand'], 2, ".", ","); ?><br />
                            </strong>$<? echo number_format($row_get_photo['invoice_grand'], 2, ".", ","); ?></p>
                        <p>$0.00</p></td>
                </tr>
            </table>
        </div>
    </div>
    <br clear="all">
