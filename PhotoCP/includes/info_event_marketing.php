<?php
$HotMenu = "Evnt,Mrkt:query";
$Key = array_search($HotMenu, $StrArray);
?>
<script type="text/javascript">
    function checkMarketing(ID) {
    var Inpts = document.getElementsByTagName('input');
    var Checked = false;
    for (var n = 0; n < Inpts.length; n++) {
    if (Inpts[n].type == "checkbox" && Inpts[n].id == ("Event_Marketing_Codes_" + ID)) {
    if (Inpts[n].checked) {
    Checked = true;
    break;
    }
    }
    }
    document.getElementById('Event_Marketing_' + ID).checked = Checked;
    document.getElementById('Event_Marketing_' + ID).onchange();
    }

    function goCheckAll(ID1, ID2) {
    var CheckAll = false;
    if (document.getElementById(ID1).checked == true)
    CheckAll = true;
    var Boxes = document.getElementById(ID2).getElementsByTagName('input');
    var n = 1;
    while (n < Boxes.length) {
    Boxes[n].checked = CheckAll;
    n++;
    }
    }
</script>
<? if ($path[3] == "Market" || $path[1] == "Mrkt") { ?>

    <h1 id="HdrType2" class="<?php if ($path[0] == 'Busn') echo 'Busn'; ?>EvntMarket">
        <div>Event Marketing</div>
    </h1>
<?php } else { ?>
    <h1 id="HdrType2-5" class="EvntMarket">
        <div>Event Marketing</div>
    </h1>
    <div id="btnCollapse">
        <a href="#" onclick="javascript: Open_Sec('EventMarket', this);
                    return false;" onmouseover="window.status = 'Expand Event Information';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Expand Event Information">+</a>
    </div>
<?php } ?>
<div id="HdrLinks"><?php if ($path[3] != "Market" && $path[1] != "Mrkt") { ?>
        <a href="#" onclick="javascript: Save_Default_Info();
                    return false;" onmouseover="window.status = 'Save Default Settings';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Save Default Settings" class="BtnSaveDefault">Save Defaults</a>
        <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status = 'Save Event Information';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Save Event Information" class="BtnSave<?php if(implode(',',$path) == 'Evnt,Evnt' && $cont=='add') echo 'Upload'; ?>">Save</a>
       <?php } else { ?>
        <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status = 'Apply to Events Selected Below';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Apply to Events Selected Below" class="BtnSaveToEvents">Apply to Events Selected Below</a>
        <a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key !== false) ? '2' : '1'; ?>);" title="Add to Hot Menu" onmouseover="window.status = 'Add to Hot Menu';
                    return true;" onmouseout="window.status = '';
                    return true;" class="BtnHotMenu<? echo ($Key !== false) ? 'Added' : ''; ?>" id="BtnHotMenu">Add to Hot Menu</a>
       <?php } ?>
</div>
<div id="RecordTable<? echo($path[3] == "Market" || $path[1] == "Mrkt") ? '' : '-5'; ?>" class="White"> <a id="EventMarket"></a>
    <div id="Top"></div>
    <div id="Records"><span id="MrkEvntCodes">
            <?
            $getEvntMrk = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
            $getEvntMrk->mysql("SELECT * FROM `photo_evnt_mrkt` WHERE `event_mrk_use` = 'y' ORDER BY `event_mrk_order` ASC;");
            $MktIds = explode("[+]", substr($MktIds, 1, -1));
            $MktCodes = explode("[+]", substr($MktCodes, 1, -1));
            foreach ($getEvntMrk->Rows() as $r) {
                ?>
                <p>
                    <input type="checkbox" name="Event Marketing[]" id="Event_Marketing_<? echo $r['event_mrk_id']; ?>" value="<? echo $r['event_mrk_id']; ?>" class="CstmFrmElmnt" title="<? echo $r['event_mrk_name']; ?>" onmouseover="window.status = '<? echo $r['event_mrk_name']; ?>';
                    return true;" onmouseout="window.status = '';
                    return true;"<? if (in_array($r['event_mrk_id'], $MktIds)) echo ' checked="checked"'; ?> />
                    <font class="fontSpecial643"><? echo $r['event_mrk_name']; ?></font><br clear="all" />
                    <?
                    $getEvntCodes = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                    $getEvntCodes->mysql("SELECT * FROM `prod_discount_codes` WHERE `disc_use` = 'y' AND `evnt_mrk_id` = '" . $r['event_mrk_id'] . "' ORDER BY `disc_order` ASC;");
                    if ($getEvntCodes->TotalRows() > 0) {
                        ?>
                    <div style="clear:both; width:660px; margin-left:auto; margin-right:auto;">
                        <?
                        if ($getEvntCodes->TotalRows() == 1) {
                            $r2 = $getEvntCodes->Rows();
                            ?>
                            <input type="hidden" name="Event Marketing Codes[]" id="Event_Marketing_Codes" value="<? echo $r2[0]['disc_id']; ?>" />
                        <? } else if ($r['event_mrk_group'] == 'n') { ?>
                            <div style="padding-top:5px;">
                                <? foreach ($getEvntCodes->Rows() as $r2) { ?>
                                    <div style="float:left; clear:none; width:150px; margin-right:15px; margin-top:5px;">
                                        <input type="checkbox" name="Event Marketing Codes[]" id="Event_Marketing_Codes_<? echo $r['event_mrk_id']; ?>" value="<? echo $r2['disc_id']; ?>" class="CstmFrmElmnt" title="<? echo $r2['disc_name']; ?>" onmouseover="window.status = '<? echo $r2['disc_name']; ?>';
                                return true;" onmouseout="window.status = '';
                                return true;"<? if (in_array($r2['disc_id'], $MktCodes)) echo ' checked="checked"'; ?> onclick="javascript: checkMarketing('<? echo $r['event_mrk_id']; ?>');" />
                                        <font class="fontSpecial2"><? echo $r2['disc_name']; ?></font><br clear="all" />
                                    </div>
                                <? } ?>
                                <br clear="all" />
                            </div>
                        <? } else { ?>
                            <div style="padding-top:5px;">
                                <?
                                $found = false;
                                $selid = 0;
                                foreach ($getEvntCodes->Rows() as $r2) {
                                    ?>
                                    <div style="float:left; clear:none; width:150px; margin-right:15px; margin-top:5px;">
                                        <input type="radio" value="<? echo $r2['disc_id']; ?>" name="Select Marketing Codes <? echo $r['event_mrk_id']; ?>" id="Select_Marketing_Codes_<? echo $r['event_mrk_id']; ?>" class="CstmFrmElmnt" title="<? echo $r2['disc_name']; ?>"<?
                                        if (in_array($r2['disc_id'], $MktCodes)) {
                                            echo ' checked="checked"';
                                            $found = true;
                                            $selid = $r2['disc_id'];
                                        }
                                        ?> onclick="document.getElementById('Event_Marketing_Codes_<? echo $r['event_mrk_id']; ?>').value = '<? echo $r2['disc_id']; ?>';
                                document.getElementById('Event_Marketing_<? echo $r['event_mrk_id']; ?>').checked = true;
                                document.getElementById('Event_Marketing_<? echo $r['event_mrk_id']; ?>').onchange();">
                                        <font class="fontSpecial2"><? echo $r2['disc_name']; ?></font><br clear="all" />
                                    </div>
                                <? } ?>
                                <div style="float:left; clear:none; width:150px; margin-right:15px; margin-top:5px;">
                                    <input type="radio" value="0" name="Select Marketing Codes <? echo $r['event_mrk_id']; ?>" id="Select_Marketing_Codes_<? echo $r['event_mrk_id']; ?>" class="CstmFrmElmnt" title="None"<? if ($found == false) echo ' checked="checked"'; ?> onclick="document.getElementById('Event_Marketing_Codes_<? echo $r['event_mrk_id']; ?>').value = '0';
                            document.getElementById('Event_Marketing_<? echo $r['event_mrk_id']; ?>').checked = false;
                            document.getElementById('Event_Marketing_<? echo $r['event_mrk_id']; ?>').onchange();">
                                    <font class="fontSpecial2">None</font><br clear="all" />
                                </div>
                                <input type="hidden" name="Event Marketing Codes[]" id="Event_Marketing_Codes_<? echo $r['event_mrk_id']; ?>" value="<? echo $selid; ?>" />
                            </div>
                        <? } ?>
                    </div>
                <? } ?>
                </p>
            <? } ?>
            <br clear="all" />
            <br clear="all" />
        </span>
    </div>
    <div id="Bottom"></div>
</div>
