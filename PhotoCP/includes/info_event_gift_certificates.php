<?php
$DefaultGiftCerts = array();
$DefaultCoupons = array(638);

if (isset($Defaults['Gift_Certs'])) {
    $DefaultGiftCerts = $Defaults['Gift_Certs'];
}
if (isset($Defaults['Coupons'])) {
    $DefaultCoupons = $Defaults['Coupons'];
}
?>
<h1 id="HdrType2-5" class="EvntGift">
    <div>Gift Certificates and Coupons</div>
</h1>
<div id="btnCollapse"><a href="#" onClick="javascript: Open_Sec('EventGift', this);
        return false;" onMouseOver="window.status = 'Expand Event Order Processing Options';
        return true;" onMouseOut="window.status = '';
        return true;" title="Expand Event Order Processing Options">+</a></div>
<div id="HdrLinks">
    <?php if (implode(',', $path) == 'Evnt,Evnt') { ?>
        <a href="#" onclick="javascript: Save_Default_Info();
            return false;" onmouseover="window.status = 'Save Default Settings';
            return true;" onmouseout="window.status = '';
            return true;" title="Save Default Settings" class="BtnSaveDefault">Save Defaults</a>
       <?php } ?>
    <a href="#" onClick="javascript:<? echo $onclick; ?> return false;" onMouseOver="window.status = 'Save Event Information';
        return true;" onMouseOut="window.status = '';
        return true;" title="Save Event Information" class="BtnSave<?php if(implode(',',$path) == 'Evnt,Evnt' && $cont=='add') echo 'Upload'; ?>">Save</a>
</div>
<div><a id="EventGift"></a>
    <div id="RecordTable-5" class="White">
        <div id="Top"></div>
        <div id="Records">
            <p>
                <label class="CstmFrmElmntLabel">Active Gift Certificates</label>
            </p>
            <div class="fontSpecial5">
                <h2>Name</h2>
                <h2>E-mail</h2>
                <h2>Amount</h2>
                <h2>Amount Rem.</h2>
                <h2>Code</h2>
            </div>
            <div id="GiftCerts">
                <?
                $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                $getInfo->mysql("SELECT `prod_discount_codes`.*, SUM(`orders_invoice_codes`.`disc_total`) AS `disc_total`, `photo_event_disc`.`event_disc_id`
                    FROM `prod_discount_codes`
                    LEFT JOIN `photo_event_disc` ON (`photo_event_disc`.`disc_id` = `prod_discount_codes`.`disc_id`
                                    OR `photo_event_disc`.`disc_id` IS NULL)
                    LEFT JOIN `orders_invoice_codes`
                            ON (`orders_invoice_codes`.`disc_id` = `prod_discount_codes`.`disc_id`
                                    OR `orders_invoice_codes`.`disc_id` IS NULL)					
                    WHERE `prod_discount_codes`.`prod_id` = '0' 
                            AND `prod_discount_codes`.`cust_id` = '$CustId' 
                            AND `prod_discount_codes`.`disc_use` = 'y' 
                            AND `prod_discount_codes`.`disc_type` = 'g' 
                            AND (	`disc_total` IS NULL || `disc_total` < `prod_discount_codes`.`disc_price` )
                            AND `photo_event_disc`.`event_disc_id` IS NULL
                    GROUP BY `prod_discount_codes`.`disc_id` 
                    ORDER BY `disc_name` ASC;");
                $n = 0;
                foreach ($getInfo->Rows() as $r) {
                    ?>
                    <div class="fontSpecial5">
                        <p>
                            <input type="checkbox" name="GiftCerts[]" id="GiftCerts_<? echo $n; ?>" value="<? echo $r['disc_id']; ?>" class="CstmFrmElmnt" title="<? echo $r['disc_name']; ?>" onmouseover="window.status = '<? echo str_replace("'", "\'", $r['disc_name']); ?>';
            return true;" onmouseout="window.status = '';
            return true;" <?php if (in_array($r['disc_id'], $DefaultGiftCerts)) echo ' checked="checked"'; ?> />
                            <? echo (strlen($r['disc_name']) > 15) ? substr($r['disc_name'], 0, 15) . "..." : $r['disc_name']; ?></p>
                        <p><? echo (strlen($r['disc_email']) > 15) ? substr($r['disc_email'], 0, 15) . "..." : $r['disc_email']; ?></p>
                        <p><? echo "$" . number_format($r['disc_price'], 2, ".", ","); ?></p>
                        <p><? echo "$" . number_format($r['disc_total'], 2, ".", ","); ?></p>
                        <p><? echo $r['disc_code']; ?></p>
                    </div>
                    <?
                    $n++;
                }
                ?>
            </div>
            <br clear="all" />
        </div>
        <div id="Bottom"></div>
    </div>
    <br clear="all" />
    <div id="RecordTable-5" class="White">
        <div id="Top"></div>
        <div id="Records">
            <p>
                <label class="CstmFrmElmntLabel">Create New Gift Certificates</label>
            </p>
            <div class="fontSpecial5-2">
                <label for="Gift_Name">Name</label>
                <label for="Gift_Email">E-mail</label>
                <label for="Gift_Amount">Amount</label>
                <label for="Gift_Code">Code</label>
                <br clear="all" />
                <input type="text" name="Gift Name" id="Gift_Name" value="" onFocus="javascript: this.className = 'NavSel';" onBlur="javascript: this.className = '';" onMouseOver="window.status = 'Gift Certificate Name';
        return true;" onMouseOut="window.status = '';
        return true;" title="Gift Certificate Name" />
                <input type="text" name="Gift Email" id="Gift_Email" value="" onFocus="javascript: this.className = 'NavSel';" onBlur="javascript: this.className = '';" onMouseOver="window.status = 'Gift Certificate Email';
        return true;" onMouseOut="window.status = '';
        return true;" title="Gift Certificate Email" />
                <input type="text" name="Gift Amount" id="Gift_Amount" value="" onFocus="javascript: this.className = 'NavSel';" onBlur="javascript: this.className = '';" onMouseOver="window.status = 'Gift Certificate Amount';
        return true;" onMouseOut="window.status = '';
        return true;" title="Gift Certificate Amount" />
                <input type="text" name="Gift Code" id="Gift_Code" value="" onFocus="javascript: this.className = 'NavSel';" onBlur="javascript: this.className = '';" onMouseOver="window.status = 'Gift Certificate Code';
        return true;" onMouseOut="window.status = '';
        return true;" title="Gift Certificate Code" />
                <div id="BtnCreateGift">
                    <input type="button" name="Create Gift Certificate" id="Create_Gift_Certificate" onMouseOver="window.status = 'Create Gift Certificate';
        return true;" onMouseOut="window.status = '';
        return true;" title="Create Gift Certificate" onclick="javascript: Save_Gift_Info();" />
                </div>
            </div>
            <br clear="all" />
        </div>
        <div id="Bottom"></div>
    </div>
    <br clear="all" />
    <span id="ActiveCoupons">
        <div id="RecordTable-5" class="White">
            <div id="Top"></div>
            <div id="Records" class="Colmn4">
                <p>
                    <label class="CstmFrmElmntLabel">Active Coupons</label>
                    <br />
                    <label for="Coupons" class="CstmFrmElmntLabel2">Offer coupons to guests of event</label>
                </p>
                <br clear="all" />
                <?
                $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                $getInfo->mysql("SELECT * FROM `prod_discount_codes`  WHERE `prod_id` = '0' AND `disc_use` = 'y' AND `disc_type` = 'c' ORDER BY `disc_order` ASC;");
                foreach ($getInfo->Rows() as $r) {
                    ?>
                    <span>
                        <p>
                            <input type="radio" name="Coupons" id="Coupons" value="<? echo $r['disc_id']; ?>" class="CstmFrmElmnt" title="<? echo $r['disc_name']; ?>" onmouseover="window.status = '<? echo str_replace("'", "\'", $r['disc_name']); ?>';
            return true;" onmouseout="window.status = '';
            return true;"<?php if (in_array($r['disc_id'], $DefaultCoupons)) echo ' checked="checked"'; ?> />
                            <font class="fontSpecial2"><? echo $r['disc_name']; ?></font><br clear="all" />
                        </p>
                    </span>
                <? } ?>
                <br clear="all" />
                <br clear="all" />
            </div>
            <div id="Bottom"></div>
        </div>
    </span>
    <br clear="all" />
</div>
